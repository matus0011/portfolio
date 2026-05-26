const plyrInstances = [];
window.plyrInstances = plyrInstances;

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


class peVideoPlayer {

    constructor(DOM_el, options) {

        this.DOM = {
            el: null,
            video: null,
            button: null,
            flip: null,
            widget: null
        };

        this.DOM.el = DOM_el;

        this.options = {
            controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'captions', 'settings', 'pip', 'airplay', 'fullscreen'],
            clickToPlay: false,
            autopause: false,
            debug: false,
            autoplay: false,
            autopause: false,
            muted: false,
            playsinline: true,
            loop: {
                active: true
            },
            storage: {
                enabled: false
            },
            youtube: {
                modestbranding: 1,
                controls: 0,
                rel: 0,
                cc_load_policy: 0,
                iv_load_policy: 3,
                noCookie: true,
                frameborder: 0,
            },
            vimeo: {
                autopause: false,
                controls: false
            }
        }

        var parent = this.DOM.el;
        this.video = parent.querySelector('.p-video');
        this.button = parent.querySelector('.pe--large--play');
        this.parent = parent.parentNode;
        this.lbHold = this.parent.querySelector('.pe--lightbox--hold');
        this.buttonOnly = false;

        if (parents(parent, '.video--button').length) {
            this.button = parent.querySelector('.pe--button');
            this.buttonOnly = true;
        }

        //Get Attributes
        this.autoplay = (this.DOM.el.dataset.autoplay === 'true');
        this.observer = (this.DOM.el.dataset.observer === 'true');
        this.muted = (this.DOM.el.dataset.muted === 'true');
        this.loop = (this.DOM.el.dataset.loop === 'true');
        this.controls = this.DOM.el.dataset.controls;
        this.lightbox = (this.DOM.el.dataset.lightbox === 'true');
        this.playScroll = (this.DOM.el.dataset.playOnScroll === 'true');

        if (this.playScroll) {
            this.scrollOpt = this.DOM.el.dataset.scroll;

            const properties = this.scrollOpt.slice(1, -1).split(';');

            this.scrollOpt = properties.reduce((acc, property) => {
                const [key, value] = property.split('=');
                acc[key] = parseValue(value);
                return acc;
            }, {});


        }


        this.hideElements = this.DOM.el.dataset.hideElements;

        this.autoplay ? parent.classList.add('autoplay--running') : parent.classList.add('not-interacted');
        this.dynamicControls = parent.classList.contains('vid--controls--dynamic') ? true : false;

        // Set Player Options
        this.options.controls = this.controls.split(',');
        this.options.autoplay = this.autoplay;
        this.options.loop.active = this.loop;
        this.options.muted = this.muted;

