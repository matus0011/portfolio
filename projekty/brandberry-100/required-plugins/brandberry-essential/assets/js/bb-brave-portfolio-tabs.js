(function () {
  /**
   * Brave Portfolio interactions:
   * - Tabs indicator + pane transitions
   * - Sync .w-tab-content height to active pane (prevents overlap)
   * - List hover image swap with fade in/out
   * - Works on front-end + Elementor editor
   */

  function raf2(cb) {
    window.requestAnimationFrame(function () {
      window.requestAnimationFrame(cb);
    });
  }

  function initTabs(widgetRoot) {
    widgetRoot.querySelectorAll('.portfolio-tabs.w-tabs').forEach(function (tabs) {
      // ✅ guard: don't init same tabs twice
      if (tabs.dataset.bbTabsInit === '1') return;
      tabs.dataset.bbTabsInit = '1';

      var menu = tabs.querySelector('.w-tab-menu');
      var links = menu ? Array.from(menu.querySelectorAll('.w-tab-link')) : [];
      var panes = Array.from(tabs.querySelectorAll('.w-tab-pane'));

      // This is the wrapper that MUST grow/shrink to prevent overlap
      // (in your markup it’s "tabs-content w-tab-content")
      var content = tabs.querySelector('.w-tab-content') || tabs.querySelector('.tabs-content');

      // If there are no panes at all, nothing to do
      if (!panes.length || !content) return;

      // ---------- shared helpers (both modes) ----------

      function syncContentHeight(activePane) {
        if (!content || !activePane) return;

        var h = activePane.scrollHeight;
        if (!h || h < 1) h = activePane.offsetHeight;
        if (!h || h < 1) h = Math.ceil(activePane.getBoundingClientRect().height);

        content.style.height = h + 'px';
      }

      function syncAfterImagesLoad(activePane) {
        if (!activePane) return;
        var imgs = Array.from(activePane.querySelectorAll('img'));
        if (!imgs.length) return;

        imgs.forEach(function (img) {
          if (img.complete) return;
          img.addEventListener(
            'load',
            function () {
              syncContentHeight(activePane);
            },
            { once: true }
          );
          img.addEventListener(
            'error',
            function () {
              syncContentHeight(activePane);
            },
            { once: true }
          );
        });
      }

      // FadeInUp helper
      function animatePaneIn(p) {
        if (!p) return;

        p.style.display = 'block';

        p.classList.remove('bb-enter', 'bb-enter-active');
        p.classList.add('bb-enter');
        p.classList.add('w--tab-active');

        void p.offsetHeight;

        window.requestAnimationFrame(function () {
          p.classList.add('bb-enter-active');
          p.classList.add('bb-pane-visible');
        });
      }

      function animatePaneOut(p) {
        if (!p) return;

        p.classList.remove('bb-pane-visible');
        p.classList.remove('bb-enter-active');
        p.classList.add('bb-enter');

        window.setTimeout(function () {
          p.classList.remove('w--tab-active');
          p.style.display = 'none';
          p.classList.remove('bb-enter');
        }, 320);
      }

      // ResizeObserver to keep wrapper height correct if content changes
      var ro = null;
      if ('ResizeObserver' in window && content) {
        ro = new ResizeObserver(function () {
          var activePane = tabs.querySelector('.w-tab-pane.w--tab-active') || panes[0];
          if (activePane) syncContentHeight(activePane);
        });
        panes.forEach(function (p) {
          ro.observe(p);
        });
      }

      // ---------- CASE 2: NO MENU (GRID-ONLY MODE) ----------
      // If there is no menu/links, we still want height sync for the single grid.
      if (!menu || !links.length) {
        var activePaneGrid = tabs.querySelector('.w-tab-pane.w--tab-active') || panes[0];

        panes.forEach(function (p) {
          var isActive = p === activePaneGrid;
          p.style.display = isActive ? 'block' : 'none';
          p.classList.toggle('w--tab-active', isActive);
        });

        if (activePaneGrid) {
          raf2(function () {
            syncContentHeight(activePaneGrid);
            syncAfterImagesLoad(activePaneGrid);
          });

          // Extra safety on mobile (late font/layout changes)
          setTimeout(function () {
            syncContentHeight(activePaneGrid);
          }, 80);
          setTimeout(function () {
            syncContentHeight(activePaneGrid);
          }, 300);
        }

        // Nothing else to do (no tabs to click)
        return;
      }

      // ---------- CASE 1: we DO have a menu (normal tabs) ----------

      // Single sliding indicator (matches the Brave HTML demo).
      var indicator = menu.querySelector('.bb-tab-indicator');
      if (!indicator) {
        indicator = document.createElement('div');
        indicator.className = 'bb-tab-indicator';

        // Reuse existing background class for styling.
        var bg = document.createElement('div');
        bg.className = 'portfolio-tab-active-background';
        indicator.appendChild(bg);

        menu.insertBefore(indicator, menu.firstChild);
      }

      // Ensure each tab has the active pill + text wrapper (defensive).
      links.forEach(function (a) {
        if (!a.querySelector('.portfolio-tab-active')) {
          var pill = document.createElement('div');
          pill.className = 'portfolio-tab-active';
          var bg2 = document.createElement('div');
          bg2.className = 'portfolio-tab-active-background';
          pill.appendChild(bg2);
          a.insertBefore(pill, a.firstChild);
        }
        if (!a.querySelector('.portfolio-tab-text')) {
          var text = document.createElement('div');
          text.className = 'portfolio-tab-text';
          text.textContent = a.textContent.trim();
          a.textContent = '';
          a.appendChild(text);
        }
      });

      function positionIndicator(activeLink) {
        if (!indicator || !activeLink) return;
        var menuRect = menu.getBoundingClientRect();
        var linkRect = activeLink.getBoundingClientRect();
        var left = linkRect.left - menuRect.left;
        indicator.style.width = linkRect.width + 'px';
        indicator.style.transform = 'translate3d(' + left + 'px, 0, 0)';
      }

      function activate(tabName) {
        var activeLink = null;

        links.forEach(function (a) {
          var isActive = a.getAttribute('data-w-tab') === tabName;
          a.classList.toggle('w--current', isActive);
          if (isActive) activeLink = a;
        });

        // Move indicator
        positionIndicator(activeLink);

        var incomingPane = null;
        var outgoingPanes = [];

        panes.forEach(function (p) {
          if (p.getAttribute('data-w-tab') === tabName) {
            incomingPane = p;
          } else {
            outgoingPanes.push(p);
          }
        });

        // Animate IN immediately
        if (incomingPane) {
          animatePaneIn(incomingPane);
        }

        // Animate OUT in parallel
        outgoingPanes.forEach(function (p) {
          animatePaneOut(p);
        });

        // After layout settles, sync wrapper height so nothing overlaps
        if (incomingPane) {
          raf2(function () {
            syncContentHeight(incomingPane);
            syncAfterImagesLoad(incomingPane);
          });

          // Extra safety on mobile (late font/layout changes)
          setTimeout(function () {
            syncContentHeight(incomingPane);
          }, 80);
          setTimeout(function () {
            syncContentHeight(incomingPane);
          }, 300);
        }
      }

      // Hook clicks
      links.forEach(function (a) {
        a.addEventListener('click', function (e) {
          e.preventDefault();
          var name = a.getAttribute('data-w-tab');
          if (name) activate(name);
        });
      });

      // Initial state
      var current = tabs.getAttribute('data-current');
      if (!current) {
        var currentLink = tabs.querySelector('.w-tab-link.w--current');
        current = currentLink ? currentLink.getAttribute('data-w-tab') : null;
      }
      if (!current && links[0]) current = links[0].getAttribute('data-w-tab');

      // Activate initial tab
      if (current) activate(current);

      function realign() {
        var activeLink = tabs.querySelector('.w-tab-link.w--current') || links[0];
        var activePane = tabs.querySelector('.w-tab-pane.w--tab-active') || panes[0];

        if (activeLink) positionIndicator(activeLink);
        if (activePane) {
          raf2(function () {
            syncContentHeight(activePane);
          });
        }
      }

      window.addEventListener('resize', realign);
      window.addEventListener('orientationchange', realign);

      // Font swap can change height/width
      if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(realign);
      }
    });
  }

  function initListHover(widgetRoot) {
    widgetRoot.querySelectorAll('.bb-portfolio--brave').forEach(function (wrapper) {
      // ✅ guard: don't init same wrapper twice
      if (wrapper.dataset.bbListInit === '1') return;
      wrapper.dataset.bbListInit = '1';

      var list = wrapper.querySelector('.projects-list');
      var main = wrapper.querySelector('.bb-brave-list-main');
      if (!list || !main) return;

      main.classList.add('bb-list-main-ready');
      var defaultHTML = main.innerHTML;

      function swapToImg(imgEl) {
        if (!imgEl) return;

        var next = imgEl.cloneNode(true);
        next.classList.remove('opacity-zero');
        next.classList.add('bb-list-main-img');
        next.style.opacity = '0';
        next.style.position = 'absolute';
        next.style.inset = '0';
        next.style.width = '100%';
        next.style.height = '100%';
        next.style.objectFit = 'cover';

        var curr = main.querySelector('img');
        if (curr) {
          curr.classList.add('bb-list-main-img');
          curr.style.position = 'absolute';
          curr.style.inset = '0';
          curr.style.width = '100%';
          curr.style.height = '100%';
          curr.style.objectFit = 'cover';
        }

        main.style.position = 'relative';
        main.appendChild(next);

        raf2(function () {
          next.style.opacity = '1';
          if (curr) curr.style.opacity = '0';
          window.setTimeout(function () {
            if (curr && curr.parentNode) curr.parentNode.removeChild(curr);
          }, 260);
        });
      }

      function restoreDefault() {
        var tmp = document.createElement('div');
        tmp.innerHTML = defaultHTML;
        var img = tmp.querySelector('img');
        if (!img) {
          main.innerHTML = defaultHTML;
          return;
        }
        swapToImg(img);
      }

      list.querySelectorAll('.projects-list-item').forEach(function (item) {
        var link = item.querySelector('.projects-list-link');
        var hiddenImg = item.querySelector('.bb-brave-item-image img');
        if (!link || !hiddenImg) return;

        link.addEventListener('mouseenter', function () {
          swapToImg(hiddenImg);
        });
        link.addEventListener('focusin', function () {
          swapToImg(hiddenImg);
        });
        link.addEventListener('mouseleave', restoreDefault);
        link.addEventListener('focusout', restoreDefault);
      });
    });
  }

  function init(root) {
    initTabs(root);
    initListHover(root);
  }

  // Front-end
  document.addEventListener('DOMContentLoaded', function () {
    init(document);
  });

  // Elementor editor support
  if (window.jQuery) {
    window.jQuery(window).on('elementor/frontend/init', function () {
      if (!window.elementorFrontend || !window.elementorFrontend.hooks) return;

      var initHandler = function ($scope) {
        var el = $scope[0];
        if (!el) return;

        // Only run if this widget actually contains our portfolio markup
        if (!el.querySelector('.bb-portfolio--brave')) return;

        init(el);
      };

      // Run for every widget; guards inside init* prevent duplicates
      elementorFrontend.hooks.addAction(
        'frontend/element_ready/global',
        initHandler
      );
    });
  }
})();
