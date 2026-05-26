(() => {
  window.addEventListener('elementor/frontend/init', function () {
    const Modules = elementorModules.frontend.handlers.Base;
    const WCFMotionCardHandler = Modules.extend({
      onInit() {
        // editor/manual flags
        this._nmcManual = false;
        this._mm = null; // gsap.matchMedia() context
        this._tls = []; // timelines created in current run

        this._nmcBindEditorTrigger();

        // floating buttons (editor only)
        this._injectBackUI();
        this._injectRunUI();

        // on frontend this will execute; in editor it will early-return
        this.run();
      },
      // ----- Floating Back button (editor only)
      _injectBackUI() {
        if (!elementorFrontend.isEditMode()) return;
        const root = this.$element && this.$element[0];
        if (!root || this._backWrap) return;
        const wrap = document.createElement('div');
        wrap.className = 'nmc-back-wrap';
        wrap.style.position = 'absolute';
        wrap.style.top = '12px';
        wrap.style.right = '12px';
        wrap.style.zIndex = '999999';
        wrap.style.display = 'none'; // initially hidden
        wrap.style.pointerEvents = 'auto';
        const btn = document.createElement('button');
        btn.className = 'button nmc-btn-back-to-design';
        btn.type = 'button';
        btn.textContent = 'Back to Design';
        btn.style.cursor = 'pointer';
        btn.style.background = '#f6502c';
        btn.style.color = '#fff';
        btn.style.padding = '9px 15px';
        btn.addEventListener('click', () => {
          this._nmcResetAll();
          this._unlockDesign();
        });
        wrap.appendChild(btn);
        if (!root.style.position) root.style.position = 'relative';
        root.appendChild(wrap);
        this._backWrap = wrap;
        this._backBtn = btn;
      },
      // ----- Floating Run button (editor only)
      _injectRunUI() {
        if (!elementorFrontend.isEditMode()) return;
        const root = this.$element && this.$element[0];
        if (!root || this._runWrap) return;
        const wrap = document.createElement('div');
        wrap.className = 'nmc-run-wrap';
        wrap.style.position = 'absolute';
        wrap.style.top = '12px';
        wrap.style.right = '12px';
        wrap.style.zIndex = '999999';
        wrap.style.display = 'block';
        wrap.style.pointerEvents = 'auto';
        const btn = document.createElement('button');
        btn.className = 'button button-primary nmc-btn-run';
        btn.type = 'button';
        btn.textContent = 'Run Animations';
        btn.style.cursor = 'pointer';
        btn.style.background = '#f6502c';
        btn.style.color = '#fff';
        btn.style.padding = '9px 15px';
        btn.addEventListener('click', () => {
          this._nmcManual = true;
          try {
            this.run();
            if (window.ScrollTrigger) ScrollTrigger.refresh(true);
            if (this._runWrap) this._runWrap.style.display = 'none';
            if (this._backWrap) this._backWrap.style.display = 'block';
          } finally {
            this._nmcManual = false;
          }
        });
        wrap.appendChild(btn);
        if (!root.style.position) root.style.position = 'relative';
        root.appendChild(wrap);
        this._runWrap = wrap;
        this._runBtn = btn;
      },
      // disable content interactions, show Back button (editor only)
      _lockDesign() {
        const root = this.$element && this.$element[0];
        if (!root) return;
        const content = root.querySelector('.aae-card-area');
        if (content) content.style.pointerEvents = 'none';
        if (this._backWrap) this._backWrap.style.display = 'block';
        if (this._runWrap) this._runWrap.style.display = 'none';
      },
      // enable interactions, show Run button (editor only)
      _unlockDesign() {
        const root = this.$element && this.$element[0];
        if (!root) return;
        const content = root.querySelector('.aae-card-area');
        if (content) content.style.pointerEvents = '';
        if (this._backWrap) this._backWrap.style.display = 'none';
        if (this._runWrap) this._runWrap.style.display = 'block';
      },
      // editor: repeater selection -> activate slide
      onEditSettingsChange(propertyName, value) {
        if ('activeItemIndex' === propertyName) this.setActiveByDataIndex(value);
      },
      setActiveByDataIndex(i) {
        const root = this.$element && this.$element[0];
        if (!root) return;
        const cards = Array.from(root.querySelectorAll('.toolkit-card-anim'));
        const active = root.querySelector('.toolkit-card-anim[data-slide-index="' + i + '"]');
        if (!cards.length || !active) return;
        cards.forEach(c => {
          c.style.zIndex = 1;
          c.classList.remove('is-active');
          if (!window.gsap) c.style.transform = 'scale(0.97)';
        });
        const pos = window.getComputedStyle ? getComputedStyle(active).position : active.style.position;
        if (!pos || pos === 'static') active.style.position = 'relative';
        active.style.zIndex = 9999;
        active.classList.add('is-active');
        if (window.gsap) {
          gsap.to(cards, {
            scale: 0.97,
            duration: 0.15,
            overwrite: true
          });
          gsap.to(active, {
            scale: 1,
            duration: 0.2,
            ease: 'power2.out'
          });
        } else {
          active.style.transform = 'scale(1)';
        }
      },
      // helper: switch cards container positioning
      _nmcSetCardsPosition(pos) {
        const root = this.$element && this.$element[0];
        if (!root) return;
        root.querySelectorAll('.aae-toolkit-item-cards').forEach(el => {
          el.style.position = pos; // 'absolute' or 'unset'
        });
      },
      // kill timelines/ScrollTriggers/matchMedia
      _nmcCleanup() {
        try {
          const root = this.$element && this.$element[0];
          if (this._tls && this._tls.length) {
            this._tls.forEach(tl => tl && tl.kill && tl.kill());
            this._tls = [];
          }
          if (root && window.ScrollTrigger) {
            ScrollTrigger.getAll().forEach(st => {
              if (st && st.trigger && root.contains(st.trigger)) st.kill();
            });
          }
          if (this._mm && this._mm.revert) this._mm.revert();
          this._mm = null;
        } catch (e) {}
      },
      // full reset to design state
      _nmcResetAll() {
        this._nmcCleanup();
        const root = this.$element && this.$element[0];
        if (root) {
          root.querySelectorAll('.toolkit-card-anim').forEach(c => {
            c.style.zIndex = '';
            c.classList.remove('is-active');
            c.style.transform = '';
          });
          root.querySelectorAll('.toolkit-top-title, .toolkit-bottom-title').forEach(el => {
            el.style.transform = '';
            el.style.willChange = '';
            el.style.transition = '';
          });
        }
        this._nmcSetCardsPosition('unset');
        this._unlockDesign();
        if (window.ScrollTrigger) ScrollTrigger.refresh(true);
      },
      run: function run() {
        // In editor: only run when triggered manually
        if (elementorFrontend.isEditMode() && !this._nmcManual) return;

        // clean any previous run
        this._nmcCleanup();

        // guards
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
          console.warn('[NMC] GSAP/ScrollTrigger not found');
          return;
        }

        // while animating, absolute (for stack layout)
        this._nmcSetCardsPosition('absolute');

        // editor-only: lock UI + reveal "Back"
        if (elementorFrontend.isEditMode()) this._lockDesign();
        gsap.registerPlugin(ScrollTrigger);
        const root = this.$element && this.$element[0];
        const toolkitContainer = root && root.querySelector('.aae-card-area');
        if (!toolkitContainer) return;
        const toolkitCards = gsap.utils.toArray(root.querySelectorAll('.toolkit-card-anim'));
        if (!toolkitCards.length) return;

        // a fresh media context
        this._mm = gsap.matchMedia();

        // ensure container positioned
        if (getComputedStyle(toolkitContainer).position === 'static') {
          toolkitContainer.style.position = 'relative';
        }

        // default first active on frontend
        if (!elementorFrontend.isEditMode()) this.setActiveByDataIndex(0);

        // (optional listener kept)
        window.addEventListener('elementor/nested-container/atomic-repeater', () => {});

        // config from data attribute
        let cfg = {};
        const raw = toolkitContainer.getAttribute('data-scroll-trigger');
        if (raw) {
          try {
            cfg = JSON.parse(raw);
          } catch (e) {
            console.warn('Invalid data-scroll-trigger JSON', e);
          }
        }
        const start = (cfg.start ?? 'top top') + '';
        const end = (cfg.end ?? 'bottom bottom') + '';
        const trigger = (cfg.trigger ?? '.test') + '';
        const endTrigger = (cfg.endTrigger ?? '.test') + '';
        const unstackStart = (cfg.unStackStart ?? 'top top') + '';
        const unStackEnd = (cfg.unStackEnd ?? 'bottom bottom') + '';
        const unStackTrigger = (cfg.unStackTrigger ?? '.test') + '';

        // main MQ
        this._mm.add('(min-width: 1200px)', () => {
          // --- first timeline (entrances)
          const cardTl = gsap.timeline({
            scrollTrigger: {
              trigger: toolkitContainer,
              start: start,
              end: end,
              toggleActions: 'restart none reverse none'
            }
          });
          this._tls.push(cardTl);
          toolkitCards.forEach((card, index) => {
            if (index !== 0) {
              cardTl.from(card, {
                x: index % 2 === 0 ? '-110vw' : '110vw',
                duration: 0.2,
                ease: 'expo.out'
              });
            }
          });

          // --- unstack timeline
          const toolkitTitlesTop = gsap.utils.toArray(root.querySelectorAll('.toolkit-top-title'));
          const toolkitTitlesBottom = gsap.utils.toArray(root.querySelectorAll('.toolkit-bottom-title'));
          const cardUnstackTl = gsap.timeline({
            scrollTrigger: {
              trigger: toolkitContainer,
              start: 'top 20%',
              end: 'bottom -=800',
              pin: true,
              scrub: true,
              pinspacing: false // correct casing
            }
          });
          this._tls.push(cardUnstackTl);
          let currentTitle = toolkitTitlesTop[0];
          let currentBottomTitle = toolkitTitlesBottom[0];
          if (toolkitTitlesTop.length && toolkitCards.length) {
            for (let i = toolkitCards.length - 1, j = 0; i > 0; i--, j++) {
              const common = {
                ease: 'power3.in',
                duration: 0
              };
              cardUnstackTl.to(toolkitCards[i], {
                y: '-150vh',
                rotation: 0,
                ease: 'none'
              }, i === toolkitCards.length - 1 ? 0 : '>.2');
              cardUnstackTl.to(currentTitle, {
                y: '-400%',
                ...common
              }, '-=0.3');
              cardUnstackTl.fromTo(toolkitTitlesTop[j + 1], {
                y: '400%'
              }, {
                y: '0%',
                ...common
              }, '-=0.3');
              cardUnstackTl.to(currentBottomTitle, {
                y: '-400%',
                ...common
              }, '-=0.3');
              cardUnstackTl.fromTo(toolkitTitlesBottom[j + 1], {
                y: '400%'
              }, {
                y: '0%',
                ...common
              }, '-=0.3');
              currentTitle = toolkitTitlesTop[j + 1];
              currentBottomTitle = toolkitTitlesBottom[j + 1];
            }
          }

          // cleanup for this MQ on revert
          return () => {
            try {
              cardTl.kill();
            } catch (e) {}
            try {
              cardUnstackTl.kill();
            } catch (e) {}
          };
        });
      },
      // editor events to run/reset from panel if needed
      _nmcBindEditorTrigger() {
        if (!elementorFrontend.isEditMode()) return;
        const topEditor = window.parent && window.parent.elementor && window.parent.elementor.channels && window.parent.elementor.channels.editor || window.top && window.top.elementor && window.top.elementor.channels && window.top.elementor.channels.editor || null;
        if (!topEditor) return;
        const self = this;
        this._nmcRunCb = function () {
          self._nmcManual = true;
          try {
            self.run();
            if (window.ScrollTrigger) ScrollTrigger.refresh(true);
          } finally {
            self._nmcManual = false;
          }
        };
        this._nmcResetCb = function () {
          self._nmcResetAll();
        };
        topEditor.on('nmc:run', this._nmcRunCb);
        topEditor.on('nmc:reset', this._nmcResetCb);
        this._nmcEditorChannel = topEditor;
      },
      onDestroy() {
        try {
          if (this._nmcEditorChannel) {
            if (this._nmcRunCb) this._nmcEditorChannel.off('nmc:run', this._nmcRunCb);
            if (this._nmcResetCb) this._nmcEditorChannel.off('nmc:reset', this._nmcResetCb);
          }
        } catch (e) {}
        this._nmcCleanup();
        if (typeof Modules.prototype.onDestroy === 'function') {
          Modules.prototype.onDestroy.apply(this, arguments);
        }
      }
    });
    elementorFrontend.hooks.addAction('frontend/element_ready/aae-nested-motion-card.default', $element => {
      elementorFrontend.elementsHandler.addHandler(WCFMotionCardHandler, {
        $element
      });
    });
  });
})();