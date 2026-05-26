(function ($) {
    "use strict";

    const isRTL = document.documentElement.dir === "rtl";
    const isHorizontal = document.body.classList.contains('lenis-scroll-horizontal') ? true : false;

    // ============================================
    // PERFORMANCE UTILITIES - WIDGET SCRIPTS
    // ============================================
    let widgetRefreshTimeout;
    function throttledWidgetRefresh(delay = 200) {
        clearTimeout(widgetRefreshTimeout);
        widgetRefreshTimeout = setTimeout(() => {
            ScrollTrigger.refresh(true); s
        }, delay);
    }

    // Cleanup ScrollTriggers
    function cleanupScrollTriggers(selector) {
        ScrollTrigger.getAll().forEach(st => {
            if (st.trigger && st.trigger.closest(selector)) {
                st.kill(true);
            }
        });
    }

    // Active intervals/timers tracking
    const activeTimers = new Set();

    function setManagedInterval(callback, delay) {
        const id = setInterval(callback, delay);
        activeTimers.add(id);
        return id;
    }

    function clearManagedInterval(id) {
        clearInterval(id);
        activeTimers.delete(id);
    }

    function clearAllManagedTimers() {
        activeTimers.forEach(id => clearInterval(id));
        activeTimers.clear();
    }

    // ============================================
    // GLOBAL CLEANUP SYSTEM
    // ============================================
    function cleanupAllInstances() {
        // Clear all timers
        clearAllManagedTimers();

        // Destroy all Swiper instances
        if (window.swiperInstances) {
            window.swiperInstances.forEach((swiper, id) => {
                if (swiper && swiper.destroy) {
                    swiper.destroy(true, true);
                }
            });
            window.swiperInstances.clear();
        }

        // Cleanup ScrollTriggers (except persistent ones)
        ScrollTrigger.getAll().forEach(st => {
            if (st.vars.id !== 'fixedHeader' &&
                st.vars.id !== 'stickyHeader' &&
                st.vars.id !== 'fixedFooter') {
                st.kill(true);
            }
        });
    }

    // Cleanup on page unload
    if (window.barba) {
        document.addEventListener('barba:before', cleanupAllInstances);
    }
    window.addEventListener('beforeunload', cleanupAllInstances);

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

    var mobileQuery = window.matchMedia('(max-width: 450px)'),
        siteHeader = $('.site-header'),
        matchMedia = gsap.matchMedia(),
        isPhone = '(max-width: 450px)',
        isTablet = '(min-width: 450px) and (max-width: 900px)',
        isDesktop = '(min-width: 900px)';



    var cursor = document.getElementById('mouseCursor') ? document.getElementById('mouseCursor') : false,
        cursorText = cursor ? cursor.querySelector('.cursor-text') : false,
        cursorIcon = cursor ? cursor.querySelector('.cursor-icon') : false;


    var keys = {
        37: 1,
        38: 1,
        39: 1,
        40: 1
    };

    function preventDefault(e) {
        e.preventDefault();
    }

    function preventDefaultForScrollKeys(e) {
        if (keys[e.keyCode]) {
            preventDefault(e);
            return false;
        }
    }

    var supportsPassive = false;
    try {
        window.addEventListener("test", null, Object.defineProperty({}, 'passive', {
            get: function () {
                supportsPassive = true;
            }
        }));
    } catch (e) { }

    var wheelOpt = supportsPassive ? {
        passive: false
    } : false;
    var wheelEvent = 'onwheel' in document.createElement('div') ? 'wheel' : 'mousewheel';

    // call this to Disable
    function disableScroll() {
        if (zeynaLenis) {
            zeynaLenis.stop();
        } else {
            window.addEventListener('DOMMouseScroll', preventDefault, false); // older FF
            window.addEventListener(wheelEvent, preventDefault, wheelOpt); // modern desktop
            window.addEventListener('touchmove', preventDefault, wheelOpt); // mobile
            window.addEventListener('keydown', preventDefaultForScrollKeys, false);
        }
    }

    // call this to Enable
    function enableScroll() {

        if (zeynaLenis) {

            zeynaLenis.start();
        } else {
            window.removeEventListener('DOMMouseScroll', preventDefault, false);
            window.removeEventListener(wheelEvent, preventDefault, wheelOpt);
            window.removeEventListener('touchmove', preventDefault, wheelOpt);
            window.removeEventListener('keydown', preventDefaultForScrollKeys, false);
        }
    }

    function clearProps(target) {
        gsap.set(target, {
            clearProps: 'all'
        })
    }


    function wrapInner(element, wrapper) {

        var wrapperElement = document.createElement(wrapper.tagName);


        while (element.firstChild) {
            wrapperElement.appendChild(element.firstChild);
        }


        element.appendChild(wrapperElement);
    }

    function peSelectBox(item) {

        var label = item.querySelector('.pe--select--label'),
            items = item.querySelector('.pe--select--items'),
            options = items.querySelectorAll('.pe--select--item'),
            select = item.querySelector('select'),
            form = parents(item, 'form')[0];


        label.addEventListener('click', () => {

            if (form.querySelector('.open')) {
                form.querySelector('.open')
                form.querySelector('.open').classList.add('hide');
                form.querySelector('.open').classList.remove('open');
            }

            items.classList.toggle('hide');
            items.classList.toggle('open');
        })

        options.forEach(option => {
            option.addEventListener('click', () => {

                select.value = option.dataset.val;
                label.querySelector('span.pe--select--selection').innerHTML = option.innerHTML;
                items.classList.toggle('hide')
                items.classList.toggle('open')


            })
        })


    }

    function peCustomSelect(targets) {

        var selectWrappers = targets;
        var totalSelects = selectWrappers.length;


        for (var i = 0; i < totalSelects; i++) {
            var originalSelect = selectWrappers[i].getElementsByTagName("select")[0];
            var totalOptions = originalSelect.length;

            var selectedDiv = document.createElement("DIV");
            selectedDiv.setAttribute("class", "select-selected");
            selectedDiv.innerHTML = originalSelect.options[originalSelect.selectedIndex].innerHTML;
            selectedDiv.setAttribute('data-select', originalSelect.options[originalSelect.selectedIndex].innerHTML);
            selectWrappers[i].appendChild(selectedDiv);

            var optionsListDiv = document.createElement("DIV");
            optionsListDiv.setAttribute("class", "select-items select-hide");

            for (var j = 1; j < totalOptions; j++) {
                var optionDiv = document.createElement("DIV");
                optionDiv.innerHTML = originalSelect.options[j].innerHTML;

                optionDiv.addEventListener("click", function (e) {
                    var selectElement = this.parentNode.parentNode.getElementsByTagName("select")[0];
                    var totalOptionsInSelect = selectElement.length;
                    var selectedDivInThis = this.parentNode.previousSibling;

                    for (var k = 0; k < totalOptionsInSelect; k++) {
                        if (selectElement.options[k].innerHTML == this.innerHTML) {
                            selectElement.selectedIndex = k;
                            selectedDivInThis.innerHTML = this.innerHTML;
                            selectedDivInThis.setAttribute('data-select', this.innerHTML);

                            var previouslySelected = this.parentNode.getElementsByClassName("same-as-selected");
                            var totalSelected = previouslySelected.length;
                            for (var l = 0; l < totalSelected; l++) {
                                previouslySelected[l].removeAttribute("class");
                            }
                            this.setAttribute("class", "same-as-selected");

                            var event = new Event('change', { bubbles: true });
                            selectElement.dispatchEvent(event);

                            break;
                        }
                    }

                    selectedDivInThis.click();
                });
                optionsListDiv.appendChild(optionDiv);
            }
            selectWrappers[i].appendChild(optionsListDiv);

            selectedDiv.addEventListener("click", function (e) {
                e.stopPropagation();
                closeAllSelect(this);
                this.nextSibling.classList.toggle("select-hide");
                this.classList.toggle("select-arrow-active");
            });
        }

        function closeAllSelect(exceptThis) {
            var allOptionsDiv = document.getElementsByClassName("select-items");
            var allSelectedDivs = document.getElementsByClassName("select-selected");
            var totalOptionsDivs = allOptionsDiv.length;
            var totalSelectedDivs = allSelectedDivs.length;
            var openSelectIndexes = [];

            for (var i = 0; i < totalSelectedDivs; i++) {
                if (exceptThis == allSelectedDivs[i]) {
                    openSelectIndexes.push(i);
                } else {
                    allSelectedDivs[i].classList.remove("select-arrow-active");
                }
            }

            for (var i = 0; i < totalOptionsDivs; i++) {
                if (openSelectIndexes.indexOf(i) == -1) {
                    // allOptionsDiv[i].classList.add("select-hide");
                }
            }
        }

        document.addEventListener("click", closeAllSelect);
    }

    function infinitePage() {

        if (zeynaLenis) {

            zeynaLenis.options.infinite = true;

            if (window.barba) {
                barba.hooks.before(() => {
                    zeynaLenis.options.infinite = false;
                });
            }
        } else {
            // ============================================
            // OPTIMIZED: Infinite Scroll - GSAP ticker kullan
            // ============================================
            const lenis = new Lenis({
                smooth: true,
                infinite: true,
                smoothTouch: true,
            });

            // GSAP ticker kullan (duplicate RAF kaldırıldı)
            gsap.ticker.add((time) => {
                lenis.raf(time * 1000);
            });

            if (window.barba) {
                barba.hooks.before(() => {
                    lenis.destroy();
                    gsap.ticker.remove(lenis.raf);
                });
            }

        }

    }


    /////////////////////////
    //   Global Scripts   //
    /////////////////////////


    function peScrollButton(button, link = false) {

        if (!link) {
            var target = button.dataset.scrollTo,
                duration = button.dataset.scrollDuration;
        } else {
            var target = button.getAttribute('href'),
                duration = 1.5;
        }

        if (isNaN(parseFloat(target))) {

            if (!document.querySelector(target)) {
                return false;
            }

            ScrollTrigger.create({
                trigger: document.querySelector(target),
                start: 'top center',
                end: 'bottom center',
                onEnter: () => {
                    document.querySelector('.sb--active') && document.querySelector('.sb--active').classList.remove('sb--active');
                    button.classList.add('sb--active')
                },
                onEnterBack: () => {
                    document.querySelector('.sb--active') && document.querySelector('.sb--active').classList.remove('sb--active');
                    button.classList.add('sb--active')
                },

            })

        }

        button.addEventListener('click', () => {

            if (zeynaLenis) {
                target = isNaN(parseFloat(target)) ? target : parseFloat(target);
                zeynaLenis.scrollTo(target, {
                    duration: duration,
                })

            } else {
                gsap.to(window, {
                    duration: duration,
                    scrollTo: isNaN(target) ? $(target).offset().top : target,
                    ease: 'power2',
                });
            }

        })

    }

    function resetCursor() {

        var mouseCursor = document.getElementById('mouseCursor');
        if (mouseCursor) {
            cursor.classList.remove('cursor--default')
            cursor.classList.remove('cursor--text');
            cursor.classList.remove('cursor--hidden');
            cursor.classList.remove('cursor--icon');
            cursor.classList.remove('cursor--drag');
            cursor.classList.remove('dragging');
            cursor.classList.remove('dragging--right');
            cursor.classList.remove('dragging--left');
            cursorText.innerHTML = '';
            cursorIcon.innerHTML = '';

            if (mouseCursor.classList.contains('cursor--plus')) {
                gsap.to("#cursorPlus", {
                    morphSVG: {
                        shape: "#cursorPlus",
                    },
                    duration: .75,
                    ease: "expo.inOut"
                })
            }
        }
    }

    if (window.barba) {

        barba.hooks.before(() => {

            resetCursor();
        });

        barba.hooks.afterEnter(() => {

            resetCursor();
        });

    }

    function peCursorInteraction(target, style = 'default') {
        var mouseCursor = document.getElementById('mouseCursor');
        if (mouseCursor) {
            // Types: default/text/icon

            var type = target.dataset.cursorType ? target.dataset.cursorType : style,
                text = target.dataset.cursorText,
                icon = target.dataset.cursorIcon;

            target.addEventListener('mouseenter', () => {

                if (type === 'hidden') {
                    mouseCursor.classList.add('cursor--hidden');
                }

                if (!target.classList.contains('cursor--disabled')) {

                    if (type === 'default') {

                        cursor.classList.add('cursor--default');
                        if (mouseCursor.classList.contains('cursor--plus')) {
                            gsap.to("#cursorPlus", {
                                morphSVG: {
                                    shape: "#cursorPlusHover",
                                },
                                duration: .75,
                                ease: "expo.out"
                            })
                        }
                    }

                    if (type === 'text') {

                        cursor.classList.add('cursor--text');
                        cursorText.innerHTML = text;

                    }

                    if (type === 'icon') {

                        cursor.classList.add('cursor--icon');
                        cursorIcon.innerHTML = icon;

                    }
                }

                if (Draggable.get(target)) {

                    let draggable = Draggable.get(target)

                    cursor.classList.add('cursor--icon');
                    cursor.classList.add('cursor--drag');

                    draggable.addEventListener('press', (self) => {
                        cursor.classList.add('dragging');
                    })

                    draggable.addEventListener('release', (self) => {
                        cursor.classList.remove('dragging');
                    })

                }

            });

            target.addEventListener('mouseleave', () => resetCursor());
        }
    }




    function isPinnng(trigger, add) {

        if (!mobileQuery.matches) {
            if (add) {
                if (document.querySelector(trigger)) {
                    document.querySelector(trigger).classList.add('is-pinning')
                }

            } else {
                if (document.querySelector(trigger)) {
                    document.querySelector(trigger).classList.remove('is-pinning')
                }
            }

        }

    }

    function textCharAnimations(button) {

        if (!button.dataset.textHover || !button.dataset.textHover.startsWith('chars')) {
            return false;
        }

        if (button.classList.contains('initialized')) {
            return false;
        } else {
            button.classList.add('initialized');
        }

        function charsSplit() {

            let textHover = button.dataset.textHover;
            let textWrap = button.querySelector('.button--text--wrap');

            SplitText.create(textWrap, {
                type: "chars",
                charsClass: "button_char",
                tag: 'span',
                display: 'inline-block',
                autoSplit: true
            });

            textWrap.querySelectorAll('.button--chars--wrap').forEach(wrap => {

                if (textHover === 'chars-left' || textHover === 'chars-right') {
                    gsap.set(wrap, {
                        '--l': wrap.querySelectorAll('.button_char').length,
                    })
                }

                wrap.querySelectorAll('.button_char').forEach((char, i) => {
                    gsap.set(char, {
                        '--c': i,
                    })
                });

            })

        };

        if (gsap.getById('zeynaPageTransition')) {
            document.addEventListener('pageTransitionDone', () => charsSplit());

        } else {
            // document.fonts.ready.then(() => charsSplit());
            charsSplit()
        }

    }


    function pePopup(scope, wrapper, customTrigger = false) {

        let popButton = scope.querySelector('.pe--pop--button'),
            popup = scope.querySelector('.pe--styled--popup'),
            overlay = scope.querySelector('.pop--overlay'),
            close = scope.querySelector('.pop--close'),
            topSpacing = getComputedStyle(popup).getPropertyValue('--topSpacing');


        function popupact(open) {

            if (open) {

                if (parents(popup, '.pin-spacer').length) {
                    parents(popup, '.pin-spacer')[0].style.zIndex = '999999';
                }

                if (parents(popup, '.pinned_true').length) {
                    parents(popup, '.pinned_true')[0].style.transition = 'none';
                    parents(popup, '.pinned_true')[0].style.transform = 'none';
                }

                if (parents(popup, '.e-transform').length) {
                    parents(popup, '.e-transform')[0].style.transition = 'none';
                    parents(popup, '.e-transform')[0].style.transform = 'none';
                    parents(popup, '.elementor-widget-container')[0].style.transition = 'none';
                    parents(popup, '.elementor-widget-container')[0].style.transform = 'none';
                }

                scope.classList.contains('pop--disable--scroll--true') ? disableScroll() : '';
                wrapper.classList.add('pop--active');

                gsap.fromTo(popup, {
                    xPercent: scope.classList.contains('pop--behavior--center') || scope.classList.contains('pop--behavior--top') || scope.classList.contains('pop--behavior--bottom') ? -50 : scope.classList.contains('pop--behavior--left') ? -120 : scope.classList.contains('pop--behavior--right') ? 120 : 0,
                    yPercent: scope.classList.contains('pop--behavior--top') ? -200 : scope.classList.contains('pop--behavior--bottom') ? 200 : scope.classList.contains('pop--behavior--center') ? -50 : 0,
                    scale: scope.classList.contains('pop--behavior--center') ? 0.7 : 1,
                    opacity: scope.classList.contains('pop--behavior--center') ? 0 : 1,
                }, {
                    xPercent: scope.classList.contains('pop--behavior--center') || scope.classList.contains('pop--behavior--top') || scope.classList.contains('pop--behavior--bottom') ? -50 : 0,
                    yPercent: scope.classList.contains('pop--behavior--center') ? -50 : 0,
                    y: 0,
                    opacity: 1,
                    scale: 1,
                    visibility: 'visible',
                    duration: 1,
                    ease: 'power3.out',
                    overwrite: true
                });

            } else {

                let backTop = parseInt(topSpacing) ? -200 - parseInt(topSpacing) : -200;

                scope.classList.contains('pop--disable--scroll--true') ? enableScroll() : '';
                wrapper.classList.remove('pop--active');
                gsap.to(popup, {
                    xPercent: scope.classList.contains('pop--behavior--center') || scope.classList.contains('pop--behavior--top') || scope.classList.contains('pop--behavior--bottom') ? -50 : scope.classList.contains('pop--behavior--left') ? -120 : scope.classList.contains('pop--behavior--right') ? 120 : 0,
                    yPercent: scope.classList.contains('pop--behavior--top') ? backTop : scope.classList.contains('pop--behavior--bottom') ? 200 : scope.classList.contains('pop--behavior--center') ? -50 : 0,
                    scale: scope.classList.contains('pop--behavior--center') ? 0.7 : 1,
                    opacity: scope.classList.contains('pop--behavior--center') ? 0 : 1,
                    onComplete: () => {
                        gsap.set(popup, {
                            visibility: 'hidden',
                        })
                    },
                    duration: 1,
                    ease: 'power3.out',
                    overwrite: true
                })
            }
        }

        if (popButton) {
            popButton.addEventListener('click', () => popupact(true));
        }

        if (scope.classList.contains('pop--action--hover')) {
            popButton.addEventListener('mouseenter', () => popupact(true));
            popup.addEventListener('mouseleave', () => popupact(false));
        }

        if (customTrigger) {
            customTrigger.addEventListener('click', () => popupact(true));
        }

        if (overlay) {
            overlay.addEventListener('click', () => popupact(false));
        }

        close.addEventListener('click', () => popupact(false));

        window.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                if (wrapper.classList.contains('pop--active')) {
                    popupact(false)
                }
            }
        })

    }

    function peSwitcher(scope, switcher, grid, gridItems) {

        let items = switcher.querySelectorAll('.switch--item');

        function moveFollower(follower) {

            let active = switcher.querySelector('.switch--active');
            let activeRect = active.getBoundingClientRect();
            let switcherRect = switcher.getBoundingClientRect();

            gsap.to(follower, {
                x: activeRect.left - switcherRect.left,
                y: activeRect.top - switcherRect.top,
                width: activeRect.width,
                height: activeRect.height,
                duration: 0.75,
                ease: 'power3.out'
            });
        }

        if (switcher.querySelector('.switch--follower')) {
            moveFollower(switcher.querySelector('.switch--follower'))
        }

        items.forEach(item => {

            item.addEventListener('click', () => {

                scope.querySelector('.switch--active').classList.remove('switch--active')
                item.classList.add('switch--active');

                if (switcher.querySelector('.switch--follower')) {
                    moveFollower(switcher.querySelector('.switch--follower'));
                }

                let state = Flip.getState(gridItems),
                    cols = item.dataset.switchCols;

                gsap.set(grid, {
                    "--columns": cols
                })

                Flip.from(state, {
                    duration: 1,
                    ease: 'power3.inOut',
                    absolute: true,
                    absoluteOnLeave: true,
                })

            })

        })

    }

    function updateActiveCarouselItem(wrapper, itemsSelector, activeClass = 'active') {
        const items = wrapper.querySelectorAll(itemsSelector);
        const centerX = window.innerWidth / 2;
        const centerY = window.innerHeight / 2;

        let closestItem = null;
        let closestDistance = Infinity;

        items.forEach(item => {
            const rect = item.getBoundingClientRect();
            const itemCenterX = rect.left + rect.width / 2;
            const itemCenterY = rect.top + rect.height / 2;

            const distance = Math.sqrt(
                Math.pow(itemCenterX - centerX, 2) + Math.pow(itemCenterY - centerY, 2)
            );

            if (distance < closestDistance) {
                closestDistance = distance;
                closestItem = item;
            }
        });

        items.forEach(item => item.classList.remove(activeClass));

        if (closestItem) {
            closestItem.classList.add(activeClass);
        }
    }

    function peTextHover(scope) {

        if (!scope.classList.contains('text--hover')) {
            return false;
        }

        if (!scope.querySelector('[data-animate="true"]')) {

            SplitText.create(scope.querySelector('[data-text-hover="true"]'), {
                type: "chars",
                charsClass: "anim_char-mask",
                tag: "span",
                autoSplit: false,
            });

        }

        let text = scope.querySelector('[data-text-hover="true"]'),
            effect = text.dataset.hoverEffect,
            chars = scope.querySelectorAll('.anim_char-mask');


        if (effect === 'rotateX' || effect === 'rotateY') {

            chars.forEach(char => {

                let rotate = gsap.to(char, {
                    rotateX: effect === 'rotateX' ? 360 : 0,
                    rotateY: effect === 'rotateY' ? 360 : 0,
                    duration: 1,
                    ease: 'power3.out',
                    paused: true,
                    overwrite: true,
                    onStart: () => {

                        char.classList.add('animating');
                        setTimeout(() => {
                            char.classList.remove('animating');
                        }, 500);
                    },
                });

                char.addEventListener('mouseenter', () => {

                    char.classList.contains('animating') ? '' : rotate.restart();
                })

            });

        }

        if (effect === 'scaleX' || effect === 'scaleY') {

            chars.forEach((char, i) => {

                char.classList.add('hover_char-' + i);

                char.addEventListener('mouseenter', () => {

                    if (char.previousElementSibling) {
                        gsap.to(char.previousElementSibling, {
                            scaleY: effect === 'scaleY' ? 1.1 : 1,
                            scaleX: effect === 'scaleX' ? 1.1 : 1,
                            duration: .3
                        })
                    }

                    gsap.to(char, {
                        scaleY: effect === 'scaleY' ? 1.2 : 1,
                        scaleX: effect === 'scaleX' ? 1.2 : 1,
                        duration: .3
                    })

                    if (char.nextElementSibling) {
                        gsap.to(char.nextElementSibling, {
                            scaleY: effect === 'scaleY' ? 1.1 : 1,
                            scaleX: effect === 'scaleX' ? 1.1 : 1,
                            duration: .3
                        })
                    }
                })

                char.addEventListener('mouseleave', () => {
                    gsap.to(chars, {
                        scaleY: 1,
                        scaleX: 1,
                        duration: .3
                    })
                })

            });

        }

    }

    function zeynaLighbox(parent, wrapper, images) {

        var holder = parents(parent, 'div')[0],
            settings = parent.querySelector('.lightbox--sett'),
            style = settings.dataset.style,
            carouselArr = [],
            thumbsArr = [],
            stagger,
            dragVal;


        parent.classList.add('zeyna--lightbox');
        parent.classList.add('lightbox--' + style);

        gsap.set(holder, {
            height: parent.getBoundingClientRect().height
        });


        images.forEach((image, i) => {

            image.classList.add('lightbox--image');
            image.classList.add('lightbox--image_' + i);
            image.dataset.index = i;

            image.addEventListener('click', () => {

                if (!parent.classList.contains('lightbox--active')) {

                    disableScroll();
                    image.classList.add('active');

                    lightboxThumbsUpdate(i)

                    if (Draggable.get(wrapper)) {
                        let drag = Draggable.get(wrapper);
                        dragVal = drag.x;

                        drag.disable();
                        gsap.to(wrapper, {
                            x: 0,
                            y: 0,
                            duration: 2,
                            ease: 'expo.inOut',
                        })
                        // clearProps(images);
                    }

                    let state = Flip.getState(images, {
                        props: ['height']
                    });

                    parent.classList.add('lightbox--active');

                    if (style === 'slideshow') {
                        gsap.set(images, {
                            height: mobileQuery.matches ? '75vh' : '90vh',
                            width: mobileQuery.matches ? '90%' : 'auto',
                            position: 'absolute',
                            top: '50%',
                            left: '50%',
                            xPercent: -50,
                            yPercent: -50
                        })

                        stagger = {
                            grid: [1, images.length],
                            from: i,
                            amount: .55,
                        };

                    } else if (style === 'carousel') {

                        gsap.set(images, {
                            height: '100%',
                            width: 'auto',
                        });

                        images.forEach((im, i) => {
                            carouselArr[i] = im.getBoundingClientRect().left - (window.outerWidth / 2) + (im.getBoundingClientRect().width / 2);
                        })
                        gsap.set(wrapper, {
                            x: carouselArr[i] * -1
                        })

                        stagger = 0;
                    }

                    Flip.from(state, {
                        duration: 1,
                        stagger: stagger,
                        ease: 'expo.inOut',
                        absolute: true,
                        absoluteOnLeave: true,
                        onComplete: () => {
                            parent.classList.add('lightbox--nav--init');
                        }
                    })
                }
            })
        })


        function lightboxNavigate(direction) {

            let activeItem = parent.querySelector('.lightbox--image.active'),
                activeIndex = activeItem.dataset.index;

            if (direction === 'prev' && activeIndex != 0) {
                activeItem.classList.remove('active');
                parent.querySelector('.lightbox--image_' + (parseInt(activeIndex) - 1)).classList.add('active');

                if (style === 'carousel') {
                    gsap.to(wrapper, {
                        x: carouselArr[parseInt(activeIndex) - 1] * -1,
                        duration: 1,
                        ease: 'power4.out'
                    })
                }

                lightboxThumbsUpdate(parseInt(activeIndex) - 1);

            } else if (direction === 'next' && activeIndex != (images.length - 1)) {
                activeItem.classList.remove('active');
                parent.querySelector('.lightbox--image_' + (parseInt(activeIndex) + 1)).classList.add('active');

                if (style === 'carousel') {
                    gsap.to(wrapper, {
                        x: carouselArr[parseInt(activeIndex) + 1] * -1,
                        duration: 1,
                        ease: 'power4.out'
                    })
                }


                lightboxThumbsUpdate(parseInt(activeIndex) + 1);
            }
        }

        if (settings.dataset.mousewheel === 'true') {
            var scrollingDirection = 0; //idle
            var lastScroll = 9999;
            var scrollIdleTime = 100; // time interval that we consider a new scroll event

            wrapper.addEventListener('wheel', function (e) {
                if (parent.classList.contains('lightbox--active')) {
                    var delta = e.deltaY;
                    var timeNow = performance.now();
                    if (delta > 0 && (scrollingDirection != 1 || timeNow > lastScroll + scrollIdleTime)) {
                        lightboxNavigate('prev')
                        scrollingDirection = 1;
                    } else if (delta < 0 && (scrollingDirection != 2 || timeNow > lastScroll + scrollIdleTime)) {
                        lightboxNavigate('next')
                        scrollingDirection = 2;
                    }
                    lastScroll = timeNow;
                }

            });

        }

        if (parent.querySelector('.zeyna--lightbox--thumbs')) {

            let thumbs = parent.querySelectorAll('.zeyna--lightbox--thumb');
            thumbs.forEach((thumb, i) => {

                thumbsArr[i] = {
                    x: thumb.offsetLeft - (window.outerWidth / 2) + (thumb.getBoundingClientRect().width / 2),
                    y: thumb.offsetTop - (window.outerHeight / 2) + (thumb.getBoundingClientRect().height)
                };

                thumb.addEventListener('click', () => {
                    let index = thumb.dataset.index;
                    lightboxThumbsUpdate(index);
                    thumbs.forEach(thmb => thmb.classList.remove('active'));
                    thumb.classList.add('active');

                    images.forEach(image => image.classList.remove('active'));
                    parent.querySelector('.lightbox--image_' + index).classList.add('active');

                    if (style === 'carousel') {
                        gsap.to(wrapper, {
                            x: carouselArr[index] * -1,
                            duration: 1,
                            ease: 'power4.out'
                        })
                    }
                })
            })
        }


        function lightboxThumbsUpdate(active) {

            if (parent.querySelector('.zeyna--lightbox--thumbs')) {

                let thumbsWrapper = parent.querySelector('.zeyna--lightbox--thumbs'),
                    thumbs = parent.querySelectorAll('.zeyna--lightbox--thumb'),
                    thumbsWidth = thumbsWrapper.getBoundingClientRect().width,
                    thumbsHeight = thumbsWrapper.getBoundingClientRect().height,
                    direction = window.getComputedStyle(thumbsWrapper).getPropertyValue('flex-direction');

                thumbs.forEach(thmb => thmb.classList.remove('active'));
                parent.querySelector('.lightbox--thumb_' + active).classList.add('active');

                if (thumbsWidth > window.outerWidth || thumbsHeight > window.outerHeight) {
                    gsap.to(thumbsWrapper, {
                        x: direction === 'row' ? thumbsArr[active].x * -1 : 0,
                        y: direction === 'column' ? thumbsArr[active].y * -1 : 0,
                        ease: 'power4.out',
                        duration: 1
                    })
                }

            }

        }

        if (parent.querySelector('.zeyna--lightbox--prev')) {
            let prev = parent.querySelector('.zeyna--lightbox--prev'),
                next = parent.querySelector('.zeyna--lightbox--next');

            prev.addEventListener('click', () => lightboxNavigate('prev'));
            next.addEventListener('click', () => lightboxNavigate('next'));
        }


        if (parent.querySelector('.zeyna--lightbox--button')) {
            parent.querySelector('.zeyna--lightbox--button').addEventListener('click', () => {
                wrapper.querySelector('.active').click();
            })
        }

        function lightboxClose() {

            let state = Flip.getState(images, {
                props: ['height']
            });

            clearProps(images);
            clearProps(wrapper);
            parent.classList.remove('lightbox--active');

            if (Draggable.get(wrapper)) {
                let drag = Draggable.get(wrapper);

                gsap.to(wrapper, {
                    x: dragVal,
                    duration: 1,
                    ease: 'expo.inOut',
                    onComplete: () => {
                        drag.enable();
                        parent.querySelector('.lightbox--image.active').classList.remove('active');
                        updateActiveCarouselItem(wrapper, '.product--gallery--image');
                    }
                })
            }

            Flip.from(state, {
                duration: 1,
                ease: 'power4.inOut',
                absolute: true,
                absoluteOnLeave: true,
                onStart: () => {
                    parent.classList.remove('lightbox--nav--init');
                    enableScroll();
                }
            })

        }

        let close = parent.querySelector('.zeyna--lightbox--close');
        close.addEventListener('click', () => lightboxClose());

        window.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                lightboxClose();
            }
        })

    }



    function zeyna_CheckoutPage() {

        if (!document.querySelector('.zeyna--checkout--wrapper')) {
            return false;
        }

        var accordion = document.querySelector('.checkout-type-accordion'),
            tabs = document.querySelector('.checkout-type-tabs');

        if (document.querySelector('.zeyna--checkout--login')) {
            let checkoutLogin = document.querySelector('.zeyna--checkout--login');

            if (checkoutLogin.querySelector('.scag--button')) {
                let button = checkoutLogin.querySelector('.scag--button'),
                    formCol = document.querySelector('.zeyna--checkout--form .form--col');


                button.addEventListener('click', () => {

                    gsap.to(checkoutLogin, {
                        opacity: 0,
                        onComplete: () => {
                            checkoutLogin.style.display = 'none'
                        }
                    })

                    gsap.to(formCol, {
                        opacity: 1,
                    })

                })
            }


        }

        if (tabs) {

            const sourceElement = document.querySelector('.zeyna--checkout--accordion');
            const targetElement = document.querySelector('.zeyna--checkout--tabs--titles');
            var wrapper = document.querySelector('.form--col');

            const titles = sourceElement.querySelectorAll('.checkout--accordion--title');


            titles.forEach((title, i) => {

                title.classList.add('title__' + i);
                title.setAttribute('data-index', i);

                const clone = title.cloneNode(true);
                clone.classList.add('title--clone');

                clone.addEventListener('click', () => title.click())
                targetElement.appendChild(clone);
            });

            if (targetElement.getBoundingClientRect().width > wrapper.getBoundingClientRect().width) {

                let paddingRight = window.getComputedStyle(wrapper).getPropertyValue('padding-right'),
                    endVal = -targetElement.getBoundingClientRect().width + wrapper.getBoundingClientRect().width - (parseFloat(paddingRight) * 3);

                Draggable.create(targetElement, {
                    id: 'checkoutTabTitles',
                    type: 'x',
                    bounds: {
                        minX: 0,
                        maxX: endVal,
                    },
                    lockAxis: true,
                    dragResistance: 0.5,
                    inertia: true,
                    allowContextMenu: true
                });

            }

        }

        function updateTitles() {

            let clones = document.querySelectorAll('.title--clone'),
                fields = document.querySelector('.zeyna--checkout--accordion');

            clones.forEach((clone, i) => {

                let index = clone.dataset.index,
                    findTitle = fields.querySelector('.title__' + i);

                parents(findTitle, '.active').length ? clone.classList.add('active') : clone.classList.remove('active');
                parents(findTitle, '.is--filled').length ? clone.classList.add('is--filled') : clone.classList.remove('is--filled');

            })
        }

        if (tabs || accordion) {

            setTimeout(() => {

                $('.zeyna--checkout--accordion').find('input').each(function () {

                    $(this).trigger('change');

                });

                let items = document.querySelectorAll('.checkout--accordion--field');

                function areAllItemsFilled() {
                    return Array.from(items).every(item => {
                        if (item.classList.contains('field--payment')) {
                            return true;
                        }
                        return item.classList.contains('is--filled');
                    });
                }

                setTimeout(() => {
                    if (areAllItemsFilled()) {
                        document.querySelector('.field--payment').classList.add('is--filled');
                    }
                    tabs ? updateTitles() : '';
                }, 1000);


                items.forEach((item, i, filled = false) => {

                    let title = item.querySelector('.checkout--accordion--title'),
                        content = item.querySelector('.checkout--accordion--content'),
                        button = item.querySelector('.zeyna--accordion--button'),
                        rows = item.querySelectorAll('.form-row'),
                        inputs = item.querySelectorAll('input');


                    function checkValidates() {
                        return Array.from(rows).every(row =>
                            row.classList.contains('validate-required') &&
                            row.classList.contains('woocommerce-validated') ||
                            item.querySelector('.zeyna--address--card')
                        );
                    }

                    item.classList.add('item__' + i);

                    function checkValidation() {

                        if (checkValidates()) {
                            item.classList.add('is--filled')
                        } else {
                            item.classList.remove('is--filled')
                        }
                        tabs ? updateTitles() : '';

                    }
                    checkValidation();

                    title.addEventListener('click', () => {

                        let state = Flip.getState(document.querySelectorAll('.checkout--accordion--content'),);

                        document.querySelector('.checkout--accordion--field.active').classList.remove('active');
                        item.classList.add('active');

                        Flip.from(state, {
                            duration: 1,
                            ease: 'power3.inOut',
                            absolute: true,
                            absoluteOnLeave: true
                        })

                    })

                    inputs.forEach(input => {
                        input.addEventListener('blur', () => {
                            checkValidation();
                        })
                    });

                    if (button) {
                        button.addEventListener('click', butt => {

                            let nextItem = document.querySelector('.item__' + (i + 1)),
                                nextContent = nextItem.querySelector('.checkout--accordion--content');
                            let state = Flip.getState(document.querySelectorAll('.checkout--accordion--content'),);

                            item.classList.remove('active');
                            nextItem.classList.add('active');

                            Flip.from(state, {
                                duration: 1,
                                ease: 'power3.inOut',
                                absolute: true,
                                absoluteOnLeave: true
                            })

                        })
                    }

                })

            }, 2000);

        }

        var reviewTable = document.querySelector('.woocommerce-checkout-review-order-table');

        if (reviewTable) {

            let couponInput = reviewTable.querySelector('#zeyna_coupon_code'),
                formCoupon = document.querySelector('#coupon_code'),
                formButton = document.querySelector('.wc--coupon--button'),
                couponButton = reviewTable.querySelector('.zeyna--coupon--button');

            couponButton.addEventListener('click', () => {
                formCoupon.value = couponInput.value;
                formButton.click();
            })

        }

        let orderCol = document.querySelector('.order--col');

        if (orderCol) {

            ScrollTrigger.getById('cartPin') ? ScrollTrigger.getById('cartPin').kill(true) : '';

            ScrollTrigger.create({
                trigger: document.body,
                start: 0,
                end: 'bottom top',
                pin: orderCol,
                id: 'cartPin'
            })

            matchMedia.add({
                isMobile: "(max-width: 570px)"
            }, (context) => {

                let {
                    isMobile
                } = context.conditions;

                ScrollTrigger.getById('cartPin') ? ScrollTrigger.getById('cartPin').kill(true) : '';

            });


        }

        if (document.querySelectorAll('.zeyna--address--card').length) {

            var cards = document.querySelectorAll('.zeyna--address--card');

            for (let i = 0; i < cards.length; i++) {

                let editButton = cards[i].querySelector('.address-card--edit');

                editButton.addEventListener('click', () => {
                    let form = document.querySelector(editButton.dataset.edit);

                    let state = Flip.getState(form);

                    if (editButton.classList.contains('active')) {
                        editButton.classList.remove('active')
                        form.style.height = 0;
                    } else {
                        editButton.classList.add('active')
                        form.style.height = 'auto';
                    }

                    Flip.from(state, {
                        duration: 1,
                        ease: 'power3.inOut',
                        absolute: false,
                        absoluteOnLeave: false
                    })

                })

            }


        }


    }

    function elementorMatches(bpName, callback) {
        const bps = elementorFrontend.config.responsive.activeBreakpoints;
        if (!bps || !bps[bpName]) {
            console.warn(`Undefined: ${bpName}`);
            return;
        }

        const bp = bps[bpName];
        const mq =
            bp.direction === "max"
                ? `(max-width: ${bp.value}px)`
                : `(min-width: ${bp.value}px)`;

        const mql = window.matchMedia(mq);

        if (mql.matches) callback(mql);

        mql.addEventListener("change", e => {
            if (e.matches) callback(e);
        });
    }


    function peSlider(scope) {
        var slider = scope.querySelector('.pe--slider'),
            container = scope.querySelector('.swiper-container'),
            wrapper = scope.querySelector('.swiper-wrapper'),
            settings = JSON.parse(slider.dataset.settings),
            mouseWheel, autoplay, pagination, paginationEl, scrollbar;

        if (settings.mouseWheel) {
            mouseWheel = {
                invert: settings.mouseWheelInvert
            }
        } else {
            mouseWheel = false;
        }

        if (settings.autoplay) {
            autoplay = {
                delay: settings.autoplayDelay
            }
        } else {
            autoplay = false;
        }

        if (document.querySelector('.fraction-for-' + settings.id)) {
            pagination = 'fraction';
            paginationEl = document.querySelector('.fraction-for-' + settings.id);
        } else if (document.querySelector('.progressbar-for-' + settings.id)) {
            pagination = 'progressbar';
            paginationEl = document.querySelector('.progressbar-for-' + settings.id);
        }

        if (document.querySelector('.scrollbar-for-' + settings.id)) {
            scrollbar = {
                el: document.querySelector('.scrollbar-for-' + settings.id),
                hide: false,
                draggable: true
            }
        } else {
            scrollbar = false;
        }

        var interleaveOffset = 1;
        if (settings.parallax) {
            interleaveOffset = settings.parallaxStrength;
        }


        var sliderOptions = {
            slidesPerView: settings.slidesPerView,
            speed: settings.speed,
            spaceBetween: settings.spaceBetween,
            grabCursor: true,
            effect: settings.effect,
            mousewheel: mouseWheel,
            autoplay: autoplay,
            centeredSlides: settings.centeredSlides,
            loop: settings.loop,
            slideToClickedSlide: scope.classList.contains('slider--highlight--active') ? true : false,
            direction: settings.direction,
            parallax: settings.parallax ? true : false,
            watchSlideProgress: settings.parallax ? true : false,
            on: {
                progress: function () {

                    if (!settings.parallax) {
                        return false;
                    };

                    let swiper = this;

                    for (let i = 0; i < swiper.slides.length; i++) {
                        let slideProgress = swiper.slides[i].progress,
                            innerOffset = swiper.height * interleaveOffset,
                            innerTranslate = slideProgress * innerOffset;

                        if (sliderOptions.direction === 'vertical') {
                            swiper.slides[i].querySelector(".slide-bgimg").style.transform =
                                "translateY(" + innerTranslate + "px)";
                        } else {
                            swiper.slides[i].querySelector(".slide-bgimg").style.transform =
                                "translateX(" + innerTranslate + "px)";
                        }
                    }

                },
                setTransition: function (speed) {

                    if (!settings.parallax) {
                        return false;
                    };

                    let swiper = this;
                    for (let i = 0; i < swiper.slides.length; i++) {

                        if (!settings.playOnScroll) {
                            swiper.slides[i].style.transition = speed + "ms";
                            swiper.slides[i].querySelector(".slide-bgimg").style.transition = settings.speed + "ms";
                        }
                    }

                },
            },
            freeMode: {
                enabled: settings.freeMode,
                minimumVelocity: 0.2,
                sticky: settings.freeModeSnap,
            },
            navigation: {
                nextEl: '.next-for-' + settings.id,
                prevEl: '.prev-for-' + settings.id,
            },
            pagination: {
                type: pagination,
                el: paginationEl,
                dynamicBullets: true,
                clickable: true,
            },
            scrollbar: scrollbar,
            fadeEffect: {
                crossFade: true
            },
            cubeEffect: {
                slideShadows: false,
            },
            coverflowEffect: {
                rotate: 45,
                slideShadows: false,
            },
            creativeEffect: {
                prev: { translate: [0, 0, -400] },
                next: { translate: ['100%', 0, 0] },
            },
            breakpointsBase: 'container'
        };

        console.log(settings)

        if (settings.breakpoints) {



            const points = settings.breakpoints;

            const breakpoints = Object.keys(points)
                .map(Number)
                .sort((a, b) => a - b);

            for (let br of breakpoints) {
                const match = window.matchMedia(`(max-width: ${br}px)`);

                if (match.matches) {
                    const bp = points[br];

                    const slidesKey = Object.keys(bp).find(k => k.endsWith('slidesPerView'));
                    const spaceKey = Object.keys(bp).find(k => k.endsWith('spaceBetween'));
                    const dirKey = Object.keys(bp).find(k => k.endsWith('direction'));

                    if (slidesKey) {
                        sliderOptions.slidesPerView = bp[slidesKey];
                        //   console.log(`Matched slidesPerView (${br}):`, bp[slidesKey]);
                    }

                    if (spaceKey) {
                        sliderOptions.spaceBetween = bp[spaceKey];
                        //   console.log(`Matched spaceBetween (${br}):`, bp[spaceKey]);
                    }

                    if (dirKey) {
                        sliderOptions.direction = bp[dirKey];
                        //   console.log(`Matched direction (${br}):`, bp[dirKey]);
                    }

                    break;
                }
            }



        }


        // ============================================
        // OPTIMIZED: Swiper instance tracking for cleanup
        // ============================================
        var slider = new Swiper(container, sliderOptions);

        // Store instance for cleanup
        if (!window.swiperInstances) window.swiperInstances = new Map();
        window.swiperInstances.set(scope.dataset.id, slider);

        if (settings.playOnScroll) {

            const existingST = ScrollTrigger.getById('slider_' + scope.dataset.id);
            if (existingST) existingST.kill(true);

            var start = settings.itemRefStart + ' ' + settings.windowRefStart;
            var end = settings.itemRefEnd + ' ' + settings.windowRefEnd;
            var trigger = scope;

            if (settings.pin === 'true') {
                end = settings.itemRefEnd + '+=' + settings.speed + ' ' + settings.windowRefEnd;
            }


            if (settings.pinTarget) {
                trigger = document.querySelector(settings.pinTarget);

                ScrollTrigger.addEventListener("refreshInit", () => {
                    ScrollTrigger.getAll().some(trigger => {
                        if (trigger.trigger === document.querySelector(settings.pinTarget) && trigger.pin) {

                            start = trigger.start;
                            end = trigger.end;


                        };
                    });
                });

            }

            let sc = ScrollTrigger.create({
                trigger: trigger,
                id: 'slider_' + scope.dataset.id,
                start: start,
                end: end,
                markers: false,
                invalidateOnRefresh: true,
                pin: settings.pin === 'true' ? true : false,
                pinSpacing: settings.pin && settings.pinTarget ? 'padding' : false,
                // markers: true,
                onUpdate: self => {
                    slider.setProgress(self.progress, 0)
                }
            })


            const elementorBreakpoints = elementorFrontend.breakpoints.getActiveBreakpointsList();

            elementorBreakpoints.forEach(point => {
                elementorMatches(point, () => {
                    if (scope.classList.contains('pin-disabled-' + point + '-yes')) {
                        sc.kill();
                        var slider = new Swiper(container, sliderOptions);
                    }
                });

            });

        }

    }

    window.addEventListener('elementor/frontend/init', function () {

        if (document.body.classList.contains('e-preview--show-hidden-elements')) {

            document.body.setAttribute('data-barba-prevent', 'all');

        }

        const elementorBreakpoints = elementorFrontend.breakpoints.getActiveBreakpointsList();



        elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    id = scope.dataset.id;

                // Reset on Editor 
                if (document.body.classList.contains('e-preview--show-hidden-elements')) {
                    if (ScrollTrigger.getById(id)) {
                        ScrollTrigger.getById(id).kill();
                    }
                }
                // Reset on Editor 
                var containerBg = document.querySelector('.bg--for--' + id);
                containerBg ? scope.prepend(containerBg) : '';


                if (scope.classList.contains('stack--inners')) {

                    let classList = scope.className,
                        classes = classList.split(' '),
                        targetClass = classes.find(cls => cls.startsWith('pin_container')),
                        trigger = targetClass ? document.querySelector(targetClass.substring("pin_container_".length)) : parents(scope, '.elementor-element')[0],
                        start = scope.dataset.pinStart,
                        end = scope.dataset.pinEnd,
                        pinMobile = scope.dataset.pinMobile,
                        id = scope.dataset.id,
                        isScatter = scope.classList.contains('stack--type--scatter');

                    gsap.getById(id) ? gsap.getById(id).scrollTrigger.kill(true) : '';

                    const elements = scope.querySelectorAll(':scope > .elementor-element');

                    let tl = gsap.timeline({
                        id: id,
                        ease: 'none',
                        scrollTrigger: {
                            trigger: trigger,
                            // markers: true,
                            start: start,
                            end: end,
                            pin: true,
                            scrub: true,
                            pinSpacing: 'padding',
                            // anticipatePin: 0.1
                        }
                    });


                    elements.forEach((element, i) => {

                        if ((i !== 0 && !isScatter) || isScatter) {
                            i++
                            tl.fromTo(element, {
                                y: isScatter ? 0 : trigger.offsetHeight,
                                visibility: 'visible',
                                rotate: gsap.utils.random(-15, 15),
                                zIndex: isScatter ? elements.length - i : i,
                            }, {
                                y: isScatter ? -trigger.offsetHeight : 0,
                                rotate: gsap.utils.random(-15, 15),
                            })

                        }

                    })

                    if (!pinMobile) {

                        matchMedia.add({
                            isMobile: "(max-width: 550px)"

                        }, (context) => {

                            let {
                                isMobile
                            } = context.conditions;

                            gsap.getById(id) && gsap.getById(id).revert();

                        });

                    }

                }

                if (scope.querySelector('.container--bg')) {

                    if (scope.classList.contains('animated--gradient')) {

                        let bg = scope.querySelector('.container--bg');

                        const b1 = getComputedStyle(bg).getPropertyValue('--b1');
                        const b2 = getComputedStyle(bg).getPropertyValue('--b2');

                        let classes = scope.className.split(' '),
                            durationClass = classes.find(cls => cls.startsWith('gradient_animation_duration')),
                            duration = durationClass ? durationClass.substring("gradient_animation_duration_".length) : '.' + scope.classList[0];


                        gsap.fromTo(bg, {
                            background: b1
                        }, {
                            ease: "none",
                            duration: duration,
                            background: b2,
                            repeat: -1,
                            yoyo: true
                        });

                    }

                    if (scope.classList.contains('bg_fixed_true')) {

                        let bg = scope.querySelector('.container--bg'),
                            bgHeight = bg.getBoundingClientRect().height,
                            strength = bg.dataset.fixedStrength ? parseInt(bg.dataset.fixedStrength) : 100;

                        gsap.fromTo(bg, {
                            yPercent: strength * -1
                        }, {
                            yPercent: strength,
                            ease: 'none',
                            scrollTrigger: {
                                trigger: scope,
                                start: 'top bottom',
                                end: 'bottom top',
                                scrub: true,
                            }
                        })

                    }

                    if (scope.classList.contains('bg_pinned_true')) {


                        let bg = scope.querySelector('.container--bg');

                        gsap.set(bg, {
                            height: '100vh'
                        })

                        ScrollTrigger.create({
                            trigger: scope,
                            start: 'top top',
                            end: 'bottom bottom',
                            pin: bg,
                            pinSpacing: false,
                            invalidateOnRefresh: true,
                            anticipatePin: .1
                        })

                    }

                    if (scope.querySelector('.bg--image')) {

                        let bg = scope.querySelector('.container--bg'),
                            bgWrap = bg.querySelector('.cont--bg--wrap'),
                            img = bgWrap.querySelector('img');


                        if (scope.classList.contains('bg--behavior--parallax')) {

                            gsap.set(img, {
                                scale: 1.2
                            })

                            gsap.fromTo(img, {
                                yPercent: -10
                            }, {
                                yPercent: 10,
                                ease: 'none',
                                scrollTrigger: {
                                    trigger: bg,
                                    scrub: true,
                                    start: 'top bottom',
                                    end: 'bottom top'
                                }
                            })

                        } else if (scope.classList.contains('bg--behavior--zoom-in')) {

                            gsap.fromTo(img, {
                                scale: 1.25
                            }, {
                                scale: 1,
                                ease: 'power3.inOut',
                                scrollTrigger: {
                                    trigger: bg,
                                    scrub: true,
                                    start: 'top bottom',
                                    end: 'center center'
                                }
                            })

                        } else if (scope.classList.contains('bg--behavior--zoom-out')) {
                            gsap.fromTo(img, {
                                scale: 1
                            }, {
                                scale: 1.25,
                                ease: 'none',
                                scrollTrigger: {
                                    trigger: bg,
                                    scrub: true,
                                    start: 'top bottom',
                                    end: 'bottom top'
                                }
                            })
                        }
                    } else if (scope.querySelector('.bg--video')) {

                        if (scope.querySelector('.container--bg')) {

                            var bg = scope.querySelector('.container--bg'),
                                video = bg.querySelector('.pe-video');

                        } else {
                            var bg = document.querySelector('.bg--for--' + id),
                                video = bg.querySelector('.pe-video');
                        }

                        if (!document.body.classList.contains('window--initialized')) {
                            new peVideoPlayer(video);
                        } else {
                            // window.addEventListener("load", function () {
                            new peVideoPlayer(video);
                            // });
                        }

                    }

                }

                if (scope.classList.contains('animate--radius')) {

                    setTimeout(() => {
                        var ar1, ar2;
                        function animateRadius() {

                            let id = scope.dataset.id;
                            let id2 = scope.dataset.id + '2';

                            gsap.getById(id) ? gsap.getById(id).revert() : '';
                            gsap.getById(id2) ? gsap.getById(id2).revert() : '';

                            let radius = window.getComputedStyle(scope).borderRadius.split(' '),
                                width = scope.getBoundingClientRect().width,
                                padding = getComputedStyle(scope).getPropertyValue('padding-left');

                            // gsap.to(scope, {
                            //     borderRadius: '0px',
                            //     width: scope.classList.contains('integared--width') ? '100%' : '--width',
                            //     padding: padding,
                            //     ease: 'none',
                            //     scrollTrigger: {
                            //         trigger: scope,
                            //         start: 'top bottom',
                            //         end: 'top top',
                            //         scrub: true
                            //     }
                            // })

                            ar1 = gsap.fromTo(scope, {
                                borderRadius: radius,
                                padding: 0,
                                width: width - (parseInt(padding) * 2)
                            }, {
                                borderRadius: '0px',
                                width: width,
                                id: id,
                                padding: padding,
                                ease: 'none',
                                immediateRender: false,
                                scrollTrigger: {
                                    trigger: scope,
                                    start: 'top bottom',
                                    end: 'top top',
                                    scrub: 1
                                }
                            })

                            ar2 = gsap.to(scope, {
                                borderRadius: radius,
                                padding: 0,
                                id: id,
                                width: width - (parseInt(padding) * 2),
                                ease: 'none',
                                immediateRender: false,
                                scrollTrigger: {
                                    trigger: scope,
                                    start: 'bottom bottom',
                                    end: 'bottom center',
                                    scrub: 1
                                }
                            })

                            matchMedia.add({
                                isMobile: "(max-width: 550px)"

                            }, (context) => {

                                let {
                                    isMobile
                                } = context.conditions;

                                ar1.revert();
                                ar2.revert();



                            });



                        }
                        animateRadius();

                    }, 1);

                }

                if (scope.classList.contains('reveal--inners')) {

                    var classList = scope.className,
                        classes = classList.split(' '),
                        targetClass = classes.find(cls => cls.startsWith('pin_container')),
                        target = targetClass ? targetClass.substring("pin_container_".length) : '.' + scope.classList[0],
                        start = scope.dataset.pinStart,
                        end = scope.dataset.pinEnd,
                        pinMobile = scope.dataset.pinMobile,
                        id = scope.dataset.id,
                        anim = scope.classList.contains('reveal--anim--scale') ? 'scale' : scope.classList.contains('reveal--anim--fade') ? 'fade' : scope.classList.contains('reveal--anim--parallax') ? 'parallax' : scope.classList.contains('reveal--anim--slide') ? 'slide' : 'fade',
                        staggered = scope.classList.contains('reveal--subs--staggered') ? true : false;

                    // const el = document.querySelector('.elementor-element-338a26b');

                    // const hasPinnedTrigger = ScrollTrigger.getAll().some(trigger => {
                    //     return trigger.trigger === el && trigger.pin;
                    // });

                    // console.log(hasPinnedTrigger ? 'Bu element pinli bir ScrollTrigger\'a sahip.' : 'Bu element pinli değil.');



                    gsap.getById(id) ? gsap.getById(id).scrollTrigger.kill(true) : '';

                    if (scope.classList.contains('con--mode--edit') && document.body.classList.contains('e-preview--show-hidden-elements')) {
                        return false;
                    }

                    let tl = gsap.timeline({
                        id: id,
                        onStart: () => {
                            scope.classList.add('reveal--start');
                        },
                        scrollTrigger: {
                            trigger: targetClass ? target : scope,
                            markers: false,
                            start: start,
                            end: end,
                            pin: scope.classList.contains('cont--behavior--pin') ? true : false,
                            scrub: scope.classList.contains('cont--behavior--pin') || scope.classList.contains('cont--behavior--scrub') ? true : false,
                            // markers: true,
                            pinSpacing: 'padding',
                            // markers: true
                        }
                    })

                    var children = Array.from(scope.children);


                    children.forEach((con, i) => {
                        if (con.classList.contains('e-con')) {
                            con.classList.add('highlight--children');

                            let target = staggered ? con.querySelectorAll('.elementor-element') : con,
                                stagger = staggered ? 0.075 : 0;

                            const isFirstActive = scope.classList.contains('reveal--first--active');
                            const isLast = i === children.length - 1;

                            if (!scope.classList.contains('reveal--anim--items')) {

                                if (i === 0 && isFirstActive) {
                                    tl.fromTo(target, {
                                        opacity: 1,
                                        y: 0,
                                        scale: 1,
                                        // clipPath: 'inset(0% 0% 0% 0%)'
                                    }, {
                                        y: anim !== 'parallax' && anim !== 'slide' ? -150 : anim === 'slide' ? window.outerHeight * -1 : 1,
                                        opacity: anim !== 'parallax' && anim !== 'slide' ? 0 : 1,
                                        scale: anim === 'scale' ? 0.75 : 1,
                                        // clipPath: anim === 'parallax' ? 'inset(0% 0% 100% 0%)' : 'inset(0% 0% 0% 0%)',
                                        duration: 1,
                                        ease: 'power2.in',
                                        stagger: stagger
                                    }, 'label_' + i);

                                } else {

                                    tl.fromTo(target, {
                                        opacity: anim !== 'parallax' && anim !== 'slide' ? 0 : 1,
                                        y: anim !== 'parallax' && anim !== 'slide' ? 150 : anim === 'slide' ? window.outerHeight : 1,
                                        scale: anim === 'scale' ? 0.75 : 1,
                                        // clipPath: anim === 'parallax' ? 'inset(100% 0% 0% 0%)' : 'inset(0% 0% 0% 0%)',
                                    }, {
                                        opacity: 1,
                                        y: 0,
                                        scale: 1,
                                        // clipPath: 'inset(0% 0% 0% 0%)',
                                        duration: 1,
                                        ease: 'power2.out',
                                        stagger: stagger
                                    }, 'label_' + i);


                                    if (!isLast) {
                                        tl.fromTo(target, {
                                            opacity: 1,
                                            y: 0,
                                            scale: 1,
                                            // clipPath: 'inset(0% 0% 0% 0%)',
                                        }, {
                                            y: anim !== 'parallax' && anim !== 'slide' ? -150 : anim === 'slide' ? window.outerHeight * -1 : 1,
                                            opacity: anim !== 'parallax' && anim !== 'slide' ? 0 : 1,
                                            scale: anim === 'scale' ? 0.75 : 1,
                                            // clipPath: anim === 'parallax' ? 'inset(0% 0% 100% 0%)' : 'inset(0% 0% 0% 0%)',
                                            delay: 1,
                                            ease: 'power2.in',
                                            stagger: stagger
                                        }, 'label_' + i);
                                    }
                                }

                            }

                        }
                    });

                    elementorBreakpoints.forEach(point => {
                        elementorMatches(point, () => {
                            if (scope.classList.contains('pin-disabled-' + point + '-yes')) {
                                tl.revert();
                            }
                        });

                    });


                }

                function backwardContainer() {

                    ScrollTrigger.getById('backward_' + id) ? ScrollTrigger.getById('backward_' + id).kill(true) : '';
                    gsap.getById('backward_2_' + id) ? gsap.getById('backward_2_' + id).revert(true) : '';

                    if (scope.classList.contains('con--mode--edit') && document.body.classList.contains('e-preview--show-hidden-elements')) {
                        return false;
                    }


                    if (scope.classList.contains('backward__fade')) {

                        var backwardScFade = gsap.to(scope, {
                            id: 'backward_2_' + id,
                            y: -200,
                            "--op": 0.55,
                            scrollTrigger: {
                                trigger: scope,
                                start: scope.classList.contains('backward-start-bottom') ? 'bottom bottom' : 'top top',
                                end: 'bottom top',
                                scrub: true,
                            }
                        })

                    }

                    var backwardSc = ScrollTrigger.create({
                        trigger: scope,
                        start: scope.classList.contains('backward-start-bottom') ? 'bottom bottom' : 'top top',
                        end: 'bottom top',
                        scrub: true,
                        pin: true,
                        pinSpacing: false,
                        id: 'backward_' + id,
                    })

                    if (!scope.classList.contains('backward__mobile-yes')) {

                        matchMedia.add({
                            isMobile: "(max-width: 550px)"

                        }, (context) => {

                            let {
                                isMobile
                            } = context.conditions;

                            backwardSc.kill(true);

                            if (scope.classList.contains('backward__fade')) {
                                backwardScFade.revert(true);
                            }

                        });

                    }

                }

                if (scope.classList.contains('backward__container')) {
                    backwardContainer();
                }

                if (scope.classList.contains('highlight--inners')) {

                    let childNodes = scope.childNodes;

                    for (let i = 0; i < childNodes.length; i++) {

                        if (childNodes[i].tagName === 'DIV' && childNodes[i].classList.contains('elementor-element')) {

                            let child = childNodes[i];

                            ScrollTrigger.create({
                                trigger: child,
                                start: 'top center',
                                end: 'bottom center',
                                onEnter: () => {
                                    gsap.to(child, {
                                        opacity: 1
                                    })
                                },
                                onLeaveBack: () => {
                                    gsap.to(child, {
                                        opacity: 0
                                    })
                                },
                                onLeave: () => {
                                    gsap.to(child, {
                                        opacity: 0
                                    })
                                },
                                onEnterBack: () => {
                                    gsap.to(child, {
                                        opacity: 1
                                    })
                                }
                            })


                        }

                    }



                }

                if (scope.classList.contains('bg--fade--edges') && scope.classList.contains('bg--fade--animate')) {

                    gsap.to(document.querySelector('.fade--overlay--for--' + id), {
                        opacity: 1,
                        scrollTrigger: {
                            trigger: scope,
                            start: 'top bottom',
                            end: 'top center',
                            scrub: true
                        }
                    })

                }

                if (scope.classList.contains('build--on--scroll') && !document.body.classList.contains('e-preview--show-hidden-elements') && !mobileQuery.matches) {


                    var grid = scope,
                        classList = grid.className,
                        classes = classList.split(' '),
                        targetClass = classes.find(cls => cls.startsWith('build_pin_container')),
                        target = targetClass ? targetClass.substring("build_pin_container_".length) : '.' + scope.classList[0],
                        fromClass = classes.find(cls => cls.startsWith('stagger_from')),
                        from = fromClass.substring("stagger_from_".length),
                        speedClass = classes.find(cls => cls.startsWith('build_speed')),
                        speed = speedClass.substring("build_speed_".length),
                        staggerClass = classes.find(cls => cls.startsWith('build_stagger')),
                        stagger = staggerClass.substring("build_stagger_".length),
                        buildTypeClass = classes.find(cls => cls.startsWith('build_type')),
                        buildType = buildTypeClass.substring("build_type_".length),
                        elements = grid.querySelectorAll('.e-con');

                    if (elements.length < 1) {
                        var elements = grid.querySelectorAll('.elementor-element');
                    }

                    gsap.getById(id) ? gsap.getById(id).scrollTrigger.kill(true) : '';

                    let tl = gsap.timeline({
                        id: id,
                        invalidateOnRefresh: true,
                        scrollTrigger: {
                            trigger: targetClass ? target : scope,
                            pin: scope.classList.contains('build--pin') ? true : false,
                            scrub: true,
                            start: 'center center',
                            end: 'bottom+=' + speed + ' top',
                            invalidateOnRefresh: true,
                            pinSpacing: 'padding'
                        }
                    });

                    var elementsArray = Array.from(elements),
                        animateTargets = [];

                    if (scope.classList.contains('animate--first--item')) {
                        animateTargets = elementsArray;
                    } else {
                        for (let i = 0; i < elementsArray.length; i++) {

                            if (from === 'end') {
                                if (i != (elementsArray.length - 1)) {
                                    animateTargets.push(elementsArray[i])
                                }
                            } else if (from === 'start') {

                                if (i != 0) {
                                    animateTargets.push(elementsArray[i])
                                }

                            } else if (from === 'center') {

                                let cent = parseInt((elementsArray.length - 1) / 2);
                                if (i != cent) {
                                    animateTargets.push(elementsArray[i])
                                }

                            }
                        }

                    }

                    if (from === 'center' && animateTargets.length == 2) {
                        var animFrom = 'start';
                    } else {
                        var animFrom = 'from';
                    }

                    let animStagger = {
                        each: parseFloat(stagger),
                        from: animFrom,
                        ease: 'none'
                    };

                    tl.fromTo(animateTargets, {
                        y: buildType === 'slide-up' ? '100vh' : buildType === 'slide-down' ? '-100vh' : '0vh',
                        x: buildType === 'slide-left' ? '-100vw' : buildType === 'slide-right' ? '100vw' : '0vw',
                        scale: buildType === 'scale-up' ? 0 : 1,
                        rotate: scope.classList.contains('rotate--items') ? Math.floor(gsap.utils.random(-45, 45)) : 0,
                        opacity: buildType === 'fade' ? 0 : 1,
                    }, {
                        y: 0,
                        x: 0,
                        scale: 1,
                        opacity: 1,
                        rotate: 0,
                        stagger: animStagger,
                        ease: 'power2.out'
                    })

                    if (scope.classList.contains('animate--inners')) {

                        elements.forEach(element => {

                            let widgets = Array.from(element.querySelectorAll('.elementor-widget:not(.elementor-widget-pelottie), .e-con')),
                                animateWidgets = [];

                            if (widgets.length > 1) {

                                for (let i = 0; i < widgets.length; i++) {
                                    if (i != 0) {
                                        animateWidgets.push(widgets[i])
                                    }

                                }
                                tl.fromTo(animateWidgets, {
                                    y: buildType === 'slide-up' ? '100vh' : buildType === 'slide-down' ? '-100vh' : '0vh',
                                    x: buildType === 'slide-left' ? '100vw' : buildType === 'slide-right' ? '100vw' : '0vw',
                                    scale: buildType === 'scale-up' ? 0 : buildType === 'scale-down' ? 1.5 : 1,
                                    opacity: buildType === 'fade' ? 0 : 1,
                                }, {
                                    y: 0,
                                    x: 0,
                                    scale: 1,
                                    opacity: 1,
                                    ease: 'power2.out',
                                    stagger: parseFloat(stagger)
                                }, 0)
                            }

                        })

                    }


                }

                if (scope.classList.contains('grid--stacked')) {

                    var classList = scope.className,
                        classes = classList.split(' '),
                        targetClass = classes.find(cls => cls.startsWith('pin_container')),
                        target = targetClass ? targetClass.substring("pin_container_".length) : '.' + scope.classList[0],
                        start = scope.dataset.pinStart,
                        end = scope.dataset.pinEnd,
                        id = scope.dataset.id;

                    gsap.getById(id) ? gsap.getById(id).scrollTrigger.kill(true) : '';

                    if (scope.classList.contains('con--mode--edit') && document.body.classList.contains('e-preview--show-hidden-elements')) {
                        return false;
                    }

                    const children = Array.from(scope.querySelectorAll(":scope > .elementor-element"));

                    const scopeRect = scope.getBoundingClientRect();
                    const scopeCenter = {
                        x: scopeRect.left + scopeRect.width / 2,
                        y: scopeRect.top + scopeRect.height / 2
                    };

                    let tl = gsap.timeline({
                        id: id,
                        scrollTrigger: {
                            trigger: targetClass ? target : scope,
                            start: scope.getBoundingClientRect().top < window.innerHeight && !scope.classList.contains('cont--behavior--pin') ? 0 : start,
                            end: end,
                            pin: scope.classList.contains('cont--behavior--pin') ? true : false,
                            scrub: scope.classList.contains('cont--behavior--pin') || scope.classList.contains('cont--behavior--scrub') ? true : false,
                            // markers: true,
                            pinSpacing: 'padding',
                            // toggleActions: "play reset resume reset"
                        }
                    });


                    const step = 150;
                    const middle = Math.floor(children.length / 2);

                    children.forEach((el, i) => {

                        const offset = (i - middle) * step;

                        const rect = el.getBoundingClientRect();
                        const elCenter = {
                            x: rect.left + rect.width / 2,
                            y: rect.top + rect.height / 2
                        };

                        const dx = scopeCenter.x - elCenter.x;
                        const dy = scopeCenter.y - elCenter.y;

                        if (scope.classList.contains('grid--stack--expand')) {

                            gsap.set(el, {
                                x: scope.classList.contains('cont--stack--offset') ? dx + offset : dx,
                                y: dy,
                                rotate: scope.classList.contains('cont--stack--rotate') ? gsap.utils.random(-7, 7) : 0,
                                transformOrigin: "center center",
                                transition: 'none'
                            });

                            tl.to(el, {
                                x: 0,
                                y: 0,
                                rotate: 0
                            }, 0)


                        } else if (scope.classList.contains('grid--stack--collapse')) {

                            tl.to(el, {
                                x: scope.classList.contains('cont--stack--offset') ? dx + offset : dx,
                                y: dy,
                                rotate: scope.classList.contains('cont--stack--rotate') ? gsap.utils.random(-7, 7) : 0,
                            }, 0)

                        }



                    });


                    elementorBreakpoints.forEach(point => {
                        elementorMatches(point, () => {
                            if (scope.classList.contains('pin-disabled-' + point + '-yes')) {
                                tl.revert(true);
                                scope.classList.remove('grid--stacked')
                                children.forEach(el => clearProps(el));
                            }
                        });

                    });


                }

                if (scope.classList.contains('parallax__container')) {

                    var classList = scope.classList,
                        parallaxStrengthClass = Array.from(classList).find(cls => cls.startsWith('parallax_strength_')),
                        strength = parallaxStrengthClass.split('_').pop(),
                        parallaxDirectionClass = Array.from(classList).find(cls => cls.startsWith('parallax_direction_')),
                        direction = parallaxDirectionClass.split('_').pop(),
                        x, y;

                    if (direction === 'down' || direction === 'up') {
                        x = 0;
                        direction === 'down' ? y = strength : y = -1 * strength;
                    }

                    if (direction === 'right' || direction === 'left') {
                        y = 0;
                        direction === 'right' ? x = strength : x = -1 * strength;
                    }

                    gsap.getById(scope.dataset.id) ? gsap.getById(scope.dataset.id).scrollTrigger.kill(true) : '';

                    let anim = gsap.to(scope, {
                        yPercent: y,
                        xPercent: x,
                        ease: 'none',
                        id: scope.dataset.id,
                        scrollTrigger: {
                            trigger: scope,
                            start: scope.getBoundingClientRect().top < window.innerHeight ? 0 : 'top bottom',
                            end: 'bottom top',
                            scrub: 1.2,
                        }
                    })


                    matchMedia.add({
                        isMobile: "(max-width: 550px)"

                    }, (context) => {

                        let {
                            isMobile
                        } = context.conditions;

                        anim.kill();

                    });

                }



                //Animations
                setTimeout(() => {

                    if (scope.hasAttribute('data-anim-general')) {

                        if (!scope.classList.contains('general--anim--initialized')) {
                            scope.classList.add('general--anim--initialized');
                            if (gsap.getById('pageLoader')) {
                                document.addEventListener('pageLoaderDone', function () {
                                    new peGeneralAnimation(scope, id);
                                })
                            } else {
                                new peGeneralAnimation(scope, id);
                            }

                        }

                    }

                    if (scope.classList.contains('will__animated') && scope.querySelector('.container--anim--hold')) {

                        let hold = scope.querySelector('.container--anim--hold'),
                            anim = hold.dataset.animation,
                            sett = hold.dataset.animSettings;

                        scope.setAttribute('data-animation', anim);
                        scope.setAttribute('data-anim-settings', sett);

                        new peGeneralAnimation(scope, id);

                    }

                }, 10);

                function carouselContainer() {

                    if (scope.classList.contains('carousel--initialized')) {
                        return false;
                    } else {
                        scope.classList.add('carousel--initialized')
                    }

                    let width = scope.offsetWidth,
                        height = scope.offsetHeight,
                        classList = scope.className,
                        classes = classList.split(' '),
                        carouselIdClass = classes.find(cls => cls.startsWith('carousel_id_')),
                        carouselTriggerClass = classes.find(cls => cls.startsWith('carousel_trigger')),
                        trigger = carouselTriggerClass ? carouselTriggerClass.substring("carousel_trigger_".length) : '.' + scope.classList[0],
                        id = carouselIdClass ? carouselIdClass.substring("carousel_id_".length) : scope.dataset.id,
                        items = scope.children;

                    for (var i = 0; i < items.length; i++) {
                        items[i].classList.contains('e-con') || items[i].classList.contains('elementor-element') ? items[i].classList.add('cr--item') : '';
                        items[i].setAttribute('data-cr', i + 1);
                    }

                    scope.setAttribute('data-total', scope.querySelectorAll('.cr--item').length);

                    clearProps(scope);
                    gsap.getById(id) ? gsap.getById(id).revert(true) : '';
                    Draggable.get(scope) ? Draggable.get(scope).kill : '';

                    if (scope.classList.contains('con--mode--edit') && document.body.classList.contains('e-preview--show-hidden-elements')) {
                        return false;
                    }

                    if (scope.classList.contains('cr--looped--autoplay')) {

                        let items = scope.querySelectorAll('.elementor-element');

                        items.forEach(function ($item) {
                            let clone = $item.cloneNode(true);
                            scope.appendChild(clone)
                        });

                        if (scope.classList.contains('loop--down')) {

                            gsap.set(scope, {
                                y: height / -2
                            })

                        };

                        let tl = gsap.timeline({
                            id: id,
                            repeat: -1,
                            scrollTrigger: {
                                trigger: scope.classList.contains('converted--vertical') ? parents(scope, '.elementor-element')[0] : scope,
                                start: 'top bottom',
                                end: 'bottom top',
                                toggleActions: "play pause play pause",
                            }
                        });

                        let yVal = scope.classList.contains('loop--down') ? 0 : -1 * height;

                        tl.to(scope, {
                            x: scope.classList.contains('converted--vertical') ? 0 : -1 * width,
                            y: scope.classList.contains('converted--vertical') ? yVal : 0,
                            ease: 'none',
                            duration: parseInt(getComputedStyle(scope).getPropertyValue('--loopSpeed'))
                        }, 0)

                        if (scope.classList.contains('speed__on__autoplay')) {
                            let whaler = Hamster(document.querySelector('body')),
                                wheelDeltaY, currentDeltaY;

                            whaler.wheel(function (event, delta, deltaX, deltaY) {

                                wheelDeltaY = event.deltaY;
                                event.deltaY < 0 ? wheelDeltaY = -1 * event.deltaY : '';
                                tl.timeScale(1 + (wheelDeltaY * 2))

                            });

                        }

                        if (scope.classList.contains('loop--speed--up')) {
                            let whaler = Hamster(document.querySelector('body')),
                                wheelDeltaY, currentDeltaY;

                            whaler.wheel(function (event, delta, deltaX, deltaY) {

                                wheelDeltaY = event.deltaY;
                                event.deltaY < 0 ? wheelDeltaY = -1 * event.deltaY : '';
                                tl.timeScale(1 + (wheelDeltaY * 2))

                            });

                        }

                        if (scope.classList.contains('loop--pause--on--hover')) {

                            scope.addEventListener('mouseenter', function () {
                                tl.pause()
                            })
                            scope.addEventListener('mouseleave', function () {
                                tl.play()
                                tl.timeScale(1)
                            })

                        }

                    }

                    if (scope.classList.contains('cr--drag')) {

                        let parent = parents(scope, '.elementor-element')[0];

                        Draggable.create(scope, {
                            id: id,
                            type: scope.classList.contains('converted--vertical') ? 'y' : 'x',
                            bounds: parent,
                            lockAxis: true,
                            dragResistance: 0.5,
                            inertia: true,
                            allowContextMenu: true
                        });

                    }

                    function scopeCarouselScroll() {
                        let carouselStart = getComputedStyle(scope).getPropertyValue('--carouselStart') ? getComputedStyle(scope).getPropertyValue('--carouselStart') : 0;


                        document.querySelector(trigger).classList.add('has--pinned--scroll');
                        document.querySelector(trigger).setAttribute('data-pin-for', scope.dataset.id);


                        let handleSpeed = scope.querySelectorAll(':scope > .e-con').length * 350;

                        gsap.getById(scope.dataset.id) ? gsap.getById(scope.dataset.id).scrollTrigger.kill(true) : '';

                        gsap.fromTo(scope, {
                            x: isRTL ? -1 * carouselStart : carouselStart,
                        }, {
                            x: -1 * width - scope.getBoundingClientRect().left + document.body.clientWidth - parseInt(carouselStart),
                            id: scope.dataset.id,
                            ease: 'none',
                            scrollTrigger: {
                                trigger: trigger,
                                scrub: true,
                                pin: trigger,
                                // markers: true,
                                ease: "elastic.out(1, 0.3)",
                                start: 'center center',
                                end: 'bottom+=' + handleSpeed + ' top',
                                pinSpacing: 'padding'
                            }
                        })

                    }

                    if (scope.classList.contains('cr--scroll') && !scope.classList.contains('sc--initalized')) {
                        scope.classList.add('sc--initalized')
                        scopeCarouselScroll();
                    }

                    if (scope.classList.contains('cr--drag--mobile')) {

                        matchMedia.add({
                            isMobile: "(max-width: 570px)"
                        }, (context) => {
                            let {
                                isMobile
                            } = context.conditions;

                            gsap.getById(scope.dataset.id) && gsap.getById(scope.dataset.id).revert();

                            let parent = parents(scope, '.elementor-element')[0];

                            Draggable.create(scope, {
                                id: id,
                                type: scope.classList.contains('converted--vertical') ? 'y' : 'x',
                                bounds: parent,
                                lockAxis: true,
                                dragResistance: 0.5,
                                inertia: true,
                                allowContextMenu: true
                            });

                        });
                    }


                }

                if (scope.classList.contains('convert--carousel')) {

                    carouselContainer();
                }

                function layeredContainer() {

                    gsap.getById('layered_' + scope.dataset.id) ? gsap.getById('layered_' + scope.dataset.id).scrollTrigger.kill(true) : '';

                    if (scope.classList.contains('con--mode--edit') && document.body.classList.contains('e-preview--show-hidden-elements')) {
                        return false;
                    }

                    let items = scope.children,
                        classList = scope.className,
                        classes = classList.split(' '),
                        speedClass = classes.find(cls => cls.startsWith('layered_speed')),
                        speed = speedClass ? speedClass.substring("layered_speed_".length) : '.' + scope.classList[0],
                        triggerClass = classes.find(cls => cls.startsWith('layered_target')),
                        trigger = triggerClass ? triggerClass.substring("layered_target_".length) : scope;

                    var animOut = '';

                    if (scope.classList.contains('layered_out_anim')) {
                        animOut = scope.classList.contains('layer--out--fade-out') ? 'fadeOut' : scope.classList.contains('layer--out--fade-left') ? 'fadeLeft' : scope.classList.contains('layer--out--fade-right') ? 'fadeRight' : scope.classList.contains('layer--out--slide-up') ? 'slideUp' : scope.classList.contains('layer--out--slide-left') ? 'slideLeft' : scope.classList.contains('layer--out--slide-right') ? 'slideRight' : 'none';
                    }

                    let tl = gsap.timeline({
                        id: 'layered_' + scope.dataset.id,
                        ease: 'none',
                        immediateRender: false,
                        scrollTrigger: {
                            trigger: trigger,
                            start: 'top top',
                            end: 'bottom+=' + speed + ' top',
                            pin: true,
                            scrub: true,
                            pinSpacing: 'padding',
                            invalidateOnRefresh: true,
                        }
                    });

                    for (var i = 0; i < items.length; i++) {

                        items[i].style.zIndex = i;

                        if (items[i].classList.contains('e-con')) {

                            if (i != 0) {
                                tl.to(items[i], {
                                    yPercent: 0,
                                    y: scope.classList.contains('accordion--layered') ? i * getComputedStyle(scope).getPropertyValue('--accordionLayerSpacing') : 0,
                                    duration: 1,
                                    ease: 'none',
                                }, 'label_' + i)

                                tl.to(items[i - 1], {
                                    duration: .25,
                                    // delay: 1,
                                    ease: 'none'
                                }, 'label_' + i)


                                if (scope.classList.contains('layered_out_anim')) {
                                    tl.to(items[i - 1], {
                                        opacity: animOut == 'fadeOut' || animOut == 'fadeUp' || animOut == 'fadeLeft' || animOut == 'fadeRight' ? 0 : 1,
                                        yPercent: animOut === 'fadeUp' || animOut === 'slideUp' ? -100 : 0,
                                        xPercent: animOut === 'fadeLeft' || animOut === 'slideLeft' ? -100 : animOut === 'fadeRight' || animOut === 'slideRight' ? 100 : 0,
                                        scale: animOut == 'fadeOut' || animOut == 'fadeUp' || animOut == 'fadeLeft' || animOut == 'fadeRight' ? .8 : 1,
                                        duration: 1,
                                        delay: 0,
                                        ease: 'none',
                                    }, 'label_' + i)

                                }

                            }

                            if (window.screen.height < items[i].getBoundingClientRect().height) {

                                if (i !== (items.length - 1)) {

                                    tl.to(items[i], {
                                        y: window.screen.height - items[i].getBoundingClientRect().height,
                                        ease: 'none'
                                    }, 'label_a_' + i)

                                }

                            }

                        }

                    }

                }

                if (scope.classList.contains('convert--layered')) {
                    layeredContainer();
                }

                if (scope.classList.contains('convert--curved')) {

                    const container = scope;
                    const parent = parents(scope, '.e-con')[0];
                    const align = scope.classList.contains('curved--items--center') ? 'center' : 'left';
                    const itemsWidth = parseInt(getComputedStyle(scope).getPropertyValue('--itemsWidth'));
                    const gap = parseInt(getComputedStyle(scope).getPropertyValue('--gap'));

                    clearProps(scope);

                    if (!container) return;

                    // sadece direct-child .elementor-element'leri al
                    const children = Array.from(
                        container.querySelectorAll(":scope > .elementor-element")
                    );

                    const total = children.length;
                    const isEven = total % 2 === 0;

                    scope.style.setProperty("--length", total - 1);

                    children.forEach((el, i) => {
                        let index;
                        if (align !== 'center') {
                            index = i;
                        } else {
                            index = i; // senin şimdiki mantığınla aynıs
                        }
                        el.style.setProperty("--i", index);
                        el.setAttribute('data-index', index);
                    });

                    if (align === 'center') {
                        gsap.set(scope, {
                            marginLeft: (window.innerWidth / 2) - (itemsWidth / 2) - (gap * ((total - 1) / 2)) + gap
                        })
                    }

                    let tl = gsap.timeline({ paused: true });

                    for (let c = 1; c < total; c++) {
                        children.forEach((el) => {
                            let i = parseInt(el.dataset.index);

                            tl.to(scope, {
                                x: -itemsWidth * c,
                                duration: 1,
                                ease: 'none'
                            }, 'label_' + c);

                            tl.to(el, {
                                '--i': i - c,
                                duration: 1,
                                ease: 'none'
                            }, 'label_' + c);
                        });
                    }


                    let tlDuration = tl.totalDuration();
                    let pixelsPerSecond = 1000;
                    let dragWidth = tlDuration * pixelsPerSecond;
                    let $proxy = $("<div/>");
                    let proxy = $proxy[0];

                    let startProgress = 0;
                    if (align === 'center') {
                        const midIndex = Math.ceil(total / 2);
                        tl.seek('label_' + midIndex);
                        startProgress = tl.time() / tlDuration;
                    }

                    gsap.set(proxy, { x: -startProgress * dragWidth });

                    const drag = Draggable.create(proxy, {
                        type: "x",
                        trigger: parent,
                        inertia: true,
                        lockAxis: true,
                        allowContextMenu: true,
                        bounds: {
                            minX: -dragWidth,
                            maxX: 0
                        },
                        onDrag: updateProgress,
                        onThrowUpdate: updateProgress
                    })[0];

                    function updateProgress() {
                        let progress = gsap.utils.clamp(0, 1, -this.x / dragWidth);

                        tl.totalProgress(progress, true);

                        let actualX = (scope && scope instanceof Element) ? gsap.getProperty(scope, "x") : (scope && scope[0]) ? gsap.getProperty(scope[0], "x") : "NO_SCOPE";


                        let expectedX = -itemsWidth * tl.time();
                        if (Math.abs(actualX - expectedX) > 1) {
                            gsap.set(scope, { x: expectedX });
                            children.forEach(el => {
                                let i = parseInt(el.dataset.index);
                                el.style.setProperty('--i', (i - tl.time()));
                            });
                        }
                    }
                }



                setTimeout(() => {

                    if (scope.classList.contains('switch_on_enter') && !scope.classList.contains('switch--initalized')) {

                        scope.classList.add('switch--initalized');

                        function switcherClick() {

                            if (!document.documentElement.classList.contains('barba--running')) {

                                let switcher = document.querySelector('.pe-layout-switcher');

                                if (switcher) {
                                    switcher.click();
                                    // console.log(switcher)
                                } else {
                                    let mainColors = [
                                        getComputedStyle(document.documentElement).getPropertyValue('--mainColor'),
                                        getComputedStyle(document.documentElement).getPropertyValue('--mainBackground'),
                                        getComputedStyle(document.documentElement).getPropertyValue('--secondaryColor'),
                                        getComputedStyle(document.documentElement).getPropertyValue('--secondaryBackground'),
                                        getComputedStyle(document.documentElement).getPropertyValue('--linesColor'),
                                    ]

                                    let switchedColors = [
                                        getComputedStyle(document.querySelector('.layout--colors')).getPropertyValue('--mainColor'),
                                        getComputedStyle(document.querySelector('.layout--colors')).getPropertyValue('--mainBackground'),
                                        getComputedStyle(document.querySelector('.layout--colors')).getPropertyValue('--secondaryColor'),
                                        getComputedStyle(document.querySelector('.layout--colors')).getPropertyValue('--secondaryBackground'),
                                        getComputedStyle(document.querySelector('.layout--colors')).getPropertyValue('--linesColor'),
                                    ]
                                    if (document.body.classList.contains('layout--switched')) {

                                        gsap.fromTo(document.body, {
                                            '--mainColor': switchedColors[0],
                                            '--mainBackground': switchedColors[1],
                                            '--secondaryColor': switchedColors[2],
                                            '--secondaryBackground': switchedColors[3],
                                        }, {
                                            '--mainColor': mainColors[0],
                                            '--mainBackground': mainColors[1],
                                            '--secondaryColor': mainColors[2],
                                            '--secondaryBackground': mainColors[3],
                                            duration: 1,
                                            ease: 'power3.out',
                                            onStart: () => {
                                                document.body.classList.add('layout--default');
                                                document.body.classList.remove('layout--switched');
                                                siteLayout = 'default';

                                            }
                                        })

                                    } else {
                                        gsap.fromTo(document.body, {
                                            '--mainColor': mainColors[0],
                                            '--mainBackground': mainColors[1],
                                            '--secondaryColor': mainColors[2],
                                            '--secondaryBackground': mainColors[3],
                                        }, {
                                            '--mainColor': switchedColors[0],
                                            '--mainBackground': switchedColors[1],
                                            '--secondaryColor': switchedColors[2],
                                            '--secondaryBackground': switchedColors[3],
                                            duration: 1,
                                            ease: 'power3.out',
                                            onStart: () => {
                                                document.body.classList.remove('layout--default');
                                                document.body.classList.add('layout--switched');
                                                siteLayout = 'switched';
                                            }
                                        })

                                    }

                                };


                            }

                        }

                        const hasPinnedTrigger = ScrollTrigger.getAll().some(trigger => {
                            return trigger.trigger === scope;
                        });

                        var start = 'top center',
                            end = 'bottom center';

                        if (hasPinnedTrigger) {

                            const sc = ScrollTrigger.getAll().filter(trigger => trigger.trigger === scope);
                            start = sc[0].start + 50;
                            end = sc[0].end;
                        }


                        let sc = ScrollTrigger.create({
                            trigger: scope,
                            start: start,
                            end: end,
                            onEnter: () => switcherClick(),
                            onLeave: () => switcherClick(),
                            onEnterBack: () => switcherClick(),
                            onLeaveBack: () => switcherClick(),
                        })
                    }


                }, 20);


                function containerPin() {

                    let settings;

                    if (scope.querySelector('.container--pin--sett')) {
                        settings = scope.querySelector('.container--pin--sett');
                    } else {
                        settings = scope;
                    }

                    if (!settings.dataset.pinMobile && mobileQuery.matches) {
                        return false;
                    }

                    ScrollTrigger.getById(scope.dataset.id) ? ScrollTrigger.getById(scope.dataset.id).kill(true) : '';

                    let items = scope.children,
                        classList = scope.className,
                        classes = classList.split(' '),
                        targetClass = classes.find(cls => cls.startsWith('pin_container')),
                        target = targetClass ? targetClass.substring("pin_container_".length) : '.' + scope.classList[0],
                        endTriggerClass = classes.find(cls => cls.startsWith('pin_container_end_trigger')),
                        endTrigger = targetClass ? targetClass.substring("pin_container_end_trigger_".length) : '.' + scope.classList[0];

                    var pinnedScroll;


                    var start = settings.dataset.pinStart,
                        end = settings.dataset.pinEnd,
                        pinMobile = settings.dataset.pinMobile;


                    if (targetClass) {

                        pinnedScroll = ScrollTrigger.create({
                            trigger: document.querySelector(target),
                            pin: scope,
                            pinSpacing: false,
                            start: start,
                            end: end,
                            id: scope.dataset.id,
                            endTrigger: endTriggerClass ? endTrigger : target,
                        })

                    } else {

                        pinnedScroll = ScrollTrigger.create({
                            trigger: scope,
                            start: start,
                            end: end,
                            id: scope.dataset.id,
                            pin: targetClass ? target : true,
                            pinSpacing: false,
                            pinSpacer: false,
                            endTrigger: targetClass ? target : 'body',
                        })
                    }

                    elementorBreakpoints.forEach(point => {
                        elementorMatches(point, () => {
                            if (scope.classList.contains('pin-disabled-' + point + '-yes')) {
                                pinnedScroll.kill();
                            }
                        });

                    });

                    // matchMedia.add({
                    //     isMobile: "(max-width: 570px)"
                    // }, (context) => {
                    //     let {
                    //         isMobile
                    //     } = context.conditions;

                    //     if (!pinMobile) {
                    //         pinnedScroll.kill();
                    //     }
                    // });
                }

                if (scope.classList.contains('pinned_true')) {
                    // setTimeout(() => {
                    containerPin();
                    // }, 200);
                }

                setTimeout(() => {
                    if (scope.classList.contains('curved_true')) {

                        let rhTop = document.querySelector('.rh--top.reverse__' + scope.dataset.id);
                        let rhBottom = document.querySelector('.rh--bottom.reverse__' + scope.dataset.id);

                        if (rhTop) {
                            gsap.set(rhTop, {
                                '--mainBackground': window.getComputedStyle(scope).backgroundColor
                            })
                        }
                        if (rhBottom) {
                            gsap.set(rhBottom, {
                                '--mainBackground': window.getComputedStyle(scope).backgroundColor
                            })

                        }
                    }

                }, 50);

                if (scope.classList.contains('outside--curved')) {

                    const color = getComputedStyle(scope).getPropertyValue('--mainBackground').trim();
                    const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 300"><path fill="${color}" d="M300,0C134.3,0,0,134.3,0,300V0H300z"/></svg>`;
                    const encodedSvg = encodeURIComponent(svg);

                    const el = scope;
                    el.style.setProperty('--pseudo-bg', `url("data:image/svg+xml,${encodedSvg}")`);


                }

                if (scope.classList.contains('e-parent') && (document.querySelector('.site-header') && document.querySelector('.site-header').classList.contains('header--auto--switch') || scope.classList.contains('switch--header--on--enter'))) {



                    var scopeBg = getComputedStyle(scope).getPropertyValue('background-color');

                    if (scopeBg !== 'rgba(0, 0, 0, 0)' || scope.classList.contains('layout--switched')) {



                        if (!parents(scope, '.site-header').length) {
                            setTimeout(() => {

                                if (!scope.classList.contains('sc--initialized') && !parents(scope, '.site--menu').length) {
                                    scope.classList.add('sc--initialized');

                                    function integrateColors() {

                                        let headerColor = getComputedStyle(document.querySelector('.site-header')).getPropertyValue('--intColor'),
                                            headerBrightness = gsap.utils.splitColor(headerColor, true)[2],
                                            scopeBg = getComputedStyle(scope).getPropertyValue('background-color'),
                                            scopeBrightness = gsap.utils.splitColor(scopeBg, true)[2],
                                            header = document.querySelector('.site-header');

                                        if (headerBrightness >= scopeBrightness) {
                                            header.classList.add('header--switched')
                                        }

                                        if (scope.classList.contains('layout--switched') && !header.classList.contains('header--switched')) {
                                            header.classList.add('header--switched')
                                        }

                                    }

                                    let start = 'top top+=50',
                                        end = 'bottom top+=50';

                                    if (scope.classList.contains('pin--trigger')) {
                                        let scopeSc = ScrollTrigger.getById(scope.dataset.scrollId);
                                        start = scopeSc.start;
                                        end = scopeSc.end + scope.getBoundingClientRect().height;
                                    }

                                    if (scope.classList.contains('has--pinned--scroll')) {

                                        let pinFor = scope.dataset.pinFor,
                                            pinElement = document.querySelector('.elementor-element-' + pinFor),
                                            crId = pinElement.dataset.id,
                                            scopeSc = gsap.getById(crId).scrollTrigger;

                                        start = scopeSc.start < 0 ? -1 : scopeSc.start;
                                        end = scopeSc.end + scope.getBoundingClientRect().height;
                                    }

                                    if (parents(scope, '.pin-spacer-fixedFooter').length) {
                                        start = 'top+=' + scope.getBoundingClientRect().height + ' top+=50';
                                        end = 'bottom+=' + scope.getBoundingClientRect().height + ' top+=50';
                                    }

                                    // const hasPinnedTrigger = ScrollTrigger.getAll().some(trigger => {
                                    //     return trigger.trigger === scope && trigger.pin;
                                    // });

                                    // if (hasPinnedTrigger) {

                                    //     const sc = ScrollTrigger.getAll().filter(trigger => trigger.trigger === scope);
                                    //     start = sc[0].start + 50;
                                    //     end = sc[0].end;
                                    // }

                                    ScrollTrigger.create({
                                        trigger: scope,
                                        start: start,
                                        end: end,
                                        onEnter: () => {
                                            integrateColors();
                                        },
                                        onEnterBack: () => {
                                            integrateColors()
                                        },
                                        onLeave: () => {
                                            document.querySelector('.site-header').classList.remove('header--switched')

                                        },
                                        onLeaveBack: () => {
                                            document.querySelector('.site-header').classList.remove('header--switched')
                                        },
                                    })

                                }
                            }, 10);

                        }

                    }

                }

                if (scope.dataset.cursor) {

                    peCursorInteraction(scope);

                }
                if ((!scope.classList.contains('intro--none') && document.body.classList.contains('e-preview--show-hidden-elements')) || parents(scope, '.pe--page--loader').length) {

                    if (scope.classList.contains('intro--fade')) {

                        gsap.fromTo(scope, {
                            opacity: 0,
                            yPercent: scope.classList.contains('fade_up') ? 100 : scope.classList.contains('fade_down') ? -100 : 0,
                            xPercent: scope.classList.contains('fade_left') ? -100 : scope.classList.contains('fade_right') ? 100 : 0,
                        }, {
                            opacity: 1,
                            yPercent: 0,
                            xPercent: 0,
                            delay: .5,
                            ease: 'power3.inOut'
                        })
                    }

                    if (scope.classList.contains('intro--slide')) {

                        gsap.fromTo(scope, {
                            y: scope.classList.contains('slide_up') ? '100vh' : scope.classList.contains('slide_down') ? '-100vh' : 0,
                            x: scope.classList.contains('slide_left') ? '-100vw' : scope.classList.contains('slide_right') ? '100vw' : 0,
                        }, {
                            opacity: 1,
                            y: 0,
                            x: 0,
                            duration: 1.25,
                            delay: .75,
                            ease: 'expo.out'
                        })
                    }

                    if (scope.classList.contains('intro--block')) {

                        gsap.to(scope, {
                            opacity: 1,
                            yPercent: 0,
                            y: 0,
                            duration: 1.25,
                            delay: .75,
                            ease: 'expo.out'
                        })
                    }

                }


            }

        })

        elementorFrontend.hooks.addAction('frontend/element_ready/widget', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    id = scope.dataset.id;

                // Scroll Buttons
                if (scope.querySelector('.pe--scroll--button')) {
                    scope.querySelectorAll('.pe--scroll--button').forEach(button => {
                        peScrollButton(button)
                    })
                }
                // Scroll Buttons

                if (scope.querySelector('.pe--browser--back')) {
                    scope.querySelector('.pe--browser--back').addEventListener('click', () => {
                        window.history.back();
                    })
                }

                if (scope.querySelector('.pe--button') && scope.querySelector('.pe--button').dataset.textHover) {
                    scope.querySelectorAll('.pe--button').forEach(button => {
                        textCharAnimations(button);
                    })

                }



                setTimeout(() => {

                    if (scope.hasAttribute('data-anim-general')) {

                        if (!scope.classList.contains('general--anim--initialized')) {
                            scope.classList.add('general--anim--initialized');
                            new peGeneralAnimation(scope, id);
                        }

                    }

                    if (scope.querySelector('.widget--anim--hold')) {

                        let hold = scope.querySelector('.widget--anim--hold'),
                            anim = hold.dataset.animation,
                            sett = hold.dataset.animSettings;

                        scope.setAttribute('data-animation', anim);
                        scope.setAttribute('data-anim-settings', sett);

                        new peGeneralAnimation(scope, id);

                    }

                }, 10);

                //Animations
                setTimeout(() => {

                    if (scope.querySelector('[data-anim-general="true"]')) {

                        var hasAnim = scope.querySelectorAll('[data-anim-general="true"]');

                        hasAnim.forEach(element => {

                            if (!scope.querySelector('div[data-elementor-type="pe-menu"]')) {

                                if (!element.classList.contains('general--anim--initialized')) {
                                    element.classList.add('general--anim--initialized');
                                    new peGeneralAnimation(element)
                                }




                            }
                        })

                    }

                }, 10);


                if (scope.querySelector('[data-anim-image="true"]')) {

                    var hasAnim = scope.querySelectorAll('[data-anim-image="true"]');

                    hasAnim.forEach(element => {

                        if (!scope.querySelector('div[data-elementor-type="pe-menu"]')) {

                            if (gsap.getById('pageLoader')) {
                                document.addEventListener('pageLoaderDone', function () {
                                    new peImageAnimation(element)
                                })
                            } else {
                                new peImageAnimation(element)
                            }
                        }

                    })

                }

                setTimeout(() => {

                    if (scope.querySelector('[data-cursor="true"]')) {

                        var targets = scope.querySelectorAll('[data-cursor="true"]');
                        targets.forEach(target => {
                            peCursorInteraction(target);
                        })

                    }

                }, 10);

                if (scope.querySelector('[data-animate="true"]')) {
                    scope.querySelectorAll('[data-animate="true"]').forEach(text => {

                        document.fonts.ready.then((e) => {
                            if (!text.classList.contains('text--anim--initalized')) {
                                text.classList.add('text--anim--initalized')

                                if (!text.classList.contains('anim--hold')) {
                                    if (document.body.classList.contains('e-preview--show-hidden-elements')) {
                                        const splitCache = new WeakMap();

                                        const observer = new IntersectionObserver(entries => {
                                            entries.forEach(entry => {
                                                if (!entry.isIntersecting) return;
                                                const el = entry.target;
                                                if (!splitCache.has(el)) {
                                                    new peTextAnimation(text, false, false, id);
                                                }
                                                observer.unobserve(el);
                                            });
                                        }, {
                                            rootMargin: '0px 0px -20% 0px'
                                        });

                                        observer.observe(scope);

                                    } else {
                                        new peTextAnimation(text, false, false, id);
                                    }

                                }
                            }
                        });

                    });

                }

                if (scope.querySelector('[data-text-hover="true"]')) {
                    document.fonts.ready.then((fontFaceSet) => {
                        peTextHover(scope);
                    });
                }

                setTimeout(() => {

                    if (scope.querySelector('.pe-video')) {

                        let videos = scope.querySelectorAll('.pe-video');

                        if (!document.body.classList.contains('window--initialized')) {
                            for (var i = 0; i < videos.length; i++) {
                                new peVideoPlayer(videos[i]);
                            }
                        } else {
                            // window.addEventListener("load", function () {
                            for (var i = 0; i < videos.length; i++) {

                                new peVideoPlayer(videos[i]);


                            }
                            // });
                        }

                    }

                }, 10);


                if (scope.querySelectorAll('.zeyna--single--product').length) {

                    scope.querySelectorAll('.zeyna--single--product').forEach(product => {

                        if (product.querySelector('.single_add_to_cart_button')) {
                            let button = product.querySelector('.single_add_to_cart_button'),
                                variationWrap = product.querySelector('.single_variation_wrap'),
                                table = product.querySelector('table.variations'),
                                form = product.querySelector('.variations_form');

                            if (product.classList.contains('product-type-variable')) {

                                setTimeout(() => {
                                    if (button.classList.contains('wc-variation-selection-needed')) {

                                        variationWrap.addEventListener('click', () => {

                                            if (!variationWrap.classList.contains('active')) {
                                                variationWrap.classList.add('active');
                                                form.classList.add('variations--active');
                                            } else {
                                                variationWrap.classList.remove('active');
                                                form.classList.remove('variations--active');
                                            }

                                        })

                                    }
                                }, 100);

                            }

                        }

                    })

                }

                if (scope.classList.contains('widget-pinned_true')) {

                    let settings = scope.querySelector('.widget--pin--sett'),
                        start = settings.dataset.pinStart,
                        end = settings.dataset.pinEnd,
                        target = settings.dataset.pinTarget,
                        pinMobile = settings.dataset.pinMobile;

                    ScrollTrigger.getById(scope.dataset.id) ? ScrollTrigger.getById(scope.dataset.id).kill(true) : '';

                    var widgetPin = ScrollTrigger.create({
                        trigger: scope,
                        start: start,
                        end: end,
                        id: scope.dataset.id,
                        pin: target ? target : true,
                        pinSpacing: false,
                        pinSpacer: false,
                        endTrigger: target ? target : 'body',
                    })

                    matchMedia.add({
                        isMobile: "(max-width: 570px)"
                    }, (context) => {

                        let {
                            isMobile
                        } = context.conditions;

                        if (!pinMobile) {
                            widgetPin.kill(true)
                        }

                    });

                }

                ScrollTrigger.addEventListener("refreshInit", () => {

                    if (scope.classList.contains('parallax__widget')) {

                        var classList = scope.classList,
                            parallaxStrengthClass = Array.from(classList).find(cls => cls.startsWith('parallax_strength_')),
                            strength = parallaxStrengthClass.split('_').pop(),
                            parallaxDirectionClass = Array.from(classList).find(cls => cls.startsWith('parallax_direction_')),
                            direction = parallaxDirectionClass.split('_').pop(),
                            x, y;

                        if (direction === 'down' || direction === 'up') {
                            x = 0;
                            direction === 'down' ? y = strength : y = -1 * strength;
                        }

                        if (direction === 'right' || direction === 'left') {
                            y = 0;
                            direction === 'right' ? x = strength : x = -1 * strength;
                        }

                        gsap.getById(scope.dataset.id) ? gsap.getById(scope.dataset.id).scrollTrigger.kill(true) : '';

                        let anim = gsap.to(scope, {
                            yPercent: y,
                            xPercent: x,
                            ease: 'none',
                            id: scope.dataset.id,
                            scrollTrigger: {
                                trigger: scope,
                                start: scope.getBoundingClientRect().top < window.innerHeight ? 0 : 'top bottom',
                                end: 'bottom top',
                                scrub: 1,
                            }
                        })

                        matchMedia.add({
                            isMobile: "(max-width: 550px)"

                        }, (context) => {

                            let {
                                isMobile
                            } = context.conditions;

                            anim.kill();

                        });

                    }
                })

                if (scope.querySelector('span[data-text-hover]')) {

                    if (scope.classList.contains('text-hover-initialized')) {
                        return false;
                    } else {
                        scope.classList.add('text-hover-initialized')
                    }

                    let items = scope.querySelectorAll('span[data-text-hover]');

                    // document.fonts.ready.then(() => {

                    items.forEach(item => {

                        var hover = item.dataset.textHover;
                        if (hover.startsWith("chars-")) {

                            let wrappers = item.querySelectorAll('span');

                            SplitText.create(wrappers, {
                                type: "chars",
                                charsClass: "text_char",
                                tag: 'span',
                                display: 'inline-block',
                                autoSplit: true,
                            });



                            wrappers.forEach(wrap => {
                                if (hover === 'chars-left' || hover === 'chars-right') {
                                    gsap.set(wrap, {
                                        '--l': wrap.querySelectorAll('.text_char').length,
                                    })
                                }

                                wrap.querySelectorAll('.text_char').forEach((char, i) => {
                                    gsap.set(char, {
                                        '--c': i,
                                    })
                                })
                            })

                        }

                    })

                    // })

                }

            }

        })



        elementorFrontend.hooks.addAction('frontend/element_ready/peproductcards.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    productCards = scope.querySelectorAll('.product--cards');

                productCards.forEach(function ($this) {
                    let cards = $this.querySelectorAll('.product--item'),
                        length = cards.length,
                        navWrap = scope.querySelector('.navigation--image--wrapper'),
                        navWrapWidth = navWrap.offsetWidth,
                        navImage = navWrap.querySelectorAll('.navigation--image'),
                        pinTarget = $this.getAttribute('data-pin-target'),
                        trigger = pinTarget,
                        e = 1;

                    if (scope.classList.contains('infinite--active')) {
                        e = 0
                        cards.forEach(function ($item) {
                            let clone = $item.cloneNode(true)
                            clone.classList.add('clone--product')
                            $this.querySelector('.products--wrapper').appendChild(clone)
                        })

                        navImage = navWrap.querySelectorAll('.navigation--image');

                        navImage.forEach(function ($nImage) {
                            let clone = $nImage.cloneNode(true)
                            navWrap.appendChild(clone)
                        })

                        navImage.forEach(function ($nImage) {
                            let clone = $nImage.cloneNode(true)
                            navWrap.appendChild(clone)
                        })

                        if (zeynaLenis) {

                            zeynaLenis.options.infinite = true;

                            if (window.barba) {
                                barba.hooks.before(() => {
                                    zeynaLenis.options.infinite = false;
                                });
                            }
                        } else {

                            // OPTIMIZED: Duplicate infinite scroll
                            const lenis = new Lenis({
                                smooth: true,
                                infinite: true,
                                smoothTouch: true
                            });

                            gsap.ticker.add((time) => {
                                lenis.raf(time * 1000);
                            });

                            if (window.barba) {
                                barba.hooks.before(() => {
                                    lenis.destroy();
                                    gsap.ticker.remove(lenis.raf);
                                });
                            }

                        }

                        cards = $this.querySelectorAll('.product--item');

                    };

                    cards[0].classList.add('product--active--meta')

                    cards.forEach(function ($item, i) {

                        $item.classList.add('product--index__' + i)

                        $item.setAttribute('data-y', (-i * 50) - (cards[0].offsetHeight / 2))
                        $item.setAttribute('data-z', -i * 150)
                        $item.setAttribute('data-opacity', 1 - (i * 0.25))

                        gsap.set($item, {
                            y: (-i * 50) - (cards[0].offsetHeight / 2),
                            z: -i * 150,
                            zIndex: 999 - i,
                            opacity: 1 - (i * 0.25)
                        })

                    })

                    if (!pinTarget) {
                        pinTarget = true
                        trigger = $this
                    }


                    ScrollTrigger.getById(scope.dataset.id) ? ScrollTrigger.getById(scope.dataset.id).kill(true) : '';

                    ScrollTrigger.create({
                        trigger: trigger,
                        pin: pinTarget,
                        scrub: true,
                        id: scope.dataset.id,
                        start: 'center center',
                        end: 'bottom+=' + $this.dataset.speed + ' bottom',
                        onUpdate: function (self) {
                            let yProg = (length) * 50 * self.progress,
                                zProg = (length) * 150 * self.progress,
                                opacityProg = length / 0.25 + self.progress * (length / 4),
                                activeIndex = parseInt(self.progress * length)

                            cards.forEach(function ($item, i) {
                                gsap.set($item, {
                                    y: parseInt($item.getAttribute('data-y')) + yProg,
                                    z: parseInt($item.getAttribute('data-z')) + zProg,
                                    opacity: 1 - ((i - 1) * 0.25) + (self.progress * (length / 4))
                                })
                                if (i < activeIndex - e) {
                                    gsap.set($item, {
                                        opacity: 0,
                                    })
                                }
                            })

                            cards.forEach(function ($card, i) {
                                if (i === activeIndex) {
                                    $card.classList.add('product--active--meta')
                                } else {
                                    $card.classList.remove('product--active--meta')
                                }

                            })


                            let prog = self.progress * (navWrapWidth - navImage[0].offsetWidth)
                            if (scope.classList.contains('infinite--active')) {
                                prog = self.progress * (navWrapWidth)
                            }
                            gsap.set(navWrap, {
                                x: -1 * prog
                            })
                            let navImages = navWrap.querySelectorAll('.navigation--image');
                            navImage.forEach(function ($card, i) {
                                if (i === Math.round(self.progress * (length))) {
                                    $card.classList.add('card--active')
                                } else {
                                    $card.classList.remove('card--active')
                                }
                            })
                        }
                    })

                    function triggerMouseWheel(deltaY) {
                        const event = new WheelEvent('wheel', {
                            deltaY: deltaY,
                        });
                        window.dispatchEvent(event);
                    }

                    navImage = navWrap.querySelectorAll('.navigation--image');
                    navImage.forEach(function ($navImage, i) {
                        $navImage.addEventListener('click', function ($click) {
                            let imageleft = this.getBoundingClientRect().left,
                                mainLeft = $this.getBoundingClientRect().left;
                            triggerMouseWheel((parseInt($this.dataset.speed) / navWrapWidth) * (imageleft - mainLeft))

                        })
                    })
                })
            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/peproductslideshow.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    productSlideshow = scope.querySelectorAll('.products--slideshow');

                productSlideshow.forEach(function ($this) {
                    let galleryWrap = $this.querySelectorAll('.product--image--gallery'),
                        productWrap = $this.querySelectorAll('.product--wrapper'),
                        length = $this.querySelectorAll('.product--item').length,
                        duration = $this.dataset.speed;


                    galleryWrap.forEach(function ($wrap) {

                        let galleryImage = $wrap.querySelectorAll('.product--gallery--image');
                        galleryImage.forEach(function ($gImage, i) {

                            var wrapper = document.createElement('div');
                            wrapper.className = 'parallax--wrap';

                            while ($gImage.firstChild) {
                                wrapper.appendChild($gImage.firstChild);
                            }

                            $gImage.appendChild(wrapper);

                            $gImage.setAttribute('data-index', i)
                            $gImage.classList.add('gallery--image__' + i)
                            gsap.set($gImage, {
                                zIndex: 100 - i
                            })
                            gsap.set($gImage.querySelector('.parallax--wrap'), {
                                width: $gImage.offsetWidth,
                                height: $gImage.offsetHeight
                            })
                        })
                    })


                    productWrap.forEach(function ($wrap) {
                        let product = $wrap.querySelectorAll('.product--item');
                        gsap.set(productWrap, {
                            height: product[0].offsetHeight
                        })
                        product.forEach(function ($product, i) {
                            $product.setAttribute('data-index', i)
                            gsap.set($product, {
                                zIndex: 100 - i
                            })
                        })
                    })

                    let activeIndex = 0
                    $this.querySelector('.nav--next').addEventListener('click', function () {

                        if (activeIndex < length - 1) {
                            activeIndex += 1
                            gsap.to($this.querySelector('.product--vertical--carousel--wrap'), {
                                yPercent: (100 / length) * activeIndex * -1,
                                duration: duration,
                                ease: 'expo.inOut'
                            })
                            galleryWrap.forEach(function ($wrap) {

                                let galleryImage = $wrap.querySelectorAll('.product--gallery--image');
                                galleryImage.forEach(function ($gImage, i) {

                                    if (activeIndex - 1 === i) {
                                        gsap.to($gImage, {
                                            width: 0,
                                            duration: duration,
                                            ease: 'expo.inOut'
                                        })
                                    }
                                })
                            })
                        }
                    })

                    $this.querySelector('.nav--prev').addEventListener('click', function () {

                        if (activeIndex > 0) {
                            activeIndex -= 1
                            gsap.to($this.querySelector('.product--vertical--carousel--wrap'), {
                                yPercent: (100 / length) * activeIndex * -1,
                                duration: duration,
                                ease: 'expo.inOut'
                            })

                            galleryWrap.forEach(function ($wrap) {

                                let galleryImage = $wrap.querySelectorAll('.product--gallery--image');
                                galleryImage.forEach(function ($gImage, i) {

                                    if (activeIndex === i) {
                                        gsap.to($gImage, {
                                            width: '100%',
                                            duration: duration,
                                            ease: 'expo.inOut'
                                        })
                                    }
                                })
                            })
                        }

                    })
                })


            }
        });


        elementorFrontend.hooks.addAction('frontend/element_ready/pecheckoutblock.default', function ($scope, $) {

            if (document.body.classList.contains('e-preview--show-hidden-elements')) {
                zeyna_CheckoutPage();
            }

        })



        elementorFrontend.hooks.addAction('frontend/element_ready/peslider.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i];

                peSlider(scope);

            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/peinnerpagenavigation.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    nav = scope.querySelector('.pe--inner--page--navigation'),
                    items = scope.querySelectorAll('.inner--nav--element'),
                    follower = scope.querySelector('.nav--follower');

                function getActive(active) {

                    let width = active.getBoundingClientRect().width,
                        height = active.getBoundingClientRect().height,
                        left = active.getBoundingClientRect().left - nav.getBoundingClientRect().left,
                        top = active.getBoundingClientRect().top - nav.getBoundingClientRect().top;

                    if (scope.classList.contains('inner--nav--metro')) {

                        gsap.to(follower, {
                            width: width,
                            height: height,
                            left: left,
                            top: top,
                            duration: 1,
                            ease: 'expo.out',
                            overwrite: true
                        })
                    }

                }

                getActive(scope.querySelector('.active'));

                items.forEach(item => {

                    let target = item.dataset.scrollTo;

                    ScrollTrigger.create({
                        trigger: target,
                        start: 'top center',
                        end: 'bottom+=10 center',
                        onEnter: () => {
                            scope.querySelector('.active').classList.remove('active');
                            item.classList.add('active');
                            getActive(item);

                        },
                        onEnterBack: () => {
                            scope.querySelector('.active').classList.remove('active');
                            item.classList.add('active');
                            getActive(item);

                        }
                    })

                    item.addEventListener('click', () => {

                        scope.querySelector('.active').classList.remove('active');
                        item.classList.add('active');

                        getActive(item);

                    })

                })

                if (scope.classList.contains('inner--nav--fraction')) {

                    let fractions = scope.querySelectorAll('.inner--nav--fracs > span'),
                        fracs = Array.from(fractions);

                    ScrollTrigger.create({
                        trigger: 'main',
                        start: 'top top',
                        end: 'bottom bottom',
                        // markers: true,
                        onUpdate: self => {

                            let prog = Math.floor(self.progress * fracs.length);

                            fractions.forEach(el => el.classList.remove('active', 'prev', 'next', 'next-next', 'prev-prev'));
                            if (fracs[prog]) fracs[prog].classList.add('active');
                            if (fracs[prog - 2]) fracs[prog - 2].classList.add('prev-prev');
                            if (fracs[prog - 1]) fracs[prog - 1].classList.add('prev');
                            if (fracs[prog + 1]) fracs[prog + 1].classList.add('next');
                            if (fracs[prog + 2]) fracs[prog + 2].classList.add('next-next');

                        }
                    })

                }

            }

        })

        elementorFrontend.hooks.addAction('frontend/element_ready/petable.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    rows = scope.querySelectorAll('.pe--table--row');


                rows.forEach(row => {

                    let image = row.querySelector('.pe--table--row--image');

                    row.addEventListener("mouseenter", (e) => {


                        if (image) {
                            gsap.set(image, {
                                x: e.layerX,
                                y: e.layerY,
                                yPercent: -50
                            })
                        }


                    });
                    row.addEventListener("mousemove", (e) => {
                        if (image) {
                            gsap.to(image, {
                                x: e.layerX + 10,
                                y: e.layerY + 10,
                                rotate: e.movementX / 2
                            })
                        }

                    });




                })


            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/pelist.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    list = scope.querySelector('.pe--list'),
                    items = scope.querySelectorAll('.pe--list--item');

                if (scope.classList.contains('list--type--cylinder')) {

                    const offset = 0.4;
                    const radius = Math.min(window.innerWidth, window.innerHeight) * offset;
                    const spacing = 130 / items.length;

                    items.forEach((item, index) => {
                        const angle = (index * spacing * Math.PI) / 180;
                        const rotationAngle = index * -spacing; s

                        const x = 0;
                        const y = Math.sin(angle) * radius;
                        const z = Math.cos(angle) * radius;

                        item.style.transform = `translate3d(-50%, -50%, 0) translate3d(${x}px, ${y}px, ${z}px) rotateX(${rotationAngle}deg)`;
                    });

                    ScrollTrigger.getById(scope.dataset.id) ? ScrollTrigger.getById(scope.dataset.id).kill(true) : '';

                    var pinTarget = list.dataset.pinTarget && document.querySelector(list.dataset.pinTarget) ? document.querySelector(list.dataset.pinTarget) : scope;

                    ScrollTrigger.create({
                        trigger: pinTarget,
                        start: "center center",
                        end: "+=2000",
                        id: scope.dataset.id,
                        // markers: true,
                        pin: pinTarget,
                        pinSpacing: true,
                        scrub: true,
                        animation: gsap.fromTo(
                            scope.querySelector('.pe--list--wrapper'),
                            { rotateX: -90 },
                            { rotateX: 120, ease: "none" }
                        ),
                    });

                }

                items.forEach(item => {

                    let image = item.querySelector('.pe--list--item--image');

                    if (scope.querySelector('.pe--list--images--wrap')) {
                        let image = scope.querySelector('.pe--list--item--image.image--' + item.dataset.index);

                        item.addEventListener("mouseenter", (e) => {

                            if (image) {
                                gsap.set(image, {
                                    opacity: 1
                                })
                            }

                        });
                        item.addEventListener("mouseleave", (e) => {
                            if (image) {
                                gsap.set(image, {
                                    opacity: 0
                                })
                            }

                        });
                    } else {

                        item.addEventListener("mouseenter", (e) => {


                            if (image) {
                                gsap.set(image, {
                                    x: scope.classList.contains('images--style--default') && e.layerX,
                                    y: e.layerY,
                                    yPercent: -50
                                })
                            }


                        });
                        item.addEventListener("mousemove", (e) => {
                            if (image) {
                                gsap.to(image, {
                                    x: scope.classList.contains('images--style--default') && e.layerX + 10,
                                    y: e.layerY + 10,
                                    rotate: scope.classList.contains('images--style--default') && e.movementX / 2
                                })
                            }

                        });
                    };



                });


            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/pegooglemaps.default', function ($scope, $) {


            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i];

                function initMaps() {
                    function initMapSingle(latitude, longitude, zoomLevel, mapStyles, markerIcon) {

                        var mapOptions = {
                            zoom: zoomLevel,
                            center: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
                            styles: mapStyles,
                            disableDefaultUI: true,
                            mapTypeControl: false,
                            fullscreenControl: false,
                            zoomControl: false,
                            streetViewControl: false,
                            rotateControl: false,
                            scaleControl: false
                        };

                        var map = new google.maps.Map(document.getElementById('pe--google--map'), mapOptions);

                        var markerOptions = {
                            position: mapOptions.center,
                            map: map,
                            icon: {
                                url: markerIcon,
                                scaledSize: new google.maps.Size(60, 60)
                            }
                        };

                        var marker = new google.maps.Marker(markerOptions);
                    }

                    function initMapMulti(markersData, mapStyles, zoomLevel) {
                        var mapOptions = {
                            zoom: zoomLevel,
                            center: {
                                lat: parseFloat(markersData[0].latitude),
                                lng: parseFloat(markersData[0].longitude)
                            },
                            styles: mapStyles,
                            disableDefaultUI: true
                        };

                        var map = new google.maps.Map(mapElement, mapOptions);
                        var markers = [];
                        var infoWindow = new google.maps.InfoWindow(); // InfoWindow oluşturuldu

                        markersData.forEach(function (markerData, i) {
                            var markerOptions = {
                                position: {
                                    lat: parseFloat(markerData.latitude),
                                    lng: parseFloat(markerData.longitude)
                                },
                                map: map,
                                icon: {
                                    url: markerData.icon,
                                    scaledSize: new google.maps.Size(60, 60)
                                },
                                title: markerData.title,
                            };


                            const marker = new google.maps.Marker(markerOptions);
                            markers.push(marker);


                            marker.addListener('click', function () {
                                infoWindow.setContent(`<div class="map-info-window">${markerData.infoWindowContent}</div>`);
                                infoWindow.open(map, marker);

                                if (scope.querySelector('.map--multi--locations--wrapper')) {
                                    if (scope.querySelector('.map--marker--details.active')) {
                                        scope.querySelector('.map--marker--details.active').classList.remove('active');
                                    }

                                    var container = scope.querySelector('.map--multi--locations--wrapper');
                                    var targetElement = container.querySelector('.marker__dets__' + i);

                                    if (targetElement) {
                                        container.scrollTo({
                                            top: targetElement.offsetTop,
                                            left: targetElement.offsetLeft,
                                            behavior: "smooth"
                                        });
                                    }

                                    scope.querySelector('.marker__dets__' + i).classList.add('active');
                                }
                            });

                        });

                        if (scope.querySelector('.view--map--button')) {
                            scope.querySelectorAll('.view--map--button').forEach(button => {
                                button.addEventListener('click', function () {
                                    let index = button.dataset.marker,
                                        targetMarker = markers[parseInt(index)],
                                        parent = parents(button, '.map--marker--details')[0];

                                    if (scope.querySelector('.map--marker--details.active')) {
                                        scope.querySelector('.map--marker--details.active').classList.remove('active');
                                    }

                                    parent.classList.add('active');

                                    if (markers.length > 0) {
                                        markers[index].setAnimation(google.maps.Animation.BOUNCE);
                                        map.setCenter(targetMarker.getPosition());
                                        map.setZoom(8);

                                        // Info window'ı tetikle
                                        infoWindow.setContent(`<div class="map-info-window">${markersData[index].infoWindowContent}</div>`);
                                        infoWindow.open(map, targetMarker);

                                        setTimeout(() => {
                                            markers[index].setAnimation(null);
                                        }, 2000);
                                    }
                                });
                            });
                        }
                    }
                    var mapElement = scope.querySelector('.pe--google--map');

                    if (mapElement) {
                        var zoomLevel = parseInt(mapElement.getAttribute('data-zoom-level'));
                        if (scope.classList.contains('map--type--single')) {
                            var latitude = mapElement.getAttribute('data-latitude');
                            var longitude = mapElement.getAttribute('data-longitude');

                            var mapStyles = JSON.parse(mapElement.getAttribute('data-map-styles'));
                            var markerIcon = mapElement.getAttribute('data-marker-icon');

                            initMapSingle(latitude, longitude, zoomLevel, mapStyles, markerIcon);

                        } else if (scope.classList.contains('map--type--multi')) {

                            var markersData = JSON.parse(mapElement.getAttribute('data-markers'));
                            var mapStyles = JSON.parse(mapElement.getAttribute('data-map-styles'));
                            initMapMulti(markersData, mapStyles, zoomLevel);

                        }
                    }
                }

                if (document.body.classList.contains('e-preview--show-hidden-elements')) {
                    initMaps();
                } else {
                    document.addEventListener('googleMapsLoaded', function () {
                        initMaps();
                    })
                }
            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/pereviews.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    cont = scope.querySelector('.ratings--swiper');

                if (scope.classList.contains('reviews--swiper--slideshow')) {
                    var reviewsSlider = new Swiper(cont, {
                        slidesPerView: 3,
                        speed: 750,
                        effect: 'fade',
                        fadeEffect: {
                            crossFade: true
                        },

                    });


                } else if (scope.classList.contains('reviews--swiper--carousel')) {

                    var reviewsSlider = new Swiper(cont, {
                        slidesPerView: 1,
                        spaceBetween: parseInt(cont.dataset.gap),
                        speed: 750,
                        breakpoints: {

                            570: {
                                slidesPerView: parseInt(cont.dataset.perView),
                                spaceBetween: parseInt(cont.dataset.gap),

                            },
                        }
                    });
                }


            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/petestimonials.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i];

                peSlider(scope);

            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/pesitenavigation.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    header = document.querySelector('.site-header'),
                    nav = scope.querySelector('.site--nav'),
                    toggle = scope.querySelector('.menu--toggle'),
                    menu = scope.querySelector('.site--menu'),
                    elementorBlock = menu.querySelectorAll('div')[0],
                    hideElements = nav.dataset.hideElements,
                    clicks = 0;

                function hideOnMenu(hide) {

                    if (hideElements) {

                        if (hide) {

                            gsap.to(hideElements, {
                                opacity: 0
                            })

                        } else {
                            gsap.to(hideElements, {
                                opacity: 1
                            })
                        }

                    }

                }

                if (!document.body.classList.contains('e-preview--show-hidden-elements')) {
                    if (scope.classList.contains('nav--initialized')) {
                        return false;
                    } else {
                        scope.classList.add('nav--initialized');
                    }
                }

                toggle.addEventListener('click', () => {
                    clicks++

                    ScrollTrigger.refresh(true);

                    if (nav.querySelector('.site--nav--overlay')) {
                        nav.querySelector('.site--nav--overlay').classList.toggle('active');
                    }

                    if (clicks % 2 == 0) {

                        if (nav.querySelectorAll('.st--active').length) {

                            nav.querySelectorAll('.st--active').forEach(st => {
                                st.click();
                            })
                        }

                        // Close
                        toggle.classList.remove('active');
                        enableScroll();

                        if (nav.classList.contains('nav--popup')) {
                            popUpNav(false);

                        } else if (nav.classList.contains('nav--fullscreen')) {
                            fullscreenNav(false);
                        } else if (nav.classList.contains('nav--expand')) {
                            expandNav(false)

                        }
                        hideOnMenu(false)

                    } else {
                        // Open
                        toggle.classList.add('active');
                        elementorBlock.style.visibility = 'visible'
                        disableScroll();

                        hideOnMenu(true)
                        if (nav.classList.contains('nav--popup')) {
                            popUpNav(true)
                        } else if (nav.classList.contains('nav--fullscreen')) {
                            fullscreenNav(true);
                        } else if (nav.classList.contains('nav--expand')) {
                            expandNav(true)
                        }

                    }

                })

                // Popup Navigation 
                function popUpNav(open) {

                    if (open) {

                        gsap.to(menu, {
                            x: scope.classList.contains('popup--pos--center') ? '-50%' : 0,
                            duration: 1.4,
                            ease: 'expo.inOut',
                            overwrite: true,
                            onStart: () => {
                                menu.classList.add('active')
                            }
                        })

                    } else {

                        gsap.to(menu, {
                            x: scope.classList.contains('popup--pos--right') ? '110%' : scope.classList.contains('popup--pos--left') ? '-110%' : '-50%',
                            duration: 1.4,
                            overwrite: true,
                            ease: 'expo.inOut',
                            onComplete: () => {
                                menu.classList.remove('active');
                                elementorBlock.style.visibility = 'hidden'
                            }
                        })
                    }

                    if (nav.querySelector('.site--nav--overlay')) {
                        nav.querySelector('.site--nav--overlay').addEventListener('click', () => {
                            toggle.click();
                        })
                    }

                    window.addEventListener('keydown', function (event) {
                        if (event.key === 'Escape') {
                            toggle.click();
                        }
                    })

                }
                // Popup Navigation 

                // Fullscreen Navigation 
                function fullscreenNav(open) {

                    let header = document.querySelector('.site-header');

                    if (open && scope.classList.contains('switch--header--on--open') && !header.classList.contains('header--switched')) {
                        setTimeout(() => {
                            header.classList.add('header--switched')
                        }, 300);
                    }

                    if (!open && scope.classList.contains('switch--header--on--open')) {
                        setTimeout(() => {
                            header.classList.remove('header--switched')
                        }, 900);
                    }

                    if (nav.classList.contains('overlay--slide')) {

                        if (open) {

                            gsap.to(menu, {
                                height: '100vh',
                                width: '100vw',
                                duration: 1,
                                overwrite: true,
                                ease: 'power4.inOut'
                            })

                            gsap.to('#primary', {
                                y: nav.classList.contains('slide--up') ? '-50vh' : nav.classList.contains('slide--down') ? '50vh' : '0vh',
                                x: nav.classList.contains('slide--left') ? '50vw' : nav.classList.contains('slide--right') ? '-50vw' : '0vw',
                                overwrite: true,
                                duration: 1,
                                ease: 'power4.inOut'
                            })

                        } else {

                            gsap.to(menu, {
                                height: nav.classList.contains('slide--left') || nav.classList.contains('slide--right') ? '100vh' : '0vh',
                                width: nav.classList.contains('slide--left') || nav.classList.contains('slide--right') ? '0vw' : '100vw',
                                duration: 1,
                                overwrite: true,
                                ease: 'power4.inOut'
                            })
                            gsap.to('#primary', {
                                y: '0vh',
                                x: '0vw',
                                duration: 1,
                                ease: 'power4.inOut',
                                overwrite: true,
                                onComplete: () => {
                                    elementorBlock.style.visibility = 'hidden';
                                    clearProps(document.querySelector('#primary'));
                                }
                            })

                        }

                    } else if (nav.classList.contains('overlay--blocks')) {

                        let blocksWrapper = scope.querySelector('.nav--blocks'),
                            duration = blocksWrapper.dataset.duration,
                            stagger = blocksWrapper.dataset.stagger,
                            staggerFrom = blocksWrapper.dataset.staggerFrom;

                        if (open) {

                            gsap.to(scope.querySelectorAll('.fullscreen--menu--block'), {
                                height: '100vh',
                                duration: duration,
                                ease: 'power4.out',
                                overwrite: true,
                                stagger: {
                                    grid: [1, 20],
                                    from: staggerFrom,
                                    amount: stagger,
                                },
                                onStart: () => {
                                    gsap.set([blocksWrapper, menu], {
                                        visibility: 'visible'
                                    })

                                }
                            })


                        } else {

                            gsap.to(scope.querySelectorAll('.fullscreen--menu--block'), {
                                height: '0vh',
                                duration: duration,
                                ease: 'power4.inOut',
                                overwrite: true,
                                id: 'fs--menu--blocks',
                                stagger: {
                                    grid: [1, 20],
                                    from: staggerFrom,
                                    amount: stagger,
                                },
                                onComplete: () => {
                                    elementorBlock.style.visibility = 'hidden'
                                    gsap.set([blocksWrapper, menu], {
                                        visibility: 'hidden'
                                    })

                                }
                            })


                        }


                    } else if (nav.classList.contains('overlay--overlay')) {

                        let overlay = scope.querySelector('.nav_overlay'),
                            bgop = scope.querySelector('.nav_bg_opacity');

                        if (open) {

                            let state = Flip.getState(menu);

                            gsap.set(menu, {
                                height: 'auto'
                            })

                            Flip.from(state, {
                                duration: 1.25,
                                ease: 'expo.inOut',
                                absolute: false,
                                overwrite: true,
                                absoluteOnLeave: false
                            })

                        } else {
                            gsap.to(menu, {
                                height: '0',
                                duration: 1.25,
                                ease: 'expo.inOut',
                                overwrite: true,
                                onComplete: () => {
                                    elementorBlock.style.visibility = 'hidden'


                                }
                            })

                        }



                    } else if (nav.classList.contains('overlay--fade')) {

                        if (open) {

                            gsap.to(menu, {
                                opacity: 1,
                                duration: .5,
                            })


                        } else {

                            gsap.to(menu, {
                                opacity: 0,
                                duration: .5,
                            })

                        }

                    }

                }
                // Fullscreen Navigation 

                // Navigation Expand
                function expandNav(open) {

                    let blockWidth = elementorBlock.getBoundingClientRect().width,
                        blockHeight = elementorBlock.getBoundingClientRect().height;


                    if (nav.dataset.expandElement && document.querySelector(nav.dataset.expandElement)) {

                        const rect = document.querySelector(nav.dataset.expandElement).getBoundingClientRect();
                        const top = rect.top;
                        const left = rect.left;


                        gsap.set(menu, {
                            top: top,
                            left: left,
                            width: scope.classList.contains('expand--pos--full') && rect.width,
                            height: scope.classList.contains('expand--pos--full') && rect.height,
                        });

                    }

                    if (open) {

                        gsap.to(menu, {
                            width: !scope.classList.contains('expand--pos--full') && blockWidth,
                            height: blockHeight,
                            duration: 1,
                            ease: 'expo.out'
                        })

                    } else {

                        gsap.to(menu, {
                            width: !scope.classList.contains('expand--pos--full') && 0,
                            height: 0,
                            duration: 1.25,
                            ease: 'expo.inOut'

                        })

                    }

                }
                // Navigation Expand

            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/pefancyobjects.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    scene = scope.querySelector('.pe--fancy--objects'),
                    wrapper = scope.querySelector('.fancy--objects--wrapper'),
                    objects = wrapper.querySelectorAll('.fancy--object'),
                    target = scene.dataset.eventsTarget,
                    mouseMoveElements = [],
                    prallaxTl = gsap.timeline({
                        scrollTrigger: {
                            trigger: target,
                            start: 'bottom bottom',
                            end: 'bottom top',
                            scrub: 1,
                        }
                    }),
                    fancyWraps = scene.querySelectorAll('.fancy--object--wrap');

                objects.forEach(object => {

                    if (object.classList.contains('motion--rotate')) {

                        gsap.to(object, {
                            rotate: 360,
                            ease: 'none',
                            duration: 15,
                            repeat: -1
                        })

                    } else if (object.classList.contains('motion--floating')) {

                        let tl = gsap.timeline({
                            repeat: -1
                        }),
                            xRand = gsap.utils.random(-50, 50),
                            yRand = gsap.utils.random(-50, 50);

                        tl.to(object, {
                            y: xRand,
                            x: yRand,
                            ease: 'none',
                            duration: 5,
                        })

                        tl.to(object, {
                            y: 0,
                            x: 0,
                            ease: 'none',
                            duration: 5,
                        })

                    } else if (object.classList.contains('motion--mousemove')) {

                        mouseMoveElements.push({
                            element: object,
                            xVal: gsap.utils.random(-75, 75),
                            yVal: gsap.utils.random(-75, 75)
                        })

                    } else if (object.classList.contains('motion--parallax')) {

                        prallaxTl.to(object, {
                            y: gsap.utils.random(-100, 100),
                        }, 0)

                    }

                })

                if (mouseMoveElements.length > 0) {

                    let windowWidth = window.innerWidth;
                    let windowHeight = window.innerHeight;

                    let movementStrength = 5;

                    document.querySelector(target).addEventListener('mousemove', (e) => {

                        let mouseX = e.clientX;
                        let mouseY = e.clientY;

                        for (let i = 0; i < mouseMoveElements.length; i++) {
                            let element = mouseMoveElements[i].element;
                            let movementX = (mouseX - windowWidth / 2) / windowWidth * movementStrength * mouseMoveElements[i].xVal;
                            let movementY = (mouseY - windowHeight / 2) / windowHeight * movementStrength * mouseMoveElements[i].yVal;

                            gsap.to(element, {
                                x: movementX,
                                y: movementY,
                                ease: "power1.out",
                                duration: 0.5
                            });
                        }
                    });
                }

                if (!scope.classList.contains('entrance--none')) {

                    let delay = scene.dataset.entranceDelay;

                    gsap.to(fancyWraps, {
                        scale: 1,
                        y: 0,
                        opacity: 1,
                        duration: 1,
                        stagger: 0.05,
                        ease: 'expo.out',
                        delay: delay ? delay : .3,
                        scrollTrigger: {
                            trigger: target,
                            start: 'center-=50 center',
                            end: 'bottom top'
                        }
                    })

                }



            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/penavmenu.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i];

                var nav = scope.querySelector('#site-navigation'),
                    menus = scope.querySelectorAll('ul'),
                    parentMenu = scope.querySelector('ul.main-menu'),
                    items = nav.querySelectorAll('.menu-item'),
                    toggleHTML = nav.dataset.subToggle,
                    toggle = document.createElement('div');

                if (!document.body.classList.contains('e-preview--show-hidden-elements')) {
                    if (scope.classList.contains('nav--initialized')) {
                        return false;
                    } else {
                        scope.classList.add('nav--initialized');
                    }
                }

                items.forEach(item => {

                    let idClass = item.classList[item.classList.length - 1],
                        num = idClass.match(/\d+$/)[0],
                        id = parseInt(num, 10);

                    peCursorInteraction(item.querySelector('a'), 'default')

                    item.querySelector('a').addEventListener('click', () => {
                        parentMenu.classList.add('menu--clicked');

                        if (document.querySelector('.current-menu-item')) {
                            document.querySelectorAll('.current-menu-item').forEach(el => el.classList.remove('current-menu-item', 'page_item', 'current_page_item'))
                        };

                        item.classList.add('current-menu-item', 'page_item', 'current_page_item');
                        document.querySelectorAll('.menu-item-' + id).forEach(el => el.classList.add('current-menu-item', 'page_item', 'current_page_item'))

                        if (parentMenu.classList.contains('menu--horizontal')) {
                            gsap.set(parentMenu, {
                                '--left': item.getBoundingClientRect().left - parentMenu.getBoundingClientRect().left + 'px',
                                '--width': item.getBoundingClientRect().width + 'px'
                            })

                        }
                    })

                    let link = item.querySelector('a');

                    if (scope.classList.contains('hover--chars-up')) {

                        SplitText.create(link, {
                            type: "chars , words",
                            charsClass: "menu--item--char",
                            wordsClass: "menu--item--word",
                            autoSplit: true,
                        });




                        link.addEventListener('mouseenter', () => {

                            let chars = link.querySelectorAll('.menu--item--char');

                            chars.forEach((char, i) => {

                                gsap.fromTo(char, {
                                    y: 0
                                }, {
                                    y: -17,
                                    duration: .4,
                                    ease: 'power2.in',
                                    delay: (i * 0.02),
                                    onComplete: () => {
                                        gsap.fromTo(char, {
                                            y: 17
                                        }, {
                                            y: 0,
                                            duration: .4,
                                            ease: 'power2.out',
                                        })

                                    }
                                })

                            })

                        })

                    };

                    if (scope.classList.contains('hover--words-up')) {

                        link.innerHTML = '<span>' + link.innerHTML + '</span>';
                        let span = link.querySelector('span');
                        let clone = span.cloneNode(true);
                        clone.classList.add('menu--item--clone');

                        link.appendChild(clone);

                    };

                    if (scope.classList.contains('nav--one--page') && item.querySelector('a').getAttribute('href').startsWith('#')) {
                        peScrollButton(item.querySelector('a'), true);
                    }

                })

                if (!scope.classList.contains('nav--init')) {

                    scope.classList.add('nav--init');

                    toggle.classList.add('st--wrap');
                    toggle.innerHTML = toggleHTML;

                    var childrens = [];
                    var childNodes = parentMenu.childNodes;

                    for (var i = 0; i < childNodes.length; i++) {

                        if (childNodes[i].nodeType === 1 && childNodes[i].tagName.toLowerCase() === "li") {

                            childrens.push(childNodes[i]);
                        }
                    }

                    if (!parentMenu.classList.contains('menu--horizontal')) {

                        parentMenu.querySelectorAll('.zeyna-sub-menu-wrap').forEach((sub) => {
                            sub.remove();
                        });

                    }

                    childrens.forEach((item, i) => {

                        i++
                        item.setAttribute('data-index', i);

                        if (item.classList.contains('menu-item-has-children') || item.classList.contains('zeyna-has-children')) {

                            let sub = item.querySelector('.sub-menu'),
                                a = item.childNodes[0];

                            item.insertBefore(toggle.cloneNode(true), sub);

                            if (scope.classList.contains('sub--style--expand')) {
                                item.addEventListener('click', (self) => {

                                    let state = Flip.getState(sub);

                                    sub.classList.toggle('sub--flex');

                                    Flip.from(state, {
                                        duration: 1,
                                        ease: 'expo.out',
                                        absolute: false,
                                        absoluteOnLeave: false,
                                    })


                                })
                            }

                            item.querySelector('.st--wrap').addEventListener('click', (self) => {

                                self.target.classList.toggle('st--active');

                                if (item.classList.contains('sub--active')) {

                                    let subState = Flip.getState(sub, {
                                        props: ['padding']
                                    });
                                    item.classList.remove('sub--active');

                                    Flip.from(subState, {
                                        duration: .75,
                                        ease: 'expo.inOut',
                                        absolute: false,
                                        absoluteOnLeave: false,
                                    })

                                } else {

                                    nav.querySelector('.sub--active') ? nav.querySelector('.sub--active > .st--wrap').click() : '';

                                    let subState = Flip.getState(sub, {
                                        props: ['padding']
                                    });

                                    item.classList.add('sub--active');

                                    Flip.from(subState, {
                                        duration: .75,
                                        ease: 'expo.inOut',
                                        absolute: false,
                                        absoluteOnLeave: false,
                                    })
                                }

                            });

                            // if (!nav.classList.contains('st--only')) {

                            //     a.addEventListener('click', (e) => {
                            //         e.preventDefault();
                            //         item.querySelector('.st--wrap').click();
                            //     })

                            // }

                        }
                        // Has Children

                    })

                }

                if (scope.classList.contains('hover--background-follower') || scope.classList.contains('active--background-follower')) {

                    if (mobileQuery.matches) {
                        return;
                    }

                    var activeItem;

                    if (scope.querySelector('.current-menu-item')) {
                        activeItem = scope.querySelector('.current-menu-item');
                        gsap.set(parentMenu, {
                            '--left': activeItem.getBoundingClientRect().left - parentMenu.getBoundingClientRect().left + 'px',
                            '--width': activeItem.getBoundingClientRect().width + 'px'
                        })
                    } else {
                        activeItem = false;
                    }

                    items.forEach(item => {

                        item.addEventListener('mouseenter', () => {
                            gsap.to(parentMenu, {
                                '--left': item.getBoundingClientRect().left - parentMenu.getBoundingClientRect().left + 'px',
                                '--width': item.getBoundingClientRect().width + 'px'
                            })
                        })

                        if (activeItem) {

                            parentMenu.addEventListener('mouseleave', () => {
                                if (!parentMenu.classList.contains('menu--clicked')) {
                                    gsap.to(parentMenu, {
                                        '--left': activeItem.getBoundingClientRect().left - parentMenu.getBoundingClientRect().left + 'px',
                                        '--width': activeItem.getBoundingClientRect().width + 'px'
                                    })
                                }

                            })

                        } else {

                            item.addEventListener('mouseleave', () => {
                                gsap.to(parentMenu, {
                                    '--left': item.getBoundingClientRect().left - parentMenu.getBoundingClientRect().left + 'px',
                                    '--width': '0px'
                                })
                            })

                        }

                    })

                }

                if (parentMenu.classList.contains('menu--toggled')) {
                    let peToggle = scope.querySelector('.pe--menu--toggle');


                    function toggleMenu(enter) {

                        var state = Flip.getState([items, peToggle], {
                            props: ['display']
                        });

                        enter ? parentMenu.classList.add('menu--open') : parentMenu.classList.remove('menu--open');
                        enter ? peToggle.classList.add('active') : peToggle.classList.remove('active');

                        Flip.from(state, {
                            duration: .75,
                            ease: 'power3.inOut',
                            absolute: true,
                            absoluteOnLeave: true,
                            onEnter: elements => gsap.fromTo(elements, {
                                opacity: 0,
                                y: 100
                            }, {
                                opacity: 1,
                                y: 0,
                                duration: .5,
                                ease: 'expo.inOut',
                                stagger: -0.1
                            }),
                            onLeave: elements => gsap.fromTo(elements, {
                                opacity: 1,
                                y: 0
                            }, {
                                opacity: 0,
                                y: 100,
                                duration: .5,
                                ease: 'expo.inOut',
                                stagger: 0.1
                            }),
                        })


                    }

                    var clicks = 0;
                    peToggle.addEventListener('click', () => {
                        clicks++
                        if (clicks % 2 == 0) {

                            toggleMenu(false)
                        } else {

                            toggleMenu(true)
                        }

                    });



                }

                if (scope.classList.contains('nav--one--page')) {

                    items.forEach(item => {

                        let link = item.querySelector('a'),
                            target = link.getAttribute('href'),
                            section = document.querySelector(target);

                        if (section) {

                            setTimeout(() => {

                                ScrollTrigger.create({
                                    trigger: section,
                                    start: 'top center',
                                    invalidateOnRefresh: true,
                                    refreshPriotiry: 1,
                                    onEnter: () => {
                                        if (nav.querySelector('.current-menu-item')) {
                                            nav.querySelector('.current-menu-item').classList.remove('current-menu-item');
                                        }
                                        item.classList.add('current-menu-item');
                                    },
                                    onEnterBack: () => {
                                        if (nav.querySelector('.current-menu-item')) {
                                            nav.querySelector('.current-menu-item').classList.remove('current-menu-item');
                                        }
                                        item.classList.add('current-menu-item');
                                    }
                                })


                            }, 200);

                        }

                    })

                }

            }
        });


        elementorFrontend.hooks.addAction('frontend/element_ready/petemplatepopup.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i];

                if (scope.querySelector('.pe--styled--popup')) {
                    pePopup(scope, scope);
                }

                if (!zeynaLenis) {

                    // OPTIMIZED: Popup Lenis - GSAP ticker
                    const popLenis = new Lenis({
                        wrapper: scope.querySelector('.zeyna--popup--template'),
                        smooth: true,
                        smoothTouch: false
                    });

                    gsap.ticker.add((time) => {
                        popLenis.raf(time * 1000);
                    });

                }

            };

        })


        elementorFrontend.hooks.addAction('frontend/element_ready/peportfoliosearch.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    wrapper = scope.querySelector('.zeyna--portfolio--search'),
                    form = scope.querySelector('#zeyna--portfolio--search--form');

                if (form.querySelector('.pe--checkbox--field--set--wrapper')) {

                    var checkboxFields = form.querySelectorAll('.pe--checkbox--field--set--wrapper');

                    checkboxFields.forEach(fieldset => {

                        let label = fieldset.querySelector('.pe--checkbox--field--set--label'),
                            pop = fieldset.querySelector('.pe--checkbox--field--set'),
                            inputs = fieldset.querySelectorAll('input'),
                            selection = fieldset.querySelector('.pe--checbox--field--set--selection');
                        label.addEventListener('click', () => {

                            if (form.querySelector('.open')) {
                                form.querySelector('.open').classList.add('hide');
                                form.querySelector('.open').classList.remove('open');
                            } else {
                                fieldset.classList.toggle('open');
                            }


                        })




                        inputs.forEach(input => {

                            input.addEventListener('change', () => {
                                if (input.checked) {
                                    let label = parents(input, 'label')[0];
                                    selection.innerHTML += '<span class="checked-' + input.value + '">' + label.querySelector('span').innerHTML + ', </span>';
                                } else {

                                    selection.querySelector('span.checked-' + input.value).remove();
                                }
                                const inputArray = Array.from(inputs);
                                const hasVal = inputArray.some(input => input.checked);
                                hasVal ? fieldset.classList.add('has--val') : fieldset.classList.remove('has--val');

                            })


                        })



                    })

                }

                scope.querySelectorAll('.pe--custom--select').forEach(select => peSelectBox(select))


            }

        })

        elementorFrontend.hooks.addAction('frontend/element_ready/pewooajaxsearch.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    wrapper = scope.querySelector('.zeyna--woo--ajax--search');

                if (!wrapper.classList.contains('search--default') && !wrapper.classList.contains('search--popup')) {

                    let button = scope.querySelector('.zeyna--woo--ajax--search--button'),
                        form = scope.querySelector('.zeyna-woo-ajax-search-form'),
                        overlay = scope.querySelector('.ajax--search--overlay');

                    button.addEventListener('click', () => {
                        wrapper.classList.toggle('active');
                    })

                    overlay.addEventListener('click', () => {
                        wrapper.classList.toggle('active');
                    })

                }

                if (wrapper.classList.contains('search--popup')) {
                    pePopup(scope, wrapper);
                }

                function wooAjaxSearch() {
                    var searchForm = $(scope).find('#zeyna-woo-ajax-search-form'),
                        parent = $(searchForm).closest('.zeyna--woo--ajax--search'),
                        count = parent.data('results-count'),
                        total,
                        debounceTimer;

                    if (!searchForm.length) {
                        return false;
                    }

                    $(scope).find('#zeyna-woo-search-input').on('keyup', function (e, offs) {
                        let searchQuery = $(this).val(),
                            resultsContainer = $(scope).find('.s--woo--search--results');

                        clearTimeout(debounceTimer);

                        debounceTimer = setTimeout(() => {
                            if (searchQuery && searchQuery.length > 2) {
                                $.ajax({
                                    url: woocommerce_params.ajax_url,
                                    type: 'POST',
                                    data: {
                                        action: 'woocommerce_ajax_search',
                                        search_query: searchQuery,
                                        count: parseInt(count),
                                        offset: offs ? offs : 0
                                    },
                                    beforeSend: function () {
                                        wrapper.classList.add('searching');
                                    },
                                    success: function (response) {
                                        const responseDoc = $.parseHTML(response)[0],
                                            products = responseDoc.querySelectorAll('.zeyna--search--product');

                                        total = responseDoc.dataset.total;

                                        if (parseInt(total) < count) {
                                            wrapper.classList.remove('has--pagination')
                                        }

                                        setTimeout(() => {
                                            wrapper.classList.remove('searching');

                                            if (offs) {
                                                products.forEach(product => {
                                                    scope.querySelector('.zeyna--ajax--search--result').appendChild(product);
                                                })
                                                let offset = (offs + 1) * parseInt(count);

                                                if (offset >= parseInt(total)) {
                                                    wrapper.classList.remove('has--pagination')
                                                }

                                            } else {
                                                resultsContainer.html(response).fadeIn();

                                                gsap.fromTo(scope.querySelector('.search--results-wrap'), {
                                                    opacity: 0,
                                                    yPercent: -20
                                                }, {
                                                    opacity: 1,
                                                    yPercent: 0,
                                                    delay: 0.5,
                                                    overwrite: true,
                                                    onComplete: () => {
                                                        total = responseDoc.dataset.total;

                                                        if (parseInt(total) > count) {
                                                            wrapper.classList.add('has--pagination')
                                                        } else {
                                                            wrapper.classList.remove('has--pagination')
                                                        }
                                                    }
                                                });
                                            }

                                            $(scope).find('a').on('click', function () {
                                                let searchPopButton = $(scope).find('.pe--search--pop--button');
                                                if (searchPopButton.length) {

                                                    searchPopButton.trigger('click');

                                                    let fullscreenNav = $(this).closest('.nav--fullscreen');

                                                    if (fullscreenNav.length) {
                                                        fullscreenNav.find('.menu--toggle').trigger('click');
                                                    }
                                                }
                                            });

                                            if (!zeynaLenis) {
                                                // OPTIMIZED: Search Lenis - GSAP ticker
                                                const searchLenis = new Lenis({
                                                    wrapper: scope.querySelector('.search--results-wrap'),
                                                    smooth: false,
                                                    smoothTouch: false
                                                });

                                                gsap.ticker.add((time) => {
                                                    searchLenis.raf(time * 1000);
                                                });

                                            }

                                        }, 1000);
                                    }
                                });

                            } else {
                                resultsContainer.hide();
                            }
                        }, 500);
                    });

                    if (scope.querySelector('.search--products--load--more')) {

                        var button = scope.querySelector('.search--products--load--more'),
                            clicks = 0;

                        button.addEventListener('click', () => {
                            clicks++
                            $(scope).find('#zeyna-woo-search-input').trigger('keyup', [clicks]);
                        })

                    }

                    if (scope.querySelector('.woo--ajax--search--tags')) {

                        let tags = scope.querySelectorAll('.search-tag');

                        tags.forEach(tag => {
                            tag.addEventListener('click', () => {
                                scope.querySelector('#zeyna-woo-search-input').value = tag.dataset.val;
                                $(scope).find('#zeyna-woo-search-input').trigger('keyup');
                            })
                        })



                    }

                }

                wooAjaxSearch();


            }
        })



        elementorFrontend.hooks.addAction('frontend/element_ready/petextwrapper.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    wrapper = scope.querySelector('.text-wrapper');


                // document.fonts.ready.then((fontFaceSet) => {
                //Inner Elements
                var innerElements = wrapper.querySelectorAll('[class^="inner--"] , .customized--word');

                innerElements.forEach((element) => {

                    let classes = element.classList,
                        hasMotion = Array.from(classes).some(className => className.startsWith('me--'));

                    // Motion Effects
                    if (hasMotion) {

                        let motion = hasMotion ? Array.from(classes).find(className => className.startsWith('me--')) : null,
                            duration = element.dataset.duration,
                            delay = element.dataset.delay,
                            ease = motion === 'me--flip-x' ? 'none' : motion === 'me--flip-y' ? 'none' : motion === 'me--hearthbeat-x' ? 'power4.inOut' : motion === 'me--slide-left' ? 'power3.in' : motion === 'me--slide-right' ? 'power3.in' : motion === 'me--rotate' ? 'none' : 'expo.out',
                            tl = gsap.timeline({
                                repeat: -1,
                                paused: true,
                                repeatDelay: parseInt(delay, 10),
                                scrollTrigger: {
                                    trigger: scope,
                                    start: 'top bottom',
                                    end: 'bottom top',
                                    toggleActions: "play pause play pause",
                                }
                            }),
                            target = element;

                        if (motion === 'me--slide-left' || motion === 'me--slide-right') {
                            target = element.firstElementChild ? element.firstElementChild : element;
                        }

                        tl.fromTo(target, {
                            xPercent: 0,
                            yPercent: 0,
                            scale: motion === 'me--hearth-beat' ? 0.6 : 1
                        }, {

                            scale: 1,
                            rotate: motion === 'me--rotate' ? -360 : 0,
                            rotateX: motion === 'me--flip-x' ? -360 : 0,
                            rotateY: motion === 'me--flip-y' ? -360 : 0,
                            xPercent: motion === 'me--slide-left' ? -100 : motion === 'me--slide-right' ? 100 : 0,
                            yPercent: motion === 'me--slide-up' ? -200 : motion === 'me--slide-down' ? 200 : 0,
                            duration: duration,
                            ease: ease,
                        })


                        if (motion === 'me--slide-left' || motion === 'me--slide-right') {

                            tl.fromTo(target, {
                                xPercent: motion === 'me--slide-left' ? 100 : motion === 'me--slide-right' ? -100 : 0
                            }, {
                                xPercent: 0,
                                duration: duration,
                                ease: 'power3.out',
                            })
                        }

                        if (motion === 'me--slide-up' || motion === 'me--slide-down') {

                            tl.fromTo(target, {
                                yPercent: motion === 'me--slide-up' ? 200 : motion === 'me--slide-down' ? -200 : 0,
                            }, {
                                yPercent: 0,
                                duration: duration,
                                ease: 'power3.out',
                            })
                        }

                        if (motion === 'me--hearth-beat') {

                            tl.to(target, {
                                scale: 0.6,
                                duration: duration
                            })
                        }

                    }

                    if (element.classList.contains('inserted--pin')) {

                        let state = Flip.getState(element),
                            target = element.dataset.zoomPin ? element.dataset.zoomPin : scope,
                            tl = gsap.timeline({
                                scrollTrigger: {
                                    trigger: target,
                                    scrub: true,
                                    pin: true,
                                    start: 'center center',
                                    end: 'bottom+=500 top',
                                }
                            });

                        gsap.set(element, {
                            position: 'fixed',
                            width: '100vw',
                            height: '100vh',
                            top: 0,
                            left: 0,

                        })

                        let fl = Flip.from(state, {
                            absolute: false,
                            absoluteOnLeave: false,
                        })

                        tl.add(fl, 0)

                    }

                    if (element.classList.contains('draw--svg')) {

                        let paths = element.querySelectorAll('line , ellipse , polygon , rect , g , path , circle');

                        gsap.from(paths, {
                            drawSVG: 0,
                            delay: 0,
                            ease: "power4.inOut",
                            duration: 2,
                            scrollTrigger: {
                                trigger: scope,
                                scrub: element.classList.contains('draw--svg--scrub') && 1,
                                start: 'top bottom',
                                end: 'bottom center',
                            },
                            onStart: () => {
                                element.classList.add('draw--start');
                            },
                            onComplete: () => {
                                clearProps(paths)
                            }
                            // stagger: stagger,
                        })

                    }

                })
                //Inner Elements

                //Dyanmic words
                function dynamicWordAnimation() {

                    if (!wrapper.querySelector('.pe-dynamic-words')) {
                        return;
                    }

                    let dynamicWords = wrapper.querySelectorAll('.pe-dynamic-words');

                    let p = wrapper.querySelector('* > p'),
                        lHeight = getComputedStyle(p).lineHeight;

                    wrapper.querySelector('span.pe-dynamic-words').style.lineHeight = lHeight;
                    wrapper.querySelector('span.pe-dynamic-words').style.height = lHeight;

                    dynamicWords.forEach((dynamic) => {

                        if (!dynamic.classList.contains('dyno--init')) {
                            dynamic.classList.add('dyno--init');

                            let innerWrap = dynamic.firstElementChild,
                                duration = parseFloat(dynamic.dataset.duration),
                                delay = parseFloat(dynamic.dataset.delay),
                                pin = dynamic.dataset.pin,
                                scrub = dynamic.dataset.scrub,
                                scroll = false;

                            if (pin === 'true' || scrub === 'true') {
                                scroll = {
                                    trigger: scope,
                                    pin: pin === 'true' ? scope : false,
                                    scrub: 1,
                                    start: pin === 'true' ? 'center center' : 'top bottom',
                                    end: pin === 'true' ? 'bottom+=500 top' : 'top top',
                                };
                            }

                            gsap.getById(scope.dataset.id) ? gsap.getById(scope.dataset.id).revert(true) : '';
                            gsap.getById(scope.dataset.id) ? gsap.getById(scope.dataset.id).scrollTrigger.kill(true) : '';

                            let lastLabel = null;
                            if (dynamic.classList.contains('dyno--slide')) {
                                let words = innerWrap.querySelectorAll('span');
                            }


                            let tl = gsap.timeline({
                                repeat: pin === 'true' ? 0 : scrub === 'true' ? 0 : -1,
                                scrollTrigger: scroll,
                                id: scope.dataset.id,
                                onUpdate: () => {
                                    const label = tl.currentLabel();

                                    // Label değişmediyse hiçbir şey yapma
                                    if (!label || label === lastLabel) return;
                                    lastLabel = label;

                                    // label_X formatından X'i al
                                    const index = parseInt(label.split('_')[1]);

                                    if (dynamic.classList.contains('dyno--slide')) {

                                        // active sınıfını güncelle
                                        words.forEach((w, i) => {
                                            if (i === index - 1) {
                                                w.classList.add('active');
                                            } else {
                                                w.classList.remove('active');
                                            }
                                        });
                                    }
                                }
                            });

                            if (dynamic.classList.contains('dyno--slide')) {

                                dynamic.style.width = Math.ceil(words[0].getBoundingClientRect().width) + 'px';
                                words.forEach((word, i) => {

                                    word.classList.add('word-' + i);

                                    tl.to(innerWrap, {
                                        yPercent: -100 / words.length * i,
                                        duration: duration,
                                        delay: i == 0 ? 0 : delay,
                                        ease: scroll ? 'none' : 'expo.inOut',
                                    }, 'label_' + i)

                                    tl.to(dynamic, {
                                        width: Math.ceil(word.getBoundingClientRect().width),
                                        duration: duration,
                                        delay: delay,
                                        ease: scroll ? 'none' : 'expo.inOut'
                                    }, 'label_' + i)

                                })

                            } else {

                                let words = dynamic.dataset.words.split('/');

                                words.forEach((word, i) => {
                                    if (word) {
                                        tl.to(dynamic, {
                                            text: { value: word, delimiter: '' },
                                            duration: duration,
                                            delay: delay,
                                            ease: 'none'
                                        }, 'label_' + i)

                                        if (i !== (words.length - 1)) {
                                            tl.to(dynamic, {
                                                text: { value: '', delimiter: '' },
                                                duration: duration,
                                                delay: delay,
                                                ease: 'none'
                                            }, 'label_' + i + (i + 1));
                                        }


                                    }
                                });


                            }


                        }



                    })

                }

                dynamicWordAnimation();
                //Dyanmic words

                setTimeout(() => {

                    if (scope.querySelector('.custom-lines-hold')) {

                        for (let i = 0; i < wrapper.childNodes.length; i++) {

                            if (wrapper.childNodes[i].tagName) {
                                SplitText.create(wrapper.childNodes[i], {
                                    type: "lines",
                                    linesClass: "customized--line",
                                    autoSplit: true,
                                });


                            }

                        }

                        wrapper.querySelectorAll('.customized--line').forEach((line, i) => {
                            i++
                            line.classList.add('cs--line--' + i)
                        })

                        let holds = scope.querySelectorAll('.csl--hold');

                        if (holds) {

                            holds.forEach(hold => {

                                let line = hold.dataset.line,
                                    id = hold.dataset.id,
                                    findLine = '.cs--line--' + line;

                                if (scope.querySelector(findLine)) {

                                    scope.querySelector(findLine).classList.add('elementor-repeater-item-' + id);

                                }





                            })
                        }

                    }

                }, 50);

                if (scope.classList.contains('slide--text')) {

                    gsap.to(wrapper, {
                        x: -wrapper.getBoundingClientRect().width + document.body.clientWidth - (wrapper.getBoundingClientRect().left * 2),
                        scrollTrigger: {
                            trigger: wrapper,
                            start: 'center center',
                            ease: 'none',
                            end: 'bottom+=2000 top',
                            scrub: 1,
                            pin: true
                        }
                    })

                }

                // });

            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/pecircletext.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i];

                var $this = scope.querySelector('.pe-circular-text');


                var textWrap = $this.querySelector('.circular-text-wrap'),
                    circularContent = $this.querySelector(".circular-text-content"),
                    dataHeight = $this.dataset.height,
                    dataDuration = $this.dataset.duration,
                    dataTarget = $this.dataset.target;

                if (textWrap.querySelectorAll('.circle-char').length === 0) {
                    let circleSplit = SplitText.create($this.querySelector('.circle-text'), {
                        type: "words, chars",
                        charsClass: "circle-char",
                        wordsClass: "circle-word",
                        position: "absolute",
                        autoSplit: true,
                    })
                }

                let fontSize = parseInt(window.getComputedStyle($this.querySelector('.circle-char')).fontSize),
                    charLength = $this.querySelectorAll('.circle-char').length,
                    textLength = (dataHeight / charLength) / (fontSize / 1.75),
                    circleChar = $this.querySelectorAll('.circle-char'),
                    circleWord = $this.querySelectorAll('.circle-word'),
                    snap = gsap.utils.snap(1),
                    dataIcon = $this.dataset.icon;



                for (var i = 2; i <= snap(textLength); i++) {
                    var clonedContent = circularContent.cloneNode(true);
                    textWrap.appendChild(clonedContent);
                }
                circularContent = $this.querySelectorAll(".circular-text-content");

                gsap.set(circularContent, {
                    width: dataHeight,
                    height: dataHeight
                })

                var circleWordElements = $this.querySelectorAll('.circle-word');

                circleWordElements.forEach(function (circleWordElement) {
                    var circleCharElement = document.createElement('span');
                    circleCharElement.className = 'circle-char';
                    circleWordElement.appendChild(circleCharElement);
                });

                $this.querySelectorAll('.circle-word').forEach(function (circleWordElement) {
                    gsap.set(circleWordElement, {
                        left: '50%',
                        top: 0,
                        height: "100%",
                        xPercent: -50
                    })
                });

                var charElements = $this.querySelectorAll('.circle-char'),
                    rotateMultiplier = 360 / charElements.length;

                charElements.forEach(function (charElement, index) {

                    gsap.set(charElement, {
                        rotate: rotateMultiplier * index,
                        left: '50%',
                        xPercent: -50,
                        top: 0,
                        height: "50%"
                    });

                });

                var tl = gsap.timeline();

                gsap.set(textWrap, {
                    width: dataHeight,
                    height: dataHeight
                });

                if ($this.classList.contains('counter-clockwise')) {
                    tl.to(textWrap, {
                        rotation: -360,
                        duration: dataDuration,
                        ease: "none",
                        repeat: -1
                    });
                } else {
                    tl.to(textWrap, {
                        rotation: 360,
                        duration: dataDuration,
                        ease: "none",
                        repeat: -1
                    });
                }

                let whaler = Hamster(document.querySelector('body')),
                    wheelDeltaY, currentDeltaY;

                function createWheelStopListener(element, callback, timeout) {
                    var handle = null;
                    var onScroll = function () {
                        if (handle) {
                            clearTimeout(handle);
                        }
                        handle = setTimeout(callback, timeout || 200); // 
                    };
                    element.addEventListener('wheel', onScroll);
                    return function () {
                        element.removeEventListener('wheel', onScroll);
                    };
                }

                whaler.wheel(function (event, delta, deltaX, deltaY) {

                    wheelDeltaY = event.deltaY;
                    event.deltaY < 0 ? wheelDeltaY = -1 * event.deltaY : '';
                    tl.timeScale(1 + (wheelDeltaY * 2))

                });

                createWheelStopListener(window, function () {
                    tl.timeScale(1)
                });

                $this.addEventListener('click', function () {
                    window.scrollTo({
                        top: document.querySelector(dataTarget).offsetTop,
                        behavior: "smooth"
                    });
                });

            }
        });


        elementorFrontend.hooks.addAction('frontend/element_ready/pecarousel.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {

                var scope = jsScopeArray[i],
                    carousel = scope.querySelector('.pe--carousel'),
                    wrapper = carousel.querySelector('.carousel--wrapper'),
                    id = carousel.dataset.id ? carousel.dataset.id : scope.dataset.id,
                    items = carousel.querySelectorAll('.carousel--item'),
                    length = items.length,
                    wrapperWidth = wrapper.offsetWidth,
                    carouselWidth = carousel.offsetWidth,
                    trigger = carousel.dataset.trigger ? carousel.dataset.trigger : '.' + carousel.classList[0];

                document.querySelector(trigger) ? document.querySelector(trigger).classList.add('pin--trigger') : '';
                document.querySelector(trigger) ? document.querySelector(trigger).dataset.scrollId = id : '';


                items.forEach(item => {
                    var index = parseInt(item.dataset.index);
                });


                function carouselScroll() {

                    gsap.getById(id) ? gsap.getById(id).scrollTrigger.kill(true) : '';

                    gsap.to(wrapper, {
                        x: isRTL ? wrapperWidth - carouselWidth : (-1 * wrapperWidth) + carouselWidth,
                        ease: 'none',
                        id: id,
                        scrollTrigger: {
                            trigger: trigger,
                            scrub: true,
                            pin: trigger,
                            ease: "elastic.out(1, 0.3)",
                            start: 'center center',
                            end: 'bottom+=6000 bottom',
                            pinSpacing: 'padding',
                            onEnter: () => isPinnng(trigger, true),
                            onEnterBack: () => isPinnng(trigger, true),
                            onLeave: () => isPinnng(trigger, false),
                            onLeaveBack: () => isPinnng(trigger, false),
                        }
                    })


                }

                function carouselDrag() {

                    Draggable.create(wrapper, {
                        type: 'x',
                        // bounds: {
                        //     minX: 0,
                        //     maxX: -wrapperWidth + document.body.clientWidth - 50
                        // },
                        bounds: parents(wrapper, '.pe--carousel')[0],
                        lockAxis: true,
                        dragResistance: 0.5,
                        inertia: true,
                        allowContextMenu: true,
                        allowEventDefault: true,
                    });


                }

                function carouselLoop() {

                    var tl = gsap.timeline({
                        repeat: -1,
                        scrollTrigger: {
                            trigger: scope,
                            start: 'top bottom',
                            end: 'bottom top',
                            toggleActions: "play pause play pause",
                        }
                    }),
                        direction = wrapper.dataset.direction,
                        speed = parseInt(wrapper.dataset.speed),
                        speedUp = wrapper.dataset.speedUp;

                    items.forEach(item => {
                        let clone = item.cloneNode(true);
                        wrapper.appendChild(clone);
                    });

                    if (direction !== 'right-to-left') {
                        // Reverse the order of items and prepend
                        Array.from(items).reverse().forEach(item => {
                            let clone = item.cloneNode(true);
                            wrapper.prepend(clone);
                        });
                    }

                    wrapper.style.width = wrapperWidth * 2

                    let gap = window.getComputedStyle(wrapper).getPropertyValue('gap');

                    if (direction === 'right-to-left') {

                        tl.to(wrapper, {
                            x: -wrapperWidth - parseFloat(gap),
                            duration: speed,
                            ease: 'none',
                        });

                    } else {

                        gsap.set(wrapper, {
                            x: -wrapperWidth - parseFloat(gap)
                        })

                        tl.fromTo(wrapper, {
                            x: -wrapperWidth - parseFloat(gap)
                        }, {
                            x: 0,
                            duration: speed,
                            ease: 'none',
                        })
                    }

                    if (speedUp) {

                        let whaler = Hamster(document.querySelector('body')),
                            wheelDeltaY, currentDeltaY;

                        function createWheelStopListener(element, callback, timeout) {
                            var handle = null;
                            var onScroll = function () {
                                if (handle) {
                                    clearTimeout(handle);
                                }
                                handle = setTimeout(callback, timeout || 200); // 
                            };
                            element.addEventListener('wheel', onScroll);
                            return function () {
                                element.removeEventListener('wheel', onScroll);
                            };
                        }

                        whaler.wheel(function (event, delta, deltaX, deltaY) {

                            wheelDeltaY = event.deltaY;
                            event.deltaY < 0 ? wheelDeltaY = -1 * event.deltaY : '';
                            tl.timeScale(1 + (wheelDeltaY * 3))

                        });

                        createWheelStopListener(window, function () {
                            tl.timeScale(1)
                        });

                    }

                    if (scope.classList.contains('pause--on--hover')) {

                        wrapper.addEventListener('mouseenter', () => {
                            tl.pause();
                        })

                        wrapper.addEventListener('mouseleave', () => {
                            tl.play();
                        })

                    }

                }

                carousel.classList.contains('cr--scroll') ? carouselScroll() : carousel.classList.contains('cr--drag') ? carouselDrag() : carouselLoop();

                if (scope.classList.contains('cr--drag--mobile')) {

                    matchMedia.add({
                        isMobile: "(max-width: 570px)"
                    }, (context) => {
                        let {
                            isMobile
                        } = context.conditions;

                        gsap.getById(scope.dataset.id) && gsap.getById(scope.dataset.id).revert();

                        Draggable.create(wrapper, {
                            type: 'x',
                            bounds: scope,
                            lockAxis: true,
                            dragResistance: 0.5,
                            inertia: true,
                            allowContextMenu: true
                        });

                    });
                }



                if (scope.querySelector('.product--archive--gallery')) {

                    let swiperCont = scope.querySelectorAll('.product--archive--gallery');

                    swiperCont.forEach(cont => {

                        var productArchiveGallery = new Swiper(cont, {
                            slidesPerView: 1,
                            speed: 750,
                            navigation: {
                                nextEl: cont.querySelector('.pag--next'),
                                prevEl: cont.querySelector('.pag--prev'),
                            },
                        });

                    });

                }


            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/pewebglcarousel.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {

                var scope = jsScopeArray[i],
                    carousel = scope.querySelector('.pe--webgl--carousel'),
                    wrapper = carousel.querySelector('.pe--webgl--carousel--wrapper'),
                    id = carousel.dataset.id ? carousel.dataset.id : scope.dataset.id;

                // if (scope.classList.contains('canvas--initalized')) {
                //     return;
                // }
                // scope.classList.add('canvas--initalized')    

                var style = getComputedStyle(scope),
                    planesWidth = parseInt(style.getPropertyValue('--imagesWidth').trim()),
                    planesHeight = parseInt(style.getPropertyValue('--imagesHeight').trim()),
                    planesGap = parseInt(style.getPropertyValue('--carouselGap').trim());

                if (!planesWidth) planesWidth = 350;
                if (!planesHeight) planesHeight = 467;
                if (!planesGap) planesGap = 75;


                const clamp = (value, min, max) => Math.min(Math.max(value, min), max);
                const lerp = (a, b, t) => a + (b - a) * t;

                class webglCarousel {

                    constructor(container) {
                        this.container = container;
                        this.DOMElements = [...this.container.querySelectorAll('.pe--webgl--project')];

                        this.renderer = new THREE.WebGLRenderer({
                            antialias: true,
                            alpha: true,
                        });

                        this.renderer.setPixelRatio(Math.min(1.5, window.devicePixelRatio));
                        this.renderer.setSize(window.innerWidth, window.innerHeight);
                        this.renderer.domElement.classList.add('content__canvas');
                        this.container.appendChild(this.renderer.domElement);

                        this.scene = new THREE.Scene();

                        const width = window.innerWidth;
                        const height = window.innerHeight;

                        this.camera = new THREE.PerspectiveCamera(
                            45,
                            width / height,
                            1,
                            3000
                        );

                        // Kamera Z mesafesini px’e göre ayarla
                        this.camera.position.z =
                            height / (2 * Math.tan((this.camera.fov * Math.PI) / 360));

                        this.raycaster = new THREE.Raycaster();
                        this.mouseNDC = new THREE.Vector2();
                        this.hoveredPlane = null;
                        this.clicked = false;

                        this.scroll = {
                            ease: 0.08,
                            current: 0,
                            target: 0,
                            last: 0,
                            velocity: 0
                        };

                        this.mouse = {
                            current: new THREE.Vector2(0, 0),
                            target: new THREE.Vector2(0, 0),
                            ease: 0.1
                        };

                        this.touch = {
                            startX: 0,
                            startY: 0,
                            lastX: 0,
                            lastY: 0,
                            isDown: false
                        };

                        this.setUpPlanes();

                        // base positions
                        this.scene.children.forEach((plane, i) => {
                            plane.userData.baseX = i * (planesWidth + planesGap);
                        });

                        // carousel limits
                        this.planeWidth = planesWidth + planesGap; // plane aralığı (baseX ile aynı)
                        this.totalPlanes = this.scene.children.length;

                        // scroll sınırları
                        this.scroll.min = 0;
                        this.scroll.max = (this.totalPlanes - 1) * this.planeWidth;

                        this.render();

                        window.addEventListener('wheel', this.onWheel.bind(this), { passive: true });
                        window.addEventListener('mousemove', this.onMouseMove.bind(this));
                        window.addEventListener('click', this.onClick.bind(this));

                        window.addEventListener('resize', this.onResize.bind(this));
                        window.addEventListener('touchstart', this.onTouchStart.bind(this), { passive: true });
                        window.addEventListener('touchmove', this.onTouchMove.bind(this), { passive: true });
                        window.addEventListener('touchend', this.onTouchEnd.bind(this));


                        if (window.barba) {
                            barba.hooks.before(() => {
                                window.removeEventListener('wheel', this.onWheel.bind(this), { passive: true });
                                window.removeEventListener('mousemove', this.onMouseMove.bind(this));
                                window.removeEventListener('click', this.onClick.bind(this));

                                window.removeEventListener('resize', this.onResize.bind(this));
                                window.removeEventListener('touchstart', this.onTouchStart.bind(this), { passive: true });
                                window.removeEventListener('touchmove', this.onTouchMove.bind(this), { passive: true });
                                window.removeEventListener('touchend', this.onTouchEnd.bind(this));
                            });
                        }

                    }

                    onWheel(e) {
                        this.scroll.target += e.deltaY * 0.25; // 👈 speed

                        this.scroll.target = clamp(
                            this.scroll.target,
                            this.scroll.min,
                            this.scroll.max
                        );

                    }
                    onMouseMove(e) {
                        this.mouseNDC.x = (e.clientX / window.innerWidth) * 2 - 1;
                        this.mouseNDC.y = -(e.clientY / window.innerHeight) * 2 + 1;

                    }
                    // 🔹 NEW – TOUCH
                    onTouchStart(e) {
                        this.touch.isDown = true;
                        this.touch.startX = e.touches[0].clientX;
                        this.touch.startY = e.touches[0].clientY;
                        this.touch.lastX = this.touch.startX;
                        this.touch.lastY = this.touch.startY;
                    }

                    onTouchMove(e) {
                        if (!this.touch.isDown) return;

                        const x = e.touches[0].clientX;
                        const y = e.touches[0].clientY;

                        const deltaX = this.touch.lastX - x;
                        const deltaY = this.touch.lastY - y;

                        // yatay + dikey swipe → tek davranış
                        const delta = Math.abs(deltaX) > Math.abs(deltaY) ? deltaX : deltaY;

                        this.scroll.target += delta * 1.5;

                        this.scroll.target = clamp(
                            this.scroll.target,
                            this.scroll.min,
                            this.scroll.max
                        );

                        this.touch.lastX = x;
                        this.touch.lastY = y;
                    }

                    onTouchEnd() {
                        this.touch.isDown = false;
                    }

                    /* -------------------- RESIZE -------------------- */

                    // 🔹 NEW
                    onResize() {
                        const width = window.innerWidth;
                        const height = window.innerHeight;

                        this.renderer.setSize(width, height);
                        this.renderer.setPixelRatio(Math.min(1.5, window.devicePixelRatio));

                        this.camera.aspect = width / height;
                        this.camera.updateProjectionMatrix();

                        this.camera.position.z =
                            height / (2 * Math.tan((this.camera.fov * Math.PI) / 360));

                        var style = getComputedStyle(scope),
                            planesWidth = parseInt(style.getPropertyValue('--imagesWidth').trim()),
                            planesHeight = parseInt(style.getPropertyValue('--imagesHeight').trim()),
                            planesGap = parseInt(style.getPropertyValue('--carouselGap').trim());

                        if (!planesWidth) planesWidth = 350;
                        if (!planesHeight) planesHeight = 467;
                        if (!planesGap) planesGap = 75;
                    }
                    setUpPlanes() {
                        this.DOMElements.forEach((project) => {
                            this.scene.add(this.generatePlane(project));
                        });
                    }

                    generatePlane(project) {
                        let texture;

                        const img = project.querySelector('img');
                        const video = project.querySelector('video');

                        if (video) {
                            // 🔹 VIDEO TEXTURE
                            video.crossOrigin = 'anonymous'; // ŞART
                            video.muted = true;
                            video.loop = true;
                            video.playsInline = true;
                            video.autoplay = true;
                            video.src = video.querySelector('source').src;
                            video.play();

                            texture = new THREE.VideoTexture(video);
                            texture.colorSpace = THREE.SRGBColorSpace;
                            texture.minFilter = THREE.LinearFilter;
                            texture.magFilter = THREE.LinearFilter;
                            texture.generateMipmaps = false;

                        } else if (img) {
                            // 🔹 IMAGE TEXTURE
                            texture = new THREE.TextureLoader().load(img.src);
                            texture.colorSpace = THREE.SRGBColorSpace;

                            texture.minFilter = THREE.LinearFilter;
                            texture.magFilter = THREE.LinearFilter;
                            texture.generateMipmaps = false;
                        }

                        const material = new THREE.ShaderMaterial({
                            vertexShader: `
                            varying vec2 vUv;
                            uniform float uHover;
                            uniform vec2 uMouse;
                            uniform float uTime;
                            uniform float uScrollVelocity;
                            uniform float uClick;
                            
                            #define PI 3.141592653
            
                            void main() {
                                vUv = uv;
            
                                vec3 pos = position;
            
                                vec3 worldPos = (modelMatrix * vec4(position, 1.0)).xyz;
                                float d = distance(worldPos.xy, vec2(0.0));
                                pos.z += sin(d * 0.005 + 1.0) * uScrollVelocity;
                                
                                float d1 = distance(vUv, vec2(0.5));
                                
                                float influence = smoothstep(1.0, 0.0, d1);
                                pos.z += influence * (uClick * 200.0);

                                gl_Position = projectionMatrix * modelViewMatrix * vec4(pos, 1.0);
                            }
                          `,
                            fragmentShader: `
                            uniform sampler2D uTexture;
                            varying vec2 vUv;
                            uniform float uHover;
                            uniform float uClick;
                            uniform float uOpacity;
                            uniform float uGrayscaleProgress;
                            uniform vec2 uMouse;
                            
                            vec3 toGrayscale(vec3 color) {
                              float gray = dot(color, vec3(0.200, 0.450, 0.100));
                              return vec3(gray);
                            }
                            
                            void main() {
                              vec3 originalColor = texture2D(uTexture, vUv).rgb;
                              vec3 grayscaleColor = toGrayscale(originalColor);
                              
                              float dist = distance(vUv, uMouse);
              
                              // 2. Create a circular mask.
                              float mask = smoothstep(0.0, (uHover * 1.0 + (uGrayscaleProgress * 6.0)), dist);
                            
                              // 3. Mix the colors based on the mask's value for each pixel.
                              vec3 finalColor = mix(originalColor, grayscaleColor, mask);
                              
                              gl_FragColor = vec4(finalColor, uOpacity);
                            }
                          `,
                            transparent: true,
                            uniforms: {
                                uGrayscaleProgress: { value: 0.0 },
                                uClick: { value: 0.0 },
                                uHover: { value: 0.0 },
                                uTime: { value: 0.0 },
                                uOpacity: { value: 1.0 },
                                uTexture: { value: texture },
                                uScrollVelocity: { value: 0 },
                                uMouse: { value: new THREE.Vector2(0, 0) },
                            },
                            transparent: true
                        });

                        const plane = new THREE.Mesh(
                            new THREE.PlaneGeometry(planesWidth, planesHeight, 50, 50),
                            material
                        );

                        plane.userData.mouse = {
                            current: new THREE.Vector2(0.0, 0.0),
                            target: new THREE.Vector2(0.0, 0.0),
                            ease: 0.15
                        };

                        plane.userData.hover = 0;
                        plane.userData.link = project.dataset.url;
                        plane.userData.id = project.dataset.id;
                        plane.userData.scene = this.scene;
                        plane.userData.camera = this.camera;

                        return plane;
                    }
                    onClick(e) {
                        if (!this.hoveredPlane) return;
                        if (this.clicked) return;

                        this.clicked = true;

                        const plane = this.hoveredPlane;
                        const link = this.hoveredPlane.userData.link;

                        if (link && !document.body.classList.contains('e-preview--show-hidden-elements')) {
                            if (window.barba) {
                                barba.go(link, plane);
                            } else {
                                window.location.href = link;
                            }
                        }

                    }
                    updateHover() {
                        this.raycaster.setFromCamera(this.mouseNDC, this.camera);
                        const intersects = this.raycaster.intersectObjects(this.scene.children);

                        if (intersects.length) {
                            const plane = intersects[0].object;

                            if (this.hoveredPlane !== plane) {
                                if (this.hoveredPlane) {
                                    this.hoveredPlane.userData.hover = 0;
                                }
                                this.hoveredPlane = plane;
                                plane.userData.hover = 1;

                            }

                            // Plane local UV
                            const uv = intersects[0].uv;
                            plane.userData.mouse.target.copy(uv);

                        } else {
                            if (this.hoveredPlane) {
                                this.hoveredPlane.userData.hover = 0;
                                this.hoveredPlane = null;
                            }
                        }

                        // Smooth update
                        this.scene.children.forEach((plane) => {

                            plane.userData.mouse.current.lerp(
                                plane.userData.mouse.target,
                                plane.userData.mouse.ease
                            );

                            plane.material.uniforms.uMouse.value.copy(
                                plane.userData.mouse.current
                            );

                            plane.material.uniforms.uHover.value = lerp(
                                plane.material.uniforms.uHover.value,
                                plane.userData.hover,
                                0.1
                            );

                            gsap.set(scope.querySelector('.webgl--project--' + plane.userData.id), {
                                opacity: plane.material.uniforms.uHover.value
                            })



                        });
                    }
                    updateScroll() {
                        if (this.clicked) return;

                        this.scroll.current +=
                            (this.scroll.target - this.scroll.current) * this.scroll.ease;

                        this.scroll.current = clamp(
                            this.scroll.current,
                            this.scroll.min,
                            this.scroll.max
                        );

                        if (scope.querySelector('.pwc--progress')) {
                            gsap.set(scope.querySelector('.pwc--progress span'), {
                                width: this.scroll.last / this.scroll.max * 100 + '%'
                            })
                        }

                        if (scope.querySelector('.pwc--current--wrapper')) {
                            gsap.set(scope.querySelector('.pwc--current--wrapper'), {
                                yPercent: -(this.scroll.last / this.scroll.max * 90)
                            })
                        }

                        this.scroll.velocity =
                            this.scroll.current - this.scroll.last;

                        this.scroll.last = this.scroll.current;

                        this.scene.children.forEach((plane) => {
                            plane.position.x =
                                plane.userData.baseX - this.scroll.current;

                            plane.material.uniforms.uScrollVelocity.value =
                                this.scroll.velocity * 7.5;
                        });
                    }
                    render() {

                        this.updateScroll();
                        this.updateHover(); // 👈 yeni
                        this.scene.children.forEach((plane) => {
                            plane.material.uniforms.uTime.value += 0.01;
                        });
                        this.renderer.render(this.scene, this.camera);
                        requestAnimationFrame(this.render.bind(this));
                    }
                }

                new webglCarousel(carousel);
            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/pecomparetable.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i];


                let container = scope.querySelector('.pe--compare--container'),
                    side = scope.querySelector('.pe--compare--container--side'),
                    main = scope.querySelector('.pe--compare--container--main'),
                    mainItemsWrap = main.querySelector('.pe--compare--items--wrap');


                function compareDraggable() {

                    if ((side.getBoundingClientRect().width + main.getBoundingClientRect().width) < container.getBoundingClientRect().width) {
                        return false;
                    }

                    let drag = Draggable.create(mainItemsWrap, {
                        type: 'x',
                        dragResistance: 0.35,
                        inertia: true,
                        bounds: main,
                        allowContextMenu: true
                    })

                };

                compareDraggable();

                document.addEventListener("compareUpdated", function () {
                    compareDraggable();
                })


            };

        });


        elementorFrontend.hooks.addAction('frontend/element_ready/pehotspotimage.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i];
                if (!scope.classList.contains('hotspot--initalized')) {
                    scope.classList.add('hotspot--initalized')
                    var wrapper = scope.querySelector('.pe--hotspot--image'),
                        markers = scope.querySelectorAll('.hotspot--marker'),
                        image = scope.querySelector('.main--hotspot--image');

                    function calcContentPos(marker, content, index) {
                        let markerTop = marker.getBoundingClientRect().top - image.getBoundingClientRect().top,
                            markerBottom = image.getBoundingClientRect().bottom - marker.getBoundingClientRect().bottom,
                            markerLeft = marker.getBoundingClientRect().left - wrapper.getBoundingClientRect().left,
                            markerRight = wrapper.getBoundingClientRect().right - marker.getBoundingClientRect().right,
                            vOrientation = marker.dataset.verticalOrientation,
                            hOrientation = marker.dataset.horizontalOrientation;

                        if (vOrientation === 'top') {
                            content.style.top = markerTop - 15 + 'px';
                        } else {
                            content.style.bottom = markerBottom - 15 + 'px';
                        }

                        if (hOrientation === 'left') {

                            content.style.left = markerLeft - 15 + 'px';
                        } else {
                            content.style.right = markerRight - 15 + 'px';
                        }

                        content.style.display = 'block';
                    }

                    if (scope.classList.contains('open-on-scroll')) {

                        let pinTarget = wrapper.dataset.pinTarget,
                            duration = 500 * markers.length;

                        gsap.getById(scope.dataset.id) ? gsap.getById(scope.dataset.id).scrollTrigger.kill(true) : '';

                        var hotspotTl = gsap.timeline({
                            scrollTrigger: {
                                trigger: scope.classList.contains('pin-on-scroll') ? pinTarget : image,
                                pin: scope.classList.contains('pin-on-scroll') ? pinTarget : false,
                                id: scope.dataset.id,
                                scrub: true,
                                start: scope.classList.contains('pin-on-scroll') ? 'top top' : 'top center',
                                markers: false,
                                end: scope.classList.contains('pin-on-scroll') ? 'bottom+=' + duration + ' top' : 'top top'
                            }
                        })

                        matchMedia.add({
                            isPhone: "(max-width: 550px)"
                        }, (context) => {

                            hotspotTl.scrollTrigger.kill(true);

                        });

                    }

                    if (scope.querySelector('.hotspots--line--scene')) {

                        let scene = scope.querySelector('.hotspots--line--scene'),
                            lines = scene.querySelectorAll('polyline');

                        const width = wrapper.getBoundingClientRect().width;
                        const height = wrapper.getBoundingClientRect().height;

                        scene.setAttribute("viewBox", `0 0 ${width} ${height}`);

                        lines.forEach(line => {

                            let index = line.dataset.index,
                                content = scope.querySelector('.hp__content__' + index);

                            let p1x = width * (parseInt(getComputedStyle(line).getPropertyValue('--p1x')) * 0.01),
                                p1y = height * (parseInt(getComputedStyle(line).getPropertyValue('--p1y')) * 0.01),
                                p2x = width * (parseInt(getComputedStyle(line).getPropertyValue('--p2x')) * 0.01),
                                p2y = height * (parseInt(getComputedStyle(line).getPropertyValue('--p2y')) * 0.01),
                                p3x = width * (parseInt(getComputedStyle(line).getPropertyValue('--p3x')) * 0.01),
                                p3y = height * (parseInt(getComputedStyle(line).getPropertyValue('--p3y')) * 0.01);

                            line.setAttribute("points", `${p1x} ${p1y} ${p2x} ${p2y} ${p3x} ${p3y}`);


                            gsap.set(content, {
                                left: p3x,
                                right: 'unset',
                                top: p3y,
                                xPercent: p3x < (width / 2) ? -100 : 0,
                            })

                            if (scope.classList.contains('click-to-open') || scope.classList.contains('hover-to-open')) {
                                gsap.set(line, {
                                    drawSVG: 0,
                                })
                            }

                        })

                        let dots = scope.querySelectorAll('.hotspot--line--dot');


                        dots.forEach((dot, i) => {

                            let line = scope.querySelector('.hp__marker__' + dot.dataset.index),
                                content = scope.querySelector('.hp__content__' + dot.dataset.index);

                            function contentToggle() {

                                gsap.fromTo(line, {
                                    drawSVG: !dot.classList.contains('active') ? '0%' : '100%',
                                }, {
                                    drawSVG: !dot.classList.contains('active') ? '100%' : '0%',
                                    duration: 1.5,
                                    ease: 'expo.inOut',
                                    overwrite: true,
                                })

                                gsap.to(content, {
                                    opacity: !dot.classList.contains('active') ? 1 : 0,
                                    delay: !dot.classList.contains('active') ? 1 : 0,
                                    overwrite: true,
                                })
                                !dot.classList.contains('active') ? dot.classList.add('active') : dot.classList.remove('active');
                            }

                            if (scope.classList.contains('click-to-open')) {
                                dot.addEventListener("click", (event) => contentToggle());

                            } else if (scope.classList.contains('hover-to-open')) {
                                dot.addEventListener("mouseenter", (event) => contentToggle());
                                dot.addEventListener("mouseleave", (event) => contentToggle());
                            } else if (scope.classList.contains('open-on-scroll')) {

                                hotspotTl.fromTo(line, {
                                    drawSVG: '0%',
                                }, {
                                    drawSVG: '100%',
                                    ease: 'none',
                                }, 'label_' + i)

                                hotspotTl.fromTo(content, {
                                    opacity: 0,
                                }, {
                                    opacity: 1,
                                    ease: 'none',
                                    delay: .75,
                                }, 'label_' + i)

                                matchMedia.add({
                                    isPhone: "(max-width: 550px)"
                                }, (context) => {

                                    clearProps(content);
                                    dot.addEventListener("click", (event) => contentToggle());

                                    let closeButton = content.querySelector('.hotspot--close');

                                    closeButton.addEventListener('click', () => {
                                        dot.click();
                                    })

                                });

                            }



                        })

                    } else {

                        if (markers) {

                            markers.forEach(marker => {

                                let index = marker.dataset.index,
                                    content = scope.querySelector('.hp__content__' + index);

                                calcContentPos(marker, content, index);

                                window.addEventListener('resize', function () {
                                    calcContentPos(marker, content, index);
                                });

                                if (scope.classList.contains('click-to-open')) {
                                    marker.addEventListener('click', () => {
                                        content.classList.toggle('active')
                                    })

                                } else if (scope.classList.contains('hover-to-open')) {
                                    marker.addEventListener('mouseenter', () => {
                                        content.classList.add('active')
                                    })
                                    marker.addEventListener('mouseleave', () => {
                                        content.classList.remove('active')

                                    })

                                } else if (scope.classList.contains('open-on-scroll')) {

                                    if (!scope.classList.contains('pin-on-scroll')) {
                                        ScrollTrigger.create({
                                            trigger: content,
                                            start: 'center center',
                                            end: 'center top',
                                            onEnter: () => {
                                                content.classList.add('active')
                                            },
                                            onLeave: () => {
                                                content.classList.remove('active')
                                            },
                                            onEnterBack: () => {
                                                content.classList.add('active')
                                            },
                                            onLeaveBack: () => {
                                                content.classList.remove('active')
                                            },
                                        })
                                    } else {
                                        tl.to(content, {
                                            clipPath: 'inset(0% 0% 0% 0% round 5px)',

                                        })
                                    }

                                }


                            })
                        }
                    }
                }
            }

        });


        elementorFrontend.hooks.addAction('frontend/element_ready/pecalltoaction.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    box = scope.querySelector('.pe--call--to--action'),
                    wrapper = scope.querySelector('.pe--cta--wrapper'),
                    items = wrapper.querySelectorAll('.cta--element');

                if (wrapper.querySelector('.show-on-hover') || wrapper.querySelector('.hide-on-hover')) {

                    function elementsHover(enter) {

                        var state = Flip.getState(items, {
                            props: ['display']
                        });

                        var state2 = Flip.getState(wrapper);

                        enter ? box.classList.add('hover') : box.classList.remove('hover');

                        var wrapperFlip = Flip.from(state2, {
                            duration: .75,
                            ease: 'power3.inOut',
                        })

                        var flip = Flip.from(state, {
                            duration: .75,
                            ease: 'power3.inOut',
                            absolute: true,
                            absoluteOnLeave: true,
                            onEnter: elements => gsap.fromTo(elements, {
                                opacity: 0,
                                // y: 100
                            }, {
                                opacity: 1,
                                // y: 0,
                                duration: .75,
                                ease: 'power3.inOut',
                                // stagger: 0.1
                            }),
                            onLeave: elements => gsap.fromTo(elements, {
                                opacity: 1,
                                // y: 0
                            }, {
                                opacity: 0,
                                // y: -100,
                                duration: .75,
                                ease: 'power3.inOut',
                                // stagger: 0.1
                            }),
                        })


                    }

                    box.addEventListener('mouseenter', () => {
                        elementsHover(true)
                    });

                    box.addEventListener('mouseleave', () => {
                        elementsHover(false)
                    });

                    // box.addEventListener('mouseleave', () => {

                    //     box.classList.remove('hover');

                    //     Flip.to(state, {
                    //         duration: 1,
                    //         ease: 'power3.inOut',
                    //         absolute: true,
                    //         absoluteOnLeave: true
                    //     })

                    // });




                }




                if (scope.querySelector('.pe--styled--popup')) {

                    pePopup(scope, scope);
                }

            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/peicon.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    animated = scope.querySelectorAll('.icon--motion');

                animated.forEach((element) => {

                    let classes = element.classList,
                        hasMotion = Array.from(classes).some(className => className.startsWith('me--'));

                    // Motion Effects
                    if (hasMotion) {

                        let motion = hasMotion ? Array.from(classes).find(className => className.startsWith('me--')) : null,
                            duration = element.dataset.duration,
                            delay = element.dataset.delay,
                            fade = element.dataset.fade,
                            ease = motion === 'me--flip-x' ? 'none' : motion === 'me--flip-y' ? 'none' : motion === 'me--hearthbeat-x' ? 'power4.inOut' : motion === 'me--slide-left' ? 'power3.in' : motion === 'me--slide-right' ? 'power3.in' : motion === 'me--rotate' ? 'none' : motion === 'me--slide-up' ? 'power3.inOut' : motion === 'me--slide-down' ? 'power3.inOut' : 'expo.out',
                            tl = gsap.timeline({
                                repeat: -1,
                                repeatDelay: parseInt(delay, 10)
                            }),
                            target = element.querySelector('i, svg');


                        if (motion === 'me--slide-left' || motion === 'me--slide-right') {

                            target = element.firstElementChild;
                        }

                        var rotate = '';

                        if (motion === 'me--rotate') {
                            rotate = scope.classList.contains('rotate--dir--clockwise') ? 360 : -360
                        } else {
                            rotate = 0
                        }


                        tl.fromTo(target, {
                            xPercent: 0,
                            yPercent: 0,
                            opacity: fade && 1,
                            scale: motion === 'me--hearth-beat' ? 0.6 : 1
                        }, {

                            scale: 1,
                            rotate: rotate,
                            rotateX: motion === 'me--flip-x' ? -360 : 0,
                            rotateY: motion === 'me--flip-y' ? -360 : 0,
                            xPercent: motion === 'me--slide-left' ? -300 : motion === 'me--slide-right' ? 300 : 0,
                            yPercent: motion === 'me--slide-down' ? 100 : motion === 'me--slide-up' ? -100 : 0,
                            duration: duration,
                            ease: ease,
                            opacity: fade && 0,
                        })

                        if (motion === 'me--slide-left' || motion === 'me--slide-right') {

                            tl.fromTo(target, {
                                xPercent: motion === 'me--slide-left' ? 300 : motion === 'me--slide-right' ? -300 : 0,
                            }, {
                                xPercent: 0,
                                duration: duration,
                                ease: 'power3.out',
                            })
                        }

                        if (motion === 'me--slide-down' || motion === 'me--slide-up') {

                            tl.fromTo(target, {
                                yPercent: motion === 'me--slide-down' ? -100 : motion === 'me--slide-up' ? 100 : 0,
                                opacity: fade && 0,
                            }, {
                                yPercent: 0,
                                opacity: fade && 1,
                                duration: duration,
                                ease: ease,
                            })
                        }

                        if (motion === 'me--hearth-beat') {

                            tl.to(target, {
                                scale: 0.6,
                                duration: duration
                            })
                        }

                    }

                })


                if (scope.classList.contains('outside--curved')) {

                    const color = getComputedStyle(scope.querySelector('.elementor-widget-container')).getPropertyValue('background-color').trim();
                    const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 300"><path fill="${color}" d="M300,0C134.3,0,0,134.3,0,300V0H300z"/></svg>`;
                    const encodedSvg = encodeURIComponent(svg);

                    const el = scope;
                    el.style.setProperty('--pseudo-bg', `url("data:image/svg+xml,${encodedSvg}")`);

                }

                let svg = scope.querySelectorAll('line , polyline , rect , g , path');

                // gsap.from(svg, {
                //     drawSVG: 0,
                //     delay: 1,
                //     ease: "none",
                //     duration: 20,
                //     stagger: 0,
                //     repeat: -1
                // })
            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/peinfosequence.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    sequence = scope.querySelector('.pe--infosequence'),
                    wrapper = sequence.querySelector('.infos--wrapper'),
                    infos = wrapper.querySelectorAll('.seq--info'),
                    type = sequence.dataset.type,
                    behavior = sequence.dataset.behavior,
                    circle;

                gsap.getById(scope.dataset.id) ? gsap.getById(scope.dataset.id).scrollTrigger.kill(true) : '';

                if (type === 'circle' && (behavior === 'draw' || behavior === 'rotate')) {

                    circle = sequence.querySelector('#seq-circle');

                    let scrub = sequence.dataset.scrub,
                        pin = sequence.dataset.pin,
                        pinTarget = sequence.dataset.pinTarget,
                        start = sequence.dataset.start,
                        end = sequence.dataset.end,
                        trigger = sequence;

                    if (pin && pinTarget) {
                        trigger = pinTarget
                    }

                    let snap = {
                        snapTo: 'labels',
                        duration: { min: 0.2, max: 0.3 },
                        delay: 0,
                        ease: 'power1.out'
                    };

                    var tl = gsap.timeline({
                        id: scope.dataset.id,
                        scrollTrigger: {
                            trigger: trigger,
                            start: start,
                            end: end,
                            scrub: scrub ? true : false,
                            pin: pin ? trigger : false,
                            snap: snap
                        },
                    });

                    if (behavior === 'draw') {
                        gsap.set(circle, {
                            drawSVG: "75% 75%",
                        })
                    }

                }

                infos.forEach((info, i) => {
                    i++;

                    let main = info.querySelector('.seq--info--main'),
                        content = info.querySelector('.seq--info--content');

                    if (behavior === 'click') {

                        main.addEventListener('click', () => {
                            if (!scope.classList.contains('open--multiple')) {
                                wrapper.querySelector('.active').classList.remove('active');
                            }
                            info.classList.toggle('active');
                        })

                    }

                    if (behavior === 'draw') {

                        let perc = ((100 / infos.length) * i) + 75;

                        tl.to(circle, {
                            drawSVG: "75% " + perc + "%",
                            ease: "none",
                        }, 'label_' + i)

                        tl.to(content, {
                            opacity: 1,
                            ease: "none",
                            onUpdate: () => {

                                let label = tl.currentLabel().match(/\d+$/)[0];

                                if (parseInt(label) >= i) {
                                    info.classList.add('active');
                                } else {
                                    info.classList.remove('active');
                                }
                            }
                        }, 'label_' + i)


                    }

                    if (behavior === 'rotate') {

                        let perc = ((360 / infos.length) * i);

                        tl.to(wrapper, {
                            rotate: -perc,
                            ease: "none",
                            onUpdate: () => {
                                let label = tl.currentLabel().match(/\d+$/)[0];

                                if (parseInt(label) == i) {
                                    info.classList.add('active');
                                    sequence.querySelector('.info--content-' + (i - 1)).classList.add('active');
                                } else {
                                    info.classList.remove('active');
                                    sequence.querySelector('.seq--info--content.active').classList.remove('active');
                                }
                            }
                        }, 'label_' + i)


                    }

                })

            }

        });


        elementorFrontend.hooks.addAction('frontend/element_ready/pedrawsvg.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    wrapper = scope.querySelector('.pe--draw--svg'),
                    svg = wrapper.querySelector('svg'),
                    elements = svg.querySelectorAll('line , ellipse , polygon , rect , g , path , circle'),
                    duration = parseInt(wrapper.dataset.duration),
                    delay = parseInt(wrapper.dataset.delay),
                    stagger = parseInt(wrapper.dataset.stagger),
                    start = wrapper.dataset.start,
                    end = wrapper.dataset.end,
                    scroll = {
                        trigger: wrapper,
                        start: start,
                        end: end,
                        scrub: scope.classList.contains('draw--scroll') ? true : false,
                    };

                var tl = gsap.timeline({
                    repeat: scope.classList.contains('drawing--loop') ? -1 : 0,
                    scrollTrigger: scope.classList.contains('draw--hover') ? false : scroll,
                    paused: scope.classList.contains('draw--hover') ? true : false,
                    yoyo: scope.classList.contains('drawing--loop--reverse') ? true : false,
                    onStart: () => {
                        wrapper.classList.add('draw--start');
                    },
                    onComplete: () => {
                        clearProps(elements)
                    }
                });

                if (scope.classList.contains('draw--hover')) {
                    svg.addEventListener('mouseenter', () => {
                        tl.play();
                    })

                    svg.addEventListener('mouseleave', () => {
                        tl.pause();
                    })
                }

                tl.from(elements, {
                    drawSVG: 0,
                    delay: delay,
                    ease: scope.classList.contains('draw--scroll') ? 'none' : "power2.inOut",
                    duration: duration,
                    stagger: stagger,
                })

                // tl.from(elements, {
                //     fill: 'transparent',
                //     ease: "none",
                // } , 0)




                elements.forEach(element => {
                    let fill = window.getComputedStyle(element).fill;

                    if (fill !== 'none' && fill !== 'rgba(0, 0, 0, 0)') {
                        element.classList.add('has--theme--stroke');
                    }
                });

            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/pelayoutswitcher.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    switcher = scope.querySelector('.pe-layout-switcher');


                let mainColors = [
                    getComputedStyle(document.documentElement).getPropertyValue('--mainColor'),
                    getComputedStyle(document.documentElement).getPropertyValue('--mainBackground'),
                    getComputedStyle(document.documentElement).getPropertyValue('--secondaryColor'),
                    getComputedStyle(document.documentElement).getPropertyValue('--secondaryBackground'),
                    getComputedStyle(document.documentElement).getPropertyValue('--linesColor'),
                ]

                let switchedColors = [
                    getComputedStyle(document.querySelector('.layout--colors')).getPropertyValue('--mainColor'),
                    getComputedStyle(document.querySelector('.layout--colors')).getPropertyValue('--mainBackground'),
                    getComputedStyle(document.querySelector('.layout--colors')).getPropertyValue('--secondaryColor'),
                    getComputedStyle(document.querySelector('.layout--colors')).getPropertyValue('--secondaryBackground'),
                    getComputedStyle(document.querySelector('.layout--colors')).getPropertyValue('--linesColor'),
                ]

                function followerFollow(item, follower, wrapper) {

                    gsap.to(follower, {
                        duration: .4,
                        ease: 'power2.out',
                        left: item.getBoundingClientRect().left - wrapper.getBoundingClientRect().left,
                        // top: activeItem.getBoundingClientRect().top - wrapper.getBoundingClientRect().top - 1,
                        width: item.getBoundingClientRect().width,
                        // height: activeItem.getBoundingClientRect().height
                    })
                }

                if (scope.querySelector('.pl--switch')) {

                    let items = scope.querySelectorAll('.pl--switch--button'),
                        follower = scope.querySelector('.pl--follower'),
                        parent = switcher;

                    items.forEach(item => {

                        let width = item.getBoundingClientRect().width,
                            left = item.getBoundingClientRect().left - parent.getBoundingClientRect().left,
                            wrapper = switcher,
                            activeItem = wrapper.querySelector('.pl--switch--button.active');

                        if (item.classList.contains('active')) {
                            followerFollow(item, follower, wrapper);

                        }
                        item.addEventListener('click', () => {
                            items.forEach(label => {
                                label.classList.remove('active');
                            })
                            item.classList.add('active');

                            followerFollow(item, follower, wrapper);
                        })

                        item.addEventListener('mouseenter', () => {
                            followerFollow(item, follower, wrapper);
                        })

                        item.addEventListener('mouseleave', () => {
                            followerFollow(wrapper.querySelector('.pl--switch--button.active'), follower, wrapper);
                        })

                    })


                    // if (document.body.classList.contains('layout--switched')) {
                    //     scope.querySelector('.pl--switched').click();
                    // }

                }

                switcher.addEventListener('click', (item) => {

                    // if (scope.querySelector('.pl--switch')) {
                    //     setTimeout(() => {
                    //         let follower = scope.querySelector('.pl--follower');
                    //         followerFollow(scope.querySelector('.pl--switch--button:not(.active)'), follower, switcher);
                    //         scope.querySelector('.pl--switch--button.active').classList.remove('active');
                    //         scope.querySelector('.pl--switch--button:not(.active)').classList.add('active');
                    //     }, 10);
                    // }   

                    if (document.body.classList.contains('layout--switched')) {

                        gsap.fromTo(document.body, {
                            '--mainColor': switchedColors[0],
                            '--mainBackground': switchedColors[1],
                            '--secondaryColor': switchedColors[2],
                            '--secondaryBackground': switchedColors[3],
                        }, {
                            '--mainColor': mainColors[0],
                            '--mainBackground': mainColors[1],
                            '--secondaryColor': mainColors[2],
                            '--secondaryBackground': mainColors[3],
                            duration: 1,
                            ease: 'power3.out',
                            onStart: () => {
                                document.body.classList.add('layout--default');
                                document.body.classList.remove('layout--switched');
                                siteLayout = 'default';
                            }
                        })

                    } else {


                        gsap.fromTo(document.body, {
                            '--mainColor': mainColors[0],
                            '--mainBackground': mainColors[1],
                            '--secondaryColor': mainColors[2],
                            '--secondaryBackground': mainColors[3],
                        }, {
                            '--mainColor': switchedColors[0],
                            '--mainBackground': switchedColors[1],
                            '--secondaryColor': switchedColors[2],
                            '--secondaryBackground': switchedColors[3],
                            duration: 1,
                            ease: 'power3.out',
                            onStart: () => {
                                document.body.classList.remove('layout--default');
                                document.body.classList.add('layout--switched');
                                siteLayout = 'switched';
                            }
                        })

                    }

                })

            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/peteammember.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    member = scope.querySelector('.pe--team--member'),
                    memberImage = member.querySelector('.team--member--image'),
                    toggle = scope.querySelector('.team--member--toggle');

                if (scope.querySelector('.pe--styled--popup')) {

                    if (scope.querySelector('.member--toggle')) {
                        pePopup(scope, member);
                    } else {
                        pePopup(scope, member, memberImage);
                    }

                }

                if (scope.classList.contains('member--content--box')) {

                    if (scope.querySelector('.member--toggle')) {
                        scope.querySelector('.member--toggle').addEventListener('click', () => {
                            member.classList.toggle('box--active')
                        })
                    } else {
                        memberImage.addEventListener('click', () => {
                            member.classList.toggle('box--active')
                        })
                    }



                }

            }

        })
        elementorFrontend.hooks.addAction('frontend/element_ready/peclients.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    clients = scope.querySelector('.pe--clients');

                if (clients.classList.contains('pe--clients--carousel')) {

                    let wrapper = clients.querySelector('.pe--clients--wrapper'),
                        wrapperWidth = wrapper.offsetWidth,
                        items = wrapper.querySelectorAll('.pe-client'),
                        speed = clients.dataset.speed,
                        direction = clients.dataset.direction,
                        stopHover = clients.dataset.hover;

                    if (items.length > 0) {

                        var tl = gsap.timeline({
                            repeat: -1,
                        });

                        let itemWidth = items[0].offsetWidth

                        for (let e = 0; e <= window.innerWidth / itemWidth; e++) {
                            items.forEach(item => {

                                let clone = item.cloneNode(true);
                                wrapper.appendChild(clone);

                            });

                        }
                        if (direction !== 'right-to-left') {
                            Array.from(items).reverse().forEach(item => {
                                let clone = item.cloneNode(true);
                                wrapper.prepend(clone);
                            });
                        }


                        wrapper.style.width = wrapperWidth * 2


                        let gap = window.getComputedStyle(wrapper).getPropertyValue('gap');

                        if (direction === 'right-to-left') {

                            tl.to(wrapper, {
                                x: -wrapperWidth - parseFloat(gap),
                                duration: speed,
                                ease: 'none',

                            });


                        } else {

                            gsap.set(wrapper, {
                                x: -wrapperWidth - parseFloat(gap)
                            })

                            tl.fromTo(wrapper, {
                                x: -wrapperWidth - parseFloat(gap)
                            }, {
                                x: 0,
                                duration: speed,
                                ease: 'none',
                            })
                        }

                    }

                    if (stopHover) {
                        wrapper.addEventListener('mouseenter', () => {
                            tl.pause();
                        })

                        wrapper.addEventListener('mouseleave', () => {
                            tl.play();
                        })
                    }


                }

            }


        });

        elementorFrontend.hooks.addAction('frontend/element_ready/peaccordion.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    accordion = scope.querySelector('.pe--accordion'),
                    wrapper = accordion.querySelector('.pe--accordion--wrapper'),
                    items = wrapper.querySelectorAll('.pe-accordion-item');

                items.forEach((item, i) => {
                    i++;

                    let title = item.querySelector('.pe-accordion-item-title'),
                        content = item.querySelector('.pe-accordion-item-content');

                    title.addEventListener('click', () => {

                        if (scope.classList.contains('accordion--images--yes')) {

                            let imagesWrapper = scope.querySelector('.pe--accordion--images--wrapper');

                            imagesWrapper.querySelector('.accordion--image--active').classList.remove('accordion--image--active');
                            imagesWrapper.querySelector('.image__' + i).classList.add('accordion--image--active');
                        }

                        if (item.classList.contains('accordion--active')) {

                            var contentState = Flip.getState(content);
                            item.classList.remove('accordion--active');

                            Flip.from(contentState, {
                                duration: .75,
                                ease: 'expo.inOut',
                                absolute: false,
                                absoluteOnLeave: false,
                            })


                        } else {

                            if (!accordion.classList.contains('open--multiple')) {

                                var currentActive = accordion.querySelector('.accordion--active');

                                if (currentActive) {

                                    let currentContentState = Flip.getState(currentActive.querySelector('.pe-accordion-item-content'));

                                    currentActive.classList.remove('accordion--active');

                                    Flip.from(currentContentState, {
                                        duration: .75,
                                        ease: 'expo.inOut',
                                        absolute: false,
                                        absoluteOnLeave: false,
                                    })

                                }
                            }
                            //Open

                            var contentState = Flip.getState(content);
                            item.classList.add('accordion--active');

                            Flip.from(contentState, {
                                duration: .75,
                                ease: 'expo.inOut',
                                absolute: false,
                                absoluteOnLeave: false,
                            })

                        }


                    })

                })

            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/pesingleimage.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    image = scope.querySelector('.single-image'),
                    img = image.querySelector('img');


                imagesLoaded(image, function (instance) {

                    if (image.classList.contains('zoomed--image')) {

                        var before = image.querySelector('.zoomed--before'),
                            center = image.querySelector('.zoomed--center'),
                            after = image.querySelector('.zoomed--after'),
                            centerWidth = center.getBoundingClientRect().width,
                            centerHeight = center.getBoundingClientRect().height;


                        let hold = document.createElement('div');

                        image.insertBefore(hold, after);

                        center.classList.add('zoomed');

                        gsap.getById(scope.dataset.id) ? gsap.getById(scope.dataset.id).scrollTrigger.kill(true) : '';

                        let tl = gsap.timeline({
                            id: scope.dataset.id,
                            scrollTrigger: {
                                trigger: scope,
                                start: 'top bottom',
                                end: 'center center',
                                scrub: true
                            }
                        });

                        tl.fromTo(image, {
                            xPercent: -100,

                        }, {
                            xPercent: 0,
                            duration: 20,
                            ease: 'none'
                        }, 0)

                        gsap.getById(scope.dataset.id + '_2') ? gsap.getById(scope.dataset.id + '_2').scrollTrigger.kill(true) : '';


                        gsap.to(center, {
                            width: '100%',
                            height: '100%',
                            id: scope.dataset.id + '_2',
                            scrollTrigger: {
                                trigger: scope,
                                scrub: true,
                                pin: scope,
                                start: 'center center',
                                pinSpacing: 'padding'

                            }
                        })

                    }

                    if (image.classList.contains('parallax--image')) {

                        setTimeout(() => {

                            var img = image.querySelectorAll('img'),
                                start = 'top bottom',
                                end = 'bottom top';

                            if (parents(image, '.pinned_true').length) {

                                let pinParent = parents(image, '.pinned_true')[0],
                                    sc = ScrollTrigger.getById(pinParent.dataset.id);

                                start = sc.start;
                                end = sc.end;
                            }

                            for (var i = 0; i < img.length; i++) {
                                gsap.set(img[i], {
                                    scale: 1.2
                                })

                                gsap.fromTo(img[i], {
                                    yPercent: isHorizontal ? 0 : -10,
                                    xPercent: isHorizontal ? 10 : 0
                                }, {
                                    yPercent: isHorizontal ? 0 : 10,
                                    xPercent: isHorizontal ? -10 : 0,
                                    ease: 'none',
                                    scrollTrigger: {
                                        trigger: image,
                                        scrub: true,
                                        start: start,
                                        end: end
                                    }
                                })
                            }

                        }, 100);


                    }

                    // ScrollTrigger.update();

                });

            }


        });

        elementorFrontend.hooks.addAction('frontend/element_ready/pemarquee.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    marqueeElement = scope.querySelector('.pe-marquee');

                if (!marqueeElement.classList.contains('initialized')) {
                    marqueeElement.classList.add('initialized');
                } else {
                    return;
                }

                var text = marqueeElement.children,
                    dataDuration = marqueeElement.getAttribute('data-duration'),
                    separator = marqueeElement.getAttribute('data-seperator');
                var wrapperElement = document.createElement("div");
                wrapperElement.className = "marquee-wrap";

                while (marqueeElement.firstChild) {
                    wrapperElement.appendChild(marqueeElement.firstChild);
                }
                marqueeElement.appendChild(wrapperElement);

                var infItem = marqueeElement.querySelector('.marquee-wrap'),
                    infWidth = infItem.offsetWidth;

                if (infWidth == 0) {
                    infWidth = document.body.clientWidth;
                }

                if (infWidth > 0) {

                    var infLength = window.innerWidth / infWidth,
                        gap = infItem.getBoundingClientRect().left;
                    if (marqueeElement.classList.contains('icon_font')) {
                        var separators = infItem.querySelectorAll('.seperator');
                        separators.forEach(function (separator) {
                            separator.style.fontSize = window.getComputedStyle(separator.parentNode).getPropertyValue('font-size');
                        });
                    }

                    function infinityOnResize() {
                        for (var i = 2; i < infLength + 2; i++) {
                            var clonedItem = infItem.cloneNode(true);
                            marqueeElement.appendChild(clonedItem);
                        }
                        var infItemLength = marqueeElement.querySelectorAll('.marquee-wrap').length;
                        infWidth = parseInt(infWidth);
                        // infItem.style.width = infWidth + 'px';
                        marqueeElement.style.width = (infItemLength * infItem.offsetWidth) + 'px';
                        marqueeElement.style.display = 'flex';

                        if (scope.classList.contains('marquee--autoplay')) {

                            var tl = gsap.timeline({
                                repeat: -1,
                            });
                            var tl2 = gsap.timeline({
                                repeat: -1
                            });
                        } else {

                            var start = 'top bottom',
                                end = 'bottom top';

                            if (parents(scope, '.pinned_true').length) {
                                start = ScrollTrigger.getById(parents(scope, '.pinned_true')[0].dataset.id).start;
                                end = ScrollTrigger.getById(parents(scope, '.pinned_true')[0].dataset.id).end;

                            }

                            var tl = gsap.timeline({
                                repeat: 0,
                                scrollTrigger: {
                                    trigger: scope,
                                    scrub: true,
                                    start: start,
                                    end: end,
                                }
                            });

                            var tl2 = gsap.timeline({
                                repeat: 0,
                                scrollTrigger: {
                                    trigger: scope,
                                    scrub: true,
                                    start: start,
                                    end: end,
                                }
                            });
                        }



                        if (marqueeElement.classList.contains('left-to-right')) {
                            tl.fromTo(marqueeElement, {
                                x: -1 * (infWidth + gap)
                            }, {
                                x: -1 * gap,
                                ease: 'none',
                                duration: infWidth / 1000 * dataDuration
                            });
                        } else {
                            tl.fromTo(marqueeElement, {
                                x: -1 * gap
                            }, {
                                x: -1 * (infWidth + gap),
                                ease: 'none',
                                duration: infWidth / 1000 * dataDuration
                            });
                        }

                        if (marqueeElement.classList.contains('rotating_seperator')) {
                            var sepDuration = marqueeElement.getAttribute('data-sepduration');
                            var rotateValue = marqueeElement.classList.contains('counter-clockwise') ? -360 : 360;
                            tl2.fromTo(marqueeElement.querySelectorAll('.seperator'), {
                                rotate: 0
                            }, {
                                rotate: rotateValue,
                                duration: sepDuration,
                                ease: 'none'
                            });
                        }
                    }

                    setTimeout(() => {
                        infinityOnResize();
                    }, 250);


                }

            }

        });


        elementorFrontend.hooks.addAction('frontend/element_ready/peblogposts.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    grid = scope.querySelector('.pe--blog--posts'),
                    wrapper = scope.querySelector('.pe--blog--posts--wrapper'),
                    settings = scope.dataset.settings,
                    args = grid.dataset.queryArgs;

                var offset = 0;
                var filterTerms = {};



                function AJAXGetPosts(trigger, request, settings, args) {

                    document.documentElement.classList.add('loading');
                    trigger.classList ? trigger.classList.add('loading') : '';

                    if (request === 'load-more') {
                        offset++;
                    }

                    if (request === 'filter') {
                        wrapper.classList.add('filters--loading');
                        scope.classList.add('posts--filtered');

                        if (trigger) {
                            trigger.classList.toggle('active');

                            let taxonomy = trigger.dataset.taxonomy,
                                termId = trigger.dataset.termId;

                            if (taxonomy && termId) {

                                if (termId === "all") {
                                    delete filterTerms[taxonomy];

                                    scope.querySelectorAll(`[data-taxonomy="${taxonomy}"]`).forEach(el => {
                                        el.classList.remove('active');
                                    });

                                } else {

                                    if (!filterTerms[taxonomy]) {
                                        filterTerms[taxonomy] = [];
                                    }

                                    if (trigger.classList.contains('active')) {

                                        scope.querySelectorAll(`[data-taxonomy="${taxonomy}"][data-term-id="all"]`).forEach(el => {
                                            el.classList.remove('active');
                                        });

                                        if (!filterTerms[taxonomy].includes(termId)) {
                                            filterTerms[taxonomy].push(termId);
                                        }
                                    } else {
                                        filterTerms[taxonomy] = filterTerms[taxonomy].filter(id => id !== termId);

                                        if (filterTerms[taxonomy].length === 0) {
                                            delete filterTerms[taxonomy];

                                            document.querySelectorAll(`[data-taxonomy="${taxonomy}"][data-term-id="all"]`).forEach(el => {
                                                el.classList.add('active');
                                            });
                                        }
                                    }
                                }
                            }
                        }

                    }

                    if (request === 'all') {
                        wrapper.classList.add('filters--loading');
                    }

                    $.ajax({
                        url: pe_get_projects.ajax_url,
                        type: "POST",
                        data: {
                            action: "pe_get_posts",
                            request: request,
                            args: args,
                            settings: settings,
                            offset: offset,
                            filters: filterTerms,
                        },
                        dataType: "json",
                        success: function (response) {

                            if (response.success) {

                                let postsHTML = response.data.posts;

                                if (postsHTML.length > 0) {

                                    let tl = gsap.timeline({
                                        onComplete: () => {
                                            ScrollTrigger.update();
                                            ScrollTrigger.getById('loadInfnite') ? ScrollTrigger.getById('loadInfnite').refresh() : '';
                                        }
                                    });

                                    if (request === 'filter' || request === 'all') {
                                        wrapper.classList.add('hidden');

                                        request === 'all' ? scope.classList.remove('portfolios--filtered') : '';

                                        setTimeout(() => {
                                            wrapper.querySelectorAll('.pe--single--post').forEach(project => project.remove());

                                            postsHTML.forEach((postsHTML, i) => {
                                                let tempDiv = document.createElement("div");
                                                tempDiv.innerHTML = postsHTML;

                                                let projectElement = tempDiv.firstElementChild;
                                                wrapper.appendChild(projectElement);

                                            })

                                        }, 400);

                                        setTimeout(() => {
                                            wrapper.classList.remove('hidden');
                                            wrapper.classList.remove('filters--loading');
                                            document.documentElement.classList.remove('loading');
                                            trigger ? trigger.classList.remove('loading') : '';
                                            throttledWidgetRefresh(100); // OPTIMIZED

                                        }, 600);

                                    } else {

                                        postsHTML.forEach((postsHTML, i) => {
                                            let tempDiv = document.createElement("div");
                                            tempDiv.innerHTML = postsHTML;

                                            let projectElement = tempDiv.firstElementChild;
                                            wrapper.appendChild(projectElement);

                                            tl.fromTo(projectElement, {
                                                opacity: 0,
                                                yPercent: 100
                                            }, {
                                                opacity: 1,
                                                yPercent: 0,
                                                duration: .75,
                                                ease: 'expo.out',
                                                onComplete: () => {
                                                    // clearProps(projectElement);
                                                    document.documentElement.classList.remove('loading');
                                                    trigger ? trigger.classList.remove('loading') : '';

                                                    throttledWidgetRefresh(100); // OPTIMIZED

                                                }
                                            }, i * 0.15);

                                        })

                                    }

                                    if (parseInt(grid.dataset.found) === scope.querySelectorAll('.pe--single--post').length) {

                                        trigger ? trigger.classList.add('hidden') : '';

                                        ScrollTrigger.getById('loadInfnite') ? ScrollTrigger.getById('loadInfnite').kill(true) : '';

                                    }

                                    setTimeout(() => {
                                        if (scope.querySelector('[data-cursor="true"]')) {

                                            var targets = scope.querySelectorAll('[data-cursor="true"]');
                                            targets.forEach(target => {
                                                peCursorInteraction(target);
                                            })

                                        }

                                    }, 500);

                                } else {
                                    wrapper.classList.remove('hidden');
                                    wrapper.classList.remove('filters--loading');
                                    document.documentElement.classList.remove('loading');
                                    trigger ? trigger.classList.remove('loading') : '';

                                    setTimeout(() => {
                                        ScrollTrigger.refresh();
                                    }, 1000);


                                }

                            }


                        },
                        error: function (response) {
                            console.log(response.error);
                        }
                    });

                }


                if (scope.querySelector('.posts--pagination')) {

                    if (scope.querySelector('.posts--load--more')) {
                        scope.querySelector('.posts--load--more .pe--button').addEventListener('click', (e) => {

                            AJAXGetPosts(scope.querySelector('.posts--load--more .pe--button'), 'load-more', settings, args);
                        })
                    }

                    if (scope.querySelector('.ajax-infinite-scroll')) {

                        ScrollTrigger.create({
                            trigger: wrapper,
                            id: 'loadInfnite',
                            start: 'bottom bottom',
                            end: 'bottom top',
                            onEnter: () => {
                                AJAXGetPosts(scope.querySelector('.ajax-infinite-scroll'), 'load-more', settings, args);
                            }
                        })

                    }

                }

                if (scope.querySelector('.pe--posts--filters')) {



                    var filters = scope.querySelector('.pe--posts--filters'),
                        terms = filters.querySelectorAll('.term-item'),
                        filtersArray = {};

                    terms.forEach(term => {
                        term.addEventListener('click', (e) => {
                            let termId = parseInt(term.dataset.termId);

                            AJAXGetPosts(term, 'filter', settings, args);
                        })
                    });

                }


            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/pepostmedia.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    image = scope.querySelector('.single-image');

                if (image.classList.contains('parallax--image')) {

                    let img = image.querySelectorAll('img');

                    for (var i = 0; i < img.length; i++) {

                        gsap.set(img[i], {
                            scale: 1.2
                        })

                        gsap.fromTo(img[i], {
                            yPercent: -10
                        }, {
                            yPercent: 10,
                            ease: 'none',
                            scrollTrigger: {
                                trigger: image,
                                scrub: true,
                                start: 'top bottom',
                                end: 'bottom top'
                            }
                        })
                    }
                }

                ScrollTrigger.update();
            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/projectmedia.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    gallery = scope.querySelector('.project--image--gallery');

                if (gallery) {

                    var wrapper = gallery.querySelector('.project--image--gallery--wrapper'),
                        images = wrapper.querySelectorAll('.project--gallery--image'),
                        gap = parseInt(window.getComputedStyle(wrapper).getPropertyValue('gap')),
                        val = wrapper.getBoundingClientRect().left + (wrapper.offsetWidth - document.body.clientWidth) + gap,
                        id = wrapper.dataset.id ? wrapper.dataset.id : scope.dataset.id,
                        trigger = gallery.dataset.trigger ? gallery.dataset.trigger : scope,
                        speed = wrapper.dataset.speed,
                        integrated = gallery.dataset.integrated,
                        isVertical = false;

                    imagesLoaded(scope, function (instance) {

                        wrapper.classList.add(id);
                        wrapper.setAttribute('data-total', images.length);
                        images.forEach((image, i) => {

                            image.setAttribute('data-cr', i + 1);
                        })


                        if (scope.classList.contains('pr--gallery--vertical') && !mobileQuery.matches) {
                            val = wrapper.getBoundingClientRect().height - window.innerHeight;
                            isVertical = true;
                        }


                        if (scope.classList.contains('cr--scroll')) {

                            wrapper.classList.add('cr--scroll');
                            gsap.getById(id) ? gsap.getById(id).scrollTrigger.kill(true) : '';



                            var crScroll = gsap.to(wrapper, {
                                id: id,
                                x: isVertical ? 0 : -val,
                                y: isVertical ? -val : 0,
                                ease: "sine.inOut",
                                scrollTrigger: {
                                    trigger: trigger,
                                    pin: trigger,
                                    // pinSpacing: 'margin',
                                    scrub: true,
                                    start: 'top top',
                                    end: 'bottom+=3000 top',
                                    onEnter: () => isPinnng(trigger, true),
                                    onEnterBack: () => isPinnng(trigger, true),
                                    onLeave: () => isPinnng(trigger, false),
                                    onLeaveBack: () => isPinnng(trigger, false),
                                    onUpdate: self => {

                                        if (integrated && !mobileQuery.matches) {

                                            gsap.to(integrated, {
                                                opacity: 1 - (self.progress * 5)
                                            })
                                        }
                                    }
                                }
                            })


                            matchMedia.add({
                                isPhone: "(max-width: 550px)"
                            }, (context) => {

                                crScroll.scrollTrigger.kill(true);

                                Draggable.create(wrapper, {
                                    type: isVertical ? 'y' : 'x',
                                    dragResistance: 0.35,
                                    inertia: true,
                                    bounds: gallery,
                                })

                            });

                        }

                        if (scope.classList.contains('cr--drag')) {

                            wrapper.classList.add('cr--drag');

                            let drag = Draggable.create(wrapper, {
                                id: id,
                                type: isVertical ? 'y' : 'x',
                                dragResistance: 0.35,
                                inertia: true,
                                allowContextMenu: true,
                                bounds: gallery,
                                onThrowUpdate: () => {
                                    let prog = drag[0].x / drag[0].minX;

                                    if (integrated) {

                                        gsap.to(integrated, {
                                            opacity: 1 - (prog * 5)
                                        })
                                    }

                                },
                                onMove: () => {

                                    let prog = drag[0].x / drag[0].minX;

                                    if (integrated) {

                                        gsap.to(integrated, {
                                            opacity: 1 - (prog * 5)
                                        })
                                    }

                                },
                                lockAxis: true,
                                dragResistance: 0.5,
                                inertia: true,
                            });

                        }

                    })



                } else {

                    let image = scope.querySelector('.project-featured-image');

                    if (image && image.classList.contains('parallax--image')) {

                        let img = image.querySelectorAll('img');


                        for (var i = 0; i < img.length; i++) {

                            gsap.fromTo(img[i], {
                                yPercent: 0
                            }, {
                                yPercent: 20,
                                ease: 'none',
                                scrollTrigger: {
                                    trigger: image,
                                    scrub: 1.2,
                                    start: scope.getBoundingClientRect().top < window.innerHeight ? 0 : 'top bottom',
                                    end: 'bottom top'
                                }
                            })


                        }

                    }
                }

                if (scope.classList.contains('project--link--scroll')) {

                    setTimeout(() => {


                        let link = scope.querySelector('.barba--trigger'),
                            image = scope.querySelector('.project-featured-image'),
                            wrapper = parents(scope, '.next-project-section')[0],
                            tl = gsap.timeline({
                                invalidateOnRefresh: true,
                                scrollTrigger: {
                                    trigger: wrapper,
                                    start: 'center center',
                                    end: 'bottom+=500 top',
                                    pin: wrapper,
                                    pinSpacing: 'padding',
                                    invalidateOnRefresh: true,
                                    // pinSpacing: "margin",
                                    // markers: true,
                                    scrub: 1,
                                    onEnter: () => isPinnng('.next-project-section', true),
                                    onEnterBack: () => isPinnng('.next-project-section', true),
                                    onLeave: () => isPinnng('.next-project-section', false),
                                    onLeaveBack: () => isPinnng('.next-project-section', false),
                                    onUpdate: (self) => {
                                        let progress = Math.floor(self.progress * 100);
                                        if (progress >= 99) {
                                            disableScroll();

                                            if (window.barba) {
                                                barba.go(link.href, link);
                                            } else {
                                                window.location.href = link;

                                            }

                                        }
                                    }
                                }
                            })

                        if (image.querySelector('img')) {
                            tl.fromTo(image.querySelector('img'), {
                                scale: 1.2
                            }, {
                                scale: 1
                            }, 0)
                        }

                        tl.fromTo(image, {
                            clipPath: 'inset(100% 0% 0% 0%)'
                        }, {
                            clipPath: 'inset(0% 0% 0% 0%)',
                        }, 0)

                    }, 200);




                }

                if (scope.querySelector('.swiper-container')) {
                    peSlider(scope);
                }

            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/pelanguagecurrencyswitcher.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    wrapper = scope.querySelector('.pe--language--currency--switcher'),
                    ul = wrapper.querySelector('.zeyna--wpml--switch'),
                    items = wrapper.querySelectorAll('li'),
                    follower;



                if (scope.classList.contains('ls--switcher--switcher')) {

                    follower = wrapper.querySelector('.lcs--follower');
                    let activeItem = wrapper.querySelector('.zeyna--wpml--lang.active--lang');

                    gsap.set(follower, {
                        duration: .4,
                        ease: 'power2.out',
                        left: activeItem.getBoundingClientRect().left - wrapper.getBoundingClientRect().left,
                        top: activeItem.getBoundingClientRect().top - wrapper.getBoundingClientRect().top - 1,
                        width: activeItem.getBoundingClientRect().width,
                        height: activeItem.getBoundingClientRect().height
                    })


                    items.forEach(item => {

                        item.addEventListener('mouseenter', () => {
                            gsap.to(follower, {
                                duration: .4,
                                ease: 'power2.out',
                                left: item.getBoundingClientRect().left - wrapper.getBoundingClientRect().left,
                                top: item.getBoundingClientRect().top - wrapper.getBoundingClientRect().top - 1,
                                width: item.getBoundingClientRect().width,
                                height: item.getBoundingClientRect().height
                            })
                        })

                        item.addEventListener('mouseleave', () => {
                            gsap.to(follower, {
                                duration: .4,
                                ease: 'power2.out',
                                left: activeItem.getBoundingClientRect().left - wrapper.getBoundingClientRect().left,
                                top: activeItem.getBoundingClientRect().top - wrapper.getBoundingClientRect().top - 1,
                                width: activeItem.getBoundingClientRect().width,
                                height: activeItem.getBoundingClientRect().height
                            })
                        })

                    })
                }

                if (wrapper.classList.contains('lcs--currency')) {

                    let items = scope.querySelectorAll('li');

                    items.forEach((item, i) => {
                        item.style.setProperty('--i', i);
                        setTimeout(() => {
                            item.querySelector('a').classList.add('pe--styled--object');
                        }, 100);


                    });

                }



            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/penumbercounter.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    counter = scope.querySelector('.pe--number--counter'),
                    counterInner = scope.querySelector('.number--count'),
                    start = parseInt(counter.dataset.start),
                    end = counter.dataset.end,
                    duration = parseInt(counter.dataset.duration);

                gsap.getById(scope.dataset.id) ? gsap.getById(scope.dataset.id).scrollTrigger.kill(true) : '';

                var tl = gsap.timeline({
                    id: scope.dataset.id,
                    repeat: scope.classList.contains('counter--repeat') ? -1 : 0,
                    repeatDelay: duration / 2500,
                    // yoyo: true,
                    scrollTrigger: {
                        trigger: counter.dataset.pinTarget ? document.querySelector(counter.dataset.pinTarget) : counter,
                        start: counter.dataset.pin ? 'center center' : 'top bottom',
                        end: counter.dataset.pin ? 'bottom+=1000 center' : counter.dataset.scrub ? 'center center' : '',
                        scrub: counter.dataset.scrub || counter.dataset.pin ? 1 : false,
                        pin: counter.dataset.pin ? true : false,
                        pinSpacing: 'padding'
                    }
                })


                if (scope.classList.contains('counter--basic')) {

                    tl.to(counterInner, {
                        innerText: end,
                        duration: duration / 1000,
                        delay: .2,
                        ease: counter.dataset.scrub || counter.dataset.pin ? 'none' : 'expo.out',
                        snap: {
                            innerText: 1
                        },
                    }, 0);


                } else if (scope.classList.contains('counter--animated')) {

                    const digits = end.toString().split('').map(Number);

                    for (let i = 0; i < digits.length; i++) {

                        if (scope.querySelector('.counter--prefix') && i == 0) {
                            tl.to(scope.querySelector('.counter--prefix'), {
                                y: '0%',
                                duration: duration / 2000,
                                ease: 'power3.out',
                            }, 'label_0');
                        }


                        tl.to(scope.querySelector('.count--' + i), {
                            // yPercent: (digits[i] * -5) - 50,
                            y: (digits[i] * -5) - 50 + '%',
                            duration: duration / 1000,
                            delay: i * 0.15,
                            ease: 'power3.inOut',
                        }, 'label_1');

                        if (scope.querySelector('.counter--suffix') && i == (digits.length - 1)) {
                            tl.to(scope.querySelector('.counter--suffix'), {
                                y: '0%',
                                duration: duration / 2000,
                                ease: 'power3.out',
                            }, 'label_2');
                        }

                    }

                } else if (scope.classList.contains('counter--multi')) {

                    let levels = JSON.parse(counterInner.dataset.numbers);

                    levels.forEach((element, i) => {

                        if (i === 0) {
                            return false;
                        }

                        if (scope.querySelector('.prefix__' + i)) {

                            tl.to(scope.querySelector('.prefix__' + i), {
                                yPercent: 0,
                                y: 0,
                                duration: duration / 1000,
                                delay: duration / 2000,
                                ease: 'power3.inOut',
                            }, 'label_' + i);

                            if (i !== (levels.length - 1)) {

                                tl.to(scope.querySelector('.prefix__' + i), {
                                    yPercent: -100,
                                    y: -100,
                                    duration: duration / 1000,
                                    delay: duration / 2000,
                                    ease: 'power3.inOut',
                                }, 'label_' + (i + 1));
                            }

                        }

                        tl.to(scope.querySelector('.counter--captions--wrap'), {
                            y: parseInt(getComputedStyle(scope.querySelector('p.counter--caption')).fontSize) * -i,
                            duration: duration / 3000,
                            delay: duration / 2000,
                            ease: 'power3.inOut',
                        }, 'label_' + i);



                        tl.to(counterInner.querySelector('.number--hold'), {
                            innerText: element,
                            duration: duration / 1000,
                            delay: duration / 2000,
                            ease: 'power3.inOut',
                            snap: {
                                innerText: 1
                            },
                        }, 'label_' + i);

                        if (scope.querySelector('.suffix__' + i)) {

                            tl.to(scope.querySelector('.suffix__' + i), {
                                yPercent: 0,
                                y: 0,
                                duration: .65,
                                delay: duration / 2000,
                                ease: 'power3.inOut',
                            }, 'label_' + i);

                            if (i !== (levels.length - 1)) {
                                tl.to(scope.querySelector('.suffix__' + i), {
                                    yPercent: -100,
                                    y: -100,
                                    duration: .65,
                                    delay: duration / 2000,
                                    ease: 'power3.inOut',
                                }, 'label_' + (i + 1));

                            }

                        }

                    });


                }

            }

        });


        function initVideos(element) {
            if (element.querySelector('.pe-video')) {
                let videos = element.querySelectorAll('.pe-video');

                for (var i = 0; i < videos.length; i++) {
                    new peVideoPlayer(videos[i]);
                }
            }
        }



        elementorFrontend.hooks.addAction('frontend/element_ready/peportfolio.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    portfolio = scope.querySelector('.pe--portfolio'),
                    wrapper = scope.querySelector('.portfolio--projects--wrapper'),
                    projects = wrapper.querySelectorAll('.zeyna--portfolio--project'),
                    settings = scope.dataset.settings,
                    args = portfolio.dataset.queryArgs;


                if (scope.querySelector('.show--on--hover') || scope.querySelector('.hide--on--hover')) {


                    function metasFlip(enter, project) {


                        let items = project.querySelectorAll('.project--title , .project--cat , .project--details--wrap, .project--taxonomies--wrap');

                        let state = Flip.getState(items, {
                            props: ['display']
                        });

                        enter ? project.classList.add('active') : project.classList.remove('active');

                        Flip.from(state, {
                            duration: .8,
                            ease: 'power3.inOut',
                            absolute: false,
                            absoluteOnLeave: true,
                            onEnter: elements => gsap.fromTo(elements, {
                                opacity: 0,
                                y: -20
                            }, {
                                opacity: 1,
                                y: 0,
                                duration: .8,
                                ease: 'power3.out',
                                // stagger: -0.1
                            }),
                            onLeave: elements => gsap.fromTo(elements, {
                                opacity: 1,
                                y: 0
                            }, {
                                opacity: 0,
                                y: -20,
                                duration: .8,
                                ease: 'power3.in',
                                // stagger: 0.1
                            }),
                        })


                    }

                    projects.forEach(project => {
                        project.addEventListener('mouseenter', () => {
                            metasFlip(true, project);
                        })
                        project.addEventListener('mouseleave', () => {
                            metasFlip(false, project);
                        })
                    })
                }


                if (scope.classList.contains('portfolio--style--grid') && scope.querySelector('.grid--switcher')) {

                    peSwitcher(scope, scope.querySelector('.grid--switcher'), wrapper, projects);

                }

                if (scope.classList.contains('portfolio--style--masonry')) {

                    var elem = scope.querySelector('.portfolio--projects--wrapper');

                    var msnry = new Masonry(elem, {
                        itemSelector: '.zeyna--portfolio--project',
                        columnWidth: '.zeyna--projects--masonry--sizer',
                        gutter: '.zeyna--projects--masonry--gutter',
                        percentPosition: true,
                    });

                    imagesLoaded(elem, function (instance) {
                        msnry.layout();
                    })

                    if (scope.querySelector('.pe-video')) {

                        document.addEventListener("playersReady", function (e) {
                            if (Masonry.data(elem) && !portfolio.classList.contains('style--switched')) {
                                msnry.layout();
                            }
                        });
                    }

                }

                if (scope.classList.contains('portfolio--style--grouped')) {

                    let groups = scope.querySelectorAll('.portfolio--projects--group');

                    groups.forEach(group => {

                        let wrapper = group.querySelector('.projects--group--wrapper');

                        if (wrapper.offsetWidth > group.offsetWidth) {

                            Draggable.create(wrapper, {
                                type: 'x',
                                edgeResistance: 0.75,
                                bounds: group,
                                dragResistance: 0.35,
                                inertia: true,
                                zIndexBoost: true,
                                alllowEventDefault: true,
                            })

                        }


                    })

                }

                function projectListImagesInit(targets) {
                    var heights = [0];
                    var xVal = 0;
                    targets.forEach((project, i) => {
                        i++;

                        let id = project.dataset.id,
                            findImage = scope.querySelector('.portfolio--list--images--wrap').querySelector('.image__' + id);

                        xVal += findImage.getBoundingClientRect().height;
                        heights.push(xVal);

                        project.addEventListener("mouseenter", (e) => {

                            findImage.classList.add('active');

                            if (scope.classList.contains('portfolio--images--hover')) {

                                gsap.to(scope.querySelector('.portfolio--list--images--wrap'), {
                                    height: findImage.getBoundingClientRect().height,
                                    duration: .75,
                                    ease: 'power3.out',

                                })

                                gsap.to(scope.querySelector('.portfolio--list--images--inner--wrap'), {
                                    y: heights[i - 1] * -1,
                                    duration: .75,
                                    ease: 'power3.out',

                                })

                            }

                        });

                        project.addEventListener("mouseleave", (e) => {
                            let id = project.dataset.id,
                                findImage = scope.querySelector('.portfolio--list--images--wrap').querySelector('.image__' + id);
                            findImage.classList.remove('active');
                        });

                    })

                }

                function projectListHoversInit() {

                    var imagesWrapper = scope.querySelector('.portfolio--list--images--wrap'),
                        innerWrapper = imagesWrapper.querySelector('.portfolio--list--images--inner--wrap');

                    wrapper.addEventListener("mouseenter", (e) => {
                        imagesWrapper.classList.add('active');
                    });

                    wrapper.addEventListener("mouseleave", (e) => {
                        imagesWrapper.classList.remove('active');
                    });

                    if (scope.classList.contains('portfolio--images--hover')) {

                        portfolio.addEventListener("mousemove", (e) => {
                            gsap.to(imagesWrapper, {
                                x: e.clientX - ((document.body.offsetWidth - portfolio.offsetWidth) / 2) - (imagesWrapper.offsetWidth / 2),
                                y: e.clientY - (imagesWrapper.offsetHeight / 2)
                            })
                        });
                    }

                }

                function initGalleries() {

                    if (scope.querySelector('.project--gallery')) {

                        let swiperCont = scope.querySelectorAll('.project--gallery');

                        swiperCont.forEach(cont => {

                            let project = parents(cont, '.zeyna--portfolio--project')[0];

                            var productArchiveGallery = new Swiper(cont, {
                                slidesPerView: 1,
                                speed: 750,
                                navigation: {
                                    nextEl: project.querySelector('.pag--next'),
                                    prevEl: project.querySelector('.pag--prev'),
                                },
                            });

                        });

                    }
                }

                initGalleries();

                if (scope.classList.contains('portfolio--style--list')) {

                    if (scope.classList.contains('portfolio--images--hover') || scope.classList.contains('portfolio--images--fullscreen')) {

                        projectListHoversInit();

                        projectListImagesInit(projects);
                        if (scope.querySelector('.pe-video')) {
                            document.addEventListener("playersReady", function (e) {
                                projectListImagesInit(projects);
                            });

                        }
                    }

                }

                function initParallaxImages(targets) {
                    for (var i = 0; i < targets.length; i++) {

                        var triggerImage = parents(targets[i], '.project--image')[0];

                        gsap.set(targets[i], {
                            scale: 1.2
                        })

                        gsap.fromTo(targets[i], {
                            yPercent: -10
                        }, {
                            yPercent: 10,
                            ease: 'none',
                            scrollTrigger: {
                                trigger: triggerImage,
                                scrub: true,
                                start: 'top bottom',
                                end: 'bottom top'
                            }
                        })
                    }

                }

                if (scope.classList.contains('project--images--parallax')) {

                    imagesLoaded(scope, function (instance) {
                        initParallaxImages(scope.querySelectorAll('.zeyna--portfolio--project .project--image img'));
                    })

                }

                var offset = 0;
                var filterTerms = {};
                var currentLayout = portfolio.dataset.style;
                var layout = currentLayout;

                function AJAXGetProjects(trigger, request, settings, args) {

                    document.documentElement.classList.add('loading');
                    trigger.classList ? trigger.classList.add('loading') : '';

                    if (request === 'load-more') {
                        offset++;
                    }

                    if (request === 'filter') {
                        wrapper.classList.add('filters--loading');
                        scope.classList.add('portfolios--filtered');

                        if (trigger) {
                            trigger.classList.toggle('active');

                            let taxonomy = trigger.dataset.taxonomy,
                                termId = trigger.dataset.termId;

                            if (taxonomy && termId) {

                                if (termId === "all") {
                                    delete filterTerms[taxonomy];

                                    scope.querySelectorAll(`[data-taxonomy="${taxonomy}"]`).forEach(el => {
                                        el.classList.remove('active');
                                    });

                                } else {

                                    if (!filterTerms[taxonomy]) {
                                        filterTerms[taxonomy] = [];
                                    }

                                    if (trigger.classList.contains('active')) {

                                        scope.querySelectorAll(`[data-taxonomy="${taxonomy}"][data-term-id="all"]`).forEach(el => {
                                            el.classList.remove('active');
                                        });

                                        if (!filterTerms[taxonomy].includes(termId)) {
                                            filterTerms[taxonomy].push(termId);
                                        }
                                    } else {
                                        filterTerms[taxonomy] = filterTerms[taxonomy].filter(id => id !== termId);

                                        if (filterTerms[taxonomy].length === 0) {
                                            delete filterTerms[taxonomy];

                                            document.querySelectorAll(`[data-taxonomy="${taxonomy}"][data-term-id="all"]`).forEach(el => {
                                                el.classList.add('active');
                                            });
                                        }
                                    }
                                }
                            }
                        }

                    }

                    if (request === 'all') {
                        wrapper.classList.add('filters--loading');
                    }

                    if (request === 'layout--switch') {
                        wrapper.classList.add('hidden');
                        layout = trigger.dataset.style;

                        scope.querySelectorAll('.switch--item').forEach(el => {
                            el.classList.remove('active');
                        });

                        trigger.classList.toggle('active');

                    }


                    $.ajax({
                        url: pe_get_projects.ajax_url,
                        type: "POST",
                        data: {
                            action: "pe_get_projects",
                            request: request,
                            args: args,
                            settings: settings,
                            offset: offset,
                            filters: filterTerms,
                            layout: layout
                        },
                        dataType: "json",
                        success: function (response) {



                            if (response.success) {

                                if (request === 'layout--switch' && layout === 'list' && (scope.classList.contains('portfolio--images--hover') || scope.classList.contains('portfolio--images--fullscreen'))) {

                                    const imagesWrap = document.createElement('div');
                                    imagesWrap.className = 'portfolio--list--images--wrap';
                                    const innerImagesWrap = document.createElement('div');
                                    innerImagesWrap.className = 'portfolio--list--images--inner--wrap';

                                    imagesWrap.appendChild(innerImagesWrap);
                                    wrapper.appendChild(imagesWrap);

                                }

                                if ((layout === 'list') && (scope.classList.contains('portfolio--images--hover') || scope.classList.contains('portfolio--images--fullscreen'))) {

                                    let imagesWrapper = scope.querySelector('.portfolio--list--images--inner--wrap');
                                    let imagesHtml = response.data.project_images;

                                    if (imagesHtml.length > 0) {

                                        imagesHtml.forEach((imagesHtml, i) => {
                                            let tempDiv = document.createElement("div");
                                            tempDiv.innerHTML = imagesHtml;
                                            let imageElement = tempDiv.firstElementChild;
                                            imagesWrapper.appendChild(imageElement);
                                            initVideos(imagesWrapper);
                                        })

                                    }

                                }

                                let projectsHtml = response.data.projects;

                                if (projectsHtml.length > 0) {

                                    let tl = gsap.timeline({
                                        onComplete: () => {
                                            ScrollTrigger.update();
                                            ScrollTrigger.getById('loadInfnite') ? ScrollTrigger.getById('loadInfnite').refresh() : '';
                                        }
                                    });

                                    if (request === 'filter' || request === 'all' || request === 'layout--switch') {
                                        wrapper.classList.add('hidden');

                                        if (request === 'layout--switch') {

                                            layout !== 'list' ? scope.classList.remove('portfolio--style--list') : '';
                                            layout !== 'grid' ? scope.classList.remove('portfolio--style--grid') : '';
                                            layout !== 'masonry' ? scope.classList.remove('portfolio--style--masonry') : '';
                                            scope.classList.add('portfolio--style--' + layout);
                                            wrapper.classList.add('style--switched--' + layout)

                                            if (Masonry.data(wrapper)) {
                                                let masonry = Masonry.data(wrapper);
                                                masonry.destroy();
                                                clearProps(wrapper);

                                            }

                                            if (scope.querySelector('.zeyna--projects--masonry--sizer')) {
                                                scope.querySelector('.zeyna--projects--masonry--sizer').remove();
                                                scope.querySelector('.zeyna--projects--masonry--gutter').remove();
                                            }

                                        }

                                        request === 'all' ? scope.classList.remove('portfolios--filtered') : '';

                                        setTimeout(() => {
                                            wrapper.querySelectorAll('.zeyna--portfolio--project , .portfolio--projects--group').forEach(project => project.remove());

                                            projectsHtml.forEach((projectsHtml, i) => {
                                                let tempDiv = document.createElement("div");
                                                tempDiv.innerHTML = projectsHtml;

                                                let projectElement = tempDiv.firstElementChild;
                                                wrapper.appendChild(projectElement);

                                                initVideos(projectElement);
                                                if (scope.classList.contains('project--images--parallax')) {
                                                    initParallaxImages(projectElement.querySelectorAll('img'));
                                                }

                                            })

                                            if (scope.classList.contains('portfolio--style--list') && (scope.classList.contains('portfolio--images--hover') || scope.classList.contains('portfolio--images--fullscreen'))) {
                                                projectListHoversInit();
                                                projectListImagesInit(scope.querySelectorAll('.zeyna--portfolio--project'));
                                            } else if (scope.querySelector('.portfolio--list--images--wrap')) {
                                                scope.querySelector('.portfolio--list--images--wrap').remove();
                                            }


                                            if (layout === 'masonry') {

                                                const sizer = document.createElement('span');
                                                sizer.className = 'zeyna--projects--masonry--sizer';
                                                wrapper.appendChild(sizer);

                                                const gutter = document.createElement('span');
                                                gutter.className = 'zeyna--projects--masonry--gutter';
                                                wrapper.appendChild(gutter);

                                                var msnry = new Masonry(wrapper, {
                                                    itemSelector: '.zeyna--portfolio--project',
                                                    columnWidth: '.zeyna--projects--masonry--sizer',
                                                    gutter: '.zeyna--projects--masonry--gutter',
                                                    percentPosition: true,
                                                });

                                            }
                                        }, 400);

                                        setTimeout(() => {
                                            wrapper.classList.remove('hidden');
                                            wrapper.classList.remove('filters--loading');
                                            document.documentElement.classList.remove('loading');
                                            trigger ? trigger.classList.remove('loading') : '';
                                            throttledWidgetRefresh(100); // OPTIMIZED

                                        }, 600);

                                    } else {

                                        projectsHtml.forEach((projectsHtml, i) => {
                                            let tempDiv = document.createElement("div");
                                            tempDiv.innerHTML = projectsHtml;

                                            let projectElement = tempDiv.firstElementChild;
                                            wrapper.appendChild(projectElement);

                                            if (scope.classList.contains('project--images--parallax')) {
                                                initParallaxImages(projectElement.querySelectorAll('img'));

                                            }

                                            if (scope.classList.contains('portfolio--style--masonry')) {
                                                let masonry = Masonry.data(wrapper);

                                                masonry.appended(projectElement);
                                                setTimeout(() => {
                                                    masonry.layout();
                                                    ScrollTrigger.update();
                                                    ScrollTrigger.getById('loadInfnite') ? ScrollTrigger.getById('loadInfnite').refresh() : '';

                                                    initVideos(projectElement);

                                                }, 10);

                                                document.documentElement.classList.remove('loading');
                                                trigger ? trigger.classList.remove('loading') : '';

                                            } else {
                                                tl.fromTo(projectElement, {
                                                    opacity: 0,
                                                    yPercent: 100
                                                }, {
                                                    opacity: 1,
                                                    yPercent: 0,
                                                    duration: .75,
                                                    ease: 'expo.out',
                                                    onComplete: () => {
                                                        // clearProps(projectElement);
                                                        document.documentElement.classList.remove('loading');
                                                        trigger ? trigger.classList.remove('loading') : '';
                                                        if (scope.classList.contains('portfolio--style--list') && (scope.classList.contains('portfolio--images--hover') || scope.classList.contains('portfolio--images--fullscreen'))) {

                                                            projectListImagesInit(scope.querySelectorAll('.zeyna--portfolio--project'));

                                                        }

                                                        initVideos(projectElement);

                                                        throttledWidgetRefresh(100); // OPTIMIZED

                                                    }
                                                }, i * 0.15);

                                            }


                                        })

                                    }



                                    if (parseInt(portfolio.dataset.found) === scope.querySelectorAll('.zeyna--portfolio--project').length) {

                                        trigger ? trigger.classList.add('hidden') : '';

                                        ScrollTrigger.getById('loadInfnite') ? ScrollTrigger.getById('loadInfnite').kill(true) : '';

                                    }

                                    setTimeout(() => {
                                        if (scope.querySelector('[data-cursor="true"]')) {

                                            var targets = scope.querySelectorAll('[data-cursor="true"]');
                                            targets.forEach(target => {
                                                peCursorInteraction(target);
                                            })

                                        }



                                    }, 500);

                                } else {
                                    wrapper.classList.remove('hidden');
                                    wrapper.classList.remove('filters--loading');
                                    document.documentElement.classList.remove('loading');
                                    trigger ? trigger.classList.remove('loading') : '';

                                }

                            } else {

                            }


                        },
                        error: function (response) {
                            console.log(response.error);
                        }
                    });

                }


                if (scope.querySelector('.portfolios--pagination')) {

                    if (scope.querySelector('.portfolios--load--more')) {
                        scope.querySelector('.portfolios--load--more .pe--button').addEventListener('click', (e) => {

                            AJAXGetProjects(scope.querySelector('.portfolios--load--more .pe--button'), 'load-more', settings, args);
                        })
                    }

                    if (scope.querySelector('.ajax-infinite-scroll')) {

                        ScrollTrigger.create({
                            trigger: wrapper,
                            id: 'loadInfnite',
                            start: 'bottom bottom',
                            end: 'bottom top',
                            onEnter: () => {
                                AJAXGetProjects(scope.querySelector('.ajax-infinite-scroll'), 'load-more', settings, args);
                            }
                        })

                    }

                }

                if (scope.querySelector('.pe--portfolio--filters')) {

                    var filters = scope.querySelector('.pe--portfolio--filters'),
                        terms = filters.querySelectorAll('.term-item'),
                        filtersArray = {};

                    terms.forEach(term => {
                        term.addEventListener('click', (e) => {
                            let termId = parseInt(term.dataset.termId);
                            AJAXGetProjects(term, 'filter', settings, args);
                        })
                    });

                    if (scope.querySelector('.portfolio--clear--filters')) {

                        scope.querySelector('.portfolio--clear--filters').addEventListener('click', () => {

                            terms.forEach(el => {
                                el.classList.remove('active');
                            });
                            AJAXGetProjects(scope.querySelector('.portfolio--clear--filters'), 'all', settings, args);

                        })
                    }


                }

                if (scope.querySelector('.pe--portfolio--style--switcher')) {

                    var switcher = scope.querySelector('.pe--switcher'),
                        switchItems = switcher.querySelectorAll('.switch--item');

                    switchItems.forEach(item => {

                        item.addEventListener('click', (e) => {

                            portfolio.classList.add('style--switched');
                            AJAXGetProjects(item, 'layout--switch', settings, args);


                        })

                    })
                }

                if (scope.classList.contains('filters--style--dropdown')) {

                    let filtersButton = scope.querySelector('.filters--button'),
                        filtersWrapper = scope.querySelector('.pe--portfolio--filters');

                    filtersButton.addEventListener('click', () => {

                        filtersButton.classList.toggle('active');

                        let state = Flip.getState(filtersWrapper, {
                            props: ['height', 'padding']
                        });

                        filtersWrapper.classList.toggle('open');

                        Flip.from(state, {
                            duration: .75,
                            ease: 'expo.out',
                            absolute: true,
                            absoluteOnLeave: true,
                        })

                    })

                }

                if (scope.classList.contains('filters--style--popup')) {

                    pePopup(scope, scope);
                }

                if (scope.classList.contains('filters--pinned')) {

                    let filters = scope.querySelector('.pe--portfolio--controls');

                    let sc = ScrollTrigger.create({
                        trigger: scope,
                        pin: filters,
                        start: 'top top+=100',
                        end: 'bottom bottom'
                    })

                    matchMedia.add({
                        isMobile: "(max-width: 550px)"

                    }, (context) => {

                        let {
                            isMobile
                        } = context.conditions;

                        sc.kill(true);

                    });

                }



            }

        });


        elementorFrontend.hooks.addAction('frontend/element_ready/peportfoliocontrols.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    filtersWidget = scope.querySelector('.pe--portfolio--filters--widget'),
                    queryId = filtersWidget.dataset.id;

                if (!document.querySelector('.query--' + queryId)) {
                    return false;
                }
                let portfolio = document.querySelector('.query--' + queryId),
                    wrapper = portfolio.querySelector('.portfolio--projects--wrapper'),
                    portfolioParent = parents(portfolio, '.elementor-element')[0],
                    settings = portfolioParent.dataset.settings,
                    args = portfolio.dataset.queryArgs;

                var offset = 0;
                var filterTerms = {};
                var currentLayout = portfolio.dataset.style;
                var layout = currentLayout;

                function AJAXGetProjects(trigger, request, settings, args) {

                    document.documentElement.classList.add('loading');
                    trigger.classList ? trigger.classList.add('loading') : '';

                    if (request === 'load-more') {
                        offset++;
                    }

                    if (request === 'filter') {
                        wrapper.classList.add('filters--loading');
                        scope.classList.add('portfolios--filtered');

                        if (trigger) {
                            trigger.classList.toggle('active');

                            let taxonomy = trigger.dataset.taxonomy,
                                termId = trigger.dataset.termId;

                            if (taxonomy && termId) {

                                if (termId === "all") {
                                    delete filterTerms[taxonomy];

                                    scope.querySelectorAll(`[data-taxonomy="${taxonomy}"]`).forEach(el => {
                                        el.classList.remove('active');
                                    });

                                } else {

                                    if (!filterTerms[taxonomy]) {
                                        filterTerms[taxonomy] = [];
                                    }

                                    if (trigger.classList.contains('active')) {

                                        scope.querySelectorAll(`[data-taxonomy="${taxonomy}"][data-term-id="all"]`).forEach(el => {
                                            el.classList.remove('active');
                                        });

                                        if (!filterTerms[taxonomy].includes(termId)) {
                                            filterTerms[taxonomy].push(termId);
                                        }
                                    } else {
                                        filterTerms[taxonomy] = filterTerms[taxonomy].filter(id => id !== termId);

                                        if (filterTerms[taxonomy].length === 0) {
                                            delete filterTerms[taxonomy];

                                            document.querySelectorAll(`[data-taxonomy="${taxonomy}"][data-term-id="all"]`).forEach(el => {
                                                el.classList.add('active');
                                            });
                                        }
                                    }
                                }
                            }
                        }

                    }

                    if (request === 'all') {
                        wrapper.classList.add('filters--loading');
                    }

                    if (request === 'layout--switch') {
                        wrapper.classList.add('hidden');
                        layout = trigger.dataset.style;



                        scope.querySelectorAll('.switch--item').forEach(el => {
                            el.classList.remove('active');
                        });

                        trigger.classList.toggle('active');

                    }

                    $.ajax({
                        url: pe_get_projects.ajax_url,
                        type: "POST",
                        data: {
                            action: "pe_get_projects",
                            request: request,
                            args: args,
                            settings: settings,
                            offset: offset,
                            filters: filterTerms,
                            layout: layout
                        },
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {

                                let scope = portfolioParent;


                                if (request === 'layout--switch' && layout === 'list' && (scope.classList.contains('portfolio--images--hover') || scope.classList.contains('portfolio--images--fullscreen'))) {

                                    const imagesWrap = document.createElement('div');
                                    imagesWrap.className = 'portfolio--list--images--wrap';
                                    const innerImagesWrap = document.createElement('div');
                                    innerImagesWrap.className = 'portfolio--list--images--inner--wrap';

                                    imagesWrap.appendChild(innerImagesWrap);
                                    wrapper.appendChild(imagesWrap);

                                }

                                if ((layout === 'list') && (scope.classList.contains('portfolio--images--hover') || scope.classList.contains('portfolio--images--fullscreen'))) {

                                    let imagesWrapper = scope.querySelector('.portfolio--list--images--inner--wrap');
                                    let imagesHtml = response.data.project_images;

                                    if (imagesHtml.length > 0) {

                                        imagesHtml.forEach((imagesHtml, i) => {
                                            let tempDiv = document.createElement("div");
                                            tempDiv.innerHTML = imagesHtml;
                                            let imageElement = tempDiv.firstElementChild;
                                            imagesWrapper.appendChild(imageElement);
                                            initVideos(imagesWrapper);
                                        })

                                    }

                                }

                                let projectsHtml = response.data.projects;

                                if (projectsHtml.length > 0) {

                                    let tl = gsap.timeline({
                                        onComplete: () => {
                                            ScrollTrigger.update();
                                            ScrollTrigger.getById('loadInfnite') ? ScrollTrigger.getById('loadInfnite').refresh() : '';
                                        }
                                    });

                                    if (request === 'filter' || request === 'all' || request === 'layout--switch') {
                                        wrapper.classList.add('hidden');

                                        if (request === 'layout--switch') {

                                            layout !== 'list' ? scope.classList.remove('portfolio--style--list') : '';
                                            layout !== 'grid' ? scope.classList.remove('portfolio--style--grid') : '';
                                            layout !== 'masonry' ? scope.classList.remove('portfolio--style--masonry') : '';
                                            scope.classList.add('portfolio--style--' + layout);
                                            wrapper.classList.add('style--switched--' + layout);

                                            if (Masonry.data(wrapper)) {
                                                let masonry = Masonry.data(wrapper);
                                                masonry.destroy();
                                                clearProps(wrapper);
                                            }

                                            if (scope.querySelector('.zeyna--projects--masonry--sizer')) {
                                                scope.querySelector('.zeyna--projects--masonry--sizer').remove();
                                                scope.querySelector('.zeyna--projects--masonry--gutter').remove();
                                            }

                                        }

                                        request === 'all' ? scope.classList.remove('portfolios--filtered') : '';

                                        setTimeout(() => {
                                            wrapper.querySelectorAll('.zeyna--portfolio--project').forEach(project => project.remove());

                                            projectsHtml.forEach((projectsHtml, i) => {
                                                let tempDiv = document.createElement("div");
                                                tempDiv.innerHTML = projectsHtml;

                                                let projectElement = tempDiv.firstElementChild;
                                                wrapper.appendChild(projectElement);
                                                initVideos(projectElement);
                                                if (scope.classList.contains('project--images--parallax')) {
                                                    initParallaxImages(projectElement.querySelectorAll('img'));

                                                }

                                            })

                                            if (scope.classList.contains('portfolio--style--list') && (scope.classList.contains('portfolio--images--hover') || scope.classList.contains('portfolio--images--fullscreen'))) {
                                                projectListHoversInit();
                                                projectListImagesInit(scope.querySelectorAll('.zeyna--portfolio--project'));
                                            } else if (scope.querySelector('.portfolio--list--images--wrap')) {
                                                scope.querySelector('.portfolio--list--images--wrap').remove();
                                            }


                                            if (layout === 'masonry') {

                                                const sizer = document.createElement('span');
                                                sizer.className = 'zeyna--projects--masonry--sizer';
                                                wrapper.appendChild(sizer);

                                                const gutter = document.createElement('span');
                                                gutter.className = 'zeyna--projects--masonry--gutter';
                                                wrapper.appendChild(gutter);

                                                var msnry = new Masonry(wrapper, {
                                                    itemSelector: '.zeyna--portfolio--project',
                                                    columnWidth: '.zeyna--projects--masonry--sizer',
                                                    gutter: '.zeyna--projects--masonry--gutter',
                                                    percentPosition: true,
                                                });

                                            }
                                        }, 400);

                                        setTimeout(() => {
                                            wrapper.classList.remove('hidden');
                                            wrapper.classList.remove('filters--loading');
                                            document.documentElement.classList.remove('loading');
                                            trigger ? trigger.classList.remove('loading') : '';

                                        }, 600);

                                    } else {

                                        projectsHtml.forEach((projectsHtml, i) => {
                                            let tempDiv = document.createElement("div");
                                            tempDiv.innerHTML = projectsHtml;

                                            let projectElement = tempDiv.firstElementChild;
                                            wrapper.appendChild(projectElement);

                                            if (scope.classList.contains('project--images--parallax')) {
                                                initParallaxImages(projectElement.querySelectorAll('img'));

                                            }

                                            if (scope.classList.contains('portfolio--style--masonry')) {
                                                let masonry = Masonry.data(wrapper);

                                                masonry.appended(projectElement);
                                                setTimeout(() => {
                                                    masonry.layout();
                                                    ScrollTrigger.update();
                                                    ScrollTrigger.getById('loadInfnite') ? ScrollTrigger.getById('loadInfnite').refresh() : '';

                                                    initVideos(projectElement);

                                                }, 10);

                                                document.documentElement.classList.remove('loading');
                                                trigger ? trigger.classList.remove('loading') : '';

                                            } else {
                                                tl.fromTo(projectElement, {
                                                    opacity: 0,
                                                    yPercent: 100
                                                }, {
                                                    opacity: 1,
                                                    yPercent: 0,
                                                    duration: .75,
                                                    ease: 'expo.out',
                                                    onComplete: () => {
                                                        clearProps(projectElement);
                                                        document.documentElement.classList.remove('loading');
                                                        trigger ? trigger.classList.remove('loading') : '';
                                                        if (scope.classList.contains('portfolio--style--list') && (scope.classList.contains('portfolio--images--hover') || scope.classList.contains('portfolio--images--fullscreen'))) {

                                                            projectListImagesInit(scope.querySelectorAll('.zeyna--portfolio--project'));

                                                        }

                                                        initVideos(projectElement);

                                                    }
                                                }, i * 0.15);

                                            }


                                        })

                                    }



                                    if (parseInt(portfolio.dataset.found) === scope.querySelectorAll('.zeyna--portfolio--project').length) {

                                        trigger ? trigger.classList.add('hidden') : '';

                                        ScrollTrigger.getById('loadInfnite') ? ScrollTrigger.getById('loadInfnite').kill(true) : '';

                                    }

                                    setTimeout(() => {
                                        if (scope.querySelector('[data-cursor="true"]')) {

                                            var targets = scope.querySelectorAll('[data-cursor="true"]');
                                            targets.forEach(target => {
                                                peCursorInteraction(target);
                                            })

                                        }



                                    }, 500);

                                } else {
                                    wrapper.classList.remove('hidden');
                                    wrapper.classList.remove('filters--loading');
                                    document.documentElement.classList.remove('loading');
                                    trigger ? trigger.classList.remove('loading') : '';

                                }

                                throttledWidgetRefresh(1000); // OPTIMIZED

                            }
                        },
                        error: function (response) {
                            console.log(response.error);
                        }
                    });

                }


                if (scope.querySelector('.portfolios--pagination')) {

                    if (scope.querySelector('.portfolios--load--more')) {
                        scope.querySelector('.portfolios--load--more .pe--button').addEventListener('click', (e) => {

                            AJAXGetProjects(scope.querySelector('.portfolios--load--more .pe--button'), 'load-more', settings, args);
                        })
                    }

                    if (scope.querySelector('.ajax-infinite-scroll')) {

                        ScrollTrigger.create({
                            trigger: wrapper,
                            id: 'loadInfnite',
                            start: 'bottom bottom',
                            end: 'bottom top',
                            onEnter: (e) => {
                                AJAXGetProjects(scope.querySelector('.ajax-infinite-scroll'), 'load-more', settings, args);
                            }
                        })

                    }

                }

                if (scope.querySelector('.pe--portfolio--filters')) {

                    var filters = scope.querySelector('.pe--portfolio--filters'),
                        terms = filters.querySelectorAll('.term-item'),
                        filtersArray = {};

                    terms.forEach(term => {
                        term.addEventListener('click', (e) => {
                            let termId = parseInt(term.dataset.termId);
                            AJAXGetProjects(term, 'filter', settings, args);
                        })
                    });

                    if (scope.querySelector('.portfolio--clear--filters')) {

                        scope.querySelector('.portfolio--clear--filters').addEventListener('click', () => {

                            terms.forEach(el => {
                                el.classList.remove('active');
                            });

                            AJAXGetProjects(scope.querySelector('.portfolio--clear--filters'), 'all', settings, args);

                        })
                    }


                }

                if (scope.querySelector('.pe--portfolio--style--switcher')) {

                    var switcher = scope.querySelector('.pe--switcher'),
                        switchItems = switcher.querySelectorAll('.switch--item');

                    switchItems.forEach(item => {

                        item.addEventListener('click', () => {

                            portfolio.classList.add('style--switched');
                            AJAXGetProjects(item, 'layout--switch', settings, args);

                        })

                    })

                }

                if (scope.classList.contains('filters--style--dropdown')) {

                    let filtersButton = scope.querySelector('.filters--button'),
                        filtersWrapper = scope.querySelector('.pe--portfolio--filters');

                    filtersButton.addEventListener('click', () => {

                        filtersButton.classList.toggle('active');

                        let state = Flip.getState(filtersWrapper, {
                            props: ['height', 'padding']
                        });

                        filtersWrapper.classList.toggle('open');

                        Flip.from(state, {
                            duration: .75,
                            ease: 'expo.out',
                            absolute: true,
                            absoluteOnLeave: true,
                        })

                    })

                }

                if (scope.classList.contains('filters--style--popup')) {

                    pePopup(scope, scope);
                }

                if (scope.classList.contains('filters--pinned')) {

                    let filters = scope.querySelector('.pe--portfolio--controls');

                    let sc = ScrollTrigger.create({
                        trigger: scope,
                        pin: filters,
                        start: 'top top+=100',
                        end: 'bottom bottom'
                    })

                    matchMedia.add({
                        isMobile: "(max-width: 550px)"

                    }, (context) => {

                        let {
                            isMobile
                        } = context.conditions;

                        sc.kill(true);

                    });

                }



            }
        })

        elementorFrontend.hooks.addAction('frontend/element_ready/peportfoliocategories.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    list = scope.querySelector('.categories--list'),
                    imagesWrapper = scope.querySelector('.category--images'),
                    items = list.querySelectorAll('.portfolio--category');


                if (scope.classList.contains('category--images--hover')) {

                    list.addEventListener('mouseenter', () => {
                        imagesWrapper.classList.add('active');
                    })

                    list.addEventListener('mouseleave', () => {
                        imagesWrapper.classList.remove('active');
                    })

                    list.addEventListener("mousemove", (e) => {
                        gsap.to(imagesWrapper, {
                            x: e.clientX,
                            y: e.clientY
                        })
                    });

                    items.forEach(item => {

                        let id = item.dataset.id;

                        item.addEventListener('mouseenter', () => {
                            imagesWrapper.querySelector('.category--image_' + id).classList.add('active');
                        })

                        item.addEventListener('mouseleave', () => {
                            imagesWrapper.querySelector('.category--image_' + id).classList.remove('active');
                        })


                    })

                };

            }
        })

        elementorFrontend.hooks.addAction('frontend/element_ready/peinteractivegrid.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    items = scope.querySelectorAll('.interactive--grid--item'),
                    clicks = 0;


                if (scope.classList.contains('expand--items--yes')) {
                    imagesLoaded(scope, function (instance) {
                        items.forEach(item => {

                            gsap.set(item, {
                                width: item.getBoundingClientRect().width,
                                height: item.getBoundingClientRect().height,
                            })

                            if (item.getBoundingClientRect().left > (document.body.clientWidth / 2)) {

                                item.classList.add('grid--item--right');

                            }

                            item.addEventListener('click', () => {

                                clicks++;

                                let states = Flip.getState(item.querySelectorAll('.grid--item--state'), {
                                    props: ['height', 'minHeight', 'maxHeight', 'padding', 'opacity']
                                });

                                item.classList.toggle('active');

                                gsap.set(item, {
                                    zIndex: clicks
                                })

                                Flip.from(states, {
                                    duration: 1.25,
                                    ease: 'expo.inOut',
                                    absolute: true,
                                    absoluteOnLeave: true
                                })

                            })

                        })

                    })

                }

            }
        })



        elementorFrontend.hooks.addAction('frontend/element_ready/peloadertransitionelement.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    id = scope.dataset.id,
                    element = scope.querySelector('.pe--lt--element'),
                    countLabels = window.countLabels,
                    totImages,
                    loadedImages,
                    percentage,
                    tl;

                if (scope.classList.contains('initialized')) {
                    return false;
                } else {
                    scope.classList.add('initialized')
                }

                tl = gsap.getById('pageLoader');

                if (document.body.classList.contains('e-preview--show-hidden-elements')) {
                    tl = gsap.timeline({
                        repeat: -1,
                        delay: 1,
                        repeatDelay: 1
                    });
                }

                if (scope.classList.contains('used--for--loader')) {

                    if (!scope.classList.contains('intro--none')) {

                        if (scope.classList.contains('intro--fade')) {

                            gsap.fromTo(element, {
                                opacity: 0,
                                yPercent: scope.classList.contains('fade_up') ? 100 : scope.classList.contains('fade_down') ? -100 : 0,
                                xPercent: scope.classList.contains('fade_left') ? -100 : scope.classList.contains('fade_right') ? 100 : 0,
                            }, {
                                opacity: 1,
                                yPercent: 0,
                                xPercent: 0,
                                delay: .5,
                                ease: 'power3.inOut'
                            })
                        }

                        if (scope.classList.contains('intro--slide')) {

                            gsap.fromTo(element, {
                                y: scope.classList.contains('slide_up') ? '100vh' : scope.classList.contains('slide_down') ? '-100vh' : 0,
                                x: scope.classList.contains('slide_left') ? '-100vw' : scope.classList.contains('slide_right') ? '100vw' : 0,
                            }, {
                                opacity: 1,
                                y: 0,
                                x: 0,
                                duration: 1.25,
                                delay: .75,
                                ease: 'expo.out'
                            })
                        }

                        if (scope.classList.contains('intro--block')) {

                            gsap.to(element, {
                                opacity: 1,
                                yPercent: 0,
                                y: 0,
                                duration: 1.25,
                                delay: .75,
                                ease: 'expo.out'
                            })
                        }

                    }

                    function elementsHandle(images, loaded) {

                        totImages = images;
                        loadedImages = loaded;
                        percentage = 100 / (totImages / loadedImages);

                        let snapPerc = gsap.utils.snap(countLabels, percentage);

                        if (scope.classList.contains('element--logo')) {

                            if (scope.querySelector('.loader--svg--logo')) {

                                let wrapper = scope.querySelector('.loader--svg--logo'),
                                    svg = scope.querySelector('svg'),
                                    paths = svg.querySelectorAll('line , ellipse , polygon , rect , g , path , circle');

                                wrapper.classList.add('draw--start');

                                paths.forEach((path, i) => {
                                    if (!path.dataset.fillColor) {
                                        path.dataset.fillColor = window.getComputedStyle(path).fill;
                                    }

                                    gsap.set(path, {
                                        drawSVG: '0%',
                                        fill: 'transparent'
                                    });

                                    tl.to(path, {
                                        drawSVG: snapPerc / 2 + '%',
                                        duration: 1,
                                        ease: 'power4.inOut',
                                        onComplete: () => {
                                            if (snapPerc == 100 && path.dataset.fillColor) {
                                                gsap.to(path, {
                                                    fill: path.dataset.fillColor
                                                })

                                            }
                                        }
                                    }, 'label_' + snapPerc);
                                });


                            } else {

                                let logo = scope.querySelector('.loader--logo--clone'),
                                    val = percentage + '%';

                                if (scope.classList.contains('logo--direction--vertical')) {

                                    let mask = 'inset(' + (100 - percentage) + '% 0% 0% 0%)'

                                    tl.to(logo, {
                                        clipPath: mask
                                    }, 'label_' + snapPerc);

                                } else {
                                    tl.to(logo, {
                                        width: val,
                                    }, 'label_' + snapPerc);

                                }

                            }
                        }

                        if (scope.classList.contains('caption--animation--fill')) {

                            let valo = percentage + '%';

                            tl.to(scope.querySelector('.caption--clone'), {
                                width: valo,
                            }, 'label_' + snapPerc);
                        }

                        if (scope.querySelector('.loader--progress--bar')) {

                            let loaderFill = scope.querySelector('.progress--fill');

                            if (scope.classList.contains('progressbar--direction--circle')) {

                                let fillSVG = scope.querySelector('svg.fill circle');

                                gsap.set(fillSVG, {
                                    drawSVG: 0,
                                    visibility: 'visible'
                                })

                                tl.to(fillSVG, {
                                    drawSVG: percentage + '%',
                                    delay: 0,
                                    ease: "power4.inOut"
                                }, 'label_' + snapPerc);



                            } else if (scope.classList.contains('progressbar--direction--horizontal')) {
                                tl.to(loaderFill, {
                                    width: percentage + '%',
                                    ease: "power4.inOut",
                                    duration: .75
                                }, 'label_' + snapPerc);


                            } else {

                                tl.to(loaderFill, {
                                    height: percentage + '%',
                                    ease: "power4.inOut",
                                    duration: .75
                                }, 'label_' + snapPerc);
                            }


                            if (scope.querySelector('.loader--progress-count')) {

                                tl.to(scope.querySelector('.loader--progress-count .lpc-count'), {
                                    text: { value: Math.floor(percentage) },
                                    ease: "power4.inOut",
                                    duration: .75
                                }, 'label_' + snapPerc);


                                tl.to(scope.querySelector('.loader--progress-count'), {
                                    top: scope.classList.contains('progressbar--direction--vertical') ? 100 - percentage + '%' : '',
                                    left: scope.classList.contains('progressbar--direction--horizontal') ? percentage + '%' : '',
                                    xPercent: scope.classList.contains('progressbar--direction--horizontal') ? -100 : 0,
                                    ease: "power4.inOut",
                                    duration: .75

                                }, 'label_' + snapPerc);

                            }

                        }

                        if (scope.querySelector('.loader--counter')) {

                            if (scope.classList.contains('counter--simple')) {

                                let count = scope.querySelector('.loader--count');

                                tl.to(count, {
                                    text: { value: Math.floor(snapPerc), delimiter: '' },
                                    ease: 'bounce.out'
                                }, 'label_' + snapPerc);

                            } else if (scope.classList.contains('counter--animated')) {

                                let height = scope.querySelector('.loader--counter').getBoundingClientRect().height;

                                if (snapPerc !== 0 && snapPerc !== 100) {
                                    const digits = snapPerc.toString().split('').map(Number);


                                    tl.to('.count--char--1', {
                                        y: digits[0] * -height,
                                        delay: .15,
                                        duration: 1,
                                        ease: 'power4.inOut',
                                    }, 'label_' + snapPerc);

                                    tl.to('.count--char--2', {
                                        y: digits[1] * -height,
                                        delay: .15,
                                        duration: 1,
                                        ease: 'power4.inOut',
                                    }, 'label_' + snapPerc);

                                } else if (snapPerc == 100) {


                                    tl.to('.count--char--1', {
                                        y: 10 * -height,
                                        delay: .5,
                                        duration: .75,
                                        ease: 'expo.inOut',
                                    }, 'label_100');

                                    tl.to('.count--char--2', {
                                        y: 10 * -height,
                                        delay: .5,
                                        duration: .75,
                                        ease: 'expo.inOut',
                                    }, 'label_100');

                                    tl.to('.count--char--unit', {
                                        y: 0,
                                        delay: .5,
                                        duration: .75,
                                        ease: 'expo.inOut',
                                    }, 'label_100');

                                }

                            }

                        }



                        tl.play();


                    }

                    if (document.body.classList.contains('e-preview--show-hidden-elements')) {
                        elementsHandle(10, 10)
                    }

                    if (tl) {
                        $('body').imagesLoaded()
                            .progress(function (instance) {
                                elementsHandle(instance.images.length, instance.progressedCount)
                            }).done(function () {

                            });
                    }


                }

                if (scope.querySelector('.caption--repeater--wrap')) {

                    let repeaterInner = scope.querySelector('.capt--repeater--inner'),
                        texts = repeaterInner.querySelectorAll('span'),
                        repeaterTl = gsap.timeline({ repeat: -1 });

                    for (let i = 1; i < texts.length; i++) {
                        let yVal = (100 / texts.length) * i;
                        repeaterTl.to(repeaterInner, {
                            yPercent: -yVal,
                            duration: .65,
                            ease: 'power2.inOut',
                            delay: .4,

                        })
                    }

                }

                if (scope.classList.contains('caption--animation--chars') || scope.classList.contains('caption--animation--words')) {

                    var captions = scope.querySelectorAll('.loader--caption'),
                        charsTl = gsap.timeline({ repeat: -1, delay: 1 }),
                        target, cloneTarget, type;

                    SplitText.create(scope.querySelectorAll('.loader--caption'), {
                        type: "chars , words",
                        charsClass: "capt_char",
                        wordsClass: "capt_word",
                        autoSplit: true,
                    });



                    if (scope.classList.contains('caption--animation--chars')) {
                        target = scope.querySelectorAll('.loader--caption:not(.caption--clone) .capt_char');
                        cloneTarget = scope.querySelectorAll('.caption--clone .capt_char');
                        type = 'chars';
                    }

                    if (scope.classList.contains('caption--animation--words')) {
                        target = scope.querySelectorAll('.loader--caption:not(.caption--clone) .capt_word');
                        cloneTarget = scope.querySelectorAll('.caption--clone .capt_word');
                        type = 'words';
                    }


                    charsTl.to(target, {
                        yPercent: -100,
                        stagger: type === 'chars' ? 0.03 : 0.2,
                        ease: 'power4.inOut',
                        duration: 1
                    }, 0)

                    charsTl.to(cloneTarget, {
                        yPercent: -100,
                        stagger: type === 'chars' ? 0.03 : 0.2,
                        ease: 'power4.inOut',
                        duration: 1
                    }, 0)




                }

            }
        })

        // Showcases 

        elementorFrontend.hooks.addAction('frontend/element_ready/peshowcasecards.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {

                var scope = jsScopeArray[i],
                    $this = scope.querySelector('.showcase--cards'),
                    offset = parseInt($this.dataset.offset),
                    depth = parseInt($this.dataset.depth);

                let project = $this.querySelectorAll('.zeyna--portfolio--project'),
                    mainProject = $this.querySelectorAll('.showcase--project'),
                    isInfinite = scope.classList.contains('infinite__active'),
                    length = isInfinite ? mainProject.length : mainProject.length - 1;

                gsap.getById(scope.dataset.id) ? gsap.getById(scope.dataset.id).scrollTrigger.kill(true) : '';

                let tl = gsap.timeline({
                    id: scope.dataset.id,
                    scrollTrigger: {
                        trigger: $this.dataset.pinTarget ? $this.dataset.pinTarget : $this,
                        pin: $this.dataset.pinTarget ? $this.dataset.pinTarget : true,
                        start: 'top top',
                        end: 'top+=' + $this.dataset.speed + ' top',
                        scrub: true,
                        onUpdate: function (self) {
                            $this.style.setProperty('--zProgress', (self.progress * length * (offset * depth)) + 'px');
                            $this.style.setProperty('--yProgress', (self.progress * length * offset) + 'px');
                        }
                    }
                })

                project.forEach(function ($item, i) {
                    $item.setAttribute('data-index', i)
                    $item.style.setProperty('--transformZ', -i * (offset * depth) + 'px')
                    $item.style.setProperty('--transformY', -i * offset + 'px')
                    $item.style.zIndex = 1000 - i

                    if (i < length) {
                        tl.to($item, {
                            top: '100vh'
                        })
                    }

                })

                isInfinite && infinitePage();

            }
        });





        elementorFrontend.hooks.addAction('frontend/element_ready/peshowcasevoid.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {

                var scope = jsScopeArray[i],
                    showcaseVoid = document.querySelectorAll('.showcase--void');
                showcaseVoid.forEach(function ($this) {
                    let project = $this.querySelectorAll('.project--container'),
                        isInfinite = scope.classList.contains('infinite__active'),
                        length = isInfinite ? $this.querySelectorAll('.main--project').length : $this.querySelectorAll('.main--project').length - 1

                    let positions = [];
                    for (let r = 1; r <= 2; r++) {
                        for (let c = 1; c <= 2; c++) {
                            let projectWidth = project[0].offsetWidth
                            if (c === 2) {
                                projectWidth = 0
                            }

                            let maxTop = r * ($this.offsetHeight / 2) - 600,
                                minTop = (r - 1) * ($this.offsetHeight / 2),
                                maxLeft = c * ($this.offsetWidth / 2) - projectWidth,
                                minLeft = (c - 1) * ($this.offsetWidth / 2)

                            matchMedia.add({
                                isMobile: "(max-width: 550px)"

                            }, (context) => {

                                let {
                                    isMobile
                                } = context.conditions;
                                maxTop = r * ($this.offsetHeight / 2) - 525
                                maxLeft = c * ($this.offsetWidth / 2) - (projectWidth * 1.25)
                            });

                            positions.push({
                                top: gsap.utils.random(minTop, maxTop),
                                left: gsap.utils.random(minLeft, maxLeft)
                            });

                        }
                    }

                    $this.querySelectorAll('.project--container').forEach(function ($item, index) {

                        let pos = positions[index % positions.length];
                        $item.setAttribute('data-top', pos.top)
                        $item.setAttribute('data-left', pos.left)

                        gsap.set($item, {
                            top: parseInt($item.dataset.top),
                            left: parseInt($item.dataset.left),
                        })

                        gsap.set($this.querySelector('.clone--project_' + index), {
                            top: parseInt(project[index].dataset.top),
                            left: parseInt(project[index].dataset.left)
                        })

                    })

                    let tl = gsap.timeline({
                        ease: 'none',
                        scrollTrigger: {
                            trigger: $this.dataset.pinTarget ? $this.dataset.pinTarget : $this,
                            pin: $this.dataset.pinTarget ? $this.dataset.pinTarget : true,
                            start: 'top top',
                            end: 'top+=' + $this.dataset.speed + ' top',
                            scrub: true,
                            onUpdate: function (self) {
                                $this.style.setProperty('--zProgress', 200 * length * self.progress + 'px')
                                $this.style.setProperty('--opacityProg', self.progress * length * 0.33)
                                $this.style.setProperty('--filterProg', self.progress * length * 15 + 'px')


                            }
                        }
                    })

                    project = $this.querySelectorAll('.project--container');
                    project.forEach(function ($item, index) {

                        $item.style.setProperty('--transformZ', (index * -200) - 100 + 'px')
                        $item.style.setProperty('--opacity', 1 - (index * 0.33))
                        $item.style.setProperty('--blur', index * 15 + 'px')
                        $item.style.zIndex = 1000 - index
                        if (!$item.classList.contains('clone__project')) {
                            tl.to($item.querySelector('.portfolio--project--wrapper'), {
                                opacity: 0,
                                filter: 'blur(10px)',
                                delay: 0.2,

                            })
                        }

                    })

                    isInfinite && infinitePage();

                })
            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/peshowcasewebglgrid.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {

                var scope = jsScopeArray[i],
                    grid = scope.querySelector('.pe--webgl--grid'),
                    wrapper = scope.querySelector('.pe--webgl--grid--wrapper'),
                    controls = scope.querySelector('.pe--webgl--grid--controls--wrapper'),
                    gridItems = wrapper.querySelectorAll('.interactive--grid--item'),
                    draggable;

                // if (scope.classList.contains('canvas--initalized')) {
                //     return;
                // }
                // scope.classList.add('canvas--initalized')    

                var style = getComputedStyle(scope),
                    planesWidth = parseInt(style.getPropertyValue('--imagesWidth').trim()),
                    planesHeight = parseInt(style.getPropertyValue('--imagesHeight').trim()),
                    planesGap = parseInt(style.getPropertyValue('--gridGap').trim()),
                    gridCols = parseInt(style.getPropertyValue('--gridCols').trim());

                if (!planesWidth) planesWidth = 160;
                if (!planesHeight) planesHeight = 200;
                if (!planesGap) planesGap = 15;
                if (!gridCols) gridCols = 8;


                const clamp = (value, min, max) => Math.min(Math.max(value, min), max);
                const lerp = (a, b, t) => a + (b - a) * t;

                class webglGrid {

                    constructor(container) {
                        this.container = container;
                        this.DOMElements = [...this.container.querySelectorAll('.pe--webgl--project')];

                        this.renderer = new THREE.WebGLRenderer({
                            antialias: true,
                            alpha: true,
                        });

                        this.renderer.setPixelRatio(Math.min(1.5, window.devicePixelRatio));
                        this.renderer.setSize(window.innerWidth, window.innerHeight);
                        this.renderer.domElement.classList.add('content__canvas');
                        this.container.appendChild(this.renderer.domElement);

                        this.scene = new THREE.Scene();
                        this.zoomed = false;

                        const width = window.innerWidth;
                        const height = window.innerHeight;

                        this.camera = new THREE.PerspectiveCamera(
                            45,
                            width / height,
                            1,
                            3000
                        );

                        // Kamera Z mesafesini px’e göre ayarla
                        this.camera.position.z =
                            height / (1.8 * Math.tan((this.camera.fov * Math.PI) / 360));

                        this.raycaster = new THREE.Raycaster();
                        this.mouseNDC = new THREE.Vector2();
                        this.hoveredPlane = null;
                        this.clicked = false;

                        this.gridHover = 0;

                        this.mouse = {
                            current: new THREE.Vector2(0, 0),
                            target: new THREE.Vector2(0, 0),
                            ease: 0.1
                        };

                        this.touch = {
                            startX: 0,
                            startY: 0,
                            lastX: 0,
                            lastY: 0,
                            isDown: false
                        };

                        this.setUpPlanes();

                        function layoutGridCentered(planes, {
                            columns = 5,
                            planesWidth = 1,
                            planesHeight = 1,
                            gapX = 0.2,
                            gapY = 0.2
                        }) {
                            const rows = Math.ceil(planes.length / columns);

                            const totalWidth = columns * planesWidth + (columns - 1) * gapX;
                            const totalHeight = rows * planesHeight + (rows - 1) * gapY;

                            const startX = -totalWidth / 2 + planesWidth / 2;
                            const startY = totalHeight / 2 - planesHeight / 2;

                            planes.forEach((plane, i) => {
                                const col = i % columns;
                                const row = Math.floor(i / columns);

                                const x = startX + col * (planesWidth + gapX);
                                const y = startY - row * (planesHeight + gapY);

                                plane.userData.baseX = x;
                                plane.userData.baseY = y;

                                plane.position.set(x, y, plane.position.z);
                            });
                        }

                        layoutGridCentered(this.scene.children, {
                            columns: gridCols,
                            planesWidth: planesWidth,
                            planesHeight: planesHeight,
                            gapX: planesGap,
                            gapY: planesGap,
                            startX: -3,
                            startY: 2
                        });

                        this.gridGapScale = 1;
                        this.gridGapTarget = 1;

                        this.gridBounds = new THREE.Box3().setFromObject(this.scene);

                        this.gridHover = 0;
                        this.gridHoverTarget = 0;

                        // carousel limits
                        this.planeWidth = planesWidth + planesGap; // plane aralığı (baseX ile aynı)
                        this.totalPlanes = this.scene.children.length;

                        this.render();

                        this.renderer.domElement.addEventListener('mousemove', this.onMouseMove.bind(this));

                        window.addEventListener('resize', this.onResize.bind(this));
                        window.addEventListener('touchstart', this.onTouchStart.bind(this), { passive: true });
                        window.addEventListener('touchmove', this.onTouchMove.bind(this), { passive: true });
                        window.addEventListener('touchend', this.onTouchEnd.bind(this));

                        this.renderer.domElement.addEventListener('click', this.onClick.bind(this));
                        scope.querySelector('.grid--zoom').addEventListener('click', this.gridZoom.bind(this));

                        scope.querySelectorAll('.grid--nav').forEach(nav => nav.addEventListener('click', this.gridNav.bind(this)))

                    }
                    setUpPlanes() {
                        this.DOMElements.forEach((project, i) => {
                            this.scene.add(this.generatePlane(project, i));
                        });
                    }
                    generatePlane(project, i) {
                        let texture;

                        const img = project.querySelector('img');
                        const video = project.querySelector('video');

                        if (video) {
                            // 🔹 VIDEO TEXTURE
                            video.crossOrigin = 'anonymous'; // ŞART
                            video.muted = true;
                            video.loop = true;
                            video.playsInline = true;
                            video.autoplay = true;
                            video.src = video.querySelector('source').src;
                            video.play();

                            texture = new THREE.VideoTexture(video);
                            texture.colorSpace = THREE.SRGBColorSpace;
                            texture.minFilter = THREE.LinearFilter;
                            texture.magFilter = THREE.LinearFilter;
                            texture.generateMipmaps = false;

                        } else if (img) {
                            // 🔹 IMAGE TEXTURE
                            texture = new THREE.TextureLoader().load(img.src);
                            texture.colorSpace = THREE.SRGBColorSpace;

                            texture.minFilter = THREE.LinearFilter;
                            texture.magFilter = THREE.LinearFilter;
                            texture.generateMipmaps = false;
                        }

                        const material = new THREE.ShaderMaterial({
                            vertexShader: `
                        varying vec2 vUv;
                        varying vec3 vWorldPos;
                        
                        uniform float uHover;
                        uniform float uGridHover;
                        uniform vec2 uMouse;
                        uniform float uTime;
                        uniform float uClick;
                        
                        #define PI 3.141592653
        
                        void main() {
                            vUv = uv;
    
                            vec3 pos = position;
                            vec3 worldPos = (modelMatrix * vec4(position, 1.0)).xyz;
                            vWorldPos = worldPos; 

                            float dWave = distance(worldPos.xy, vec2(0.0));
                            pos.z += sin(dWave * 0.01 + uTime) * 30.0 * (uGridHover - 1.0) * uClick;

                            float d = distance(worldPos.xy, uMouse);
                            pos.z += sin(d * 0.005 + 1.0) * 125.0 * uGridHover;
                            
                            // float d1 = distance(vUv, vec2(0.5));
                            
                            // float influence = smoothstep(1.0, 0.0, d1);
                            // pos.z += influence * (uClick * 125.0);

                            gl_Position = projectionMatrix * modelViewMatrix * vec4(pos, 1.0);
                        }
                      `,
                            fragmentShader: `
                        uniform sampler2D uTexture;
                        
                        varying vec2 vUv;
                        varying vec3 vWorldPos;
                        
                        uniform float uHover;
                        uniform float uClick;
                        uniform float uOpacity;
                        uniform float uGrayscaleProgress;
                        uniform vec2 uMouse;
                        
                        vec3 toGrayscale(vec3 color) {
                          float gray = dot(color, vec3(0.200, 0.450, 0.100));
                          return vec3(gray);
                        }
                        
                        void main() {

                          vec3 originalColor = texture2D(uTexture, vUv).rgb;
                          vec3 grayscaleColor = toGrayscale(originalColor);
                          
                          float d = distance(vWorldPos.xy, uMouse);
          
                          // 2. Create a circular mask.
                          float mask = smoothstep(0.0, (uHover * 1.0 + (1.0 * 300.0)), d);
                        
                          // 3. Mix the colors based on the mask's value for each pixel.
                          vec3 finalColor = mix(originalColor, grayscaleColor, mask);
                          
                          gl_FragColor = vec4(finalColor, uOpacity);
                        }
                      `,
                            transparent: true,
                            uniforms: {
                                uGrayscaleProgress: { value: 0.0 },
                                uClick: { value: 1.0 },
                                uGridHover: { value: 0.0 },
                                uHover: { value: 0.0 },
                                uTime: { value: 0.0 },
                                uOpacity: { value: 1.0 },
                                uTexture: { value: texture },
                                uMouse: { value: new THREE.Vector2(0, 0) },
                            },
                            transparent: true
                        });

                        const plane = new THREE.Mesh(
                            new THREE.PlaneGeometry(planesWidth, planesHeight, 50, 50),
                            material
                        );

                        plane.userData.mouse = {
                            current: new THREE.Vector2(0.0, 0.0),
                            target: new THREE.Vector2(0.0, 0.0),
                            ease: 0.15
                        };

                        plane.userData.index = i;
                        plane.userData.hover = 0;
                        plane.userData.id = project.dataset.id;
                        plane.userData.scene = this.scene;
                        plane.userData.camera = this.camera;

                        return plane;
                    }
                    onMouseMove(e) {

                        // if (this.clicked) return;

                        // NDC
                        this.mouseNDC.x = (e.clientX / window.innerWidth) * 2 - 1;
                        this.mouseNDC.y = -(e.clientY / window.innerHeight) * 2 + 1;

                        // Raycast
                        this.raycaster.setFromCamera(this.mouseNDC, this.camera);

                        // Z=0 plane ile kesişim noktası (grid düzlemi)
                        const planeZ = new THREE.Plane(new THREE.Vector3(0, 0, 1), 0);
                        const intersectPoint = new THREE.Vector3();

                        this.raycaster.ray.intersectPlane(planeZ, intersectPoint);

                        // WORLD SPACE mouse
                        this.mouse.target.x = intersectPoint.x;
                        this.mouse.target.y = intersectPoint.y;

                        // 🧠 GRID ALANI KONTROLÜ (gap alanları dahil)
                        const mouseVec3 = new THREE.Vector3(intersectPoint.x, intersectPoint.y, 0);

                        if (this.gridBounds.containsPoint(mouseVec3)) {
                            this.gridHoverTarget = 1;
                        } else {
                            this.gridHoverTarget = 0;
                        }
                    }
                    gridNav(e) {

                        if (!this.zoomed) return;
                        if (!this.activePlane) return;

                        let dir = e.target.classList.contains('grid--item--next') ? 'next' : e.target.classList.contains('grid--item--prev') ? 'prev' : '',
                            activeID = this.activePlane.id,
                            targetID = dir === 'next' ? activeID + 1 : activeID - 1,
                            targetPlane = this.scene.children.filter(el => el.id == targetID);


                        if (targetPlane) {
                            this.hoveredPlane = targetPlane[0];
                            this.onClick();
                        }

                    }
                    gridZoom() {

                        if (this.zoomed) {

                            this.clicked = false;
                            this.zoomed = false;
                            this.gridHover = 0.0;
                            this.gridHoverTarget = 0.0;

                            grid.classList.remove('grid--state--zoomed');
                            scope.querySelector('.pe--webgl--project.active') && scope.querySelector('.pe--webgl--project.active').classList.remove('active');

                            gsap.to(this.camera.position, {
                                z: window.innerHeight / (1.8 * Math.tan((this.camera.fov * Math.PI) / 360)),
                                x: 0,
                                y: 0,
                                duration: 0.75,
                                ease: 'power3.inOut',
                                onComplete: () => {

                                }
                            });

                            gsap.to(this, {
                                gridGapScale: 1,
                                duration: .75,
                                ease: 'power3.inOut'
                            });

                            this.scene.children.forEach((p) => {

                                gsap.to(p.material.uniforms.uClick, {
                                    value: 1.0,
                                    duration: .75,
                                    ease: 'power3.inOut'
                                });

                            });

                        } else {
                            const planes = this.scene.children;
                            const centerIndex = Math.floor((planes.length - 1) / 2);

                            this.hoveredPlane = planes[centerIndex];
                            this.onClick();
                        }

                    }
                    onClick(e) {
                        if (!this.hoveredPlane) return;
                        // if (this.clicked) return;

                        this.scene.children.forEach((p) => {
                            p.material.uniforms.uClick.value = 0.0;
                        });

                        this.clicked = true;
                        this.zoomed = true;

                        grid.classList.add('grid--state--zoomed');

                        const plane = this.hoveredPlane;
                        this.activePlane = plane;

                        const baseX = plane.userData.baseX;
                        const baseY = plane.userData.baseY;

                        const scale = 1.4;

                        const targetX = baseX * scale;
                        const targetY = baseY * scale;


                        scope.querySelector('.pe--webgl--project.active') && scope.querySelector('.pe--webgl--project.active').classList.remove('active');
                        scope.querySelector('.webgl--project--' + plane.userData.id).classList.add('active');

                        // kamera zoom mesafesi
                        const zoomZ = window.innerHeight / (5 * Math.tan((this.camera.fov * Math.PI) / 360));

                        // kamera animasyonu
                        gsap.to(this.camera.position, {
                            x: targetX,
                            y: targetY,
                            z: zoomZ,
                            duration: 0.75,
                            ease: 'power3.inOut'
                        });

                        this.scene.children.forEach((p) => {
                            gsap.to(p.material.uniforms.uGridHover, {
                                value: 0.0,
                                duration: .75,
                                ease: 'power3.inOut'
                            });

                            gsap.to(p.material.uniforms.uHover, {
                                value: 1.0,
                                duration: .75,
                                ease: 'power3.inOut'
                            });
                        });

                        gsap.to(this, {
                            gridGapScale: 1.4,   // boşluk büyüme oranı
                            duration: .75,
                            ease: 'power3.inOut'
                        });

                    }
                    updateHover() {
                        this.raycaster.setFromCamera(this.mouseNDC, this.camera);
                        const intersects = this.raycaster.intersectObjects(this.scene.children);

                        if (intersects.length) {

                            const plane = intersects[0].object;

                            if (this.hoveredPlane !== plane) {
                                if (this.hoveredPlane) {
                                    this.hoveredPlane.userData.hover = 0;
                                }
                                this.hoveredPlane = plane;
                                plane.userData.hover = 1;
                            }

                            // Plane local UV
                            const uv = intersects[0].uv;
                            plane.userData.mouse.target.copy(uv);

                        } else {
                            if (this.hoveredPlane) {
                                this.hoveredPlane.userData.hover = 0;
                                this.hoveredPlane = null;
                            }

                        }

                        this.scene.children.forEach((plane) => {

                            plane.userData.mouse.current.lerp(
                                plane.userData.mouse.target,
                                plane.userData.mouse.ease
                            );

                            plane.material.uniforms.uMouse.value.copy(
                                plane.userData.mouse.current
                            );

                            plane.material.uniforms.uHover.value = lerp(
                                plane.material.uniforms.uHover.value,
                                plane.userData.hover,
                                0.1
                            );

                        });
                    }
                    updatePointerFromEvent(clientX, clientY) {

                        // NDC
                        this.mouseNDC.x = (clientX / window.innerWidth) * 2 - 1;
                        this.mouseNDC.y = -(clientY / window.innerHeight) * 2 + 1;

                        // Raycaster
                        this.raycaster.setFromCamera(this.mouseNDC, this.camera);

                        const intersects = this.raycaster.intersectObjects(this.scene.children);

                        if (intersects.length) {
                            this.hoveredPlane = intersects[0].object;
                        } else {
                            this.hoveredPlane = null;
                        }
                    }
                    // 🔹 NEW – TOUCH
                    onTouchStart(e) {

                        const touch = e.touches[0];

                        this.updatePointerFromEvent(touch.clientX, touch.clientY);

                        if (this.hoveredPlane) {
                            this.onClick();
                        }
                    }

                    onTouchMove(e) {
                        if (!this.touch.isDown) return;

                        const x = e.touches[0].clientX;
                        const y = e.touches[0].clientY;

                        const deltaX = this.touch.lastX - x;
                        const deltaY = this.touch.lastY - y;

                        // yatay + dikey swipe → tek davranış
                        const delta = Math.abs(deltaX) > Math.abs(deltaY) ? deltaX : deltaY;

                        this.touch.lastX = x;
                        this.touch.lastY = y;
                    }

                    onTouchEnd() {
                        this.touch.isDown = false;
                    }

                    // 🔹 NEW
                    onResize() {
                        const width = window.innerWidth;
                        const height = window.innerHeight;

                        this.renderer.setSize(width, height);
                        this.renderer.setPixelRatio(Math.min(1.5, window.devicePixelRatio));

                        this.camera.aspect = width / height;
                        this.camera.updateProjectionMatrix();

                        this.camera.position.z =
                            height / (1.8 * Math.tan((this.camera.fov * Math.PI) / 360));

                        var style = getComputedStyle(scope),
                            planesWidth = parseInt(style.getPropertyValue('--imagesWidth').trim()),
                            planesHeight = parseInt(style.getPropertyValue('--imagesHeight').trim()),
                            planesGap = parseInt(style.getPropertyValue('--gridGap').trim()),
                            gridCols = parseInt(style.getPropertyValue('--gridCols').trim());

                        if (!planesWidth) planesWidth = 160;
                        if (!planesHeight) planesHeight = 200;
                        if (!planesGap) planesGap = 15;
                        if (!gridCols) gridCols = 8;

                    }
                    render() {

                        this.updateHover();

                        this.mouse.current.lerp(this.mouse.target, this.mouse.ease);
                        if (!this.zoomed) {
                            this.gridHover = lerp(this.gridHover, this.gridHoverTarget, 0.08);
                        } else {
                            this.gridHover = lerp(this.gridHover, 1.0, 0.04);
                        }

                        this.scene.children.forEach((plane) => {

                            plane.position.x = plane.userData.baseX * this.gridGapScale;
                            plane.position.y = plane.userData.baseY * this.gridGapScale;

                            plane.material.uniforms.uTime.value += 0.01;
                            plane.material.uniforms.uMouse.value.copy(this.mouse.current);

                            if (!this.zoomed) {
                                plane.material.uniforms.uGridHover.value = this.gridHover;
                            }

                        });

                        this.renderer.render(this.scene, this.camera);
                        requestAnimationFrame(this.render.bind(this));
                    }
                }

                new webglGrid(grid);


            }

        })


        elementorFrontend.hooks.addAction('frontend/element_ready/peshowcasetable.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {

                var scope = jsScopeArray[i],
                    showcaseTable = document.querySelectorAll('.showcase--table');

                showcaseTable.forEach(function ($this) {

                    let project = $this.querySelectorAll('.zeyna--portfolio--project');

                    function shuffleCards(delay = 0) {
                        let positions = [];
                        for (let r = 0; r < 3; r++) {
                            for (let c = 0; c < 3; c++) {
                                positions.push({
                                    top: r * $this.offsetHeight / 3,
                                    left: c * $this.offsetWidth / 3
                                });
                            }
                        }
                        positions = gsap.utils.shuffle(positions);
                        project.forEach(function (item, index) {

                            let pos = positions[index % positions.length];
                            gsap.to(item, {
                                top: index < 9 ? pos.top + gsap.utils.random(0, $this.offsetHeight / 3 - item.offsetHeight) : gsap.utils.random(0, $this.offsetHeight - item.offsetHeight),
                                left: index < 9 ? pos.left + gsap.utils.random(0, $this.offsetWidth / 3 - item.offsetWidth) : gsap.utils.random(0, $this.offsetWidth - item.offsetWidth),
                                y: gsap.utils.random(-1 * item.offsetHeight / 4, item.offsetHeight / 2),
                                x: gsap.utils.random(item.offsetWidth / -2, item.offsetWidth),
                                rotate: gsap.utils.random(-30, 30),
                                delay: gsap.utils.random(delay, delay + 0.5),
                                ease: 'expo.out',
                                duration: 1.75
                            })

                        });
                    }



                    project.forEach(function (item, index) {

                        var itemsDrag = Draggable.create(item, {
                            type: 'x, y',
                            edgeResistance: 0.75,
                            id: 'dragger_item_' + i,
                            bounds: {
                                top: -item.offsetHeight / 2,
                                left: -item.offsetWidth / 2,
                                width: $this.offsetWidth * (item.offsetWidth * 1.5),
                                height: $this.offsetHeight * (item.offsetHeight * 1.5)
                            },
                            dragResistance: 0.35,
                            inertia: true,
                            zIndexBoost: true,
                            allowContextMenu: true,
                            alllowEventDefault: true,
                            onPress: () => {
                                item.classList.add('dragging')
                            },
                            onRelease: () => {
                                item.classList.remove('dragging')
                            }
                        })

                    });

                    $this.querySelector('.shuffle--button').addEventListener('click', function () {
                        shuffleCards(0);
                    })

                    if (gsap.getById('pageLoader')) {
                        document.addEventListener('pageLoaderDone', function () {

                            shuffleCards(0.75);


                        })
                    } else {
                        shuffleCards(0.75);
                    }



                })


            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/peshowcase3dcarousel.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {

                var scope = jsScopeArray[i],
                    showcase3dCarousel = scope.querySelectorAll('.showcase--3d--carousel');

                let horizontal = false,
                    lenisDirection = 'vertical';
                matchMedia.add({
                    isMobile: "(max-width: 550px)"

                }, (context) => {

                    let {
                        isMobile
                    } = context.conditions;

                    horizontal = true
                    lenisDirection = 'horizontal'
                });

                showcase3dCarousel.forEach(function ($this) {
                    let project = $this.querySelectorAll('.project--container'),
                        spacing = parseInt(getComputedStyle($this.querySelector('.showcase--3d--carousel--wrapper')).getPropertyValue('--spacing')),
                        isInfinite = scope.classList.contains('infinite__active'),
                        length = project.length

                    project.forEach(function ($item, i) {

                        $item.setAttribute('data-transformZ', (i * spacing))
                        $item.style.zIndex = 100 - i;
                        $item.style.setProperty('--transformZ', (i * spacing) + 'px');

                    })
                    let pinTarget,
                        trigger;

                    if ($this.dataset.pinTarget) {
                        pinTarget = $this.dataset.pinTarget
                        trigger = pinTarget
                    } else {
                        pinTarget = true
                        trigger = $this
                    }

                    let scrollTimeout,
                        sct = new ScrollTrigger.create({
                            horizontal: horizontal,
                            trigger: trigger,
                            start: 'center center',
                            end: "center+=" + $this.dataset.speed + "top",
                            scrub: true,
                            pin: pinTarget,
                            onUpdate: function (self) {
                                $this.style.setProperty('--progress', (self.progress * (spacing * ((length - $this.querySelectorAll('.clone--container').length))) + 'px'));
                                if (!$this.classList.contains('is__scrolling')) {
                                    $this.classList.add('is__scrolling');
                                }
                                clearTimeout(scrollTimeout);
                                scrollTimeout = setTimeout(() => {
                                    $this.classList.remove('is__scrolling');
                                }, 100);

                            }
                        });

                    if (scope.classList.contains('infinite__active')) {

                        if (zeynaLenis) {
                            zeynaLenis.options.infinite = true;
                            zeynaLenis.options.orientation = lenisDirection;


                            if (window.barba) {
                                barba.hooks.before(() => {
                                    zeynaLenis.options.infinite = false;


                                });
                            }
                        } else {
                            const lenis = new Lenis({
                                infinite: true,
                                smooth: true,
                                smoothTouch: true,
                                orientation: lenisDirection,

                            });

                            // OPTIMIZED: GSAP ticker kullan
                            gsap.ticker.add((time) => {
                                lenis.raf(time * 1000);
                            });

                            if (window.barba) {
                                barba.hooks.before(() => {
                                    lenis.destroy();
                                    gsap.ticker.remove(lenis.raf);
                                });
                            }

                        }

                    }

                })


            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/peshowcaserotate.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {

                var scope = jsScopeArray[i],
                    showcaseRotate = document.querySelectorAll('.showcase--rotate');

                function triggerMouseWheel(deltaY) {
                    const event = new WheelEvent('wheel', {
                        deltaY: deltaY,
                        duration: 2
                    });
                    window.dispatchEvent(event);
                }

                showcaseRotate.forEach(function ($this) {
                    let wrapper = $this.querySelector('.showcase--rotate--wrapper'),
                        project = $this.querySelectorAll('.zeyna--portfolio--project'),
                        navigation = $this.querySelector('.showcase--rotate--navigation'),
                        navItem = navigation.querySelectorAll('.navigation--item'),
                        itemFirts = navigation.querySelector('.item--first'),
                        itemLast = navigation.querySelector('.item--last'),
                        navBg = navigation.querySelector('.active--item--bg'),
                        speed = parseInt($this.dataset.speed),
                        pinTarget = $this.dataset.pinTarget,
                        trigger = pinTarget

                    if (!pinTarget) {
                        pinTarget = true
                        trigger = $this
                    }

                    navBg.style.width = itemFirts.offsetWidth + 'px';
                    navBg.style.height = itemFirts.offsetHeight + 'px';
                    navBg.style.left = itemFirts.getBoundingClientRect().left - navigation.getBoundingClientRect().left + 'px';

                    function navBgAnimate(item) {
                        gsap.to(navBg, {
                            width: item.offsetWidth,
                            left: item.getBoundingClientRect().left - navigation.getBoundingClientRect().left
                        })
                        navItem.forEach(function ($item) {
                            $item.classList.remove('nav--item--active')
                        })
                        item.classList.add('nav--item--active')
                    }

                    function zoomAnimate(zoomIn) {
                        gsap.to(wrapper, {
                            scale: zoomIn ? 1 : 0.33333,
                            x: zoomIn ? $this.offsetWidth * -2 + (project[0].querySelector('.portfolio--project--wrapper').offsetWidth * 0.5) : wrapper.offsetWidth * -0.33333,
                        })
                    }

                    navItem.forEach(function ($navItem) {
                        let item = $navItem
                        $navItem.addEventListener('click', function () {
                            navBgAnimate($navItem);
                            zoomAnimate($navItem.classList.contains('item--first') ? false : true)
                            if ($navItem.classList.contains('item--last')) {
                                gsap.to(window, {
                                    scrollTo: Math.round(window.scrollY / (speed / project.length)) * (speed / project.length),
                                })
                                let activeIndex = (Math.round(window.scrollY / (speed / project.length)) * (speed / project.length)) / (speed / project.length)
                                project[activeIndex].classList.add('active')
                                if ($this.classList.contains('zoom--out')) {
                                    $this.classList.remove('zoom--out')
                                    $this.classList.add('zoom--in')
                                }
                            }

                            if ($navItem.classList.contains('item--first')) {
                                project.forEach(function ($project) {
                                    $project.classList.remove('active')
                                })
                                if ($this.classList.contains('zoom--in')) {
                                    $this.classList.add('zoom--out')
                                    $this.classList.remove('zoom--in')
                                }
                            }
                        })
                    })

                    project.forEach(function ($item, index) {
                        if (scope.classList.contains('intro__on')) {
                            gsap.to($item, {
                                '--rotate': -360 / project.length * index + 'deg',
                                duration: 1.2,
                                ease: 'expo.inOut'
                            })
                        } else {
                            $item.style.setProperty('--rotate', -360 / project.length * index + 'deg')
                        }

                    })
                    let activeIndex = 0;
                    let tl = gsap.timeline({
                        ease: 'none',
                        scrollTrigger: {
                            trigger: trigger,
                            start: 'top top',
                            end: 'top+=' + speed + ' top',
                            pin: pinTarget,
                            scrub: true,
                            onUpdate: (self) => {
                                let prog = self.progress * 360;
                                activeIndex = Math.round(self.progress * (project.length))
                                activeIndex = activeIndex % project.length

                                if ($this.classList.contains('zoom--in')) {


                                    clearTimeout(scroll.timer)
                                    scroll.timer = setTimeout(function () {
                                        gsap.to(window, {
                                            scrollTo: Math.round(window.scrollY / (speed / project.length)) * (speed / project.length),
                                        })
                                    }, 100)

                                    project.forEach(function ($project, i) {
                                        if (i === activeIndex) {
                                            $project.classList.add('active')
                                        } else {
                                            $project.classList.remove('active')
                                        }
                                    })
                                }

                                $this.style.transition = 'none';
                                $this.style.setProperty('--progress', prog + 'deg')
                            }
                        }
                    })

                    infinitePage();

                    project.forEach(function ($item, i) {
                        $item.addEventListener('click', function () {
                            if ($this.classList.contains('zoom--out')) {
                                $this.classList.remove('zoom--out')
                                $this.classList.add('zoom--in')
                            }
                            let currentRotation = parseInt(getComputedStyle(this).getPropertyValue('--rotate')) + ((window.scrollY * 360) / 5000),
                                target = (5000 / project.length) * i,
                                current = window.scrollY,
                                triggerSize = target - current

                            if (triggerSize >= 2500) {
                                triggerSize = triggerSize - 5000
                            } else if (triggerSize <= -2500) {
                                triggerSize = triggerSize + 5000
                            }
                            triggerMouseWheel(triggerSize);

                            zoomAnimate(true);
                            navBgAnimate($this.querySelector('.item--last'));
                            project.forEach(function ($project) {
                                $project.classList.remove('active')
                            })
                            this.classList.add('active')

                        })
                    })
                })
            }
        });






        elementorFrontend.hooks.addAction('frontend/element_ready/pecategorieslist.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    categoriesList = document.querySelectorAll('.categories--list');
                categoriesList.forEach(function ($this) {
                    let category = $this.querySelectorAll('.product--category'),
                        image = $this.querySelectorAll('.category--image'),
                        pinTarget = $this.dataset.pinTarget,
                        trigger = pinTarget

                    if (!pinTarget) {
                        pinTarget = true
                        trigger = $this
                    }



                    category.forEach(function ($cat, i) {
                        let rotate = -i * (120 / category.length);
                        $cat.style.setProperty('--rotate', rotate + "deg");
                        $cat.setAttribute('data-rotate', rotate + "deg");

                        if (rotate < -60 || rotate > 60) {
                            gsap.set($cat, { opacity: 0 });
                        }

                        $cat.addEventListener('mouseenter', function () {
                            gsap.to($this.querySelector('.images--wrapper'), { opacity: 1 });
                        });

                        $cat.addEventListener('mouseleave', function () {
                            gsap.to($this.querySelector('.images--wrapper'), { opacity: 0 });
                        });
                    });

                    let mm = gsap.matchMedia();

                    if ($this.classList.contains('cursor__image')) {

                        $this.addEventListener("mousemove", (e) => {
                            gsap.to($this.querySelector('.images--wrapper'), {
                                x: e.clientX - ((document.body.offsetWidth - $this.offsetWidth) / 2) - ($this.querySelector('.images--wrapper').offsetWidth / 2),
                                y: e.clientY - ($this.querySelector('.images--wrapper').offsetHeight / 2)
                            })
                        });

                    }

                    gsap.to($this.querySelector('.categories--wrapper'), {
                        rotateX: 120 - (120 / category.length),
                        ease: 'none',
                        scrollTrigger: {
                            trigger: trigger,
                            start: 'top top',
                            end: 'bottom+=' + $this.getAttribute('data-speed') + 'bottom',
                            scrub: 1,
                            pin: pinTarget,
                            onUpdate: (self) => {
                                let activeIndex = Math.floor(self.progress * (category.length - 1)),
                                    prog = Math.floor(self.progress * 120);

                                category.forEach(function ($cat) {
                                    let rotate = parseInt($cat.getAttribute('data-rotate')) + prog;
                                    if (rotate <= -60 || rotate >= 60) {
                                        gsap.set($cat, { opacity: 0 });
                                    } else {
                                        if ($cat.classList.contains('category__' + activeIndex)) {
                                            gsap.set($cat, { opacity: 1 });
                                            $cat.classList.add('category--active');
                                        } else {
                                            gsap.set($cat, { opacity: 0.2 });
                                            $cat.classList.remove('category--active');
                                        }
                                    }
                                });

                                image.forEach(function ($image) {
                                    if ($image.classList.contains('image__' + activeIndex)) {
                                        gsap.to($image, { opacity: 1 });
                                    } else {
                                        gsap.to($image, { opacity: 0 });
                                    }
                                });
                            }
                        }
                    });


                    mm.add("(max-width: 570px)", () => {

                        let draggableInstance = Draggable.create($this, {
                            type: 'y',
                            inertia: true,
                            onDrag: function () {
                                let rotationX = Math.min(Math.max(this.y / -7.5, 0), 180);
                                if (this.y >= 0) {
                                    this.y = 0
                                } else if (this.y <= -1350) {
                                    this.y = -1350
                                }
                                gsap.set($this, {
                                    y: this.y
                                })


                                gsap.set($this.querySelector('.categories--wrapper'), {
                                    rotateX: rotationX,
                                });

                                updateCategories(rotationX);
                            },
                            onThrowUpdate: function () {
                                let rotationX = Math.min(Math.max(this.y / -7.5, 0), 180);
                                if (this.y >= 0) {
                                    this.y = 0
                                } else if (this.y <= -1350) {
                                    this.y = -1350
                                }
                                gsap.set($this, {
                                    y: this.y
                                })

                                gsap.set($this.querySelector('.categories--wrapper'), {
                                    rotateX: rotationX,
                                });

                                updateCategories(rotationX);
                            }
                        });

                        function updateCategories(rotationX) {
                            let activeIndex = Math.floor(rotationX / 180 * (category.length - 1));

                            category.forEach(function ($cat) {

                                let rotate = parseInt($cat.getAttribute('data-rotate')) + rotationX;
                                if (rotate <= -90 || rotate >= 90) {
                                    gsap.set($cat, { opacity: 0 });
                                } else {
                                    if ($cat.classList.contains('category__' + activeIndex)) {
                                        gsap.set($cat, { opacity: 1 });
                                        $cat.classList.add('category--active');
                                    } else {
                                        gsap.set($cat, { opacity: 0.2 });
                                        $cat.classList.remove('category--active');
                                    }
                                }
                            });

                            image.forEach(function ($image) {
                                if ($image.classList.contains('image__' + activeIndex)) {
                                    gsap.to($image, { opacity: 1 });
                                } else {
                                    gsap.to($image, { opacity: 0 });
                                }
                            });
                        }
                    });
                });





            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/pesocialshare.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i];

                const shareButtons = scope.querySelectorAll('.social-share-button');

                shareButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const url = button.getAttribute('data-url');
                        const network = button.getAttribute('data-network');

                        const width = 600;
                        const height = 400;
                        const left = (screen.width / 2) - (width / 2);
                        const top = (screen.height / 2) - (height / 2);

                        window.open(
                            url,
                            `${network} Share`,
                            `toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=${width}, height=${height}, top=${top}, left=${left}`
                        );
                    });
                });



            }

        })

        elementorFrontend.hooks.addAction('frontend/element_ready/peshowcaserounded.default', function ($scope, $) {

            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {

                var scope = jsScopeArray[i],
                    main = document.querySelectorAll('.showcase--rounded');

                main.forEach(function ($this) {
                    let item = $this.querySelectorAll('.project--container'),
                        wrapper = $this.querySelector('.showcase--rounded--wrapper'),
                        computedStyle = window.getComputedStyle($this),
                        totalRotate = -1 * parseInt(computedStyle.getPropertyValue('--totalRotate'))


                    item.forEach(function ($item, i) {
                        let itemIndex = i - Math.floor(item.length / 2)

                        $item.setAttribute('data-width', $item.offsetWidth)
                        $item.setAttribute('data-height', $item.offsetHeight)
                        $item.setAttribute('data-index', i)

                        if (item.length % 2 === 0) {
                            $item.style.setProperty('--rotateX', (totalRotate / item.length * itemIndex) + (totalRotate / item.length / 2) + 'deg')
                        } else {
                            $item.style.setProperty('--rotateX', totalRotate / item.length * itemIndex + 'deg')
                        }

                        gsap.set($item, {
                            xPercent: -50,
                            yPercent: -50,
                            force3D: true,
                            // matrix: true
                        })
                        let transform = $item.style.transform
                        $item.setAttribute('data-transform', transform)

                        $item.addEventListener('click', function () {
                            if (i === 0) {
                                $this.classList.add('first__project__active')
                            } else if (i >= item.length - 1) {
                                $this.classList.add('last__project__active')
                            }
                            gsap.to(wrapper, {
                                x: 0,
                                duration: 1.2,
                                ease: 'expo.inOut',
                                onComplete: () => {
                                    $this.classList.add('zoomed__active')
                                }
                            })

                            this.classList.add('showcase--project--active');

                            gsap.to(this, {
                                transform: 'none',
                                duration: 1.2,
                                ease: 'expo.inOut',
                                force3D: true,
                            })

                            gsap.to(this, {
                                width: $this.offsetWidth / 2,
                                height: $this.offsetHeight / 2,
                                duration: 1.2,
                                ease: 'expo.inOut',
                                xPercent: -50,
                                yPercent: -50,
                                force3D: true,
                                onComplete: () => {
                                    $this.classList.add('is__active')
                                }

                            })

                            item.forEach(function ($product, e) {



                                if (e < i) {
                                    gsap.to($product, {
                                        transform: 'none',
                                        duration: 1.2,
                                        ease: 'expo.inOut',
                                        force3D: true
                                    })

                                    gsap.to($product, {

                                        x: '-150vw',
                                        duration: 1.2,
                                        ease: 'expo.inOut',
                                        force3D: true,
                                    })
                                } else if (i < e) {

                                    gsap.to($product, {
                                        transform: 'none',
                                        duration: 1.2,
                                        ease: 'expo.inOut',
                                        force3D: true
                                    })

                                    gsap.to($product, {
                                        x: '150vw',
                                        duration: 1.2,
                                        ease: 'expo.inOut',
                                        force3D: true,
                                    })
                                }

                            })
                        })

                        let computedStyle = window.getComputedStyle($item);

                        let transformMatrix = computedStyle.getPropertyValue('transform');

                        $item.setAttribute('data-transform', transformMatrix)

                    })

                    function closeProject() {
                        $this.classList.remove('first__project__active')
                        $this.classList.remove('last__project__active')
                        $this.classList.remove('is__active')

                        item.forEach(function ($item, i) {
                            let computedStyle = window.getComputedStyle($item),
                                transformMatrix = computedStyle.getPropertyValue('transform');
                            $item.classList.remove('showcase--project--active')
                            $item.style.transform = 'translate(-50%, -50%) '

                            gsap.to($item, {
                                transform: 'none',
                                duration: 1.2,
                                ease: 'expo.inOut',
                                force3D: true,
                                onComplete: () => {
                                    $this.classList.remove('zoomed__active')
                                }
                            })

                            gsap.to($item, {
                                width: $item.dataset.width,
                                height: $item.dataset.height,
                                transform: $item.getAttribute('data-transform'),
                                duration: 1.2,
                                ease: 'expo.inOut',
                            })
                        })
                    }

                    $this.querySelector('.close--button').addEventListener('click', closeProject)

                    document.addEventListener('keydown', function (e) {
                        if (e.key === 'Escape' || e.key === 'Esc') {
                            closeProject()
                        }
                    })

                    $this.querySelector('.nav--next').addEventListener('click', function () {
                        let activeEl = $this.querySelector('.showcase--project--active')

                        if (parseInt(activeEl.dataset.index) + 1 >= item.length - 1) {
                            $this.classList.add('last__project__active')
                        } else (
                            $this.classList.remove('first__project__active')
                        )
                        gsap.to($this.querySelector('.showcase--project--active'), {
                            x: '-150vw',
                            transform: $this.querySelector('.showcase--project--active').dataset.transform,
                            width: $this.offsetWidth / 2,
                            height: $this.offsetHeight / 2,
                            duration: 1.2,
                            ease: 'expo.inOut'
                        })
                        gsap.to($this.querySelector('.showcase--project--active').nextElementSibling, {
                            transform: 'none',
                            duration: 1.2,
                            ease: 'expo.inOut',
                            force3D: true
                        })

                        gsap.to($this.querySelector('.showcase--project--active').nextElementSibling, {
                            width: $this.offsetWidth / 2,
                            height: $this.offsetHeight / 2,
                            duration: 1.2,
                            ease: 'expo.inOut',
                            xPercent: -50,
                            yPercent: -50,
                            force3D: true,
                            // matrix: true,
                        })

                        activeEl.nextElementSibling.classList.add('showcase--project--active')
                        activeEl.classList.remove('showcase--project--active')
                    })

                    $this.querySelector('.nav--prev').addEventListener('click', function () {

                        let activeEl = $this.querySelector('.showcase--project--active');


                        if (parseInt(activeEl.dataset.index) - 1 === 0) {
                            $this.classList.add('first__project__active')
                        } else {
                            $this.classList.remove('last__project__active')

                        }
                        if (!activeEl) return;

                        let prevEl = activeEl.previousElementSibling;
                        if (!prevEl) return;

                        activeEl.classList.remove('showcase--project--active');
                        prevEl.classList.add('showcase--project--active');

                        gsap.to(activeEl, {
                            x: '150vw',
                            transform: activeEl.dataset.transform,
                            width: activeEl.dataset.width,
                            height: activeEl.dataset.height,
                            duration: 1.2,
                            ease: 'expo.inOut'
                        });

                        gsap.to(prevEl, {
                            transform: 'none',
                            duration: 1.2,
                            ease: 'expo.inOut',
                            force3D: true
                        });

                        gsap.to(prevEl, {
                            width: $this.offsetWidth / 2,
                            height: $this.offsetHeight / 2,
                            duration: 1.2,
                            ease: 'expo.inOut',
                            xPercent: -50,
                            yPercent: -50,
                            force3D: true
                        });
                    })

                    matchMedia.add({
                        isMobile: "(max-width: 550px)"

                    }, (context) => {

                        let {
                            isMobile
                        } = context.conditions;



                        let drag = Draggable.create(wrapper, {
                            type: 'x',
                            bounds: {
                                minX: window.innerWidth * -1.5,
                                maxX: window.innerWidth * 1.5,
                            },
                            inertia: true
                        })

                    });


                })


            }
        });



        elementorFrontend.hooks.addAction('frontend/element_ready/peshowcaseverticalslider.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    verticalSlider = document.querySelectorAll('.showcase--vertical--slider');
                verticalSlider.forEach(function ($this) {
                    let scrollWrap = $this.querySelector('.showcase--vertical--scroll--cards'),
                        productWrap = $this.querySelector('.showcase--vertical--products--wrapper'),
                        scrollImage = $this.querySelectorAll('.scroll--card--image'),
                        pinTarget = $this.getAttribute('data-pin-target'),
                        trigger = pinTarget

                    if (!pinTarget) {
                        pinTarget = true
                        trigger = $this
                    }

                    matchMedia.add({
                        isDesktop: "(min-width: 570px)"

                    }, (context) => {

                        let {
                            isDesktop
                        } = context.conditions;
                        scrollImage.forEach(function ($zoomImg, i) {
                            let scroll = parseInt($this.getAttribute('data-speed')) / (scrollImage.length - 1)

                            $zoomImg.addEventListener('click', function () {
                                gsap.to(window, {
                                    scrollTo: scroll * i,
                                    duration: 1.6,
                                    ease: 'power4.inOut',

                                })
                            })
                        })

                        gsap.getById(scope.dataset.id) ? gsap.getById(scope.dataset.id).scrollTrigger.kill(true) : '';

                        let tl = gsap.timeline({
                            ease: 'none',
                            id: scope.dataset.id,
                            scrollTrigger: {
                                trigger: trigger,
                                start: 'top top',
                                end: 'bottom+=' + $this.getAttribute('data-speed') + ' bottom',
                                pin: mobileQuery.matches ? false : pinTarget,
                                scrub: 1,
                                onUpdate: (self) => {

                                    if (scope.classList.contains('parallax__on')) {

                                        let prog = self.progress * 20
                                        $this.querySelectorAll('.parallax--wrap').forEach(function ($prlx) {
                                            gsap.to($prlx, {
                                                yPercent: (-1 * prog)
                                            })
                                        })
                                    }

                                },

                            }
                        })

                        tl.to(scrollWrap, {
                            y: -1 * scrollWrap.offsetHeight + scrollWrap.querySelectorAll('.scroll--card--image')[0].offsetHeight,
                            ease: 'none',
                        }, 0)

                        tl.to(productWrap, {
                            y: -1 * productWrap.offsetHeight + productWrap.querySelectorAll('.showcase--product')[0].offsetHeight,
                            ease: 'none'
                        }, 0)

                    });



                })





            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/peshowcasecarouselold.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {

                var scope = jsScopeArray[i],
                    showcaseCarousel = document.querySelectorAll('.showcase--carousel');

                showcaseCarousel.forEach(function ($this) {
                    let wrap = $this.querySelector('.showcase--carousel--wrapper'),
                        project = $this.querySelectorAll('.showcase--product'),
                        wrapperWidth = wrap.offsetWidth + parseInt(window.getComputedStyle(wrap).gap)
                    if (scope.classList.contains('autoplay__active')) {
                        project.forEach(function ($item) {
                            let clone = $item.cloneNode(true)
                            wrap.appendChild(clone)
                        })
                        project = $this.querySelectorAll('.showcase--product')
                        let tl = gsap.timeline({
                            repeat: -1
                        })
                        tl.to(wrap, {
                            x: -1 * wrapperWidth,
                            ease: 'none',
                            duration: parseInt($this.getAttribute('data-autoplay-duration'))
                        }, 0)

                        if (scope.classList.contains('speed__on__autoplay')) {
                            let whaler = Hamster(document.querySelector('body')),
                                wheelDeltaY, currentDeltaY;

                            whaler.wheel(function (event, delta, deltaX, deltaY) {

                                wheelDeltaY = event.deltaY;
                                event.deltaY < 0 ? wheelDeltaY = -1 * event.deltaY : '';
                                tl.timeScale(1 + (wheelDeltaY * 2))

                            });

                        }

                        if (scope.classList.contains('parallax__on')) {
                            $this.querySelectorAll('.parallax--wrapper').forEach(function ($prlx) {
                                $prlx.style.width = '125%'
                            })
                        }

                        project.forEach(function ($item) {
                            $item.addEventListener('mouseenter', function () {
                                tl.pause()
                            })
                            $item.addEventListener('mouseleave', function () {
                                tl.play()
                                tl.timeScale(1)
                            })
                        })
                    } else if (scope.classList.contains('infinite__scroll')) {
                        project.forEach(function ($item) {
                            let clone = $item.cloneNode(true)
                            wrap.appendChild(clone)
                        })
                        let pinTarget = $this.dataset.pinTarget,
                            trigger = $this.dataset.pinTarget;

                        if (!pinTarget) {
                            pinTarget = true;
                        }

                        if (!trigger) {
                            trigger = $this
                        }

                        project = $this.querySelectorAll('.showcase--product')

                        let sct = ScrollTrigger.create({
                            trigger: trigger,
                            start: 'top top',
                            end: 'bottom+=' + $this.getAttribute('data-speed') + ' bottom',
                            scrub: true,
                            pin: pinTarget,
                            onUpdate: (self) => {
                                let prog = self.progress * wrapperWidth * -1
                                gsap.set(wrap, {
                                    x: prog
                                })
                            }
                        })

                    } else if (scope.classList.contains('navigate__draggable')) {
                        gsap.set(wrap, {
                            x: ($this.offsetWidth / 2) - (project[0].offsetWidth / 2)
                        })
                        Draggable.create(wrap, {
                            type: 'x',
                            inertia: true,
                            bounds: {
                                minX: (-1 * wrapperWidth) + (($this.offsetWidth / 2) + (project[0].offsetWidth / 2)),
                                maxX: ($this.offsetWidth / 2) - (project[0].offsetWidth / 2)
                            },
                            onDrag: function () {
                                if (scope.classList.contains('parallax__on')) {
                                    let prog = (this.x - (($this.offsetWidth / 2) - (project[0].offsetWidth / 2))) / ((-1 * wrapperWidth) + (($this.offsetWidth / 2) + (project[0].offsetWidth / 2)) - ($this.offsetWidth / 2) - (project[0].offsetWidth / 2))
                                    $this.querySelectorAll('.parallax--wrapper').forEach(function ($prlx) {
                                        gsap.set($prlx, {
                                            x: -prog * 20 + '%'
                                        })
                                    })
                                }
                            },
                            onThrowUpdate: function () {
                                if (scope.classList.contains('parallax__on')) {
                                    let prog = (this.x - (($this.offsetWidth / 2) - (project[0].offsetWidth / 2))) / ((-1 * wrapperWidth) + (($this.offsetWidth / 2) + (project[0].offsetWidth / 2)) - ($this.offsetWidth / 2) - (project[0].offsetWidth / 2))
                                    $this.querySelectorAll('.parallax--wrapper').forEach(function ($prlx) {
                                        gsap.set($prlx, {
                                            x: -prog * 20 + '%'
                                        })
                                    })
                                }
                            }
                        });

                    } else if (scope.classList.contains('navigate__scroll')) {
                        let pinTarget = $this.dataset.pinTarget,
                            trigger = $this.dataset.pinTarget;

                        if (!pinTarget) {
                            pinTarget = true;
                        }

                        if (!trigger) {
                            trigger = $this
                        }

                        ScrollTrigger.getById(scope.dataset.id) ? ScrollTrigger.getById(scope.dataset.id).kill(true) : '';

                        let tl = gsap.timeline({
                            id: wrap.dataset.id ? wrap.dataset.id : scope.dataset.id,
                            scrollTrigger: {
                                trigger: trigger,
                                id: scope.dataset.id,
                                start: 'top top',
                                end: 'bottom+=' + $this.getAttribute('data-speed') + ' bottom',
                                pin: pinTarget,
                                scrub: true,
                            }
                        });

                        tl.fromTo(wrap, {
                            x: ($this.offsetWidth / 2) - (project[0].offsetWidth / 2)
                        }, {
                            x: (-1 * wrapperWidth) + (($this.offsetWidth / 2) + (project[0].offsetWidth / 2)),
                        }, 0)
                        if (scope.classList.contains('parallax__on')) {
                            $this.querySelectorAll('.parallax--wrapper').forEach(function ($prlx) {
                                tl.to($prlx, {
                                    x: '-20%'
                                }, 0)
                            })
                        }
                    }
                    // let infinite = false
                    // if (scope.classList.contains('infinite__scroll')) {
                    //     infinite = true
                    // }

                    // const lenis = new Lenis({
                    //     infinite: infinite,
                    // });

                    // function onRaf(time) {
                    //     lenis.raf(time);
                    //     requestAnimationFrame(onRaf);
                    // }

                    // requestAnimationFrame(onRaf)

                })

            }
        });


        elementorFrontend.hooks.addAction('frontend/element_ready/peshowcase3d.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {

                var scope = jsScopeArray[i],
                    showcase3d = document.querySelectorAll('.showcase--3d');

                let horizontal = false,
                    lenisDirection = 'vertical';
                matchMedia.add({
                    isMobile: "(max-width: 550px)"

                }, (context) => {

                    let {
                        isMobile
                    } = context.conditions;

                    horizontal = true
                    lenisDirection = 'horizontal'
                });


                showcase3d.forEach(function ($this) {
                    let imageWrap = $this.querySelector('.showcase--image--wrapper'),
                        image = $this.querySelectorAll('.project--container'),
                        imageInner = $this.querySelectorAll('.project--image'),
                        speed = $this.dataset.speed,
                        pinTarget = $this.dataset.pinTarget,
                        trigger = pinTarget;

                    if (!pinTarget) {
                        pinTarget = true
                        trigger = $this
                    }

                    let rot = 360 / image.length

                    image.forEach(function ($image, i) {
                        let rotate = i * rot - 180,
                            img = $image.querySelector('.project--image')
                        $image.style.setProperty('--rotate', rotate + 'deg')
                        $image.setAttribute('data-rotate', i * rot)
                    })

                    let scrollTimeout;

                    function snapToNearest(value, snapValue) {
                        return Math.round(value / snapValue) * snapValue;
                    }
                    gsap.set(imageWrap, {
                        rotateY: 0
                    })

                    ScrollTrigger.getById(scope.dataset.id) ? ScrollTrigger.getById(scope.dataset.id).kill(true) : '';

                    let sct = ScrollTrigger.create({
                        horizontal: horizontal,
                        trigger: trigger,
                        pin: pinTarget,
                        scrub: true,
                        id: scope.dataset.id,
                        start: 'top top',
                        end: 'top+=' + speed + ' top',
                        onUpdate: function (self) {

                            let prog = self.progress * -(360);
                            gsap.set(imageWrap, {
                                rotateY: prog
                            })
                            image.forEach(function ($img) {
                                $img.classList.remove('active__project')
                            })
                            clearTimeout(scrollTimeout);
                            scrollTimeout = setTimeout(() => {
                                let scrollProgress = window.scrollY

                                matchMedia.add({
                                    isMobile: "(max-width: 550px)"

                                }, (context) => {

                                    let {
                                        isMobile
                                    } = context.conditions;

                                    scrollProgress = window.scrollX

                                });

                                let snappedValue = snapToNearest(scrollProgress, speed / image.length)

                                gsap.to(window, {
                                    scrollTo: {
                                        x: snappedValue,
                                        y: snappedValue
                                    },
                                    ease: 'none'
                                });

                                image[parseInt(snappedValue / (speed / image.length))].classList.add('active__project')


                            }, 100);

                        },
                    })

                    let whaler = Hamster(document.querySelector('body')),
                        wheelTimeout;

                    whaler.wheel(function (event, delta, deltaX, deltaY) {
                        gsap.to($this, {
                            scale: 1,
                            ease: 'none'
                        })

                        clearTimeout(wheelTimeout);

                        wheelTimeout = setTimeout(function () {
                            gsap.to($this, {
                                scale: 2,
                                duration: 0.5,
                                ease: 'none'
                            })
                        }, 1000);
                    });

                })
                if (scope.classList.contains('infinite__active')) {

                    if (zeynaLenis) {
                        zeynaLenis.options.infinite = true;
                        zeynaLenis.options.orientation = lenisDirection;


                        if (window.barba) {
                            barba.hooks.before(() => {
                                zeynaLenis.options.infinite = false;


                            });
                        }
                    } else {
                        const lenis = new Lenis({
                            infinite: true,
                            smooth: true,
                            smoothTouch: true,
                            orientation: lenisDirection,

                        });

                        // OPTIMIZED: GSAP ticker kullan
                        gsap.ticker.add((time) => {
                            lenis.raf(time * 1000);
                        });

                        if (window.barba) {
                            barba.hooks.before(() => {
                                lenis.destroy();
                                gsap.ticker.remove(lenis.raf);
                            });
                        }

                    }

                }

            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/peshowcaseexplore.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {

                var scope = jsScopeArray[i],
                    showcaseExplore = document.querySelectorAll('.showcase--explore');


                showcaseExplore.forEach(function ($this) {
                    let exploreWrapper = $this.querySelector('.showcase--explore--wrapper'),
                        project = exploreWrapper.querySelectorAll('.project--container'),
                        usedPositions = [],
                        wrap = $this.querySelector('.showcase--explore--wrapper'),
                        gridSize = Math.floor(Math.sqrt(project.length)) + 1,
                        gridLength = getComputedStyle(exploreWrapper).getPropertyValue('--gridLength'),
                        positions = [],
                        projectWidth = project[0].offsetWidth,
                        projectHeight = project[0].offsetHeight,
                        length = project.length;

                    function shuffle(array) {
                        let a = [...array];
                        for (let i = a.length - 1; i > 0; i--) {
                            const j = Math.floor(Math.random() * (i + 1));
                            [a[i], a[j]] = [a[j], a[i]];
                        }
                        return a;
                    }

                    for (let r = 1; r <= gridLength; r++) {
                        for (let c = 1; c <= gridLength; c++) {
                            let maxTop = (c * (exploreWrapper.offsetHeight) / gridLength) - (projectHeight / 2),
                                minTop = (c - 1) * (exploreWrapper.offsetHeight / gridLength),
                                maxLeft = r * ((exploreWrapper.offsetWidth) / gridLength) - (projectWidth / 2),
                                minLeft = (r - 1) * (exploreWrapper.offsetWidth / gridLength)

                            positions.push({
                                top: gsap.utils.random(minTop, maxTop),
                                left: gsap.utils.random(minLeft, maxLeft),
                                width: gsap.utils.random(projectWidth * 0.5, projectWidth * 0.7),
                            })
                        }
                    }

                    shuffle(positions);

                    $this.querySelectorAll('.showcase--explore--wrapper').forEach(function ($wrap) {

                        $wrap.querySelectorAll('.project--container').forEach(function ($item, i) {

                            let position = positions[i % length]

                            $item.setAttribute('data-index', i);
                            gsap.set($item, {
                                top: position.top,
                                left: position.left,
                            })
                            gsap.set($item.querySelector('.portfolio--project--wrapper'), {
                                width: position.width
                            })
                        })

                    })

                    if (scope.classList.contains('infinite__active')) {
                        let x = 0,
                            y = 0;

                        $this.addEventListener('mousemove', function (e) {
                            $this.querySelectorAll('.showcase--explore--wrapper').forEach(function ($grid) {
                                gsap.to($grid, {
                                    x: e.clientX / 5,
                                    y: e.clientY / 5,
                                    duration: 1,
                                })
                            })
                        })

                        function infiniteLoop(pos, limit) {
                            if (pos <= -limit) {
                                pos += limit;
                            } else if (pos >= 0) {
                                pos -= limit;
                            }
                            return pos;
                        }

                        const limitX = exploreWrapper.offsetWidth,
                            limitY = exploreWrapper.offsetHeight;

                        window.addEventListener("wheel", (e) => {
                            x += -e.deltaX / 4;
                            y += -e.deltaY / 4;

                            x = infiniteLoop(x, limitX);
                            gsap.set($this, { x });
                            y = infiniteLoop(y, limitY);
                            gsap.set($this, { y });
                        }, { passive: true });

                        let drag = new Draggable.create($this, {
                            type: 'x,y',
                            allowContextMenu: true,
                            onDrag: function () {
                                x = this.x;
                                y = this.y;
                                x = infiniteLoop(x, limitX);
                                gsap.set($this, { x });
                                y = infiniteLoop(y, limitY);
                                gsap.set($this, { y });
                            },
                        })

                    } else {

                        gsap.set(exploreWrapper, {
                            x: (exploreWrapper.offsetWidth - scope.offsetWidth) / -2,
                            y: (exploreWrapper.offsetHeight - scope.offsetHeight) / -2,
                        })

                        window.addEventListener('mousemove', (event) => {

                            gsap.to(wrap, {
                                x: (-1 * (((event.clientX / window.innerWidth) * ((exploreWrapper.offsetWidth * 1.4) - scope.offsetWidth)))),
                                y: (-1 * (((event.clientY / window.innerHeight) * ((exploreWrapper.offsetHeight * 1.4) - scope.offsetHeight)))),
                                duration: 2
                            })

                        });

                        matchMedia.add({
                            isMobile: "(max-width: 550px)"

                        }, (context) => {

                            let {
                                isMobile
                            } = context.conditions;

                            let drag = Draggable.create(wrap, {
                                type: 'x, y',
                                bounds: {
                                    minX: $this.offsetWidth / 10,
                                    maxX: $this.offsetWidth * -1,
                                    minY: $this.offsetHeight / 10,
                                    maxY: $this.offsetHeight * -0.6
                                },
                                inertia: true
                            })

                        });

                    }


                })

            }
        });







        elementorFrontend.hooks.addAction('frontend/element_ready/peshowcasecarousel.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {

                var scope = jsScopeArray[i],
                    showcaseCarousel = document.querySelectorAll('.showcase--carousel');

                showcaseCarousel.forEach(function ($this) {
                    let wrap = $this.querySelector('.showcase--carousel--wrapper'),
                        project = $this.querySelectorAll('.zeyna--portfolio--project'),
                        wrapperWidth = wrap.offsetWidth + parseInt(window.getComputedStyle(wrap).gap)
                    if (scope.classList.contains('autoplay__active')) {
                        project.forEach(function ($item) {
                            let clone = $item.cloneNode(true)
                            wrap.appendChild(clone)
                        })
                        project = $this.querySelectorAll('.showcase--product')
                        let tl = gsap.timeline({
                            repeat: -1
                        })
                        tl.to(wrap, {
                            x: -1 * wrapperWidth,
                            ease: 'none',
                            duration: parseInt($this.getAttribute('data-autoplay-duration'))
                        }, 0)

                        if (scope.classList.contains('speed__on__autoplay')) {
                            let whaler = Hamster(document.querySelector('body')),
                                wheelDeltaY, currentDeltaY;

                            whaler.wheel(function (event, delta, deltaX, deltaY) {

                                wheelDeltaY = event.deltaY;
                                event.deltaY < 0 ? wheelDeltaY = -1 * event.deltaY : '';
                                tl.timeScale(1 + (wheelDeltaY * 2))

                            });

                        }

                        if (scope.classList.contains('parallax__on')) {
                            $this.querySelectorAll('.portfolio--project--wrapper').forEach(function ($prlx) {
                                $prlx.style.width = '125%'
                            })
                        }

                        project.forEach(function ($item) {
                            $item.addEventListener('mouseenter', function () {
                                tl.pause()
                            })
                            $item.addEventListener('mouseleave', function () {
                                tl.play()
                                tl.timeScale(1)
                            })
                        })
                    } else if (scope.classList.contains('infinite__scroll')) {
                        project.forEach(function ($item) {
                            let clone = $item.cloneNode(true)
                            wrap.appendChild(clone)
                        })
                        let pinTarget = $this.dataset.pinTarget,
                            trigger = $this.dataset.pinTarget;

                        if (!pinTarget) {
                            pinTarget = true;
                        }

                        if (!trigger) {
                            trigger = $this
                        }

                        project = $this.querySelectorAll('.showcase--product')

                        let sct = ScrollTrigger.create({
                            trigger: trigger,
                            start: 'top top',
                            end: 'bottom+=' + $this.getAttribute('data-speed') + ' bottom',
                            scrub: true,
                            pin: pinTarget,
                            onUpdate: (self) => {
                                let prog = self.progress * wrapperWidth * -1
                                gsap.set(wrap, {
                                    x: prog
                                })
                            }
                        })

                    } else if (scope.classList.contains('navigate__draggable')) {
                        gsap.set(wrap, {
                            x: ($this.offsetWidth / 2) - (project[0].offsetWidth / 2)
                        })
                        Draggable.create(wrap, {
                            type: 'x',
                            inertia: true,
                            bounds: {
                                minX: (-1 * wrapperWidth) + (($this.offsetWidth / 2) + (project[0].offsetWidth / 2)),
                                maxX: ($this.offsetWidth / 2) - (project[0].offsetWidth / 2)
                            },
                            onDrag: function () {
                                if (scope.classList.contains('parallax__on')) {
                                    let prog = (this.x - (($this.offsetWidth / 2) - (project[0].offsetWidth / 2))) / ((-1 * wrapperWidth) + (($this.offsetWidth / 2) + (project[0].offsetWidth / 2)) - ($this.offsetWidth / 2) - (project[0].offsetWidth / 2))
                                    $this.querySelectorAll('.portfolio--project--wrapper').forEach(function ($prlx) {
                                        gsap.set($prlx, {
                                            x: -prog * 20 + '%'
                                        })
                                    })
                                }
                            },
                            onThrowUpdate: function () {
                                if (scope.classList.contains('parallax__on')) {
                                    let prog = (this.x - (($this.offsetWidth / 2) - (project[0].offsetWidth / 2))) / ((-1 * wrapperWidth) + (($this.offsetWidth / 2) + (project[0].offsetWidth / 2)) - ($this.offsetWidth / 2) - (project[0].offsetWidth / 2))
                                    $this.querySelectorAll('.portfolio--project--wrapper').forEach(function ($prlx) {
                                        gsap.set($prlx, {
                                            x: -prog * 20 + '%'
                                        })
                                    })
                                }
                            }
                        });

                    } else if (scope.classList.contains('navigate__scroll')) {
                        let pinTarget = $this.dataset.pinTarget,
                            trigger = $this.dataset.pinTarget;

                        if (!pinTarget) {
                            pinTarget = true;
                        }

                        if (!trigger) {
                            trigger = $this
                        }

                        let tl = gsap.timeline({
                            scrollTrigger: {
                                trigger: trigger,
                                start: 'top top',
                                end: 'bottom+=' + $this.getAttribute('data-speed') + ' bottom',
                                pin: pinTarget,
                                scrub: true,
                            }
                        });

                        tl.fromTo(wrap, {
                            x: ($this.offsetWidth / 2) - (project[0].offsetWidth / 2)
                        }, {
                            x: (-1 * wrapperWidth) + (($this.offsetWidth / 2) + (project[0].offsetWidth / 2)),
                        }, 0)
                        if (scope.classList.contains('parallax__on')) {
                            $this.querySelectorAll('.portfolio--project--wrapper').forEach(function ($prlx) {
                                tl.to($prlx, {
                                    x: '-20%'
                                }, 0)
                            })
                        }
                    }
                    let infinite = false
                    if (scope.classList.contains('infinite__scroll')) {
                        infinite = true
                    }

                    const lenis = new Lenis({
                        infinite: infinite,
                    });

                    // OPTIMIZED: GSAP ticker kullan
                    gsap.ticker.add((time) => {
                        lenis.raf(time * 1000);
                    });

                })

            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/peimagegallery.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    gallery = scope.querySelector('.pe--image--gallery'),
                    wrapper = scope.querySelector('.pe--image--gallery--wrapper'),
                    items = scope.querySelectorAll('.pe--image--gallery--item'),
                    columnCount = parseInt(gallery.dataset.columns),
                    id = scope.dataset.id;

                if (scope.classList.contains('pe--gallery--grid')) {


                    if (scope.classList.contains('ligbtbox--gallery--yes')) {
                        zeynaLighbox(gallery, wrapper, items);
                    }

                    if (!scope.classList.contains('build_type_none')) {

                        let buildType = gallery.dataset.buildType,
                            target = gallery.dataset.buildTarget,
                            pin = gallery.dataset.buildPin,
                            scrub = gallery.dataset.buildScrub,
                            stagger = gallery.dataset.buildStagger,
                            animFrom = gallery.dataset.buildStaggerFrom,
                            start = gallery.dataset.buildStart,
                            end = gallery.dataset.buildEnd,
                            duration = gallery.dataset.buildDuration;


                        gsap.getById(id) ? gsap.getById(id).scrollTrigger.kill(true) : '';

                        let tl = gsap.timeline({
                            id: id,
                            scrollTrigger: {
                                trigger: target ? target : gallery,
                                pin: (pin && target) ? target : (pin && !target) ? true : false,
                                scrub: scrub ? true : false,
                                start: start,
                                end: end,
                            }
                        });

                        var animStagger = {
                            each: parseFloat(stagger),
                            from: animFrom,
                            ease: 'none'
                        };

                        if (buildType === '3d--scale--in') {
                            items.forEach(function ($gridItem, i) {
                                let left = $gridItem.getBoundingClientRect().left - (scope.offsetWidth / 2) + ($gridItem.offsetWidth / 2),
                                    top = $gridItem.getBoundingClientRect().top - scope.getBoundingClientRect().top - (scope.offsetHeight / 2) + ($gridItem.offsetHeight / 2),
                                    rotateY = left / 2,
                                    rotateX = top / 4
                                $gridItem.setAttribute('data-left', left)
                                $gridItem.setAttribute('data-top', top)

                                gsap.set($gridItem, {
                                    x: left * 4,
                                    y: top * 3,
                                    z: top,
                                    scale: 1.3,
                                    rotateY: -rotateY,
                                    rotateX: rotateX,
                                    opacity: 0
                                })

                                animStagger = function (index, target) {
                                    let dataTop = parseInt(target.dataset.top),
                                        dataLeft = parseInt(target.dataset.left)
                                    let hipo = Math.sqrt((dataTop * dataTop) + (dataLeft * dataLeft))
                                    return hipo * 0.00025
                                }

                            })
                        }

                        tl.fromTo(items, {
                            y: buildType === 'slide-up' ? '100vh' : buildType === 'slide-down' ? '-100vh' : buildType === 'rotate--up' ? '25vh' : '0vh',
                            x: buildType === 'slide-left' ? '-100vw' : buildType === 'slide-right' ? '100vw' : '0vw',
                            scale: buildType === 'scale-up' ? 0 : 1,
                            opacity: buildType === 'fade' || buildType === '3d--scale--in' ? 0 : 1,
                            rotateX: buildType === 'rotate--up' ? -90 : 0,
                        }, {
                            x: 0,
                            y: 0,
                            z: 0,
                            rotateX: 0,
                            rotateY: 0,
                            scale: 1,
                            opacity: 1,
                            duration: duration,
                            stagger: animStagger,
                            ease: 'power2.out'
                        });

                    }

                    items.forEach(item => {

                        const index = parseInt(getComputedStyle(item).getPropertyValue('--i'), 10);
                        const row = Math.floor(index / columnCount) + 1;
                        const col = (index % columnCount) + 1;

                        if (scope.classList.contains('parallax_items')) {
                            const centerCol = (columnCount + 1) / 2;
                            const distanceFromCenter = Math.abs(col - centerCol);
                            const strength = gallery.dataset.parallaxStrength ? gallery.dataset.parallaxStrength : 100;

                            let yVal = scope.classList.contains('parallax--end') ? -col * -strength :
                                scope.classList.contains('parallax--start') ? (columnCount - col) * strength :
                                    scope.classList.contains('parallax--center') ? -distanceFromCenter * -strength :
                                        scope.classList.contains('parallax--random') ? gsap.utils.random(-strength, strength) : '';


                            gsap.fromTo(item, {
                                y: 0
                            }, {
                                y: -yVal * 5,
                                force3D: true,
                                ease: 'none',
                                scrollTrigger: {
                                    trigger: scope,
                                    scrub: 1,
                                    start: 'top bottom',
                                    end: 'bottom top'
                                }
                            })
                        }


                        if (scope.classList.contains('parallax_images')) {
                            let img = item.querySelector('img');
                            gsap.set(img, {
                                scale: 1.2
                            })

                            gsap.fromTo(img, {
                                yPercent: -10
                            }, {
                                yPercent: 10,
                                ease: 'none',
                                scrollTrigger: {
                                    trigger: scope.classList.contains('parallax--random') ? item : gallery,
                                    scrub: true,
                                    start: 'top bottom',
                                    end: 'bottom top'
                                }
                            })

                        };

                        if (scope.classList.contains('parallax_images')) {

                        }

                    })

                } else if (scope.classList.contains('pe--gallery--slider')) {
                    peSlider(scope);

                }

            }
        })


        elementorFrontend.hooks.addAction('frontend/element_ready/peshowcaseverticalcarousel.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    showcaseVerticalCarousel = document.querySelectorAll('.showcase--vertical--carousel');
                showcaseVerticalCarousel.forEach(function ($this) {
                    let wrap = $this.querySelector('.showcase--vertical--carousel--wrapper'),
                        wrapHeight = wrap.offsetHeight,
                        project = $this.querySelectorAll('.showcase--project'),
                        speed = $this.getAttribute('data-speed'),
                        pinTarget = $this.getAttribute('data-pin-target'),
                        trigger = pinTarget

                    if (!pinTarget) {
                        pinTarget = true
                        trigger = $this
                    }


                    project.forEach(function ($item) {
                        let clonedItem = $item.cloneNode(true);
                        wrap.appendChild(clonedItem)
                    })
                    ScrollTrigger.create({
                        trigger: trigger,
                        pin: pinTarget,
                        start: 'top top',
                        end: "bottom+=" + speed + " top",
                        scrub: true,
                        onUpdate: function (self) {
                            let prog = self.progress * wrapHeight
                            gsap.set(wrap, {
                                y: -1 * prog
                            })
                        }
                    })

                    const lenis = new Lenis({
                        smooth: true,
                        infinite: true,
                        smoothTouch: false,
                    });

                    // OPTIMIZED: GSAP ticker kullan
                    gsap.ticker.add((time) => {
                        lenis.raf(time * 1000);
                    });
                })
            }
        });






        elementorFrontend.hooks.addAction('frontend/element_ready/peshowcaselist.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    showcaseList = document.querySelectorAll('.showcase--list');
                showcaseList.forEach(function ($this) {
                    let title = $this.querySelectorAll('.portfolio--project--list'),
                        totalHeight = (title[0].offsetHeight * title.length),
                        titlesWrap = $this.querySelector('.showcase--list--title--wrapper'),
                        cloneLength = Math.ceil($this.offsetHeight / totalHeight),
                        e = 0;

                    title.forEach(function ($title, i) {
                        $title.setAttribute('data-index', i)
                    })

                    for (e = 0; e <= cloneLength; e++) {
                        title.forEach(function ($title) {
                            let cloneTitle = $title.cloneNode(true)
                            cloneTitle.classList.add('clone--title')
                            titlesWrap.appendChild(cloneTitle)
                        })
                    }

                    $this.querySelectorAll('.showcase--project--image').forEach(function ($image, i) {
                        $image.classList.add('project__' + i)
                    })

                    let speed = $this.getAttribute('data-speed');

                    if (speed === null) {
                        speed = '1000'
                    }



                    let tl = gsap.timeline({
                        ease: 'none',
                        scrollTrigger: {
                            trigger: $this,
                            start: 'center center',
                            end: 'bottom+=' + speed + ' top',
                            pin: true,
                            scrub: true,
                            onUpdate: (self) => {
                                let prog = self.progress * totalHeight
                                if (prog > 0) {
                                    prog = prog - totalHeight
                                } else if (prog < totalHeight * -1) {
                                    prog = prog + totalHeight
                                }
                                gsap.set(titlesWrap, {
                                    y: prog
                                })


                                matchMedia.add({
                                    isMobile: "(max-width: 550px)"

                                }, (context) => {

                                    let {
                                        isMobile
                                    } = context.conditions;

                                    let activeIndex = -1 * Math.ceil(prog / title[0].offsetHeight),
                                        image = $this.querySelectorAll('.showcase--project--image');

                                    image.forEach(function ($image) {
                                        if ($image.classList.contains('project__' + activeIndex)) {
                                            gsap.to($image, {
                                                opacity: 1,
                                                scale: 1
                                            })
                                        } else {
                                            gsap.to($image, {
                                                opacity: 0,
                                                scale: 0.8
                                            })
                                        }
                                    })
                                    title.forEach(function ($title) {
                                        if ($title.classList.contains('clone--title')) {
                                            if ($title.classList.contains('title__' + activeIndex)) {
                                                gsap.set($title, {
                                                    opacity: 1,
                                                })
                                            } else {
                                                gsap.set($title, {
                                                    opacity: 0.2
                                                })
                                            }
                                        } else {
                                            gsap.set($title, {
                                                opacity: 0.2
                                            })
                                        }

                                    })


                                });

                            }
                        }
                    })

                    const lenis = new Lenis({
                        smooth: true,
                        infinite: true,
                        smoothTouch: true,
                    });

                    // OPTIMIZED: GSAP ticker kullan
                    gsap.ticker.add((time) => {
                        lenis.raf(time * 1000);
                    });

                    title = $this.querySelectorAll('.portfolio--project--list');


                    title.forEach(function ($title) {
                        $title.addEventListener('mouseenter', function () {

                            let activeIndex = parseInt(($title.getAttribute('data-index')))
                            if (scope.querySelector('.has-gl')) {
                                window.mediaStore[activeIndex].mesh.visible = true;
                            }

                            gsap.set($this.querySelector('.project__' + activeIndex), {
                                opacity: 1,
                                scale: 1
                            })
                            gsap.to(title, {
                                opacity: 0.05
                            })
                            gsap.to($title, {
                                opacity: 1,

                            })


                        })
                        $title.addEventListener('mouseleave', function () {
                            let activeIndex = parseInt(($title.getAttribute('data-index')))

                            if (scope.querySelector('.has-gl')) {
                                window.mediaStore[activeIndex].mesh.visible = false;
                            }

                            gsap.to($this.querySelector('.project__' + activeIndex), {
                                opacity: 0,
                            })
                            gsap.to(title, {
                                opacity: 1,

                            })


                        })
                    })

                    titlesWrap.addEventListener('mousemove', function (e) {

                        let mouseLeft = e.clientX,
                            mouseTop = e.clientY;

                        gsap.to(document.querySelector('.showcase--list--image--wrapper'), {
                            left: mouseLeft,
                            top: mouseTop,
                            duration: 1,
                            ease: 'power3.out',
                        })
                    })

                })
            }
        });



        elementorFrontend.hooks.addAction('frontend/element_ready/peshowcasefullscreenslideshow.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {

                var scope = jsScopeArray[i],
                    swiperCont = scope.querySelectorAll('.showcase--fullscreen--slideshow');

                swiperCont.forEach(cont => {

                    let duration = parseFloat(cont.dataset.duration),
                        mousewheel = cont.dataset.mousewheel,
                        loop = cont.dataset.loop,
                        autoplay = (cont.dataset.autoplay === 'true'),
                        parallax = cont.dataset.parallax,
                        autoplayDelay = parseFloat(cont.dataset.autoplayDelay),
                        thumbs = false,
                        navigation = false,
                        autoplayParams,
                        interacted;

                    if (autoplay) {
                        autoplayParams = {
                            delay: autoplayDelay,
                            disableOnInteraction: false
                        }

                    } else {
                        autoplayParams = false;
                    }

                    var tl;
                    if (scope.querySelector('.showcase--fullscreen--slideshow--thumbs') && autoplay) {

                        var thmbs = scope.querySelectorAll('.showcase--fullscreen--slideshow--thumb');
                        tl = gsap.timeline({
                            paused: true
                        });

                        thmbs.forEach((thmb, i) => {
                            tl.fromTo(thmb, {
                                '--bakdropWidth': '0%'
                            }, {
                                '--bakdropWidth': '100%',
                                duration: autoplayDelay / 1000,
                                delay: i == 0 ? 0 : 1.25,
                                ease: 'none'
                            }, 'label_' + i)
                        })
                    }


                    if (scope.querySelector('.showcase--fullscreen--slideshow--thumbs')) {
                        var thumbsSwiper = new Swiper(scope.querySelector('.showcase--fullscreen--slideshow--thumbs'), {
                            spaceBetween: 25,
                            slidesPerView: 2,
                            breakpoints: {
                                // when window width is >= 320px
                                // 320: {
                                //     slidesPerView: 2,
                                //     spaceBetween: 20
                                // },
                                // when window width is >= 480px
                                480: {
                                    slidesPerView: 3,
                                    spaceBetween: 30,
                                },
                                830: {
                                    slidesPerView: 4,
                                    spaceBetween: 40
                                },
                                1200: {
                                    spaceBetween: 25,
                                    slidesPerView: 6,
                                }
                            },
                            autoplay: autoplayParams,
                            centeredSlides: true,
                            slideToClickedSlide: true,
                            mousewheel: mousewheel ? true : false,
                            centerInsufficientSlides: true,
                            freeMode: false,
                            watchSlidesProgress: true,
                            speed: duration ? duration : 1250,
                            on: {
                                progress: function (self, prog) {

                                    if (autoplay) {

                                        if (!prog == 0) {
                                            if (Math.floor(prog * 10) == 10) {
                                                tl.play('label_9');
                                            } else {
                                                tl.play('label_' + Math.floor(prog * 10))
                                            }
                                        } else if (interacted) {
                                            tl.play('label_0')
                                        }

                                    }

                                    this.slides.forEach((slide) => {
                                        const progress = slide.progress;
                                        if (progress > 0) {
                                            slide.classList.add('before-active');
                                            slide.classList.remove('after-active');
                                        } else if (progress < 0) {
                                            slide.classList.add('after-active');
                                            slide.classList.remove('before-active');
                                        } else {
                                            slide.classList.remove('before-active', 'after-active');
                                        }
                                    });
                                },
                            }
                        });

                        if (scope.querySelector('.pe-video')) {
                            thumbsSwiper.autoplay.stop();
                            scope.querySelector('.showcase--fullscreen--slideshow--thumbs').addEventListener("firsSlideVidReady", function (e) {
                                tl.play('label_0');
                                interacted = true;
                            });
                        }

                    }

                    if (scope.querySelector('.showcase--fullscreen--slideshow--arrows')) {

                        navigation = {
                            nextEl: scope.querySelector('.swiper--next'),
                            prevEl: scope.querySelector('.swiper--prev')
                        }

                    }

                    var interleaveOffset = 1;
                    var backSwiper = new Swiper(cont, {
                        slidesPerView: 1,
                        speed: duration ? duration : 1250,
                        parallax: parallax ? true : false,
                        // effect: 'fade',
                        watchSlideProgress: true,
                        navigation: navigation,
                        on: {
                            progress: function () {
                                if (parallax) {
                                    let swiper = this;
                                    for (let i = 0; i < swiper.slides.length; i++) {
                                        let slideProgress = swiper.slides[i].progress,
                                            innerOffset = swiper.height * interleaveOffset,
                                            innerTranslate = slideProgress * innerOffset;

                                        if (scope.classList.contains('swiper--vertical')) {
                                            swiper.slides[i].querySelector("a.barba--trigger").style.transform =
                                                "translateY(" + innerTranslate + "px)";
                                        } else {
                                            swiper.slides[i].querySelector("a.barba--trigger").style.transform =
                                                "translateX(" + innerTranslate + "px)";
                                        }

                                    }
                                }
                            },
                            setTransition: function (speed) {
                                if (parallax) {

                                    let swiper = this;
                                    for (let i = 0; i < swiper.slides.length; i++) {
                                        swiper.slides[i].style.transition = speed + "ms";
                                        swiper.slides[i].querySelector("a.barba--trigger").style.transition = 1250 + "ms";
                                    }
                                }
                            },

                        }
                    });

                    if (scope.querySelector('.showcase--fullscreen--slideshow--thumbs')) {
                        thumbsSwiper.controller.control = backSwiper;
                        backSwiper.controller.control = thumbsSwiper;
                    }

                })

            }
        });


        // Showcases

        elementorFrontend.hooks.addAction('frontend/element_ready/peshoppingcart.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    button = scope.querySelector('.zeyna--cart--button');

                if (scope.classList.contains('cart-mini-cart')) {
                    pePopup(scope, scope);
                }
                if (!zeynaLenis && scope.querySelector('.cart_list')) {
                    // OPTIMIZED: Cart Lenis - GSAP ticker
                    const cartLenis = new Lenis({
                        wrapper: document.querySelector('.cart_list'),
                        smooth: false,
                        smoothTouch: false
                    });

                    gsap.ticker.add((time) => {
                        cartLenis.raf(time * 1000);
                    });

                    window.cartLenis = cartLenis;

                } else {
                    window.cartLenis = false;
                }

            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/nested-tabs.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    tabs = scope.querySelector('.e-n-tabs'),
                    contents = scope.querySelectorAll('.e-n-tabs-content > div'),
                    titlesWrap = scope.querySelector('.e-n-tabs-heading');

                if (titlesWrap.offsetWidth > tabs.offsetWidth) {

                    let draggable;
                    draggable = Draggable.create(titlesWrap, {
                        type: 'x',
                        bounds: tabs,
                        lockAxis: true,
                        dragResistance: 0.5,
                        inertia: true,
                        allowContextMenu: true,
                    })[0];

                    function moveToTitle(title) {
                        const wrapRect = titlesWrap.getBoundingClientRect();
                        const titleRect = title.getBoundingClientRect();

                        let currentX = gsap.getProperty(titlesWrap, 'x');

                        let delta = titleRect.left - wrapRect.left;
                        let targetX = currentX - delta;

                        // bounds içinde tut
                        targetX = Math.min(0, targetX);
                        targetX = Math.max(tabs.offsetWidth - titlesWrap.offsetWidth, targetX);

                        gsap.to(titlesWrap, {
                            x: targetX,
                            duration: 0.5,
                            ease: 'power3.out',
                            onUpdate: () => draggable && draggable.update()
                        });
                    };

                    scope.querySelectorAll('.e-n-tab-title').forEach(title => {
                        title.addEventListener('click', () => {
                            moveToTitle(title);
                        });
                    });


                }

            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/nested-accordion.default', function ($scope) {

            var items = $scope[0].querySelectorAll('details.e-n-accordion-item'),
                revealOnscroll = $scope[0].classList.contains('reveal--on--scroll'),
                tl;

            if (revealOnscroll) {

                let start = $scope[0].dataset.start,
                    end = $scope[0].dataset.end,
                    trigger = $scope[0].dataset.trigger ? $scope[0].dataset.trigger : $scope[0];

                gsap.getById($scope[0].dataset.id) && gsap.getById($scope[0].dataset.id).revert();

                tl = gsap.timeline({
                    id: $scope[0].dataset.id,
                    scrollTrigger: {
                        trigger: trigger,
                        start: start,
                        end: end,
                        // markers: true,
                        scrub: true,
                        pin: $scope[0].dataset.pin ? true : false,
                        pinSpacing: 'padding',
                    }
                })

                matchMedia.add({
                    isMobile: "(max-width: 550px)"

                }, (context) => {

                    let {
                        isMobile
                    } = context.conditions;

                    tl.revert(true);


                });

            }

            items.forEach((item, i) => {
                const summary = item.querySelector('summary.e-n-accordion-item-title');
                const content = item.querySelector(':scope >[role="region"]');
                item.open = true;
                var isOpen = false;
                var index = i;
                const id = item.id.match(/(\d+)$/)[1];
                var images;

                if (document.querySelector('.e-n-accordion-item-custom-icon-' + id)) {

                    const icon = document.querySelector('.e-n-accordion-item-custom-icon-' + id);
                    if (icon) {
                        item
                            .querySelector('.e-n-accordion-item-title-header')
                            .prepend(icon);
                    }
                }

                if ($scope[0].querySelector('.e-n-accordion-images')) {
                    images = $scope[0].querySelectorAll('.accordion--image');
                }

                if ($scope[0].classList.contains('first--expanded')) {
                    if (i === 0) {
                        isOpen = true;
                        item.classList.add('is-open');
                        gsap.set(content, { height: 'auto', overflow: 'hidden' });
                        if ($scope[0].querySelector('.ac--image--' + index)) {
                            $scope[0].querySelector('.ac--image--' + index).classList.add('active');
                        }
                    } else {
                        gsap.set(content, { height: 0, overflow: 'hidden' });
                        if ($scope[0].querySelector('.ac--image--' + index)) {
                            $scope[0].querySelector('.ac--image--' + index).classList.remove('active');
                        }
                    }
                } else {
                    gsap.set(content, { height: 0, overflow: 'hidden' });
                }

                if (!revealOnscroll || window.matchMedia("(max-width: 550px)").matches) {
                    summary.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();

                        if ($scope[0].querySelector('.e-n-accordion-images')) {
                            var state = Flip.getState(images, {
                                props: ['display']
                            });
                        }


                        if (isOpen) {
                            item.classList.remove('is-open');
                            isOpen = false;
                            if ($scope[0].querySelector('.ac--image--' + index)) {
                                $scope[0].querySelector('.ac--image--' + index).classList.remove('active');
                            }
                            gsap.to(content, {
                                height: 0,
                                duration: 1,
                                ease: 'expo.inOut',
                            })

                        } else {

                            if ($scope[0].querySelector('.is-open') && !$scope[0].classList.contains('allow--multiple')) {
                                $scope[0].querySelector('.is-open').querySelector('summary.e-n-accordion-item-title').click();
                            }

                            item.classList.add('is-open');
                            isOpen = true;
                            if ($scope[0].querySelector('.ac--image--' + index)) {
                                $scope[0].querySelector('.ac--image--' + index).classList.add('active');
                            }
                            gsap.to(content, {
                                height: 'auto',
                                duration: 1,
                                ease: 'expo.inOut',
                            })

                        }


                    }, true);

                } else {

                    if ($scope[0].classList.contains('first--expanded')) {
                        if (i === 0) {
                            isOpen = true;
                            item.classList.add('is-open');
                        } else {

                            tl.to(content, {
                                height: 'auto',
                                // duration: 1,
                                ease: 'none',
                                onStart: () => {
                                    isOpen = true;
                                    item.classList.add('is-open');
                                },
                                onReverseComplete: () => {
                                    item.classList.remove('is-open')
                                    items[i - 1].classList.add('is-open')
                                }
                            }, 'label_' + i);

                        }
                    }

                    if (i !== items.length - 1) {

                        tl.to(content, {
                            height: 0,
                            // duration: 1,
                            ease: 'none',
                            onStart: () => {
                                isOpen = false;
                                item.classList.remove('is-open');
                            }
                        }, 'label_' + (i + 1));

                    }


                }


            });

        });


        elementorFrontend.hooks.addAction('frontend/element_ready/pebutton.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    button = scope.querySelector('.pe--button'),
                    textHover = button.dataset.textHover;

                if (scope.classList.contains('initialized')) {
                    return false;
                } else {
                    scope.classList.add('initialized');
                }

                if (scope.querySelector('.pe--styled--popup')) {

                    pePopup(scope, scope);
                }

            }

        });



        elementorFrontend.hooks.addAction('frontend/element_ready/peproductelements.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i];

                // ============================================
                // OPTIMIZED: Countdown - GSAP animasyonları kaldırıldı
                // ============================================
                if (scope.querySelector('.zeyna--sale--countdown')) {

                    let countdownElement = scope.querySelector('.zeyna--sale--countdown');
                    var saleEndTime = parseInt(countdownElement.getAttribute('data-endtime'));

                    const daysEl = countdownElement.querySelector('.days');
                    const hoursEl = countdownElement.querySelector('.hours');
                    const minutesEl = countdownElement.querySelector('.minutes');
                    const secondsEl = countdownElement.querySelector('.seconds');

                    var interval = setManagedInterval(function () {
                        var now = new Date().getTime();
                        var distance = saleEndTime * 1000 - now;

                        if (distance < 0) {
                            clearManagedInterval(interval);
                            countdownElement.innerHTML = "Sale Ended";
                        } else {
                            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                            // GSAP animasyonlar kaldırıldı - doğrudan set (4x daha hızlı!)
                            if (daysEl) daysEl.textContent = days;
                            if (hoursEl) hoursEl.textContent = hours;
                            if (minutesEl) minutesEl.textContent = minutes;
                            if (secondsEl) secondsEl.textContent = seconds;
                        }
                    }, 1000);
                }




                if (scope.querySelector('.pe--styled--popup')) {
                    pePopup(scope, scope.querySelector('.zeyna--product--element'));

                }

                if (scope.querySelector('.swc--accordion')) {

                    let items = scope.querySelectorAll('.swc--accordion--item');


                    items.forEach(item => {

                        let title = item.querySelector('.swc--item--title'),
                            content = item.querySelector('.swc--item--content');

                        title.addEventListener('click', () => {

                            let contState = Flip.getState(content, {
                                props: ['padding']
                            });

                            if (scope.querySelector('.swc--accordion--item.active') && !item.classList.contains('active')) {
                                scope.querySelector('.swc--accordion--item.active').querySelector('.swc--item--title').click();
                            }

                            item.classList.toggle('active');

                            Flip.from(contState, {
                                duration: 1,
                                ease: 'expo.inOut',
                                absolute: false,
                                absoluteOnLeave: false,
                            })


                        })


                    })

                    if (scope.classList.contains('accordion--first--active')) {
                        items[0].querySelector('.swc--item--title').click();
                        throttledWidgetRefresh(1000); // OPTIMIZED
                    }


                }

                if (scope.querySelector('.element--rating')) {

                    let ratings = scope.querySelectorAll('.element--rating');

                    ratings.forEach(rating => {

                        rating.addEventListener('click', () => {

                            if (parents(rating, '.product-page')) {

                                let page = parents(rating, '.product-page')[0];

                                if (page.querySelector('.item-reviews')) {

                                    page.querySelectorAll('.item-reviews').forEach(revs => revs.click());
                                }

                            }

                        })

                    })


                }

                if (scope.querySelector('.wc-tabs')) {

                    let tabs = scope.querySelector('.wc-tabs'),
                        titles = tabs.childNodes,
                        contents = scope.querySelectorAll('.woocommerce-Tabs-panel');

                    contents.forEach(cont => cont.style.display = 'none');

                    for (let i = 0; i < titles.length; i++) {

                        if (titles[i].tagName === 'LI') {
                            var findCont;
                            if (i == 1) {
                                titles[i].classList.add('active');
                                findCont = titles[i].getAttribute('aria-controls');
                                scope.querySelector('#' + findCont).style.display = 'block';
                            }
                        }
                    }

                    if (scope.querySelector('#rating')) {

                        scope.querySelectorAll('#rating').forEach(rating => {

                            const ratingElement = rating;
                            // Hide the #rating input and add the stars UI
                            ratingElement.style.display = 'none';
                            const starsContainer = document.createElement('p');
                            starsContainer.className = 'stars';
                            starsContainer.innerHTML = `
                <span>
                    <a class="star-1" href="#">1</a>
                    <a class="star-2" href="#">2</a>
                    <a class="star-3" href="#">3</a>
                    <a class="star-4" href="#">4</a>
                    <a class="star-5" href="#">5</a>
                </span>
            `;
                            ratingElement.insertAdjacentElement('beforebegin', starsContainer);
                        })



                    }

                }

                if (scope.classList.contains('add--configurator') && scope.querySelector('.variations_form')) {

                    var titlesWrap = scope.querySelector('.sv--configurator--titles'),
                        titles = titlesWrap.querySelectorAll('.svc--title'),
                        form = scope.querySelector('.variations_form'),
                        variations = form.querySelectorAll('tr'),
                        buttons = scope.querySelectorAll('.svc--button'),
                        inputs = scope.querySelectorAll('input[type=radio]');



                    variations.forEach((variation, i) => {
                        if (i == 0) {
                            variation.classList.add('active');
                        }
                    })

                    buttons.forEach((button, i) => {
                        if (i == 0) {
                            button.classList.add('active');
                        };

                        button.addEventListener('click', () => {
                            scope.querySelector('.svc--title--' + button.dataset.attr).click();
                        })
                    })

                    inputs.forEach(input => {

                        input.addEventListener('change', function () {

                            let parent = parents(input, 'tr'),
                                index = parseInt(parent[0].dataset.index) + 1,
                                button = scope.querySelector('.svc--button--' + index);

                            if (button) {
                                button.classList.remove('svc--disabled');
                            }
                            scope.querySelector('.svc--title--' + parent[0].dataset.attr).classList.remove('svc--disabled')
                        })
                    })


                    titles.forEach((title, i) => {

                        let attr = '.attr_' + title.dataset.attr,
                            button = scope.querySelector('.svc--button_' + title.dataset.attr),
                            buttonIndex = button ? parseInt(button.dataset.index) : null;

                        i == 0 ? title.classList.add('active') : '';

                        title.addEventListener('click', () => {

                            form.querySelector('.svc--title.active').classList.remove('active');
                            title.classList.add('active');

                            if (button) {
                                button.classList.remove('active');

                                if (scope.querySelector('.svc--button--' + (buttonIndex + 1))) {
                                    scope.querySelector('.svc--button--' + (buttonIndex + 1)).classList.add('active');
                                } else if (buttonIndex == buttons.length) {
                                    scope.querySelector('.single_variation_wrap').classList.add('active')
                                }
                            } else {

                                scope.querySelector('.single_variation_wrap').classList.remove('active');
                                buttons.forEach((button, i) => {
                                    button.classList.remove('active');
                                })
                                scope.querySelector('.svc--button--1').classList.add('active');
                            }

                            if (form.querySelector('tr.active')) {

                                form.querySelector('tr.active').classList.remove('active');
                            }

                            let state = Flip.getState(variations, {
                                props: ['display']
                            });

                            form.querySelector(attr).classList.add('active');

                            Flip.from(state, {
                                duration: 1,
                                ease: 'expo.inOut',
                                absolute: true,
                                absoluteOnLeave: true,
                                onEnter: elements => gsap.fromTo(elements, {
                                    opacity: 0,
                                    x: 100
                                }, {
                                    opacity: 1,
                                    x: 0
                                }),
                                onLeave: elements => gsap.fromTo(elements, {
                                    opacity: 1,
                                    x: 0
                                }, {
                                    opacity: 0,
                                    x: -100,
                                    stagger: 0.1
                                }),
                            })

                        })

                    })

                }

                scope.querySelectorAll('form.variations_form .zeyna-variation-radio-buttons input[type=radio]').forEach(function (radioButton) {
                    radioButton.addEventListener('change', function () {
                        var form = radioButton.closest('form.variations_form');
                        var attributeName = radioButton.getAttribute('name');
                        var attributeValue = radioButton.value;

                        var selectElement = form.querySelector('select[name="' + attributeName + '"]');

                        if (selectElement) {
                            selectElement.value = attributeValue;

                            var event = new Event('change', { bubbles: true });
                            selectElement.dispatchEvent(event);
                        }

                        var checkEvent = new Event('check_variations', { bubbles: true });
                        form.dispatchEvent(checkEvent);

                        if (scope.classList.contains('variation--selection--show')) {

                            let selectionText = form.querySelector('option[value="' + selectElement.value + '"]').innerHTML,
                                findLabel = parents(radioButton, 'tr')[0].querySelector('th.label label');

                            if (findLabel.querySelector('span')) {
                                findLabel.querySelector('span').remove();
                            }
                            findLabel.innerHTML += '<span> : ' + selectionText + '</span>';
                        }
                    });
                });

                if (scope.querySelector('select')) {
                    let selects = scope.querySelectorAll('select');
                    selects.forEach(select => {
                        if (select.querySelector('option[selected]')) {
                            let val = select.querySelector('option[selected]').value;
                            let findRadio = scope.querySelector('input[value="' + val + '"]');
                            findRadio.checked = true;

                            var event = new Event('change', { bubbles: true });
                            findRadio.dispatchEvent(event);

                        }

                    })
                }

                function zeyna_quantityControl(item) {
                    if (!item.querySelector('.input-text.qty') || !item.querySelector('.zeyna--quantity--control')) {
                        return false;
                    }
                    let qty = item.querySelector('.input-text.qty'),
                        max = parseInt(qty.max),
                        min = parseInt(qty.min),
                        val = parseInt(qty.value),
                        control = item.querySelector('.zeyna--quantity--control'),
                        decrease = control.querySelector('.quantity--decrease'),
                        increase = control.querySelector('.quantity--increase'),
                        current = control.querySelector('.current--quantity');

                    increase.addEventListener('click', () => {
                        val++;
                        val >= max ? val = max : '';
                        current.innerHTML = val;
                        qty.value = val;
                        updateButton.click();
                    })

                    decrease.addEventListener('click', () => {
                        val--
                        val <= min ? val = min : '';
                        current.innerHTML = val;
                        qty.value = val;
                        updateButton.click();

                    })

                }

                var addToCart = scope.querySelectorAll('.zeyna--cart--form');
                if (addToCart.length) {
                    addToCart.forEach(button => {
                        zeyna_quantityControl(button)
                    })
                }

                var inputs = scope.querySelectorAll('.inputfile');

                if (inputs.length) {

                    Array.prototype.forEach.call(inputs, function (input) {
                        var label = input.nextElementSibling,
                            labelVal = label.innerHTML;

                        input.addEventListener('change', function (e) {
                            var fileName = '';
                            if (this.files && this.files.length > 1)
                                fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
                            else
                                fileName = e.target.value.split('\\').pop();

                            if (fileName) {
                                label.querySelector('span').innerHTML = fileName;
                                input.classList.add('has--files');
                            } else {
                                label.innerHTML = labelVal;
                                input.classList.remove('has--files');
                            }
                        });

                        input.addEventListener('focus', function () { input.classList.add('has-focus'); });
                        input.addEventListener('blur', function () { input.classList.remove('has-focus'); });
                    });

                }

                if (scope.querySelector('.variations_form')) {
                    var form = scope.querySelector('.variations_form'),
                        tableRows = form.querySelectorAll('tr'),
                        disabled = true;

                    function checkVariations() {
                        disabled = false;
                        tableRows.forEach(row => {
                            let inputs = row.querySelectorAll('input');
                            let rowValid = false;

                            inputs.forEach(input => {

                                if (input.type === 'file') {
                                    rowValid = true;
                                } else if (input.checked) {
                                    rowValid = true;
                                }
                            });

                            if (!rowValid) {
                                disabled = true;
                            }
                        });

                        if (scope.querySelector('.woocommerce-variation-add-to-cart')) {

                            if (disabled == true) {
                                scope.querySelector('.woocommerce-variation-add-to-cart').classList.add('woocommerce-variation-add-to-cart-disabled')
                                scope.querySelector('.single_add_to_cart_button').classList.add('disabled')
                            } else {
                                scope.querySelector('.woocommerce-variation-add-to-cart').classList.remove('woocommerce-variation-add-to-cart-disabled')
                                scope.querySelector('.woocommerce-variation-add-to-cart').classList.add('woocommerce-variation-add-to-cart-enabled')
                                scope.querySelector('.single_add_to_cart_button').classList.remove('disabled')
                            }

                        }
                    }

                    checkVariations();

                    tableRows.forEach(row => {
                        row.querySelectorAll('input').forEach(input => {
                            input.addEventListener('change', () => {
                                checkVariations();
                            });
                        });
                    });
                }

                if (scope.querySelector('.zeyna--fbt-products')) {

                    const fbtCheckboxes = scope.querySelectorAll('.fbt-checkbox');
                    const fbtTotalValue = scope.querySelector('.fbt-total-value');

                    function updateTotalPrice() {
                        let total = 0;

                        fbtCheckboxes.forEach(checkbox => {
                            if (checkbox.checked) {
                                const productItem = checkbox.closest('.fbt-product-item');
                                const priceElement = productItem.querySelector('.fbt-price');
                                const priceText = priceElement.textContent.replace(/[^0-9.]/g, ''); // Remove currency symbols
                                total += parseFloat(priceText);

                                // Handle variations if any
                                const variationSelect = productItem.querySelector('.fbt-variation-select');
                                if (variationSelect && variationSelect.value) {
                                    const variationPrice = parseFloat(variationSelect.selectedOptions[0].dataset.price || 0);
                                    if (!isNaN(variationPrice)) {
                                        total += variationPrice - parseFloat(priceText); // Adjust for variation price
                                    }
                                }
                            }
                        });

                        fbtTotalValue.textContent = new Intl.NumberFormat('en-US', {
                            style: 'currency',
                            currency: 'USD',
                        }).format(total);
                    }

                    fbtCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', updateTotalPrice);
                    });

                    scope.querySelectorAll('.fbt-variation-select').forEach(select => {
                        select.addEventListener('change', updateTotalPrice);
                    });

                    // Initial total calculation
                    updateTotalPrice();


                }

                if (scope.classList.contains('sticky--atc--active')) {

                    let atc = scope.querySelector('.element--add-to-cart');
                    if (scope.querySelector('.zeyna--sticky--add--to--cart')) {
                        document.querySelector('.elementor_library-template').appendChild(scope.querySelector('.zeyna--sticky--add--to--cart'));
                    }

                    let stickyAtc = document.querySelector('.zeyna--sticky--add--to--cart');

                    ScrollTrigger.getById(scope.dataset.id) ? ScrollTrigger.getById(scope.dataset.id).kill(true) : '';

                    clearProps(stickyAtc);

                    ScrollTrigger.create({
                        trigger: atc,
                        id: scope.dataset.id,
                        start: 'bottom top',
                        onLeave: () => {
                            gsap.to(stickyAtc, {
                                yPercent: 0,
                                y: 0,
                                duration: .65,
                                ease: 'power4.out'
                            })
                        },
                        onLeaveBack: () => {
                            gsap.to(stickyAtc, {
                                yPercent: 100,
                                y: '100%',
                                duration: .65,
                                ease: 'power4.out'
                            })
                        }
                    })

                }





            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/petimeline.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {

                var scope = jsScopeArray[i],
                    timeline = scope.querySelectorAll('.pe--timeline');

                timeline.forEach(function ($this) {

                    let pinTarget = $this.getAttribute('data-pin-target'),
                        trigger = pinTarget,
                        start = parseInt($this.dataset.startItem),
                        item = $this.querySelectorAll('.timeline--item');

                    item.forEach(function ($item, i) {
                        if (i < start - 1) {
                            gsap.set($item, {
                                opacity: 0.3
                            })
                        }
                        let sep = $item.querySelector('.item--point')

                        setTimeout(function () {

                            if (scope.classList.contains('border__anim__active')) {
                                let tl = gsap.timeline({
                                    repeat: -1,
                                })

                                tl.to(sep, {
                                    width: sep.offsetWidth * 2,
                                    height: sep.offsetHeight * 2,
                                    ease: 'none',
                                    duration: 1.5
                                })

                                tl.to(sep, {
                                    width: sep.offsetWidth * 1,
                                    height: sep.offsetHeight * 1,
                                    ease: 'none',
                                    duration: 1.5
                                })
                            }

                        }, gsap.utils.random(500, 1500))


                    })

                    gsap.set($this, {
                        x: (-1 * ($this.offsetWidth / $this.querySelectorAll('.timeline--item').length)) * (start - 1)
                    })

                    if (!pinTarget) {
                        pinTarget = true
                        trigger = $this
                    }



                    if (scope.classList.contains('nav__scroll')) {
                        gsap.to($this, {
                            x: -1 * ($this.offsetWidth - scope.offsetWidth),
                            ease: 'none',
                            scrollTrigger: {
                                trigger: trigger,
                                pin: pinTarget,
                                scrub: true,
                                start: 'center center',
                                end: 'bottom+=' + $this.getAttribute('data-speed') + ' top'
                            }
                        })
                    } else {
                        Draggable.create($this, {
                            type: 'x',
                            bounds: {
                                minX: -1 * ($this.offsetWidth - scope.offsetWidth),
                                maxX: 0
                            },
                            inertia: true
                        })
                    }

                })




            }
        });


        elementorFrontend.hooks.addAction('frontend/element_ready/peyithwidgets.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    account = scope.querySelector('.pe--yith--widget'),
                    wrap = scope.querySelector('.pe--yith--widget--wrap');

                if (scope.classList.contains('initialized')) {
                    return false;
                } else {
                    scope.classList.add('initialized');
                }

                function loadCompareTable() {
                    if (scope.querySelector('#yith-woocompare')) {
                        scope.querySelector('#yith-woocompare').remove();
                        scope.querySelector('h1').remove();
                    }

                    var compareTableElement = scope.querySelector('.zeyna--compare--table');
                    var compareTableUrl = compareTableElement.getAttribute('data-url');
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', compareTableUrl, true);

                    xhr.onload = function () {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            var tempDiv = document.createElement('div');
                            tempDiv.innerHTML = xhr.responseText;

                            var compareContent = tempDiv.querySelector('#yith-woocompare'),
                                compareTitle = tempDiv.querySelector('h1');

                            if (compareContent) {
                                compareTableElement.innerHTML = compareContent.outerHTML;
                                compareTableElement.appendChild(compareTitle);

                                // Remove button listener
                                addRemoveButtonListeners();
                            } else {
                                compareTableElement.innerHTML = '<p>YITH Compare table not found.</p>';
                            }
                        } else {
                            console.error('AJAX Error: ' + xhr.statusText);
                            compareTableElement.innerHTML = '<p>Compare table couldn\'t be loaded.</p>';
                        }
                    };

                    xhr.onerror = function () {
                        console.error('AJAX Error: ' + xhr.statusText);
                        compareTableElement.innerHTML = '<p>An error occurred, please try again later.</p>';
                    };

                    xhr.send();
                }

                function addRemoveButtonListeners() {
                    var removeButtons = scope.querySelectorAll('.zeyna--compare--table a');
                    removeButtons.forEach(function (button) {
                        button.setAttribute('data-barba-prevent', 'all')
                        button.addEventListener('click', function (event) {
                            event.preventDefault();
                            setTimeout(() => {
                                let productId = button.getAttribute('data-product_id');
                                scope.querySelectorAll('.product_' + productId).forEach(item => item.remove());
                            }, 1000);

                        });
                    });
                }

                if (scope.querySelector('.zeyna--compare--table')) {
                    loadCompareTable();
                }

                if (scope.querySelector('.pe--yith--popup')) {
                    pePopup(scope, wrap)
                }
            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/peclock.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    clock = scope.querySelector('.pe--clock');

                const display = clock.querySelector('.clock-display');
                const format = clock.dataset.format;
                const mode = clock.dataset.mode;
                const timezone = clock.dataset.timezone;

                function updateClock() {
                    let now;

                    if (mode === 'timezone' && timezone) {
                        // Format time in specific timezone
                        now = new Date();
                        display.textContent = new Intl.DateTimeFormat('en-US', {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            hour12: format === '12',
                            timeZone: timezone
                        }).format(now);
                    } else {
                        // Local time (user's device)
                        now = new Date();
                        let hours = now.getHours();
                        let minutes = now.getMinutes();
                        let seconds = now.getSeconds();
                        let ampm = '';

                        if (format === '12') {
                            ampm = hours >= 12 ? 'PM' : 'AM';
                            hours = hours % 12 || 12;
                        }

                        display.textContent =
                            String(hours).padStart(2, '0') + ':' +
                            String(minutes).padStart(2, '0') + ':' +
                            String(seconds).padStart(2, '0') +
                            (format === '12' ? ' ' + ampm : '');
                    }
                }

                updateClock();
                setManagedInterval(updateClock, 1000); // OPTIMIZED: Managed interval


            }

        })


        elementorFrontend.hooks.addAction('frontend/element_ready/peaccount.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    account = scope.querySelector('.pe--account'),
                    wrap = scope.querySelector('.pe--account--wrap');

                if (scope.querySelector('.pe--account--pop--button') && !account.classList.contains('is--logged--in')) {

                    pePopup(scope, wrap);

                    function handleMobile(open) {

                        let headerElements = document.querySelector('.site-header').querySelectorAll('.elementor-element:not(.elementor-widget-peaccount , .e-con , .elementor-widget-pesitenavigation)'),
                            toggle = document.querySelector('.menu--toggle--wrap');

                        clearProps('.site-header');

                        if (open) {
                            gsap.to([headerElements, toggle], {
                                opacity: 0,
                                pointerEvents: 'none'
                            })

                            if (parents(scope, '.elementor-widget-pesitenavigation').length) {

                                let navWidget = parents(scope, '.elementor-widget-pesitenavigation')[0],
                                    navParent = parents(navWidget, '.e-con')[0];

                                gsap.set(navParent, {
                                    zIndex: 999999999999
                                })
                            }

                        } else {

                            gsap.to([headerElements, toggle], {
                                opacity: 1,
                                pointerEvents: 'all',
                                onComplete: () => {
                                    gsap.set([headerElements, toggle], {
                                        clearProps: 'all'
                                    })
                                }
                            })

                            if (parents(scope, '.elementor-widget-pesitenavigation').length) {
                                let navWidget = parents(scope, '.elementor-widget-pesitenavigation')[0],
                                    navParent = parents(navWidget, '.e-con')[0];

                                gsap.set(navParent, {
                                    clearProps: 'all'
                                })
                            }


                        }

                    }

                }

            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/peproductsarchive.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i];

                if (scope.querySelector('.wishlist--empty')) {
                    return false;
                }

                var grid = scope.querySelector('.archive-products-section'),
                    items = grid.querySelectorAll('.zeyna--single--product');



                if (grid && grid.querySelector('.products--grid--switcher')) {

                    peSwitcher(scope, scope.querySelector('.products--grid--switcher'), scope.querySelector('.zeyna--products-grid '), items);

                }

                if (scope.querySelector('.pe--product--filters')) {

                    let filters = scope.querySelector('.pe--product--filters'),
                        wrapper = scope.querySelector('.filters--wrapper');

                    if (filters.classList.contains('filters--dropdown')) {

                        let button = scope.querySelector('.filters--button');

                        button.addEventListener('click', () => {



                            button.classList.toggle('active')

                            let state = Flip.getState(wrapper, {
                                props: ['border', 'margin', 'padding']
                            });

                            wrapper.classList.contains('active') ? wrapper.classList.remove('active') : wrapper.classList.add('active');

                            Flip.from(state, {
                                duration: 1,
                                ease: 'expo.inOut',
                                absolute: false,
                                absoluteOnLeave: false
                            })
                        })

                    } else if (filters.classList.contains('filters--popup')) {
                        pePopup(scope, scope);

                        if (scope.classList.contains('filters--button--fixed')) {

                            let button = scope.querySelector('.filters--button');
                            //     calc = window.innerHeight - scope.querySelector('.zeyna--products-grid').getBoundingClientRect().top - 45;

                            // gsap.set(button, {
                            //     top: calc
                            // })

                            ScrollTrigger.create({
                                trigger: scope,
                                start: 'top center',
                                end: 'bottom center',
                                onEnter: () => {
                                    button.classList.add('fb--active')
                                },
                                onEnterBack: () => {
                                    button.classList.add('fb--active')
                                },
                                onLeave: () => {
                                    button.classList.remove('fb--active')
                                },
                                onLeaveBack: () => {
                                    button.classList.remove('fb--active')
                                }
                            })

                        }
                    }

                }

                if (scope.querySelector('.filter-price-range')) {

                    let wrap = document.querySelector('.filter-price-range'),
                        labelMin = wrap.querySelector('.label--price--min span'),
                        labelMax = wrap.querySelector('.label--price--max span');

                    const rangeMin = document.getElementById('range_min');
                    const rangeMax = document.getElementById('range_max');
                    const minPrice = document.getElementById('min_price');
                    const maxPrice = document.getElementById('max_price');


                    rangeMin.addEventListener('input', updateRange);
                    rangeMax.addEventListener('input', updateRange);

                    function updateRange() {

                        if (parseInt(rangeMin.value) > parseInt(rangeMax.value)) {
                            rangeMin.value = rangeMax.value;
                        }
                        minPrice.value = rangeMin.value;
                        maxPrice.value = rangeMax.value;

                        labelMin.textContent = rangeMin.value;
                        labelMax.textContent = rangeMax.value;
                    }

                    minPrice.addEventListener('input', function () {
                        rangeMin.value = this.value;
                    });

                    maxPrice.addEventListener('input', function () {
                        rangeMax.value = this.value;
                    });

                }

                if (scope.querySelector('.zeyna--products--pagination')) {

                    var loadMore = scope.querySelector('.zeyna--products--load--more'),
                        clicks = 0;

                    function productsLoadMore(e, loadMore) {
                        if (document.body.classList.contains('e-preview--show-hidden-elements')) {
                            return false;
                        }

                        loadMore.classList.add('loading');
                        clicks++;

                        if (e) e.preventDefault();

                        document.documentElement.classList.add('loading');
                        loadMore.classList.add('loading');


                        $.ajax({
                            url: woocommerce_params.ajax_url,
                            type: "POST",
                            data: {
                                action: "pe_get_products",
                                offset: clicks,
                                args: grid.dataset.queryArgs,
                                settings: scope.dataset.settings
                            },
                            dataType: "json",
                            success: function (response) {
                                if (response.success) {

                                    let productsHtml = response.data.products;
                                    var wrapper = scope.querySelector('.zeyna--products-grid');

                                    if (productsHtml.length > 0) {

                                        let tl = gsap.timeline({
                                            onComplete: () => {
                                                ScrollTrigger.update();
                                                ScrollTrigger.getById('loadInfnite') ? ScrollTrigger.getById('loadInfnite').refresh() : '';
                                            }
                                        });

                                        productsHtml.forEach((productHtml, i) => {
                                            let tempDiv = document.createElement("div");
                                            tempDiv.innerHTML = productHtml;

                                            let productElement = tempDiv.firstElementChild;
                                            wrapper.appendChild(productElement);

                                            if (scope.querySelector('.archive-products-section').classList.contains('archive--masonry')) {
                                                let masonry = Masonry.data(scope.querySelector('.zeyna--products-grid'));

                                                masonry.appended(productElement);
                                                setTimeout(() => {
                                                    masonry.layout();
                                                    ScrollTrigger.update();
                                                    ScrollTrigger.getById('loadInfnite') ? ScrollTrigger.getById('loadInfnite').refresh() : '';
                                                }, 10);

                                                loadMore.classList.remove('loading');
                                            } else {

                                                tl.fromTo(productElement, {
                                                    opacity: 0,
                                                    yPercent: 100
                                                }, {
                                                    opacity: 1,
                                                    yPercent: 0,
                                                    duration: .75,
                                                    ease: 'expo.out',
                                                    onComplete: () => {
                                                        clearProps(productElement);
                                                        loadMore.classList.remove('loading');

                                                        if (productElement.querySelector('.pe-video')) {
                                                            let videos = productElement.querySelectorAll('.pe-video');

                                                            for (var i = 0; i < videos.length; i++) {
                                                                new peVideoPlayer(videos[i]);
                                                            }
                                                        }
                                                    }
                                                }, i * 0.15);
                                            }

                                            if (scope.querySelector('.archive-products-section').dataset.maxPages == clicks + 1) {
                                                loadMore.classList.add('hidden');
                                                ScrollTrigger.getById('loadInfnite') ? ScrollTrigger.getById('loadInfnite').kill(true) : '';
                                            }

                                            document.documentElement.classList.remove('loading');
                                            setTimeout(() => {
                                                ScrollTrigger.getById('fsb--' + scope.dataset.id,) ? ScrollTrigger.getById('fsb--' + scope.dataset.id).refresh(true) : '';
                                            }, 1000);

                                        });


                                    } else {
                                        console.log("No more products to load.");
                                    }
                                }
                            },
                            error: function (response) {
                                console.log(response.error);
                            }
                        });


                    }


                    if (loadMore) {
                        loadMore.addEventListener('click', (e) => {
                            productsLoadMore(e, loadMore);
                        })
                    }

                    let products = scope.querySelector('.archive-products-section');
                    if (products && products.classList.contains('pag_infinite-scroll')) {

                        let offset = 0;

                        ScrollTrigger.create({
                            trigger: products,
                            id: 'loadInfnite',
                            start: 'bottom bottom',
                            end: 'bottom top',
                            onEnter: () => {
                                offset++;
                                productsLoadMore(false, document.querySelector('.zeyna--products--infinite--scroll'))

                            }
                        })

                    }

                }

                scope.querySelectorAll('.pe--product--filters input , .products--sorting select').forEach(function (input) {

                    input.addEventListener('change', function () {
                        var filters = {};

                        let parentCats = parents(input, '.zeyna--products--filter--cats');

                        if (parentCats.length) {

                            parentCats[0].querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
                                if (checkbox !== input) {
                                    checkbox.checked = false;
                                }
                            });
                        }

                        let filterparents = scope.querySelectorAll('.pe--product--filters');
                        filterparents.forEach(parent => {
                            parent.classList.add('loading');
                        });

                        var sortingSelect = scope.querySelector('.products--sorting select');
                        if (sortingSelect) {
                            filters['orderby'] = sortingSelect.value;
                        }

                        scope.querySelectorAll('.pe--product--filters input:checked').forEach(function (checkedInput) {
                            var filterName = checkedInput.getAttribute('name');

                            if (!filters[filterName]) {
                                filters[filterName] = [];
                            }

                            filters[filterName].push(checkedInput.value);

                            if (checkedInput.value === 'all') {
                                filters['product_cat'] = [];
                            }

                            if (input.classList.contains('check--sale')) {
                                filters['sale_products'] = checkedInput.value;
                            }

                        });

                        if (scope.querySelector('.filter-price-range')) {
                            var minPrice = scope.querySelector('#min_price').value;
                            var maxPrice = scope.querySelector('#max_price').value;

                            if (minPrice) {
                                filters['min_price'] = minPrice;
                            }
                            if (maxPrice) {
                                filters['max_price'] = maxPrice;
                            }
                        }

                        var queryParams = [];
                        for (var key in filters) {
                            if (filters.hasOwnProperty(key)) {
                                if (Array.isArray(filters[key])) {
                                    filters[key].forEach(function (value) {
                                        queryParams.push(encodeURIComponent(key) + '[]=' + encodeURIComponent(value));
                                    });
                                } else {
                                    queryParams.push(encodeURIComponent(key) + '=' + encodeURIComponent(filters[key]));
                                }
                            }
                        }

                        $.ajax({
                            url: woocommerce_params.ajax_url,
                            type: "POST",
                            data: {
                                action: "pe_get_products",
                                args: grid.dataset.queryArgs,
                                settings: scope.dataset.settings,
                                filters: filters
                            },
                            dataType: "json",
                            success: function (response) {
                                if (response.success) {



                                    let productsHtml = response.data.products;
                                    var productGrid = scope.querySelector('.zeyna--products-grid');
                                    productGrid.querySelectorAll('.zeyna--single--product').forEach(pr => pr.remove());

                                    let filterparents = scope.querySelectorAll('.pe--product--filters');
                                    filterparents.forEach(parent => {
                                        parent.classList.remove('loading');
                                    });

                                    productsHtml.forEach(function (newProduct) {
                                        let tempDiv = document.createElement("div");
                                        tempDiv.innerHTML = newProduct;

                                        let productElement = tempDiv.firstElementChild;
                                        productGrid.appendChild(productElement);

                                        setTimeout(() => {
                                            if (scope.querySelector('.archive-products-section').classList.contains('archive--masonry')) {
                                                var elem = scope.querySelector('.zeyna--products-grid');
                                                var msnry = new Masonry(elem, {
                                                    itemSelector: '.zeyna--single--product',
                                                    columnWidth: '.zeyna--products--masonry--sizer',
                                                    gutter: '.zeyna--products--masonry--gutter',
                                                    percentPosition: true,
                                                });
                                            }

                                            if (scope.querySelector('.product--archive--gallery')) {

                                                let swiperCont = scope.querySelectorAll('.product--archive--gallery');

                                                swiperCont.forEach(cont => {

                                                    var productArchiveGallery = new Swiper(cont, {
                                                        slidesPerView: 1,
                                                        speed: 750,
                                                        navigation: {
                                                            nextEl: cont.querySelector('.pag--next'),
                                                            prevEl: cont.querySelector('.pag--prev'),
                                                        },
                                                    });

                                                });

                                            }

                                        }, 10);


                                    });

                                }
                            },
                            error: function (response) {
                                console.log(response.error);
                            }
                        });

                    });

                });

                if (grid.classList.contains('archive--masonry')) {

                    var elem = scope.querySelector('.zeyna--products-grid');

                    var msnry = new Masonry(elem, {
                        itemSelector: '.zeyna--single--product',
                        columnWidth: '.zeyna--products--masonry--sizer',
                        gutter: '.zeyna--products--masonry--gutter',
                        percentPosition: true,
                    });

                    imagesLoaded(elem, function (instance) {
                        msnry.layout();
                    })


                }

                if (scope.querySelector('.zeyna--products--filter--cats')) {

                    let filterCats = scope.querySelector('.zeyna--products--filter--cats'),
                        catsWidth = filterCats.getBoundingClientRect().width;

                    if (filterCats.querySelector('.filter--cats--images--wrapper')) {

                        let imageCatsWrap = filterCats.querySelector('.filter--cats--images--wrapper');

                        if (catsWidth < imageCatsWrap.getBoundingClientRect().width) {

                            Draggable.create(imageCatsWrap, {
                                type: 'x',
                                bounds: filterCats,
                                lockAxis: true,
                                dragResistance: 0.5,
                                inertia: true,
                                allowContextMenu: true
                            });

                        }

                    }

                    matchMedia.add({
                        isMobile: "(max-width: 550px)"

                    }, (context) => {

                        let {
                            isMobile
                        } = context.conditions;

                        if (!filterCats.querySelector('.filter--cats--images--wrapper')) {

                            Draggable.create(filterCats, {
                                id: scope.dataset.id,
                                type: 'x',
                                bounds: {
                                    minX: 0,
                                    maxX: -catsWidth + (document.body.clientWidth / 2),
                                },
                                lockAxis: true,
                                dragResistance: 0.5,
                                inertia: true,
                                allowContextMenu: true
                            });

                        }

                    });

                }

                if (scope.classList.contains('filters--sidebar--pin') && scope.querySelector('.filters--sidebar')) {

                    let sidebar = scope.querySelector('.filters--sidebar'),
                        gridWrapper = scope.querySelector('.zeyna--products-grid'),
                        startOff = siteHeader[0].classList.contains('header--fixed') ? document.querySelector('.site-header').getBoundingClientRect().height : 25;

                    var filtersSidePin = ScrollTrigger.create({
                        trigger: gridWrapper,
                        pin: sidebar,
                        id: 'fsb--' + scope.dataset.id,
                        start: 'top top+=' + startOff,
                        end: 'bottom bottom',
                        pinSpacing: false
                    })

                    matchMedia.add({
                        isMobile: "(max-width: 550px)"

                    }, (context) => {

                        let {
                            isMobile
                        } = context.conditions;

                        filtersSidePin.kill(true);

                    });

                }

                if (scope.classList.contains('filters--accordion')) {

                    let wrapper = scope.querySelector('.filters--wrapper'),
                        titles = wrapper.querySelectorAll('.terms-list-title , .filter--label');

                    titles.forEach(title => {

                        let content, parent;
                        if (title.classList.contains('terms-list-title')) {
                            parent = parents(title, '.terms-list')[0],
                                content = parent.querySelector('.terms--terms')
                        } else {
                            parent = parents(title, '.filters--item')[0],
                                content = parent.querySelector('.terms--terms')
                        }

                        if (content) {

                            title.addEventListener('click', (title) => {

                                if (parent.classList.contains('active')) {

                                    var contentState = Flip.getState(content, {
                                        props: ['padding']
                                    });
                                    parent.classList.remove('active');

                                    Flip.from(contentState, {
                                        duration: .75,
                                        ease: 'expo.inOut',
                                        absolute: false,
                                        absoluteOnLeave: false,
                                    })


                                } else {

                                    var contentState = Flip.getState(content, {
                                        props: ['padding']
                                    });

                                    parent.classList.add('active');

                                    Flip.from(contentState, {
                                        duration: .75,
                                        ease: 'expo.inOut',
                                        absolute: false,
                                        absoluteOnLeave: false,
                                    })

                                }

                            })


                        }





                    })


                }

                if (scope.querySelector('.product--archive--gallery')) {

                    let swiperCont = scope.querySelectorAll('.product--archive--gallery');

                    swiperCont.forEach(cont => {

                        var productArchiveGallery = new Swiper(cont, {
                            slidesPerView: 1,
                            speed: 750,
                            navigation: {
                                nextEl: cont.querySelector('.pag--next'),
                                prevEl: cont.querySelector('.pag--prev'),
                            },
                        });

                    });

                }

                if (scope.querySelector('.filters--wrapper--inner')) {

                    if (!zeynaLenis) {
                        // OPTIMIZED: Filters Popup Lenis - GSAP ticker
                        const popFiltersLenis = new Lenis({
                            wrapper: scope.querySelector('.filters--wrapper--inner'),
                            smooth: true,
                            smoothTouch: false
                        });

                        gsap.ticker.add((time) => {
                            popFiltersLenis.raf(time * 1000);
                        });

                    }

                }



            }
        });


        elementorFrontend.hooks.addAction('frontend/element_ready/pe3drenderer.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i];

                const peRenderer = scope.querySelector('.pe--3d--renderer');
                let container = scope.querySelector('.renderer--container')

                const anim = new ThreeAnimation(container, peRenderer);

            }

        })

        elementorFrontend.hooks.addAction('frontend/element_ready/pesplineloader.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i];

                const peSpline = document.querySelector('.pe--spline--loader');
                let container = scope.querySelector('.spline--canvas');

                if (gsap.getById('pageLoader')) {
                    document.addEventListener('pageLoaderDone', function () {
                        new SplineLoader(container, peSpline);
                    })
                } else {
                    new SplineLoader(container, peSpline);
                }


            }

        })



        elementorFrontend.hooks.addAction('frontend/element_ready/pelottie.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    playerElement = scope.querySelector('dotlottie-player'),
                    player = playerElement.getLottie(),
                    src = playerElement.getAttribute('src');

                if (parents(scope, '.e-n-tabs').length) {

                    let tabs = parents(scope, '.e-n-tabs')[0],
                        parentContent = parents(scope, 'div[role=tabpanel]')[0];

                    const match = parentContent.id.match(/(\d+)$/);
                    if (match) {
                        const tabbId = parseInt(match[1], 10);
                        let title = document.querySelector('#e-n-tab-title-' + tabbId);
                        title.addEventListener('click', () => {
                            playerElement.play();
                        })
                    }
                }

                ScrollTrigger.addEventListener("refreshInit", () => {

                    playerElement.load(src);

                    setTimeout(() => {
                        if (scope.classList.contains('autoplay')) {
                            ScrollTrigger.create({
                                trigger: scope,
                                start: 'top bottom',
                                onEnter: () => {
                                    playerElement.play();
                                },
                                onEnterBack: () => {
                                    playerElement.play();
                                },
                                onLeave: () => {
                                    playerElement.pause();
                                },
                                onLeaveBack: () => {
                                    playerElement.pause();
                                },

                            })

                        }
                    }, 500);
                })
            }

        })


        elementorFrontend.hooks.addAction('frontend/element_ready/peforms.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i],
                    form = scope.querySelector('.pe--form'),
                    inputs = form.querySelectorAll('input , textarea');

                if (scope.querySelector('.zeyna--form--submit--icon') && form.querySelector('.wpcf7-submit')) {
                    let p = parents(form.querySelector('.wpcf7-submit'), 'p')[0],
                        icon = scope.querySelector('.zeyna--form--submit--icon'),
                        submit = form.querySelector('.wpcf7-submit');

                    p.insertBefore(icon, submit)

                }

                inputs.forEach(input => {

                    input.addEventListener('input', function () {
                        if (input.value.trim() !== '') {
                            input.classList.add('has-content');
                            input.classList.remove('no-content');
                        } else {
                            input.classList.add('no-content');
                            input.classList.remove('has-content');
                        }
                    });

                })

                if (scope.querySelector('.wpcf7-form-control-wrap:has(.wpcf7-select)')) {

                    scope.querySelectorAll('.wpcf7-form-control-wrap:has(.wpcf7-select)').forEach(select => {

                        const selectChange = new Event('change', { bubbles: true });

                        var defaultSelect = select.querySelector('select'),
                            options = select.querySelectorAll('option'),
                            selectWrap = document.createElement('DIV'),
                            selectedWrap = document.createElement('DIV');
                        selectWrap.classList.add('pe--select--wrap');
                        selectedWrap.classList.add('pe--selected--wrap');

                        selectedWrap.innerHTML = defaultSelect.value;

                        options.forEach(option => {
                            selectWrap.innerHTML += '<div class="pe--select--option" data-option="' + option.innerHTML + '">' + option.innerHTML + '</div>';
                        })

                        select.appendChild(selectedWrap);
                        select.appendChild(selectWrap);

                        select.querySelectorAll('.pe--select--option').forEach(selected => {

                            selected.addEventListener('click', (sctd) => {
                                select.classList.toggle('active');
                                selectedWrap.innerHTML = selected.dataset.option;
                                defaultSelect.value = selected.dataset.option;
                                defaultSelect.dispatchEvent(selectChange);

                            })
                        });

                        selectedWrap.addEventListener('click', () => {

                            select.classList.toggle('active');

                        })


                    })


                }

                if (scope.querySelector('.form--custom')) {
                    let form = scope.querySelector('.pe--form');
                    new peForm(form);
                }

            }

        })

        elementorFrontend.hooks.addAction('frontend/element_ready/pesingleproduct.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();
            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i];
                //     product = scope.querySelector('.zeyna--single--product'),
                //     button = scope.querySelector('.single_add_to_cart_button'),
                //     variationWrap = scope.querySelector('.single_variation_wrap'),
                //     table = scope.querySelector('table.variations'),
                //     form = scope.querySelector('.variations_form');

                // if (product.classList.contains('product-type-variable')) {

                //     setTimeout(() => {
                //         if (button.classList.contains('wc-variation-selection-needed')) {

                //             variationWrap.addEventListener('click', () => {

                //                 if (!variationWrap.classList.contains('active')) {
                //                     variationWrap.classList.add('active');
                //                     form.classList.add('variations--active');

                //                 } else {

                //                     variationWrap.classList.remove('active');
                //                     form.classList.remove('variations--active');


                //                 }

                //             })

                //         }
                //     }, 100);

                // }

                if (scope.querySelector('.product--archive--gallery')) {

                    let swiperCont = scope.querySelectorAll('.product--archive--gallery');

                    swiperCont.forEach(cont => {

                        var productArchiveGallery = new Swiper(cont, {
                            slidesPerView: 1,
                            speed: 750,
                            navigation: {
                                nextEl: cont.querySelector('.pag--next'),
                                prevEl: cont.querySelector('.pag--prev'),
                            },
                        });

                    });

                }

            }

        })

        elementorFrontend.hooks.addAction('frontend/element_ready/productmedia.default', function ($scope, $) {
            var jsScopeArray = $scope.toArray();

            for (var i = 0; i < jsScopeArray.length; i++) {
                var scope = jsScopeArray[i];

                if (!scope.querySelector('.product--gallery')) {
                    return false;
                }

                var gallery = scope.querySelector('.product--gallery'),
                    wrapper = gallery.querySelector('.product--gallery--wrapper'),
                    images = wrapper.querySelectorAll('.product--gallery--image'),
                    id = wrapper.dataset.id ? wrapper.dataset.id : scope.dataset.id,
                    trigger = wrapper.dataset.pinTarget ? wrapper.dataset.pinTarget : scope,
                    speed = wrapper.dataset.speed;


                images.forEach(image => {
                    if (image.querySelector('.img--zoom')) {
                        let zoomImage = image.querySelector('.img--zoom');

                        if (zoomImage.classList.contains('zoom-outer')) {
                            let zoomWrap = gallery.querySelector('.product--image--zoom--wrap');
                            zoomWrap.appendChild(zoomImage);
                        }

                        image.addEventListener('mouseenter', () => {
                            gsap.to(zoomImage, {
                                opacity: 1,
                                duration: 0.3,
                            });

                            image.classList.add('zoom--active');
                            gallery.querySelector('.product--image--zoom--wrap') ? gallery.querySelector('.product--image--zoom--wrap').classList.add('active') : '';
                        });

                        image.addEventListener('mousemove', (e) => {
                            const rect = image.getBoundingClientRect();
                            const zoomWidth = zoomImage.offsetWidth;
                            const zoomHeight = zoomImage.offsetHeight;

                            const xPercent = ((e.clientX - rect.left) / rect.width) * 100;
                            const yPercent = ((e.clientY - rect.top) / rect.height) * 100;

                            const offsetX = (xPercent - 50) * (zoomWidth / rect.width);
                            const offsetY = (yPercent - 50) * (zoomHeight / rect.height);

                            if (zoomImage.classList.contains('zoom-outer')) {

                                let zoomWrap = gallery.querySelector('.product--image--zoom--wrap'),
                                    follower = image.querySelector('.outer--zoom--follower');

                                gsap.to(zoomWrap, {
                                    left: rect.left + rect.width + 10,
                                    top: rect.top + 10,
                                    xPercent: rect.left > (window.outerWidth / 2) ? -200 : 0
                                });

                                gsap.to(follower, {
                                    x: (e.clientX - rect.left) - 50,
                                    y: (e.clientY - rect.top) - 50
                                });

                            }

                            gsap.to(zoomImage, {
                                x: -offsetX,
                                y: -offsetY,
                                duration: 0.1,
                                ease: "power2.out",
                            });
                        });

                        image.addEventListener('mouseleave', () => {

                            image.classList.remove('zoom--active');
                            gallery.querySelector('.product--image--zoom--wrap') ? gallery.querySelector('.product--image--zoom--wrap').classList.remove('active') : '';

                            gsap.to(zoomImage, {
                                opacity: 0,
                                x: 0,
                                y: 0,
                                duration: 0.3,
                            });
                        });

                    }
                });


                updateActiveCarouselItem(wrapper, '.product--gallery--image')

                if (gallery.classList.contains('gallery--carousel') && wrapper.classList.contains('cr--drag')) {
                    wrapper.classList.add(id);

                    Draggable.create(wrapper, {
                        id: id,
                        type: scope.classList.contains('carousel--vertical') ? 'y' : 'x',
                        bounds: {
                            minX: 0,
                            maxX: -wrapper.getBoundingClientRect().width + document.body.clientWidth,
                            minY: 0,
                            maxY: -wrapper.getBoundingClientRect().height + scope.getBoundingClientRect().height,
                        },
                        onDrag: () => {
                            updateActiveCarouselItem(wrapper, '.product--gallery--image')
                        },
                        lockAxis: true,
                        dragResistance: 0.5,
                        inertia: true,
                        allowContextMenu: true
                    });

                } else if (gallery.classList.contains('gallery--carousel') && wrapper.classList.contains
                    ('cr--scroll')) {

                    wrapper.classList.add(id);


                    var crScroll = gsap.to(wrapper, {
                        x: scope.classList.contains('carousel--horizontal') ? -wrapper.getBoundingClientRect().width + document.body.clientWidth : 0,
                        y: scope.classList.contains('carousel--vertical') ? -wrapper.getBoundingClientRect().height + scope.getBoundingClientRect().height : 0,
                        scrollTrigger: {
                            id: id,
                            trigger: trigger,
                            scrub: 1.2,
                            pin: trigger,
                            ease: "elastic.out(1, 0.3)",
                            start: 'top top',
                            end: 'bottom+=' + speed + ' bottom',
                            pinSpacing: 'padding',
                            onEnter: () => isPinnng(trigger, true),
                            onEnterBack: () => isPinnng(trigger, true),
                            onLeave: () => isPinnng(trigger, false),
                            onLeaveBack: () => isPinnng(trigger, false),
                            onUpdate: () => {
                                updateActiveCarouselItem(wrapper, '.product--gallery--image');
                            }
                        }
                    })

                    matchMedia.add({
                        isMobile: "(max-width: 550px)"

                    }, (context) => {

                        let {
                            isMobile
                        } = context.conditions;

                        crScroll.scrollTrigger.kill(true);

                        Draggable.create(wrapper, {
                            id: id,
                            type: 'x',
                            bounds: {
                                minX: 0,
                                maxX: -wrapper.getBoundingClientRect().width + document.body.clientWidth,
                                minY: 0,
                                maxY: -wrapper.getBoundingClientRect().height + scope.getBoundingClientRect().height,
                            },
                            lockAxis: true,
                            dragResistance: 0.5,
                            inertia: true,
                            allowContextMenu: true,
                            onDrag: () => {
                                updateActiveCarouselItem(wrapper, '.product--gallery--image')
                            },
                        });

                    });

                } else if (gallery.classList.contains('gallery--slideshow')) {
                    gallery.classList.add(id);
                    var interleaveOffset = 0.5;
                    var productSlider = new Swiper(gallery, {
                        slidesPerView: 1,
                        speed: 1250,
                        direction: scope.classList.contains('swiper--vertical') ? 'vertical' : 'horizontal',
                        loop: scope.classList.contains('swiper_loop') ? true : false,
                        parallax: scope.classList.contains('swiper_parallax') ? true : false,
                        mousewheel: scope.classList.contains('swiper_wheel') ? { invert: false } : false,
                        watchSlideProgress: true,
                        navigation: {
                            nextEl: '.next-for-' + id,
                            prevEl: '.prev-for-' + id,
                        },
                        on: {
                            progress: function () {
                                if (scope.classList.contains('swiper_parallax')) {
                                    let swiper = this;
                                    for (let i = 0; i < swiper.slides.length; i++) {
                                        let slideProgress = swiper.slides[i].progress,
                                            innerOffset = swiper.height * interleaveOffset,
                                            innerTranslate = slideProgress * innerOffset;

                                        if (scope.classList.contains('swiper--vertical')) {
                                            swiper.slides[i].querySelector(".slide-bgimg").style.transform =
                                                "translateY(" + innerTranslate + "px)";
                                        } else {
                                            swiper.slides[i].querySelector(".slide-bgimg").style.transform =
                                                "translateX(" + innerTranslate + "px)";
                                        }




                                    }
                                }
                            },
                            setTransition: function (speed) {
                                if (scope.classList.contains('swiper_parallax')) {

                                    let swiper = this;
                                    for (let i = 0; i < swiper.slides.length; i++) {
                                        swiper.slides[i].style.transition = speed + "ms";
                                        swiper.slides[i].querySelector(".slide-bgimg").style.transition = 1250 + "ms";
                                    }
                                }
                            },
                            slideChangeTransitionEnd: () => {
                                if (scope.querySelector('.gallery--slideshow--thumbnails')) {
                                    scope.querySelector('.gs--thumb.active').classList.remove('active');
                                    scope.querySelector('.gs_thumb_' + productSlider.activeIndex).classList.add('active');

                                }

                            }
                        }
                    });

                    if (scope.querySelector('.gallery--slideshow--thumbnails')) {

                        let thumbs = scope.querySelectorAll('.gs--thumb');

                        scope.querySelectorAll('.gs--thumb')[0].classList.add('active');

                        thumbs.forEach(thumb => {

                            thumb.addEventListener('click', () => {
                                scope.querySelector('.gs--thumb.active').classList.remove('active');
                                thumb.classList.add('active');

                                productSlider.slideTo(thumb.dataset.id, 1250)
                            })

                        })
                    }



                }

                if (scope.classList.contains('images--lightbox--yes')) {
                    zeynaLighbox(gallery, wrapper, images);
                }


            }
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/pesccontrols.default', function ($scope, $) {

            setTimeout(function () {

                var jsScopeArray = $scope.toArray();

                for (var i = 0; i < jsScopeArray.length; i++) {

                    var scope = jsScopeArray[i],
                        control = scope.querySelector('.pe--sc--controls'),
                        id = control.dataset.id,
                        target = document.querySelector('.' + id);

                    if (target.classList.contains('swiper-container')) {

                        let swiper = target.swiper;


                        let speed = swiper.passedParams.speed;



                        swiper.update();

                        if (scope.querySelector('.sc--navigation')) {

                            let prev = scope.querySelector('.sc--prev'),
                                next = scope.querySelector('.sc--next');

                            prev.addEventListener('click', () => {
                                swiper.slidePrev(speed, true);
                            })

                            next.addEventListener('click', () => {
                                swiper.slideNext(speed, true)
                            })

                        } else if (scope.querySelector('.sc--fraction')) {

                            function unitize(number) {
                                return number.toString().padStart(2, '0');
                            }

                            let curr = scope.querySelector('.sc--current'),
                                tot = scope.querySelector('.sc--total'),
                                length = target.querySelectorAll('.swiper-slide').length;

                            curr.innerHTML = control.classList.contains('unitaze--numbers') ? unitize(1) : 1;
                            tot.innerHTML = control.classList.contains('unitaze--numbers') ? unitize(length) : length;

                            swiper.on('slideChange', function () {
                                curr.innerHTML = control.classList.contains('unitaze--numbers') ? unitize(swiper.activeIndex + 1) : swiper.activeIndex + 1;

                            });

                        }

                    } else {

                        imagesLoaded(scope, function (instance) {
                            setTimeout(() => {

                                if (target.querySelectorAll('.cr--item').length) {

                                    var items = target.querySelectorAll('.cr--item'),
                                        vars = {
                                            progress: '',
                                            current: '',
                                            total: items.length,
                                            width: items[0].offsetWidth
                                        };

                                    control.classList.add('sc--id__' + id);


                                    items.forEach((item, c) => {
                                        c++;
                                        item.setAttribute('data-cr', c);

                                    });

                                    if (scope.querySelector('.sc--fraction')) {

                                        if (control.classList.contains('unitaze--numbers')) {
                                            scope.querySelector('.sc--total').innerHTML = vars.total < 10 ? '0' + vars.total : vars.total;
                                        } else {
                                            scope.querySelector('.sc--total').innerHTML = vars.total;
                                        }

                                    }

                                    function getCurrentItem() {

                                        let crValues = [];

                                        items.forEach(item => {

                                            if (item.getBoundingClientRect().x < (document.body.clientWidth * 0.75)) {
                                                crValues.push(parseInt(item.dataset.cr, 10));
                                            }

                                        });

                                        if (crValues.length > 0) {
                                            let maxCrValue = Math.max(...crValues);
                                            if (control.classList.contains('unitaze--numbers')) {

                                                vars.current = maxCrValue < 10 ? '0' + maxCrValue : maxCrValue;
                                            } else {
                                                vars.current = maxCrValue;
                                            }

                                        }

                                        scope.querySelector('.sc--current') ? scope.querySelector('.sc--current').innerHTML = vars.current : '';


                                    }

                                    function updateOthers() {

                                        let allControls = document.querySelectorAll('.sc--id__' + id);

                                        if (allControls.length > 1) {

                                            allControls.forEach(control => {

                                                let fraction = control.querySelector('.sc--fraction');

                                                if (fraction) {

                                                    let current = fraction.querySelector('.sc--current'),
                                                        total = fraction.querySelector('.sc--total');

                                                    getCurrentItem()

                                                    if (control.classList.contains('unitaze--numbers')) {

                                                        current.innerHTML = vars.current < 10 ? '0' + vars.current : vars.current;
                                                        total.innerHTML = vars.total < 10 ? '0' + vars.total : vars.total;

                                                    } else {
                                                        current.innerHTML = vars.current;
                                                        total.innerHTML = vars.total;

                                                    }

                                                }

                                            })

                                        }

                                    }

                                    if (target.classList.contains('cr--scroll')) {

                                        let tween = gsap.getById(target.dataset.id);

                                        if (tween) {

                                            tween.eventCallback('onUpdate', () => {

                                                vars.progress = tween.progress() * 100;

                                                let crValues = [];

                                                items.forEach(item => {

                                                    if (item.getBoundingClientRect().x < (document.body.clientWidth * 0.75)) {

                                                        crValues.push(parseInt(item.dataset.cr, 10));
                                                    }

                                                });

                                                if (crValues.length > 0) {
                                                    let maxCrValue = Math.max(...crValues);
                                                    vars.current = maxCrValue;

                                                }

                                                if (scope.querySelector('.sc--fraction')) {

                                                    let current = scope.querySelector('.sc--current'),
                                                        total = scope.querySelector('.sc--total');

                                                    current.innerHTML = vars.current;
                                                    total.innerHTML = vars.total;

                                                }

                                                if (scope.querySelector('.sc--progressbar')) {

                                                    let prog = scope.querySelector('.sc--prog');
                                                    gsap.to(prog, {
                                                        width: vars.progress + '%'
                                                    })
                                                }

                                                if (scope.querySelector('.sc--progress')) {

                                                    let prog = scope.querySelector('.sc--progress-perc');
                                                    prog.innerHTML = String(Math.floor(vars.progress)).padStart(2, '0');
                                                }

                                            })
                                        }

                                    }

                                    if (target.classList.contains('cr--drag') || mobileQuery.matches) {

                                        let draggable = Draggable.get(target);

                                        if (draggable) {

                                            draggable.addEventListener('throwupdate', () => {

                                                vars.progress = draggable.x / draggable.minX * 100;

                                                if (scope.querySelector('.sc--fraction')) {

                                                    let current = scope.querySelector('.sc--current'),
                                                        total = scope.querySelector('.sc--total');

                                                    getCurrentItem()

                                                    current.innerHTML = vars.current;
                                                    total.innerHTML = vars.total;

                                                }

                                                if (scope.querySelector('.sc--progressbar')) {

                                                    let prog = scope.querySelector('.sc--prog');

                                                    gsap.to(prog, {
                                                        width: vars.progress + '%'
                                                    })
                                                }

                                                if (scope.querySelector('.sc--progress')) {

                                                    let prog = scope.querySelector('.sc--progress-perc');
                                                    prog.innerHTML = String(Math.floor(vars.progress)).padStart(2, '0');
                                                }
                                            });

                                            if (scope.querySelector('.sc--navigation')) {

                                                let next = scope.querySelector('.sc--next'),
                                                    prev = scope.querySelector('.sc--prev'),
                                                    xVal = 0;

                                                next.addEventListener('click', () => {

                                                    xVal = draggable.x;
                                                    xVal -= vars.width;


                                                    console.log(xVal);

                                                    gsap.to(target, {
                                                        x: xVal,
                                                        onComplete: () => {
                                                            draggable.update(true);
                                                            updateOthers();
                                                        }
                                                    })

                                                })

                                                prev.addEventListener('click', () => {

                                                    xVal = draggable.x;
                                                    xVal += vars.width;

                                                    gsap.to(target, {
                                                        x: xVal,
                                                        onComplete: () => {
                                                            draggable.update(true);
                                                            updateOthers();
                                                        }
                                                    })


                                                })

                                            }

                                        }

                                    }

                                    if (target.classList.contains('swiper-container')) {

                                        let swiper = target.swiper,
                                            speed = swiper.passedParams.speed;

                                        if (scope.querySelector('.sc--navigation')) {

                                            let prev = scope.querySelector('.sc--prev'),
                                                next = scope.querySelector('.sc--next');

                                            prev.addEventListener('click', () => {
                                                swiper.slidePrev(speed, true);
                                            })

                                            next.addEventListener('click', () => {
                                                swiper.slideNext(speed, true)
                                            })

                                        } else if (scope.querySelector('.sc--fraction')) {

                                            function unitize(number) {
                                                return number.toString().padStart(2, '0');
                                            }

                                            let curr = scope.querySelector('.sc--current'),
                                                tot = scope.querySelector('.sc--total'),
                                                length = target.querySelectorAll('.swiper-slide').length;

                                            curr.innerHTML = control.classList.contains('unitaze--numbers') ? unitize(1) : 1;
                                            tot.innerHTML = control.classList.contains('unitaze--numbers') ? unitize(length) : length;

                                            swiper.on('slideChange', function () {
                                                curr.innerHTML = control.classList.contains('unitaze--numbers') ? unitize(swiper.activeIndex + 1) : swiper.activeIndex + 1;

                                            });

                                        }

                                    } else {
                                        getCurrentItem()
                                    }

                                }
                            }, 10);
                        })
                    }

                }

            }, 50)

        })


    }

    )

})(jQuery)





