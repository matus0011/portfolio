<?php
if ( ! defined('ABSPATH') ) exit;

use Elementor\Controls_Manager;

/**
 * Adds a SAVED switcher "Brandberry Hover Effect" to the WCF Nav Menu widget.
 * This version is section-ID agnostic: it waits until the widget has registered
 * menu_hover_pointer, then injects our switcher once.
 */
add_action('elementor/element/before_section_end', function( $element, $section_id ){

    // Only our widget
    if ( ! is_object($element) || ! method_exists($element, 'get_name') ) return;
    if ( $element->get_name() !== 'wcf--nav-menu' ) return;

    $controls = $element->get_controls();
    if ( empty($controls) || ! is_array($controls) ) return;

    // Wait until the widget has registered its own control (so we insert in a real section)
    if ( ! isset($controls['menu_hover_pointer']) ) return;

    // Already added
    if ( isset($controls['bb_brandberry_hover_effect']) ) return;

    /**
     * Add our switcher at the end of the CURRENT section (whichever section Elementor is closing now).
     * Because this runs at before_section_end, Elementor will register it properly -> it will SAVE.
     */
    $element->add_control(
        'bb_brandberry_hover_effect',
        [
            'label'        => esc_html__( 'Menu Flip on Hover Effect?', 'brandberry-essential' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'brandberry-essential' ),
            'label_off'    => esc_html__( 'No', 'brandberry-essential' ),
            'return_value' => 'yes',
            'default'      => '',
            'separator'    => 'before',
        ]
    );

}, 9999, 2);
