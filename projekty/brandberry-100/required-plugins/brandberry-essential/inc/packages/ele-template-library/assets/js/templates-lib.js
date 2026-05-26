/* eslint-disable semi */
/* eslint-disable arrow-parens */
/**
 * WCF Template Library Editor Core
 * Fixed version: paging + lazy-load + insert/preview state
 */

/* global jQuery, WCF_Template_library_Editor, WCF_TEMPLATE_LIBRARY, wp, elementor, elementorCommon, $e */

(function ($, window, document) {
  let storeCategory;
  let currentPage = 1;
  let currentCategory = "";
  let currentType = "";
  let currentColorType = "";
  let active_menu_first_load = 0;
  let active_resize_first_load = 0;

  if (typeof WCF_TEMPLATE_LIBRARY === "undefined") {
    return;
  }

  // ---- GLOBAL LAZY LOAD STATE (prevents multiple observers) ----
  window.wcfTplLazy = window.wcfTplLazy || {
    observer: null,
    loading: false,
  };

  let allCategory = async () => {
    await fetch(
      "https://member.treethemes.com/wp-json/templates/v2/wcf-tpl-category"
    )
      .then((res) => res.json())
      .then((res) => {
        storeCategory = res;
      });
  };

  allCategory();

  let aae_domain =
    "https://member.treethemes.com/wp-json/wp/v2/wcf-templates?page=1&per_page=20&subtype=block&theme=brandberry";

  const activePlugin = async () => {
    await fetch(WCF_TEMPLATE_LIBRARY.ajaxurl, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
        Accept: "application/json",
      },

      body: new URLSearchParams({
        action: "activate_from_editor_plugin",
        action_base:
          "animation-addons-for-elementor-pro/animation-addons-for-elementor-pro.php",
        nonce: WCF_TEMPLATE_LIBRARY.nonce,
      }),
    })
      .then((response) => response.json())
      .then((return_content) => {
        if (return_content?.success) {
          window.location.reload();
        }
      });
  };

  const templates_validate = function (remotetemplates) {
    let templates = [];

    remotetemplates.forEach((template) => {
      template["valid"] = "yes";
      templates.push(template);
    });

    return templates.reverse();
  };

  // ---- GET CATEGORY TEMPLATES ----
  // ✅ CRITICAL FIX: restore page param so it doesn't always fetch page=1
  const get_category_templates = async function (
    category = "",
    type,
    page = 1,
    color_type = ""
  ) {
    let result = [];
    let query_domain = new URL(aae_domain);

    if (type) query_domain.searchParams.set("subtype", type);
    if (category && category !== "") query_domain.searchParams.set("cat", category);

    // ✅ THIS WAS COMMENTED OUT IN YOUR FILE -> causes endless duplicates
    if (page) query_domain.searchParams.set("page", page);

    if (color_type) query_domain.searchParams.set("color_type", color_type);

    try {
      const response = await fetch(query_domain);
      if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
      const data = await response.json();
      result = data.templates || [];
    } catch (error) {
      console.error("Fetch Error:", error);
    }

    return templates_validate(result);
  };

  const search_category_templates = async function (text = "") {
    let type =
      $("#elementor-template-library-header-menu .elementor-active").attr(
        "data-tab"
      ) || "block";

    let result = [];
    let query_domain = new URL(aae_domain);

    if (type) query_domain.searchParams.set("subtype", type);
    if (text && text !== "") query_domain.searchParams.set("s", text);

    const cat = $("wcf-template-library-filter-subtype").val();
    if (cat && cat !== "") query_domain.searchParams.set("cat", cat);

    try {
      const response = await fetch(query_domain);
      if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
      const data = await response.json();
      result = data.templates || [];
    } catch (error) {
      console.error("Fetch Error:", error);
    }

    return templates_validate(result);
  };

  $("document").ready(function () {
    let templateAddSection = $("#tmpl-elementor-add-section");

    if (0 < templateAddSection.length) {
      var oldTemplateButton = templateAddSection.html();
      oldTemplateButton = oldTemplateButton.replace(
        '<div class="elementor-add-section-drag-title',
        '<div class="elementor-add-section-area-button elementor-add-vendy-template-button vendy"></div><div class="elementor-add-section-drag-title'
      );
      templateAddSection.html(oldTemplateButton);
    }

    elementor.on("preview:loaded", function () {
      $(elementor.$previewContents[0].body).on(
        "click",
        ".elementor-add-vendy-template-button",
        function (event) {
          event.preventDefault();

          window.wcftmLibrary = elementorCommon.dialogsManager.createWidget(
            "lightbox",
            {
              id: "wcf-template-library",
              onShow: function () {
                this.getElements("widget").addClass("elementor-templates-modal");
                this.getElements("header").remove();
                this.getElements("message").remove();
                this.getElements("buttonsWrapper").remove();
                let t = this.getElements("widgetContent");
                render_popup(t);
              },
              onHide: function () {
                window.wcftmLibrary.destroy();
              },
            }
          );

          window.wcftmLibrary.getElements("header").remove();
          window.wcftmLibrary.show();
          $(window).trigger("resize");

          function render_popup(t) {
            let tmpTypes = wp.template("wcf-templates-header");
            let content = tmpTypes({
              template_types: WCF_TEMPLATE_LIBRARY.template_types,
            });

            t.html(content);

            active_menu(t);
            selected_category(t);
            selected_color_type(t);
            render_single_template(t);
            search_function();
            template_import(null); // normal mode
          }

          async function render_templates(
            t,
            activeMenu,
            category = "",
            color_type = ""
          ) {
            // ✅ reset paging whenever you re-render (new tab/category/color)
            currentPage = 1;

            let templates = wp.template("wcf-templates");
            let is_loading = true;
            loading(is_loading);

            let contents = await templates({
              templates: [],
              categories: storeCategory,
            });

            t.append(contents);

            const container = document.querySelector(".wcf-library-templates");
            currentCategory = category;
            currentType = activeMenu;
            currentColorType = color_type;

            if (active_resize_first_load === 0) {
              $(window).trigger("resize");
              active_resize_first_load++;
            }

            const firstChunk = await get_category_templates(
              category,
              activeMenu,
              1,
              color_type
            );

            if (container) container.innerHTML = "";

            firstChunk.forEach((item) => {
              const templateHtml = generateTemplate(item);
              if (container) container.innerHTML += templateHtml;
            });

            // ✅ lazy load starts here
            aaeadddon_run_lazy_load();

            $($(".wcf-library-template").last())
              .find("img")
              .on("load", function () {
                is_loading = false;
                loading(is_loading);
                $(window).trigger("resize");
              });

            if (category) {
              $(
                "#wcf-template-library-filter-subtype option[value='" +
                  category +
                  "']"
              ).attr("selected", "selected");
            }

            if (color_type) {
              $(
                "#wcf-template-library-color-subtype option[value='" +
                  color_type +
                  "']"
              ).attr("selected", "selected");
            }
          }

          // ✅ preview click + back resets insert mode
          function render_single_template(t) {
            const backContent = $("#wcf-template-library .dialog-widget-content").html();

            $(document).off("click.wcfTpl", ".thumbnail");
            $(document).on("click.wcfTpl", ".thumbnail", function () {
              let _that = $(this);
              const template_id = _that.closest(".wcf-library-template").data("id");
              const template_url = _that.closest(".wcf-library-template").data("url");

              let singleTmp = wp.template("wcf-templates-single");
              let content_single = singleTmp({ template_link: template_url });

              t.html(content_single);

              let is_loading = true;
              loading(is_loading);

              $("#wcf-template-library iframe").on("load", function () {
                is_loading = false;
                loading(is_loading);
              });

              // preview mode: lock insert to this id
              template_import(template_id);
            });

            $(document).off("click.wcfTpl", "#wcf-template-library-header-preview-back");
            $(document).on("click.wcfTpl", "#wcf-template-library-header-preview-back", function () {
              $("#wcf-template-library .dialog-widget-content").html(backContent);
              loading(false);
              active_menu(t);

              // ✅ back to normal mode: insert clicked item
              template_import(null);
            });
          }

          function active_menu(t) {
            active_menu_first_load++;
            const menu_item = $(
              ".wcf-template-library--header .elementor-template-library-menu-item"
            );

            menu_item.click(function () {
              if ($(this).hasClass("elementor-active")) return;

              menu_item.removeClass("elementor-active");
              $(this).addClass("elementor-active");

              const activeMenu = $(this).attr("data-tab");
              $(t).find(".dialog-message").remove();

              render_templates(t, activeMenu);
            });

            $(".elementor-templates-modal__header__close").on("click", function () {
              window.wcftmLibrary.hide();
            });

            let activeMenu = $(".wcf-template-library--header .elementor-active").attr("data-tab");
            render_templates(t, activeMenu);
          }

          function selected_category(t) {
            $(document).off("change.wcfTpl", "#wcf-template-library-filter-subtype");
            $(document).on("change.wcfTpl", "#wcf-template-library-filter-subtype", function () {
              let activeMenu = $(".wcf-template-library--header .elementor-active").attr("data-tab");
              let valueSelected = this.value;

              $(t).find(".dialog-message").remove();
              render_templates(t, activeMenu, valueSelected, currentColorType);

              template_import(null);
            });
          }

          function selected_color_type(t) {
            $(document).off("change.wcfTpl", "#wcf-template-library-color-subtype");
            $(document).on("change.wcfTpl", "#wcf-template-library-color-subtype", function () {
              let activeMenu = $(".wcf-template-library--header .elementor-active").attr("data-tab");
              let valueSelected = this.value;

              $(t).find(".dialog-message").remove();
              render_templates(t, activeMenu, currentCategory, valueSelected);

              template_import(null);
            });
          }

          function search_function() {
            function debounce(func, delay) {
              let timeout;
              return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
              };
            }

            $(document).off("keyup.wcfTpl", "#wcf-template-library-filter-text");
            $(document).on(
              "keyup.wcfTpl",
              "#wcf-template-library-filter-text",
              debounce(async function () {
                const filter = this.value.toLowerCase();
                const container = document.querySelector(".wcf-library-templates");

                const currentchunk = await search_category_templates(filter);
                if (container) container.innerHTML = "";

                currentchunk.forEach((item) => {
                  const templateHtml = generateTemplate(item);
                  if (container) container.innerHTML += templateHtml;
                });

                setTimeout(() => {
                  const elements = $(".wcf-library-template");
                  const re = new RegExp(filter, "i");

                  elements.each((_, element) => {
                    const title = $(element).find(".title")[0];
                    if (title && re.test(title.textContent)) {
                      title.innerHTML = title.textContent.replace(re, "<b>$&</b>");
                    }
                  });
                }, 100);
              }, 300)
            );
          }

          // ✅ insert handler: no duplicates, no stuck state (namespaced)
          function template_import(id = null) {
            let is_loading = true;

            $(document).off("click.wcfTpl", ".library--action.insert");
            $(document).on("click.wcfTpl", ".library--action.insert", function () {
              let _that = $(this);
              let template_id = id;

              if (template_id === null) {
                template_id = $(this).closest(".wcf-library-template").data("id");
              }

              loading(is_loading);
              _that.hide();

              window.wcftmLibrary.currentRequest = elementorCommon.ajax
                .addRequest("get_brandberry_theme_template_data", {
                  unique_id: template_id,
                  data: {
                    edit_mode: !0,
                    display: !0,
                    template_id: template_id,
                  },
                  success: function (e) {
                    $e.run("document/elements/import", {
                      model: window.elementor.elementsModel,
                      data: e,
                    });
                    is_loading = false;
                    window.wcftmLibrary.hide();
                  },
                })
                .fail(function () {});
            });
          }

          function loading(is_loading) {
            let loadingEl = $(".wcf-template-library--loading");
            if (!is_loading) {
              loadingEl.hide();
              loadingEl.attr("hidden");
            } else {
              loadingEl.show();
              loadingEl.removeAttr("hidden");
            }
          }
        }
      );
    });
  });

  $(document).on("click", ".aaeplugin-activate", function (e) {
    e.preventDefault();
    var userConfirmed = confirm(
      "Are you sure you want to activate plugin? Any unsaved changes will be lost. Please Save change."
    );
    if (userConfirmed) activePlugin();
  });

  // ---- LAZY LOAD: disconnect previous observer + increment understood page ----
  function aaeadddon_run_lazy_load() {
    const state = window.wcfTplLazy;

    // stop any previous observer to avoid duplicates
    if (state.observer) {
      try {
        state.observer.disconnect();
      } catch (e) {}
      state.observer = null;
    }

    const listItems = document.querySelectorAll(".aaeaadon-loadmore-footer");
    if (!listItems || !listItems.length) return;

    const lastItem = listItems[listItems.length - 1];

    const observerOptions = {
      root: null,
      rootMargin: "0px",
      threshold: 0.1,
    };

    const observerCallback = async (entries, observer) => {
      for (const entry of entries) {
        if (!entry.isIntersecting) continue;
        if (state.loading) return;

        state.loading = true;
        observer.unobserve(entry.target);

        const nextPage = (typeof currentPage === "number" ? currentPage : 1) + 1;

        const currentchunk = await get_category_templates(
          currentCategory,
          currentType,
          nextPage,
          currentColorType
        );

        if (!currentchunk || !currentchunk.length) {
          state.loading = false;
          try {
            observer.disconnect();
          } catch (e) {}
          state.observer = null;
          return;
        }

        const container = document.querySelector(".wcf-library-templates");
        currentchunk.forEach((item) => {
          container.insertAdjacentHTML("beforeend", generateTemplate(item));
        });

        // ✅ update page
        currentPage = nextPage;

        state.loading = false;

        // reattach to new footer sentinel
        aaeadddon_run_lazy_load();
      }
    };

    state.observer = new IntersectionObserver(
      debounceAsync(observerCallback),
      observerOptions
    );

    state.observer.observe(lastItem);
  }

  // ---- TEMPLATE HTML ----
  const generateTemplate = (item) => {
    return `
      <div class="wcf-library-template" data-id="${item.id}" data-url="${item.template_demo_url}">
        <div class="thumbnail">
          <img src="${item?.preview?.url}" alt="${item.title}">
        </div>

        ${
          item?.valid && item.valid
            ? `
          <button class="library--action insert">
            <i class="eicon-file-download"></i>
            Insert
          </button>
        `
            : `
          ${
            !WCF_TEMPLATE_LIBRARY?.pro_installed
              ? `
            <a href="https://animation-addons.com" class="library--action pro" target="_blank">
              <i class="eicon-external-link-square"></i>
              Go Premium
            </a>
          `
              : ""
          }

          ${
            WCF_TEMPLATE_LIBRARY?.pro_installed &&
            WCF_TEMPLATE_LIBRARY?.pro_active &&
            !WCF_TEMPLATE_LIBRARY?.config?.wcf_valid
              ? `
            <a href="${WCF_TEMPLATE_LIBRARY.dashboard_link}" class="library--action pro" target="_blank">
              <i class="eicon-external-link-square"></i>
              Activate License
            </a>
          `
              : ""
          }

          ${
            WCF_TEMPLATE_LIBRARY?.pro_installed && !WCF_TEMPLATE_LIBRARY?.pro_active
              ? `
            <button class="library--action pro aaeplugin-activate">
              <i class="eicon-external-link-square"></i>
              Activate
            </button>
          `
              : ""
          }
        `
        }

        <p class="title">${item.title}</p>
      </div>
    `;
  };

  // debounce helper for async callbacks
  function debounceAsync(fn, delay = 300) {
    let timeoutId = null;

    return (...args) => {
      return new Promise((resolve, reject) => {
        if (timeoutId) clearTimeout(timeoutId);

        timeoutId = setTimeout(async () => {
          try {
            const result = await fn(...args);
            resolve(result);
          } catch (err) {
            reject(err);
          }
        }, delay);
      });
    };
  }
})(jQuery, window, document);
