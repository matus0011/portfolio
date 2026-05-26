!function(e){"use strict";var t={init:function(n){t.config={responsive_menu_width:1199,header_menu:e(".lawyer-header__inner"),header:e(".default-blog-header")},e.extend(t.config,n),t.setup()},setup:function(){t.offcanvas(),t.woo()},offcanvas:function(){let e=document.querySelector(".offcanvas__area"),t=document.querySelector(".info-default-offcanvas"),n=document.querySelector(".offcanvas__close");t&&t.addEventListener("click",()=>{e.classList.add("show")}),n&&n.addEventListener("click",()=>{e.classList.remove("show")}),document.addEventListener("click",n=>{if(e){let a=e.contains(n.target)||t.contains(n.target);a||e.classList.remove("show")}});let a=document.querySelectorAll(".brandberry-mb-menu-items li > a");a.forEach(e=>{let t=e.nextElementSibling;t&&"UL"===t.tagName&&t.classList.contains("dp-menu")&&(e.querySelector(".nav-direction-icon").setAttribute("data-icon","+"),e.querySelector(".nav-direction-icon").addEventListener("click",function(e){let t=this.parentElement.nextElementSibling;if(t&&"UL"===t.tagName&&t.classList.contains("dp-menu")){e.preventDefault();let n="true"===t.getAttribute("aria-expanded");t.setAttribute("aria-expanded",!n),t.style.display=n?"none":"block",n?this.setAttribute("data-icon","+"):this.setAttribute("data-icon","-")}}))}),document.querySelectorAll(".hello-animation-mb-menu-items a").forEach(e=>{e.addEventListener("keydown",function(e){if("ArrowDown"===e.key){e.preventDefault();let t=e.target.parentElement.nextElementSibling;t&&t.querySelector("a").focus()}if("ArrowUp"===e.key){e.preventDefault();let n=e.target.parentElement.previousElementSibling;n&&n.querySelector("a").focus()}})})},woo:function(){let t=0,n=null;e(document).on("click",".quantity .plus",function(){n=e(this).parents(".quantity").find("input"),t=parseInt(n.val()),n.val(++t),jQuery("[name='update_cart']").prop("disabled",!1),brandberry_obj.cart_update_qty_change&&jQuery("[name='update_cart']").trigger("click")}),e(document).on("click",".quantity .minus",function(){n=e(this).parents(".quantity").find("input"),t=parseInt(n.val()),0==(t=--t)&&(t=1),n.val(t),jQuery("[name='update_cart']").prop("disabled",!1),brandberry_obj.cart_update_qty_change&&jQuery("[name='update_cart']").trigger("click")})}};e(document).ready(t.init)}(jQuery);

(function () {
  // Desktop only
  if (window.matchMedia("(max-width: 1024px)").matches) return;

  function initRotateBadges() {
    // Make sure GSAP + ScrollTrigger exist
    if (!window.gsap || !window.ScrollTrigger) return;

    // IMPORTANT: register plugin (required in many setups)
    try { gsap.registerPlugin(ScrollTrigger); } catch (e) {}

    // Helper: only create if element exists
    function makeRotation(selector) {
      const el = document.querySelector(selector);
      if (!el) return;

      gsap.timeline({
        scrollTrigger: {
          trigger: el,
          start: "bottom bottom",
          end: "top top",
          scrub: 2,
          // pin: true, // <-- pin belongs here, not in the .to() (more reliable)
          // markers: true, // uncomment to debug
        }
      }).to(el, {
        rotation: 360,
        ease: "none"
      });
    }

    makeRotation(".rotate-on-scroll");
    makeRotation(".rotate-expertise");
    makeRotation(".rotate-about");
  }

  // Run after page fully loads (safer with Elementor)
  window.addEventListener("load", initRotateBadges);

  // Also re-run when Elementor renders content (editor + some dynamic loads)
  document.addEventListener("elementor/frontend/init", function () {
    if (window.elementorFrontend && elementorFrontend.hooks) {
      elementorFrontend.hooks.addAction("frontend/element_ready/global", initRotateBadges);
    }
  });
})();


