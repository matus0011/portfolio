<?php
/**
 * Brandberry Essential — Elementor Text 3D renderer (Heading widget)
 * Wraps the Elementor Heading output into the Text-3D structure and
 * duplicates the heading markup for the "back" face (best visual match).
 */

if ( ! defined('ABSPATH') ) {
    exit;
}

function brandberry_text_3d_num_to_css_seconds( $num, $default ) {
    if ( $num === '' || $num === null ) {
        return $default;
    }
    $val = (float) $num;
    if ( $val < 0 ) {
        $val = 0;
    }
    $s = rtrim( rtrim( (string) $val, '0' ), '.' );
    return $s === '' ? $default : $s;
}

function brandberry_wrap_heading_with_text_3d( $content, $widget ) {

    if ( ! is_object( $widget ) || ! method_exists( $widget, 'get_name' ) ) {
        return $content;
    }

    // Only Elementor Heading widget (extend later if you want)
    if ( 'heading' !== $widget->get_name() ) {
        return $content;
    }

    if ( ! method_exists( $widget, 'get_settings_for_display' ) ) {
        return $content;
    }

    $settings = $widget->get_settings_for_display();

    if (
        empty( $settings['brandberry_text_3d_enable'] ) ||
        $settings['brandberry_text_3d_enable'] !== 'text_3d'
    ) {
        return $content;
    }

    // Extract the exact heading HTML Elementor generated
    if ( ! preg_match( '/(<h([1-6])[^>]*>.*?<\/h\2>)/is', $content, $m ) ) {
        return $content;
    }

    $heading_html = $m[1];

    // Plain text for the back layer (keeps markup identical but avoids nested HTML issues)
    $plain_text = trim( wp_strip_all_tags( $heading_html ) );

    // Read controls
    $delay = brandberry_text_3d_num_to_css_seconds(
        isset( $settings['brandberry_text_3d_delay'] ) ? $settings['brandberry_text_3d_delay'] : null,
        '0.25'
    );

    $dur = brandberry_text_3d_num_to_css_seconds(
        isset( $settings['brandberry_text_3d_duration'] ) ? $settings['brandberry_text_3d_duration'] : null,
        '1.25'
    );

    $px = 50;
    if ( isset( $settings['brandberry_text_3d_perspective_x']['size'] ) ) {
        $px = (int) $settings['brandberry_text_3d_perspective_x']['size'];
    }
    if ( $px < 0 ) {
        $px = 0;
    }
    if ( $px > 100 ) {
        $px = 100;
    }

    $replay = ( ! empty( $settings['brandberry_text_3d_replay'] ) && $settings['brandberry_text_3d_replay'] === 'yes' )
        ? '1'
        : '0';

    $style = sprintf(
        '--text-3d-delay:%ss;--text-3d-duration:%ss;--text-3d-perspective-x:%s%%;',
        esc_attr( $delay ),
        esc_attr( $dur ),
        esc_attr( (string) $px )
    );

    /**
     * Back layer: reuse the same heading tag + attributes/classes as the front
     * but replace inner content with plain text.
     *
     * This gives the best visual match (same typography, spacing, etc.)
     */
    $back_heading = preg_replace(
        '/>(.*?)</is',
        '>' . esc_html( $plain_text ) . '<',
        $heading_html,
        1
    );

    $wrapped = '
<div class="text-3d brandberry-text-3d" data-bb-3d-replay="' . esc_attr( $replay ) . '" style="' . esc_attr( $style ) . '">
  <div class="text-3d-inner">
    <div class="text-3d-front">' . $heading_html . '</div>
    <div class="text-3d-hack">
      <div class="text-3d-back">' . $back_heading . '</div>
    </div>
  </div>
</div>';

    // Replace only the heading with our wrapped structure, preserving Elementor wrapper markup
    $content = str_replace( $heading_html, $wrapped, $content );

    return $content;
}

add_filter( 'elementor/widget/render_content', 'brandberry_wrap_heading_with_text_3d', 10, 2 );
