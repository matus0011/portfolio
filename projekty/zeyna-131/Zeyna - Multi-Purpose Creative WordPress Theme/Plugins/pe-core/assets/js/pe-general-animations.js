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

var gmatchMedia = gsap.matchMedia(),
    mobileQuery = window.matchMedia('(max-width: 450px)');;

function clearProps(target) {
    gsap.set(target, {
        clearProps: 'all'
    })
}

function elementorMatches(bpName, callback) {
    const bps = elementorFrontend.config.responsive.activeBreakpoints;
    if (!bps || !bps[bpName]) return;

    // breakpoint'leri value'ya göre küçükten büyüğe sırala
    const sorted = Object.entries(bps)
        .map(([name, bp]) => ({ name, ...bp }))
        .sort((a, b) => a.value - b.value);

    const currentIndex = sorted.findIndex(bp => bp.name === bpName);
    if (currentIndex === -1) return;

    const current = sorted[currentIndex];
    const prev = sorted[currentIndex - 1];
    const next = sorted[currentIndex + 1];

    let mq = '';

    // En küçük (mobile)
    if (!prev) {
        mq = `(max-width: ${current.value}px)`;
    }
    // En büyük (desktop)
    else if (!next) {
        mq = `(min-width: ${current.value}px)`;
    }
    // Aradaki (tablet vb.)
    else {
        mq = `(min-width: ${prev.value + 1}px) and (max-width: ${current.value}px)`;
    }

    const mql = window.matchMedia(mq);

    if (mql.matches) callback(current.name);

    mql.addEventListener('change', e => {
        if (e.matches) callback(current.name);
    });
}

class peGeneralAnimation {