(function () {
  // Desktop only
  if (window.matchMedia("(max-width: 1024px)").matches) return;

  function initMoveOnScroll() {
    // Make sure GSAP + ScrollTrigger exist
    if (!window.gsap || !window.ScrollTrigger) return;

    // Register plugin
    try { gsap.registerPlugin(ScrollTrigger); } catch (e) {}

    function makeMove(selector, xPercentValue) {
      const els = gsap.utils.toArray(selector);
      if (!els.length) return;

      els.forEach((el) => {
        // Prevent duplicates when Elementor re-inits
        ScrollTrigger.getAll().forEach((st) => {
          if (st && st.trigger === el) st.kill();
        });
        gsap.killTweensOf(el);

        gsap.to(el, {
          xPercent: xPercentValue,
          ease: "none",
          scrollTrigger: {
            trigger: el,
            start: "top center",
            end: "bottom top",
            scrub: true,
            // markers: true,
          }
        });
      });
    }

    makeMove(".move-x-left", -20);
    makeMove(".move-x-right", 20);
  }

  // Run after page fully loads (safer with Elementor)
  window.addEventListener("load", initMoveOnScroll);

  // Re-run when Elementor renders content (editor + dynamic loads)
  document.addEventListener("elementor/frontend/init", function () {
    if (window.elementorFrontend && elementorFrontend.hooks) {
      elementorFrontend.hooks.addAction("frontend/element_ready/global", initMoveOnScroll);
    }
  });
})();



