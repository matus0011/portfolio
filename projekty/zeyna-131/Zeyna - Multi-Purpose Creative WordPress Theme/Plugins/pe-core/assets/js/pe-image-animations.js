class peImageAnimation {

	constructor(DOM_el, options, fromOptions, scroll, out) {

		this.DOM = {
			el: null,
		};

		this.DOM.el = DOM_el;
		this.settings = this.DOM.el.dataset.settings;

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

		this.stagger = settings.stagger;
		this.duration = settings.duration;
		this.delay = settings.delay;
		this.scrub = settings.scrub;
		this.repeat = settings.repeat;
		this.pin = settings.pin;
		this.pinTarget = settings.pinTarget;
		this.animOut = settings.out;
		this.target = this.DOM.el.classList.contains('anim-multiple') ? this.DOM.el.querySelectorAll('.inner--anim') : this.DOM.el;
		this.ease = settings.easing;
		this.startScale = settings.start_scale;
		this.endScale = settings.end_scale;
		this.innerScale = settings.inner_scale;

		this.block_direction = settings.block_direction;

		this.img = this.DOM.el.querySelector('img');

		this.id = settings.id;

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
			'--blWidth': '100%',
			'--blHeight': '100%',

		};

		// Animation start stages
		this.from = {
			yPercent: 0,
			xPercent: 0,
			x: 0,
			y: 0,
			'--blWidth': '100%',
			'--blHeight': '100%',
		}

		// Scroll options
		this.scroll = {
			scrollTrigger: {
				id: this.id,
				trigger: null,
				scrub: null,
				pin: null,
				start: 'top bottom',
				end: 'bottom center',
				onEnter: () => {
					this.DOM.el.classList.add('viewport-enter');
				}
			}
		}

		this.out = {
			yPercent: null,
			stagger: this.stagger,
			duration: this.duration,
			delay: this.delay,
			ease: 'power1.in',
		}

		this.scroll.scrollTrigger.start = settings.item_ref_start + ' ' + settings.window_ref_start;
		this.scroll.scrollTrigger.end = settings.item_ref_end + ' ' + settings.window_ref_end;


		this.progress = 0;

		this.pin == null ? this.pin = false : '';
		this.scrub == null ? this.scrub = false : '';
		this.animOut == null ? this.animOut = false : '';


		this.anim = this.DOM.el.dataset.animation;


		// Defaults for animations

		if (this.anim === 'scale') {

			this.from.scale = this.startScale;
			this.defaults.scale = this.endScale;

			this.defaults.duration = 0.75;

			this.out.scale = this.startScale;
		}

		if (this.anim === 'block') {

			this.defaults['--blWidth'] = this.block_direction === 'left' || this.block_direction === 'right' ? '0%' : '100%';
			this.defaults['--blHeight'] = this.block_direction === 'up' || this.block_direction === 'down' ? '0%' : '100%';


			this.out['--blWidth'] = this.block_direction === 'left' || this.block_direction === 'right' ? '100%' : '0%';
			this.out['--blHeight'] = this.block_direction === 'up' || this.block_direction === 'down' ? '100%' : '0%';

		}

		if (this.anim === 'mask') {
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
			this.defaults.ease = 'power3.inOut';

		}

		this.stagger == null ? this.stagger = this.defaults.stagger : '';
		this.delay == null ? this.delay = this.defaults.delay : '';
		this.duration == null ? this.duration = this.defaults.duration : '';

		this.options = Object.assign(this.defaults, options);
		this.fromOptions = Object.assign(this.from, fromOptions);
		this.scroll = Object.assign(this.scroll, scroll);

		this.options.stagger = this.stagger;
		this.options.delay = this.delay;
		this.options.duration = this.duration;

		this.scroll.scrollTrigger.trigger = this.DOM.el;

		if (this.pin) {

			this.scrub = true
			this.scroll.scrollTrigger.scrub = 1;

			parents(this.DOM.el, '.elementor-widget-container')[0].classList.add('anim--parent');

			if (this.pinTarget) {

				this.scroll.scrollTrigger.pin = this.pinTarget;
				this.scroll.scrollTrigger.trigger = this.pinTarget;

				const element = document.querySelector(this.pinTarget);

				element.style.cssText += 'transition-duration:0s';

			} else {

				this.scroll.scrollTrigger.pin = parents(this.DOM.el, '.elementor-element-1cbf11e');
			}

		}

		if ((this.scrub) && (!this.pin)) {
			this.scroll.scrollTrigger.scrub = 1;
		}

		this.scrub ? this.defaults.ease = 'none' : '';

		if (this.animOut) {
			this.out.stagger = this.options.stagger;
			this.out.duration = this.options.duration;
			this.out = Object.assign(this.out, out);
		}

		if (parents(this.DOM.el, '.site--menu').length || (parents(this.DOM.el, '.e-n-tabs-content').length && !parents(this.DOM.el, '.e-active').length)) {
			this.scroll.scrollTrigger = false;
		}



		if (this.scrub || this.pin) {

			this.defaults.ease = 'none';
		}

		if (this.repeat) {
			this.scroll.scrollTrigger.toggleActions = "play reset resume reset";
		}

		if (this.ease !== 'default') {
			this.defaults.ease = this.ease;
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

			if (this.innerScale) {

				this.tl.fromTo(this.img, {
					scale: 1.25,
					duration: this.defaults.duration,
					delay: this.defaults.delay,
					ease: this.defaults.ease,

				}, {
					scale: 1
				}, this.delay)

			}

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
