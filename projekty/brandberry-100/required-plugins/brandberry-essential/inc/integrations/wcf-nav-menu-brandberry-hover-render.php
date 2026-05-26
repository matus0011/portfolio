<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Brandberry Hover Effect
 * - Enabled by switcher: bb_brandberry_hover_effect === 'yes'
 * - Rewrites WCF nav menu anchor text into 2 stacked spans for slide effect
 * - Enqueues CSS only when enabled
 *
 * FIX:
 * - Some templates output <a>Text</a>
 * - Others output <a><span class="menu-text">Text</span></a> (your Home case)
 * This file now supports BOTH cases.
 */
add_filter('elementor/widget/render_content', function( $content, $widget ){

    if ( ! is_object($widget) || ! method_exists($widget, 'get_name') ) {
        return $content;
    }

    if ( $widget->get_name() !== 'wcf--nav-menu' ) {
        return $content;
    }

    if ( ! method_exists($widget, 'get_settings_for_display') ) {
        return $content;
    }

    $settings = $widget->get_settings_for_display();

    if ( empty($settings['bb_brandberry_hover_effect']) || $settings['bb_brandberry_hover_effect'] !== 'yes' ) {
        return $content;
    }

    // Enqueue CSS only when option enabled
    if ( ! wp_style_is('bb-wcf-nav-menu-brandberry-hover', 'enqueued') ) {
        wp_enqueue_style('bb-wcf-nav-menu-brandberry-hover');
    }

    // Add scope class to container
    $content = preg_replace(
        '/(<div[^>]*class="[^"]*\bwcf-nav-menu-container\b[^"]*)"/i',
        '$1 bb-wcf-hover-brandberry"',
        $content,
        1
    );

    /**
     * CASE A (old):
     * <a ... class="wcf-nav-item" ...>Home</a>
     *
     * CASE B (home / other templates):
     * <a ... class="wcf-nav-item" ...><span class="menu-text" data-text="Home">Home</span></a>
     *
     * Both become:
     * <a ... class="wcf-nav-item" ...>
     *   <span class="menu-text" data-text="Home">
     *     <span class="bb-nav-inner">
     *       <span class="bb-nav-text">Home</span>
     *       <span class="bb-nav-text-hover" aria-hidden="true">Home</span>
     *     </span>
     *   </span>
     * </a>
     */

    // -----------------------------------------
    // CASE A: <a ...>Text</a> (no nested tags)
    // -----------------------------------------
    $pattern_plain = '/<a([^>]*\bclass=("|\')[^"\']*\bwcf-nav-item\b[^"\']*\2[^>]*)>([^<]+)<\/a>/i';

    $content = preg_replace_callback($pattern_plain, function( $m ){

        // avoid double-processing
        if ( stripos($m[0], 'bb-nav-inner') !== false ) {
            return $m[0];
        }

        $a_attrs  = $m[1];
        $raw_text = html_entity_decode(trim($m[3]), ENT_QUOTES, 'UTF-8');

        if ( $raw_text === '' ) {
            return $m[0];
        }

        $safe_text = esc_html($raw_text);
        $safe_attr = esc_attr($raw_text);

        return '<a' . $a_attrs . '>'
             .   '<span class="menu-text" data-text="' . $safe_attr . '">'
             .     '<span class="bb-nav-inner">'
             .       '<span class="bb-nav-text">' . $safe_text . '</span>'
             .       '<span class="bb-nav-text-hover" aria-hidden="true">' . $safe_text . '</span>'
             .     '</span>'
             .   '</span>'
             . '</a>';

    }, $content);

    // ------------------------------------------------------------
    // CASE B: <a ...><span class="menu-text" ...>Text</span></a>
    // ------------------------------------------------------------
    $pattern_span = '/<a([^>]*\bclass=("|\')[^"\']*\bwcf-nav-item\b[^"\']*\2[^>]*)>\s*<span([^>]*\bclass=("|\')[^"\']*\bmenu-text\b[^"\']*\4[^>]*)>([^<]*)<\/span>\s*<\/a>/i';

    $content = preg_replace_callback($pattern_span, function( $m ){

        // avoid double-processing
        if ( stripos($m[0], 'bb-nav-inner') !== false ) {
            return $m[0];
        }

        $a_attrs    = $m[1];
        $span_attrs = $m[3]; // attributes that already exist on menu-text span
        $raw_text   = html_entity_decode(trim($m[5]), ENT_QUOTES, 'UTF-8');

        if ( $raw_text === '' ) {
            return $m[0];
        }

        $safe_text = esc_html($raw_text);
        $safe_attr = esc_attr($raw_text);

        // Ensure data-text exists (keep existing attrs, append if missing)
        $has_data_text = ( stripos($span_attrs, 'data-text=') !== false );
        $span_open = '<span' . $span_attrs . ( $has_data_text ? '' : ' data-text="' . $safe_attr . '"' ) . '>';

        return '<a' . $a_attrs . '>'
             .   $span_open
             .     '<span class="bb-nav-inner">'
             .       '<span class="bb-nav-text">' . $safe_text . '</span>'
             .       '<span class="bb-nav-text-hover" aria-hidden="true">' . $safe_text . '</span>'
             .     '</span>'
             .   '</span>'
             . '</a>';

    }, $content);

    return $content;

}, 25, 2);