/* Fix for sticky sections + brand slider */
(function () {
  "use strict";

  const SLIDER_SEL = ".wcf--brand-slider-wrapper .swiper";
  const STICKY_CONTEXT_SEL = ".pin-spacer, .aae-pro-sticky-active, .aae-is-translating";

  // How often we check if it is stalled
  const WATCH_INTERVAL = 300; // ms

  // How long it must be not moving to be considered stalled
  const STALL_TIME = 400; // ms

  // Minimal translate delta to consider "moving"
  const EPS = 0.5;

  const DEBUG = false;
  const log = (...a) => DEBUG && console.log("[brand-slider-watchdog]", ...a);

  function qsa(sel, root = document) {
    return Array.from(root.querySelectorAll(sel));
  }

  function isSticky(el) {
    return !!el.closest(STICKY_CONTEXT_SEL);
  }

  function getStickyBrandSwipers() {
    return qsa(SLIDER_SEL)
      .filter(el => isSticky(el))
      .map(el => el.swiper)
      .filter(sw => sw && !sw.destroyed);
  }

  function inViewport(el) {
    if (!el) return false;
    const r = el.getBoundingClientRect();
    // consider visible if it intersects viewport
    return r.bottom > 0 && r.top < (window.innerHeight || document.documentElement.clientHeight);
  }

  function softUpdate(sw) {
    try {
      sw.update();
      sw.updateSize();
    } catch (e) {}
  }

  function hardRevive(sw) {
    try {
      // light updates first
      sw.update();
      sw.updateSize();
      sw.updateSlides();

      // only restart if autoplay exists
      if (sw.autoplay) {
        sw.autoplay.stop();
        sw.autoplay.start();
      }

      // re-apply current translate (no GPU nudge)
      if (typeof sw.getTranslate === "function" && typeof sw.setTranslate === "function") {
        sw.setTranslate(sw.getTranslate());
      }

      log("hardRevive", sw);
    } catch (e) {
      log("hardRevive error", e);
    }
  }

  // ---- Watchdog state per swiper ----
  const state = new WeakMap();

  function tickWatchdog() {
    const list = getStickyBrandSwipers();

    list.forEach(sw => {
      // only care when visible; avoids work + avoids interfering with offscreen things
      if (!inViewport(sw.el)) return;

      const now = performance.now();
      const t = (typeof sw.getTranslate === "function") ? sw.getTranslate() : null;

      let s = state.get(sw);
      if (!s) {
        s = { lastT: t, lastMoveAt: now, lastCheckAt: now };
        state.set(sw, s);
        return;
      }

      // if translate changes, it is moving
      if (t !== null && s.lastT !== null && Math.abs(t - s.lastT) > EPS) {
        s.lastT = t;
        s.lastMoveAt = now;
        s.lastCheckAt = now;
        return;
      }

      // no movement detected
      s.lastCheckAt = now;

      const running = !!sw.autoplay?.running;

      // If autoplay says it is running BUT we haven't moved for a while -> revive
      if (running && (now - s.lastMoveAt) > STALL_TIME) {
        hardRevive(sw);
        // reset timers after revive
        s.lastMoveAt = now;
        s.lastT = (typeof sw.getTranslate === "function") ? sw.getTranslate() : s.lastT;
      }
    });
  }

  // ---- Trigger revives on “interaction events” (but NOT on scroll) ----
  let reviveTimer = 0;
  function scheduleRevive(delay = 120) {
    clearTimeout(reviveTimer);
    reviveTimer = setTimeout(() => {
      getStickyBrandSwipers().forEach(sw => {
        // Only revive if it claims autoplay exists but not running (or if it’s disabled)
        if (sw.autoplay && !sw.autoplay.running) hardRevive(sw);
        else softUpdate(sw);
      });
    }, delay);
  }

  function bindTriggers() {
    // clicks (one-page nav etc.)
    document.addEventListener("click", () => scheduleRevive(150), true);
    window.addEventListener("hashchange", () => scheduleRevive(150));

    // returning to tab/window
    document.addEventListener("visibilitychange", () => {
      if (!document.hidden) scheduleRevive(0);
    });
    window.addEventListener("focus", () => scheduleRevive(0));

    // Scroll: DO NOT revive continuously.
    // Only do a *soft* update after scrolling stops.
    let scrollEndTimer = 0;
    window.addEventListener("scroll", () => {
      clearTimeout(scrollEndTimer);
      scrollEndTimer = setTimeout(() => {
        getStickyBrandSwipers().forEach(softUpdate);
      }, 180);
    }, { passive: true });
  }

  let watchdogId = null;

  function boot() {
    bindTriggers();

    // initial soft update
    getStickyBrandSwipers().forEach(softUpdate);

    // start watchdog
    if (!watchdogId) {
      watchdogId = setInterval(tickWatchdog, WATCH_INTERVAL);
    }

    // a couple of late passes (Elementor/Swiper init timing)
    scheduleRevive(300);
    scheduleRevive(1200);
  }

  document.addEventListener("DOMContentLoaded", boot);
  window.addEventListener("load", boot);
})();


/* Fix mobile menu on onepage and remove cursor */