    constructor(DOM_el, id, options, fromOptions, scroll, out) {

        this.DOM = {
            el: null,
        };

        this.DOM.el = DOM_el;
        this.settings = this.DOM.el.dataset.animSettings;


        this.handlePos = null;


        if (gsap.getById('pageLoader')) {

            if (this.DOM.el.getBoundingClientRect().top - window.outerHeight < window.outerHeight) {
                this.handlePos = this.DOM.el.getBoundingClientRect().top - window.outerHeight;
            }

        }

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

        const elementorBreakpoints = elementorFrontend.breakpoints.getActiveBreakpointsList();


        this.stagger = settings.stagger;
        this.staggerFrom = settings.staggerFrom;
        this.duration = settings.duration;
        this.delay = settings.delay;
        this.scrub = settings.scrub;
        this.repeat = settings.repeat;
        this.pin = settings.pin;
        this.pinMobile = settings.mobilePin;
        this.pinTarget = settings.pinTarget;
        this.animOut = settings.out;
        this.ease = settings.easing;
        this.target = this.DOM.el.classList.contains('anim-multiple') ? this.DOM.el.querySelectorAll('.inner--anim') : this.DOM.el;
        this.mobileDelay = settings.mobile_delay;

        // let childs = Array.from(this.DOM.el.children)
        //     .filter(el => el.classList.contains('elementor-element'));

        // this.target = childs;

        this.id = settings.id;

        if (this.staggerFrom) {

            this.stagger = {
                grid: [1, 20],
                from: this.staggerFrom,
                amount: settings.stagger,
            };
        }


        // Animation Defaults
        this.defaults = {
            x: 0,
            y: 0,
            xPercent: 0,
            yPercent: 0,
            scale: 1,
            opacity: 1,
            duration: .75,
            delay: 0,
            stagger: 0,
            ease: 'expo.out',

        };

        // Animation start stages
        this.from = {
            yPercent: 0,
            xPercent: 0,
            x: 0,
            y: 0,
        }

        // Scroll options
        this.scroll = {
            scrollTrigger: {
                trigger: null,
                scrub: null,
                pin: null,
                start: () => 'top bottom',
                end: () => 'bottom center',
                id: this.id,
                // pinSpacing: 'margin',
                invalidateOnRefresh: true,
                anticipatePin: 1,
                // markers: true,
                onEnter: () => {
                    this.DOM.el.classList.add('viewport-enter');
                },
            }
        }

        // ScrollTrigger.normalizeScroll(true);

        this.out = {
            yPercent: null,
            stagger: this.stagger,
            duration: this.duration,
            delay: this.delay,
            ease: 'power1.in',
        }


        this.scroll.scrollTrigger.start = settings.item_ref_start + ' ' + settings.window_ref_start;
        this.scroll.scrollTrigger.end = settings.item_ref_end + ' ' + settings.window_ref_end;
        // this.scroll.scrollTrigger.start = "clamp(20px 10%)";
        // this.scroll.scrollTrigger.end = () => `+=${this.target.offsetHeight}`;
        // this.scroll.scrollTrigger.invalidateOnRefresh = true;
        // this.scroll.scrollTrigger.markers = true;

        this.progress = 0;

        this.pin == null ? this.pin = false : '';
        this.scrub == null ? this.scrub = false : '';
        this.animOut == null ? this.animOut = false : '';


        this.anim = this.DOM.el.dataset.animation;


        // Defaults for animations

        if (this.anim === 'customMask') {

            this.currentMask = getComputedStyle(this.DOM.el).getPropertyValue('clip-path');

            var masks = {
                square: {
                    start: settings.square_start,
                    end: settings.square_end,
                },
                circle: {
                    start: settings.circle_start,
                    end: settings.circle_end,
                },
                triangle: {
                    start: 'polygon(50% 0%, 50% 0%, 100% 100%, 0% 100%)',
                    end: 'polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)',
                },
                rhombus: {
                    start: 'polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%)',
                    end: 'polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)',
                },
                hexagon: {
                    start: 'polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%)',
                    end: 'polygon(50% 0%, 100% 0%, 100% 100%, 50% 100%, 0% 100%, 0% 0%)',
                },
                left_arrow: {
                    start: 'polygon(40% 0%, 40% 20%, 100% 20%, 100% 80%, 40% 80%, 40% 100%, 0% 50%)',
                    end: 'polygon(0% 0%, 40% 0%, 100% 0%, 100% 100%, 40% 100%, 0% 100%, 0% 50%)',
                },
                right_arrow: {
                    start: 'polygon(0% 20%, 60% 20%, 60% 0%, 100% 50%, 60% 100%, 60% 80%, 0% 80%)',
                    end: ' polygon(0% 0%, 60% 0%, 100% 0%, 100% 50%, 100% 100%, 60% 100%, 0% 100%)',
                },
                left_chevron: {
                    start: 'polygon(100% 0%, 75% 50%, 100% 100%, 25% 100%, 0% 50%, 25% 0%)',
                    end: 'polygon(100% 0%, 100% 50%, 100% 100%, 0% 100%, 0% 50%, 0% 0%)',
                },
                right_chevron: {
                    start: 'polygon(75% 0%, 100% 50%, 75% 100%, 0% 100%, 25% 50%, 0% 0%)',
                    end: 'polygon(100% 0%, 100% 50%, 100% 100%, 0% 100%, 0% 50%, 0% 0%)',
                },
                star: {
                    start: 'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)',
                    end: 'polygon(50% 0%, 100% 0%, 100% 34%, 100% 59%, 100% 100%, 50% 100%, 0% 100%, 0% 63%, 0% 36%, 0% 0%)',
                },
                close: {
                    start: 'polygon(20% 0%, 0% 20%, 30% 50%, 0% 80%, 20% 100%, 50% 70%, 80% 100%, 100% 80%, 70% 50%, 100% 20%, 80% 0%, 50% 30%)',
                    end: 'polygon(0% 0%, 0% 19%, 0% 54%, 0% 75%, 0% 100%, 46% 100%, 100% 100%, 100% 80%, 100% 50%, 100% 21%, 100% 0%, 47% 0%)',
                }
            };

            this.from.clipPath = (masks[settings.mask_start]['start']);
            this.defaults.clipPath = (masks[settings.mask_start]['end']);

            this.out.clipPath = (masks[settings.mask_start]['start']);

            if (this.currentMask !== 'none') {
                this.from.clipPath = 'polygon(0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, % 0%, 0% 0%, 0% 0%)';
                this.defaults.clipPath = this.currentMask;

                this.out.clipPath = 'polygon(0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, % 0%, 0% 0%, 0% 0%)';

            }


        }


        if (this.anim === 'fadeIn') {

            this.from.opacity = 0;


            // this.defaults.duration = 0.75;
            this.defaults.ease = 'expo.inOut';

            if (settings.fade_blur) {
                this.from.filter = "blur(100px)";
                this.defaults.filter = "blur(0px)";
            }

            this.out.opacity = 0;
            this.out.filter = "blur(0px)";


        }

        if ((this.anim === 'fadeUp') || (this.anim === 'fadeDown')) {

            this.from.opacity = 0;
            this.anim === 'fadeUp' ? this.from.y = 100 : this.from.y = -100;

            if (settings.fade_blur) {
                this.from.filter = "blur(100px)";
                this.defaults.filter = "blur(0px)";
            }

            this.out.opacity = 0;
            this.out.filter = "blur(0px)";
            this.anim === 'fadeUp' ? this.out.y = -100 : this.out.y = 100;



        }

        if ((this.anim === 'fadeLeft') || (this.anim === 'fadeRight')) {

            this.from.opacity = 0;
            this.anim === 'fadeLeft' ? this.from.x = -100 : this.from.x = 100;

            if (settings.fade_blur) {
                this.from.filter = "blur(100px)";
                this.defaults.filter = "blur(0px)";
            }

            this.out.opacity = 0;
            this.anim === 'fadeLeft' ? this.out.x = 100 : this.out.x = -100;


        }

        if (this.anim === 'slideUp') {

            //    this.scroll.scrollTrigger.start = 'top-=' + this.DOM.el.offsetHeight + ' bottom';
            this.scroll.scrollTrigger.start = 'top bottom';

            this.from.y = window.innerHeight - this.DOM.el.getBoundingClientRect().top;
            this.defaults.y = 0;
            this.out.y = window.innerHeight - this.DOM.el.getBoundingClientRect().top;


            if (this.DOM.el.dataset.settings && getComputedStyle(this.DOM.el).transform !== 'none') {
                this.defaults.y = parseInt(getComputedStyle(this.DOM.el).transform.match(/matrix.*\((.+)\)/)[1].split(', ')[13]);
            }

        }


        if (this.anim === 'slideDown') {

            //    this.scroll.scrollTrigger.start = 'top-=' + this.DOM.el.offsetHeight + ' bottom';
            // this.scroll.scrollTrigger.start = 'top bottom';

            this.from.y = (window.innerHeight - this.DOM.el.getBoundingClientRect().top) * -1;
            this.defaults.y = 0;
            this.out.y = (window.innerHeight - this.DOM.el.getBoundingClientRect().top) * -1;

        }

        if (this.anim === 'slideLeft' || this.anim === 'slideRight') {

            this.from.x = this.anim === 'slideLeft' ? (this.DOM.el.getBoundingClientRect().left + this.DOM.el.getBoundingClientRect().width) * -1 : (this.DOM.el.getBoundingClientRect().width);
            this.out.x = this.anim === 'slideLeft' ? (this.DOM.el.getBoundingClientRect().left + this.DOM.el.getBoundingClientRect().width) * 1 : (this.DOM.el.getBoundingClientRect().width * -1);

        }

        if (this.anim === 'scaleUp') {

            let startScale = settings.start_scale,
                endScale = settings.end_scale;

            this.from.scale = startScale;
            this.defaults.scale = endScale;
            this.defaults.ease = 'expo.out';

            this.out.scale = startScale;

        }

        if (this.anim === 'scaleDown') {

            let startScale = settings.start_scale,
                endScale = settings.end_scale;

            this.from.scale = startScale;
            this.defaults.scale = endScale;
            // this.defaults.ease = 'expo.out';

            this.out.scale = startScale;

            this.scroll.scrollTrigger.pinSpacing = 'padding';


        }

        if (this.anim === 'maskUp') {

            this.from.clipPath = ('inset(100% 0% 0% 0%)');
            this.defaults.clipPath = ('inset(0% 0% 0% 0%)');

            this.out.clipPath = ('inset(100% 0% 0% 0%)');



        }

        if (this.anim === 'maskDown') {

            this.from.clipPath = ('inset(0% 0% 100% 0%)');
            this.defaults.clipPath = ('inset(0% 0% 0% 0%)');

            this.defaults.ease = 'power3.inOut'

            this.out.clipPath = ('inset(0% 0% 100% 0%)');
        }

        if (this.anim === 'maskLeft') {

            this.from.clipPath = ('inset(0% 0% 0% 100%)');
            this.defaults.clipPath = ('inset(0% 0% 0% 0%)');
            this.defaults.ease = 'expo.inOut'

            this.out.clipPath = ('inset(0% 0% 0% 100%)');

        }


        if (this.anim === 'maskRight') {

            this.from.clipPath = ('inset(0% 100% 0% 0%)');
            this.defaults.clipPath = ('inset(0% 0% 0% 0%)');

            this.out.clipPath = ('inset(0% 100% 0% 0%)');

        }

        if (this.anim === 'animateWidth') {
            this.defaults.width = settings.animate_width + '%';

            elementorBreakpoints.forEach(point => {
                elementorMatches(point, () => {
                    console.log(point);
                    if (settings[`animate_width_${point}`]) {
                        this.defaults.width = settings[`animate_width_${point}`] + '%';

                        console.log(settings[`animate_width_${point}`]);
                    }
                });
            });

        }



        this.stagger == null ? this.stagger = this.defaults.stagger : '';
        this.delay == null ? this.delay = this.defaults.delay : '';
        this.duration == null ? this.duration = this.defaults.duration : '';

        this.options = Object.assign(this.defaults, options);
        this.fromOptions = Object.assign(this.from, fromOptions);
        this.scroll = Object.assign(this.scroll, scroll);



        if (this.mobileDelay && mobileQuery.matches) {
            console.log(this.mobileDelay);
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

            this.scroll.scrollTrigger.refreshPriority = 999;

        }

        if ((this.scrub) && (!this.pin)) {
            this.scroll.scrollTrigger.scrub = 1;
        }

        if (this.scrub || this.pin) {
            this.defaults.ease = 'none';

        }



        gmatchMedia.add({
            isMobile: "(max-width: 550px)"

        }, (context) => {
            let {
                isMobile
            } = context.conditions;
            if (this.pinMobile !== true && this.scroll.scrollTrigger.pin) {
                this.scroll.scrollTrigger.pin = false;
            }


        });


        if (this.animOut) {
            this.out.stagger = this.options.stagger;
            this.out.duration = this.options.duration;
            this.out = Object.assign(this.out, out);
        }

        if (parents(this.DOM.el, '.site--menu').length || (parents(this.DOM.el, '.e-n-tabs-content').length && !parents(this.DOM.el, '.e-active').length)) {
            this.scroll.scrollTrigger = false;
        }

        if (this.ease !== 'default') {
            this.defaults.ease = this.ease;
        }

        if (this.repeat) {
            this.scroll.scrollTrigger.toggleActions = "play reset resume reset";
        }

        if (gsap.getById('pageLoader')) {
            document.addEventListener('pageLoaderDone', () => {
                this.render();
            });
        } else if (gsap.getById('zeynaPageTransition')) {
            let duration = gsap.getById('zeynaPageTransition').duration() / 2;
            document.addEventListener('pageTransitionDone', () => {
                setTimeout(() => {
                    this.render();
                }, duration * 1000);
            });

        } else if (gsap.getById('zeynaProjectTransition')) {
            document.addEventListener('projectTransitionDone', () => {
                this.render();
            });

        } else {
            this.render();
        }


    }

