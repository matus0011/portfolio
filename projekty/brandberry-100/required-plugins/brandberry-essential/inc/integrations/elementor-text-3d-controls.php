<?php
if ( ! defined('ABSPATH') ) exit;

use Elementor\Controls_Manager;

function brandberry_register_text_3d_controls_for_heading( $element ) {

    $element->add_control(
        'brandberry_text_3d_enable',
        [
            'label'   => __('Text 3D', 'brandberry-essential'),
            'type'    => Controls_Manager::SELECT,
            'default' => '',
            'options' => [
                ''        => __('None', 'brandberry-essential'),
                'text_3d'  => __('3D Brandberry', 'brandberry-essential'),
            ],
        ]
    );

    $element->add_control(
        'brandberry_text_3d_delay',
        [
            'label'     => __('3D Delay (s)', 'brandberry-essential'),
            'type'      => Controls_Manager::NUMBER,
            'default'   => 0.25,
            'min'       => 0,
            'step'      => 0.05,
            'condition' => [
                'brandberry_text_3d_enable' => 'text_3d',
            ],
        ]
    );

    $element->add_control(
        'brandberry_text_3d_duration',
        [
            'label'     => __('3D Duration (s)', 'brandberry-essential'),
            'type'      => Controls_Manager::NUMBER,
            'default'   => 1.25,
            'min'       => 0.05,
            'step'      => 0.05,
            'condition' => [
                'brandberry_text_3d_enable' => 'text_3d',
            ],
        ]
    );

    $element->add_control(
        'brandberry_text_3d_perspective_x',
        [
            'label'      => __('3D Perspective X (%)', 'brandberry-essential'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range'      => [
                '%' => ['min' => 0, 'max' => 100],
            ],
            'default'    => [
                'unit' => '%',
                'size' => 50,
            ],
            'condition'  => [
                'brandberry_text_3d_enable' => 'text_3d',
            ],
        ]
    );

    $element->add_control(
        'brandberry_text_3d_replay',
        [
            'label'        => __('Replay on scroll', 'brandberry-essential'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'brandberry-essential'),
            'label_off'    => __('No', 'brandberry-essential'),
            'return_value' => 'yes',
            'default'      => '',
            'condition'    => [
                'brandberry_text_3d_enable' => 'text_3d',
            ],
        ]
    );
}

function brandberry_attach_text_3d_controls() {
    // Elementor Heading widget: Section “Title”
    add_action(
        'elementor/element/heading/section_title/before_section_end',
        'brandberry_register_text_3d_controls_for_heading',
        10,
        1
    );
}

add_action('elementor/init', 'brandberry_attach_text_3d_controls');