(function () {
  "use strict";

  // Only run on touch/mobile-ish devices
  const isTouch =
    "ontouchstart" in window ||
    (navigator.maxTouchPoints || 0) > 0 ||
    window.matchMedia("(hover: none), (pointer: coarse)").matches;

  const MOBILE_MQ = window.matchMedia("(max-width: 1024px)");

  const SELECTORS = {
    menuRoot: ".wcf__nav-menu",
    burger: ".wcf-menu-hamburger, button[aria-label='hamburger-icon']",
    overlay: ".mobile-sub-back",
    menuLinks: ".wcf-nav-menu-container a.wcf-nav-item, .wcf-nav-menu-container a[href]"
  };

  const OPEN_CLASSES = ["mobile-menu-active", "wcf-nav-is-toggled", "is-open", "open"];

  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

  function getBurger() {
    return $(SELECTORS.burger);
  }

  function getMenuRoot() {
    const burger = getBurger();
    return (burger && burger.closest(SELECTORS.menuRoot)) || $(SELECTORS.menuRoot);
  }

  function isMenuOpen(root) {
    if (!root) return false;
    return OPEN_CLASSES.some((c) => root.classList.contains(c)) || OPEN_CLASSES.some((c) => document.body.classList.contains(c));
  }

  function unlockScrollHard() {
    // Remove common scroll locks
    document.documentElement.style.overflow = "";
    document.documentElement.style.position = "";
    document.documentElement.style.height = "";
    document.documentElement.style.top = "";
    document.documentElement.style.width = "";

    document.body.style.overflow = "";
    document.body.style.position = "";
    document.body.style.height = "";
    document.body.style.top = "";
    document.body.style.width = "";
    document.body.style.touchAction = "";

    // Ensure overlay/backdrop isn't blocking taps
    const overlay = $(SELECTORS.overlay);
    if (overlay) overlay.style.display = "none";
  }

  function syncScrollLock() {
    const root = getMenuRoot();
    // If menu isn't open, never allow overflow hidden to remain
    if (!isMenuOpen(root)) {
      const bodyOverflow = getComputedStyle(document.body).overflow;
      const htmlOverflow = getComputedStyle(document.documentElement).overflow;
      if (bodyOverflow === "hidden" || htmlOverflow === "hidden") {
        unlockScrollHard();
      }
    }
  }
  
    function menuLooksVisible() {
	  const panel =
	    document.querySelector(".wcf-nav-menu-container") ||
	    document.querySelector(".wcf__nav-menu-container") ||
	    document.querySelector(".wcf-nav-menu__container") ||
	    document.querySelector(".wcf__nav-menu");
	
	  if (!panel) return false;
	
	  const rect = panel.getBoundingClientRect();
	  const style = window.getComputedStyle(panel);
	
	  const vw = window.innerWidth || document.documentElement.clientWidth;
	  const vh = window.innerHeight || document.documentElement.clientHeight;
	
	  // fully off-canvas / outside viewport
	  const offscreen =
	    rect.right <= 1 ||            // hidden to the left (your case: right == 0)
	    rect.left >= vw - 1 ||        // hidden to the right
	    rect.bottom <= 1 ||           // hidden above
	    rect.top >= vh - 1;           // hidden below
	
	  const notRenderable =
	    style.display === "none" ||
	    style.visibility === "hidden" ||
	    style.opacity === "0" ||
	    style.pointerEvents === "none";
	
	  return !(offscreen || notRenderable);
	}


  // 🔥 Stronger scroll-lock sync:
  // If scroll is locked BUT menu isn't visible, unlock no matter what classes say.
  function syncScrollLockHard() {
    const bodyOverflow = getComputedStyle(document.body).overflow;
    const htmlOverflow = getComputedStyle(document.documentElement).overflow;

    const locked = bodyOverflow === "hidden" || htmlOverflow === "hidden";

    if (!locked) return;

    // If menu isn't actually visible, this is a bug -> unlock
    if (!menuLooksVisible()) {
      const root = getMenuRoot();

      // Remove common "open" classes aggressively
      if (root) root.classList.remove(...OPEN_CLASSES);
      document.body.classList.remove(...OPEN_CLASSES);

      unlockScrollHard();
    }
  }


  function clickBurger() {
    const burger = getBurger();
    if (burger) burger.click();
  }

  // ----- anchor detection -----
  function isSamePageHashLink(a) {
    if (!a || !a.getAttribute) return false;
    const href = a.getAttribute("href") || "";
    if (!href.includes("#")) return false;

    if (href.startsWith("#")) return true;

    try {
      const url = new URL(href, window.location.href);
      return (
        url.origin === window.location.origin &&
        url.pathname.replace(/\/+$/, "") === window.location.pathname.replace(/\/+$/, "") &&
        !!url.hash
      );
    } catch {
      return false;
    }
  }

  function getHash(a) {
    const href = a.getAttribute("href") || "";
    if (href.startsWith("#")) return href;
    try {
      const url = new URL(href, window.location.href);
      return url.hash || "";
    } catch {
      return "";
    }
  }

  // ----- native smooth scroll (slower) -----
  function nativeSmoothScrollToHash(hash) {
    if (!hash || hash === "#") return;

    const id = hash.slice(1);
    const target = document.getElementById(id) || document.querySelector(hash);
    if (!target) return;

    const startY = window.pageYOffset;
    const targetY = target.getBoundingClientRect().top + startY;
    const distance = targetY - startY;

    // 👇 slow it down here
    const duration = 1700; // was 1200
    const startTime = performance.now();

    function easeInOutCubic(t) {
      return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
    }

    function step(now) {
      const elapsed = now - startTime;
      const progress = Math.min(elapsed / duration, 1);
      const eased = easeInOutCubic(progress);

      window.scrollTo(0, startY + distance * eased);

      if (progress < 1) requestAnimationFrame(step);
    }

    requestAnimationFrame(step);
  }

  // ----- close menu safely -----
  function closeMenuAndUnlock() {
    const root = getMenuRoot();
    if (!root) {
      unlockScrollHard();
      return;
    }

    if (isMenuOpen(root)) {
      clickBurger(); // close via theme
    }

    // Always ensure scroll is not stuck
    setTimeout(unlockScrollHard, 60);
    setTimeout(syncScrollLock, 120);
    setTimeout(syncScrollLock, 500);
  }

  // ----- burger reliability -----
  function installBurgerFailsafe() {
    const burger = getBurger();
    if (!burger) return;

    // Make burger easier to tap (z-index + touch-action)
    const style = document.createElement("style");
    style.textContent = `
      .wcf-menu-hamburger, button[aria-label='hamburger-icon']{
        position: relative;
        z-index: 999999;
        pointer-events: auto;
        touch-action: manipulation;
      }
    `;
    document.head.appendChild(style);

    // After any burger click, resync scroll lock
    burger.addEventListener(
      "click",
      function () {
        setTimeout(syncScrollLock, 0);
        setTimeout(syncScrollLock, 250);
        setTimeout(syncScrollLock, 800);
      },
      true
    );

    // Watchdog: if body gets stuck hidden while menu is closed, unlock
    setInterval(syncScrollLock, 400);

    window.addEventListener("resize", syncScrollLock, { passive: true });
    window.addEventListener("orientationchange", syncScrollLock, { passive: true });
  }

  // ----- close on anchor click (mobile menu) -----
  function installCloseOnAnchorClick() {
    document.addEventListener(
      "click",
      function (e) {
        if (!MOBILE_MQ.matches) return;

        const a = e.target && e.target.closest ? e.target.closest(SELECTORS.menuLinks) : null;
        if (!a) return;

        // only anchors / same-page hashes
        if (!isSamePageHashLink(a)) return;

        const root = getMenuRoot();
        if (!isMenuOpen(root)) return;

        e.preventDefault();
        const hash = getHash(a);

        // close menu first
        closeMenuAndUnlock();

        // then smooth scroll (small delay so menu can close)
        setTimeout(() => nativeSmoothScrollToHash(hash), 180);
      },
      true
    );
  }

  // ----- disable cursor on touch (SAFE way) -----
  function disableCursorOnTouch() {
    if (!isTouch) return;

    // Hide cursor elements so they never block touches
    const style = document.createElement("style");
    style.textContent = `
      @media (hover: none), (pointer: coarse) {
        .wcf-cursor,
        .wcf-cursor-wrapper,
        .wcf-cursor-dot,
        .wcf-cursor-outline,
        .cursor-hover-effects {
          display: none !important;
          pointer-events: none !important;
          visibility: hidden !important;
        }
      }
    `;
    document.head.appendChild(style);

    // Remove any cursor nodes already injected
    const remove = () => {
      $$(".wcf-cursor, .wcf-cursor-wrapper, .wcf-cursor-dot, .wcf-cursor-outline, .cursor-hover-effects").forEach((el) => el.remove());
    };
    remove();
    window.addEventListener("load", remove);
  }

  function init() {
    disableCursorOnTouch();
    installBurgerFailsafe();
    installCloseOnAnchorClick();

    // initial cleanup in case it loads stuck
    setTimeout(syncScrollLock, 0);
    setTimeout(syncScrollLock, 400);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();

