
class peTextAnimation {

    constructor(DOM_el, extSettings, extAnim, id, options, fromOptions, scroll, out) {
        this.DOM = {
            el: null,
            chars: null,
            words: null,
            lines: null
        };

        this.DOM.el = DOM_el;
        this.settings = extSettings ? extSettings : this.DOM.el.dataset.settings;



        const properties = this.settings.slice(1, -1).split(';');

        const settings = properties.reduce((acc, property) => {
            const [key, value] = property.split('=');
            acc[key] = parseValue(value);
            return acc;
        }, {});

        function parseValue(value) {

            if (value === "true" || value === "false") {
                return value === "true";
            }

            const parsedNumber = parseFloat(value.replace(',', '.'));
            if (!isNaN(parsedNumber)) {
                return parsedNumber;
            }

            return value;
        }

        this.stagger = settings.stagger;
        this.duration = settings.duration;
        this.delay = settings.delay;
        this.scrub = settings.scrub;
        this.repeat = settings.repeat;
        this.pin = settings.pin;
        this.pinTarget = settings.pinTarget;
        this.animOut = settings.out;
        this.target = settings.target;
        this.fade = settings.fade;
        this.justifyReveal = settings.justifyReveal;
        this.mobileDelay = settings.mobile_delay;
        this.ease = settings.easing;

        this.inserted = settings.inserted;
        this.parented = settings.parented;

        function parents(element, selector) {
            const parentsArray = [];
            let currentElement = element.parentElement;

            while (currentElement !== null) {
                if (currentElement.matches(selector)) {
                    parentsArray.push(currentElement);
                }
                currentElement = currentElement.parentElement;
            }

            return parentsArray;
        }

        if (this.parented) {

            this.parent = parents(this.DOM.el, '.tde__parent');
        }


        this.animations = ['charsUp', 'charsDown', 'charsRight', 'charsLeft', 'wordsUp', 'wordsDown', 'linesUp', 'linesDown', 'charsFadeOn', 'wordsFadeOn', 'linesFadeOn', 'charsScaleUp', 'charsScaleDown', 'charsRotateIn', 'charsFlipUp', 'charsFlipDown', 'linesMask', 'wordsJustifyCollapse', 'wordsJustifyExpand', 'slideLeft', 'slideRight'];

        this.defaults = {
            yPercent: 0,
            xPercent: 0,
            x: 0,
            y: 0,
            duration: 1,
            delay: 0,
            stagger: 0,
            ease: 'expo.out',
            opacity: 1,
            onComplete: () => {
                this.DOM.el.classList.add('is-inview')
                // this.pin != true && this.scrub != true && loader == null && !this.inserted && !	parents(this.DOM.el , '[data-elementor-type="pe-menu"]').length ? this.split.revert() : '';
                this.progress = 1;
            }
        };

        this.from = {
            yPercent: 0,
            xPercent: 0,
            x: 0,
            y: 0,
            opacity: this.fade ? 0 : 1
        }

        this.scroll = {
            scrollTrigger: {
                trigger: null,
                scrub: null,
                id: id ? id : parents(this.DOM.el, '.elementor-element')[0].dataset.id,
                pin: null,
                pinSpacing: 'padding',
                start: 'top bottom',
                end: 'bottom center',
                markers: false,
                // refreshPriority: 99,
                // invalidateOnRefresh: true,
                onEnter: () => {
                    this.DOM.el.classList.add('viewport-enter');

                    this.parent ? this.parent[0].classList.add('tde__enter') : '';

                },
            }
        }

        this.out = {
            yPercent: null,
            stagger: this.stagger,
            duration: this.duration,
            delay: this.delay,
            ease: 'power1.in',
            delay: this.delay,
            opacity: this.fade ? 0 : 1
        }

        this.id = Math.random().toString(16).slice(2);

        this.scroll.scrollTrigger.start = settings.item_ref_start + ' ' + settings.window_ref_start;
        this.scroll.scrollTrigger.end = settings.item_ref_end + ' ' + settings.window_ref_end;

        this.progress = 0;

        this.pin == null ? this.pin = false : '';
        this.scrub == null ? this.scrub = false : '';
        this.animOut == null ? this.animOut = false : '';

        this.anim = extAnim ? extAnim : this.DOM.el.dataset.animation;

        this.anim.includes('chars') ? this.type = 'chars, words' : '';
        this.anim.includes('words') ? this.type = 'words' : '';
        this.anim.includes('lines') ? this.type = 'lines' : '';
        this.anim.includes('Justify') ? this.type = 'lines, words' : '';

        this.splits = this.DOM.el;
        if (this.DOM.el.classList.contains('text--anim--multi')) {
            this.splits = this.DOM.el.querySelectorAll('.text--anim--inner');
        }



        if (this.anim.includes('words')) {
            this.target = this.DOM.el.querySelectorAll('.anim_word');
        }

        if (this.anim.includes('chars')) {
            this.target = this.DOM.el.querySelectorAll('.anim_char');
        }

        if (this.anim.includes('lines')) {
            this.target = this.DOM.el.querySelectorAll('.anim_line');
        }

        this.state = null;
        if (this.anim === 'wordsJustifyExpand' || this.anim === 'wordsJustifyCollapse') {
            this.state = Flip.getState(this.DOM.el.querySelectorAll('.anim_line > .anim_word'));
        }

        if (this.anim === 'linesTypeWrite') {

            if (this.DOM.el.querySelector('.field--content , .field--label')) {
                this.target = this.DOM.el.querySelectorAll('.field--content , .field--label');
            }

            this.defaults.text = {
                value: '',
                delimiter: ''
            };

        }

        if (this.anim === 'charsUp') {
            this.from.yPercent = 100;
            this.defaults.yPercent = 0;
            this.defaults.stagger = 0.05;
            this.defaults.duration = 2;
            this.out.yPercent = -100;

        }

        if (this.anim === 'charsDown') {
            this.from.yPercent = -100
            this.defaults.stagger = 0.035;
            this.defaults.duration = 2;

            this.out.yPercent = -100;
        }

        if (this.anim === 'charsRight') {

            this.from.x = -100
            this.defaults.x = 0

            this.out.x = 100;

        }

        if (this.anim === 'charsLeft') {


            this.from.x = 100;
            this.defaults.x = 0;
            this.out.x = -100;

        }

        if (this.anim === 'wordsUp' || this.justifyReveal === 'words-up') {
            this.from.yPercent = 110

            this.defaults.stagger = 0.025;
            this.defaults.duration = 2;

            this.animOut ? this.out.yPercent = -100 : '';

            if (this.justifyReveal === 'words-up') {
                this.defaults.ease = 'expo.inOut';
            }

            this.out.yPercent = -110;

        }

        if (this.anim === 'wordsDown' || this.justifyReveal === 'words-down') {

            this.from.yPercent = -110

            this.defaults.stagger = -0.01;
            this.defaults.duration = 2;

            if (this.justifyReveal === 'words-down') {
                this.defaults.ease = 'expo.inOut';
            }

            this.out.yPercent = 110;


        }

        if (this.anim === 'linesUp') {

            this.from.yPercent = 100

            this.defaults.stagger = 0.15;
            this.defaults.duration = 2;
            this.defaults.ease = 'expo.out';

            this.out.yPercent = -100;
        }

        if (this.anim === 'linesDown') {

            this.from.yPercent = -100

            this.defaults.stagger = -0.1;
            this.defaults.duration = 1.5;

            this.out.yPercent = 100;
        }

        if (this.anim === 'charsFadeOn') {

            this.defaults.opacity = 1;

            this.defaults.stagger = 0.01;
            this.defaults.duration = 1.5;

            this.out.opacity = 0;
            this.out.stagger = -0.01;
            this.out.ease = 'none';


        }

        if (this.anim === 'wordsFadeOn') {

            this.defaults.opacity = 1;

            this.defaults.stagger = 0.02;
            this.defaults.duration = 3;

            this.out.opacity = 0;
        }

        if (this.anim === 'linesFadeOn') {

            this.defaults.opacity = 1;

            this.defaults.stagger = 0.1;
            this.defaults.duration = 2;

            this.out.opacity = 0;
        }

        if ((this.anim === 'charsScaleUp') || (this.anim === 'charsScaleDown')) {
            this.from.scaleY = 0

            this.defaults.scaleY = 1
            this.defaults.stagger = 0.05;
            this.defaults.duration = 2;

            this.out.scaleY = 0;


        }

        if (this.anim === 'charsRotateIn') {

            this.from.rotateX = -90

            this.defaults.rotateX = 0;

            this.defaults.stagger = 0.03;
            this.defaults.duration = 2;

            this.animOut ? this.out.rotateX = 90 : '';

            this.out.rotateX = -90

        }

        if (this.anim === 'charsFlipUp') {

            this.from.x = -50
            this.from.yPercent = 50
            this.from.rotateY = 180
            this.from.opacity = 0

            this.defaults.x = 0
            this.defaults.yPercent = 0
            this.defaults.rotateY = 0
            this.defaults.opacity = 1

            this.defaults.stagger = -0.05;
            this.defaults.duration = 1;

            this.out.x = -50
            this.out.yPercent = 50
            this.out.rotateY = 180
            this.out.opacity = 0

        }

        if (this.anim === 'charsFlipDown') {

            this.from.x = 50
            this.from.yPercent = -50
            this.from.rotateY = -180
            this.from.opacity = 0

            this.defaults.x = 0
            this.defaults.yPercent = 0
            this.defaults.rotateY = 0
            this.defaults.opacity = 1

            this.defaults.stagger = 0.05;
            this.defaults.duration = 1;

            this.out.x = 50
            this.out.yPercent = -50
            this.out.rotateY = -180
            this.out.opacity = 0
        }

        if (this.anim === 'linesMask') {

            this.from.width = '0%';

            this.defaults.width = '100%';
            this.defaults.stagger = 0.2;
            this.defaults.duration = 2;

            this.out.width = '0%';
            this.out.opacity = 0;

            this.scroll.scrollTrigger.start = 'top 70%';
            this.scroll.scrollTrigger.end = 'bottom center';
        }

        if (this.anim === 'linesHighlight') {

            this.from.opacity = 0;

            this.defaults.opacity = 1;

            this.out.opacity = 0;
        }


        if (this.anim === 'wordsHighlight') {

            this.from.opacity = 0;

            this.defaults.opacity = 1;

            this.out.opacity = 0;
        }


        // 

        this.stagger == null ? this.stagger = this.defaults.stagger : '';
        this.delay == null ? this.delay = this.defaults.delay : '';
        this.duration == null ? this.duration = this.defaults.duration : '';

        this.options = Object.assign(this.defaults, options);
        this.fromOptions = Object.assign(this.from, fromOptions);
        this.scroll = Object.assign(this.scroll, scroll);

        if (this.mobileDelay && mobileQuery.matches) {
            this.delay = settings.mobileDelay;
        }

        this.options.stagger = this.stagger;
        this.options.delay = this.delay;
        this.options.duration = this.duration;

        this.scroll.scrollTrigger.trigger = this.DOM.el;

        if (this.pin) {

            this.scrub = true
            this.scroll.scrollTrigger.scrub = 1;

            if (this.pinTarget) {

                this.scroll.scrollTrigger.pin = this.pinTarget;
                this.scroll.scrollTrigger.trigger = this.pinTarget;

                const element = document.querySelector(this.pinTarget);

                element.style.cssText += 'transition-duration:0s';

            } else {
                this.scroll.scrollTrigger.pin = true;
            }

        }

        if ((this.scrub) && (!this.pin)) {
            this.scroll.scrollTrigger.scrub = 1;
        }

        if (this.animOut) {

            this.out.stagger = this.options.stagger;
            this.out.duration = this.options.duration;
            this.out = Object.assign(this.out, out);
        }

        if (parents(this.DOM.el, '.site--menu').length || (parents(this.DOM.el, '.e-n-tabs-content').length && !parents(this.DOM.el, '.e-active').length)) {
            this.scroll.scrollTrigger = false;
        }


        if (parents(this.DOM.el, '.markers--on').length) {
            this.scroll.scrollTrigger.markers = true;

        }

        if (this.scrub == true) {
            this.defaults.ease = "power3.out";
            this.out.ease = "power2.in";
            this.defaults.scrub = true;
            this.DOM.el.classList.add('anim_scrubbed')
        }


        if (this.scrub || this.pin) {

            this.defaults.ease = 'none';
            this.out.ease = 'none';
        }

        if (this.repeat) {
            this.scroll.scrollTrigger.toggleActions = "play reset resume reset";
        }

        this.defaults.id = parents(this.DOM.el, '.elementor-element')[0].dataset.id;

        if (this.ease !== 'default') {
            this.defaults.ease = this.ease;
        }



        this.split = SplitText.create(this.splits, {
            type: this.type,
            mask: this.anim.includes('chars') ? 'chars' : this.anim.includes('lines') ? 'lines' : this.anim.includes('words') ? 'words' : '',
            charsClass: 'anim_char',
            linesClass: 'anim_line',
            wordsClass: 'anim_word',
            tag: 'span',
            autoSplit: true,
            // smartWrap: true,
            ignore: [".inserted--sup--text", ".inner--image", '.pe-dynamic-words'],
            onSplit: (self) => {
                var target;
                if (this.anim.includes('words')) {
                    target = self.words;
                }
                if (this.anim.includes('chars')) {
                    target = self.chars;
                }
                if (this.anim.includes('lines')) {
                    target = self.lines;
                }

                if (this.anim === 'linesMask') {

                    var elements = self.lines;

                    elements.forEach(function (element) {
                        var clone = element.cloneNode(true);
                        clone.classList.add('clone');
                        element.insertAdjacentElement('afterend', clone);
                    });

                }

                if (gsap.getById('pageLoader')) {
                    document.addEventListener('pageLoaderDone', () => {
                        this.render(target);
                    });
                } else if (gsap.getById('zeynaPageTransition')) {
                    let duration = gsap.getById('zeynaPageTransition').duration() / 4;
                    document.addEventListener('pageTransitionDone', () => {
                        setTimeout(() => {
                            this.render(target);
                        }, duration * 1000);
                    });

                } else if (gsap.getById('zeynaProjectTransition')) {
                    document.addEventListener('projectTransitionDone', () => {
                        this.render(target);
                    });

                } else {
                    this.render(target);
                }
            }
        });
    }