        this.render();

    }

    render() {

        if (!this.DOM.el.classList.contains('vid--initialized')) {
            let players = document.querySelectorAll('.pe-video');

            this.player = new Plyr(this.video, this.options);

            this.player.on('ready', (event) => {

                if (this.playScroll) {
                    this.player.play();
                    this.player.muted = true;
                    this.player.loop = false;
                    this.player.autoplay = true;
                    this.player.preload = true;


                    let start = this.scrollOpt.item_ref_start + ' ' + this.scrollOpt.window_ref_start;
                    let end = this.scrollOpt.item_ref_end + '+=2000 ' + this.scrollOpt.window_ref_end;

                    let triggera = parents(this.DOM.el, '.elementor-element')[0];
                    let pinValue = this.scrollOpt.pin ? true : false;

                    // if (this.scrollOpt.pinTarget) {
                    //     const el = document.querySelector(this.scrollOpt.pinTarget);
                    //     triggera = el;

                    //     const pinnedTrigger = ScrollTrigger.getAll().find(trigger => {
                    //         return trigger.trigger === el && trigger.pin;
                    //     });

                    //     if (pinnedTrigger) {
                    //         start = pinnedTrigger.start;
                    //         end = pinnedTrigger.end;
                    //         pinValue = false;
                    //     }

                    //     if (document.body.classList.contains('e-preview--show-hidden-elements')) {
                    //         return false;
                    //     }
                    // }

                    const existing = ScrollTrigger.getById(this.scrollOpt.id);
                    if (existing) existing.kill(true);

                    ScrollTrigger.create({
                        id: this.scrollOpt.id,
                        trigger: triggera,
                        start: start,
                        end: end,
                        pin: pinValue,
                        pinSpacer: this.scrollOpt.pinSpacer ? '.video-pin-spacer' : '',
                        pinSpacing: this.scrollOpt.pinSpacer ? 'padding' : false,
                        onEnter: () => {
                            this.player.pause();
                            this.player.currentTime = 0;
                        },

                        onUpdate: (self) => {
                            const duration = this.player.duration;
                            if (!duration || isNaN(duration)) return;
                            const newTime = self.progress * duration;
                            this.player.currentTime = newTime - 0.1;
                            console.log(newTime);
                        }
                    });

                }

            });

            ScrollTrigger.create({
                trigger: this.DOM.el,
                start: 'top bottom',
                end: 'bottom bottom',
                onEnter: () => {
                    if (!parents(this.DOM.el, '.vid--no--ratio').length && this.player.ratio && !this.DOM.el.classList.contains('pe-self') && !this.DOM.el.classList.contains('pe-stream')) {
                        ;

                        this.ratio = this.player.ratio.split(':')[0] / this.player.ratio.split(':')[1];
                        var parentRatio = this.parent.offsetWidth / this.parent.offsetHeight;
                        const $iframe = this.parent.querySelector('.plyr__video-wrapper');
                        const targetWidth = this.parent.offsetHeight * this.ratio;

                        if (parentRatio < this.ratio) {

                            gsap.set($iframe, {
                                width: targetWidth + 10,
                                height: this.parent.offsetHeight + 10,
                                x: -1 * (targetWidth - this.parent.offsetWidth) / 2
                            })

                        }

                    }
                },
            })

            if (this.button) {

                this.button.addEventListener("click", () => {

                    if (!this.DOM.el.classList.contains('vid--interracted')) {

                        this.DOM.el.classList.add('vid--interracted');

                        if (this.hideElements) {
                            gsap.to(document.querySelectorAll(this.hideElements), {
                                opacity: 0,
                                pointerEvents: 'none'
                            })
                        }

                        if (this.lightbox || this.buttonOnly) {

                            // let state = Flip.getState([this.DOM.el, this.DOM.el.querySelector('.plyr')]);
                            let state = Flip.getState(this.DOM.el, {
                                props: ['padding']
                            });

                            this.player.play();

                            this.DOM.el.classList.add('lightbox-open');
                            this.parent.classList.add('lb-hold');
                            document.body.classList.add('lightbox--active');

                            parents(this.DOM.el, '.e-parent')[0].style.zIndex = 9999;

                            this.flip = Flip.from(state, {
                                duration: 1,
                                absolute: true,
                                absoluteOnLeave: true,
                                ease: 'expo.inOut',
                                onReverseComplete: () => {

                                    this.parent.classList.remove('lb-hold');
                                    this.DOM.el.classList.remove('lightbox-open');
                                    this.DOM.el.classList.remove('lightbox--started');
                                    document.body.classList.remove('lightbox--active');
                                    this.DOM.el.classList.remove('vid--interracted');
                                    parents(this.DOM.el, '.e-parent')[0].style.zIndex = 'auto';

                                    gsap.set([this.DOM.el, this.DOM.el.querySelector('.plyr--video')], {
                                        clearProps: 'all'
                                    })

                                    this.flip.kill();

                                },
                                onComplete: () => {
                                    this.DOM.el.classList.add('lightbox--started');
                                }
                            });

                            this.DOM.el.classList.remove('autoplay--running');
                            this.DOM.el.classList.add('vid--playing');
                            this.player.muted = false;
                            // this.autoplay ? this.player.restart() : this.player.play();


                        } else {

                            this.DOM.el.classList.remove('autoplay--running');
                            this.DOM.el.classList.add('vid--playing');
                            this.player.muted = false;
                            this.autoplay ? this.player.restart() : this.player.play();
                        }

                    } else {

                        this.player.play();
                        this.DOM.el.classList.add('vid--playing');
                        this.DOM.el.classList.remove('vid--paused');


                    }

                }, false);

            }


            this.player.on('pause', (event) => {

                if (this.hideElements) {

                    gsap.to(document.querySelectorAll(this.hideElements), {
                        opacity: 1,
                        pointerEvents: 'auto'
                    })
                }


                this.DOM.el.classList.remove('vid--playing');
                this.DOM.el.classList.add('vid--paused');

            })

            this.player.on('play', (event) => {


                if (this.DOM.el.classList.contains('vid--interracted')) {

                    if (this.hideElements) {
                        gsap.to(document.querySelectorAll(this.hideElements), {
                            opacity: 0,
                            pointerEvents: 'none'
                        })
                    }

                    this.DOM.el.classList.add('vid--playing');
                    this.DOM.el.classList.remove('vid--paused');

                }

            })

            this.player.once('playing', (event) => {
                plyrInstances.push(this.player);

                if (plyrInstances.length == players.length) {
                    document.dispatchEvent(new CustomEvent("playersReady"));
                }

                if (this.playScroll || this.observer) {
                    this.player.pause();
                    this.player.currentTime = 0;
                }

                if (this.autoplay && this.observer) {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                this.player.play();
                            } else {
                                this.player.pause();
                            }
                        });
                    }, {
                        threshold: 0.25
                    });

                    observer.observe(this.DOM.el);
                }

                if (parents(this.DOM.el, '.swiper-container').length && !parents(this.DOM.el, '.pe--slider').length) {

                    let domEl = this.DOM.el,
                        slide = parents(this.DOM.el, '.swiper-slide')[0],
                        player = this.player,
                        swiperCont = parents(this.DOM.el, '.swiper-container')[0].swiper;

                    if (!slide.classList.contains('swiper-slide-active')) {
                        this.player.pause();
                    } else if (slide.classList.contains('swiper-slide-0') && parents(this.DOM.el, '.swiper-container')[0].dataset.autoplay === 'true') {
                        swiperCont.autoplay.start();
                        parents(this.DOM.el, '.swiper-container')[0].dispatchEvent(new CustomEvent("firsSlideVidReady"));
                    }

                    swiperCont.on('slideChangeTransitionEnd', function () {
                        if (slide.classList.contains('swiper-slide-active')) {
                            player.play();
                        } else {
                            player.pause();
                        }
                    });
                }

                if (parents(this.DOM.el, '.zeyna--masonry--layout').length) {
                    let parentMasonry = parents(this.DOM.el, '.zeyna--masonry--layout')[0];
                    var msnry = Masonry.data(parentMasonry);
                    msnry.layout();
                }

                // ScrollTrigger.update();
                if (this.muted && this.autoplay) {
                    this.player.muted = true;
                }
                this.DOM.el.classList.add('vid--initialized');

                if (this.lbHold) {
                    gsap.set(this.lbHold, {
                        height: this.DOM.el.offsetHeight
                    })
                }

                setTimeout(() => {

                    if (!parents(this.DOM.el, '.vid--no--ratio').length && this.player.ratio && !this.DOM.el.classList.contains('pe-self') && !this.DOM.el.classList.contains('pe-stream')) {

                        this.ratio = this.player.ratio.split(':')[0] / this.player.ratio.split(':')[1];

                        var parentRatio = this.parent.offsetWidth / this.parent.offsetHeight;

                        const $iframe = this.parent.querySelector('.plyr__video-wrapper');
                        const targetWidth = this.parent.offsetHeight * this.ratio;

                        if (parentRatio < this.ratio) {

                            gsap.set($iframe, {
                                width: targetWidth + 10,
                                height: this.parent.offsetHeight + 10,
                                x: -1 * (targetWidth - this.parent.offsetWidth) / 2
                            })

                        }

                    }



                }, 10);


            })

            if (this.lightbox || this.buttonOnly) {

                this.lightboxClose = () => {
                    this.player.pause();
                    this.DOM.el.classList.remove('lightbox--started');
                    this.flip.reverse();
                    this.player.muted = true;
                    this.player.loop = true;
                    this.player.autoplay = true;
                }

                window.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        this.lightboxClose();
                    }
                });

                this.DOM.el.querySelector('.pe--lightbox--close')
                    .addEventListener('click', this.lightboxClose);
            }

            this.player.on('ready', (event) => {
                if (parents(this.DOM.el, '.swiper-container').length) {
                    let swiperCont = parents(this.DOM.el, '.swiper-container')[0].swiper;
                    this.player.pause();
                }
            })

        }

    }

}