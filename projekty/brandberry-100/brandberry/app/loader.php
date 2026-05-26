<?php
/*----------------------------------------------------
                UTILITY Functions
-----------------------------------------------------*/
require_once BRANDBERRY_THEME_DIR . '/app/utility/global.php';
require_once BRANDBERRY_THEME_DIR . '/app/utility/helpers.php';
require_once BRANDBERRY_THEME_DIR . '/app/utility/util-part.tpl.php';

/*----------------------------------------------------
                Core Classes
-----------------------------------------------------*/
require_once BRANDBERRY_THEME_DIR . '/app/core/woo.php';
require_once BRANDBERRY_THEME_DIR . '/app/core/wcf-upgrade.php';
require_once BRANDBERRY_THEME_DIR . '/app/core/blog.class.php';
require_once BRANDBERRY_THEME_DIR . '/app/core/googlefonts.class.php';
require_once BRANDBERRY_THEME_DIR . '/app/core/walkernav.class.php';
require_once BRANDBERRY_THEME_DIR . '/app/core/setup.class.php';
require_once BRANDBERRY_THEME_DIR . '/app/core/enqueue.class.php';
require_once BRANDBERRY_THEME_DIR . '/app/core/default.widgets.class.php';
require_once BRANDBERRY_THEME_DIR . '/app/core/tags.class.php';
require_once( BRANDBERRY_THEME_DIR . '/app/class-tgm-plugin-activation.php');
require_once BRANDBERRY_THEME_DIR . '/app/core/required-plugins.class.php';
// should place in required plugin
require_once BRANDBERRY_THEME_DIR . '/app/core/inline.style.class.php';

require_once BRANDBERRY_THEME_DIR . '/app/init.php';

if ( class_exists( 'brandberry\\Init' ) ):
    brandberry\Init::register_services();
endif;

add_action('wp_enqueue_scripts', function () {

    // Field default is true
    $raw = bb_get_option_anywhere('disable_gsap_on_mobile', true);
    $disable_gsap_mobile = bb_to_bool($raw, true);

    // If option OFF -> do nothing (GSAP loads normally)
    if ( ! $disable_gsap_mobile ) {
        return;
    }

    // Only disable on mobile
    if ( ! wp_is_mobile() ) {
        return;
    }

    // 1) Remove GSAP
    $handles = [
        'gsap', 'gsap-scrolltrigger', 'gsap-scrollsmoother',
        'ScrollTrigger', 'ScrollSmoother',
        'aae-scroll-to-ele', 'gsap-scrollto', 'gsap-scrolltoplugin',
        // Brandberry/other possible handles (safe to include)
        'bb--gsap', 'bb--gsap-scrolltrigger', 'bb--gsap-scrollsmoother', 'bb--gsap-flip',
    ];

    foreach ($handles as $h) {
        wp_dequeue_script($h);
        wp_deregister_script($h);
    }

    // 2) Add native fallback JS
    wp_register_script('bb-native-scroll', false, [], null, true);
    wp_enqueue_script('bb-native-scroll');

    wp_add_inline_script('bb-native-scroll', "
    (function() {

      // --- existing nativeScrollTo + scroll-to fallbacks (keep if you still want them) ---

      function nativeScrollTo(target, offset, duration = 1500) {
        offset = offset || 0;
        var y = 0;

        if (typeof target === 'number') {
          y = target;
        } else if (typeof target === 'string') {
          if (target === '#top') {
            y = 0;
          } else {
            try {
              var el = document.querySelector(target);
              if (!el) return;
              y = window.pageYOffset + el.getBoundingClientRect().top - offset;
            } catch (e) {
              return;
            }
          }
        } else if (target && target.nodeType === 1) {
          y = window.pageYOffset + target.getBoundingClientRect().top - offset;
        } else {
          return;
        }

        window.scrollTo({ top: Math.max(0, y), behavior: 'smooth' });
      }

      window.nativeScrollTo = nativeScrollTo;

      // Handle AAE scroll-to containers
      document.addEventListener('click', function(e){
        var wrapper = e.target.closest && e.target.closest('.aae-scroll-to');
        if (!wrapper) return;

        var href = wrapper.getAttribute('href') || '';
        if (!href || href.charAt(0) !== '#') return;

        e.preventDefault();
        e.stopImmediatePropagation();

        if (href === '#top') {
          nativeScrollTo(0, 0);
          return;
        }

        var targetEl = null;
        try { targetEl = document.querySelector(href); } catch(err) {}
        if (!targetEl) return;

        nativeScrollTo(targetEl, 0);
      }, true);


      // Optional: also support classic <a href=\"#id\"> anchors
      document.addEventListener('click', function(e){
        var a = e.target.closest && e.target.closest('a[href^=\"#\"]');
        if (!a) return;

        var href = a.getAttribute('href') || '';
        if (!href || href === '#') return;

        var targetEl = null;
        try { targetEl = document.querySelector(href); } catch(err) {}
        if (!targetEl) return;

        e.preventDefault();
        e.stopImmediatePropagation();
        nativeScrollTo(targetEl, 0);

        if (history.pushState) {
          history.pushState(null, '', href);
        } else {
          window.location.hash = href;
        }
      }, true);


      // --- Wraplink fallback: treat data-wcf-wrapper-link as (possibly JSON) link ---
      document.addEventListener('click', function(e){
        if (!e.target.closest) return;

        var wrap = e.target.closest('[data-wcf-wrapper-link]');
        if (!wrap) return;

        var raw = wrap.getAttribute('data-wcf-wrapper-link');
        if (!raw) return;

        var url = raw;

        // If it looks like JSON, parse and use .href
        var trimmed = raw.trim();
        if (trimmed.charAt(0) === '{') {
          try {
            var obj = JSON.parse(trimmed);
            if (obj && typeof obj.href === 'string') {
              url = obj.href;
            } else {
              return; // no usable href
            }
          } catch (err) {
            return; // invalid JSON, bail
          }
        }

        // If it's already a real <a href=\"...\"> let the browser handle it
        if (wrap.tagName === 'A' && wrap.getAttribute('href')) return;

        if (e.defaultPrevented) return;

        e.preventDefault();
        e.stopImmediatePropagation();

        // Support cmd/ctrl/middle-click → new tab
        if (e.metaKey || e.ctrlKey || e.button === 1) {
          window.open(url, '_blank');
        } else {
          window.location.href = url;
        }
      }, true);

    })();
    ", 'after');

}, 9999);