    render(target) {

        if (!this.DOM.el.classList.contains('initialized')) {

            let id = this.scroll.scrollTrigger.id,
                scope = parents(this.DOM.el, '.elementor-element');

            ScrollTrigger.getById(id) ? ScrollTrigger.getById(id).kill(true) : '';

            this.DOM.el.classList.add('initialized')

            this.tl = gsap.timeline(this.scroll);

            if (!parents(this.DOM.el, '.reveal--anim--items').length) {

                if (this.anim === 'linesTypeWrite') {
                    this.tl.from(target, this.defaults);
                } else {
                    this.tl.fromTo(target, this.fromOptions, this.options);
                }

            } else {

                this.tl.kill();

                let revealCont = parents(this.DOM.el, '.reveal--anim--items')[0],
                    firstContId = revealCont.querySelector('.e-con:first-child').dataset.id,
                    tla = gsap.getById(revealCont.dataset.id),
                    parentCon = parents(this.DOM.el, '.highlight--children')[0];


                if (revealCont.classList.contains('reveal--first--active')) {
                    if (!parents(this.DOM.el, '.elementor-element-' + firstContId).length) {
                        let tween = gsap.fromTo(target, this.fromOptions, this.options);
                        tla.add(tween, 'label_' + parentCon.dataset.id);
                    }
                } else {
                    let tween = gsap.fromTo(target, this.fromOptions, this.options);
                    tla.add(tween, 'label_' + parentCon.dataset.id);
                }

                if (!parents(this.DOM.el, '.elementor-element-03ae347').length) {
                    let tweenOut = gsap.to(target, this.out);
                    tla.add(tweenOut, 'label_out_' + parentCon.dataset.id);
                }

            }


            if (this.DOM.el.querySelector('.inserted--ls--hold, .inserted--element')) {

                let items = this.DOM.el.querySelectorAll('.inserted--ls--hold, .inserted--element');

                gsap.to(items, {
                    width: 'auto',
                    visibility: 'visible',
                    duration: this.duration / 2,
                    delay: 5,
                    stagger: this.stagger,
                    ease: 'power2.out'
                })
            }

            if (this.DOM.el.querySelector('span.underlined')) {

                let items = this.DOM.el.querySelectorAll('span.underlined');

                this.tl.fromTo(items, {
                    "--lineWidth": '0%',
                }, {
                    "--lineWidth": '100%',
                    duration: this.duration,
                    delay: this.delay,
                    stagger: this.stagger,
                    ease: 'power1.inOut'
                }, 0)
            }

            this.animOut == true ? this.tl.to(target, this.out) : '';

            this.tl.eventCallback("onStart", () => {
                this.DOM.el.classList.add('anim_start');
            });

            if (this.anim === 'wordsJustifyExpand' || this.anim === 'wordsJustifyCollapse') {

                this.DOM.el.classList.add('words--just--switch');

                this.flip = Flip.from(this.state, {
                    ease: 'expo.inOut',
                    absolute: true,
                    absoluteOnLeave: true,
                    stagger: this.stagger,
                    duration: this.duration,
                })

                if (this.justifyReveal !== 'none') {
                    this.tl.add(this.flip, this.duration / 2);
                } else {
                    this.tl.add(this.flip, 0);
                };

            }

            if (parents(this.DOM.el, '.site--menu').length && !parents(this.DOM.el, '.e-n-tabs-content').length) {

                let menu = parents(this.DOM.el, '.site--menu'),
                    nav = parents(menu[0], '.site--nav');
    
                this.tl.pause();
    
                let toggle = nav[0].querySelector('.menu--toggle'),
                    clicks = 0;
    
                toggle.addEventListener('click', () => {
                    clicks++
    
                    if (clicks % 2 == 0) {
                        // Close
                        this.tl.reverse();
    
                    } else {
                        // Open
                        this.tl.play();
                    }
                })
    
            }
    
            if (parents(this.DOM.el, '.e-n-tabs-content').length) {
    
                let tabs = parents(this.DOM.el, '.e-n-tabs')[0],
                    parentContent = parents(this.DOM.el, 'div[role=tabpanel]')[0];
    
                const match = parentContent.id.match(/(\d+)$/);
    
                if (match) {
                    const tabbId = parseInt(match[1], 10);
                    let title = document.querySelector('#e-n-tab-title-' + tabbId);
                    title.addEventListener('click', () => {
                        this.tl.restart();
                    })
                }
    
            }

        }

    }
}
