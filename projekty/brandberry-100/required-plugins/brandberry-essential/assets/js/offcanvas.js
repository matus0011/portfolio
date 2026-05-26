(function ($) {
  'use strict';

  function safeParseJSON(str) {
    try { return JSON.parse(str); } catch (e) { return {}; }
  }

  function getPanelWidth($wrap) {
    var $bar = $wrap.find('.bbe-offcanvas-bar').first();
    if (!$bar.length) return 0;
    // Force layout read.
    return $bar[0].getBoundingClientRect().width || 0;
  }

  function getPushTargets() {
    // Try common theme wrappers.
    var selectors = ['#page', '.site', '.site-container', '.site-content', 'main', 'body > .dialog-widget', 'body > .elementor'];
    var $targets = $();
    selectors.forEach(function (sel) {
      var $el = $(sel).first();
      if ($el.length) $targets = $targets.add($el);
    });
    // Fallback: direct children except the offcanvas itself.
    if (!$targets.length) {
      $targets = $('body').children().not('.bbe-offcanvas');
    }
    return $targets;
  }

  function applyPush($wrap, settings, isOpen) {
    var mode = settings.mode || 'slide';
    if (mode !== 'push' && mode !== 'reveal') return;

    var width = getPanelWidth($wrap);
    if (!width) return;

    var dir = (settings.flip === true || settings.flip === 'right') ? -1 : 1; // default left pushes content right
    // If position is right, reverse
    if ((settings.position || 'left') === 'right') dir = dir * -1;

    var x = (isOpen ? (width * dir) : 0);
    var $targets = getPushTargets();
    $targets.css('transform', x ? ('translate3d(' + x + 'px,0,0)') : '');
    $targets.css('transition', isOpen ? 'transform .25s ease' : '');

    if (mode === 'reveal') {
      // Reveal: panel sits under content, so lower z-index.
      $wrap.toggleClass('bbe-offcanvas-reveal', true);
    } else {
      $wrap.toggleClass('bbe-offcanvas-reveal', false);
    }
  }

  function bindWidget($wrap) {
    if ($wrap.data('bbeBound')) return;
    $wrap.data('bbeBound', true);

    var raw = $wrap.attr('data-bbe-settings') || $wrap.attr('data-settings') || '{}';
    var settings = safeParseJSON(raw);

    var $bar = $wrap.find('.bbe-offcanvas-bar');
    var $overlay = $wrap.find('.bbe-offcanvas-overlay');
    var $close = $wrap.find('.bbe-offcanvas-close');

    function open(e) {
      if (e) e.preventDefault();
      if ($wrap.hasClass('bbe-open')) return;

      $wrap.addClass('bbe-open');
      $('body').addClass('bbe-offcanvas-active');
      applyPush($wrap, settings, true);

      // Focus trap (basic)
      setTimeout(function () {
        var $focusable = $bar
          .find('a,button,input,textarea,select,[tabindex]:not([tabindex="-1"])')
          .filter(':visible')
          .first();
        if ($focusable.length) $focusable.trigger('focus');
      }, 50);
    }

    function close(e) {
      if (e) e.preventDefault();
      if (!$wrap.hasClass('bbe-open')) return;

      $wrap.removeClass('bbe-open');
      $('body').removeClass('bbe-offcanvas-active');
      applyPush($wrap, settings, false);
    }

    // Expose open/close for delegated triggers.
    $wrap.on('bbe:open', open);
    $wrap.on('bbe:close', close);

    // Trigger
    if (settings.layout === 'custom' && settings.custom_selector) {
      var ns = '.bbeOffcanvas' + ($wrap.attr('id') || Math.random().toString(36).slice(2));
      $(document).off('click' + ns, settings.custom_selector).on('click' + ns, settings.custom_selector, open);
    } else {
      // Default layout button may render outside the wrapper (Elementor does this
      // sometimes depending on HTML structure). Use delegated binding.
      var target = '#' + ($wrap.attr('id') || '');
      if (target !== '#') {
        $(document).on('click', '.bbe-offcanvas-button[data-bbe-target="' + target + '"]', open);
      }
    }

    // Close
    $close.on('click', close);

    if (settings.bg_close !== false) {
      $overlay.on('click', close);
    }

    // Esc
    if (settings.esc_close !== false) {
      $(document).on('keydown', function (evt) {
        if (!$wrap.hasClass('bbe-open')) return;
        if (evt.key === 'Escape') close(evt);
      });
    }
  }

  function initScope($scope) {
    $scope.find('.bbe-offcanvas').each(function () {
      bindWidget($(this));
    });
  }

  $(window).on('elementor/frontend/init', function () {
    if (typeof elementorFrontend !== 'undefined') {
      elementorFrontend.hooks.addAction('frontend/element_ready/brandberry-offcanvas.default', initScope);
      elementorFrontend.hooks.addAction('frontend/element_ready/global', initScope);
    }
  });

  $(function () {
    // Delegated trigger for default layout where the button can be rendered
    // outside the offcanvas wrapper.
    $(document).on('click', '.bbe-offcanvas-button[data-bbe-target]', function (e) {
      e.preventDefault();
      var target = $(this).attr('data-bbe-target');
      if (!target) return;
      var $wrap = $(target).first();
      if ($wrap.length) {
        $wrap.trigger('bbe:open');
      }
    });
    initScope($(document));
  });

})(jQuery);