    render() {

        ScrollTrigger.getById(this.id) ? ScrollTrigger.getById(this.id).kill(true) : '';
        this.tl = gsap.timeline(this.scroll)
        if (!parents(this.DOM.el, '.reveal--anim--items').length) {

            this.tl.fromTo(this.target, this.fromOptions, this.options);

        } else {
            this.tl.kill();

            let revealCont = parents(this.DOM.el, '.reveal--anim--items')[0],
                firstContId = revealCont.querySelector('.e-con:first-child').dataset.id,
                tla = gsap.getById(revealCont.dataset.id),
                parentCon = parents(this.DOM.el, '.highlight--children')[0];


            if (revealCont.classList.contains('reveal--first--active')) {
                if (!parents(this.DOM.el, '.elementor-element-' + firstContId).length) {
                    let tween = gsap.fromTo(this.target, this.fromOptions, this.options);
                    tla.add(tween, 'label_' + parentCon.dataset.id);
                }
            } else {
                let tween = gsap.fromTo(this.target, this.fromOptions, this.options);
                tla.add(tween, 'label_' + parentCon.dataset.id);
            }


            if (!parents(this.DOM.el, '.elementor-element-03ae347').length) {
                let tweenOut = gsap.to(this.target, this.out);
                tla.add(tweenOut, 'label_out_' + parentCon.dataset.id);
            }
        }



        this.animOut == true ? this.tl.to(this.target, this.out) : '';

        this.tl.eventCallback("onStart", () => {
            this.DOM.el.classList.add('anim_start')
        });

        this.tl.eventCallback("onComplete", () => {
            if ((!this.scrub) && (!this.pin) && (this.anim !== 'scaleDown')) {
                clearProps(this.DOM.el);
                this.tl.revert();
            }
        });

        if (this.DOM.el.classList.contains('anim--mobile--disabled')) {
            gmatchMedia.add({
                isMobile: "(max-width: 550px)"

            }, (context) => {
                let {
                    isMobile
                } = context.conditions;
                this.tl.revert();


            });
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

};
