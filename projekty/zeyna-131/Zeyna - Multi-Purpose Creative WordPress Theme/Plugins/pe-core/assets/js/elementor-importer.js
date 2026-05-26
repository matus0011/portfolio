(function ($) {
    "use strict";

    let peImporterInitialized = false;

    const PE_LIBRARY_JSON_URL = 'https://themes.pethemes.com/zeyna/template-library/manifest.json';

    function regenerateIds(model) {
        model.id = elementorCommon.helpers.getUniqueId();

        if (model.elements && model.elements.length) {
            model.elements.forEach(child => regenerateIds(child));
        }
    }

    const PeElementorImporter = {

        init() {
            const self = this;

            this.fetched = false;
            self.data = null;
            this.type = 'block';
            this.filtered = null;
            this.demo = null;
            this.category = null;
            this.page = 1;
            this.view = 15;
            this.hasMore = false;
            self.loading = false;
            this.length = null;
            this.libraryLoaded = false;
            this.insertIndex = null;

            this.dialog = null;
            self.masonry = null;

            var templateAddWrap = $("#tmpl-elementor-add-section");
            if (templateAddWrap.length > 0) {
                var inner = templateAddWrap.text();
                inner = inner.replace(
                    '<div class="elementor-add-section-drag-title',
                    '<div id="pe-add-template-btn" class="elementor-add-section-area-button elementor-pe-library-button" title="Pe Core Library"><i class="eicon-folder"></i></div><div class="elementor-add-section-drag-title'
                );
                templateAddWrap.text(inner);
            }

        },

        /* -------------------------
         POPUP
        ------------------------- */
        openPeTemplatePopup() {

            if (this.dialog) {
                this.dialog.show();
                return;
            }

            this.dialog = elementor.dialogsManager.createWidget('lightbox', {
                id: 'pe-template-library',
                headerMessage: false,
                addElement: '<div class="pe-popup-disable"></div>',
                message: '<div class="pe-popup-loading"><i class="eicon-loading"></i><span>LIBRARY LOADING...</span></div>',
                position: {
                    my: 'center',
                    at: 'center'
                },
                onShow: () => {
                    if (this.libraryLoaded) {
                        return;
                    }

                    $.post(ajaxurl, {
                        action: 'pe_elementor_template_popup'
                    }).done((response) => {

                        this.dialog.setMessage(response.data.message);
                        this.dialog.setHeaderMessage(response.data.header);

                        this.fetchRemoteLibrary().then(json => {
                            this.renderBlocks(json, 'block');
                            this.bindInfiniteScroll();
                            this.initMasonry();
                            this.libraryLoaded = true;
                        });

                    });
                },
                onHide() {
                    // İstersen burada state resetleyebilirsin
                    // this.libraryLoaded = false;
                }
            });

            this.dialog.show();
        },

        /* -------------------------
         REMOTE JSON FETCH
        ------------------------- */
        fetchRemoteLibrary() {

            if (this.fetched) {
                return Promise.resolve(self.data);
            }

            return fetch(PE_LIBRARY_JSON_URL)
                .then(res => res.json())
                .then(json => {
                    self.data = json;
                    this.fetched = true;
                    return json;
                });
        },

        /* -------------------------
         RENDER BLOCKS (NO FILTER)
        ------------------------- */
        renderBlocks(json, type = 'block') {

            const container = $('.pe--elementor--templates--container');
            container.empty();

            if (!json.items || !json.items.length) {
                container.html('<p>No templates found.</p>');
                return;
            }

            this.type = type;

            const demos = json.demos;
            const categories = json.categories;

            let filters = type === 'block' ? categories : demos;

            const filtersUl = $('.ptl--filters ul');
            filtersUl.empty();

            const allCount = json.items.filter(item =>
                item.type === type
            ).length;

            filtersUl.append(`<li data-fetch="all" data-type="${type}">All <span class="cat-count">(${allCount})</span></li>`);

            filters.forEach(el => {

                const count = json.items.filter(item =>
                    item.type === type && (item.category === el.slug || item.demo === el.slug)
                ).length;

                filtersUl.append(`
                    <li data-type="${type}" data-fetch="${el.slug}">
                        ${el.name}
                        <span class="cat-count">(${count})</span>
                    </li>
                `);
            });

            const loadedItems = json.items.filter((item) =>
                item.type === type
            );

            this.length = loadedItems.length;

            loadedItems.forEach((item, i) => {

                if (i >= this.view) {
                    this.hasMore = true;
                    return;
                }

                const demoObj = json.demos.find(demo => demo.slug === item.demo);
                const demoName = demoObj ? demoObj.name : '';

                container.append(`
                    <div class="ptl--template ptl--block ptl--${item.category} elementor-template-library-template elementor-template-library-template-remote elementor-template-library-template-block"
                         data-json="${item.import}">

                        <div class="elementor-template-library-template-body">
                            <img src="${item.preview}" loading="lazy" />
            
                            <div class="ptl--actions elementor-template-library-template-preview">
                            <a class="elementor-template-library-template-action pe--template--insert elementor-template-library-template-insert elementor-button e-primary">
                            <i class="eicon-library-download" aria-hidden="true"></i>
                            <span class="elementor-button-title">Insert</span>
                        </a>
                                <a class="ptl--button ptl--preview" href="${item.preview_url}" target="_blank">Preview   <i class="eicon-arrow-right" aria-hidden="true"></i> </a>
                    </div>

                        </div>
            
                        <div class="ptl--template-footer">
                            <div class="ptl--template-title">
                                ${item.title}
                                ${demoName ? `<span class="ptl--template-demo">${demoName}</span>` : ''}
                            </div>
                        </div>
            
                    </div>
                `);

            });

        },
        bindInfiniteScroll() {

            const container = $('.ptl--content');
            let lastScrollTop = 0;

            container.off('scroll.peInfinite');
            container.on('scroll.peInfinite', () => {

                if (self.loading || !this.hasMore) {
                    return;
                }

                const scrollTop = container.scrollTop();
                const innerHeight = container.innerHeight();
                const scrollHeight = container[0].scrollHeight;

                if (
                    scrollTop + innerHeight >= scrollHeight - 200 &&
                    scrollTop > lastScrollTop
                ) {
                    this.page++;
                    self.loading = true;
                    this.appendItems();
                }

                lastScrollTop = scrollTop;
            });
        },
        appendItems() {

            const container = $('.pe--elementor--templates--container');
            var containerDom = document.querySelector('.pe--elementor--templates--container');

            let json = self.data;

            const loadedItems = json.items.filter(item => {

                if (!this.filtered) {
                    return item.type === this.type;
                }

                return (
                    item.type === this.type &&
                    (this.category === null || item.category === this.category) &&
                    (this.demo === null || item.demo === this.demo)
                );

            });

            const start = (this.page - 1) * this.view;
            const end = start + this.view;

            const pagedItems = loadedItems.slice(start, end);

            pagedItems.forEach(item => {

                const demoObj = json.demos.find(demo => demo.slug === item.demo);
                const demoName = demoObj ? demoObj.name : '';

                const template = document.createElement('div');

                template.className =
                    'ptl--template ptl--block ptl--' + item.category + ' elementor-template-library-template elementor-template-library-template-remote elementor-template-library-template-block';


                template.dataset.json = item.import;

                template.style.display = 'none';

                template.innerHTML = `
                <div class="elementor-template-library-template-body">
                <img src="${item.preview}" loading="lazy" />

                <div class="ptl--actions elementor-template-library-template-preview">
                <a class="elementor-template-library-template-action pe--template--insert elementor-template-library-template-insert elementor-button e-primary">
                <i class="eicon-library-download" aria-hidden="true"></i>
                <span class="elementor-button-title">Insert</span>
            </a>
                    <a class="ptl--button ptl--preview" href="${item.preview_url}" target="_blank">Preview   <i class="eicon-arrow-right" aria-hidden="true"></i> </a>
        </div>

            </div>

            <div class="ptl--template-footer">
                <div class="ptl--template-title">
                    ${item.title}
                    ${demoName ? `<span class="ptl--template-demo">${demoName}</span>` : ''}
                </div>
            </div>
                `;

                containerDom.appendChild(template);

                let contMasonry = Masonry.data(containerDom);
                contMasonry.appended(template);

                imagesLoaded(containerDom, () => {
                    template.style.display = 'block';
                    contMasonry.layout();
                });

            });

            if (container.find('.ptl--template').length >= this.length) {
                this.hasMore = false;

            }

        },
        filterBlocks(json, type, fetch) {

            const container = $('.pe--elementor--templates--container');
            container.empty();

            if (!json.items || !json.items.length) {
                container.html('<p>No templates found.</p>');
                return;
            }

            // 🔥 state reset (çok önemli)
            this.page = 1;
            this.hasMore = false;

            this.filtered = fetch !== 'all' ? true : null;
            this.category = type === 'page' ? null : fetch;
            this.demo = type === 'block' ? null : fetch;

            console.log(this.category);

            // filtrelenmiş listeyi üret
            const filteredItems = json.items.filter(item => {

                if (item.type !== type) return false;

                if (fetch !== 'all') {
                    if (type === 'block' && item.category !== fetch) return false;
                    if (type === 'page' && item.demo !== fetch) return false;
                }

                return true;
            });

            this.length = filteredItems.length;

            filteredItems.forEach((item, i) => {

                if (i >= this.view) {
                    this.hasMore = true;
                    return;
                }

                const demoObj = json.demos.find(demo => demo.slug === item.demo);
                const demoName = demoObj ? demoObj.name : '';



                container.append(`
                <div class="ptl--template ptl--block ptl--${item.category} elementor-template-library-template elementor-template-library-template-remote elementor-template-library-template-block"
                     data-json="${item.import}">

                    <div class="elementor-template-library-template-body">
                        <img src="${item.preview}" loading="lazy" />
        
                        <div class="ptl--actions elementor-template-library-template-preview">
                        <a class="elementor-template-library-template-action pe--template--insert elementor-template-library-template-insert elementor-button e-primary">
                        <i class="eicon-library-download" aria-hidden="true"></i>
                        <span class="elementor-button-title">Insert</span>
                    </a>
                            <a class="ptl--button ptl--preview" href="${item.preview_url}" target="_blank">Preview   <i class="eicon-arrow-right" aria-hidden="true"></i> </a>
                </div>

                    </div>
        
                    <div class="ptl--template-footer">
                        <div class="ptl--template-title">
                            ${item.title}
                            ${demoName ? `<span class="ptl--template-demo">${demoName}</span>` : ''}
                        </div>
                    </div>
        
                </div>
            `);
            });
        },
        /* -------------------------
         PHP IMPORT
        ------------------------- */
        importTemplate(path) {
            return $.post(ajaxurl, {
                action: 'pe_import_elementor_template',
                post_id: elementor.getPreviewContainer().document.id,
                json_path: path,
            });
        },
        /* -------------------------
         INSERT TO ELEMENTOR
        ------------------------- */
        insertTemplate(content) {

            if (typeof elementor === 'undefined' || typeof $e === 'undefined') {
                console.error('Elementor editor not ready');
                return;
            }

            let index = this.insertIndex !== null
                ? this.insertIndex
                : -1;

            const historyId = $e.internal('document/history/start-log', {
                type: 'add',
                title: 'Insert Template'
            });

            content.forEach(section => {
                regenerateIds(section);

                $e.run('document/elements/create', {
                    container: elementor.getPreviewContainer(),
                    model: section,
                    options: index >= 0 ? { at: index++ } : {}
                });
            });

            $e.internal('document/history/end-log', {
                id: historyId
            });

            this.insertIndex = null;

            if (this.dialog) {
                this.dialog.hide();
                $('.pe--template--insert.inserting').removeClass('inserting');
            }
        },
        /* -------------------------
         MASONRY LAYOUT
        ------------------------- */
        initMasonry() {

            $('.ptl--content').addClass('loading');

            if (self.masonry) {
                self.masonry.destroy();
            }

            self.masonry = new Masonry('.pe--elementor--templates--container', {
                itemSelector: '.ptl--template',
                percentPosition: true,
                // transitionDuration: 0
            });

            let msnry = self.masonry;

            imagesLoaded('.pe--elementor--templates--container', function (instance) {
                msnry.layout();
            })

            msnry.on('layoutComplete', function (event, items) {
                $('.ptl--content').removeClass('loading');
                setTimeout(() => {
                    self.loading = false;
                }, 1000);
            });



        },
    };

    /* -------------------------
     INIT
    ------------------------- */
    $(window).on('elementor/panel/init', function () {

        if (peImporterInitialized) return;
        peImporterInitialized = true;

        PeElementorImporter.init();

        elementor.on("preview:loaded", function () {

            $(elementor.$previewContents[0].body).on("click", "#pe-add-template-btn", function (e) {
                e.preventDefault();
                const $addSection = $(this).closest('.elementor-add-section-inline');
                var index;
                if ($addSection.length) {
                    index = $addSection.index();
                } else {
                    index = -1
                }

                PeElementorImporter.insertIndex = index;
                PeElementorImporter.openPeTemplatePopup();
            });

            $(document).on("click", ".pe--template--insert", function (e) {
                e.preventDefault();

                $(this).addClass('inserting');
                const template = $(this).closest('.ptl--template');
                const jsonPath = template.data('json');

                PeElementorImporter.importTemplate(jsonPath).done(response => {

                    if (!response.success) {
                        alert('Import failed');
                        return;
                    }

                    PeElementorImporter.insertTemplate(response.data.content, e);
                });
            });

            $(document).on("click", ".pe--library--switch", function (e) {
                e.preventDefault();

                $('#elementor-template-library-header-menu .elementor-active')
                    .removeClass('elementor-active');

                $(this).addClass('elementor-active');

                PeElementorImporter.page = 1;
                PeElementorImporter.hasMore = true;
                PeElementorImporter.loading = false;

                PeElementorImporter.renderBlocks(self.data, $(this).data('fetch'));
                PeElementorImporter.bindInfiniteScroll();
                PeElementorImporter.initMasonry();
            });

            $(document).on("click", ".ptl--filters ul li", function (e) {
                e.preventDefault();

                $('.ptl--filters ul li').removeClass('active');
                $(this).addClass('active');

                PeElementorImporter.filterBlocks(self.data, $(this).data('type'), $(this).data('fetch'));
                PeElementorImporter.bindInfiniteScroll();
                PeElementorImporter.initMasonry();


            });

            $(document).on("click", ".elementor-templates-modal__header__close--normal", function (e) {

                if (PeElementorImporter.dialog) {
                    PeElementorImporter.dialog.hide();
                }

            });

        });

    });

})(jQuery);