<?php


function build_anchor_attributes($atts)
{
    $attributes = [];

    if (!empty($atts['url'])) {
        $attributes[] = 'href="' . esc_url($atts['url']) . '"';
    }

    if (!empty($atts['is_external'])) {
        $attributes[] = 'target="_blank"';
    }

    if (!empty($atts['nofollow'])) {
        $attributes[] = 'rel="nofollow"';
    }

    if (!empty($atts['custom_attributes'])) {
        $attributes[] = trim($atts['custom_attributes']);
    }

    $attributes[] = 'class="pb--handle"';


    return implode(' ', $attributes);
}


function pe_general_animation_settings($widget, $tab = false, $container = false, $label = 'Animations', $section = true, $prefix = '', $isMulti = false)
{


    if ($section) {
        $widget->start_controls_section(
            'section_animate',
            [
                'label' => __($label, 'pe-core'),
                'tab' => $tab,
            ]
        );
    }

    $widget->add_control(
        $prefix .
        'select_animation',
        [
            'label' => esc_html__('Select Animation', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'description' => esc_html__('Will be used as intro animation.', 'pe-core'),
            'options' => [
                'none' => esc_html__('None', 'pe-core'),
                'fadeIn' => esc_html__('Fade In', 'pe-core'),
                'fadeUp' => esc_html__('Fade Up', 'pe-core'),
                'fadeDown' => esc_html__('Fade Down', 'pe-core'),
                'fadeLeft' => esc_html__('Fade Left', 'pe-core'),
                'fadeRight' => esc_html__('Fade Right', 'pe-core'),
                'slideUp' => esc_html__('Slide Up', 'pe-core'),
                'slideDown' => esc_html__('Slide Down', 'pe-core'),
                'slideLeft' => esc_html__('Slide Left', 'pe-core'),
                'slideRight' => esc_html__('Slide Right', 'pe-core'),
                'scaleUp' => esc_html__('Scale Up', 'pe-core'),
                'scaleDown' => esc_html__('Scale Down', 'pe-core'),
                'maskUp' => esc_html__('Mask Up', 'pe-core'),
                'maskDown' => esc_html__('Mask Down', 'pe-core'),
                'maskLeft' => esc_html__('Mask Left', 'pe-core'),
                'maskRight' => esc_html__('Mask Right', 'pe-core'),
                'customMask' => esc_html__('Custom Mask', 'pe-core'),
                'animateWidth' => esc_html__('Animate Width', 'pe-core'),
            ],
            'label_block' => true,
        ]
    );

    $widget->add_control(
        $prefix .
        'animate_width',
        [
            'label' => esc_html__('Animate Width (%)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ]
            ],
            'default' => [
                'unit' => '%',
                'size' => 60,
            ],
            'condition' => [
                $prefix . 'select_animation' => 'animateWidth',
            ]
        ]
    );


    $widget->add_control(
        $prefix .
        'fade_blur',
        [
            'label' => esc_html__('Fade Blur?', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => 'false',
            'condition' => [
                $prefix .
                'select_animation' => ['fadeIn', 'fadeDown', 'fadeUp', 'fadeLeft', 'fadeRight'],
            ]
        ]
    );

    $widget->add_control(
        $prefix .
        'image_transform_origin',
        [
            'label' => esc_html__('Animation Origin', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top left' => [
                    'title' => esc_html__('Top Left', 'pe-core'),
                    'icon' => 'material-icons md-north_west',
                ],
                'top center' => [
                    'title' => esc_html__('Top Center', 'pe-core'),
                    'icon' => 'material-icons md-north'
                ],
                'top right' => [
                    'title' => esc_html__('Top Right', 'pe-core'),
                    'icon' => 'material-icons md-north_east',
                ],
                'left center' => [
                    'title' => esc_html__('Left', 'pe-core'),
                    'icon' => 'material-icons md-west',
                ],
                'center center' => [
                    'title' => esc_html__('Center Center', 'pe-core'),
                    'icon' => 'material-icons md-filter_center_focus',
                ],
                'right center' => [
                    'title' => esc_html__('Right', 'pe-core'),
                    'icon' => 'material-icons md-east',
                ],
                'bottom left' => [
                    'title' => esc_html__('Bottom Left', 'pe-core'),
                    'icon' => 'material-icons md-south_west',
                ],
                'bottom center' => [
                    'title' => esc_html__('Bottom Center', 'pe-core'),
                    'icon' => 'material-icons md-south'
                ],
                'bottom right' => [
                    'title' => esc_html__('Bottom Right', 'pe-core'),
                    'icon' => 'material-icons md-south_east',
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}' => 'transform-origin: {{VALUE}};',
                '{{WRAPPER}} > div > [data-anim-general=true]' => 'transform-origin: {{VALUE}};',
            ],
            'default' => 'center center',
            'label_block' => true,
            'toggle' => false,
            'condition' => [
                $prefix .
                'select_animation' => ['scaleUp', 'scaleDown'],
            ]
        ]
    );


    $widget->add_control(
        $prefix .
        'mask_type',
        [
            'label' => esc_html__('Mask Type', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'square',
            'options' => [
                'square' => esc_html__('Square', 'pe-core'),
                'circle' => esc_html__('Circle', 'pe-core'),
                'triangle' => esc_html__('Triangle', 'pe-core'),
                'rhombus' => esc_html__('Rhombus', 'pe-core'),
                'hexagon' => esc_html__('Hexagon', 'pe-core'),
                'left_arrow' => esc_html__('Left Arrow', 'pe-core'),
                'right_arrow' => esc_html__('Right Arrow', 'pe-core'),
                'left_chevron' => esc_html__('Left Chevron', 'pe-core'),
                'right_chevron' => esc_html__('Right Chevron', 'pe-core'),
                'star' => esc_html__('Star', 'pe-core'),
                'close' => esc_html__('Close', 'pe-core'),
            ],
            'label_block' => true,
            'condition' => [
                $prefix .
                'select_animation' => 'customMask',
            ]
        ]
    );

    $widget->add_control(
        $prefix .
        'square_mask_start',
        [
            'label' => esc_html__('Start Mask', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['%'],
            'default' => [
                'top' => 10,
                'right' => 20,
                'bottom' => 23,
                'left' => 50,
                'unit' => '%',
                'isLinked' => false,
            ],
            'condition' => [
                $prefix . 'mask_type' => 'square',
                $prefix . 'select_animation' => 'customMask',
            ]
        ]
    );

    $widget->add_control(
        $prefix .
        'square_mask_end',
        [
            'label' => esc_html__('End Mask', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['%'],
            'default' => [
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0,
                'unit' => '%',
                'isLinked' => false,
            ],
            'condition' => [
                $prefix .
                'mask_type' => 'square',
                $prefix . 'select_animation' => 'customMask',
            ]
        ]
    );

    $widget->add_control(
        $prefix .
        'square_mask_radius',
        [
            'label' => esc_html__('Square Radius (Start)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 0,
            'condition' => [
                $prefix .
                'mask_type' => 'square',
                $prefix . 'select_animation' => ['mask', 'customMask'],
            ]
        ]
    );

    $widget->add_control(
        $prefix .
        'square_mask_radius_end',
        [
            'label' => esc_html__('Square Radius (End)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 0,
            'condition' => [
                $prefix .
                'mask_type' => 'square',
                $prefix . 'select_animation' => ['mask', 'customMask'],
            ]
        ]
    );


    $widget->start_controls_tabs(
        $prefix .
        'circle_tabs',
        [
            'condition' => [
                $prefix .
                'mask_type' => 'circle',
                $prefix . 'select_animation' => 'customMask',
            ]
        ]

    );

    $widget->start_controls_tab(
        $prefix .
        'circle_start_tab',
        [
            'label' => esc_html__('Start', 'pe-core'),
        ]
    );

    $widget->add_responsive_control(
        $prefix .
        'circle_size_start',
        [
            'label' => esc_html__('Circle Size', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 50,
            ],
            'condition' => [
                $prefix .
                'mask_type' => 'circle',
                $prefix . 'select_animation' => 'customMask',
            ]
        ]
    );

    $widget->add_responsive_control(
        $prefix .
        'circle_top_pos_start',
        [
            'label' => esc_html__('Circle Top Position', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 50,
            ],
            'condition' => [
                $prefix .
                'mask_type' => 'circle',
                $prefix . 'select_animation' => 'customMask',
            ]
        ]
    );

    $widget->add_responsive_control(
        $prefix .
        'circle_left_pos_start',
        [
            'label' => esc_html__('Circle Left Position', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 50,
            ],
            'condition' => [
                $prefix .
                'mask_type' => 'circle',
                $prefix . 'select_animation' => 'customMask',
            ]
        ]
    );


    $widget->end_controls_tab();

    $widget->start_controls_tab(
        $prefix .
        'circle_end_tab',
        [
            'label' => esc_html__('End', 'pe-core'),
        ]
    );

    $widget->add_responsive_control(
        $prefix .
        'circle_size_end',
        [
            'label' => esc_html__('Circle Size', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 50,
            ],
            'condition' => [
                $prefix .
                'mask_type' => 'circle',
                $prefix . 'select_animation' => 'customMask',
            ]
        ]
    );

    $widget->add_responsive_control(
        $prefix .
        'circle_top_pos_end',
        [
            'label' => esc_html__('Circle Top Position', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 50,
            ],
            'condition' => [
                $prefix .
                'mask_type' => 'circle',
                $prefix . 'select_animation' => 'customMask',
            ]
        ]
    );

    $widget->add_responsive_control(
        $prefix .
        'circle_left_pos_end',
        [
            'label' => esc_html__('Circle Left Position', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 50,
            ],
            'condition' => [
                $prefix .
                'mask_type' => 'circle',
                $prefix . 'select_animation' => 'customMask',
            ]
        ]
    );


    $widget->end_controls_tab();

    $widget->end_controls_tabs();


    $widget->add_control(
        $prefix .
        'gen_start_scale',
        [
            'label' => esc_html__('Start Scale', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 5,
            'step' => 0.1,
            'default' => 0,
            'condition' => [
                $prefix .
                'select_animation' => ['scaleUp', 'scaleDown'],
            ],

        ]
    );

    $widget->add_control(
        $prefix .
        'gen_end_scale',
        [
            'label' => esc_html__('End Scale', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 1,
            'step' => 0.1,
            'default' => 1,
            'condition' => [
                $prefix .
                'select_animation' => ['scaleUp', 'scaleDown'],
            ],

        ]
    );

    $widget->add_control(
        $prefix .
        'view',
        [
            'label' => esc_html__('View', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HIDDEN,
            'default' => 'animated',
            'prefix_class' => 'will__',
            'condition' => [$prefix . 'select_animation!' => 'none'],
        ]
    );

    $widget->add_control(
        $prefix .
        'more_options',
        [
            'label' => esc_html__('Animation Options', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]
    );

    $widget->start_controls_tabs(
        $prefix .
        'animation_options_tabs'
    );

    $widget->start_controls_tab(
        $prefix .
        'basic_tab',
        [
            'label' => esc_html__('Basic', 'pe-core'),
        ]
    );

    $widget->add_control(
        $prefix .
        'duration',
        [
            'label' => esc_html__('Duration', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0.1,
            'step' => 0.1,
            'default' => 1.5
        ]
    );

    $widget->add_control(
        $prefix .
        'delay',
        [
            'label' => esc_html__('Delay', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'step' => 0.1,
            'default' => 0
        ]
    );

    $widget->add_control(
        $prefix .
        'stagger',
        [
            'label' => esc_html__('Stagger', 'pe-core'),
            'description' => esc_html__('Delay between animated elements (for multiple element animation types)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => -5,
            'max' => 5,
            'step' => 0.01,
            'default' => 0.1,
        ]
    );

    if ($widget->get_name() === 'peshowcaserounded') {

        $widget->add_control(
            $prefix .
            'stagger_from',
            [
                'label' => esc_html__('Stagger From', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'start',
                'options' => [
                    'random' => esc_html__('Random', 'pe-core'),
                    'start' => esc_html__('Start', 'pe-core'),
                    'center' => esc_html__('Center', 'pe-core'),
                    'end' => esc_html__('End', 'pe-core'),
                ],
            ]
        );

    }

    $widget->add_control(
        $prefix .
        'scrub',
        [
            'label' => esc_html__('Scrub', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'scrubbed__true',
            'prefix_class' => '',
            'default' => '',
            'render_type' => 'template',
            'description' => esc_html__('Animation will follow scrolling behavior of the page.', 'pe-core'),
        ]
    );

    $widget->add_control(
        $prefix .
        'pin',
        [
            'label' => esc_html__('Pin', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'pinned__true',
            'prefix_class' => '',
            'default' => '',
            'render_type' => 'template',
            'description' => esc_html__('Animation will be pinned to window during animation.', 'pe-core'),
        ]
    );

    $widget->add_control(
        $prefix .
        'repeat',
        [
            'label' => esc_html__('Repeat', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => 'false',
            'render_type' => 'template',
            'description' => esc_html__('Animation will be restarted when leaveing/entering the viewport.', 'pe-core'),

        ]
    );

    $widget->add_control(
        $prefix .
        'mobile_pin',
        [
            'label' => esc_html__('Pin Mobile Devices', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'prefix_class' => 'mobile__pin__',
            'default' => 'false',
            'condition' => [
                $prefix .
                'pin' => 'pinned__true',
            ],
            'description' => esc_html__('Pinning large sections/containers at mobile devices may cause issues.', 'pe-core'),
        ]
    );



    $widget->end_controls_tab();

    $widget->start_controls_tab(
        $prefix .
        'advanced_tab',
        [
            'label' => esc_html__('Advanced', 'pe-core'),
        ]
    );

    $widget->add_control(
        $prefix .
        'animation_easing',
        [
            'label' => esc_html__('Easing', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'default',
            'description' => esc_html__('Will be used as intro animation.', 'pe-core'),
            'options' => [
                'default' => esc_html__('Default', 'pe-core'),
                'none' => esc_html__('None', 'pe-core'),
                'power1' => esc_html__('Power 1', 'pe-core'),
                'power2' => esc_html__('Power 2', 'pe-core'),
                'power3' => esc_html__('Power 3', 'pe-core'),
                'power4' => esc_html__('Power 4', 'pe-core'),
                'expo' => esc_html__('Expo', 'pe-core'),
                'circ' => esc_html__('Circ', 'pe-core'),
                'sine' => esc_html__('Sine', 'pe-core'),
            ],
            'label_block' => false,
        ]
    );

    $widget->add_control(
        $prefix .
        'easing_behavior',
        [
            'label' => esc_html__('Behavior', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'in',
            'description' => esc_html__('Will be used as intro animation.', 'pe-core'),
            'options' => [
                'in' => esc_html__('In', 'pe-core'),
                'out' => esc_html__('Out', 'pe-core'),
                'inOut' => esc_html__('In - Out', 'pe-core'),
            ],
            'label_block' => false,
            'condition' => [
                $prefix .
                'animation_easing!' => ['default', 'none'],
            ]
        ]
    );

    $widget->add_control(
        $prefix .
        'pinned_target',
        [
            'label' => esc_html__('Pin Target', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => esc_html__('Eg: #container2', 'pe-core'),
            'description' => esc_html__('You can enter a container id/class which the element will be pinned during animation.', 'pe-core'),

        ]
    );


    $widget->add_control(
        $prefix .
        'start_references',
        [
            'label' => esc_html__('Start References', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'after',
        ]
    );

    $widget->add_control(
        $prefix .
        'references_notice',
        [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
	           This references below are adjusts the animation start/end positions on the screen. <b>For Example: If you select <u>'Top' for item reference point</u> and <u>'Bottom' for the window reference point</u>; animation will start when item's top edge enters the window's bottom edge.</b></div>",
        ]
    );

    $widget->add_control(
        $prefix .
        'item_ref_start',
        [
            'label' => esc_html__('Item Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'default' => 'top',
            'toggle' => false,
        ]
    );

    $widget->add_control(
        $prefix .
        'window_ref_start',
        [
            'label' => esc_html__('Window Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'default' => 'bottom',
            'toggle' => false,
        ]
    );

    $widget->add_control(
        $prefix .
        'end_references',
        [
            'label' => esc_html__('End References', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'after',
        ]
    );

    $widget->add_control(
        $prefix .
        'end_notice',
        [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>For scrubbed/pinned animations only.</div>",
        ]
    );

    $widget->add_control(
        $prefix .
        'item_ref_end',
        [
            'label' => esc_html__('Item Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'default' => 'bottom',
            'toggle' => false,
        ]
    );

    $widget->add_control(
        $prefix .
        'window_ref_end',
        [
            'label' => esc_html__('Window Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'default' => 'top',
            'toggle' => false,
        ]
    );



    $widget->add_control(
        $prefix .
        'animate_out',
        [
            'label' => esc_html__('Animate Out', 'pe-core'),
            'description' => esc_html__('Animation will be played backwards when leaving from viewport. (For scrubbed/pinned animations)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'default' => 'false',
            'return_value' => 'true',

        ]
    );

    $widget->add_control(
        $prefix .
        'mobile_delay',
        [
            'label' => esc_html__('Mobile Delay', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'step' => 0.1,
        ]
    );

    $active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

    foreach ($active_breakpoints as $breakpoint) {
        $name = $breakpoint->get_name();
        $label = $breakpoint->get_label();
        $value = $breakpoint->get_value();
        $direction = $breakpoint->get_direction();

        $widget->add_control(
            $prefix . 'anim_breakpoint_' . $name,
            [
                'label' => sprintf(__('%s', 'pe-core'), $label),
                'description' => sprintf(__('%dpx %s-width', 'pe-core'), $value, $direction),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => [
                    $prefix . 'select_animation' => 'animateWidth',
                ]
            ]
        );

        $widget->add_control(
            $prefix . 'anim_breakpoint_size' . $name,
            [
                'label' => sprintf(__('%dpx %s-width', 'pe-core'), $value, $direction),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'default',
                'condition' => [
                    $prefix . 'select_animation' => 'animateWidth',
                ]
            ]
        );

        $widget->add_control(
            $prefix .
            'animate_width' . $name,
            [
                'label' => sprintf(__('Animate Width', 'pe-core'), $label),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    $prefix . 'select_animation' => 'animateWidth',
                ]
            ]
        );


    }

    $widget->end_controls_tab();

    $widget->end_controls_tabs();

    if ($section) {
        $widget->end_controls_section();
    }

}

function pe_general_animation($widget, $prefix = '')
{

    $settings = $widget->get_settings_for_display();

    $out = $settings[$prefix . 'animate_out'] ? $settings[$prefix . 'animate_out'] : 'false';


    if ($settings[$prefix . 'mask_type'] === 'square' && $settings[$prefix . 'select_animation'] === 'customMask') {
        $squareStart = ';square_start=inset(' . $settings[$prefix . 'square_mask_start']['top'] . '% ' . $settings[$prefix . 'square_mask_start']['right'] . '% ' . $settings[$prefix . 'square_mask_start']['bottom'] . '% ' . $settings[$prefix . 'square_mask_start']['left'] . '% ' . 'round ' . $settings[$prefix . 'square_mask_radius'] . 'px)';
        $squareEnd = ';square_end=inset(' . $settings[$prefix . 'square_mask_end']['top'] . '% ' . $settings[$prefix . 'square_mask_end']['right'] . '% ' . $settings[$prefix . 'square_mask_end']['bottom'] . '% ' . $settings[$prefix . 'square_mask_end']['left'] . '% ' . 'round ' . $settings[$prefix . 'square_mask_radius_end'] . 'px)';
    } else {
        $squareStart = '';
        $squareEnd = '';
    }

    if ($settings[$prefix . 'mask_type'] === 'circle' && $settings[$prefix . 'select_animation'] === 'customMask') {
        $circleStart = ';circle_start=circle(' . $settings[$prefix . 'circle_size_start']['size'] . '% at ' . $settings[$prefix . 'circle_left_pos_start']['size'] . '% ' . $settings[$prefix . 'circle_top_pos_start']['size'] . '%)';
        $circleEnd = ';circle_end=circle(' . $settings[$prefix . 'circle_size_end']['size'] . '% at ' . $settings[$prefix . 'circle_left_pos_end']['size'] . '% ' . $settings[$prefix . 'circle_top_pos_end']['size'] . '%)';
    } else {
        $circleStart = '';
        $circleEnd = '';
    }

    if ($settings[$prefix . 'select_animation'] === 'animateWidth') {
        $animateWidth = ';animate_width=' . $settings[$prefix . 'animate_width']['size']['unit'];

        $active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

        foreach ($active_breakpoints as $breakpoint) {
            $name = $breakpoint->get_name();
            if (
                isset($settings[$prefix .
                    'animate_width' . $name])
            ) {
                $animateWidth .= ';animate_width_' . $name . '=' . $settings[$prefix .
                    'animate_width' . $name]['size'];
            }

        }
    } else {
        $animateWidth = '';
    }

    $easing = 'default';
    if ($settings[$prefix . 'animation_easing'] !== 'default' && $settings[$prefix . 'animation_easing'] !== 'none') {
        $easing = $settings[$prefix . 'animation_easing'] . '.' . $settings[$prefix . 'easing_behavior'];
    } else if ($settings[$prefix . 'animation_easing'] === 'none') {
        $easing = 'none';
    }

    $staggerFrom = '';
    if ($widget->get_name() === 'peshowcaserounded') {
        $staggerFrom = ';staggerFrom=' . $settings[$prefix . 'stagger_from'];
    }

    $dataset = '{' .
        'id=' . $widget->get_id() . '' .
        ';duration=' . $settings[$prefix . 'duration'] . '' .
        ';delay=' . $settings[$prefix . 'delay'] . '' .
        ';mobile_delay=' . $settings[$prefix . 'mobile_delay'] . '' .
        ';stagger=' . $settings[$prefix . 'stagger'] . '' .
        $staggerFrom .
        ';pin=' . $settings[$prefix . 'pin'] . '' .
        ';mobilePin=' . $settings[$prefix . 'mobile_pin'] . '' .
        ';pinTarget=' . $settings[$prefix . 'pinned_target'] . '' .
        ';scrub=' . $settings[$prefix . 'scrub'] . '' .
        ';repeat=' . $settings[$prefix . 'repeat'] . '' .
        ';item_ref_start=' . $settings[$prefix . 'item_ref_start'] . '' .
        ';window_ref_start=' . $settings[$prefix . 'window_ref_start'] . '' .
        ';item_ref_end=' . $settings[$prefix . 'item_ref_end'] . '' .
        ';window_ref_end=' . $settings[$prefix . 'window_ref_end'] . '' .
        ';start_scale=' . $settings[$prefix . 'gen_start_scale'] . '' .
        ';end_scale=' . $settings[$prefix . 'gen_end_scale'] . '' .
        ';out=' . $out . '' .
        ';easing=' . $easing . '' .
        ';fade_blur=' . $settings[$prefix . 'fade_blur'] . '' .
        ';mask_start=' . $settings[$prefix . 'mask_type'] . '' . $squareStart . $squareEnd . $circleStart . $circleEnd .
        '}';

    $animation = $settings[$prefix . 'select_animation'] !== 'none' ? $settings[$prefix . 'select_animation'] : '';

    //Scroll Button Attributes
    $widget->add_render_attribute(
        'animation_settings',
        [
            'data-anim-general' => 'true',
            'data-animation' => $animation,
            'data-anim-settings' => $dataset,

        ]
    );

    $animationSettings = $settings[$prefix . 'select_animation'] !== 'none' ? $widget->get_render_attribute_string('animation_settings') : '';
    return $animationSettings;

}

function pe_image_animation_settings($widget)
{

    $widget->start_controls_section(
        'section_animate',
        [
            'label' => __('Animations', 'pe-core'),
        ]
    );

    $widget->add_control(
        'image_select_animation',
        [
            'label' => esc_html__('Select Animation', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'description' => esc_html__('Will be used as intro animation.', 'pe-core'),
            'options' => [
                'none' => esc_html__('None', 'pe-core'),
                'scale' => esc_html__('Scale', 'pe-core'),
                'block' => esc_html__('Block', 'pe-core'),
                'mask' => esc_html__('Mask', 'pe-core'),

            ],
            'label_block' => true,
        ]
    );

    $widget->add_control(
        'image_mask_type',
        [
            'label' => esc_html__('Mask Type', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'square',
            'options' => [
                'square' => esc_html__('Square', 'pe-core'),
                'circle' => esc_html__('Circle', 'pe-core'),
                'triangle' => esc_html__('Triangle', 'pe-core'),
                'rhombus' => esc_html__('Rhombus', 'pe-core'),
                'hexagon' => esc_html__('Hexagon', 'pe-core'),
                'left_arrow' => esc_html__('Left Arrow', 'pe-core'),
                'right_arrow' => esc_html__('Right Arrow', 'pe-core'),
                'left_chevron' => esc_html__('Left Chevron', 'pe-core'),
                'right_chevron' => esc_html__('Right Chevron', 'pe-core'),
                'star' => esc_html__('Star', 'pe-core'),
                'close' => esc_html__('Close', 'pe-core'),
            ],
            'label_block' => true,
            'condition' => [
                'image_select_animation' => 'mask',
            ]
        ]
    );

    $widget->add_control(
        'image_square_mask_start',
        [
            'label' => esc_html__('Start Mask', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['%'],
            'default' => [
                'top' => 10,
                'right' => 20,
                'bottom' => 23,
                'left' => 50,
                'unit' => '%',
                'isLinked' => false,
            ],
            'condition' => [
                'image_mask_type' => 'square',
                'image_select_animation' => 'mask',
            ]
        ]
    );

    $widget->add_control(
        'image_square_mask_end',
        [
            'label' => esc_html__('End Mask', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['%'],
            'default' => [
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0,
                'unit' => '%',
                'isLinked' => false,
            ],
            'condition' => [
                'image_mask_type' => 'square',
                'image_select_animation' => 'mask',
            ]
        ]
    );

    $widget->add_control(
        'image_square_mask_radius',
        [
            'label' => esc_html__('Square Radius (Start)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 0,
            'condition' => [
                'image_mask_type' => 'square',
                'image_select_animation' => ['mask', 'customMask'],
            ]
        ]
    );

    $widget->add_control(
        'image_square_mask_radius_end',
        [
            'label' => esc_html__('Square Radius (End)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 0,
            'condition' => [
                'image_mask_type' => 'square',
                'image_select_animation' => ['mask', 'customMask'],
            ]
        ]
    );



    $widget->start_controls_tabs(
        'image_circle_tabs',
        [
            'condition' => [
                'mask_type' => 'circle',
            ]
        ]

    );

    $widget->start_controls_tab(
        'image_circle_start_tab',
        [
            'label' => esc_html__('Start', 'pe-core'),
        ]
    );

    $widget->add_responsive_control(
        'image_circle_size_start',
        [
            'label' => esc_html__('Circle Size', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 50,
            ],
            'condition' => [
                'image_mask_type' => 'circle',
            ]
        ]
    );

    $widget->add_responsive_control(
        'image_circle_top_pos_start',
        [
            'label' => esc_html__('Circle Top Position', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 50,
            ],
            'condition' => [
                'image_mask_type' => 'circle',
            ]
        ]
    );

    $widget->add_responsive_control(
        'image_circle_left_pos_start',
        [
            'label' => esc_html__('Circle Left Position', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 50,
            ],
            'condition' => [
                'image_mask_type' => 'circle',
            ]
        ]
    );


    $widget->end_controls_tab();

    $widget->start_controls_tab(
        'image_circle_end_tab',
        [
            'label' => esc_html__('End', 'pe-core'),
        ]
    );

    $widget->add_responsive_control(
        'image_circle_size_end',
        [
            'label' => esc_html__('Circle Size', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 50,
            ],
            'condition' => [
                'image_mask_type' => 'circle',
            ]
        ]
    );

    $widget->add_responsive_control(
        'image_circle_top_pos_end',
        [
            'label' => esc_html__('Circle Top Position', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 50,
            ],
            'condition' => [
                'image_mask_type' => 'circle',
            ]
        ]
    );

    $widget->add_responsive_control(
        'image_circle_left_pos_end',
        [
            'label' => esc_html__('Circle Left Position', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 50,
            ],
            'condition' => [
                'image_mask_type' => 'circle',
            ]
        ]
    );


    $widget->end_controls_tab();

    $widget->end_controls_tabs();

    $widget->add_control(
        'image_transform_origin',
        [
            'label' => esc_html__('Animation Origin', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top left' => [
                    'title' => esc_html__('Top Left', 'pe-core'),
                    'icon' => 'material-icons md-north_west',
                ],
                'top center' => [
                    'title' => esc_html__('Top Center', 'pe-core'),
                    'icon' => 'material-icons md-north'
                ],
                'top right' => [
                    'title' => esc_html__('Top Right', 'pe-core'),
                    'icon' => 'material-icons md-north_east',
                ],
                'left center' => [
                    'title' => esc_html__('Left', 'pe-core'),
                    'icon' => 'material-icons md-west',
                ],
                'center center' => [
                    'title' => esc_html__('Center Center', 'pe-core'),
                    'icon' => 'material-icons md-filter_center_focus',
                ],
                'right center' => [
                    'title' => esc_html__('Right', 'pe-core'),
                    'icon' => 'material-icons md-east',
                ],
                'bottom left' => [
                    'title' => esc_html__('Bottom Left', 'pe-core'),
                    'icon' => 'material-icons md-south_west',
                ],
                'bottom center' => [
                    'title' => esc_html__('Bottom Center', 'pe-core'),
                    'icon' => 'material-icons md-south'
                ],
                'bottom right' => [
                    'title' => esc_html__('Bottom Right', 'pe-core'),
                    'icon' => 'material-icons md-south_east',
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .single-image[data-anim-image=true]' => 'transform-origin: {{VALUE}};',
                '{{WRAPPER}} .single-image[data-anim-image=true] img' => 'transform-origin: {{VALUE}};',
            ],
            'default' => 'center center',
            'label_block' => true,
            'toggle' => false,
            'condition' => [
                'image_select_animation' => 'scale',
            ]
        ]
    );

    $widget->add_control(
        'image_start_scale',
        [
            'label' => esc_html__('Start Scale', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 100,
            'step' => 0.1,
            'default' => 0,
            'condition' => [
                'image_select_animation' => 'scale',
            ]

        ]
    );

    $widget->add_control(
        'image_end_scale',
        [
            'label' => esc_html__('End Scale', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 100,
            'step' => 0.1,
            'default' => 1,
            'condition' => [
                'image_select_animation' => 'scale',
            ]

        ]
    );

    $widget->add_control(
        'image_block_direction',
        [
            'label' => esc_html__('Image Type', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'up' => esc_html__('Up', 'pe-core'),
                'down' => esc_html__('Down', 'pe-core'),
                'left' => esc_html__('Left', 'pe-core'),
                'right' => esc_html__('Right', 'pe-core'),
            ],
            'default' => 'up',
            'condition' => [
                'image_select_animation' => 'block',
            ],
            'label_block' => true
        ]
    );

    $widget->add_control(
        'image_block_color',
        [
            'label' => esc_html__('Block Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .single-image[data-animation=block]::after' => 'background-color: {{VALUE}}',
            ],
            'condition' => [
                'image_select_animation' => 'block',
            ],
        ]
    );


    $widget->add_control(
        'image_inner_scale',
        [
            'label' => esc_html__('Inner Scale', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => 'true',
        ]
    );

    $widget->add_control(
        'image_ia_more_options',
        [
            'label' => esc_html__('Animation Options', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]
    );

    $widget->start_controls_tabs(
        'image_animation_options_tabs'
    );

    $widget->start_controls_tab(
        'image_basic_tab',
        [
            'label' => esc_html__('Basic', 'pe-core'),
        ]
    );

    $widget->add_control(
        'image_duration',
        [
            'label' => esc_html__('Duration', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0.1,
            'step' => 0.1,
            'default' => 1.5
        ]
    );

    $widget->add_control(
        'image_delay',
        [
            'label' => esc_html__('Delay', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'step' => 0.1,
            'default' => 0
        ]
    );

    $widget->add_control(
        'image_stagger',
        [
            'label' => esc_html__('Stagger', 'pe-core'),
            'description' => esc_html__('Delay between animated elements (for multiple element animation types)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'step' => 0.01,
            'default' => 0.1,
        ]
    );


    $widget->add_control(
        'image_scrub',
        [
            'label' => esc_html__('Scrub', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => 'false',
            'render_type' => 'template',
            'description' => esc_html__('Animation will follow scrolling behavior of the page.', 'pe-core'),
        ]
    );

    $widget->add_control(
        'image_pin',
        [
            'label' => esc_html__('Pin', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'render_type' => 'template',
            'default' => 'false',
            'description' => esc_html__('Animation will be pinned to window during animation.', 'pe-core'),
        ]
    );

    $widget->add_control(
        'image_repeat',
        [
            'label' => esc_html__('Repeat', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => 'false',
            'render_type' => 'template',
            'description' => esc_html__('Animation will be restarted when leaveing/entering the viewport.', 'pe-core'),
        ]
    );


    $widget->end_controls_tab();

    $widget->start_controls_tab(
        'image_advanced_tab',
        [
            'label' => esc_html__('Advanced', 'pe-core'),
        ]
    );


    $widget->add_control(
        'image_animation_easing',
        [
            'label' => esc_html__('Easing', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'default',
            'description' => esc_html__('Will be used as intro animation.', 'pe-core'),
            'options' => [
                'default' => esc_html__('Default', 'pe-core'),
                'none' => esc_html__('None', 'pe-core'),
                'power1' => esc_html__('Power 1', 'pe-core'),
                'power2' => esc_html__('Power 2', 'pe-core'),
                'power3' => esc_html__('Power 3', 'pe-core'),
                'power4' => esc_html__('Power 4', 'pe-core'),
                'expo' => esc_html__('Expo', 'pe-core'),
                'circ' => esc_html__('Circ', 'pe-core'),
                'sine' => esc_html__('Sine', 'pe-core'),
            ],
            'label_block' => false,
        ]
    );


    $widget->add_control(
        'image_easing_behavior',
        [
            'label' => esc_html__('Behavior', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'in',
            'description' => esc_html__('Will be used as intro animation.', 'pe-core'),
            'options' => [
                'in' => esc_html__('In', 'pe-core'),
                'out' => esc_html__('Out', 'pe-core'),
                'inOut' => esc_html__('In - Out', 'pe-core'),
            ],
            'label_block' => false,
            'condition' => [
                'image_animation_easing!' => ['default', 'none'],
            ]
        ]
    );



    $widget->add_control(
        'image_anim_pin_target',
        [
            'label' => esc_html__('Pin Target', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => esc_html__('Eg: #container2', 'pe-core'),
            'description' => esc_html__('You can enter a container id/class which the element will be pinned during animation.', 'pe-core'),

        ]
    );


    $widget->add_control(
        'image_start_references',
        [
            'label' => esc_html__('Start References', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'after',
        ]
    );

    $widget->add_control(
        'image_references_notice',
        [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
	           This references below are adjusts the animation start/end positions on the screen. <b>For Example: If you select <u>'Top' for item reference point</u> and <u>'Bottom' for the window reference point</u>; animation will start when item's top edge enters the window's bottom edge.</b></div>",


        ]
    );

    $widget->add_control(
        'image_item_ref_start',
        [
            'label' => esc_html__('Item Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'default' => 'top',
            'toggle' => false,
        ]
    );

    $widget->add_control(
        'image_window_ref_start',
        [
            'label' => esc_html__('Window Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'default' => 'bottom',
            'toggle' => false,
        ]
    );

    $widget->add_control(
        'image_end_references',
        [
            'label' => esc_html__('End References', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'after',
        ]
    );

    $widget->add_control(
        'image_end_notice',
        [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>For scrubbed/pinned animations only.</div>",
        ]
    );

    $widget->add_control(
        'image_item_ref_end',
        [
            'label' => esc_html__('Item Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'default' => 'bottom',
            'toggle' => false,
        ]
    );

    $widget->add_control(
        'image_window_ref_end',
        [
            'label' => esc_html__('Window Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'default' => 'top',
            'toggle' => false,
        ]
    );


    $widget->add_control(
        'image_animate_out',
        [
            'label' => esc_html__('Animate Out', 'pe-core'),
            'description' => esc_html__('Animation will be played backwards when leaving from viewport. (For scrubbed/pinned animations)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'default' => 'false',
            'return_value' => 'true',

        ]
    );


    $widget->end_controls_tab();

    $widget->end_controls_tabs();

    $widget->end_controls_section();


}

function pe_image_animation($widget)
{

    $settings = $widget->get_settings_for_display();

    $out = $settings['image_animate_out'] ? $settings['image_animate_out'] : 'false';


    if ($settings['image_mask_type'] === 'square') {

        $squareStart = ';square_start=inset(' . $settings['image_square_mask_start']['top'] . '% ' . $settings['image_square_mask_start']['right'] . '% ' . $settings['image_square_mask_start']['bottom'] . '% ' . $settings['image_square_mask_start']['left'] . '% ' . 'round ' . $settings['image_square_mask_radius'] . 'px)';

        $squareEnd = ';square_end=inset(' . $settings['image_square_mask_end']['top'] . '% ' . $settings['image_square_mask_end']['right'] . '% ' . $settings['image_square_mask_end']['bottom'] . '% ' . $settings['image_square_mask_end']['left'] . '% ' . 'round ' . $settings['image_square_mask_radius_end'] . 'px)';

    } else {

        $squareStart = '';
        $squareEnd = '';
    }


    if ($settings['image_mask_type'] === 'circle') {

        $circleStart = ';circle_start=circle(' . $settings['image_circle_size_start']['size'] . '% at ' . $settings['image_circle_left_pos_start']['size'] . '% ' . $settings['image_circle_top_pos_start']['size'] . '%)';

        $circleEnd = ';circle_end=circle(' . $settings['image_circle_size_end']['size'] . '% at ' . $settings['image_circle_left_pos_end']['size'] . '% ' . $settings['image_circle_top_pos_end']['size'] . '%)';

    } else {

        $circleStart = '';
        $circleEnd = '';
    }

    $easing = 'default';
    if ($settings['image_animation_easing'] !== 'default' && $settings['image_animation_easing'] !== 'none') {
        $easing = $settings['image_animation_easing'] . '.' . $settings['image_easing_behavior'];
    } else if ($settings['image_animation_easing'] === 'none') {
        $easing = 'none';
    }

    $dataset = '{' .
        'id=' . $widget->get_id() . '' .
        ';duration=' . $settings['image_duration'] . '' .
        ';delay=' . $settings['image_delay'] . '' .
        ';stagger=' . $settings['image_stagger'] . '' .
        ';pin=' . $settings['image_pin'] . '' .
        ';pinTarget=' . $settings['image_anim_pin_target'] . '' .
        ';scrub=' . $settings['image_scrub'] . '' .
        ';repeat=' . $settings['image_repeat'] . '' .
        ';item_ref_start=' . $settings['image_item_ref_start'] . '' .
        ';window_ref_start=' . $settings['image_window_ref_start'] . '' .
        ';item_ref_end=' . $settings['image_item_ref_end'] . '' .
        ';window_ref_end=' . $settings['image_window_ref_end'] . '' .
        ';out=' . $out . '' .
        ';start_scale=' . $settings['image_start_scale'] . '' .
        ';end_scale=' . $settings['image_end_scale'] . '' .
        ';inner_scale=' . $settings['image_inner_scale'] . '' .
        ';block_direction=' . $settings['image_block_direction'] . '' .
        ';easing=' . $easing . '' .
        ';mask_start=' . $settings['image_mask_type'] . '' . $squareStart . $squareEnd . $circleStart . $circleEnd .
        '}';



    $animation = $settings['image_select_animation'] !== 'none' ? $settings['image_select_animation'] : '';

    //Scroll Button Attributes
    $widget->add_render_attribute(
        'image_animation_settings',
        [
            'data-anim-image' => 'true',
            'data-animation' => $animation,
            'data-animation-direction' => $settings['image_block_direction'],
            'data-settings' => $dataset,

        ]
    );

    $animationSettings = $settings['image_select_animation'] !== 'none' ? $widget->get_render_attribute_string('image_animation_settings') : '';

    return $animationSettings;



}

function pe_text_animation_settings($widget, $multiple = false)
{


    $widget->start_controls_section(
        'section_animate',
        [
            'label' => __('Animations', 'pe-core'),
        ]
    );

    $widget->add_control(
        'text_hover_effects',
        [
            'label' => esc_html__('Hover Effects', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'text--hover',
            'prefix_class' => '',
            'default' => '',

        ]
    );

    $widget->add_control(
        'select_hover',
        [
            'label' => esc_html__('Select Effect', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none' => esc_html__('None', 'pe-core'),
                'rotateX' => esc_html__('Rotate X', 'pe-core'),
                'rotateY' => esc_html__('Rotate Y', 'pe-core'),
                'scaleY' => esc_html__('Scale Y', 'pe-core'),
                'scaleX' => esc_html__('Scale X', 'pe-core'),

            ],
            'label_block' => true,
            'condition' => ['text_hover_effects' => 'text--hover'],
        ]
    );

    $widget->add_control(
        'insert2_notice',
        [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => '<div class="elementor-panel-notice elementor-panel-alert elementor-panel-alert-info">	
	           <span>"Line" based animations deprecated because of inserted elements.</span></div>',
            'condition' => ['additional' => 'insert'],
        ]
    );

    $widget->add_control(
        'dynamic2_notice',
        [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => '<div class="elementor-panel-notice elementor-panel-alert elementor-panel-alert-info">	
	           <span>Scrubbing/pinning deprecated because of the dynamic word.</span></div>',
            'condition' => ['additional' => 'dynamic'],
        ]
    );

    $widget->add_control(
        'select_text_animation',
        [
            'label' => esc_html__('Select Animation', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'description' => esc_html__('Will be used as intro animation.', 'pe-core'),
            'options' => [
                'none' => esc_html__('None', 'pe-core'),
                'charsUp' => esc_html__('Chars Up', 'pe-core'),
                'charsDown' => esc_html__('Chars Down', 'pe-core'),
                'charsRight' => esc_html__('Chars Right', 'pe-core'),
                'charsLeft' => esc_html__('Chars Left', 'pe-core'),
                'wordsUp' => esc_html__('Words Up', 'pe-core'),
                'wordsDown' => esc_html__('Words Down', 'pe-core'),
                'linesUp' => esc_html__('Lines Up', 'pe-core'),
                'linesDown' => esc_html__('Lines Down', 'pe-core'),
                'charsScaleUp' => esc_html__('Chars Scale Up', 'pe-core'),
                'charsScaleDown' => esc_html__('Chars Scale Down', 'pe-core'),
                'charsFlipUp' => esc_html__('Chars Flip Up', 'pe-core'),
                'charsFlipDown' => esc_html__('Chars Flip Down', 'pe-core'),
                'linesMask' => esc_html__('Lines Mask', 'pe-core'),
                'linesHighlight' => esc_html__('Highlight Lines', 'pe-core'),
                'wordsHighlight' => esc_html__('Highlight Words', 'pe-core'),
                'linesTypeWrite' => esc_html__('Typewriter', 'pe-core'),
                'wordsJustifyExpand' => esc_html__('Justify Expand', 'pe-core'),
                'wordsJustifyCollapse' => esc_html__('Justify Collapse', 'pe-core'),
            ],
            'label_block' => true,
        ]
    );


    $widget->add_control(
        'justify_reveal',
        [
            'label' => esc_html__('Justify Reveal', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none' => esc_html__('None', 'pe-core'),
                'words-up' => esc_html__('Words Up', 'pe-core'),
                'words-down' => esc_html__('Words Down', 'pe-core'),
            ],
            'condition' => ['select_text_animation' => ['wordsJustifyExpand', 'wordsJustifyCollapse']],

        ]
    );

    $widget->add_control(
        'text_anim_fade',
        [
            'label' => esc_html__('Fade', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'prefix_class' => 'text--anim--fade-',
            'default' => 'false',
            'render_type' => 'template',
            'condition' => ['select_text_animation!' => ['linesHighlight', 'linesMask', 'none']],


        ]
    );


    $widget->add_control(
        'text_more_options',
        [
            'label' => esc_html__('Animation Options', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]
    );

    $widget->start_controls_tabs(
        'text_animation_options_tabs'
    );

    $widget->start_controls_tab(
        'text_basic_tab',
        [
            'label' => esc_html__('Basic', 'pe-core'),
        ]
    );

    $widget->add_control(
        'text_duration',
        [
            'label' => esc_html__('Duration', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0.1,
            'step' => 0.1,
            'default' => 1.5
        ]
    );

    $widget->add_control(
        'text_delay',
        [
            'label' => esc_html__('Delay', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'step' => 0.1,
            'default' => 0
        ]
    );

    $widget->add_control(
        'text_stagger',
        [
            'label' => esc_html__('Stagger', 'pe-core'),
            'description' => esc_html__('Delay between animated elements (for multiple element animation types)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 1,
            'step' => 0.01,
            'default' => 0.1,
        ]
    );

    $textWrapperCond = [];

    if ($widget->get_name() === 'petextwrapper') {
        $textWrapperCond = ['additional!' => 'dynamic'];
    }

    $widget->add_control(
        'text_scrub',
        [
            'label' => esc_html__('Scrub', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => 'false',
            'render_type' => 'template',
            'description' => esc_html__('Animation will follow scrolling behavior of the page.', 'pe-core'),
            'condition' => $textWrapperCond,


        ]
    );

    $widget->add_control(
        'text_pin',
        [
            'label' => esc_html__('Pin', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => 'false',
            'render_type' => 'template',
            'description' => esc_html__('Animation will be pinned to window during animation.', 'pe-core'),
            'condition' => $textWrapperCond,

        ]
    );

    $widget->add_control(
        'text_repeat',
        [
            'label' => esc_html__('Repeat', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => 'false',
            'render_type' => 'template',
            'description' => esc_html__('Animation will be restarted when leaveing/entering the viewport.', 'pe-core'),
            'condition' => $textWrapperCond,
        ]
    );

    $widget->end_controls_tab();

    $widget->start_controls_tab(
        'text_advanced_tab',
        [
            'label' => esc_html__('Advanced', 'pe-core'),
        ]
    );


    $widget->add_control(
        'text_animation_easing',
        [
            'label' => esc_html__('Easing', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'default',
            'description' => esc_html__('Will be used as intro animation.', 'pe-core'),
            'options' => [
                'default' => esc_html__('Default', 'pe-core'),
                'none' => esc_html__('None', 'pe-core'),
                'power1' => esc_html__('Power 1', 'pe-core'),
                'power2' => esc_html__('Power 2', 'pe-core'),
                'power3' => esc_html__('Power 3', 'pe-core'),
                'power4' => esc_html__('Power 4', 'pe-core'),
                'expo' => esc_html__('Expo', 'pe-core'),
                'circ' => esc_html__('Circ', 'pe-core'),
                'sine' => esc_html__('Sine', 'pe-core'),
            ],
            'label_block' => false,
        ]
    );


    $widget->add_control(
        'text_easing_behavior',
        [
            'label' => esc_html__('Behavior', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'in',
            'description' => esc_html__('Will be used as intro animation.', 'pe-core'),
            'options' => [
                'in' => esc_html__('In', 'pe-core'),
                'out' => esc_html__('Out', 'pe-core'),
                'inOut' => esc_html__('In - Out', 'pe-core'),
            ],
            'label_block' => false,
            'condition' => [
                'text_animation_easing!' => ['default', 'none'],
            ]
        ]
    );




    $widget->add_control(
        'text_pin_target',
        [
            'label' => esc_html__('Pin Target', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => esc_html__('Eg: #container2', 'pe-core'),
            'description' => esc_html__('You can enter a container id/class which the element will be pinned during animation.', 'pe-core'),

        ]
    );

    $widget->add_control(
        'text_anim_pin_header',
        [
            'label' => esc_html__('Disable Header Pinning', 'pe-core'),
            'description' => esc_html__('Normally the pin keeps header until completed if it starts on top of the page, you can disable header pin setting this option to "yes".', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
            'render_type' => 'template',
            'prefix_class' => 'header--pin--disabled--',
            'condition' => ['text_pin' => 'true'],
        ]
    );


    $widget->add_control(
        'text_start_references',
        [
            'label' => esc_html__('Start References', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'after',
        ]
    );

    $widget->add_control(
        'text_references_notice',
        [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
	           This references below are adjusts the animation start/end positions on the screen. <b>For Example: If you select <u>'Top' for item reference point</u> and <u>'Bottom' for the window reference point</u>; animation will start when item's top edge enters the window's bottom edge.</b></div>",


        ]
    );

    $widget->add_control(
        'text_item_ref_start',
        [
            'label' => esc_html__('Item Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'default' => 'top',
            'toggle' => false,
        ]
    );

    $widget->add_control(
        'text_window_ref_start',
        [
            'label' => esc_html__('Window Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'default' => 'bottom',
            'toggle' => false,
        ]
    );

    $widget->add_control(
        'text_end_references',
        [
            'label' => esc_html__('End References', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'after',
        ]
    );

    $widget->add_control(
        'text_end_notice',
        [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>For scrubbed/pinned animations only.</div>",
        ]
    );

    $widget->add_control(
        'text_item_ref_end',
        [
            'label' => esc_html__('Item Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'default' => 'bottom',
            'toggle' => false,
        ]
    );

    $widget->add_control(
        'text_window_ref_end',
        [
            'label' => esc_html__('Window Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'default' => 'top',
            'toggle' => false,
        ]
    );


    $widget->add_control(
        'text_animate_out',
        [
            'label' => esc_html__('Animate Out', 'pe-core'),
            'description' => esc_html__('Animation will be played backwards when leaving from viewport. (For scrubbed/pinned animations)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'default' => 'false',
            'return_value' => 'true',

        ]
    );


    $widget->add_control(
        'text_mobile_delay',
        [
            'label' => esc_html__('Mobile Delay', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'step' => 0.1,
        ]
    );


    $widget->end_controls_tab();

    $widget->end_controls_tabs();

    $widget->end_controls_section();



}

function pe_text_animation($widget, $multiple = false)
{

    $settings = $widget->get_settings_for_display();

    $out = $settings['text_animate_out'] ? $settings['text_animate_out'] : 'false';

    if ($widget->get_name() === 'petextwrapper') {
        $inserted = $settings['additional'] === 'insert' ? 'true' : 'false';
    } else {
        $inserted = false;
    }


    $easing = 'default';
    if ($settings['text_animation_easing'] !== 'default' && $settings['text_animation_easing'] !== 'none') {
        $easing = $settings['text_animation_easing'] . '.' . $settings['text_easing_behavior'];
    } else if ($settings['text_animation_easing'] === 'none') {
        $easing = 'none';
    }


    $dataset = '{' .
        'duration=' . $settings['text_duration'] . '' .
        ';delay=' . $settings['text_delay'] . '' .
        ';mobile_delay=' . $settings['text_mobile_delay'] . '' .
        ';stagger=' . $settings['text_stagger'] . '' .
        ';pin=' . $settings['text_pin'] . '' .
        ';pinTarget=' . $settings['text_pin_target'] . '' .
        ';scrub=' . $settings['text_scrub'] . '' .
        ';repeat=' . $settings['text_repeat'] . '' .
        ';item_ref_start=' . $settings['text_item_ref_start'] . '' .
        ';window_ref_start=' . $settings['text_window_ref_start'] . '' .
        ';item_ref_end=' . $settings['text_item_ref_end'] . '' .
        ';window_ref_end=' . $settings['text_window_ref_end'] . '' .
        ';out=' . $out . '' .
        ';fade=' . $settings['text_anim_fade'] . '' .
        ';justifyReveal=' . $settings['justify_reveal'] . '' .
        ';inserted=' . $inserted . '' .
        ';easing=' . $easing . '' .
        '}';


    $animation = $settings['select_text_animation'] !== 'none' ? $settings['select_text_animation'] : '';

    $widget->add_render_attribute(
        'text_animation_attributes',
        [
            'data-animate' => 'true',
            'data-animation' => [$animation],
            'data-settings' => [$dataset],
        ]
    );


    $hover = $settings['text_hover_effects'] === 'text--hover' ? 'true' : 'false';

    $widget->add_render_attribute(
        'text_hover_attributes',
        [
            'data-text-hover' => $hover,
            'data-hover-effect' => $settings['select_hover']
        ]
    );

    $animationAttributes = $settings['select_text_animation'] !== 'none' ? $widget->get_render_attribute_string('text_animation_attributes') : '';
    $hoverAttributes = $settings['text_hover_effects'] === 'text--hover' ? $widget->get_render_attribute_string('text_hover_attributes') : '';

    return $animationAttributes . $hoverAttributes;

}

function pe_button_settings($widget, $link = false, $condition = false, $prefix = '', $section = false, $name = '')
{
    $defaultText = 'Button';
    $frontend = false;

    if ($widget->get_name() === 'peblogposts') {

        $frontend = true;
        if ($prefix === 'post_button') {
            $defaultText = 'Read More';
        } else if ($prefix === 'load_more_button') {
            $defaultText = 'Load More';
        }

    }

    if ($widget->get_name() === 'peprojectfield') {
        $defaultText = 'View Project';
    }

    if ($widget->get_name() === 'pevideo') {
        $defaultText = 'Play';
    }

    if ($widget->get_name() === 'peportfoliosearch') {
        $defaultText = 'Search';
    }

    if ($widget->get_name() === 'peforms') {
        $defaultText = 'Submit';
    }

    if ($widget->get_name() === 'peproductsarchive') {
        $defaultText = 'Load More';
    }

    if ($widget->get_name() === 'peproductelements') {
        $defaultText = 'Add to Cart';
    }

    if ($widget->get_name() === 'peshowcasefullscreenslideshow') {
        $defaultText = 'View Case';
    }

    if ($widget->get_name() === 'pevideo') {
        $defaultText = 'Play';
    }


    if ($section) {

        $widget->start_controls_section(
            $prefix . 'pe_button_section',
            [
                'label' => __($name . ' Button', 'pe-core'),
                'condition' => $condition,
            ]
        );
    }

    $widget->add_control(
        $prefix . 'button_text',
        [
            'label' => esc_html__('Button Text', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => esc_html__('Write your text here', 'pe-core'),
            'default' => $defaultText,
            'condition' => $condition,
            'frontend_available' => $frontend,
        ]
    );

    if ($link) {

        $pop = $widget->get_name() === 'pebutton' ? ['open-popup' => esc_html__('Open Popup', 'pe-core')] : [];

        $widget->add_control(
            $prefix . 'interaction',
            [
                'label' => esc_html__('Interaction', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'link',
                'options' => [
                    'link' => esc_html__('Link', 'pe-core'),
                    'to--page' => esc_html__('To Page/Post', 'pe-core'),
                    'pe--scroll--button' => esc_html__('Scroll To', 'pe-core'),
                    ...$pop,
                ],
                'condition' => $condition,
                'frontend_available' => $frontend,

            ]
        );

        if ($widget->get_name() === 'pebutton') {

            $templates = [];

            $templates = get_posts([
                'post_type' => 'elementor_library',
                'numberposts' => -1
            ]);

            foreach ($templates as $template) {
                $templates[$template->ID] = $template->post_title;
            }


            $widget->add_control(
                'select_template',
                [
                    'label' => __('Select Template', 'pe-core'),
                    'description' => __('You can create your template via "Templates > Saved Templates > Add New Template" on your admin dashboard.', 'pe-core'),
                    'label_block' => false,
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $templates,
                    'condition' => ['interaction' => 'open-popup'],
                    'frontend_available' => $frontend,

                ]
            );


        }

        $widget->add_control(
            $prefix . 'select_page',
            [
                'label' => esc_html__('Select Page / Post', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'condition' => [
                    $prefix . 'interaction' => 'to--page',
                ],
                'groups' => get_grouped_page_options(),
                'frontend_available' => $frontend,
            ]
        );

        $widget->add_control(
            $prefix . 'scroll_target',
            [
                'label' => esc_html__('Scroll To', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Eg: #aboutContainer', 'pe-core'),
                'description' => esc_html__('Enter a target ID or exact number of desired scroll position ("0" for scrolling top)', 'pe-core'),
                'condition' => [$prefix . 'interaction' => 'pe--scroll--button'],
                'frontend_available' => $frontend,
            ]
        );

        $widget->add_control(
            $prefix . 'scroll_duration',
            [
                'label' => esc_html__('Duration', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0.1,
                'step' => 0.1,
                'default' => 1,
                'description' => esc_html__('Seconds', 'pe-core'),
                'condition' => [$prefix . 'interaction' => 'pe--scroll--button'],
                'frontend_available' => $frontend,
            ]
        );

        $cond = [];
        if ($condition) {
            $key = array_key_first($condition);
            $value = $condition[$key];
            $cond = [$key => $value];
        }


        $widget->add_control(
            $prefix . 'link',
            [
                'label' => esc_html__('Link', 'pe-core'),
                'type' => \Elementor\Controls_Manager::URL,
                'options' => ['url', 'is_external', 'nofollow', 'custom_attributes'],
                'default' => [
                    'url' => 'http://',
                    'is_external' => false,
                    'nofollow' => true,
                    // 'custom_attributes' => '',
                ],
                'label_block' => true,
                'condition' => [$prefix . 'interaction' => 'link', ...$cond],
                'frontend_available' => $frontend,
            ]
        );

    }



    $widget->add_control(
        $prefix . 'button_style',
        [
            'label' => esc_html__('Button Style', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'simple' => [
                    'title' => esc_html__('Simple', 'pe-core'),
                ],
                'underlined' => [
                    'title' => esc_html__('Underlined', 'pe-core'),
                ],
                'rounded' => [
                    'title' => esc_html__('Rounded', 'pe-core'),
                ],
                'background' => [
                    'title' => esc_html__('Background', 'pe-core'),
                ],
                'bordered' => [
                    'title' => esc_html__('Bordered', 'pe-core'),
                ],
                'sharp' => [
                    'title' => esc_html__('Sharp', 'pe-core'),
                ],
                'seperated' => [
                    'title' => esc_html__('Seperated', 'pe-core'),
                ],
                'stylized' => [
                    'title' => esc_html__('Stylized', 'pe-core'),
                ],
                'covered' => [
                    'title' => esc_html__('Covered', 'pe-core'),
                ],
                'framed' => [
                    'title' => esc_html__('Framed', 'pe-core'),
                ],
                'marquee' => [
                    'title' => esc_html__('Marquee', 'pe-core'),
                ],
                // 'circle' => [
                //     'title' => esc_html__('Circle', 'pe-core'),
                // ],
            ],
            'default' => 'underlined',
            'prefix_class' => 'bt--',
            'render_type' => 'template',
            'toggle' => false,
            'label_block' => true,
            'condition' => $condition,
            'frontend_available' => $frontend,
        ]
    );


    $widget->add_responsive_control(
        $prefix . 'corner_borders_size',
        [
            'label' => esc_html__('Borders Size', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
            ],
            'condition' => [
                $prefix . 'button_style' => 'framed'
            ],
            'selectors' => [
                '{{WRAPPER}} .pe--button--wrapper' => '--borderSize: {{SIZE}}{{UNIT}}',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . 'corner_borders_width',
        [
            'label' => esc_html__('Borders Width', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
            ],
            'condition' => [
                $prefix . 'button_style' => 'framed'
            ],
            'selectors' => [
                '{{WRAPPER}} .pe--button--wrapper' => '--borderWidth: {{SIZE}}{{UNIT}}',
            ],
        ]
    );



    $widget->add_control(
        $prefix . 'marquee_direction',
        [
            'label' => esc_html__('Marquee Direction', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left-to-right' => [
                    'title' => esc_html__('Left To Right', 'pe-core'),
                    'icon' => 'eicon-h-align-right',
                ],
                'right-to-left' => [
                    'title' => esc_html__('Right To Left', 'pe-core'),
                    'icon' => 'eicon-h-align-left',
                ],
            ],
            'default' => 'right-to-left',
            'toggle' => false,
            'label_block' => false,
            'condition' => ['button_style' => 'marquee'],
            'frontend_available' => $frontend,
        ]
    );

    $widget->add_control(
        $prefix . 'marquee_duration',
        [
            'label' => esc_html__('Duration', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 60,
            'step' => 1,
            'default' => 5,
            'condition' => ['button_style' => 'marquee'],
            'selectors' => [
                '{{WRAPPER}} .pb--marquee__inner' => '--duration: {{VALUE}}s;',
            ],
            'condition' => $condition,
        ]
    );



    $widget->add_control(
        $prefix . 'show_icon',
        [
            'label' => esc_html__('Show Icon', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => 'true',
            'condition' => $condition,
            'frontend_available' => $frontend,

        ]
    );

    $widget->add_control(
        $prefix . 'add_sub',
        [
            'label' => esc_html__('Add Sub', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
            'condition' => $condition,
            'frontend_available' => $frontend,

        ]
    );


    $widget->add_control(
        $prefix . 'button_sub',
        [
            'label' => esc_html__('Sub Text', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,

            'condition' => [$prefix . 'add_sub' => 'true'],
            'frontend_available' => $frontend,
        ]
    );




    if ($widget->get_name() !== 'peproductelements') {

        if ($condition) {
            $key = array_key_first($condition);
            $value = $condition[$key];


            $widget->add_control(
                $prefix . 'icon',
                [
                    'type' => \Elementor\Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'material-icons md-arrow_outward',
                        'library' => 'material-design-icons',
                    ],
                    'condition' => [
                        $prefix . 'show_icon' => 'true',
                        $key => $value
                    ],
                ]
            );

        } else {

            $widget->add_control(
                $prefix . 'icon',
                [
                    'type' => \Elementor\Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'material-icons md-arrow_outward',
                        'library' => 'material-design-icons',
                    ],
                    'condition' => [
                        $prefix . 'show_icon' => 'true',
                    ],
                    'frontend_available' => $frontend,
                ]
            );

        }
    } else {
        if ($condition) {
            $key = array_key_first($condition);
            $value = $condition[$key];


            $widget->add_control(
                $prefix . 'icon',
                [
                    'type' => \Elementor\Controls_Manager::ICONS,
                    'condition' => [
                        $prefix . 'show_icon' => 'true',
                        $key => $value
                    ],
                    'frontend_available' => $frontend,
                ]
            );

        } else {

            $widget->add_control(
                $prefix . 'icon',
                [
                    'type' => \Elementor\Controls_Manager::ICONS,
                    'condition' => [
                        $prefix . 'show_icon' => 'true',
                    ],
                    'frontend_available' => $frontend,
                ]
            );

        }
    }





    $widget->add_control(
        $prefix . 'hover_effect',
        [
            'label' => esc_html__('Hover Effect', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'default',
            'options' => [
                'default' => esc_html__('Use Style Presets', 'pe-core'),
                'custom' => esc_html__('Custom', 'pe-core'),
                'none' => esc_html__('None', 'pe-core'),
            ],
            'condition' => $condition,
            'frontend_available' => $frontend,
        ]
    );

    $widget->add_control(
        $prefix . 'background_hover',
        [
            'label' => esc_html__('Background', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none' => esc_html__('None', 'pe-core'),
                'slide-up' => esc_html__('Slide Up', 'pe-core'),
                'slide-down' => esc_html__('Slide Down', 'pe-core'),
                'slide-left' => esc_html__('Slide Left', 'pe-core'),
                'slide-right' => esc_html__('Slide Right', 'pe-core'),
                'zoom-in' => esc_html__('Zoom - In', 'pe-core'),
                'zoom-out' => esc_html__('Zoom - Out', 'pe-core'),
                'background-reveal' => esc_html__('Reveal', 'pe-core'),
                'background-conceal' => esc_html__('Conceal', 'pe-core'),
            ],
            'condition' => [
                $prefix . 'hover_effect' => 'custom',
                $prefix . 'button_style!' => ['stylized', 'covered']

            ],
            'frontend_available' => $frontend,
        ]
    );

    $widget->add_control(
        $prefix . 'text_hover',
        [
            'label' => esc_html__('Text', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none' => esc_html__('None', 'pe-core'),
                'slide-up' => esc_html__('Slide Up', 'pe-core'),
                'slide-down' => esc_html__('Slide Down', 'pe-core'),
                'slide-left' => esc_html__('Slide Left', 'pe-core'),
                'slide-right' => esc_html__('Slide Right', 'pe-core'),
                'chars-up' => esc_html__('Chars Up', 'pe-core'),
                'chars-down' => esc_html__('Chars Down', 'pe-core'),
                'chars-left' => esc_html__('Chars Left', 'pe-core'),
                'chars-right' => esc_html__('Chars Right', 'pe-core'),
                'chars-expand' => esc_html__('Chars Expand', 'pe-core'),
            ],
            'condition' => [
                $prefix . 'hover_effect' => 'custom',
                $prefix . 'button_style!' => ['marquee']
            ],
            'frontend_available' => $frontend,
        ]
    );

    $widget->add_control(
        $prefix . 'icon_hover',
        [
            'label' => esc_html__('Icon Hover', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none' => esc_html__('None', 'pe-core'),
                'slide-up' => esc_html__('Slide Up', 'pe-core'),
                'slide-down' => esc_html__('Slide Down', 'pe-core'),
                'slide-left' => esc_html__('Slide Left', 'pe-core'),
                'slide-right' => esc_html__('Slide Right', 'pe-core'),
                'slide-up-right' => esc_html__('Slide Up-Right', 'pe-core'),
                'slide-up-left' => esc_html__('Slide Up-Left', 'pe-core'),
                'slide-down-right' => esc_html__('Slide Down-Right', 'pe-core'),
                'slide-down-left' => esc_html__('Slide Down-Left', 'pe-core'),
                'background-reveal' => esc_html__('Background Reveal', 'pe-core'),
                'background-conceal' => esc_html__('Background Conceal', 'pe-core'),
            ],
            'condition' => [
                $prefix . 'hover_effect' => 'custom',
                $prefix . 'button_style!' => ['marquee']
            ],
            'frontend_available' => $frontend,
        ]
    );

    if ($section) {
        $widget->end_controls_section();
    }


}

function pe_button_style_settings($widget, $name = 'Button', $prefix = '', $condition = false, $section = true, $selector = '')
{

    $widthSelector = '{{WRAPPER}} ' . $selector . '.pe--button';

    if ($widget->get_name() === 'peportfoliosearch') {
        $widthSelector = '{{WRAPPER}} .pe--portfolio--search--button';
    }
    ;

    if ($widget->get_name() === 'peforms') {
        $widthSelector = '{{WRAPPER}} ' . $selector . '.pe--form--button--wrap';
    }
    ;

    if ($section) {
        $widget->start_controls_section(
            $prefix . 'button_styles',
            [

                'label' => esc_html__($name, ' Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => $condition,
            ]
        );


    }

    $widget->add_responsive_control(
        $prefix . 'button_width',
        [
            'label' => esc_html__('Button Width', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em', 'custom', 'vw', 'vh', 'rem'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                $widthSelector => 'width: {{SIZE}}{{UNIT}} !important;',
            ],
            'condition' => $condition,
        ]
    );

    $widget->add_responsive_control(
        $prefix . 'button_height',
        [
            'label' => esc_html__('Button Height', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em', 'custom', 'vw', 'vh', 'rem'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle' => 'height: {{SIZE}}{{UNIT}} !important;',
            ],
            'condition' => $condition,
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_text_type',
        [
            'label' => esc_html__('Button Size', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'text-p' => [
                    'title' => esc_html__('P', 'pe-core'),
                    'icon' => ' eicon-editor-paragraph',
                ],
                'text-h1' => [
                    'title' => esc_html__('H1', 'pe-core'),
                    'icon' => ' eicon-editor-h1',
                ],
                'text-h2' => [
                    'title' => esc_html__('H2', 'pe-core'),
                    'icon' => ' eicon-editor-h2',
                ],
                'text-h3' => [
                    'title' => esc_html__('H3', 'pe-core'),
                    'icon' => ' eicon-editor-h3',
                ],
                'text-h4' => [
                    'title' => esc_html__('H4', 'pe-core'),
                    'icon' => ' eicon-editor-h4',
                ],
                'text-h5' => [
                    'title' => esc_html__('H5', 'pe-core'),
                    'icon' => ' eicon-editor-h5',
                ],
                'text-h6' => [
                    'title' => esc_html__('H6', 'pe-core'),
                    'icon' => ' eicon-editor-h6',
                ]

            ],
            'condition' => $condition,
            'selectors' => [
                '{{WRAPPER}} ' . $selector . '.pe--button' => 'font: var(--{{VALUE}});letter-spacing: var(--{{VALUE}}-letter-spacing)',

            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_paragraph_size',
        [
            'label' => esc_html__('Paragraph Size', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'p-small' => [
                    'title' => esc_html__('Small', 'pe-core'),
                    'icon' => 'eicon-t-letter',
                ],
                'p-large' => [
                    'title' => esc_html__('Large', 'pe-core'),
                    'icon' => 'eicon-t-letter',
                ],
            ],
            'default' => '',
            'toggle' => true,
            'condition' => [$prefix . '_text_type' => 'text-p'],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . '.pe--button' => 'font: var(--text-{{VALUE}});letter-spacing: var(--text-{{VALUE}}-letter-spacing)',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_heading_size',
        [
            'label' => esc_html__('Heading Size', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'md-title' => [
                    'title' => esc_html__('Medium', 'pe-core'),
                    'icon' => 'eicon-t-letter',
                ],
                'big-title' => [
                    'title' => esc_html__('Large', 'pe-core'),
                    'icon' => 'eicon-t-letter',
                ],
            ],
            'default' => '',
            'toggle' => true,
            'condition' => [$prefix . '_text_type' => 'text-h1'],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . '.pe--button' => 'font: var(--text-{{VALUE}});letter-spacing: var(--text-{{VALUE}}-letter-spacing)',
            ],
        ]
    );


    $widget->add_responsive_control(
        $prefix . 'button_use_secondary_font',
        [
            'label' => esc_html__('Use Secondary Font', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} ' . $selector . '.pe--button' => '
                    font-family: var(--sec_typo-font-family);
                    font-size: var(--sec_typo-font-size);
                    line-height: var(--sec_typo-line-height);
                    letter-spacing: var(--sec_typo-letter-spacing);
                    font-weight: var(--sec_typo-font-weight);
               text-transform: var(--sec_typo-text-transform);',

            ],
        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name' => $prefix . '_button_typography',
            'selector' => '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle',
            'condition' => $condition,
        ]
    );


    $widget->add_responsive_control(
        $prefix . 'button_flex_direction',
        [
            'label' => esc_html__('Direction', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'row' => [
                    'title' => esc_html__('Row', 'pe-core'),
                    'icon' => ' eicon-h-align-right',
                ],
                'column' => [
                    'title' => esc_html__('Column', 'pe-core'),
                    'icon' => 'eicon-v-align-bottom',
                ],
                'row-reverse' => [
                    'title' => esc_html__('Row-Reverse', 'pe-core'),
                    'icon' => ' eicon-h-align-left',
                ],
                'column-reverse' => [
                    'title' => esc_html__('Column-Reverse', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
            ],
            'prefix_class' => 'bt--',
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle' => 'flex-direction: {{VALUE}};',
            ],
            'condition' => $condition,
        ]
    );

    $widget->add_responsive_control(
        $prefix . 'button_justify_content_row',
        [
            'label' => esc_html__('Justify Content', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => esc_html__('Start', 'pe-core'),
                    'icon' => 'eicon-justify-start-v'
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-justify-center-v'
                ],
                'flex-end' => [
                    'title' => esc_html__('End', 'pe-core'),
                    'icon' => 'eicon-justify-end-v'
                ],
                'space-between' => [
                    'title' => esc_html__('Space-Between', 'pe-core'),
                    'icon' => 'eicon-justify-space-between-h'
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle' => 'justify-content: {{VALUE}};',
            ],
            'condition' => [
                $prefix . 'button_flex_direction' => ['row', 'row-reverse'],
            ],
        ]
    );


    $widget->add_responsive_control(
        $prefix . 'button_align_items_row',
        [
            'label' => esc_html__('Align Items', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => esc_html__('Start', 'pe-core'),
                    'icon' => 'eicon-justify-start-h'
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-justify-center-h'
                ],
                'flex-end' => [
                    'title' => esc_html__('End', 'pe-core'),
                    'icon' => 'eicon-justify-end-h'
                ],
                'space-between' => [
                    'title' => esc_html__('Space-Between', 'pe-core'),
                    'icon' => 'eicon-justify-space-between-h'
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle' => 'align-items: {{VALUE}};',
            ],
            'condition' => [
                $prefix . 'button_flex_direction' => ['row', 'row-reverse'],
            ],
        ]
    );


    $widget->add_responsive_control(
        $prefix . 'button_gap',
        [
            'label' => esc_html__('Gap', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em', 'custom', 'vw', 'vh', 'rem'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle' => 'gap: {{SIZE}}{{UNIT}};',
            ],
            'condition' => $condition,
        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
        [
            'name' => $prefix . '_button_border',
            'selector' => '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle',
            'condition' => $condition,
        ]
    );


    $widget->add_control(
        $prefix . 'button_bg',
        [
            'label' => esc_html__('Background', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'pe-core'),
            'label_off' => __('No', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
            'render_type' => '',
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle::before' => ' content: "";display: block;position: absolute;width: 100%;height: 100%;left: 50%;transform: translate(-50%);border-radius: var(--radius);background: var(--secondaryBackground);z-index: -1; transition: all .3s cubic-bezier(0.89, 0.02, 0.12, 0.99)',
            ],
            'condition' => $condition,
        ]
    );


    $widget->add_control(
        $prefix . 'button_backdrop',
        [
            'label' => esc_html__('Backdrop Filter', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'has--backdrop',
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle::before' => 'content: "";',
            ],
            'default' => '',
            'condition' => $condition,
        ]
    );


    $widget->add_responsive_control(
        $prefix . 'button_backdrop_blur',
        [
            'label' => esc_html__('Bluriness', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 10,
            ],
            'condition' => [
                $prefix . 'button_backdrop' => 'has--backdrop',
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle' => '--backdropBlur: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle::before' => 'backdrop-filter: blur(var(--backdropBlur));',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . 'button_padding',
        [
            'label' => esc_html__('Padding', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle' => '--paddingTop: {{TOP}}{{UNIT}}; --paddingRight:{{RIGHT}}{{UNIT}}; --paddingBottom:{{BOTTOM}}{{UNIT}}; --paddingLeft:{{LEFT}}{{UNIT}};',
            ],
            'condition' => $condition,
        ]
    );

    $widget->add_responsive_control(
        $prefix . 'button_radius',
        [
            'label' => esc_html__('Border Radius', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle' => '--radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => $condition,
        ]
    );

    $widget->add_responsive_control(
        $prefix . 'icon_size',
        [
            'label' => esc_html__('Icon Size', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em', 'custom', 'vw', 'vh', 'rem'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle > span.pe--button--icon' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
            'condition' => $condition,
        ]
    );


    $widget->add_responsive_control(
        $prefix . 'icon_bg_size',
        [
            'label' => esc_html__('Icon Background Size', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'custom', 'vw', 'vh', 'rem'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle::after' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [$prefix . 'button_style' => ['stylized', 'covered']],


        ]
    );

    $cond = [];
    if ($condition) {
        $key = array_key_first($condition);
        $value = $condition[$key];
        $cond = [$key => $value];
    }

    objectStyles($widget, $prefix . '_button_icon', 'Icon', $selector . ' .pe--button--wrapper .pe--button--icon', false, [$prefix . 'show_icon' => 'true', ...$cond], false, false, false, true);

    objectStyles($widget, $prefix . '_button_text', 'Text', $selector . ' .pe--button--wrapper .pe--button--text', false, [$prefix . 'show_icon' => 'true', ...$cond], false, false, false, true);

    $widget->add_control(
        $prefix . '_custom_colors',
        [
            'label' => esc_html__('Custom Colors', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'pe-core'),
            'label_off' => __('No', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
            'render_type' => 'template',
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pe--button--text' => 'color: var(--buttonTextColor)',
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--marquee--wrap' => 'color: var(--buttonTextColor)',
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pe--button--icon' => 'color: var(--buttonIconColor)',
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle::before' => 'background-color: var(--buttonBgcolor) !important',
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle::after' => 'background-color: var(--buttonBgHovercolor)',
            ],
            'condition' => $condition,
        ]
    );

    $widget->add_control(
        $prefix . '_colors',
        [
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'label' => esc_html__($name . ' Colors', 'pe-core'),
            'label_off' => esc_html__('Default', 'pe-core'),
            'label_on' => esc_html__('Custom', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
            'render_type' => 'template',
            'condition' => [
                $prefix . '_custom_colors' => 'true'
            ],


        ]
    );

    $widget->start_popover();

    $widget->add_control(
        $prefix . '_colors_notice',
        [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => '<div class="elementor-control-field-description">If you are using layout switching features on your website; this colors does not replaced on layout switch so if you want to have dynamic colors on this button please adjust widget color options for default and switchet states.</div>',
        ]
    );

    $widget->add_control(
        $prefix . '_default_colors_heading',
        [
            'label' => esc_html__('Default Colors', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'after',
        ]
    );

    $widget->add_control(
        $prefix . '_button_default_text_color',
        [
            'label' => esc_html__('Texts Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pe--button--text' => '--buttonTextColor: {{VALUE}}',
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--marquee--wrap' => '--buttonTextColor: {{VALUE}}',
            ]
        ]
    );

    $widget->add_control(
        $prefix . '_button_default_icon_color',
        [
            'label' => esc_html__('Icon Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pe--button--icon' => '--buttonIconColor: {{VALUE}}',
            ]
        ]
    );

    $widget->add_control(
        $prefix . '_button_default_background_color',
        [
            'label' => esc_html__('Icon Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle::before' => '--buttonBgcolor: {{VALUE}}',
            ]
        ]
    );

    $widget->add_control(
        $prefix . '_hover_colors_heading',
        [
            'label' => esc_html__('Hover Colors', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'after',
        ]
    );

    $widget->add_control(
        $prefix . '_button_hover_text_color',
        [
            'label' => esc_html__('Texts Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle:hover .pe--button--text' => '--buttonTextColor: {{VALUE}}',
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle:hover .pb--marquee--wrap' => '--buttonTextColor: {{VALUE}}',
            ]
        ]
    );

    $widget->add_control(
        $prefix . '_button_hover_icon_color',
        [
            'label' => esc_html__('Icon Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle:hover .pe--button--icon' => '--buttonIconColor: {{VALUE}}',
            ]
        ]
    );

    $widget->add_control(
        $prefix . '_button_hover_background_color',
        [
            'label' => esc_html__('Background Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .pe--button--wrapper .pb--handle::after' => '--buttonBgHovercolor: {{VALUE}}',
            ]
        ]
    );


    $widget->end_popover();




    if ($section) {
        $widget->end_controls_section();
    }


}

function pe_button_render($widget, $attributes = false, $cursor = false, $prefix = '', $customText = false, $repeater = false, $selector = '', $isQuery = false)
{


    $animation = '';
    if ($repeater || isset($_POST['request'])) {
        $settings = $widget;
    } else {
        $settings = $widget->get_settings_for_display();
        if ($widget->get_name() === 'pebutton') {
            $animation = pe_general_animation($widget);
        }

    }

    $style = $settings[$prefix . 'button_style'];
    $hover = $settings[$prefix . 'hover_effect'];
    if (isset($settings[$prefix . 'interaction'])) {
        $interaction = $settings[$prefix . 'interaction'];
    } else {
        $interaction = false;
    }

    ob_start();
    \Elementor\Icons_Manager::render_icon($settings[$prefix . 'icon'], ['aria-hidden' => 'true']);
    $sub = '';

    if ($settings[$prefix . 'add_sub'] === 'true') {
        $sub = '<span class="button--sub">' . $settings[$prefix . 'button_sub'] . '</span>';
    }

    $buttonIcon = ob_get_clean();
    $buttonText = $customText ? $customText : $settings[$prefix . 'button_text'];

    $buttonTextHtml = $buttonText . $sub;
    $buttonIconHtml = $buttonIcon;


    if ($hover === 'custom') {

        $textHover = $settings[$prefix . 'text_hover'];
        $iconHover = $settings[$prefix . 'icon_hover'];

        if ($textHover !== 'none') {
            $hoverText = $settings[$prefix . 'hover_effect'] === 'none' ? '' : '<span class="button--text--hover button--chars--wrap">' . $buttonText . '</span>';
            $buttonText = '<span class="button--text--main button--chars--wrap">' . $buttonText . '</span>';
            $buttonTextHtml = '<span class="button--text--wrap">' . $buttonText . $hoverText . '</span>';
        }

        if ($style !== 'marquee' && $iconHover !== 'none' && !str_starts_with($iconHover, 'background')) {
            $buttonIconHtml = '<span class="button--icon--main">' . $buttonIcon . '</span>
            <span class="button--icon--hover">' . $buttonIcon . '</span>';
        }

        $customHover = [
            'data-text-hover' => isset($settings[$prefix . 'text_hover']) ? $settings[$prefix . 'text_hover'] : '',
            'data-background-hover' => isset($settings[$prefix . 'background_hover']) ? $settings[$prefix . 'background_hover'] : '',
            'data-icon-hover' => isset($settings[$prefix . 'icon_hover']) ? $settings[$prefix . 'icon_hover'] : '',
        ];

        $hoverAttributes = '';
        foreach ($customHover as $key => $value) {
            $hoverAttributes .= sprintf('%s="%s" ', $key, htmlspecialchars($value, ENT_QUOTES));
        }
    } else {
        $hoverAttributes = '';
    }

    $link = 'class="pb--handle"';

    if ($attributes) {

        if ($isQuery || isset($_POST['request'])) {

            $link = implode(' ', array_map(
                fn($k, $v) => esc_attr($k) . '="' . esc_attr($v) . '"',
                array_keys($attributes),
                $attributes
            ));

        } else {

            $widget->add_render_attribute(
                $prefix . 'link_attributes',
                $attributes
            );
            $link = $widget->get_render_attribute_string($prefix . 'link_attributes');
        }

    } else {
        if ($interaction === 'link' && !empty($settings[$prefix . 'link']['url'])) {

            if (!$repeater) {

                $widget->add_link_attributes('link', $settings[$prefix . 'link']);
                $widget->add_render_attribute(
                    'class',
                    ['class' => 'pb--handle']
                );
                $link = $widget->get_render_attribute_string('link') . $widget->get_render_attribute_string('class');

            } else {

                $link = build_anchor_attributes($settings[$prefix . 'link']);

            }

        } else if ($interaction === 'to--page' && isset($settings[$prefix . 'select_page'])) {
            $link = 'class="pb--handle" href="' . get_the_permalink($settings[$prefix . 'select_page']) . '"';
        } else if ($interaction === 'pe--scroll--button') {

            if ($repeater) {

                $scrollAtss = [
                    'class' => 'pe--scroll--button pb--handle',
                    'data-scroll-to' => $settings[$prefix . 'scroll_target'],
                    'data-scroll-duration' => $settings[$prefix . 'scroll_duration'],
                ];

                $link = implode(' ', array_map(
                    fn($k, $v) => $k . '="' . esc_attr($v) . '"',
                    array_keys($scrollAtss),
                    $scrollAtss
                ));
            } else {
                $widget->add_render_attribute(
                    $prefix . 'scroll_attributes',
                    [
                        'class' => 'pe--scroll--button pb--handle',
                        'data-scroll-to' => $settings[$prefix . 'scroll_target'],
                        'data-scroll-duration' => $settings[$prefix . 'scroll_duration'],
                    ]
                );
                $link = $widget->get_render_attribute_string('scroll_attributes');
            }

        }

    }

    $pop = '';
    if (!isset($_POST['request']) && !$repeater && $widget->get_name() === 'pebutton' && $settings['interaction'] === 'open-popup') {
        $pop = 'pe--pop--button';
    }

    ?>

                <div <?php echo $animation ?>
                    class="<?php echo 'pe--button ' . ' ' . $selector . ' button--' . $style . ' hover--' . $hover . ' button--' . ' ' . $pop ?>"
                    <?php echo trim($hoverAttributes); ?>>

                    <div class="pe--button--wrapper">

                        <?php
                        if (!isset($_POST['request']) && !$repeater) {
                            if ($widget->get_name() === 'peportfoliosearch' || $widget->get_name() === 'peforms') {

                                echo '<button name="submit" class="pb--handle" type="submit"' . pe_cursor($settings, $widget) . '>';

                            } else {
                                echo '<a ' . $link . pe_cursor($settings, $widget) . '>';
                            }
                        } else {
                            echo '<a ' . $link . '>';
                        } ?>

                        <span class="pe--button--text pe--styled--object">
                            <?php echo $buttonTextHtml ?>
                        </span>
                        <?php if ($settings[$prefix . 'show_icon'] === 'true') { ?>
                                        <span class="pe--button--icon pe--styled--object">
                                            <?php echo $buttonIconHtml ?>
                                        </span>
                        <?php } ?>

                        <?php if ($settings[$prefix . 'button_style'] === 'marquee') { ?>

                                        <div class="pb--marquee--wrap <?php echo $settings[$prefix . 'marquee_direction'] ?>" aria-hidden="true">

                                            <div class="pb--marquee__inner">

                                                <span><?php echo $buttonTextHtml . $buttonIconHtml ?></span>
                                                <span><?php echo $buttonTextHtml . $buttonIconHtml ?></span>
                                                <span><?php echo $buttonTextHtml . $buttonIconHtml ?></span>
                                                <span><?php echo $buttonTextHtml . $buttonIconHtml ?></span>

                                            </div>

                                        </div>

                        <?php }

                        if (!isset($_POST['request']) && !$repeater) {
                            if ($widget->get_name() === 'peportfoliosearch' || $widget->get_name() === 'peforms') {
                                echo '</button>';
                            } else {
                                echo '</a>';
                            }
                        } else {
                            echo '</a>';
                        }
                        ?>
                    </div>
                </div>


<?php }

function pe_cursor_settings($widget, $drag = false, $frontend = false)
{

    $widget->start_controls_section(
        'cursor_interactions',
        [
            'label' => __('Cursor Interactions', 'pe-core'),
        ]
    );


    if ($drag) {

        $widget->add_control(
            'cursor_drag',
            [
                'label' => esc_html__('Cursor Drag Interaction', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'cursor_drag',
                'prefix_class' => '',
                'default' => false,
            ]
        );

    }

    $widget->add_control(
        'cursor_type',
        [
            'label' => esc_html__('Interaction', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'default',
            'options' => [
                'none' => esc_html__('None', 'pe-core'),
                'default' => esc_html__('Default', 'pe-core'),
                'text' => esc_html__('Text', 'pe-core'),
                'icon' => esc_html__('Icon', 'pe-core'),
                'hidden' => esc_html__('Hidden', 'pe-core'),
            ],
            'frontend_available' => $frontend,


        ]
    );

    $widget->add_control(
        'cursor_icon',
        [
            'label' => esc_html__('Icon', 'pe-core'),
            'description' => esc_html__('Only Material Icons allowed, do not select Font Awesome icons.', 'pe-core'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-circle',
                'library' => 'fa-solid',
            ],
            'condition' => ['cursor_type' => 'icon'],
            'frontend_available' => $frontend,
        ]
    );

    $widget->add_control(
        'cursor_text',
        [
            'label' => esc_html__('Text', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'condition' => ['cursor_type' => 'text'],
            'frontend_available' => $frontend,
        ]
    );




    $widget->end_controls_section();


}

function pe_cursor($settings, $widget)
{

    ob_start();

    \Elementor\Icons_Manager::render_icon($settings['cursor_icon'], ['aria-hidden' => 'true']);

    $cursorIcon = ob_get_clean();

    $widget->add_render_attribute(
        'cursor_settings',
        [
            'data-cursor' => "true",
            'data-cursor-type' => $settings['cursor_type'],
            'data-cursor-text' => $settings['cursor_text'],
            'data-cursor-icon' => $cursorIcon,
        ]
    );

    $cursor = $settings['cursor_type'] !== 'none' ? $widget->get_render_attribute_string('cursor_settings') : '';

    return $cursor;

}

function pe_color_options($widget, $selector = '', $prefix = '', $section = true)
{

    if ($section) {

        $widget->start_controls_section(
            $prefix . '_widget_colors',
            [

                'label' => esc_html__('Colors', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
    }

    $widget->start_controls_tabs(
        $prefix . '_widget_colors_tabs'
    );

    $widget->start_controls_tab(
        $prefix . '_widget_default_colors_tab',
        [
            'label' => esc_html__('Default', 'pe-core'),
        ]
    );

    $widget->add_control(
        $prefix . '_widget_default_notice',
        [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => '<div class="elementor-control-field-description">If you apply custom colors; this widget will no longer change colors on layout switching unless you set switched color options from the "Switched" tab above.</div>',
        ]
    );


    $widget->add_control(
        $prefix . '_widget_main_texts_color',
        [
            'label' => esc_html__('Main Color', 'pe-core'),
            'description' => esc_html__('Used for text/icon color, borders, hover background color etc.', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} ' . $selector => '--mainColor: {{VALUE}}',
            ]
        ]
    );

    $widget->add_control(
        $prefix . '_widget_secondary_texts_color',
        [
            'label' => esc_html__('Secondary Color', 'pe-core'),
            'description' => esc_html__('Generally used for sub-texts but in some cases may be used as hover colors.', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} ' . $selector => '--secondaryColor: {{VALUE}}',
            ]
        ]
    );

    $widget->add_control(
        $prefix . '_widget_main_background_color',
        [
            'label' => esc_html__('Main Background Color', 'pe-core'),
            'description' => esc_html__('Used as main background color when it necessary.', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} ' . $selector => '--mainBackground: {{VALUE}}',
            ]
        ]
    );

    $widget->add_control(
        $prefix . '_widget_secondary_background_color',
        [
            'label' => esc_html__('Secondary Background Color', 'pe-core'),
            'description' => esc_html__('Most of times this color will be used inner element backgrounds. Such as; inline buttons/switchers etc.', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} ' . $selector => '--secondaryBackground: {{VALUE}}',
            ]
        ]
    );

    $widget->add_control(
        $prefix . '_widget_main_lines_color',
        [
            'label' => esc_html__('Lines Color', 'pe-core'),
            'description' => esc_html__('Used for lines, borders etc..', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} ' . $selector => '--linesColor: {{VALUE}}',
            ]
        ]
    );

    $widget->end_controls_tab();

    $widget->start_controls_tab(
        $prefix . '_widget_switched_colors_tab',
        [
            'label' => esc_html__('Switched', 'pe-core'),

        ]
    );

    $widget->add_control(
        $prefix . '_widget_switched_notice',
        [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => '<div class="elementor-control-field-description">This settings will be used when the page layout switched from default.</div>',


        ]
    );

    $widget->add_control(
        $prefix . '_widget_switched_main_texts_color',
        [
            'label' => esc_html__('Main Color', 'pe-core'),
            'description' => esc_html__('Used for text/icon color, borders, hover background color etc.', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                'body.layout--switched {{WRAPPER}} ' . $selector => '--mainColor: {{VALUE}}',
                '.header--switched {{WRAPPER}} ' . $selector => '--mainColor: {{VALUE}}',
            ]
        ]
    );

    $widget->add_control(
        $prefix . '_widget_switched_secondary_texts_color',
        [
            'label' => esc_html__('Secondary Color', 'pe-core'),
            'description' => esc_html__('Generally used for sub-texts but in some cases may be used as hover colors.', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                'body.layout--switched {{WRAPPER}} ' . $selector => '--secondaryColor: {{VALUE}}',
                '.header--switched {{WRAPPER}} ' . $selector => '--secondaryColor: {{VALUE}}',
            ]
        ]
    );

    $widget->add_control(
        $prefix . '_widget_switched_main_background_color',
        [
            'label' => esc_html__('Main Background Color', 'pe-core'),
            'description' => esc_html__('Used as main background color when it necessary.', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                'body.layout--switched {{WRAPPER}} ' . $selector => '--mainBackground: {{VALUE}}',
                '.header--switched {{WRAPPER}} ' . $selector => '--mainBackground: {{VALUE}}',
            ]
        ]
    );

    $widget->add_control(
        $prefix . '_widget_switched_secondary_background_color',
        [
            'label' => esc_html__('Secondary Background Color', 'pe-core'),
            'description' => esc_html__('Most of times this color will be used inner element backgrounds. Such as; inline buttons/switchers etc.', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                'body.layout--switched {{WRAPPER}} ' . $selector => '--secondaryBackground: {{VALUE}}',
                '.header--switched {{WRAPPER}} ' . $selector => '--secondaryBackground: {{VALUE}}',
            ]
        ]
    );

    $widget->add_control(
        $prefix . '_widget_switched_lines_color',
        [
            'label' => esc_html__('Lines Color', 'pe-core'),
            'description' => esc_html__('Used for lines, borders etc..', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                'body.layout--switched {{WRAPPER}} ' . $selector => '--linesColor: {{VALUE}}',
            ]
        ]
    );


    $widget->end_controls_tab();

    $widget->end_controls_tabs();

    if ($section) {
        $widget->end_controls_section();
    }
}

function pe_video_settings($widget, $conditionId = false, $conditionVal = false, $prefix = '', $dynamic = false)
{

    if (!$dynamic) {

        $widget->add_control(
            $prefix . 'video_provider',
            [
                'label' => esc_html__('Video Provider', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'self',
                'options' => [
                    'self' => esc_html__('Self', 'pe-core'),
                    'vimeo' => esc_html__('Vimeo', 'pe-core'),
                    'youtube' => esc_html__('Youtube', 'pe-core'),
                    'stream' => esc_html__('Stream', 'pe-core'),
                ],
                'condition' => [
                    $conditionId => $conditionVal,

                ]
            ]
        );

        $widget->add_control(
            $prefix . 'stream_url',
            [
                'label' => esc_html__('Stream URL', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,
                'condition' => [
                    $prefix . 'video_provider' => ['stream'],
                    $conditionId => $conditionVal,
                ]
            ]
        );

        $widget->add_control(
            $prefix . 'self_video',
            [
                'label' => esc_html__('Choose Video', 'pe-core'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => ['video'],
                'condition' => [
                    $prefix . 'video_provider' => 'self',
                    $conditionId => $conditionVal,

                ]
            ]
        );

        $widget->add_control(
            $prefix . 'youtube_id',
            [
                'label' => esc_html__('Video ID', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Enter video id here.', 'pe-core'),
                'ai' => false,
                'condition' => [
                    $prefix . 'video_provider' => ['youtube'],
                    $conditionId => $conditionVal,
                ]
            ]
        );

        $widget->add_control(
            $prefix . 'vimeo_id',
            [
                'label' => esc_html__('Video ID', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Enter video id here.', 'pe-core'),
                'ai' => false,
                'condition' => [
                    $prefix . 'video_provider' => ['vimeo'],
                    $conditionId => $conditionVal,
                ]
            ]
        );

        $widget->add_control(
            $prefix . 'video_poster',
            [
                'label' => esc_html__('Video Poster', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => 'false',
                'condition' => [
                    $conditionId => $conditionVal,
                ]
            ]
        );

        $widget->add_control(
            $prefix . 'poster_image',
            [
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    $prefix . 'video_poster' => ['true'],
                    $conditionId => $conditionVal,
                ],
            ]
        );

    }


    $widget->add_control(
        $prefix . 'controls',
        [
            'label' => esc_html__('Controls', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => 'true',
            'condition' => [
                $conditionId => $conditionVal,
            ]
        ]
    );


    $widget->add_control(
        $prefix . 'select_controls',
        [
            'label' => esc_html__('Select Controls', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'label_block' => true,
            'multiple' => true,
            'options' => [
                'play' => esc_html__('Play', 'pe-core'),
                'current-time' => esc_html__('Current Time', 'pe-core'),
                'duration' => esc_html__('Duration', 'pe-core'),
                'progress' => esc_html__('Progress Bar', 'pe-core'),
                'mute' => esc_html__('Mute', 'pe-core'),
                'volume' => esc_html__('Volume', 'pe-core'),
                'captions' => esc_html__('Captions', 'pe-core'),
                'settings' => esc_html__('Settings', 'pe-core'),
                'pip' => esc_html__('PIP', 'pe-core'),
                'airplay' => esc_html__('Airplay (Safari Only)', 'pe-core'),
                'fullscreen' => esc_html__('Fullscreen', 'pe-core'),
            ],
            'default' => ['play', 'current-time', 'duration', 'progress', 'mute', 'volume', 'fullscreen'],
            'condition' => [
                $prefix . 'controls' => ['true'],
                $conditionId => $conditionVal,
            ]
        ]
    );


    $widget->add_control(
        $prefix . 'autoplay',
        [
            'label' => esc_html__('Autoplay', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => 'false',
            'condition' => [
                $conditionId => $conditionVal,
            ]
        ]
    );

    $widget->add_control(
        $prefix . 'word_notice',
        [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => '<div class="elementor-panel-notice elementor-panel-alert elementor-panel-alert-info">	
           <span>When autoplay is enabled, many browsers require the video to be "muted" for it to autoplay properly.</div>',
            'condition' => [
                $prefix . 'autoplay' => 'true',
                $conditionId => $conditionVal,
            ],


        ]
    );

    $widget->add_control(
        $prefix . 'muted',
        [
            'label' => esc_html__('Muted', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => 'false',
            'condition' => [
                $conditionId => $conditionVal,
            ]
        ]
    );

    $widget->add_control(
        $prefix . 'loop',
        [
            'label' => esc_html__('Loop', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => 'false',
            'condition' => [
                $conditionId => $conditionVal,
            ]
        ]
    );

    $widget->add_control(
        $prefix . 'lightbox',
        [
            'label' => esc_html__('Play in Lightbox', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => 'false',
            'condition' => [
                'controls' => ['true'],
                $conditionId => $conditionVal,
            ]
        ]
    );

    $widget->add_control(
        $prefix . 'play_button',
        [
            'label' => esc_html__('Play Button', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'icon',
            'options' => [
                'icon' => esc_html__('Icon', 'pe-core'),
                'text' => esc_html__('Text', 'pe-core'),
            ],
            'condition' => [
                'controls' => ['true'],
                $conditionId => $conditionVal,
            ]
        ]
    );

    $widget->add_control(
        $prefix . 'play_text',
        [
            'label' => esc_html__('Play Text', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__('PLAY', 'pe-core'),
            'condition' => [
                'play_button' => ['text'],
                'controls' => ['true'],
                $conditionId => $conditionVal,
            ],

        ]
    );

    pe_text_hover_settings($widget, '', [$prefix . 'play_button' => 'text']);
    pe_icon_hover_settings($widget, '', [$prefix . 'play_button' => 'icon']);

    $widget->add_control(
        $prefix . 'player_skin',
        [
            'label' => esc_html__('Player Skin', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'global',
            'options' => [
                'global' => esc_html__('Use Global', 'pe-core'),
                'skin--simple' => esc_html__('Simple', 'pe-core'),
                'skin--rounded' => esc_html__('Rounded', 'pe-core'),
                'skin--minimal' => esc_html__('Minimal', 'pe-core'),
            ],
            'condition' => [
                $conditionId => $conditionVal,

            ]
        ]
    );

    $widget->add_control(
        $prefix . 'video_hide_elements',
        [
            'label' => esc_html__('Hide Elements', 'pe-core'),
            'description' => esc_html__('You can enter element classes here so when player is interacted (such as played/paused) these target elements will be hidden/visible on the screen.', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => esc_html__('Eg. #container1', 'pe-core'),
            'condition' => [
                $conditionId => $conditionVal,
            ]
        ]
    );

    objectStyles($widget, 'play_button', 'Play/Pause Button', '.pe--large--play.pe--styled--object', true, false, false, false, true, true);


}

function pe_video_render($widget, $repeater = false, $dynamicAttributes = false, $prefix = '')
{

    if ($repeater) {
        $settings = $widget;
    } else {
        $settings = $widget->get_settings_for_display();
    }

    $skin = $settings[$prefix . 'player_skin'];

    if ($dynamicAttributes) {
        $provider = $dynamicAttributes['provider'];
        $video_id = $dynamicAttributes['video_id'];
        $self_video = $dynamicAttributes['self_video'];
        $posterImage = $dynamicAttributes['poster_image'];
        $videoPoster = !empty($posterImage) ? 'true' : false;
    } else {
        $provider = $settings[$prefix . 'video_provider'];
        $video_id = '';
        $videoPoster = $settings[$prefix . 'video_poster'];
        if ($videoPoster === 'true') {
            $posterImage = \Elementor\Group_Control_Image_Size::get_attachment_image_html($settings, 'full', $prefix . 'poster_image');
        } else {
            $posterImage = '';
        }

        if ($provider === 'youtube') {
            $video_id = $settings[$prefix . 'youtube_id'];
        }

        if ($provider === 'vimeo') {
            $video_id = $settings[$prefix . 'vimeo_id'];
        }

        if ($provider === 'self') {
            $self_video = $settings[$prefix . 'self_video']['url'];
        }

        if ($provider === 'stream') {
            $self_video = $settings[$prefix . 'stream_url'];
        }

    }


    $controls = [];
    if ($settings[$prefix . 'select_controls']) {
        foreach ($settings[$prefix . 'select_controls'] as $control) {

            array_push($controls, $control);
        }
    }

    $hideElements = $settings[$prefix . 'video_hide_elements'];

    ?>

                <?php ob_start(); ?>
                <div data-hide-elements="<?php echo esc_attr($hideElements) ?>"
                    class="pe-video pe-<?php echo $provider . ' ' . $skin ?>" data-controls="<?php echo implode(',', $controls) ?>"
                    data-autoplay="<?php echo $settings[$prefix . 'autoplay'] ?>"
                    data-muted="<?php echo $settings[$prefix . 'muted'] ?>" data-loop="<?php echo $settings[$prefix . 'loop'] ?>"
                    data-lightbox="<?php echo $settings[$prefix . 'lightbox'] ?>">

                    <?php if ($settings[$prefix . 'lightbox'] === 'true') { ?>
                                    <div class="pe--lightbox--close x-icon">

                                        <div class="pl--close--icon">
                                            <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . '../assets/img/close.svg'); ?>">
                                        </div>

                                    </div>
                    <?php }

                    if ($settings[$prefix . 'controls'] === 'true') {

                        if ($settings[$prefix . 'play_button'] === 'icon') { ?>

                                                    <div class="pe--large--play icons pe--styled--object">

                                                        <div class="pe--play">

                                                            <svg xmlns="http://www.w3.org/2000/svg" height="100%" width="100%" viewBox="0 -960 960 960" width="24">
                                                                <path d="M320-200v-560l440 280-440 280Z" />
                                                            </svg>

                                                        </div>

                                                    </div>

                                    <?php } else { ?>

                                                    <div class="pe--large--play texts pe--styled--object">
                                                        <div class="pe--play">
                                                            <?php echo pe_text_hover($settings, $settings[$prefix . 'play_text']); ?>
                                                        </div>
                                                    </div>

                                    <?php }
                    } ?>

                    <?php if ($videoPoster === 'true') { ?>

                                    <div class="pe--video--poster">

                                        <?php
                                        echo $posterImage;
                                        ?>

                                    </div>

                    <?php } ?>


                    <?php if ($provider === 'self' || $provider === 'stream') { ?>

                                    <video class="p-video" playsinline loop autoplay>
                                        <source src="<?php echo esc_url($self_video) ?>">
                                    </video>

                    <?php } else { ?>

                                    <div class="p-video" data-plyr-provider="<?php echo $provider ?>" data-plyr-embed-id="<?php echo $video_id ?>">
                                    </div>

                    <?php } ?>


                </div>
                <?php

                $video = ob_get_clean();
                return $video;

}

function pe_product_styles($widget, $condition = false)
{


    $widget->start_controls_section(
        'single_product_styles',
        [

            'label' => esc_html__('Product Styles', 'pe-core'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => $condition,
        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Image_Size::get_type(),
        [
            'name' => 'product_images_size',
            'exclude' => [],
            'include' => [],
            'default' => 'large',
        ]
    );


    $widget->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name' => 'title_typography',
            'label' => esc_html__('Title Typohraphy', 'pe-core'),
            'selector' => '{{WRAPPER}} .product-name',
        ]
    );



    $widget->add_responsive_control(
        'metas_gap',
        [
            'label' => esc_html__('Metas Gap', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--meta' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]
    );


    $widget->add_responsive_control(
        'metas_height',
        [
            'label' => esc_html__('Metas Height', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em', 'vh', 'custom'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--meta' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]
    );


    $widget->add_responsive_control(
        'metas_padding',
        [
            'label' => esc_html__('Metas Padding', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', 'rem', '%', 'custom'],
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
            ],
        ]
    );

    $widget->add_responsive_control(
        'metas_radius',
        [
            'label' => esc_html__('Metas Radius', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', 'rem', '%', 'custom'],
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--meta' => '--radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    flexOptions($widget, false, '.zeyna--product--meta', 'metas_', 'Metas', true, '.zeyna--product--meta > div');

    $widget->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
        [
            'name' => 'single_product_border',
            'selector' => '{{WRAPPER}} .zeyna--single--product',
        ]
    );

    $widget->add_responsive_control(
        'image_wrap_width',
        [
            'label' => esc_html__('Width (Image)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw', 'custom'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--image--wrap' => 'width: {{SIZE}}{{UNIT}};margin: auto',
            ],
        ]
    );

    $widget->add_responsive_control(
        'image_wrap_height',
        [
            'label' => esc_html__('Height (Image)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vh', 'custom'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--image--wrap' => 'height: {{SIZE}}{{UNIT}};margin: auto',
            ],
        ]
    );

    $widget->add_responsive_control(
        'border_radius',
        [
            'label' => esc_html__('Border Radius (Image)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--image--wrap' => '--radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow: hidden',

            ],
        ]
    );

    $widget->add_responsive_control(
        'border_radius_producy',
        [
            'label' => esc_html__('Border Radius (Product)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors' => [
                '{{WRAPPER}} .swiper-slide:has(.zeyna--single--product)' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow: hidden',
                '{{WRAPPER}} .zeyna--single--product' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow: hidden',

            ],
        ]
    );

    $widget->add_responsive_control(
        'single_product_width',
        [
            'label' => esc_html__('Width', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .zeyna--single--product' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        'single_product_height',
        [
            'label' => esc_html__('Height', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vh', 'em', 'rem', 'custom'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .zeyna--single--product' => 'min-height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .zeyna--single--product' => 'height: {{SIZE}}{{UNIT}};',

            ],
        ]
    );


    $widget->add_control(
        'image_position',
        [
            'label' => esc_html__('Image Position', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'default' => 'center',
            'toggle' => false,
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--image img' => 'object-position: {{VALUE}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        'image_fit',
        [
            'label' => esc_html__('Image Fit', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'cover',
            'options' => [
                'cover' => esc_html__('Cover', 'pe-core'),
                'contain' => esc_html__('Contain', 'pe-core'),
            ],
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--image img' => 'object-fit: {{VALUE}};',
            ],

        ]
    );

    objectAbsolutePositioning($widget, '.sale--badge', 'sale_badge', 'Sale Badge');

    $widget->add_control(
        'totals_colors',
        [
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'label' => esc_html__('Sale Badge Styles', 'pe-core'),
            'label_off' => esc_html__('Default', 'pe-core'),
            'label_on' => esc_html__('Custom', 'pe-core'),
            'return_value' => 'sale--badge--styled',
            'prefix_class' => '',
        ]
    );

    $widget->start_popover();

    $widget->add_control(
        'sale_badge_bg_color',
        [
            'label' => esc_html__('Background Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.sale--badge--styled .sale--badge' => 'background-color: {{VALUE}}',
            ],
        ]
    );

    $widget->add_control(
        'sale_badge_text_color',
        [
            'label' => esc_html__('Text Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.sale--badge--styled .sale--badge' => 'color: {{VALUE}}',
            ],
        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
        [
            'name' => 'sale_badge_border',
            'label' => esc_html__('Borders', 'pe-core'),
            'selector' => '{{WRAPPER}}.sale--badge--styled .sale--badge',
        ]
    );

    $widget->add_responsive_control(
        'sale_badge_border_radius',
        [
            'label' => esc_html__('Border Radius', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', 'rem', '%', 'custom'],
            'selectors' => [
                '{{WRAPPER}}.sale--badge--styled .sale--badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}}  {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        'sale_badge_padding',
        [
            'label' => esc_html__('Padding', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', 'rem', '%', 'custom'],
            'selectors' => [
                '{{WRAPPER}}.sale--badge--styled .sale--badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}}  {{LEFT}}{{UNIT}};',
            ],
        ]
    );


    $widget->end_popover();

    flexOptions($widget, false, '.zeyna--single--product .zeyna--product--meta>div.zeyna--product--main', 'main_meta_', 'Title', true, false);



    $widget->end_controls_section();


    $widget->start_controls_section(
        'product_buttons_styles',
        [
            'label' => esc_html__('Product Actions Styles', 'pe-core'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,

        ]
    );

    objectStyles($widget, 'product_buttons_', 'Buttons', '.zeyna--product-quick-action .pe--styled--object:not(button)', false, false, false, false, false);
    objectAbsolutePositioning($widget, '.zeyna--product--actions', 'actions_pos_', 'Product Actions');



    $widget->add_control(
        'cart_opt',
        [
            'label' => esc_html__('Add to Cart Button', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'after',
            'condition' => [
                'behavior' => 'add-to-cart',
                'add-to-cart-style' => 'icon',
            ],
        ]
    );


    $widget->add_control(
        'cart_use_custom_icon',
        [
            'label' => esc_html__('Use Custom Icons', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'yes',
            'default' => '',
            'condition' => [
                'behavior' => 'add-to-cart',
                'add-to-cart-style' => 'icon',
            ],
        ]
    );

    $widget->add_control(
        'cart_add_icon',
        [
            'label' => esc_html__('Add Icon', 'pe-core'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'material-icons md-arrow_outward',
                'library' => 'material-design-icons',
            ],
            'condition' => [
                'behavior' => 'add-to-cart',
                'add-to-cart-style' => 'icon',
                'cart_use_custom_icon' => 'yes',

            ],
            'skin' => 'inline'
        ]
    );

    $widget->add_control(
        'cart_added_icon',
        [
            'label' => esc_html__('Added Icon', 'pe-core'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'material-icons md-arrow_outward',
                'library' => 'material-design-icons',
            ],
            'condition' => [
                'behavior' => 'add-to-cart',
                'add-to-cart-style' => 'icon',
                'cart_use_custom_icon' => 'yes',

            ],
            'skin' => 'inline'
        ]
    );

    $widget->add_control(
        'wishlist_opt',
        [
            'label' => esc_html__('Wishlist Button', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'after',
            'condition' => [
                'favorite' => 'show',
                'wishlist_type' => 'built-in',
            ],
        ]
    );

    $widget->add_control(
        'wishlist_use_custom_icon',
        [
            'label' => esc_html__('Use Custom Icons', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'yes',
            'default' => '',
            'frontend_available' => true,
            'condition' => [
                'favorite' => 'show',
                'wishlist_type' => 'built-in',
            ],
        ]
    );


    $widget->add_control(
        'wishlist_show_caption',
        [
            'label' => esc_html__('Show caption', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'yes',
            'default' => '',
            'frontend_available' => true,
            'condition' => [
                'favorite' => 'show',
                'wishlist_type' => 'built-in',
            ],
        ]
    );


    $widget->add_control(
        'wishlist_add_caption',
        [
            'label' => esc_html__('Add Caption', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__('Add to wishlist.', 'pe-core'),
            'ai' => false,
            'condition' => [
                'favorite' => 'show',
                'wishlist_type' => 'built-in',
                'wishlist_show_caption' => ['yes'],
            ],
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'wishlist_added_caption',
        [
            'label' => esc_html__('Added Caption', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__('Remove from wishlist.', 'pe-core'),
            'ai' => false,
            'condition' => [
                'favorite' => 'show',
                'wishlist_type' => 'built-in',
                'wishlist_show_caption' => ['yes'],
            ],
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'wishlist_add_icon',
        [
            'label' => esc_html__('Add Icon', 'pe-core'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'material-icons md-arrow_outward',
                'library' => 'material-design-icons',
            ],
            'condition' => [
                'favorite' => 'show',
                'wishlist_type' => 'built-in',
                'wishlist_use_custom_icon' => 'yes',
            ],
            'frontend_available' => true,
            'skin' => 'inline'
        ]
    );

    $widget->add_control(
        'wishlist_added_icon',
        [
            'label' => esc_html__('Added Icon', 'pe-core'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'material-icons md-arrow_outward',
                'library' => 'material-design-icons',
            ],
            'condition' => [
                'favorite' => 'show',
                'wishlist_type' => 'built-in',
                'wishlist_use_custom_icon' => 'yes',
            ],
            'frontend_available' => true,
            'skin' => 'inline'
        ]
    );



    $widget->add_control(
        'compare_opt',
        [
            'label' => esc_html__('Compare Button', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'after',
            'condition' => [
                'favorite' => 'show',
                'wishlist_type' => 'built-in',
            ],
        ]
    );


    $widget->add_control(
        'compare_use_custom_icon',
        [
            'label' => esc_html__('Use Custom Icons', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'yes',
            'default' => '',
            'condition' => [
                'favorite' => 'show',
                'wishlist_type' => 'built-in',
            ],
        ]
    );

    $widget->add_control(
        'compare_show_caption',
        [
            'label' => esc_html__('Show caption', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'yes',
            'default' => '',
            'condition' => [
                'favorite' => 'show',
                'wishlist_type' => 'built-in',
            ],
        ]
    );


    $widget->add_control(
        'compare_add_caption',
        [
            'label' => esc_html__('Add Caption', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__('Add to compare.', 'pe-core'),
            'ai' => false,
            'condition' => [
                'favorite' => 'show',
                'wishlist_type' => 'built-in',
                'compare_show_caption' => ['yes'],
            ],
        ]
    );

    $widget->add_control(
        'compare_added_caption',
        [
            'label' => esc_html__('Added Caption', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__('Remove from compare.', 'pe-core'),
            'ai' => false,
            'condition' => [
                'favorite' => 'show',
                'wishlist_type' => 'built-in',
                'compare_show_caption' => ['yes'],
            ],
        ]
    );


    $widget->add_control(
        'compare_add_icon',
        [
            'label' => esc_html__('Add Icon', 'pe-core'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'material-icons md-arrow_outward',
                'library' => 'material-design-icons',
            ],
            'condition' => [
                'favorite' => 'show',
                'wishlist_type' => 'built-in',
                'compare_use_custom_icon' => 'yes',

            ],
            'skin' => 'inline'
        ]
    );

    $widget->add_control(
        'compare_added_icon',
        [
            'label' => esc_html__('Added Icon', 'pe-core'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'material-icons md-arrow_outward',
                'library' => 'material-design-icons',
            ],
            'condition' => [
                'favorite' => 'show',
                'wishlist_type' => 'built-in',
                'compare_use_custom_icon' => 'yes',

            ],
            'skin' => 'inline'
        ]
    );

    pe_hover_effects($widget, 'icon', 'action_', '');

    $widget->end_controls_section();

    $widget->start_controls_section(
        'fast_add_to_cart_styles',
        [

            'label' => esc_html__('Fast Add To Cart Styles', 'pe-core'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['add-to-cart-variables' => 'fast'],
            'frontend_available' => true,

        ]
    );

    $widget->add_control(
        'fast_vars_show_titles',
        [
            'label' => esc_html__('Show Titles', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'yes',
            'default' => 'yes',
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'fast_vars_bg',
        [
            'label' => esc_html__('Background (Block)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'yes',
            'prefix_class' => 'fast--vars--has--bg--',
            'default' => 'yes',
        ]
    );

    $widget->add_control(
        'fast_vars_items_bg',
        [
            'label' => esc_html__('Background (Items)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'yes',
            'prefix_class' => 'fast--vars--items--has--bg--',
            'default' => 'yes',
        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name' => 'fast_vars_typoraphy',
            'label' => esc_html__('Variations Typography', 'pe-core'),
            'selector' => '{{WRAPPER}} .single--product--vars li',
        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
        [
            'name' => 'fast_vars_border',
            'label' => esc_html__('Borders', 'pe-core'),
            'selector' => '{{WRAPPER}} .single--product--vars li',
        ]
    );

    $widget->add_responsive_control(
        'fast_vars_border_radius',
        [
            'label' => esc_html__('Border Radius', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', 'rem', '%', 'custom'],
            'selectors' => [
                '{{WRAPPER}} .single--product--vars li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}}  {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        'fast_vars_padding',
        [
            'label' => esc_html__('Padding', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', 'rem', '%', 'custom'],
            'selectors' => [
                '{{WRAPPER}} .single--product--vars li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}}  {{LEFT}}{{UNIT}};',
            ],
        ]
    );



    $widget->end_controls_section();

    $widget->start_controls_section(
        'quick_add_popup_styles',
        [
            'label' => esc_html__('Quick Add To Cart Styles', 'pe-core'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['add-to-cart-variables' => 'popup'],
        ]
    );

    popupStyles($widget, ['add-to-cart-variables' => 'popup'], '.quick-atc-popup', 'quick_atc_pop_');

    $widget->add_responsive_control(
        'quick_atc_image_width',
        [
            'label' => esc_html__('Image Width', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw', 'em', 'rem', 'custom'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .zeyna--popup--cart-product-image' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        'quick_atc_image_height',
        [
            'label' => esc_html__('Image Height', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vh', 'em', 'rem', 'custom'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .zeyna--popup--cart-product-image' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        'quick_atc_image_radius',
        [
            'label' => esc_html__('Border Radius', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', 'rem', 'vw', 'custom'],
            'selectors' => [
                '{{WRAPPER}} .zeyna--popup--cart-product-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        'quick_atc_content_width',
        [
            'label' => esc_html__('Content Width', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw', 'em', 'rem', 'custom'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .zeyna--popup--cart-product-meta' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        'content_padding',
        [
            'label' => esc_html__('Padding', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', 'rem', 'vw', 'custom'],
            'selectors' => [
                '{{WRAPPER}} .zeyna--popup--cart-product-meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name' => 'quick_atc_title_typography',
            'label' => esc_html__('Title Typography', 'pe-core'),
            'selector' => '{{WRAPPER}} .spcp--title',
        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name' => 'quick_atc_desc_typography',
            'label' => esc_html__('Description Typography', 'pe-core'),
            'selector' => '{{WRAPPER}} .spcp--desc',
        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name' => 'quick_atc_price_typography',
            'label' => esc_html__('Price Typography', 'pe-core'),
            'selector' => '{{WRAPPER}} .spcp--price',
        ]
    );


    flexOptions($widget, false, '.zeyna--popup--cart--product', 'quick_atc_product', 'Product');

    flexOptions($widget, false, '.zeyna--popup--cart-product-meta', 'quick_atc_product_meta', 'Product Meta');

    flexOptions($widget, false, '.zeyna--popup--cart--product tbody', 'quick_atc_product_vars_table', 'Product Variations Table');

    flexOptions($widget, false, '.zeyna-variation-radio-buttons', 'quick_atc_product_vars', 'Product Variations');

    $widget->end_controls_section();

    $widget->start_controls_section(
        'extras_styles',
        [

            'label' => esc_html__('Extras Styles', 'pe-core'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => $condition,

        ]
    );

    $widget->add_control(
        'extras_orientation',
        [
            'label' => esc_html__('Extras Oritentation', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'column' => [
                    'title' => esc_html__('Column', 'pe-core'),
                    'icon' => 'eicon-v-align-bottom',
                ],
                'row' => [
                    'title' => esc_html__('Row', 'pe-core'),
                    'icon' => ' eicon-h-align-right',
                ],
            ],
            'default' => 'column',
            'toggle' => false,
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--extras' => 'flex-direction: {{VALUE}};',
                '{{WRAPPER}} .zeyna--single--product--attributes' => 'flex-direction: {{VALUE}};',
            ],
            'prefix_class' => 'extras__orientation-',
        ]
    );


    $widget->add_responsive_control(
        'prouct_extras_alignment_column',
        [
            'label' => esc_html__('Alignments', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'start' => [
                    'title' => esc_html__('Left', 'pe-core'),
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-text-align-center'
                ],
                'end' => [
                    'title' => esc_html__('Right', 'pe-core'),
                    'icon' => 'eicon-text-align-right',
                ],
            ],
            'default' => 'start',
            'toggle' => true,
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--extras' => 'align-items: {{VALUE}};',
                '{{WRAPPER}} .zeyna--single--product--attributes' => 'align-items: {{VALUE}};',
            ],
            'condition' => [
                'extras_orientation' => 'column',
            ],
        ]
    );

    $widget->add_responsive_control(
        'prouct_extras_alignment_row',
        [
            'label' => esc_html__('Alignments', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'start' => [
                    'title' => esc_html__('Left', 'pe-core'),
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-text-align-center'
                ],
                'end' => [
                    'title' => esc_html__('Right', 'pe-core'),
                    'icon' => 'eicon-text-align-right',
                ],
                'space-between' => [
                    'title' => esc_html__('Justigy', 'pe-core'),
                    'icon' => 'eicon-text-align-justify',
                ],
            ],
            'default' => 'space-between',
            'toggle' => true,
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--extras' => 'justify-content: {{VALUE}};',
            ],
            'condition' => [
                'extras_orientation' => 'row',
            ],
        ]
    );

    $widget->add_responsive_control(
        'extras_gap',
        [
            'label' => esc_html__('Extras Gap', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--extras' => 'gap: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .zeyna--single--product.detailed .zeyna--product--extras>div.zeyna--single--product--attributes' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]
    );


    $widget->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name' => 'product_cats_typoraphy',
            'label' => esc_html__('Categories Typography', 'pe-core'),
            'selector' => '{{WRAPPER}} .zeyna--product--cats',
        ]
    );

    $widget->add_control(
        'product_cats_color',
        [
            'label' => esc_html__('Categories Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--cats' => 'color: {{VALUE}}',
            ],
        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name' => 'product_tags_typoraphy',
            'label' => esc_html__('Tags Typography', 'pe-core'),
            'selector' => '{{WRAPPER}} .zeyna--product--tags',
        ]
    );

    $widget->add_control(
        'product_tags_color',
        [
            'label' => esc_html__('Tags Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--tags' => 'color: {{VALUE}}',
            ],
        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name' => 'varitations_typoraphy',
            'label' => esc_html__('Variations Typography', 'pe-core'),
            'selector' => '{{WRAPPER}} .zeyna--product--extras > div.zeyna--single--product--attributes .single--product--attributes:not(.attr--dt--variation_color_only) > span',
        ]
    );

    objectStyles($widget, 'attributes_', 'Attributes', '.zeyna--product--extras > div.zeyna--single--product--attributes .single--product--attributes:not(.attr--dt--variation_color_only) > span', true, false, false, false, false);



    $widget->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
        [
            'name' => 'single_variations_colors_border',
            'label' => esc_html__('Borders (Colors)', 'pe-core'),
            'selector' => '{{WRAPPER}} .zeyna--product--extras > div.zeyna--single--product--attributes .single--product--attributes.attr--dt--variation_color_only > span',
        ]
    );

    $widget->add_responsive_control(
        'single_variations_colors_border_radius',
        [
            'label' => esc_html__('Border Radius (Colors)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', 'rem', '%', 'custom'],
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--extras > div.zeyna--single--product--attributes .single--product--attributes.attr--dt--variation_color_only > span' => '--radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
            ],
        ]
    );

    $widget->add_responsive_control(
        'single_variations_colors_size',
        [
            'label' => esc_html__('Colors Size', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--extras > div.zeyna--single--product--attributes .single--product--attributes.attr--dt--variation_color_only > span' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
            ],
        ]
    );


    $widget->add_control(
        'cats_order',
        [
            'label' => esc_html__('Categories Order', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 10,
            'step' => 1,
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--cats' => 'order: {{VALUE}};',
            ],

        ]
    );

    $widget->add_control(
        'tags_order',
        [
            'label' => esc_html__('Tags Order', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 10,
            'step' => 1,
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--tags' => 'order: {{VALUE}};',
            ],

        ]
    );

    $widget->add_control(
        'attributes_order',
        [
            'label' => esc_html__('Attributes Order', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 10,
            'step' => 1,
            'selectors' => [
                '{{WRAPPER}} .zeyna--single--product--attributes' => 'order: {{VALUE}};',
            ],
        ]
    );

    $widget->end_controls_section();

    $widget->start_controls_section(
        'products_additional',
        [

            'label' => esc_html__('Additional Options', 'pe-core'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => $condition,

        ]
    );

    $widget->add_control(
        'title_order',
        [
            'label' => esc_html__('Title Order', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 10,
            'step' => 1,
            'selectors' => [
                '{{WRAPPER}} .product-name' => 'order: {{VALUE}};',
            ],

        ]
    );


    $widget->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name' => 'price_typography',
            'label' => esc_html__('Price Typohraphy', 'pe-core'),
            'selector' => '{{WRAPPER}} .woocommerce-Price-amount',
        ]
    );

    $widget->add_control(
        'price_order',
        [
            'label' => esc_html__('Price Order', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 10,
            'step' => 1,
            'selectors' => [
                '{{WRAPPER}} .product-price' => 'order: {{VALUE}};',
            ],

        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name' => 'short_desc_typography',
            'label' => esc_html__('Short Desc Typohraphy', 'pe-core'),
            'selector' => '{{WRAPPER}} .product-short-desc',
        ]
    );

    $widget->add_control(
        'short_desc_order',
        [
            'label' => esc_html__('Short Desc Order', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 10,
            'step' => 1,
            'selectors' => [
                '{{WRAPPER}} .product-short-desc' => 'order: {{VALUE}};',
            ],

        ]
    );


    $widget->add_responsive_control(
        'prouct_metas_alignment',
        [
            'label' => esc_html__('Metas Alignment', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => esc_html__('Left', 'pe-core'),
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-text-align-center'
                ],
                'right' => [
                    'title' => esc_html__('Right', 'pe-core'),
                    'icon' => 'eicon-text-align-right',
                ],
            ],
            'default' => is_rtl() ? 'right' : 'left',
            'toggle' => true,
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--meta' => 'text-align: {{VALUE}};',
            ],
        ]
    );


    $widget->add_control(
        'metas_order',
        [
            'label' => esc_html__('Metas Order', 'pe-core'),
            'description' => esc_html__('Title & Price', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 10,
            'step' => 1,
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--main' => 'order: {{VALUE}};',
            ],

        ]
    );


    $widget->add_control(
        'extras_order',
        [
            'label' => esc_html__('Extras Order', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 10,
            'step' => 1,
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--extras' => 'order: {{VALUE}};',
            ],

        ]
    );

    objectStyles($widget, 'price_', 'Price', '.zeyna--product--meta .product-price.pe--styled--object', true, false, false, false, true);

    $widget->end_controls_section();

}

function pe_product_controls($widget, $condition = false)
{

    if (!class_exists('WooCommerce')) {
        return false;
    }

    $widget->add_control(
        'product_style',
        [
            'label' => esc_html__('Style', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'classic',
            'options' => [
                'classic' => esc_html__('Classic', 'pe-core'),
                'metro' => esc_html__('Metro', 'pe-core'),
                'card' => esc_html__('Card', 'pe-core'),
                'sharp' => esc_html__('Sharp', 'pe-core'),
                'detailed' => esc_html__('Detailed', 'pe-core'),
            ],
            'condition' => $condition,
            'frontend_available' => true,
        ]
    );

    $widget->add_responsive_control(
        'box_direction',
        [
            'label' => esc_html__('Box Direction', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'row' => [
                    'title' => esc_html__('Row', 'pe-core'),
                    'icon' => ' eicon-h-align-right',
                ],
                'column' => [
                    'title' => esc_html__('Column', 'pe-core'),
                    'icon' => 'eicon-v-align-bottom',
                ],
                'row-reverse' => [
                    'title' => esc_html__('Row-Reverse', 'pe-core'),
                    'icon' => ' eicon-h-align-left',
                ],
                'column-reverse' => [
                    'title' => esc_html__('Column-Reverse', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
            ],
            'default' => 'row',
            'toggle' => false,
            'selectors' => [
                '{{WRAPPER}} .zeyna--product--wrap.product--box--wrap' => 'flex-direction: {{VALUE}};',
            ],
            'condition' => [
                'product_style' => 'detailed',
            ],
            'frontend_available' => true,
        ]
    );



    $widget->add_control(
        'short__desc',
        [
            'label' => esc_html__('Short Description', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Show', 'pe-core'),
            'label_off' => esc_html__('Hide', 'pe-core'),
            'return_value' => 'show',
            'default' => 'hide',
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'price',
        [
            'label' => esc_html__('Price', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Show', 'pe-core'),
            'label_off' => esc_html__('Hide', 'pe-core'),
            'return_value' => 'show',
            'default' => 'show',
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'favorite',
        [
            'label' => esc_html__('Wishlist', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Show', 'pe-core'),
            'label_off' => esc_html__('Hide', 'pe-core'),
            'return_value' => 'show',
            'default' => 'hide',
            'frontend_available' => true,
        ]
    );


    $widget->add_control(
        'wishlist_type',
        [
            'label' => esc_html__('Wishlist Type', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'yith',
            'options' => [
                'yith' => esc_html__('YITH', 'pe-core'),
                'built-in' => esc_html__('Built-in (Zeyna)', 'pe-core'),
            ],
            'condition' => [
                'favorite' => 'show',
            ],
            'frontend_available' => true,
        ]
    );


    $widget->add_control(
        'compare',
        [
            'label' => esc_html__('Compare', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Show', 'pe-core'),
            'label_off' => esc_html__('Hide', 'pe-core'),
            'return_value' => 'show',
            'default' => 'hide',
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'compare_type',
        [
            'label' => esc_html__('Compare Type', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'yith',
            'options' => [
                'yith' => esc_html__('YITH', 'pe-core'),
                'built-in' => esc_html__('Built-in (Zeyna)', 'pe-core'),
            ],
            'condition' => [
                'compare' => 'show',
            ],
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'behavior',
        [
            'label' => esc_html__('Add to Cart', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'render_type' => 'template',
            'prefix_class' => 'product--behavior--',
            'options' => [
                'none' => esc_html__('Hide', 'pe-core'),
                'add-to-cart' => esc_html__('Show', 'pe-core'),
            ],
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'add-to-cart-style',
        [
            'label' => esc_html__('Add To Cart Style', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'wide',
            'prefix_class' => 'add--to--cart--style--',
            'options' => [
                'wide' => esc_html__('Wide', 'pe-core'),
                'icon' => esc_html__('Icon', 'pe-core'),
            ],
            'condition' => [
                'behavior' => 'add-to-cart',
            ],
            'frontend_available' => true,
        ]
    );

    $productAttributes = array();

    $attributes1 = wc_get_attribute_taxonomies();

    foreach ($attributes1 as $key => $attribute) {
        $productAttributes[$attribute->attribute_id] = $attribute->attribute_label;
    }

    $widget->add_control(
        'add-to-cart-variables',
        [
            'label' => esc_html__('Add To Cart Behavior (Variables)', 'pe-core'),
            'description' => esc_html__('Varible products add to cart behavior.', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'popup',
            'render_type' => 'template',
            'label_block' => true,
            'prefix_class' => 'add--to--cart--variables--',
            'options' => [
                'popup' => esc_html__('Popup', 'pe-core'),
                'fast' => esc_html__('Fast', 'pe-core'),
            ],
            'condition' => [
                'behavior' => 'add-to-cart',
            ],
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'add-to-cart-vars',
        [
            'label' => __('Add to cart variable.', 'pe-core'),
            'description' => __("Don't forget to set default selections for the variations that won't be displayed here; otherwise, the fast add to cart feature won't work.", 'pe-core'),
            'label_block' => false,
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => false,
            'options' => $productAttributes,
            'condition' => ['add-to-cart-variables' => 'fast'],
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'view_button',
        [
            'label' => esc_html__('View Button', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'show',
            'default' => '',
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'actions_visibility',
        [
            'label' => esc_html__('Actions Visibility', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'hover',
            'render_type' => 'template',
            'label_block' => false,
            'prefix_class' => 'actions--visiblity--',
            'options' => [
                'hover' => esc_html__('Show On Hover', 'pe-core'),
                'visible' => esc_html__('Always Show', 'pe-core'),
                'show-on-image' => esc_html__('Show on Image', 'pe-core'),
            ],
            'condition' => $condition,
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'product_image_hover',
        [
            'label' => esc_html__('Hover', 'pe-core'),
            'description' => esc_html__('If product type is variable quick add to cart popup will be used for add to cart event.', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'image',
            'render_type' => 'template',
            'prefix_class' => 'image-hover-',
            'options' => [
                'none' => esc_html__('None', 'pe-core'),
                'image' => esc_html__('Image', 'pe-core'),
                'zoom-in' => esc_html__('Zoom In', 'pe-core'),
                'zoom-out' => esc_html__('Zoom Out', 'pe-core'),
            ],
            'condition' => [
                'product_gallery!' => 'yes',
            ],
            'frontend_available' => true,
        ]
    );


    $widget->add_control(
        'product_gallery',
        [
            'label' => esc_html__('Product Gallery', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'yes',
            'default' => '',
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'show_categories',
        [
            'label' => esc_html__('Categories', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Show', 'pe-core'),
            'label_off' => esc_html__('Hide', 'pe-core'),
            'return_value' => 'yes',
            'render_type' => 'template',
            'default' => 'no',
            'prefix_class' => 'product--cats--',
            'frontend_available' => true,
        ]
    );

    $productCats = array();

    $args = array(
        'hide_empty' => true,
        'taxonomy' => 'product_cat'
    );

    $categories = get_categories($args);

    foreach ($categories as $key => $category) {
        $productCats[$category->term_id] = $category->name;
    }

    $widget->add_control(
        'single_categories_to_show',
        [
            'label' => __('Select Categories', 'pe-core'),
            'description' => __('Leave it empty if you want to display all.', 'pe-core'),
            'label_block' => false,
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $productCats,
            'condition' => [
                'show_categories' => 'yes',
            ],
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'show_tags',
        [
            'label' => esc_html__('Tags', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Show', 'pe-core'),
            'label_off' => esc_html__('Hide', 'pe-core'),
            'return_value' => 'yes',
            'default' => 'no',
            'render_type' => 'template',
            'prefix_class' => 'product--tags--',
            'frontend_available' => true,
        ]
    );

    $productTags = array();

    $args = array(
        'hide_empty' => true,
        'taxonomy' => 'product_tag'
    );

    $tags = get_categories($args);

    foreach ($tags as $key => $tag) {
        $productTags[$tag->term_id] = $tag->name;
    }

    $widget->add_control(
        'single_tags_to_show',
        [
            'label' => __('Select Tags', 'pe-core'),
            'description' => __('Leave it empty if you want to display all.', 'pe-core'),
            'label_block' => false,
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $productTags,
            'condition' => [
                'show_tags' => 'yes',
            ],
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'show_variations',
        [
            'label' => esc_html__('Variations', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Show', 'pe-core'),
            'label_off' => esc_html__('Hide', 'pe-core'),
            'return_value' => 'yes',
            'default' => 'no',
            'render_type' => 'template',
            'prefix_class' => 'product--vars--',
            'frontend_available' => true,
        ]
    );


    $widget->add_control(
        'single_attributes_to_show',
        [
            'label' => __('Attributes to display.', 'pe-core'),
            'label_block' => true,
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $productAttributes,
            'frontend_available' => true,
            'condition' => ['show_variations' => 'yes']
        ]
    );

    $widget->add_control(
        'show_variations_style',
        [
            'label' => esc_html__('Variations Style', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'default',
            'options' => [
                'default' => esc_html__('Default', 'pe-core'),
                'hover' => esc_html__('Hover', 'pe-core'),
            ],
            'render_type' => 'template',
            'prefix_class' => 'product--vars--',
            'frontend_available' => true,
            'condition' => ['show_variations' => 'yes']

        ]
    );


    $widget->add_control(
        'variations_swatches',
        [
            'label' => esc_html__('Variations Swatches', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'yes',
            'default' => 'no',
            'render_type' => 'template',
            'frontend_available' => true,
            'condition' => [
                'show_variations' => 'yes',
            ],
        ]
    );

    $widget->add_control(
        'sale_badge',
        [
            'label' => esc_html__('Sale Badge', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Show', 'pe-core'),
            'label_off' => esc_html__('Hide', 'pe-core'),
            'return_value' => 'yes',
            'default' => 'yes',
            'render_type' => 'template',
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'sale_badge_text',
        [
            'label' => esc_html__('Sale Badge Text', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__('SALE', 'pe-core'),
            'ai' => false,
            'condition' => [
                'sale_badge' => ['yes'],
            ],
            'frontend_available' => true,
        ]
    );

}

function zeynaFastAddToCart($product, $settings)
{

    if ($settings['add-to-cart-variables'] === 'fast' && $product->is_type('variable')) { ?>
                                <div class="zeyna--fast--add">

                                    <?php $attribute = $settings['add-to-cart-vars'];
                                    if (!empty($attribute)) {

                                        $default_attributes = $product->get_default_attributes();


                                        ?>
                                                    <div class="zeyna--fast--add--vars" data-product-id="<?php echo esc_attr($product->get_id()); ?>">

                                                        <?php

                                                        $vars = wc_get_attribute($attribute);
                                                        $variations = $product->get_available_variations();

                                                        if ($vars) {
                                                            $taxonomy = esc_attr($vars->slug);
                                                            $id = $vars->id;
                                                            $display_type = get_option("wc_attribute_display_type-$id", 'default');
                                                            $terms = wc_get_product_terms($product->get_id(), $taxonomy, array('fields' => 'all'));
                                                            $matched_variations = [];

                                                            if (!empty($default_attributes)) {
                                                                foreach ($default_attributes as $key => $attr) {
                                                                    if ($key !== $taxonomy) {
                                                                        $matched_variations = array_filter($variations, function ($variation) use ($key, $attr) {
                                                                            return isset($variation['attributes']['attribute_' . $key]) && $variation['attributes']['attribute_' . $key] === $attr;
                                                                        });
                                                                    }
                                                                }
                                                            }

                                                            if (!empty($terms)) {

                                                                if ($settings['fast_vars_show_titles'] == 'yes') {
                                                                    echo '<span class="fast--var--name">' . esc_html($vars->name) . '</span>';
                                                                }

                                                                ?>
                                                                                        <ul class="single--product--vars attr--dt--<?php echo $display_type ?>">
                                                                                            <?php
                                                                                            foreach ($terms as $term) {

                                                                                                $variation_id = null;
                                                                                                $in_stock = true;

                                                                                                foreach ($variations as $variation) {
                                                                                                    if (isset($variation['attributes']["attribute_$taxonomy"]) & $variation['attributes']["attribute_$taxonomy"] == $term->slug) {
                                                                                                        $variation_id = $variation['variation_id'];
                                                                                                        $in_stock = $variation['is_in_stock'];
                                                                                                        break;
                                                                                                    }
                                                                                                }

                                                                                                if (!empty($default_attributes) && $in_stock) {
                                                                                                    $slug = $term->slug;

                                                                                                    $match = array_filter($matched_variations, function ($matcho) use ($taxonomy, $slug) {
                                                                                                        return isset($matcho['attributes']['attribute_' . $taxonomy]) && $matcho['attributes']['attribute_' . $taxonomy] === $slug;
                                                                                                    });

                                                                                                    $variation_ids = array_column($match, 'variation_id');
                                                                                                    $variation_id = $variation_ids[0];

                                                                                                }
                                                                                                ;

                                                                                                if (get_field('term_color', $term)) {
                                                                                                    echo '<li data-stock="' . $in_stock . '" style="--bg: ' . get_field('term_color', $term) . '" data-variation-id="' . esc_attr($variation_id) . '">' . esc_html($term->name) . '</li>';
                                                                                                } else {
                                                                                                    echo '<li data-stock="' . $in_stock . '" data-variation-id="' . esc_attr($variation_id) . '">' . esc_html($term->name) . '
                                                            <svg class="cart-loading" width="1em" height="1em">
                                                            <use xlink:href="#cart-loading"></use>
                                                          </svg>
                                                          <svg class="cart-done" width="1em" height="1em">
                                                          <use xlink:href="#cart-done"></use>
                                                        </svg>
                                                         </li>';
                                                                                                }
                                                                                            } ?>
                                                                                        </ul>
                                                                        <?php }
                                                        }
                                                        ?>
                                                    </div>
                                                    <?php
                                    } ?>

                                </div>
                <?php }

}

function zeynaProductActions($product, $settings)
{ ?>
                <div class="zeyna--product--actions">

                    <?php if ($settings['favorite'] === 'show') { ?>
                                    <div class="zeyna--product-quick-action" data-barba-prevent="all"
                                        data-add-caption="<?php echo $settings['wishlist_add_caption'] ?>"
                                        data-added-caption="<?php echo $settings['wishlist_added_caption'] ?>">
                                        <?php
                                        if ($settings['wishlist_type'] === 'yith') {
                                            if (class_exists('YITH_WCWL') && $settings['favorite'] === 'show') {
                                                echo do_shortcode('[yith_wcwl_add_to_wishlist]');
                                            }
                                        } else {
                                            peWishlistButton($product->get_id(), $settings);
                                        }
                                        ?>
                                    </div>

                    <?php } ?>

                    <?php if ($settings['compare'] === 'show') { ?>

                                    <div class="zeyna--product-quick-action" data-add-caption="<?php echo $settings['compare_add_caption'] ?>"
                                        data-added-caption="<?php echo $settings['compare_added_caption'] ?>">
                                        <?php
                                        if ($settings['wishlist_type'] === 'yith') {
                                            if (class_exists('YITH_WCWL') && $settings['favorite'] === 'show') {
                                                $svgPath = get_template_directory() . '/assets/img/compare.svg';
                                                $icon = file_get_contents($svgPath);

                                                echo '<span class="pe--compare--wrap" data-barba-prevent="all">
                                  <span class="compare--svg">' . $icon . '</span>
                                  '
                                                    . do_shortcode('[yith_compare_button]') . '
                                  </span>';
                                            }
                                        } else {
                                            peCompareButton($product->get_id(), $settings);
                                        }

                                        ?>
                                    </div>

                    <?php } ?>

                    <?php
                    if ($settings['behavior'] !== 'none' && $settings['add-to-cart-variables'] !== 'fast') { ?>
                                    <?php if ($product->is_type('variable') || $product->is_type('grouped')) { ?>
                                                    <div class="zeyna--product-quick-action" data-barba-prevent="all">
                                                        <button class="quick-add-to-cart-btn" data-product-id="<?php echo esc_attr($product->get_id()); ?>">

                                                            <?php
                                                            if ($settings['add-to-cart-style'] === 'wide') {

                                                                echo '<span class="quick--text">' . esc_html('Quick Shop', 'pe-core') . '</span>';

                                                            } ?>
                                                            <span class="card-add-icon">
                                                                <?php
                                                                $svgPath = get_template_directory() . '/assets/img/cart-add.svg';
                                                                $icon = file_get_contents($svgPath);
                                                                echo $icon; ?>
                                                            </span>

                                                            <svg class="cart-loading" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 -960 960 960"
                                                                width="1em">
                                                                <path
                                                                    d="M480-80q-82 0-155-31.5t-127.5-86Q143-252 111.5-325T80-480q0-83 31.5-155.5t86-127Q252-817 325-848.5T480-880q17 0 28.5 11.5T520-840q0 17-11.5 28.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160q133 0 226.5-93.5T800-480q0-17 11.5-28.5T840-520q17 0 28.5 11.5T880-480q0 82-31.5 155t-86 127.5q-54.5 54.5-127 86T480-80Z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                    <?php } else { ?>
                                                    <div class="zeyna--single--atc zeyna--product-quick-action">
                                                        <?php if ($settings['behavior'] === 'add-to-cart') {
                                                            if ($product->is_type('simple')) {
                                                                // woocommerce_simple_add_to_cart();
                                                            } elseif ($product->is_type('grouped')) {
                                                                // woocommerce_grouped_add_to_cart();
                                                            } elseif ($product->is_type('external')) {
                                                                // woocommerce_external_add_to_cart();
                                                            }

                                                            zeyna_widget_add_to_cart_icon($product, $settings);

                                                        } ?>

                                                    </div>
                                    <?php }
                    } ?>

                    <?php if ($settings['view_button'] === 'show') { ?>

                                    <div class="zeyna--product-quick-action">
                                        <?php
                                        $svgPath = get_template_directory() . '/assets/img/arrow_forward.svg';
                                        $icon = file_get_contents($svgPath);
                                        echo '<a data-cursor="true" data-cursor-type="hidden"href="' . apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product) . '" class="pe--view--button pe--styled--object product--barba--trigger" data-id="' . get_the_id() . '">
        <span>' . $icon . '</span>
        </a>';
                                        ?>
                                    </div>

                    <?php } ?>
                </div>
<?php }


function zeynaProductImage($product, $cursor, $settings, $actions = true, $customMedia = false)
{
    ?>

                <div class="zeyna--product--image--wrap">

                    <?php if ((get_field('product_video') === 'vimeo' || get_field('product_video') === 'youtube' || get_field('product_video') === 'self') && get_field('use_as_featured_media') == true || (isset($settings['media_type']) && $settings['media_type'] === 'video')) {

                        if (isset($settings['media_type']) && $settings['media_type'] === 'video') {
                            $provider = $settings['video_provider'];

                            $video_id = '';

                            if ($provider === 'youtube') {

                                $video_id = $settings['youtube_id'];
                            }

                            if ($provider === 'vimeo') {

                                $video_id = $settings['vimeo_id'];
                            }
                            $self_video = $settings['self_video'];

                        } else {
                            $provider = get_field('product_video');
                            $video_id = get_field('video_id');
                            $self_video = get_field('self_hosted_video');
                        }

                        ?>

                                    <div class="zeyna--product--video">
                                        <a <?php echo $cursor ?>
                                            href="<?php echo apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product); ?>"
                                            data-id="<?php echo get_the_id() ?>">

                                            <div class="pe-video pe-<?php echo esc_attr($provider) ?>" data-controls=false data-autoplay=true
                                                data-muted=true data-loop=true>

                                                <?php if ($provider === 'self') { ?>
                                                                <video class="p-video" autoplay muted loop playsinline>
                                                                    <source src="<?php echo esc_url($self_video); ?>">
                                                                </video>
                                                <?php } else { ?>
                                                                <div class="p-video" data-plyr-provider="<?php echo esc_attr($provider) ?>"
                                                                    data-plyr-embed-id="<?php echo esc_attr($video_id) ?>"></div>
                                                <?php } ?>
                                            </div>
                                        </a>
                                    </div>

                                    <?php if ($settings['image_hover'] === 'image' && !$customMedia) {

                                        echo '<div class="product--image--hover">' .
                                            wp_get_attachment_image(get_post_thumbnail_id(), 'medium_large', false, array(
                                                'loading' => 'eager',
                                                'fetchpriority' => 'high',
                                            ))
                                            . '</div>';
                                    } ?>

                    <?php } else { ?>

                                    <div class="zeyna--product--image product__image__<?php echo get_the_ID() ?>">

                                        <a <?php echo $cursor ?> class="product--barba--trigger" data-id="<?php echo get_the_id() ?>"
                                            href="<?php echo apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product); ?>">

                                            <?php $attachment_ids = $product->get_gallery_image_ids();

                                            if ($attachment_ids) { ?>

                                                            <?php

                                                            if ($customMedia && $settings['media_type'] === 'image') {
                                                                $settings['product_images_size'] = [
                                                                    'id' => $settings['product_custom_image']['id'],
                                                                ];
                                                            } else {
                                                                $settings['product_images_size'] = [
                                                                    'id' => get_post_thumbnail_id(),
                                                                ];
                                                            }

                                                            $image_html = \Elementor\Group_Control_Image_Size::get_attachment_image_html($settings, 'product_images_size');
                                                            $image_html = str_replace('<img ', '<img class="product-image-front" ', $image_html);

                                                            echo $image_html;
                                                            ?>

                                            <?php } else { ?>
                                                            <?php
                                                            if ($customMedia && $settings['media_type'] === 'image') {
                                                                $settings['product_images_size'] = [
                                                                    'id' => $settings['product_custom_image']['id'],
                                                                ];
                                                            } else {
                                                                $settings['product_images_size'] = [
                                                                    'id' => get_post_thumbnail_id(),
                                                                ];
                                                            }
                                                            \Elementor\Group_Control_Image_Size::print_attachment_image_html($settings, 'product_images_size');
                                                            ?>
                                            <?php } ?>
                                        </a>

                                    </div>

                                    <?php if ($settings['product_gallery'] === 'yes') {
                                        $attachment_ids = $product->get_gallery_image_ids();

                                        if ($attachment_ids) {

                                            echo '<div class="product--archive--gallery swiper-container">'; ?>

                                                                    <div class="product--archive--gallery--nav">

                                                                        <div class="pag--prev">
                                                                            <?php $svgPath = plugin_dir_path(__FILE__) . '../assets/img/chevron_down.svg';
                                                                            $icon = file_get_contents($svgPath);
                                                                            echo $icon;
                                                                            ?>

                                                                        </div>
                                                                        <div class="pag--next">
                                                                            <?php
                                                                            $icon = file_get_contents($svgPath);
                                                                            echo $icon;
                                                                            ?>

                                                                        </div>

                                                                    </div>

                                                                    <?php echo '<div class="swiper-wrapper">';

                                                                    $settings['product_images_size'] = [
                                                                        'id' => get_post_thumbnail_id(),
                                                                    ];

                                                                    ?>
                                                                    <?php echo '<div class="product--archvive--gallery--image swiper-slide">
                    <a href="' . apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product) . '"' . $cursor . ' class="product--barba--trigger" data-id="' . get_the_id() . '">';
                                                                    \Elementor\Group_Control_Image_Size::print_attachment_image_html($settings, 'product_images_size');
                                                                    echo '</a></div>';

                                                                    foreach ($attachment_ids as $key => $attachment_id) {

                                                                        $settings['product_images_size'] = [
                                                                            'id' => $attachment_id,
                                                                        ];

                                                                        echo '<div class="product--archvive--gallery--image swiper-slide">
                        <a href="' . apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product) . '"' . $cursor . ' class="product--barba--trigger" data-id="' . get_the_id() . '">';

                                                                        \Elementor\Group_Control_Image_Size::print_attachment_image_html($settings, 'product_images_size');

                                                                        echo '</a></div>';

                                                                    }

                                                                    echo '</div>';
                                                                    echo '</div>';

                                        }

                                    } ?>

                                    <?php if (isset($settings['image_hover']) && $settings['image_hover'] === 'image' && !$customMedia) {
                                        $attachment_ids = $product->get_gallery_image_ids();

                                        if ($attachment_ids) {

                                            foreach ($attachment_ids as $key => $attachment_id) {
                                                if ($key == 0) {
                                                    echo '<div class="product--image--hover">' .
                                                        wp_get_attachment_image($attachment_id, 'medium_large', false, array(
                                                            'loading' => 'eager',
                                                            'fetchpriority' => 'high',
                                                        ))
                                                        . '</div>';
                                                }
                                            }

                                        }
                                    } ?>

                    <?php }

                    echo zeynaFastAddToCart($product, $settings);

                    if ($actions && ($settings['actions_visibility'] === 'hover' || $settings['actions_visibility'] === 'show-on-image')) {
                        echo zeynaProductActions($product, $settings);
                    } ?>

                </div>

<?php }

function zeynaProductRender($settings, $product, $classes, $cursor = '', $image = true)
{
    $style = $settings['product_style'];
    $list = isset($settings['products_archive_style']) && $settings['products_archive_style'] === 'list';
    $rotate = isset($settings['rotate_navigation_types']) ? true : false;

    ?>

                <div <?php wc_product_class($classes, $product); ?> data-product-id="<?php echo get_the_ID(); ?>">
                    <?php if ($settings['behavior'] === 'add-to-cart' && $product->is_type('variable')) { ?>
                                    <div class="pop--behavior--center quick-add-to-cart-popup quick_pop_id-<?php echo get_the_ID(); ?>"
                                        data-product-id="<?php echo get_the_ID(); ?>" style="display: none">
                                        <span class="pop--overlay"></span>

                                        <div class="pe--styled--popup quick-atc-popup">

                                            <span class="pop--close">

                                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                                                    <path
                                                        d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z" />
                                                </svg>

                                            </span>
                                            <div class="zeyna--popup--cart--product">

                                                <div class="zeyna--popup--cart-product-image">
                                                    <img class="spcp--img" src="">
                                                </div>

                                                <div class="zeyna--popup--cart-product-meta">

                                                    <div class="zeyna--popup--cart-product-cont">
                                                        <h6 class="spcp--price"></h6>
                                                        <h4 class="spcp--title"></h4>
                                                        <p class="spcp--desc no-margin"></p>

                                                    </div>
                                                    <div class="zeyna--popup--cart-product-form"></div>
                                                </div>


                                            </div>

                                        </div>
                                    </div>
                    <?php } ?>

                    <div class="zeyna--product--wrap">
                        <?php
                        if ($product->is_on_sale() && $settings['sale_badge'] === 'yes') {
                            $regular_price = (float) $product->get_regular_price();
                            $sale_price = (float) $product->get_price();
                            $discount_percentage = calculate_discount_percentage($regular_price, $sale_price);

                            echo '<span class="sale--badge">' . $settings['sale_badge_text'];
                            if ($discount_percentage > 0) {
                                echo '<p class="discount-badge">-' . $discount_percentage . '%</p>';
                            }
                            echo '</span>';


                        }

                        if ($image) {
                            zeynaProductImage($product, $cursor, $settings);
                        }

                        ?>

                        <!-- Product Meta -->
                        <div class="zeyna--product--meta">
                            <div class="zeyna--product--main">

                                <?php echo '<div class="product-name ' . esc_attr(apply_filters('woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title')) . '">' . get_the_title() . '</div>'; // Product title 
                                
                                    if ($settings['price'] === 'show') {

                                        if ($price_html = $product->get_price_html()) { ?>
                                                                <div class="product-price pe--styled--object"><?php echo do_shortcode($price_html); ?></div>
                                                                <!-- Product Price -->
                                                <?php }
                                    }

                                    if ($settings['short__desc'] === 'show') {
                                        echo '<div class="product-short-desc">' . $product->get_short_description() . '</div>';
                                    }
                                    ?>


                            </div>

                            <?php

                            if ($settings['actions_visibility'] === 'visible') {
                                echo zeynaProductActions($product, $settings);
                            }

                            ?>


                            <div class="zeyna--product--extras">

                                <?php if ($settings['show_categories'] === 'yes') { ?>

                                                <div class="zeyna--product--cats">

                                                    <?php

                                                    $selectedCats = $settings['single_categories_to_show'];
                                                    $categories = wp_get_post_terms($product->get_id(), 'product_cat');

                                                    foreach ($categories as $category) {
                                                        if (!empty($selectedCats)) {
                                                            if (in_array($category->term_id, $selectedCats)) {
                                                                echo '<span>' . $category->name . '</span>';
                                                            }
                                                        } else {
                                                            echo '<span>' . $category->name . '</span>';
                                                        }

                                                    } ?>

                                                </div>

                                <?php } ?>

                                <?php if ($settings['show_tags'] === 'yes') { ?>

                                                <div class="zeyna--product--tags">

                                                    <?php

                                                    $selectedTags = $settings['single_tags_to_show'];
                                                    $tags = wp_get_post_terms($product->get_id(), 'product_tag');

                                                    foreach ($tags as $tag) {
                                                        if (!empty($selectedTags)) {
                                                            if (in_array($tag->term_id, $selectedTags)) {
                                                                echo '<span>' . $tag->name . '</span>';
                                                            }
                                                        } else {
                                                            echo '<span>' . $tag->name . '</span>';
                                                        }
                                                    } ?>

                                                </div>

                                <?php } ?>

                                <?php if ($settings['show_variations'] === 'yes') {
                                    $attributes = $settings['single_attributes_to_show'];
                                    $swatches = '';

                                    if (!empty($attributes) && $product->is_type('variable')) {
                                        if ($settings['variations_swatches'] === 'yes') {
                                            $variations = $product->get_available_variations();
                                            $swatches = 'has--swatches';
                                        }
                                        ?>
                                                                <div class="zeyna--single--product--attributes <?php echo esc_attr($swatches) ?>">
                                                                    <?php
                                                                    foreach ($attributes as $attribute) {
                                                                        $vars = wc_get_attribute($attribute);



                                                                        if ($vars) {
                                                                            $taxonomy = esc_attr($vars->slug);
                                                                            $id = $vars->id;
                                                                            $display_type = get_option("wc_attribute_display_type-$id", 'default');
                                                                            $terms = wc_get_product_terms($product->get_id(), $taxonomy, array('fields' => 'all'));

                                                                            if (!empty($terms)) {

                                                                                if ($settings['show_variations_style'] === 'hover') {
                                                                                    echo '<span class="single--product--attribute--label">+' . count($terms) . ' ' . $vars->name . '</span>';
                                                                                }


                                                                                ?>
                                                                                                                    <div class="single--product--attributes attr--dt--<?php echo $display_type ?>">
                                                                                                                        <?php
                                                                                                                        foreach ($terms as $term) {

                                                                                                                            $variation_id = null;

                                                                                                                            if ($settings['variations_swatches'] === 'yes') {

                                                                                                                                foreach ($variations as $variation) {
                                                                                                                                    if (
                                                                                                                                        isset($variation['attributes']["attribute_$taxonomy"]) &&
                                                                                                                                        $variation['attributes']["attribute_$taxonomy"] == $term->slug
                                                                                                                                    ) {
                                                                                                                                        $variation_id = $variation['variation_id'];
                                                                                                                                        break;
                                                                                                                                    }
                                                                                                                                }
                                                                                                                            }

                                                                                                                            $linked = '';
                                                                                                                            if (get_post_meta($variation_id, '_linked_variation_checkbox', true)) {
                                                                                                                                $linked_product_id = get_post_meta($variation_id, '_linked_variation_product', true);
                                                                                                                                $linked = 'data-linked-id="' . $linked_product_id . '"';
                                                                                                                            }

                                                                                                                            if (get_field('term_color', $term)) {
                                                                                                                                echo '<span class="term--has--color" ' . $linked . ' style="--bg: ' . get_field('term_color', $term) . '" data-variation-id="' . esc_attr($variation_id) . '">' . esc_html($term->name) . '</span>';
                                                                                                                            } else {
                                                                                                                                echo '<span class="pe--styled--object" ' . $linked . ' data-variation-id="' . esc_attr($variation_id) . '">' . esc_html($term->name) . '</span>';
                                                                                                                            }
                                                                                                                        } ?>
                                                                                                                    </div>
                                                                                                    <?php }
                                                                        }
                                                                    } ?>
                                                                </div>
                                                                <?php
                                    }
                                } ?>


                            </div>

                        </div>


                        <!--/ Product Meta -->

                    </div>
                </div>


                <?php
}

function zeynaProductBox($settings, $product, $classes, $cursor = '', $image = true, $customMedia = false)
{


    $style = $settings['product_style']; ?>


                <div <?php wc_product_class($classes, $product); ?> data-product-id="<?php echo get_the_ID(); ?>">
                    <?php if ($settings['behavior'] === 'add-to-cart' && $product->is_type('variable')) { ?>
                                    <div class="pop--behavior--center quick-add-to-cart-popup quick_pop_id-<?php echo get_the_ID(); ?>"
                                        data-product-id="<?php echo get_the_ID(); ?>" style="display: none">
                                        <span class="pop--overlay"></span>

                                        <div class="pe--styled--popup">

                                            <span class="pop--close">

                                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                                                    <path
                                                        d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z" />
                                                </svg>

                                            </span>
                                            <div class="zeyna--popup--cart--product">

                                                <div class="zeyna--popup--cart-product-image">
                                                    <img class="spcp--img" src="">
                                                </div>

                                                <div class="zeyna--popup--cart-product-meta">

                                                    <div class="zeyna--popup--cart-product-cont">
                                                        <h6 class="spcp--price"></h6>
                                                        <h4 class="spcp--title"></h4>
                                                        <p class="spcp--desc no-margin"></p>

                                                    </div>
                                                    <div class="zeyna--popup--cart-product-form"></div>
                                                </div>


                                            </div>

                                        </div>
                                    </div>
                    <?php } ?>
                    <div class="zeyna--product--wrap product--box--wrap">
                        <?php
                        if ($product->is_on_sale()) {
                            $regular_price = (float) $product->get_regular_price();
                            $sale_price = (float) $product->get_price();
                            $discount_percentage = calculate_discount_percentage($regular_price, $sale_price);

                            if ($discount_percentage > 0) {
                                echo '<p class="discount-badge">-' . $discount_percentage . '%</p>';
                            }
                        }

                        if ($image) {

                            zeynaProductImage($product, $cursor, $settings, false, $customMedia);
                        }

                        ?>

                        <!-- Product Meta -->
                        <div class="zeyna--product--meta">
                            <div class="zeyna--product--main">

                                <?php echo '<div class="product-name ' . esc_attr(apply_filters('woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title')) . '">' . get_the_title() . '</div>'; // Product title 
                                
                                    if ($settings['price'] === 'show') {

                                        if ($price_html = $product->get_price_html()) { ?>
                                                                <div class="product-price"><?php echo do_shortcode($price_html); ?></div><!-- Product Price -->
                                                <?php }
                                    }

                                    if ($settings['short__desc'] === 'show') {
                                        echo '<div class="product-short-desc">' . $product->get_short_description() . '</div>';
                                    }
                                    ?>


                            </div>

                            <div class="zeyna--product--extras">

                                <?php if ($settings['show_categories'] === 'yes') { ?>

                                                <div class="zeyna--product--cats">

                                                    <?php

                                                    $selectedCats = $settings['single_categories_to_show'];
                                                    $categories = wp_get_post_terms($product->get_id(), 'product_cat');

                                                    foreach ($categories as $category) {
                                                        if (!empty($selectedCats)) {
                                                            if (in_array($category->term_id, $selectedCats)) {
                                                                echo '<span>' . $category->name . '</span>';
                                                            }
                                                        } else {
                                                            echo '<span>' . $category->name . '</span>';
                                                        }

                                                    } ?>

                                                </div>

                                <?php } ?>

                                <?php if ($settings['show_tags'] === 'yes') { ?>

                                                <div class="zeyna--product--tags">

                                                    <?php

                                                    $selectedTags = $settings['single_tags_to_show'];
                                                    $tags = wp_get_post_terms($product->get_id(), 'product_tag');

                                                    foreach ($tags as $tag) {
                                                        if (!empty($selectedTags)) {
                                                            if (in_array($tag->term_id, $selectedTags)) {
                                                                echo '<span>' . $tag->name . '</span>';
                                                            }
                                                        } else {
                                                            echo '<span>' . $tag->name . '</span>';
                                                        }
                                                    } ?>

                                                </div>

                                <?php } ?>

                                <?php if ($settings['show_variations'] === 'yes') {
                                    $attributes = $settings['single_attributes_to_show'];
                                    $swatches = '';

                                    if (!empty($attributes)) {
                                        if ($settings['variations_swatches'] === 'yes') {
                                            $variations = $product->get_available_variations();
                                            $swatches = 'has--swatches';
                                        }
                                        ?>
                                                                <div class="zeyna--single--product--attributes <?php echo esc_attr($swatches) ?>">
                                                                    <?php
                                                                    foreach ($attributes as $attribute) {
                                                                        $vars = wc_get_attribute($attribute);

                                                                        if ($vars) {
                                                                            $taxonomy = esc_attr($vars->slug);
                                                                            $id = $vars->id;
                                                                            $display_type = get_option("wc_attribute_display_type-$id", 'default');
                                                                            $terms = wc_get_product_terms($product->get_id(), $taxonomy, array('fields' => 'all'));

                                                                            if (!empty($terms)) { ?>
                                                                                                                    <div class="single--product--attributes attr--dt--<?php echo $display_type ?>">
                                                                                                                        <?php
                                                                                                                        foreach ($terms as $term) {

                                                                                                                            $variation_id = null;

                                                                                                                            if ($settings['variations_swatches'] === 'yes') {

                                                                                                                                foreach ($variations as $variation) {
                                                                                                                                    if (
                                                                                                                                        isset($variation['attributes']["attribute_$taxonomy"]) &&
                                                                                                                                        $variation['attributes']["attribute_$taxonomy"] == $term->slug
                                                                                                                                    ) {
                                                                                                                                        $variation_id = $variation['variation_id'];
                                                                                                                                        break;
                                                                                                                                    }
                                                                                                                                }

                                                                                                                            }
                                                                                                                            if (get_field('term_color', $term)) {
                                                                                                                                echo '<span style="--bg: ' . get_field('term_color', $term) . '" data-variation-id="' . esc_attr($variation_id) . '">' . esc_html($term->name) . '</span>';
                                                                                                                            } else {
                                                                                                                                echo '<span class="pe--styled--object" data-variation-id="' . esc_attr($variation_id) . '">' . esc_html($term->name) . '</span>';
                                                                                                                            }
                                                                                                                        } ?>
                                                                                                                    </div>
                                                                                                    <?php }
                                                                        }
                                                                    } ?>
                                                                </div>
                                                                <?php
                                    }
                                } ?>


                            </div>

                            <?php echo zeynaProductActions($product, $settings); ?>

                        </div>


                        <!--/ Product Meta -->

                    </div>
                </div>

                <?php
}

function zeynaProductListRender($settings, $product, $classes, $cursor = '')
{

    $style = $settings['product_style'];
    ?>

                <div <?php wc_product_class($classes, $product); ?> data-product-id="<?php echo get_the_ID(); ?>">
                    <?php if ($settings['behavior'] === 'add-to-cart' && $product->is_type('variable')) { ?>
                                    <div class="pop--behavior--center quick-add-to-cart-popup quick_pop_id-<?php echo get_the_ID(); ?>"
                                        data-product-id="<?php echo get_the_ID(); ?>" style="display: none">
                                        <span class="pop--overlay"></span>

                                        <div class="pe--styled--popup">

                                            <span class="pop--close">

                                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                                                    <path
                                                        d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z" />
                                                </svg>

                                            </span>
                                            <div class="zeyna--popup--cart--product">

                                                <div class="zeyna--popup--cart-product-image">
                                                    <img class="spcp--img" src="">
                                                </div>

                                                <div class="zeyna--popup--cart-product-meta">

                                                    <div class="zeyna--popup--cart-product-cont">
                                                        <h6 class="spcp--price"></h6>
                                                        <h4 class="spcp--title"></h4>
                                                        <p class="spcp--desc no-margin"></p>

                                                    </div>
                                                    <div class="zeyna--popup--cart-product-form"></div>
                                                </div>


                                            </div>

                                        </div>
                                    </div>
                    <?php } ?>

                    <div class="zeyna--product--wrap">
                        <?php
                        if ($product->is_on_sale()) {
                            $regular_price = (float) $product->get_regular_price();
                            $sale_price = (float) $product->get_price();
                            $discount_percentage = calculate_discount_percentage($regular_price, $sale_price);

                            if ($discount_percentage > 0) {
                                echo '<p class="discount-badge">-' . $discount_percentage . '%</p>';
                            }
                        }

                        zeynaProductImage($product, $cursor, $settings, false);

                        ?>

                        <!-- Product Meta -->
                        <div class="zeyna--product--meta">
                            <div class="zeyna--product--main">

                                <?php echo '<div class="product-name ' . esc_attr(apply_filters('woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title')) . '">' . get_the_title() . '</div>'; // Product title 
                                
                                    if ($settings['price'] === 'show') {

                                        if ($price_html = $product->get_price_html()) { ?>
                                                                <div class="product-price"><?php echo do_shortcode($price_html); ?></div><!-- Product Price -->
                                                <?php }
                                    } ?>

                            </div>

                            <div class="zeyna--product--extras">

                                <?php if ($settings['show_variations'] === 'yes') {
                                    $attributes = $settings['single_attributes_to_show'];

                                    if (!empty($attributes)) { ?>
                                                                <div class="zeyna--single--product--attributes">
                                                                    <?php

                                                                    foreach ($attributes as $attribute) {
                                                                        $vars = wc_get_attribute($attribute);
                                                                        if ($vars) {
                                                                            $taxonomy = esc_attr($vars->slug);
                                                                            $id = $vars->id;
                                                                            $display_type = get_option("wc_attribute_display_type-$id", 'default');
                                                                            $terms = wc_get_product_terms($product->get_id(), $taxonomy, array('fields' => 'all'));

                                                                            if (!empty($terms)) { ?>
                                                                                                                    <div class="single--product--attributes attr--dt--<?php echo $display_type ?>">
                                                                                                                        <?php foreach ($terms as $term) {
                                                                                                                            if (get_field('term_color', $term)) {
                                                                                                                                echo '<span style="--bg: ' . get_field('term_color', $term) . '">' . esc_html($term->name) . '</span>';
                                                                                                                            } else {
                                                                                                                                echo '<span class="pe--styled--object >' . esc_html($term->name) . '</span>';
                                                                                                                            }

                                                                                                                        } ?>
                                                                                                                    </div>
                                                                                                    <?php } ?>

                                                                                    <?php }
                                                                    } ?>

                                                                </div>

                                                <?php }
                                } ?>

                            </div>

                        </div>

                        <div class="list--product--meta--2">


                            <?php if ($settings['show_categories'] === 'yes') { ?>

                                            <div class="zeyna--product--cats">

                                                <?php

                                                $selectedCats = $settings['single_categories_to_show'];
                                                $categories = wp_get_post_terms($product->get_id(), 'product_cat');

                                                foreach ($categories as $category) {
                                                    if (!empty($selectedCats)) {
                                                        if (in_array($category->term_id, $selectedCats)) {
                                                            echo '<span>' . $category->name . '</span>';
                                                        }
                                                    } else {
                                                        echo '<span>' . $category->name . '</span>';
                                                    }

                                                } ?>

                                            </div>

                            <?php } ?>

                            <?php if ($settings['show_tags'] === 'yes') { ?>

                                            <div class="zeyna--product--tags">

                                                <?php

                                                $selectedTags = $settings['single_tags_to_show'];
                                                $tags = wp_get_post_terms($product->get_id(), 'product_tag');

                                                foreach ($tags as $tag) {
                                                    if (!empty($selectedTags)) {
                                                        if (in_array($tag->term_id, $selectedTags)) {
                                                            echo '<span>' . $tag->name . '</span>';
                                                        }
                                                    } else {
                                                        echo '<span>' . $tag->name . '</span>';
                                                    }
                                                } ?>

                                            </div>

                            <?php } ?>

                            <?php
                            if ($settings['short__desc'] === 'show') {
                                echo '<div class="product-short-desc">' . $product->get_short_description() . '</div>';
                            }
                            ?>
                        </div>



                        <?php

                        echo zeynaProductActions($product, $settings);

                        ?>


                        <!--/ Product Meta -->

                    </div>
                </div>


<?php }


function zeyna_product_query_selection($widget, $highlights = false, $condition = false)
{

    if (!class_exists('WooCommerce')) {
        return false;
    }

    $cond = [];

    if ($condition) {
        $cond = $condition;
    }



    $widget->add_control(
        'product_selection',
        [
            'label' => esc_html__('Selection', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'all',
            'options' => [
                'by--cat' => esc_html__('By Category', 'pe-core'),
                'by--hand' => esc_html__('Manual Select', 'pe-core'),
                'by--tag' => esc_html__('By Tag', 'pe-core'),
                'by--brand' => esc_html__('By Brand', 'pe-core'),
                'all' => esc_html__('Get All Products', 'pe-core'),
            ],
            'condition' => $condition,

        ]
    );

    $repeaterProducts = [];

    $products = get_posts([
        'post_type' => 'product',
        'numberposts' => -1
    ]);

    foreach ($products as $product) {
        $repeaterProducts[$product->ID] = $product->post_title;
    }

    $productsRepeater = new \Elementor\Repeater();

    $productsRepeater->add_control(
        'select_product',
        [
            'label' => __('Select Product', 'pe-core'),
            'label_block' => true,
            'description' => __('Select project which will display in the slider.', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $repeaterProducts,
        ]
    );

    $widget->add_control(
        'products_list',
        [
            'label' => esc_html__('Products', 'pe-core'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $productsRepeater->get_controls(),
            'show_label' => false,
            'condition' => [
                $condition,
                'product_selection' => 'by--hand',
            ],
        ]
    );


    $productCats = array();

    $args = array(
        'hide_empty' => true,
        'taxonomy' => 'product_cat'
    );

    $categories = get_categories($args);

    foreach ($categories as $key => $category) {
        $productCats[$category->term_id] = $category->name;
    }

    $widget->add_control(
        'product_filter_cats',
        [
            'label' => __('Categories', 'pe-core'),
            'description' => __('Select categories to display products.', 'pe-core'),
            'label_block' => true,
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $productCats,
            'condition' => [
                ...$cond,
                'product_selection' => 'by--cat',
            ],
        ]
    );

    $productTags = array();

    $args = array(
        'hide_empty' => true,
        'taxonomy' => 'product_tag'
    );

    $tags = get_categories($args);

    foreach ($tags as $key => $tag) {
        $productTags[$tag->term_id] = $tag->name;
    }

    $widget->add_control(
        'product_filter_tags',
        [
            'label' => __('Tags', 'pe-core'),
            'description' => __('Select tags to display products.', 'pe-core'),
            'label_block' => true,
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $productTags,
            'condition' => [
                ...$cond,
                'product_selection' => 'by--tag',
            ],
        ]
    );

    $productBrands = array();

    $brands = get_terms(array(
        'taxonomy' => 'brand',
        'hide_empty' => false,
    ));

    if ($brands) {
        foreach ($brands as $key => $brand) {
            if ($brand && $productBrands) {
                $productBrands[$brand->term_id] = $brand->name;
            }

        }

    }

    $widget->add_control(
        'product_filter_brands',
        [
            'label' => __('Brand', 'pe-core'),
            'description' => __('Select brands to display products.', 'pe-core'),
            'label_block' => true,
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $productBrands,
            'condition' => [
                ...$cond,
                'product_selection' => 'by--brand',
            ],
        ]
    );

    $widget->add_control(
        'exclude_products',
        [
            'label' => esc_html__('Exclude Products', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'options' => $repeaterProducts,
            'multiple' => true,
            'condition' => [
                ...$cond,
                'product_selection!' => ['by--hand'],
            ],
        ]
    );

    $widget->add_control(
        'number_products',
        [
            'label' => esc_html__('Posts Per View', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 999,
            'step' => 1,
            'render_type' => 'template',
            'default' => 10,
            'condition' => [
                ...$cond,
                'product_selection!' => ['by--hand'],
            ],

        ]
    );

    $widget->add_control(
        'products_order_by',
        [
            'label' => esc_html__('Order By', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'date',
            'options' => [
                'ID' => esc_html__('ID', 'pe-core'),
                'title' => esc_html__('Title', 'pe-core'),
                'date' => esc_html__('Date', 'pe-core'),
                'author' => esc_html__('Author', 'pe-core'),
                'type' => esc_html__('Type', 'pe-core'),
                'rand' => esc_html__('Random', 'pe-core'),
            ],
            'condition' => [
                ...$cond,
                'product_selection!' => ['by--hand'],
            ],
        ]
    );

    $widget->add_control(
        'products_order',
        [
            'label' => esc_html__('Order', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'DESC',
            'options' => [
                'ASC' => esc_html__('ASC', 'pe-core'),
                'DESC' => esc_html__('DESC', 'pe-core')

            ],
            'condition' => [
                ...$cond,
                'product_selection!' => ['by--hand'],
            ],

        ]
    );

    if ($highlights) {


        $widget->add_control(
            'highlight_products',
            [
                'label' => __('Highlight Products', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core'),
                'label_off' => __('No', 'pe-core'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    ...$cond
                ],
            ]
        );

        $widget->add_control(
            'highlight_by',
            [
                'label' => esc_html__('Hightlight By;', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'key',
                'options' => [
                    'key' => esc_html__('Key', 'pe-core'),
                    'product' => esc_html__('Product', 'pe-core'),
                ],
                'condition' => [
                    ...$cond,
                    'highlight_products' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'highlighted_products',
            [
                'label' => __('Highlighted Products', 'pe-core'),
                'description' => __('Select products that will be highlighted.', 'pe-core'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $repeaterProducts,
                'condition' => [
                    ...$cond,
                    'highlight_by' => 'product',
                ],
            ]
        );


        $widget->add_control(
            'highlight_keys',
            [
                'label' => esc_html__('Highlight by Index', 'pe-core'),
                'description' => esc_html__('Enter product keys. For example: "2,5" that means 2nd and 5th items will be highlighted.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => false,
                'condition' => [
                    ...$cond,
                    'highlight_by' => 'key',
                ],
            ]
        );

    }


}

function zeyna_product_query_args($widget)
{

    $settings = $widget->get_settings_for_display();
    $taxQuery = [];

    if (isset($_GET['filter']) && $_GET['filter'] == true) {
        $taxQuery = [
            'relation' => 'AND',
        ];

        $attributes = wc_get_attribute_taxonomies();

        foreach ($attributes as $key => $attr) {
            $name = $attr->attribute_name;

            if (isset($_GET[$name])) {
                $taxQuery[] = [
                    'taxonomy' => 'pa_' . $name,
                    'field' => 'slug',
                    'terms' => $_GET[$name],
                    'operator' => 'AND'
                ];
            }
        }

        if (isset($_GET['product_cat']) && $_GET['product_cat'] !== 'all') {

            $taxQuery[] = [
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'terms' => $_GET['product_cat'],
                'operator' => 'AND'
            ];
        }

        if (isset($_GET['product_tag'])) {

            $taxQuery[] = [
                'taxonomy' => 'product_tag',
                'field' => 'id',
                'terms' => $_GET['product_tag'],
                'operator' => 'AND'
            ];
        }

        if (isset($_GET['brand'])) {

            $taxQuery[] = [
                'taxonomy' => 'brand',
                'field' => 'id',
                'terms' => $_GET['brand'],
                'operator' => 'AND'
            ];

        }

        $attributes = wc_get_attribute_taxonomies();

        foreach ($attributes as $attribute) {
            $attr = 'pa_' . $attribute->attribute_name;
            if (isset($_GET[$attr])) {
                $taxQuery[] = [
                    'taxonomy' => $attr,
                    'field' => 'slug',
                    'terms' => array_map('sanitize_text_field', $_GET[$attr]),
                    'operator' => 'AND'
                ];
            }
        }

    }

    if (isset($_GET['min_price']) || isset($_GET['max_price'])) {
        $meta_query = array('relation' => 'AND');

        if (isset($_GET['min_price']) && !empty($_GET['min_price'])) {
            $meta_query[] = array(
                'key' => '_price',
                'value' => floatval($_GET['min_price']),
                'compare' => '>=',
                'type' => 'NUMERIC',
            );
        }

        if (isset($_GET['max_price']) && !empty($_GET['max_price'])) {
            $meta_query[] = array(
                'key' => '_price',
                'value' => floatval($_GET['max_price']),
                'compare' => '<=',
                'type' => 'NUMERIC',
            );
        }
    } else {
        $meta_query = false;
    }

    isset($_GET['offset']) ? $offset = $_GET['offset'] : $offset = 0;

    if ($settings['product_selection'] === 'by--hand') {

        $ids = [];

        foreach ($settings['products_list'] as $key => $product) {
            $ids[] = $product['select_product'];
        }

        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 10,
            'post__in' => $ids,
            'orderby' => 'post__in'
        );

    } else if ($settings['product_selection'] === 'by--brand' || $settings['product_selection'] === 'by--tag' || $settings['product_selection'] === 'by--cat' || $settings['product_selection'] === 'all') {

        $excluded = is_array($settings['exclude_products']) ? $settings['exclude_products'] : [$settings['exclude_products']];
        $cats = $settings['product_filter_cats'];
        $tags = $settings['product_filter_tags'];
        $brands = $settings['product_filter_brands'];


        if ($settings['product_selection'] === 'by--cat' && !empty($cats)) {
            $taxQuery[] = [
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'terms' => $cats,
            ];
        }

        if ($settings['product_selection'] === 'by--tag' && !empty($tags)) {
            $taxQuery[] = [
                'taxonomy' => 'product_tag',
                'field' => 'id',
                'terms' => $tags,
            ];
        }

        if ($settings['product_selection'] === 'by--brand' && !empty($brands)) {
            $taxQuery[] = [
                'taxonomy' => 'brand',
                'field' => 'id',
                'terms' => $brands,
            ];
        }

        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $settings['number_products'],
            // 'offset' => $offset * (isset($settings['number_products']) ? $settings['number_products'] : 1),
            'orderby' => $settings['products_order_by'],
            'order' => $settings['products_order'],
            'post__not_in' => $excluded,
            'tax_query' => $taxQuery,
            'meta_query' => $meta_query,
        );

        if (isset($_GET['orderby'])) {
            $orderby = sanitize_text_field($_GET['orderby']);

            switch ($orderby) {
                case 'menu_order':
                    $args['orderby'] = 'menu_order title';
                    $args['order'] = 'ASC';
                    break;
                case 'popularity':
                    $args['meta_key'] = 'total_sales';
                    $args['orderby'] = 'meta_value_num';
                    break;
                case 'rating':
                    $args['meta_key'] = '_wc_average_rating';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                    break;
                case 'date':
                    $args['orderby'] = 'date';
                    $args['order'] = 'DESC';
                    break;
                case 'price':
                    $args['meta_key'] = '_price';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'ASC';
                    break;
                case 'price-desc':
                    $args['meta_key'] = '_price';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                    break;
                default:
                    $args['orderby'] = 'menu_order';
                    $args['order'] = 'ASC';
                    break;
            }
        }

        if (isset($_GET['sale_products']) && $_GET['sale_products'] == 1) {
            $args['post__in'] = wc_get_product_ids_on_sale();
        }

    }

    if (isset($settings['is_related_query']) && $settings['is_related_query'] === 'yes' && is_product() && !\Elementor\Plugin::$instance->editor->is_edit_mode()) {

        $product_id = get_the_ID();

        $product = wc_get_product($product_id);
        $terms_cat = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
        $terms_tag = wp_get_post_terms($product_id, 'product_tag', array('fields' => 'ids'));

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $settings['number_products'],
            'post__not_in' => array($product_id),
            'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $terms_cat,
                ),
                array(
                    'taxonomy' => 'product_tag',
                    'field' => 'term_id',
                    'terms' => $terms_tag,
                ),
            ),
        );
    }

    if (isset($settings['is_fbt_query']) && $settings['is_fbt_query'] === 'yes' && is_product() && !\Elementor\Plugin::$instance->editor->is_edit_mode()) {

        $product_id = get_the_ID();
        $product = wc_get_product($product_id);

        $fbt_data = get_post_meta($product->get_id(), '_fbt_data', true);
        if (!empty($fbt_data)) {

            $ids = [];
            foreach ($fbt_data as $key => $product) {
                $ids[] = $product['product_id'];
            }

            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => 99,
                'post__in' => $ids,
                'orderby' => 'post__in'
            );

        }
    }

    if (isset($settings['is_wishlist']) && $settings['is_wishlist'] === 'yes' && !\Elementor\Plugin::$instance->editor->is_edit_mode()) {

        if (is_user_logged_in()) {

            $user_id = get_current_user_id();
            $wishlist = get_user_meta($user_id, 'pe_wishlist', true);
            $wishlist = is_array($wishlist) ? $wishlist : [];

        } else {
            $wishlist = isset($_COOKIE['pe_wishlist']) ? json_decode(stripslashes($_COOKIE['pe_wishlist']), true) : [];
            $wishlist = is_array($wishlist) ? $wishlist : [];
        }

        if (isset($_GET['wishlist'])) {
            $wishlist = is_array($wishlist) ? $wishlist : [];
        }

        if (!empty($wishlist)) {

            $ids = [];
            foreach ($wishlist as $product_id) {
                $ids[] = $product_id;
            }

            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => 99,
                'post__in' => $ids,
                'orderby' => 'post__in'
            );

        } else {
            $args = array(
                'post_type' => 'none',

            );
        }
    }

    return $args;

}

function variationStyles($widget, $prefix, $selector)
{


    if ($prefix === 'vr_colors_only') {

        $widget->add_control(
            'vr_colors_style',
            [
                'label' => esc_html__('Interaction Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'opacity',
                'options' => [
                    'opacity' => esc_html__('Opacity', 'pe-core'),
                    'bordered' => esc_html__('Bordered', 'pe-core'),
                ],
                'label_block' => false,
                'prefix_class' => 'colors--interaction--',
            ]
        );

        $widget->add_control(
            'vr_colors_label_vis',
            [
                'label' => esc_html__('Label Visibility', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'on--hover',
                'options' => [
                    'on--hover' => esc_html__('On Hover', 'pe-core'),
                    'hidden' => esc_html__('Hidden', 'pe-core'),
                    'visible' => esc_html__('Visible', 'pe-core'),
                ],
                'label_block' => false,
                'prefix_class' => 'colors--labels--',
            ]
        );

    }

    $widget->add_control(
        $prefix . '_selected_variation_style',
        [
            'label' => esc_html__('Selected Variation Style', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'default',
            'options' => [
                'default' => esc_html__('Default', 'pe-core'),
                'underlined' => esc_html__('Underlined', 'pe-core'),
            ],
            'label_block' => false,
            'prefix_class' => $prefix . '_active_',

        ]
    );


    if ($prefix !== 'vr_colors_only') {



        $widget->add_control(
            $prefix . '_has_underline',
            [
                'label' => esc_html__('Unerlined', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => $prefix . '--underlined',
                'prefix_class' => '',
                'default' => '',
            ]
        );
    }

    $widget->add_control(
        $prefix . '_has_border',
        [
            'label' => esc_html__('Bordered', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => $prefix . '--bordered',
            'prefix_class' => '',
            'default' => $prefix . '--bordered',
        ]
    );

    $widget->add_control(
        $prefix . '_has_rounded',
        [
            'label' => esc_html__('Rounded', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => $prefix . '--rounded',
            'prefix_class' => '',
            'default' => $prefix . '--rounded',
        ]
    );

    if ($prefix !== 'vr_colors_only') {
        $widget->add_control(
            $prefix . '_has_bg',
            [
                'label' => esc_html__('Background', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => $prefix . '--has--bg',
                'prefix_class' => '',
                'default' => $prefix . '--has--bg',
            ]
        );
    }

    $widget->add_responsive_control(
        $prefix . '_has_border_radius',
        [
            'label' => esc_html__('Border Radius', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', 'rem', 'vw', 'custom'],
            'selectors' => [
                '{{WRAPPER}}.' . $prefix . '--pop--active .' . $selector . ' .zeyna-variation-radio-buttons .attr--label' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};overflow: hidden',
                '{{WRAPPER}}.' . $prefix . '--pop--active .' . $selector . '  .zeyna-variation-radio-buttons:has(.attr--meta) label.radio--parent' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};overflow: hidden',
                '{{WRAPPER}}.' . $prefix . '--pop--active .' . $selector . ' .zeyna-variation-radio-buttons span.attr--color' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};overflow: hidden',
            ],
        ]
    );

    if ($prefix !== 'vr_colors_only') {

        $widget->add_control(
            $prefix . '_has_padding',
            [
                'label' => esc_html__('Padding', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}}.' . $prefix . '--pop--active .' . $selector . ' .zeyna-variation-radio-buttons .attr--label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.' . $prefix . '--pop--active .' . $selector . ' .zeyna-variation-radio-buttons:has(.attr--meta) label.radio--parent' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


    }

    if ($prefix === 'vr_colors_only') {

        $widget->add_responsive_control(
            $prefix . '_color_width',
            [
                'label' => esc_html__('Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.' . $prefix . '--pop--active .zeyna-variation-radio-buttons span.attr--color' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_responsive_control(
            $prefix . '_color_height',
            [
                'label' => esc_html__('Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.' . $prefix . '--pop--active .zeyna-variation-radio-buttons span.attr--color' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

    }

    if ($prefix === 'vr_labels_images') {

        $widget->add_responsive_control(
            $prefix . 'image__has_border_radius',
            [
                'label' => esc_html__('Image Border Radius', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}}.' . $prefix . '--pop--active .variation_image_label .zeyna-variation-radio-buttons:has(.attr--meta) label.radio--parent .attr--thumb' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};overflow: hidden',

                ],
            ]
        );

        $widget->add_responsive_control(
            $prefix . '_metas_alignment',
            [
                'label' => esc_html__('Metas Alignment', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Start', 'pe-core'),
                        'icon' => 'eicon-justify-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'pe-core'),
                        'icon' => 'eicon-justify-space-around-v',
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'pe-core'),
                        'icon' => 'eicon-justify-end-v',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .zeyna-variation-radio-buttons:has(.attr--meta) label.radio--parent' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $widget->add_responsive_control(
            $prefix . '_image_width',
            [
                'label' => esc_html__('Image Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.' . $prefix . '--pop--active .attr--thumb' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_responsive_control(
            $prefix . '_image_height',
            [
                'label' => esc_html__('Image Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.' . $prefix . '--pop--active .attr--thumb' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );



    }

}

function objectStyles($widget, $style_prefix, $style_label, $style_selector, $typo = true, $condition = false, $section = true, $simple = false, $dimensions = true, $popover = false)
{


    $selector = '{{WRAPPER}} ' . $style_selector;

    if ($section) {
        $widget->start_controls_section(
            $style_prefix . '_styles',
            [

                'label' => esc_html__($style_label . ' Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => $condition,
            ]
        );
    } else if (!$popover) {

        $widget->add_control(
            $style_prefix . '_elementor_bg_notice',
            [
                'label' => esc_html__($style_label . ' Styles', 'pe-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => $condition,
            ]
        );
    }

    if ($popover) {

        $widget->add_control(
            $style_prefix . '_object_styles_popover',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__($style_label . ' Styles', 'pe-core'),
                'label_off' => esc_html__('Default', 'pe-core'),
                'label_on' => esc_html__('Custom', 'pe-core'),
                'return_value' => $style_prefix . '--pop--styled',
                'prefix_class' => '',
                'condition' => $condition,

            ]
        );

        $selector = '{{WRAPPER}}.' . $style_prefix . '--pop--styled ' . $style_selector;

        $widget->start_popover();
    }


    if ($typo && !$popover) {

        $widget->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => $style_prefix . '_typography',
                'label' => esc_html__('Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} ' . $style_selector,
                'condition' => $condition,
            ]
        );

    }

    $widget->add_responsive_control(
        $style_prefix . '_text_align',
        [
            'label' => esc_html__('Alignment', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => esc_html__('Left', 'pe-core'),
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-text-align-center'
                ],
                'right' => [
                    'title' => esc_html__('Right', 'pe-core'),
                    'icon' => 'eicon-text-align-right',
                ],
            ],
            'toggle' => true,
            'condition' => $condition,
            'selectors' => [
                $selector => 'text-align:  {{VALUE}};',
            ],
        ]
    );


    if (!$simple) {


        $widget->add_control(
            $style_prefix . '_has_bg',
            [
                'label' => esc_html__('Background', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'has--bg',
                'default' => '',
                'selectors' => [
                    $selector . '::before' => 'content: ""',
                ],
                'condition' => $condition,
            ]
        );

        $widget->add_control(
            $style_prefix . '_has_backdrop',
            [
                'label' => esc_html__('Backdrop Filter', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'has--backdrop',
                'selectors' => [
                    $selector . '::before' => 'content: "";',
                ],

                'default' => '',
                'condition' => $condition,
            ]
        );


        $widget->add_responsive_control(
            $style_prefix . '_bg_backdrop_blur',
            [
                'label' => esc_html__('Bluriness', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'condition' => [
                    $style_prefix . '_has_backdrop' => 'has--backdrop',
                ],
                'selectors' => [
                    $selector => '--backdropBlur: {{SIZE}}{{UNIT}};',
                    $selector . '::before' => 'backdrop-filter: blur(var(--backdropBlur));',
                ],
            ]
        );


        if ($widget->get_name() !== 'pesitenavigation') {


            // $widget->add_control(
            //     $style_prefix . '_backdrop_color',
            //     [
            //         'label' => esc_html__('Backdrop Color', 'pe-core'),
            //         'type' => \Elementor\Controls_Manager::COLOR,
            //         'selectors' => [
            //             '{{WRAPPER}} ' . $style_selector => 'background-color: {{VALUE}}',
            //         ],
            //         'condition' => [
            //             $style_prefix . '_has_backdrop' => 'has--backdrop',
            //         ],
            //     ]
            // );
        }
    }


    $widget->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
        [
            'name' => $style_prefix . '_border',
            'selector' => '{{WRAPPER}} ' . $style_selector,
            'important' => true,
            'condition' => $condition,
        ]
    );


    $widget->add_responsive_control(
        $style_prefix . '_has_border_radius',
        [
            'label' => esc_html__('Border Radius', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', 'rem', '%', 'custom'],
            'selectors' => [
                $selector => '--radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} !important;',
            ],
            'condition' => $condition,
        ]
    );

    $widget->add_responsive_control(
        $style_prefix . '_has_padding',
        [
            'label' => esc_html__('Padding', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', 'rem', '%', 'custom'],
            'selectors' => [
                $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;--paddingTop: {{TOP}}{{UNIT}};--paddingLeft: {{LEFT}}{{UNIT}};--paddingBottom: {{BOTTOM}}{{UNIT}};--paddingRight: {{RIGHT}}{{UNIT}}',
            ],
            'condition' => $condition,
        ]
    );

    $widget->add_responsive_control(
        $style_prefix . '_has_margin',
        [
            'label' => esc_html__('Margin', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', 'rem', '%', 'custom'],
            'selectors' => [
                $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => $condition,
        ]
    );

    if (!$popover) {

        $widget->add_control(
            $style_prefix . '_box_shadow',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__('Box Shadow', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'label_on' => esc_html__('Yes', 'pe-core'),
                'return_value' => 'objectShadow',
                'selectors' => [
                    $selector => 'box-shadow: var(--{{VALUE}});',
                ],
                'condition' => $condition,
            ]
        );

        $widget->start_popover();

        $widget->add_control(
            $style_prefix . '_custom_box_shadow',
            [
                'label' => esc_html__('Box Shadow', 'pe-core'),
                'type' => \Elementor\Controls_Manager::BOX_SHADOW,
                'selectors' => [
                    $selector => '--objectShadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
                ],
                'condition' => [$style_prefix . '_box_shadow' => 'objectShadow']
            ]
        );

        $widget->end_popover();

    }




    if ($dimensions) {

        $widget->add_responsive_control(
            $style_prefix . '_width',
            [
                'label' => esc_html__('Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    $selector => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => $condition,
            ]
        );

        $widget->add_responsive_control(
            $style_prefix . '_height',
            [
                'label' => esc_html__('Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    $selector => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => $condition,
            ]
        );
    }

    $option = get_option('pe-redux');

    if (isset($option['sec_typo']) && !empty($option['sec_typo']['font-family'])) {

        $widget->add_control(
            $style_prefix . '_use_secondary_font',
            [
                'label' => esc_html__('Use Secondary Font', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} ' . $style_selector . ' > * ' => '
                    font-family: var(--sec_typo-font-family);
                    font-size: var(--sec_typo-font-size);
                    line-height: var(--sec_typo-line-height);
                    letter-spacing: var(--sec_typo-letter-spacing);
                    font-weight: var(--sec_typo-font-weight);
               text-transform: var(--sec_typo-text-transform);',
                    '{{WRAPPER}} ' . $style_selector . '' => '
                    font-family: var(--sec_typo-font-family);
                    font-size: var(--sec_typo-font-size);
                    line-height: var(--sec_typo-line-height);
                    letter-spacing: var(--sec_typo-letter-spacing);
                    font-weight: var(--sec_typo-font-weight);
               text-transform: var(--sec_typo-text-transform);',
                ],
                'condition' => $condition,

            ]
        );

    }

    $widget->add_control(
        $style_prefix . '_text_color',
        [
            'label' => esc_html__($style_label . ' Text Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                $selector => '--mainColor: {{VALUE}} ; color: {{VALUE}}',
            ],
        ]
    );

    $widget->add_control(
        $style_prefix . '_background_color',
        [
            'label' => esc_html__($style_label . ' Background Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                $selector . '' => '--secondaryBackground: {{VALUE}};',
                $selector . '::before' => 'background-color  : {{VALUE}};',
            ],
            'condition' => [
                $style_prefix . '_has_bg' => 'has--bg',
            ],
        ]
    );

    // if ($widget->get_name() === 'peicon') {



    //     $widget->add_control(
    // 		$style_prefix . '_outside_curve',
    // 		[
    // 			'label' => esc_html__('Outside Curve', 'pe-core'),
    // 			'description' => esc_html__('For "classic" background type only.', 'pe-core'),
    // 			'type' => \Elementor\Controls_Manager::SWITCHER,
    // 			'label_on' => esc_html__('Yes', 'pe-core'),
    // 			'label_off' => esc_html__('No', 'pe-core'),
    // 			'return_value' => 'outside--curved',
    // 			'default' => '',
    // 			'render_type' => 'template',
    // 			'prefix_class' => '',
    //             'condition' => [
    // 				$style_prefix . '_has_bg' => ['has--bg']
    // 			],


    // 		]
    // 	);

    // 	$widget->add_control(
    // 		$style_prefix . '_top_curve_pos',
    // 		[
    // 			'label' => __('Top Curve Position', 'zeyna'),
    // 			'type' => \Elementor\Controls_Manager::SELECT,
    // 			'default' => 'right',
    // 			'prefix_class' => 'top--curve--',
    // 			'options' => [
    // 				'right' => __('Right-Top', 'zeyna'),
    // 				'left' => __('Left-Top', 'zeyna'),
    // 				'right-right' => __('Right-Right', 'zeyna'),
    // 				'left-left' => __('Left-Left', 'zeyna'),
    // 			],
    // 			'condition' => [
    // 				$style_prefix . '_outside_curve' => ['outside--curved']
    // 			],
    // 		]
    // 	);

    // 	$widget->add_control(
    // 		$style_prefix . '_bottom_curve_pos',
    // 		[
    // 			'label' => __('Bottom Curve Position', 'zeyna'),
    // 			'type' => \Elementor\Controls_Manager::SELECT,
    // 			'default' => 'left',
    // 			'prefix_class' => 'bottom--curve--',
    // 			'options' => [
    // 				'right' => __('Right-Right', 'zeyna'),
    // 				'left' => __('Left-Left', 'zeyna'),
    // 				'right-bottom' => __('Right-Bottom', 'zeyna'),
    // 				'left-left' => __('Left-Bottom', 'zeyna'),
    // 			],
    // 			'condition' => [
    // 				$style_prefix . '_outside_curve' => ['outside--curved']
    // 			],
    // 		]
    // 	);


    // 	$widget->add_responsive_control(
    // 		$style_prefix . '_outside_curve_size',
    // 		[
    // 			'label' => esc_html__('Outside Curve Size', 'pe-core'),
    // 			'type' => \Elementor\Controls_Manager::SLIDER,
    // 			'size_units' => ['px'],
    // 			'range' => [
    // 				'px' => [
    // 					'min' => 0,
    // 					'max' => 150,
    // 					'step' => 1,
    // 				],
    // 			],
    // 			'default' => [
    // 				'unit' => 'px',
    // 				'size' => 20,
    // 			],
    // 			'condition' => [
    // 				$style_prefix . '_outside_curve' => ['outside--curved']
    // 			],
    // 			'selectors' => [
    // 				'{{WRAPPER}}' => '--outsideCurveWidth: {{SIZE}}{{UNIT}};',
    // 			],
    // 		]
    // 	);

    //     $widget->add_control(
    //         $style_prefix . '_outside_curve_color',
    //         [
    //             'label' => esc_html__('Curve Color', 'pe-core'),
    //             'type' => \Elementor\Controls_Manager::COLOR,
    //             'render_type' => 'template',
    //             'selectors' => [
    //                 '{{WRAPPER}}' => '--outsideCurveColor: {{VALUE}}',
    //             ],
    //             'condition' => [
    // 				$style_prefix . '_outside_curve' => ['outside--curved']
    // 			],
    //         ]
    //     );

    // }

    if ($popover) {
        $widget->end_popover();
    }


    if ($section) {
        $widget->end_controls_section();
    }



}

function popupOptions($widget, $condition = false)
{
    $widget->add_control(
        'popup_behavior',
        [
            'label' => esc_html__('Popup Behavior', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => esc_html__('Left', 'pe-core'),
                    'icon' => 'eicon-h-align-left',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle',
                ],
                'right' => [
                    'title' => esc_html__('Right', 'pe-core'),
                    'icon' => 'eicon-h-align-right',
                ],
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => 'eicon-v-align-bottom',
                ],
            ],
            'prefix_class' => 'pop--behavior--',
            'default' => 'top',
            'toggle' => false,
            'condition' => $condition,
        ]
    );

    $widget->add_control(
        'back_overlay',
        [
            'label' => esc_html__('Overlay', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'render_type' => 'template',
            'prefix_class' => 'pop-overlay-',
            'default' => 'true',
            'description' => esc_html__('Animation will follow scrolling behavior of the page.', 'pe-core'),
            'condition' => $condition,
        ]
    );

    $widget->add_control(
        'back_overlay_backdrop',
        [
            'label' => esc_html__('Overlay Backgrop Filter', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'prefix_class' => 'pop-overlay-backdrop-',
            'default' => '',
            'condition' => $condition,
        ]
    );

    $widget->add_responsive_control(
        'back_overlay_backdrop_blur',
        [
            'label' => esc_html__('Bluriness', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 10,
            ],
            'condition' => [
                'back_overlay_backdrop' => 'true',
            ],
            'selectors' => [
                '{{WRAPPER}} .pop--overlay' => '--backdropBlur: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_control(
        'back_overlay_color',
        [
            'label' => esc_html__('Overlay Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} span.pop--overlay' => 'background-color: {{VALUE}}',
            ],
            'condition' => $condition,
        ]
    );

    $widget->add_control(
        'pop_disable_scroll',
        [
            'label' => esc_html__('Disable Scroll', 'pe-core'),
            'description' => esc_html__('Page scrolling will disabled when popup is opened.', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'prefix_class' => 'pop--disable--scroll--',
            'default' => 'true',
            'condition' => $condition,
        ]
    );

    $widget->add_control(
        'popup_z_index',
        [
            'label' => esc_html__('Z-Index (Popup)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => -100,
            'max' => 99999999,
            'step' => 1,
            'selectors' => [
                '{{WRAPPER}} .pe--styled--popup' => 'z-index: {{VALUE}};',
            ],
            'condition' => $condition,
        ]
    );

    $widget->add_control(
        'popup_overlay_z_index',
        [
            'label' => esc_html__('Z-Index (Overlay)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => -100,
            'max' => 99999999,
            'step' => 1,
            'selectors' => [
                '{{WRAPPER}} .pop--overlay' => 'z-index: {{VALUE}};',
            ],
            'condition' => $condition,
        ]
    );

    $widget->add_control(
        'template_popup_action',
        [
            'label' => esc_html__('Popup Action', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'click',
            'prefix_class' => 'pop--action--',
            'render_type' => 'template',
            'options' => [
                'click' => esc_html__('Click', 'pe-core'),
                'hover' => esc_html__('Hover', 'pe-core'),
            ],
            'condition' => $condition,
        ]
    );

}

function popupStyles($widget, $condition = false, $selector = '', $prefix = '')
{
    $widget->add_responsive_control(
        $prefix . 'pe_popup_width',
        [
            'label' => esc_html__('Width', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw', 'rem', 'em', 'custom'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . '.pe--styled--popup' => 'min-width: {{SIZE}}{{UNIT}};max-width: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}}.pop--behavior--left div#zeyna-woo-search-results' => 'max-width: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}}.pop--behavior--right div#zeyna-woo-search-results' => 'max-width: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .woocommerce-privacy-policy-text' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . 'pe_popup_height',
        [
            'label' => esc_html__('Height', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vh', 'rem', 'em', 'custom'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . '.pe--styled--popup' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . 'pe_popup_border_radius',
        [
            'label' => esc_html__('Border Radius', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . '.pe--styled--popup' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};',

            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . 'pe_popup_padding',
        [
            'label' => esc_html__('Padding', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . '.pe--styled--popup' => '--popPadding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . 'popup_content_max_height_spacing',
        [
            'label' => esc_html__('Max Height (Content)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em', 'vh', 'custom'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . '.pe--styled--popup' => '--contMaxHeight: {{SIZE}}{{UNIT}};',
            ],
        ]
    );


    $widget->add_responsive_control(
        $prefix . 'popup_left_spacing',
        [
            'label' => esc_html__('Left Spacing', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em', 'vw', 'custom'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'condition' => [
                $prefix . 'popup_behavior' => 'left',
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . '.pe--styled--popup' => '--leftSpacing: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . 'popup_right_spacing',
        [
            'label' => esc_html__('Right Spacing', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em', 'vw', 'custom'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'condition' => [
                $prefix . 'popup_behavior' => 'right',
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . '.pe--styled--popup' => '--rightSpacing: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . 'popup_top_spacing',
        [
            'label' => esc_html__('Top Spacing', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em', 'vh', 'custom'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'condition' => [
                $prefix . 'popup_behavior' => ['right', 'top', 'left'],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . '.pe--styled--popup' => '--topSpacing: {{SIZE}}{{UNIT}};',
            ],
        ]
    );



    $widget->add_responsive_control(
        $prefix . 'popup_bottom_spacing',
        [
            'label' => esc_html__('Bottom Spacing', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em', 'vh', 'custom'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'condition' => [
                $prefix . 'popup_behavior' => ['right', 'bottom', 'left'],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . '.pe--styled--popup' => '--bottomSpacing: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_control(
        $prefix . 'pop_has_backdrop',
        [
            'label' => esc_html__('Backdrop Filter', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'pop--has--backdrop',
            'prefix_class' => '',
            'default' => '',
        ]
    );


    $widget->add_responsive_control(
        $prefix . 'pop_bg_backdrop_blur',
        [
            'label' => esc_html__('Bluriness', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 10,
            ],
            'condition' => [
                'pop_has_backdrop' => 'pop--has--backdrop',
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . '.pe--styled--popup' => '--backdropBlur: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_control(
        $prefix . 'pop_backdrop_color',
        [
            'label' => esc_html__('Backdrop Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} ' . $selector . '.pe--styled--popup' => 'background-color: {{VALUE}}',
            ],
            'condition' => [
                $prefix . 'pop_has_backdrop' => 'pop--has--backdrop',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . 'pop_close_button',
        [
            'label' => esc_html__('Close Button', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Show', 'pe-core'),
            'label_off' => esc_html__('Hide', 'pe-core'),
            'return_value' => 'show',
            'prefix_class' => 'pop--close--%s-',
            'default' => 'show',
        ]
    );


    $widget->add_control(
        $prefix . 'close_buton_position',
        [
            'label' => esc_html__('Close Button Position', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'inside',
            'options' => [
                'inside' => esc_html__('Inside', 'pe-core'),
                'outside' => esc_html__('Outside', 'pe-core'),
            ],
            'label_block' => false,
            'prefix_class' => 'close--button--',
            'condition' => [
                $prefix . 'pop_close_button' => 'show',
            ],
        ]
    );

    objectAbsolutePositioning($widget, '' . $selector . ' span.pop--close', $prefix . 'close_button', 'Close Button');

    $widget->add_control(
        $prefix . 'close_button_styles_pop',
        [
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'label' => esc_html__('Close Button Styles', 'pe-core'),
            'label_off' => esc_html__('Default', 'pe-core'),
            'label_on' => esc_html__('Custom', 'pe-core'),
            'return_value' => 'popup--colors',
        ]
    );

    $widget->start_popover();

    $widget->add_control(
        $prefix . 'close_button_has_hover',
        [
            'label' => esc_html__('Hover Effect', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'close--button--has--hover',
            'prefix_class' => '',
            'default' => '',
        ]
    );

    $widget->add_control(
        $prefix . 'close_button_has_bg',
        [
            'label' => esc_html__('Background', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'close--button--has--bg',
            'prefix_class' => '',
            'default' => '',
        ]
    );

    $widget->add_control(
        $prefix . 'close_button_background_color',
        [
            'label' => esc_html__('Background Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} span.pop--close' => 'background-color: {{VALUE}}',
            ],
            'condition' => [
                $prefix . 'close_button_has_bg' => 'has--bg',
            ],
        ]
    );

    $widget->add_control(
        $prefix . 'close_button_has_backdrop',
        [
            'label' => esc_html__('Backdrop Filter', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'close--button--has--backdrop',
            'prefix_class' => '',
            'default' => '',
        ]
    );


    $widget->add_responsive_control(
        $prefix . 'close_button_bg_backdrop_blur',
        [
            'label' => esc_html__('Bluriness', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 10,
            ],
            'condition' => [
                $prefix . 'close_button_has_backdrop' => 'has--backdrop',
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' span.pop--close' => '--backdropBlur: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_control(
        $prefix . 'close_button_backdrop_color',
        [
            'label' => esc_html__('Backdrop Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' span.pop--close' => 'background-color: {{VALUE}}',
            ],
            'condition' => [
                $prefix . 'close_button_has_backdrop' => 'has--backdrop',
            ],
        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
        [
            'name' => $prefix . 'close_button_border',
            'selector' => '{{WRAPPER}} ' . $selector . ' span.pop--close',
            'important' => true
        ]
    );


    $widget->add_responsive_control(
        $prefix . 'close_button_has_border_radius',
        [
            'label' => esc_html__('Border Radius', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', 'rem', '%', 'custom'],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' span.pop--close' => '--radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} !important;',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . 'close_button_has_padding',
        [
            'label' => esc_html__('Padding', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', 'rem', '%', 'custom'],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' span.pop--close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . 'close_button_width',
        [
            'label' => esc_html__('Width', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' span.pop--close' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . 'close_button_height',
        [
            'label' => esc_html__('Height', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vh'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' span.pop--close' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]
    );
    $widget->end_popover();


    $widget->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
        [
            'name' => $prefix . 'pop_border',
            'selector' => '{{WRAPPER}} ' . $selector . '.pe--styled--popup',
        ]
    );

    $widget->add_control(
        $prefix . 'popup_colorasds',
        [
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'label' => esc_html__('Popup Colors', 'pe-core'),
            'label_off' => esc_html__('Default', 'pe-core'),
            'label_on' => esc_html__('Custom', 'pe-core'),
            'return_value' => 'popup--colors',
        ]
    );

    $widget->start_popover();

    pe_color_options($widget, '' . $selector . '.pe--styled--popup', $prefix . 'popup_', false);

    $widget->end_popover();


}

function widgetPinningSettings($widget)
{

    $widget->start_controls_section(
        'element_pinning',
        [
            'label' => __('Widget Pinning', 'pe-core'),
        ]
    );

    $widget->add_control(
        'pin_element',
        [
            'label' => esc_html__('Pin Widget', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'prefix_class' => 'widget-pinned_',
            'default' => '',
            'render_type' => 'template',
        ]
    );

    $widget->add_control(
        'element_pin_target',
        [
            'label' => esc_html__('Pin Target', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => esc_html__('Eg: #container2', 'pe-core'),
            'description' => esc_html__('Leave it empty if you want to pin widget to body.', 'pe-core'),

        ]
    );

    $widget->add_control(
        'pin_mobile',
        [
            'label' => esc_html__('Pin Mobile', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
            'render_type' => 'template',
        ]
    );


    $widget->add_control(
        'element_start_references',
        [
            'label' => esc_html__('Start References', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'after',
        ]
    );

    $widget->add_control(
        'element_references_notice',
        [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
	           This references below are adjusts the pinning start/end positions on the screen. <b>For Example: If you select <u>'Top' for item reference point</u> and <u>'Bottom' for the window reference point</u>; pinning will start when item's top edge enters the window's bottom edge.</b></div>",


        ]
    );

    $widget->add_control(
        'element_start_offset',
        [
            'label' => esc_html__('Start Offset', 'pe-core'),
            'description' => esc_html__('An offset value (px) which will be added to pinning start position. Usefull if you are using a fixed,/sticky header.', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => -1000,
            'max' => 1000,
            'step' => 1,
            'default' => 0,
        ]
    );

    $widget->add_control(
        'element_item_ref_start',
        [
            'label' => esc_html__('Item Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'render_type' => 'template',
            'default' => 'center',
            'toggle' => false,
        ]
    );

    $widget->add_control(
        'element_window_ref_start',
        [
            'label' => esc_html__('Window Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'render_type' => 'template',
            'default' => 'center',
            'toggle' => false,
        ]
    );

    $widget->add_control(
        'element_end_references',
        [
            'label' => esc_html__('End References', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'after',
        ]
    );

    $widget->add_control(
        'element_item_ref_end',
        [
            'label' => esc_html__('Item Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'render_type' => 'template',
            'default' => 'bottom',
            'toggle' => false,
        ]
    );

    $widget->add_control(
        'element_window_ref_end',
        [
            'label' => esc_html__('Window Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'render_type' => 'template',
            'default' => 'top',
            'toggle' => false,
        ]
    );

    $widget->end_controls_section();


}

function widgetPinningRender($widget)
{
    $settings = $widget->get_settings_for_display();

    $start = $settings['element_item_ref_start'] . ' ' . $settings['element_window_ref_start'] . '+=' . $settings['element_start_offset'];
    $end = $settings['element_item_ref_end'] . ' ' . $settings['element_window_ref_end'];

    $widget->add_render_attribute(
        'widget_pinning_settings',
        [
            'data-pin-start' => $start,
            'data-pin-end' => $end,
            'data-pin-target' => $settings['element_pin_target'],
            'data-pin-mobile' => $settings['pin_mobile'],
        ]
    );

    $widgetPinning = $settings['pin_element'] === 'true' ? '<div hidden ' . $widget->get_render_attribute_string('widget_pinning_settings') . ' class="widget--pin--sett"></div>' : '';
    return $widgetPinning;

}

function objectAbsolutePositioning($widget, $selector, $prefix, $label, $condition = false)
{


    $widget->add_control(
        $prefix . '_absolute_pos',
        [
            'label' => esc_html__($label . ' Absolute Position', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'pe-core'),
            'label_off' => __('No', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} ' . $selector => 'position: absolute;transform: translate(var(--transformX), var(--transformY))',
            ],
            'condition' => $condition,
        ]
    );

    $widget->add_control(
        $prefix . '_positioning',
        [
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'label' => esc_html__($label . ' Positioning', 'pe-core'),
            'label_off' => esc_html__('Default', 'pe-core'),
            'label_on' => esc_html__('Custom', 'pe-core'),
            'return_value' => 'absolute',
            // 'prefix_class' => $prefix,
            'default' => '',
            'render_type' => 'template',
            'condition' => [
                $prefix . '_absolute_pos' => 'true'
            ],


        ]
    );

    $widget->start_popover();

    $widget->add_responsive_control(
        $prefix . '_vertical_orientation',
        [
            'label' => esc_html__('Vertical Orientation', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => 'eicon-v-align-bottom'
                ],
            ],
            'default' => 'top',
            'toggle' => false,

        ]
    );

    $widget->add_responsive_control(
        $prefix . '_vertical_offset_top',
        [
            'label' => esc_html__('Vertical Offset', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vh'],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 75,
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector => 'top: {{SIZE}}{{UNIT}};bottom: unset;position: absolute',
            ],
            'condition' => [
                $prefix . '_absolute_pos' => 'true',
                $prefix . '_vertical_orientation' => 'top'
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_vertical_offset_bottom',
        [
            'label' => esc_html__('Vertical Offset', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vh'],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 75,
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector => 'bottom: {{SIZE}}{{UNIT}};top: unset;position: absolute',
            ],
            'condition' => [
                $prefix . '_absolute_pos' => 'true',
                $prefix . '_vertical_orientation' => 'bottom'
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_transform_y',
        [
            'label' => esc_html__('Transform Y', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vh'],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 0,
            ],
            'selectors' => [
                $prefix . '_absolute_pos' => 'true',
                '{{WRAPPER}} ' . $selector => '--transformY: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_horizontal_orientation',
        [
            'label' => esc_html__('Horizontal Orientation', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => esc_html__('Left', 'pe-core'),
                    'icon' => 'eicon-h-align-left',
                ],
                'right' => [
                    'title' => esc_html__('Right', 'pe-core'),
                    'icon' => 'eicon-h-align-right'
                ],
            ],
            'default' => is_rtl() ? 'right' : 'left',
            'toggle' => false,
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_horizontal_offset_left',
        [
            'label' => esc_html__('Horizontal Offset', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw'],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 50,
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector => 'left: {{SIZE}}{{UNIT}};right: unset',
            ],
            'condition' => [
                $prefix . '_absolute_pos' => 'true',
                $prefix . '_horizontal_orientation' => 'left'
            ],
        ]
    );


    $widget->add_responsive_control(
        $prefix . '_horizontal_offset_right',
        [
            'label' => esc_html__('Horizontal Offset', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw'],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 50,
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector => 'right: {{SIZE}}{{UNIT}};left: unset;width: auto',
            ],
            'condition' => [
                $prefix . '_absolute_pos' => 'true',
                $prefix . '_horizontal_orientation' => 'right'
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_transform_x',
        [
            'label' => esc_html__('Transform X', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw'],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 0,
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector => '--transformX: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_control(
        $prefix . '_z_index',
        [
            'label' => esc_html__('Z-Index', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => -100,
            'max' => 999,
            'step' => 1,
            'selectors' => [
                '{{WRAPPER}} ' . $selector => 'z-index: {{VALUE}};',
            ],
            'condition' => $condition,
        ]
    );


    $widget->end_popover();

}

function zeynaCompareItemRender($settings, $product, $product_id, $product_link)
{

    $wcAttributes = wc_get_attribute_taxonomies();
    ?>

                <div class="pe-compare-item zeyna--single--product <?php echo 'post-' . $product_id ?>">

                    <div class="pop--behavior--center quick-add-to-cart-popup quick_pop_id-<?php echo $product_id; ?>"
                        data-product-id="<?php echo $product_id; ?>" style="display: none">

                        <span class="pop--overlay"></span>

                        <div class="pe--styled--popup">

                            <span class="pop--close">

                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                                    <path
                                        d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z" />
                                </svg>

                            </span>
                            <div class="zeyna--popup--cart--product">

                                <div class="zeyna--popup--cart-product-image">
                                    <img class="spcp--img" src="">
                                </div>


                                <div class="zeyna--popup--cart-product-meta">


                                    <div class="zeyna--popup--cart-product-cont">
                                        <h6 class="spcp--price"></h6>
                                        <h4 class="spcp--title"></h4>
                                        <p class="spcp--desc no-margin"></p>

                                    </div>

                                    <div class="zeyna--popup--cart-product-form"></div>

                                </div>



                            </div>

                        </div>
                    </div>

                    <div class="pe-compare-image">
                        <img src="<?php echo esc_url(get_the_post_thumbnail_url($product_id, 'medium')); ?>"
                            alt="<?php echo esc_attr($product->get_name()); ?>">
                    </div>

                    <div class="pe-compare-item-meta">
                        <div class="pe-compare-title">
                            <p><?php echo esc_html($product->get_name()); ?></p>
                        </div>
                        <div class="pe-compare-price">
                            <?php echo wp_kses_post($product->get_price_html()); ?>
                        </div>

                    </div>

                    <div class="pe-compare-actions add--to--cart--style--icon">

                        <?php if ($settings['add-to-cart-button'] === 'yes') { ?>

                                        <?php if ($product->is_type('variable') || $product->is_type('grouped')) { ?>
                                                        <div class="zeyna--product-quick-action" data-barba-prevent="all">
                                                            <button class="quick-add-to-cart-btn" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                                                                <span class="card-add-icon">
                                                                    <?php
                                                                    $svgPath = get_template_directory() . '/assets/img/cart-add.svg';
                                                                    $icon = file_get_contents($svgPath);
                                                                    echo $icon; ?>
                                                                </span>

                                                                <svg class="cart-loading" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 -960 960 960"
                                                                    width="1em">
                                                                    <path
                                                                        d="M480-80q-82 0-155-31.5t-127.5-86Q143-252 111.5-325T80-480q0-83 31.5-155.5t86-127Q252-817 325-848.5T480-880q17 0 28.5 11.5T520-840q0 17-11.5 28.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160q133 0 226.5-93.5T800-480q0-17 11.5-28.5T840-520q17 0 28.5 11.5T880-480q0 82-31.5 155t-86 127.5q-54.5 54.5-127 86T480-80Z" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                        <?php } else { ?>



                                                        <div class="zeyna--single--atc">
                                                            <?php
                                                            if ($product->is_type('simple')) {
                                                                woocommerce_simple_add_to_cart();
                                                            } elseif ($product->is_type('grouped')) {
                                                                woocommerce_grouped_add_to_cart();
                                                            } elseif ($product->is_type('external')) {
                                                                woocommerce_external_add_to_cart();
                                                            }
                                                            ?>

                                                        </div>
                                        <?php }

                        }



                        if ($settings['view-product-button'] === 'yes') { ?>

                                        <div class="zeyna--product-quick-action">
                                            <?php
                                            $svgPath = get_template_directory() . '/assets/img/arrow_forward.svg';
                                            $icon = file_get_contents($svgPath);
                                            echo '<a href="' . $product_link . '" class="pe--view--button product--barba--trigger" data-id="' . $product_id . '">
<span>' . $icon . '</span>
</a>';
                                            ?>
                                        </div>
                        <?php } ?>
                    </div>

                    <div class="pe-compare-remove">
                        <a data-product-id="<?php echo $product_id ?>" class="remove-compare">
                            <?php
                            $svgPath = get_template_directory() . '/assets/img/remove.svg';
                            $icon = file_get_contents($svgPath);
                            echo $icon;
                            ?>
                        </a>
                    </div>

                    <div class="pe-compare-item-vars">

                        <?php
                        if ($settings['sku'] === 'yes') {
                            echo '<div class="pe--compare--item--var pe-compare-item-sku"><span class="pe--compare--mobile--label">' . esc_html('SKU', 'pe-core') . '</span>' . esc_html($product->get_sku()) . '</div>';
                        }

                        if ($settings['dimensions'] === 'yes') {
                            echo '<div class="pe--compare--item--var pe-compare-item-dimensions"><span class="pe--compare--mobile--label">' . esc_html('Dimensions', 'pe-core') . '</span>' . esc_html(wc_format_dimensions($product->get_dimensions(false))) . '</div>';
                        }

                        if ($settings['zeyna_weight'] === 'yes') {
                            echo '<div class="pe--compare--item--var pe-compare-item-weight"><span class="pe--compare--mobile--label">' . esc_html('Weight', 'pe-core') . '</span>' . esc_html($product->get_weight()) . '</div>';
                        }

                        if ($settings['stock'] === 'yes') {
                            echo '<div class="pe--compare--item--var pe-compare-item-stock">';
                            echo '<span class="pe--compare--mobile--label">' . esc_html('Stock', 'pe-core') . '</span>';
                            echo esc_html($product->get_stock_status());
                            $stock_quantity = $product->get_stock_quantity();
                            if ($stock_quantity !== null) {
                                echo '<span class="stock-quantity">(' . esc_html($stock_quantity) . ')</span>';
                            }
                            ;
                            echo '</div>';
                        }


                        foreach ($wcAttributes as $key => $attr) {
                            if ($settings[$attr->attribute_name] === 'yes') {
                                $vals = [];
                                $terms = wc_get_product_terms($product_id, 'pa_' . $attr->attribute_name, ['fields' => 'all']);
                                foreach ($terms as $term) {
                                    $vals[] = $term->name;
                                }
                                echo '<div class="pe--compare--item--var pe-compare-item-' . $attr->attribute_name . '"><span class="pe--compare--mobile--label">' . $attr->attribute_label . '</span>' . esc_html(implode(', ', $vals)) . '</div>';
                            }
                        }
                        ?>

                    </div>

                </div>


<?php }

function flexOptions($widget, $condition = false, $selector = '', $prefix = '', $label = '', $popover = true, $itemsSelector = false)
{

    if ($popover) {
        $output = '{{WRAPPER}}.' . $prefix . '_flex--styled ' . $selector;
    } else {
        $output = '{{WRAPPER}} ' . $selector;
    }

    if ($popover) {

        $widget->add_control(
            $prefix . '_flex_options',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => $label . esc_html__(' Flex Options', 'pe-core'),
                'label_off' => esc_html__('Default', 'pe-core'),
                'label_on' => esc_html__('Custom', 'pe-core'),
                'return_value' => '_flex--styled',
                'prefix_class' => $prefix,
                'condition' => $condition,
            ]
        );

        $widget->start_popover();
    } else {
        $widget->add_control(
            $prefix . 'flex_opt_heading',
            [
                'label' => esc_html__($label . ' Flex Options', 'pe-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => $condition,
            ]
        );
    }


    $widget->add_responsive_control(
        $prefix . '_flex_width',
        [
            'label' => esc_html__('Flex Width', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw', 'vh', 'em', 'custom'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                $output => 'width: {{SIZE}}{{UNIT}} !important;',
            ],

        ]
    );

    if ($itemsSelector) {


        $widget->add_responsive_control(
            $prefix . '_flex_items_width',
            [
                'label' => esc_html__('Flex Items Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw', 'vh', 'em', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $itemsSelector => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $itemsSelector => 'min-width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

    }



    $widget->add_responsive_control(
        $prefix . '_flex_direction',
        [
            'label' => esc_html__('Flex Direction', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'row' => [
                    'title' => esc_html__('Row', 'pe-core'),
                    'icon' => ' eicon-h-align-right',
                ],
                'column' => [
                    'title' => esc_html__('Column', 'pe-core'),
                    'icon' => 'eicon-v-align-bottom',
                ],
                'row-reverse' => [
                    'title' => esc_html__('Row-Reverse', 'pe-core'),
                    'icon' => ' eicon-h-align-left',
                ],
                'column-reverse' => [
                    'title' => esc_html__('Column-Reverse', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
            ],
            'selectors' => [
                $output => 'flex-direction: {{VALUE}} !important;',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_justify_content_row',
        [
            'label' => esc_html__('Justify Content', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => esc_html__('Start', 'pe-core'),
                    'icon' => 'eicon-justify-start-h'
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-justify-center-h'
                ],
                'flex-end' => [
                    'title' => esc_html__('End', 'pe-core'),
                    'icon' => 'eicon-justify-end-h'
                ],
                'space-between' => [
                    'title' => esc_html__('Space-Between', 'pe-core'),
                    'icon' => 'eicon-justify-space-between-h'
                ],
            ],
            'selectors' => [
                $output => 'justify-content: {{VALUE}} !important;',
            ],
            'condition' => [
                $prefix . '_flex_direction' => ['row', 'row-reverse'],
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_align_items_row',
        [
            'label' => esc_html__('Align Items', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => esc_html__('Start', 'pe-core'),
                    'icon' => 'eicon-align-start-v'
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-align-center-v'
                ],
                'flex-end' => [
                    'title' => esc_html__('End', 'pe-core'),
                    'icon' => 'eicon-align-end-v'
                ],
                'stretch' => [
                    'title' => esc_html__('Strecth', 'pe-core'),
                    'icon' => 'eicon-align-stretch-v'
                ],
            ],

            'selectors' => [
                $output => 'align-items: {{VALUE}} !important;',
            ],
            'condition' => [
                $prefix . '_flex_direction' => ['row', 'row-reverse'],
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_align_content_row',
        [
            'label' => esc_html__('Align Content', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => esc_html__('Start', 'pe-core'),
                    'icon' => 'eicon-align-start-v'
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-align-center-v'
                ],
                'flex-end' => [
                    'title' => esc_html__('End', 'pe-core'),
                    'icon' => 'eicon-align-end-v'
                ],
                'space-between' => [
                    'title' => esc_html__('Strecth', 'pe-core'),
                    'icon' => 'eicon-align-stretch-v'
                ],
            ],

            'selectors' => [
                $output => 'align-content: {{VALUE}} !important;',
            ],
            'condition' => [
                $prefix . '_flex_direction' => ['row', 'row-reverse'],
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_align_items_column',
        [
            'label' => esc_html__('Align Items', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => esc_html__('Start', 'pe-core'),
                    'icon' => 'eicon-align-start-h'
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-align-center-h'
                ],
                'flex-end' => [
                    'title' => esc_html__('End', 'pe-core'),
                    'icon' => 'eicon-align-end-h'
                ],
                'space-between' => [
                    'title' => esc_html__('Stretch', 'pe-core'),
                    'icon' => 'eicon-align-stretch-h'
                ],
            ],
            'selectors' => [
                $output => 'align-items: {{VALUE}} !important;',
            ],
            'condition' => [
                $prefix . '_flex_direction' => ['column', 'column-reverse'],
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_justify_content_column',
        [
            'label' => esc_html__('Justify Content', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => esc_html__('Start', 'pe-core'),
                    'icon' => 'eicon-justify-start-v'
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-justify-center-v'
                ],
                'flex-end' => [
                    'title' => esc_html__('End', 'pe-core'),
                    'icon' => 'eicon-justify-end-v'
                ],
                'space-between' => [
                    'title' => esc_html__('Space-Between', 'pe-core'),
                    'icon' => 'eicon-justify-center-v'
                ],
            ],
            'selectors' => [
                $output => 'justify-content: {{VALUE}} !important;',
            ],
            'condition' => [
                $prefix . '_flex_direction' => ['column', 'column-reverse'],
            ],
        ]
    );


    $widget->add_responsive_control(
        $prefix . '_columns_gap',
        [
            'label' => esc_html__('Columns Gap', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em', 'custom', 'vw', 'vh', 'rem'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                $output => 'column-gap: {{SIZE}}{{UNIT}} !important;--columnGap: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_rows_gap',
        [
            'label' => esc_html__('Rows Gap', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em', 'custom', 'vw', 'vh', 'rem'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                $output => 'row-gap: {{SIZE}}{{UNIT}} !important;',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_flex_wrap',
        [
            'label' => esc_html__('Flex Wrap', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'nowrap' => [
                    'title' => esc_html__('Nowrap', 'pe-core'),
                    'icon' => 'eicon-nowrap'
                ],
                'wrap' => [
                    'title' => esc_html__('Wrap', 'pe-core'),
                    'icon' => 'eicon-wrap',
                ],
            ],
            'toggle' => true,
            'selectors' => [
                $output => 'flex-wrap: {{VALUE}} !important;',
            ],
        ]
    );


    if ($popover) {
        $widget->end_popover();
    }

}


function zeyna_project_query_selection($widget, $highlights = false, $condition = false)
{

    $projectCats = array();

    $args = array(
        'hide_empty' => true,
        'taxonomy' => 'project-categories'
    );

    $categories = get_categories($args);

    foreach ($categories as $key => $category) {
        $projectCats[$category->term_id] = $category->name;
    }

    $cond = [];

    if ($condition) {
        $cond = $condition;
    }

    if ($widget->get_name() === 'peportfolio') {

        $groupCatsRepeater = new \Elementor\Repeater();

        $groupCatsRepeater->add_control(
            'projects_group_cats_select',
            [
                'label' => __('Categories', 'pe-core'),
                'description' => __('Select categories to be grouped.', 'pe-core'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::SELECT,
                'multiple' => false,
                'options' => $projectCats,

            ]
        );

        $widget->add_control(
            'projects_group_cats',
            [
                'label' => esc_html__('Categories', 'pe-core'),
                'description' => __('Select categories to be grouped.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $groupCatsRepeater->get_controls(),
                'show_label' => false,
                'frontend_available' => true,
                'condition' => [
                    'portfolio_style' => 'grouped',

                ],
            ]
        );


        $widget->add_control(
            'project_selection',
            [
                'label' => esc_html__('Selection', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'all',
                'options' => [
                    'by--cat' => esc_html__('By Category', 'pe-core'),
                    'by--hand' => esc_html__('Manual Select', 'pe-core'),
                    'by--tag' => esc_html__('By Tag', 'pe-core'),
                    'all' => esc_html__('Get All Projects', 'pe-core'),
                ],
                'condition' => ['portfolio_style!' => 'grouped']

            ]
        );


    } else {
        $widget->add_control(
            'project_selection',
            [
                'label' => esc_html__('Selection', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'all',
                'options' => [
                    'by--cat' => esc_html__('By Category', 'pe-core'),
                    'by--hand' => esc_html__('Manual Select', 'pe-core'),
                    'by--tag' => esc_html__('By Tag', 'pe-core'),
                    'all' => esc_html__('Get All Projects', 'pe-core'),
                ],
                'condition' => $condition

            ]
        );
    }

    $widget->add_control(
        'query_id',
        [
            'label' => esc_html__('Query ID', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'ai' => false,
            'placeholder' => esc_html__('Eg: portfolioQuery', 'pe-core'),
            'description' => esc_html__('Required if you will going to use "Portfolio Controls" widget for this query.', 'pe-core'),
            'condition' => $condition
        ]
    );



    $repeaterProjects = [];

    $projects = get_posts([
        'post_type' => 'portfolio',
        'numberposts' => -1
    ]);

    foreach ($projects as $project) {
        $repeaterProjects[$project->ID] = $project->post_title;
    }

    $projectsRepeater = new \Elementor\Repeater();

    $projectsRepeater->add_control(
        'select_project',
        [
            'label' => __('Select Project', 'pe-core'),
            'label_block' => true,
            'description' => __('Select project which will display in the slider.', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $repeaterProjects,
        ]
    );

    $projectsRepeater->add_control(
        'project_name',
        [
            'label' => __('Name', 'pe-core'),
            'description' => __('For editor purposes', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'ai' => false,
        ]
    );

    $widget->add_control(
        'projects_list',
        [
            'label' => esc_html__('Projects', 'pe-core'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $projectsRepeater->get_controls(),
            'show_label' => false,
            'condition' => [
                ...$cond,
                'project_selection' => 'by--hand',
            ],
            'title_field' => '{{{ select_project }}} - {{{ project_name }}}',
        ]
    );



    $widget->add_control(
        'project_query_cats',
        [
            'label' => __('Categories', 'pe-core'),
            'description' => __('Select categories to display projects.', 'pe-core'),
            'label_block' => true,
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $projectCats,
            'condition' => [
                ...$cond,
                'project_selection' => 'by--cat'
            ],

        ]
    );


    $projectTags = array();

    $args = array(
        'hide_empty' => true,
        'taxonomy' => 'post_tag'
    );

    $tags = get_categories($args);

    foreach ($tags as $key => $tag) {
        $projectTags[$tag->term_id] = $tag->name;
    }

    $widget->add_control(
        'project_query_tags',
        [
            'label' => __('Tags', 'pe-core'),
            'description' => __('Select tags to display projects.', 'pe-core'),
            'label_block' => true,
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $projectTags,
            'condition' => [
                ...$cond,
                'project_selection' => 'by--tag',

            ],
        ]
    );

    $widget->add_control(
        'exclude_projects',
        [
            'label' => esc_html__('Exclude Projects', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'options' => $repeaterProjects,
            'multiple' => true,
            'condition' => [
                ...$cond,
                'project_selection' => ['by--cat', 'all'],

            ],
        ]
    );

    $widget->add_control(
        'number_projects',
        [
            'label' => esc_html__('Posts Per View', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 999,
            'step' => 1,
            'render_type' => 'template',
            'default' => 10,
            'condition' => [
                ...$cond,
                'project_selection' => ['by--cat', 'all'],

            ],

        ]
    );

    $widget->add_control(
        'projects_order_by',
        [
            'label' => esc_html__('Order By', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'date',
            'options' => [
                'ID' => esc_html__('ID', 'pe-core'),
                'title' => esc_html__('Title', 'pe-core'),
                'date' => esc_html__('Date', 'pe-core'),
                'author' => esc_html__('Author', 'pe-core'),
                'type' => esc_html__('Type', 'pe-core'),
                'rand' => esc_html__('Random', 'pe-core'),
            ],
            'condition' => [
                ...$cond,
                'project_selection' => ['by--cat', 'all'],

            ],
        ]
    );

    $widget->add_control(
        'projects_order',
        [
            'label' => esc_html__('Order', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'DESC',
            'options' => [
                'ASC' => esc_html__('ASC', 'pe-core'),
                'DESC' => esc_html__('DESC', 'pe-core')

            ],
            'condition' => [
                ...$cond,
                'project_selection' => ['by--cat', 'all'],

            ],

        ]
    );

    $widget->add_control(
        'projects_hover',
        [
            'label' => esc_html__('Projects Hover', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none' => esc_html__('None', 'pe-core'),
                'opacity' => esc_html__('Opacity', 'pe-core'),
                'blur' => esc_html__('Blur', 'pe-core'),
                'grayscale' => esc_html__('Grayscale', 'pe-core'),

            ],
            'condition' => $condition,
            'prefix_class' => 'projects--hover--'

        ]
    );

    if ($highlights) {


        $widget->add_control(
            'highlight_projects',
            [
                'label' => __('Highlight Projects', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core'),
                'label_off' => __('No', 'pe-core'),
                'return_value' => 'yes',
                'default' => 'no',
                'frontend_available' => true,
                'condition' => $condition
            ]
        );

        $widget->add_control(
            'highlight_by',
            [
                'label' => esc_html__('Hightlight By;', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'key',
                'options' => [
                    'key' => esc_html__('Key', 'pe-core'),
                    'project' => esc_html__('Product', 'pe-core'),
                ],
                'condition' => [
                    ...$cond,
                    'highlight_projects' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'highlighted_projects',
            [
                'label' => __('Highlighted Products', 'pe-core'),
                'description' => __('Select products that will be highlighted.', 'pe-core'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $repeaterProjects,
                'condition' => [
                    ...$cond,
                    'highlight_projects' => 'yes',
                    'highlight_by' => 'project',
                ],
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'highlight_keys',
            [
                'label' => esc_html__('Highlight by Index', 'pe-core'),
                'description' => esc_html__('Enter product keys. For example: "2,5" that means 2nd and 5th items will be highlighted.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => false,
                'condition' => [
                    ...$cond,
                    'highlight_projects' => 'yes',
                    'highlight_by' => 'key',
                ],
                'frontend_available' => true,
            ]
        );

    }


}

function zeyna_project_query_args($widget)
{

    $settings = $widget->get_settings_for_display();
    $taxQuery = [];

    if (isset($_GET['filter']) && $_GET['filter'] == true) {
        $taxQuery = [
            'relation' => 'AND',
        ];

        $attributes = wc_get_attribute_taxonomies();

        if (isset($_GET['project_cat']) && $_GET['project_cat'] !== 'all') {

            $taxQuery[] = [
                'taxonomy' => 'project-categories',
                'field' => 'id',
                'terms' => $_GET['project_cat'],
                'operator' => 'AND'
            ];
        }

        if (isset($_GET['post_tag'])) {

            $taxQuery[] = [
                'taxonomy' => 'post_tag',
                'field' => 'id',
                'terms' => $_GET['post_tag'],
                'operator' => 'AND'
            ];
        }

    }

    isset($_GET['offset']) ? $offset = $_GET['offset'] : $offset = 0;

    if ($settings['project_selection'] === 'by--hand' && isset($settings['projects_list'])) {
        $ids = [];
        foreach ($settings['projects_list'] as $key => $product) {
            $ids[] = $product['select_project'];
        }

        $args = array(
            'post_type' => 'portfolio',
            'post_status' => 'publish',
            'posts_per_page' => 99,
            'post__in' => $ids,
            'orderby' => 'post__in',
        );

    } else {

        $excluded = is_array($settings['exclude_projects']) ? $settings['exclude_projects'] : [$settings['exclude_projects']];
        $cats = $settings['project_query_cats'];
        $tags = $settings['project_query_tags'];


        if ($settings['project_selection'] === 'by--cat' && !empty($cats)) {
            $taxQuery[] = [
                'taxonomy' => 'project-categories',
                'field' => 'id',
                'terms' => $cats,
            ];
        }

        if ($settings['project_selection'] === 'by--tag' && !empty($tags)) {
            $taxQuery[] = [
                'taxonomy' => 'post_tag',
                'field' => 'id',
                'terms' => $tags,
            ];
        }

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $args = array(
            'post_type' => 'portfolio',
            'post_status' => 'publish',
            'posts_per_page' => $settings['number_projects'],
            'orderby' => $settings['projects_order_by'],
            'order' => $settings['projects_order'],
            'post__not_in' => $excluded,
            'tax_query' => $taxQuery,
            // 'tax_query' => [
            //     'relation' => 'AND',
            //     [
            //         'taxonomy' => 'project_status',
            //         'field' => 'id',
            //         'terms' => [28],
            //     ],
            //     [
            //         'taxonomy' => 'project-categories',
            //         'field' => 'id',
            //         'terms' => [19 , 20],
            //     ],
            // ],
            'paged' => $paged,
        );

        if (isset($_GET['orderby'])) {
            $orderby = sanitize_text_field($_GET['orderby']);

            switch ($orderby) {
                case 'menu_order':
                    $args['orderby'] = 'menu_order title';
                    $args['order'] = 'ASC';
                    break;
                case 'popularity':
                    $args['meta_key'] = 'total_sales';
                    $args['orderby'] = 'meta_value_num';
                    break;
                case 'rating':
                    $args['meta_key'] = '_wc_average_rating';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                    break;
                case 'date':
                    $args['orderby'] = 'date';
                    $args['order'] = 'DESC';
                    break;
                case 'price':
                    $args['meta_key'] = '_price';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'ASC';
                    break;
                case 'price-desc':
                    $args['meta_key'] = '_price';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                    break;
                default:
                    $args['orderby'] = 'menu_order';
                    $args['order'] = 'ASC';
                    break;
            }
        }

    }


    if (isset($settings['is_related']) && $settings['is_related'] === 'true' && !\Elementor\Plugin::$instance->editor->is_edit_mode()) {

        $project_id = get_the_ID();

        $terms_cat = wp_get_post_terms($project_id, 'project-categories', array('fields' => 'ids'));
        $terms_tag = wp_get_post_terms($project_id, 'tag', array('fields' => 'ids'));

        $args = array(
            'post_type' => 'portfolio',
            'post_status' => 'publish',
            'posts_per_page' => $settings['number_projects'],
            'orderby' => $settings['projects_order_by'],
            'order' => $settings['projects_order'],
            'post__not_in' => array($project_id),
            'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'project-categories',
                    'field' => 'term_id',
                    'terms' => $terms_cat,
                ),
                array(
                    'taxonomy' => 'tag',
                    'field' => 'term_id',
                    'terms' => $terms_tag,
                ),
            ),
        );


    }


    return $args;

}

function pe_project_image($postid, $custom, $hover, $autoplay = 'true' , $widget = false)
{
    $option = get_option('pe-redux');

    $id = $postid;

    if ($custom) {
        $type = $custom['type'];
    } else {
        $type = get_field('media_type', $id);
    }

    if ($type === 'image') {

        $image = $custom ? $custom['imageUrl']['id'] : get_field('image', $id);
        $size = 'full';

        if ($widget && !isset($_POST['request'])) {
            $settings = $widget->get_settings_for_display();
            $size = $settings['project_images_size_size'];
        } else {
            $size = 'medium_large';
        }

        ?>

                                <?php if ($hover === 'bulge') { ?>

                                                <div class="pe--bulge card is-visible" data-img="<?php echo wp_get_attachment_image_url($image, $size); ?>">
                                                    <div class="card__content">
                                                        <canvas class="card__canvas"></canvas>
                                                        <?php echo wp_get_attachment_image($image, $size) ?>
                                                    </div>
                                                </div>

                                <?php } else {
                                    echo wp_get_attachment_image($image, $size);
                                } ?>


                <?php } else if ($type === 'video') {

        $provider = $custom ? $custom['provider'] : get_field('video_provider', $id);
        $video_id = $custom ? $custom['videoId'] : get_field('video_id', $id);
        $self_video = $custom ? $custom['videoUrl'] : get_field('self_video', $id);

        $video_cover = get_field('video_cover', $id);


        ?>

                                                <div class="pe-video pe-<?php echo esc_attr($provider) ?>" data-controls=false
                                                    data-autoplay=<?php echo esc_attr($autoplay) ?> data-muted=true data-loop=true>

                                                    <?php
                                                    if ($video_cover) {

                                                        echo '<div class="pe--video--poster">' . wp_get_attachment_image($video_cover['ID'], 'medium-large') . '</div>';
                                                    }

                                                    ?>

                                    <?php if ($provider === 'self' || $provider === 'stream') {

                                        $url = '';
                                        if ($provider === 'self') {
                                            $url = $self_video['url'];
                                        } else if ($provider === 'stream') {
                                            $url = get_field('stream_url', $id);
                                        }

                                        ?>

                                                                    <video class="p-video" autoplay muted loop playsinline>
                                                                        <source src="<?php echo esc_url($url); ?>">
                                                                    </video>

                                    <?php } else { ?>

                                                                    <div class="p-video" data-plyr-provider="<?php echo esc_attr($provider) ?>"
                                                                        data-plyr-embed-id="<?php echo esc_attr($video_id) ?>"></div>


                                    <?php } ?>

                                                </div>

                <?php }

}

function zeyna_project_styles($widget, $condition = false, $prefix = '', $label = '')
{


    $widget->start_controls_section(
        $label . 'project_styles',
        [
            'label' => __($label . ' Project Styles', 'pe-core'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            // 'conditions' => $condition
        ]
    );


    $widget->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'label' => esc_html__('Title Typography', 'pe-core'),
            'name' => $label . 'project_title_typography',
            'selector' => '{{WRAPPER}} ' . $prefix . ' .project--title',
        ]
    );

    if (!$prefix) {

        $widget->add_control(
            'title_tag',
            [
                'label' => esc_html__('Title Tag', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'h1' => [
                        'title' => esc_html__('H1', 'pe-core'),
                        'icon' => ' eicon-editor-h1',
                    ],
                    'h2' => [
                        'title' => esc_html__('H2', 'pe-core'),
                        'icon' => ' eicon-editor-h2',
                    ],
                    'h3' => [
                        'title' => esc_html__('H3', 'pe-core'),
                        'icon' => ' eicon-editor-h3',
                    ],
                    'h4' => [
                        'title' => esc_html__('H4', 'pe-core'),
                        'icon' => ' eicon-editor-h4',
                    ],
                    'h5' => [
                        'title' => esc_html__('H5', 'pe-core'),
                        'icon' => ' eicon-editor-h5',
                    ],
                    'h6' => [
                        'title' => esc_html__('H6', 'pe-core'),
                        'icon' => ' eicon-editor-h6',
                    ],
                    'p' => [
                        'title' => esc_html__('Paragraph', 'pe-core'),
                        'icon' => ' eicon-editor-paragraph',
                    ]

                ],
                'default' => 'p',
                'toggle' => false,
                'frontend_available' => true,
            ]
        );


    }


    $widget->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'label' => esc_html__('Category Typography', 'pe-core'),
            'name' => $label . 'cats_typography',
            'selector' => '{{WRAPPER}} ' . $prefix . ' .project--cat',
        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'label' => esc_html__('Metas Typography', 'pe-core'),
            'name' => $label . 'metas_typography',
            'selector' => '{{WRAPPER}} ' . $prefix . ' .project--details--wrap p, {{WRAPPER}} ' . $prefix . ' .project--taxonomies--wrap p',
        ]
    );
    if (!$prefix) {
        $widget->add_responsive_control(
            'details_position',
            [
                'label' => esc_html__('Details Position', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'column-reverse' => [
                        'title' => esc_html__('Top', 'pe-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'column' => [
                        'title' => esc_html__('Bottom', 'pe-core'),
                        'icon' => ' eicon-v-align-bottom',
                    ],
                ],
                'default' => 'column',
                'prefix_class' => 'details_pos--',
                'toggle' => false,
                'selectors' => [
                    '{{WRAPPER}} ' . $prefix . ' .portfolio--project--wrapper' => 'flex-direction: {{VALUE}};',
                ],
                'condition' => [
                    'portfolio_style!' => 'list',
                ],
                'frontend_available' => true,

            ]
        );
    }

    flexOptions($widget, ['portfolio_style' => 'list'], ' ' . $prefix . ' .zeyna--portfolio--project.portfolio--project--list a', $label . 'list_project', 'Project');

    $flexCond = false;
    if ($widget->get_name() === 'peportfolio') {
        $flexCond = ['portfolio_style!' => 'list'];
    }

    flexOptions($widget, $flexCond, ' ' . $prefix . ' .portfolio--project--wrapper > a', $label . 'project', 'Project');
    flexOptions($widget, false, ' ' . $prefix . ' .project--metas--wrap', $label . 'box', 'Texst Wrap');
    flexOptions($widget, false, ' ' . $prefix . ' .project--main--wrap', $label . 'title', 'Title');
    flexOptions($widget, false, ' ' . $prefix . ' .project--details--wrap', $label . 'metas', 'Metas', true, '.project--details--wrap > p');
    flexOptions($widget, false, ' ' . $prefix . ' .project--taxonomies--wrap', $label . 'taxonomies', 'Taxonomies');

    $widget->add_responsive_control(
        $label . 'project_border-radius',
        [
            'label' => esc_html__('Border Radius (Box)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em', 'rem', 'vh'],
            'selectors' => [
                '{{WRAPPER}} ' . $prefix . ' .zeyna--portfolio--project' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
        [
            'name' => $label . 'project_box_border',
            'selector' => '{{WRAPPER}} .zeyna--portfolio--project',
        ]
    );

    $widget->add_responsive_control(
        $label . 'project_padding',
        [
            'label' => esc_html__('Padding', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em', 'rem', 'vw'],
            'selectors' => [
                '{{WRAPPER}} ' . $prefix . ' .zeyna--portfolio--project' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        $label . 'project_margin',
        [
            'label' => esc_html__('Margin', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em', 'rem', 'vw'],
            'selectors' => [
                '{{WRAPPER}} ' . $prefix . ' .zeyna--portfolio--project' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        $label . 'image_border-radius',
        [
            'label' => esc_html__('Border Radius (Image)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em', 'rem', 'vh'],
            'selectors' => [
                '{{WRAPPER}} ' . $prefix . ' .project--image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} ' . $prefix . ' .portfolio--list--images--wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        $label . 'details_padding',
        [
            'label' => esc_html__('Padding (Details)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em', 'rem', 'vw'],
            'selectors' => [
                '{{WRAPPER}} ' . $prefix . ' .project--metas--wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );


    $widget->add_responsive_control(
        $label . 'project_image_width',
        [
            'label' => esc_html__('Image Width', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vh', 'em', 'rem', 'custom'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $prefix . ' .zeyna--portfolio--project .project--image' => 'width: {{SIZE}}{{UNIT}};',

            ],
        ]
    );

    $widget->add_responsive_control(
        $label . 'project_image_height',
        [
            'label' => esc_html__('Image Height', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vh', 'em', 'rem', 'custom'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $prefix . ' .showcase--fullscreen--slideshow--thumb.zeyna--portfolio--project .project--image' => 'height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} ' . $prefix . ' .zeyna--portfolio--project a .project--image img' => 'height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} ' . $prefix . ' .zeyna--portfolio--project a .project--image .pe-video' => 'height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} ' . $prefix . ' .zeyna--portfolio--project a .project--image .pe-video .plyr' => 'height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} ' . $prefix . ' .zeyna--portfolio--project.portfolio--project--list a .project--image' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    if ($widget->get_name() === 'peportfolio') {

        $widget->add_responsive_control(
            $label . 'project_highlighted_image_height',
            [
                'label' => esc_html__('Highlighted Image Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'highlight_projects' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $prefix . ' .zeyna--portfolio--project.project--highlighted a .project--image img' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $prefix . ' .zeyna--portfolio--project.project--highlighted a .project--image .pe-video' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

    }



    $widget->add_responsive_control(
        $label . 'project_metas_width',
        [
            'label' => esc_html__('Metas Width', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw', 'em', 'rem', 'custom'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $prefix . ' .project--metas--wrap' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        $label . 'project_metas_height',
        [
            'label' => esc_html__('Metas Height', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vh', 'em', 'rem', 'custom'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $prefix . ' .project--metas--wrap' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    if (!$prefix) {

        $widget->add_control(
            'project_order_items',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__('Order Items', 'pe-core'),
                'label_off' => esc_html__('Default', 'pe-core'),
                'label_on' => esc_html__('Ordered', 'pe-core'),
                'return_value' => 'project--items--ordered',
                'prefix_class' => '',
            ]
        );

        $widget->start_popover();

        $widget->add_control(
            'project_title_order',
            [
                'label' => esc_html__('Title Order', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}}.project--items--ordered .project--main--wrap' => 'order: {{VALUE}};',
                ],

            ]
        );

        $widget->add_control(
            'project_metas_order',
            [
                'label' => esc_html__('Metas Order', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}}.project--items--ordered .project--details--wrap' => 'order: {{VALUE}};',
                ],

            ]
        );

        $widget->add_control(
            'project_taxonomies_order',
            [
                'label' => esc_html__('Taxonomies Order', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}}.project--items--ordered .project--taxonomies--wrap' => 'order: {{VALUE}};',
                ],

            ]
        );

        $widget->add_control(
            'project_image_order',
            [
                'label' => esc_html__('Image Order', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}}.project--items--ordered .project--image' => 'order: {{VALUE}};',
                ],

            ]
        );


        $widget->add_control(
            'project_index_order',
            [
                'label' => esc_html__('Index Order', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}}.project--items--ordered .project--index' => 'order: {{VALUE}};',
                ],
                'condition' => [
                    'show_indexes' => 'indexes--on'
                ]

            ]
        );

        $widget->end_popover();

        // $widget->add_group_control(
        //     \Elementor\Group_Control_Css_Filter::get_type(),
        //     [
        //         'name' => $label . 'css_filters',
        //         'selector' => '{{WRAPPER}} .project--image , {{WRAPPER}} .portfolio--list--images--wrap',
        //     ]
        // );

        $widget->add_control(
            'project_images_overlay',
            [
                'label' => __('Image Overlay', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'true',
                'default' => '',
                'render_type' => 'template',
            ]
        );

        objectStyles($widget, 'project_images', 'Images', '.project--image.pe--styled--object', false, false, false, false, false, true);

        $widget->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'project_images_overlay_color',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} span.project--image--overlay',
                'condition' => [
                    'project_images_overlay' => 'true',
                ]
            ]
        );


        $widget->add_control(
            'project_images_overlay_blend',
            [
                'label' => esc_html__('Overlay Blend', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'normal' => esc_html__('Normal', 'pe-core'),
                    'multiply' => esc_html__('Multiply', 'pe-core'),
                    'color' => esc_html__('Color', 'pe-core'),
                    'color-burn' => esc_html__('Color Burn', 'pe-core'),
                    'darken' => esc_html__('Darken', 'pe-core'),
                    'difference' => esc_html__('Difference', 'pe-core'),
                    'hard-light' => esc_html__('Hard Light', 'pe-core'),
                    'screen' => esc_html__('Screen', 'pe-core'),
                    'overlay' => esc_html__('Overlay', 'pe-core'),
                ],
                'default' => 'normal',
                'condition' => [
                    'project_images_overlay' => 'true',
                ],
                'selectors' => [
                    '{{WRAPPER}} span.project--image--overlay' => 'mix-blend-mode: {{VALUE}}',
                ],
                'label_block' => false,

            ]
        );

    }


    if ($widget->get_name() === 'peportfolio' && !$prefix) {
        $widget->add_control(
            'texts_blend',
            [
                'label' => __('Texts Blend', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'project--texts--blend',
                'default' => '',
                'prefix_class' => '',
                'condition' => [
                    'portfolio_style' => 'list',
                    'portfolio_images_style' => ['hover', 'fullscreen'],
                ],
            ]
        );

    }

    $widget->end_controls_section();
    // objectStyles($widget , 'project_taxonomies_' , 'Taxonomies' , 'p.pe--styled--object.project--taxonomy' , true , ['show_taxonomies' => 'taxonomies--on'] , true , false , false , false);


}


function get_project_meta_fields()
{
    $projectMetas = [];

    $args = array(
        'post_type' => 'portfolio',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        foreach ($query->posts as $post) {
            $repeater = get_post_meta($post->ID, 'project_metas', true); // ACF kullanıyorsan bu yeterli

            if (is_array($repeater)) {
                foreach ($repeater as $row) {
                    if (!empty($row['label'])) {
                        $label = $row['label'];
                        $projectMetas[$label] = $label; // hem key hem value aynı olabilir
                    }
                }
            }
        }
    }

    $projectMetas = array_unique($projectMetas);
    return $projectMetas;

}

function zeyna_project_settings($widget, $condition = false)
{

    $widget->start_controls_section(
        'project_settings',
        [
            'label' => __('Project Settings', 'pe-core'),
            'condition' => $condition
        ]
    );

    $widget->add_control(
        'project_style',
        [
            'label' => esc_html__('Project Style', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => $widget->get_name() === 'peshowcaserounded' ? 'metro' : 'classic',
            'options' => [
                'classic' => esc_html__('Classic', 'pe-core'),
                'metro' => esc_html__('Metro', 'pe-core'),
                'card' => esc_html__('Card', 'pe-core'),
            ],
            'frontend_available' => true,
        ]
    );

    pe_image_hover_settings($widget);

    $widget->add_control(
        'project_images_parallax',
        [
            'label' => __('Parallax Images', 'pe-core '),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'pe-core '),
            'label_off' => __('No', 'pe-core '),
            'return_value' => 'project--images--parallax',
            'prefix_class' => '',
            'default' => '',
            'render_type' => 'template',
            'condition' => [
                // 'project_image_hover' => 'none',
                // 'project_galleries!' => 'project--image--galleries',
            ],
        ]
    );

    $widget->add_control(
        'project_galleries',
        [
            'label' => __('Project Galleries', 'pe-core '),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Show', 'pe-core '),
            'label_off' => __('Hide', 'pe-core '),
            'return_value' => 'project--image--galleries',
            'default' => '',
            'render_type' => 'template',
            'condition' => [
                'image_hover' => 'none',
                'project_images_parallax!' => 'project--images--parallax',
            ],
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'show_cats',
        [
            'label' => __('Show Categories', 'pe-core '),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'pe-core '),
            'label_off' => __('No', 'pe-core '),
            'return_value' => 'cats--on',
            'default' => 'cats--on',
            'frontend_available' => true,
        ]
    );



    $widget->add_control(
        'cats_sperator',
        [
            'label' => esc_html__('Categories Seperator', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => esc_html__('Eg: /', 'pe-core'),
            'ai' => false,
            'description' => esc_html__('Leave it empty if you do not want to display seperator between metas.', 'pe-core'),
            'condition' => [
                'show_cats' => 'cats--on',
            ],
            'frontend_available' => true,
        ]
    );



    $widget->add_control(
        'show_indexes',
        [
            'label' => __('Show Indexes', 'pe-core '),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'pe-core '),
            'label_off' => __('No', 'pe-core '),
            'return_value' => 'indexes--on',
            'default' => 'true',
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'show_metas',
        [
            'label' => __('Show Metas', 'pe-core '),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'pe-core '),
            'label_off' => __('No', 'pe-core '),
            'return_value' => 'metas--on',
            'default' => 'true',
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'show_button',
        [
            'label' => __('Show Button', 'pe-core '),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'pe-core '),
            'label_off' => __('No', 'pe-core '),
            'return_value' => 'button--on',
            'default' => $widget->get_name() === 'peshowcasefullscreenslideshow' ? 'button--on' : '',
            'frontend_available' => true,
        ]
    );


    $metasRepeater = new \Elementor\Repeater();

    $metasRepeater->add_control(
        'select_meta',
        [
            'label' => __('Select Meta', 'pe-core'),
            'label_block' => true,
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => get_project_meta_fields(),
            'frontend_available' => true,
        ]
    );

    $metasRepeater->add_control(
        'show_meta_label',
        [
            'label' => __('Show Label', 'pe-core '),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'pe-core '),
            'label_off' => __('No', 'pe-core '),
            'return_value' => 'true',
            'default' => '',
            'frontend_available' => true,
        ]
    );


    $metasRepeater->add_control(
        'meta_seperator',
        [
            'label' => esc_html__('Seperator', 'pe-core'),
            'default' => esc_html__(':', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'ai' => false,
            'condition' => ['show_meta_label' => 'true'],
        ]
    );


    $widget->add_control(
        'metas_list',
        [
            'label' => esc_html__('Metas', 'pe-core'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $metasRepeater->get_controls(),
            'title_field' => '{{{ select_meta }}}',
            'show_label' => false,
            'condition' => [
                'show_metas' => 'metas--on',
            ],
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'metas_seperator',
        [
            'label' => esc_html__('Metas Seperator', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => esc_html__('Eg: /', 'pe-core'),
            'description' => esc_html__('Leave it empty if you do not want to display seperator between metas.', 'pe-core'),
            'condition' => [
                'show_metas' => 'metas--on',
            ],
            'frontend_available' => true,

        ]
    );


    $widget->add_control(
        'show_acf_fields',
        [
            'label' => __('Show ACF Fields', 'pe-core '),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'pe-core '),
            'label_off' => __('No', 'pe-core '),
            'return_value' => 'acf--fields--on',
            'default' => 'true',
            'frontend_available' => true,
        ]
    );



    $acfFields = [];

    $fieldGroups = acf_get_field_groups([
        'post_type' => 'portfolio'
    ]);

    $excludeFields = [
        'header_behavior',
        'show_footer',
        'page_layout',
        'header_layout',
        'page_title',
        'media_type',
        'image',
        'video_provider',
        'video_id',
        'self_video',
        'hero_style',
        'hero_template',
        'gallery_project',
        'video_project',
        'ph_video_provider',
        'ph_video_id',
        'ph_self_video',
        'image_gallery',
        'video_cover',
        'ph_video_cover',
        'client',
        'excerpt',
        'project_metas',
    ];

    if (!empty($fieldGroups)) {
        foreach ($fieldGroups as $group) {

            $fields = acf_get_fields($group['key']);
            if (!empty($fields)) {
                foreach ($fields as $field) {

                    if (!in_array($field['name'], $excludeFields)) {
                        $acfFields[$field['name']] = $field['label'];
                    }
                }
            }
        }
    }

    $acfFieldsRepeater = new \Elementor\Repeater();

    $acfFieldsRepeater->add_control(
        'select_acf_field',
        [
            'label' => __('Select Field', 'pe-core'),
            'label_block' => true,
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $acfFields,
            'frontend_available' => true,
        ]
    );

    $acfFieldsRepeater->add_control(
        'show_acf_label',
        [
            'label' => __('Show Label', 'pe-core '),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'pe-core '),
            'label_off' => __('No', 'pe-core '),
            'return_value' => 'true',
            'default' => '',
            'frontend_available' => true,
        ]
    );


    $acfFieldsRepeater->add_control(
        'acf_seperator',
        [
            'label' => esc_html__('Seperator', 'pe-core'),
            'default' => esc_html__(':', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'ai' => false,
            'condition' => ['show_acf_label' => 'true'],
        ]
    );


    $widget->add_control(
        'acf_fields_list',
        [
            'label' => esc_html__('Metas', 'pe-core'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $acfFieldsRepeater->get_controls(),
            'title_field' => '{{{ select_acf_field }}}',
            'show_label' => false,
            'condition' => [
                'show_acf_fields' => 'acf--fields--on',
            ],
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'acf_fields_seperator',
        [
            'label' => esc_html__('ACF Fields Seperator', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => esc_html__('Eg: /', 'pe-core'),
            'description' => esc_html__('Leave it empty if you do not want to display seperator between metas.', 'pe-core'),
            'condition' => [
                'show_acf_fields' => 'acf--fields--on',
            ],
            'frontend_available' => true,

        ]
    );

    $widget->add_control(
        'show_taxonomies',
        [
            'label' => __('Show Taxonomies', 'pe-core '),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'pe-core '),
            'label_off' => __('No', 'pe-core '),
            'return_value' => 'taxonomies--on',
            'default' => '',
            'frontend_available' => true,
        ]
    );

    $taxonomiesRepeater = new \Elementor\Repeater();

    $post_type = 'portfolio';
    $taxonomies = get_object_taxonomies($post_type, 'objects');
    $taxonomy_options = [];

    if (!empty($taxonomies) && !is_wp_error($taxonomies)) {
        foreach ($taxonomies as $taxonomy) {
            $taxonomy_options[$taxonomy->name] = $taxonomy->label;
        }
    }


    $taxonomiesRepeater->start_controls_tabs('select_taxonomy_tabs');

    $taxonomiesRepeater->start_controls_tab(
        'select_taxonomy_content',
        [
            'label' => esc_html__('Content', 'pe-core'),
        ]
    );

    $taxonomiesRepeater->add_control(
        'select_taxonomy',
        [
            'label' => __('Select Taxonomy', 'pe-core'),
            'label_block' => true,
            'description' => __('Select a taxonomy for this item.', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $taxonomy_options,
            'frontend_available' => true,
        ]
    );


    $taxonomiesRepeater->end_controls_tab();

    $taxonomiesRepeater->start_controls_tab(
        'select_taxonomy_style',
        [
            'label' => esc_html__('Style', 'pe-core'),
        ]
    );

    flexOptions($taxonomiesRepeater, false, '{{CURRENT_ITEM}}', 'list', 'List', false);
    objectAbsolutePositioning($taxonomiesRepeater, '{{CURRENT_ITEM}}', 'list_pos', 'List', false);
    objectStyles($taxonomiesRepeater, 'list_items', 'Items', '{{CURRENT_ITEM}} .pe--styled--object', true, false, false, false, false);

    $taxonomiesRepeater->end_controls_tab();

    $taxonomiesRepeater->end_controls_tabs();


    $widget->add_control(
        'taxonomies_list',
        [
            'label' => esc_html__('Taxonomies', 'pe-core'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $taxonomiesRepeater->get_controls(),
            'title_field' => '{{{ select_taxonomy }}}',
            'show_label' => false,
            'condition' => [
                'show_taxonomies' => 'taxonomies--on',
            ],
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'link_behavior',
        [
            'label' => esc_html__('Project Link Behavior', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'render_type' => 'template',
            'default' => 'default',
            'options' => [
                'default' => esc_html__('Default', 'pe-core'),
                'open_external' => esc_html__('Use External URL', 'pe-core'),
            ],
            'frontend_available' => true,
        ]
    );


    $widget->end_controls_section();

    pe_button_settings($widget, false, ['show_button' => 'button--on'], 'project__button_', true, 'Project');
    pe_button_style_settings($widget, 'Project Button', 'project__button_', ['show_button' => 'button--on'], true, '');

    $widget->start_controls_section(
        'project_additional_settings',
        [
            'label' => __('Additional Settings', 'pe-core'),
            'condition' => $condition
        ]
    );


    $widget->add_control(
        'title_visibility',
        [
            'label' => esc_html__('Title Visibility', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'render_type' => 'template',
            'default' => '',
            'options' => [
                '' => esc_html__('Visible', 'pe-core'),
                'show--on--hover' => esc_html__('Show on Hover', 'pe-core'),
                'hide--on--hover' => esc_html__('Hide on Hover', 'pe-core'),
            ],
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'category_visibility',
        [
            'label' => esc_html__('Category Visibility', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'render_type' => 'template',
            'default' => '',
            'options' => [
                '' => esc_html__('Visible', 'pe-core'),
                'show--on--hover' => esc_html__('Show on Hover', 'pe-core'),
                'hide--on--hover' => esc_html__('Hide on Hover', 'pe-core'),
            ],
            'frontend_available' => true,
        ]
    );
    $widget->add_control(
        'taxonomies_visibility',
        [
            'label' => esc_html__('Taxonomies Visibility', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'render_type' => 'template',
            'default' => '',
            'options' => [
                '' => esc_html__('Visible', 'pe-core'),
                'show--on--hover' => esc_html__('Show on Hover', 'pe-core'),
                'hide--on--hover' => esc_html__('Hide on Hover', 'pe-core'),
            ],
            'frontend_available' => true,
        ]
    );
    $widget->add_control(
        'metas_visibility',
        [
            'label' => esc_html__('Metas Visibility', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'render_type' => 'template',
            'default' => '',
            'options' => [
                '' => esc_html__('Visible', 'pe-core'),
                'show--on--hover' => esc_html__('Show on Hover', 'pe-core'),
                'hide--on--hover' => esc_html__('Hide on Hover', 'pe-core'),
            ],
            'frontend_available' => true,
        ]
    );

    $widget->add_control(
        'disable_transition',
        [
            'label' => esc_html__('Disable Image Transition', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
             'frontend_available' => true,
        ]
    );

     $widget->add_group_control(
        \Elementor\Group_Control_Image_Size::get_type(),
        [
            'name' => 'project_images_size',
            'exclude' => [],
            'include' => [],
            'default' => 'large',
            'frontend_available' => true,
        ]
    );


    $widget->end_controls_section();

}

function pe_project_title_wrap($widget, $id)
{

    if (isset($_POST['request'])) {
        $settings = $widget;
    } else {
        $settings = $widget->get_settings_for_display();
    }
    $titleTag = $settings['title_tag'] ? $settings['title_tag'] : 'h6';

    ?>

                <div class="project--main--wrap">

                    <?php
                    echo '<' . $titleTag . ' class="project--title ' . $settings['title_visibility'] . '" >' . get_the_title($id) . '</' . $titleTag . '>';
                    ?>


                    <?php if (isset($settings['show_cats']) && $settings['show_cats'] === 'cats--on') { ?>
                                    <p class="project--cat <?php echo $settings['category_visibility'] ?>">
                                        <?php

                                        $terms = get_the_terms($id, 'project-categories');

                                        if ($terms) {
                                            $term_names = array();
                                            foreach ($terms as $key => $term) {
                                                $key++;
                                                echo '<span>' . $term->name . '</span>';
                                                if (!empty($settings['cats_sperator']) && (count(($terms)) !== $key)) {
                                                    echo '<span class="cats--seperator">' . $settings['cats_sperator'] . '</span>';
                                                }
                                            }
                                        }

                                        ?>

                                    </p>
                    <?php } ?>

                </div>
<?php }

function pe_project_details_wrap($widget, $id)
{
    if (isset($_POST['request'])) {
        $settings = $widget;
    } else {
        $settings = $widget->get_settings_for_display();
    }

    if ((isset($settings['show_acf_fields']) && $settings['show_acf_fields'] === 'acf--fields--on') || isset($settings['show_metas']) && $settings['show_metas'] === 'metas--on') { ?>

                                <div class="project--details--wrap <?php echo $settings['metas_visibility'] ?>">
                                    <?php

                                    if ($settings['acf_fields_list']) {


                                        foreach ($settings['acf_fields_list'] as $key => $meta) {
                                            $key++;

                                            if (get_field($meta['select_acf_field'])) {
                                                if ($meta['show_acf_label']) {
                                                    echo '<p class="project--meta meta--' . $meta['select_acf_field'] . '">' . '<span class="field--label">' . get_field_object($meta['select_acf_field'])['label'] . $meta['acf_seperator'] . '</span> '
                                                        . '<span class="field--content">' . get_field($meta['select_acf_field']) . '</span>';
                                                    echo '</p>';
                                                    ;
                                                } else {

                                                    echo '<p class="project--meta meta--' . $meta['select_acf_field'] . '">' . get_field($meta['select_acf_field']);
                                                    echo '</p>';

                                                }

                                                if (!empty($settings['acf_fields_seperator']) && (count(($settings['acf_fields_list'])) !== $key)) {
                                                    echo '<span class="acf--fields--seperator">' . $settings['acf_fields_seperator'] . '</span>';
                                                }

                                            }


                                        }
                                    }


                                    if ($settings['metas_list'] && is_array($settings['metas_list'])) {

                                        foreach ($settings['metas_list'] as $key => $sMeta) {
                                            $key++;

                                            $selectedMeta = $sMeta['select_meta'];

                                            $project_metas = get_field('project_metas', $id);


                                            if ($project_metas) {

                                                foreach ($project_metas as $meta) {
                                                    if (isset($meta['label']) && $meta['label'] === $selectedMeta) {
                                                        if ($sMeta['show_meta_label']) {
                                                            echo '<p class="project--meta meta--' . $meta['label'] . '">
                                                <span class="field--label">' . $meta['label'] . $sMeta['meta_seperator'] . '</span>' .
                                                                ' <span class="field--content">' . $meta['content'] . '</span></p>';
                                                        } else {
                                                            echo '<p class="project--meta meta--' . $meta['label'] . '">' . $meta['content'] . '</p>';
                                                        }
                                                        if (!empty($settings['metas_seperator']) && (count(($settings['metas_list'])) !== $key)) {
                                                            echo '<span class="metas--seperator">' . $settings['metas_seperator'] . '</span>';
                                                        }
                                                        break;
                                                    }
                                                }

                                            }

                                        }

                                    } ?>

                                </div>


                <?php }


    if (isset($settings['show_taxonomies']) && $settings['show_taxonomies'] === 'taxonomies--on') { ?>

                                <div class="project--taxonomies--wrap <?php echo $settings['taxonomies_visibility'] ?>">

                                    <?php foreach ($settings['taxonomies_list'] as $tax) {

                                        if (!empty($tax['select_taxonomy'])) {

                                            $taxonomy = get_taxonomy($tax['select_taxonomy']);

                                            $terms = get_the_terms($id, $tax['select_taxonomy']);
                                            if ($terms && $taxonomy) {

                                                echo '<div class="project--taxonomies--list  elementor-repeater-item-' . $tax['_id'] . ' taxonomy-' . $taxonomy->name . '">';

                                                foreach ($terms as $key => $term) {
                                                    if (!is_a($term, 'WP_Term')) {
                                                        continue;
                                                    }
                                                    echo '<p class="pe--styled--object project--taxonomy taxonomy--' . $term->term_id . '">';
                                                    if (get_field('tag_image', $term)) {
                                                        echo wp_get_attachment_image(get_field('tag_image', $term)['ID'], 'full');
                                                    } else {
                                                        echo $term->name;
                                                    }
                                                    echo '</p>';

                                                }
                                                echo '</div>';
                                            }
                                        }




                                    } ?>


                                </div>
                <?php }

                if ($settings['show_button'] === 'button--on') {

                    $attr = [
                        'href' => get_permalink($id),
                        'class' => 'pb--handle'
                    ];

                    pe_button_render($widget , $attr , false, 'project__button_' , false , false, 'project--button' , true);
                }

}

function zeyna_project_render($widget, $classes, $cursor = '', $type = 'all', $forced_id = null)
{
    if (isset($_POST['request'])) {
        $settings = $widget;
        $animation = '';
    } else {
        $settings = $widget->get_settings_for_display();
        $animation = '';
        if ($widget->get_name() === 'pesingleproject') {
            $animation = pe_general_animation($widget);

        }
        ;
    }
    $id = $forced_id ? $forced_id : get_the_ID();

    $selfPos = '';

    if ((!isset($_POST['request']) && $widget->get_name() === 'peportfolio' && $settings['randomized'] === 'random--items') || (isset($_POST['request']) && $settings['randomized'] === 'random--items')) {
        if ($settings['randomized'] === 'random--items') {

            if ($settings['randomize_range']) {
                $pos = $settings['randomize_range'];
            } else {
                $pos = ['start', 'end', 'center'];
            }

            $rk1 = array_rand($pos);
            $rk2 = array_rand($pos);
            $selfPos = 'style="align-self:' . $pos[$rk1] . ';justify-self:' . $pos[$rk2] . ';"';
        }
    }



    ?>

                <div <?php echo $selfPos . $animation ?>
                    class="zeyna--portfolio--project pe--hover--trigger project--<?php echo $settings['project_style'] ?> portfolio--post--<?php echo $id; ?> <?php echo $classes ?>"
                    data-id="<?php echo $id; ?>">

                    <div class="portfolio--project--wrapper">
                        <?php

                        if ($type === 'all' || $type === 'image') {

                            if ($settings['project_galleries'] == 'project--image--galleries' && get_field('project_gallery', $id)) { ?>
                                                        <div class="project--archive--gallery--nav">

                                                            <div class="pag--prev">
                                                                <?php $svgPath = plugin_dir_path(__FILE__) . '../assets/img/chevron_down.svg';
                                                                $icon = file_get_contents($svgPath);
                                                                echo $icon;
                                                                ?>

                                                            </div>
                                                            <div class="pag--next">
                                                                <?php
                                                                $icon = file_get_contents($svgPath);
                                                                echo $icon;
                                                                ?>

                                                            </div>

                                                        </div>
                                        <?php }


                            $link = get_the_permalink();
                            $target = '';
                            if ($settings['link_behavior'] === 'open_external') {
                                $link = get_field('external_link_url', $id);
                                $target = ' target="_blank"';
                            }

                            $barba = 'barba--trigger';
                            if ($settings['disable_transition']) {
                                $barba = '';
                            }

                            ?>

                                        <a class="<?php echo esc_attr($barba) ?>" href="<?php echo $link ?>" <?php echo $cursor . $target ?>
                                            data-id="<?php echo $id; ?>">

                                            <?php if ($settings['project_galleries'] == 'project--image--galleries' && get_field('project_gallery', $id)) {

                                                $gallery_ids = get_field('project_gallery', $id); ?>

                                                            <div class="project--gallery swiper-container">
                                                                <div class="swiper-wrapper project--gallery--wrapper">
                                                                    <?php
                                                                    foreach ($gallery_ids as $imageId) {

                                                                        echo '<div class="swiper-slide project--archive--gallery--image">';
                                                                        echo wp_get_attachment_image($imageId, 'medium-large');
                                                                        echo '</div>';
                                                                    } ?>
                                                                </div>
                                                            </div>

                                            <?php } ?>

                                            <div <?php echo pe_image_hover($widget) ?>
                                                class="project--image pe--styled--object project__image__<?php echo $id; ?>">
                                                <?php if ($settings['project_images_overlay'] === 'true') {
                                                    echo '<span class="project--image--overlay"></span>';
                                                } ?>

                                                <?php pe_project_image($id, false, false , false , $widget) ?>
                                            </div>

                            <?php } ?>

                            <?php if ($type === 'all' || $type === 'metas') { ?>
                                            <div class="project--metas--wrap">
                                                <?php pe_project_title_wrap($widget, $id) ?>
                                                <?php pe_project_details_wrap($widget, $id) ?>
                                            </div>
                            <?php } ?>
                        </a>
                    </div>


                </div>


                <?php
}

function zeyna_project_list_render($widget, $classes, $cursor = '', $index = false)
{
    if (isset($_POST['request'])) {
        $settings = $widget;
    } else {
        $settings = $widget->get_settings_for_display();
    }

    $id = get_the_ID();
    $barba = 'barba--trigger';
    if ($settings['disable_transition']) {
        $barba = '';
    }

    ?>

                <div class="zeyna--portfolio--project pe--hover--trigger portfolio--project--list project--<?php echo $settings['project_style'] ?> portfolio--post--<?php echo $id; ?>"
                    data-id="<?php echo $id; ?>">

                    <div class="portfolio--project--wrapper">

                        <a class="<?php echo esc_attr($barba) ?>" href="<?php echo get_the_permalink() ?>" <?php echo $cursor ?>
                            data-id="<?php echo $id; ?>">

                            <?php if ($index && $settings['show_indexes'] === 'indexes--on') {
                                echo '<span class="project--index">' . sprintf("%02d", $index) . '</span>';
                            } ?>

                            <?php pe_project_title_wrap($widget, $id) ?>

                            <?php pe_project_details_wrap($widget, $id) ?>

                            <?php if ($settings['project_galleries'] == 'project--image--galleries' && get_field('project_gallery', $id)) {

                                $gallery_ids = get_field('project_gallery', $id); ?>

                                            <div class="project--gallery swiper-container">

                                                <div class="project--archive--gallery--nav">

                                                    <div class="pag--prev">
                                                        <?php $svgPath = plugin_dir_path(__FILE__) . '../assets/img/chevron_down.svg';
                                                        $icon = file_get_contents($svgPath);
                                                        echo $icon;
                                                        ?>

                                                    </div>
                                                    <div class="pag--next">
                                                        <?php
                                                        $icon = file_get_contents($svgPath);
                                                        echo $icon;
                                                        ?>

                                                    </div>

                                                </div>

                                                <div class="swiper-wrapper project--gallery--wrapper">

                                                    <?php
                                                    foreach ($gallery_ids as $imageId) {
                                                        echo '<div class="swiper-slide project--archive--gallery--image">';
                                                        echo wp_get_attachment_image($imageId, 'medium-large');
                                                        echo '</div>';
                                                    } ?>
                                                </div>
                                            </div>

                            <?php } ?>

                            <div <?php echo pe_image_hover($widget) ?> class="project--image project__image__<?php echo $id; ?>">
                                <?php if ($settings['project_images_overlay'] === 'true') {
                                    echo '<span class="project--image--overlay"></span>';
                                } ?>
                                <?php pe_project_image($id, false, false) ?>
                                >
                            </div>

                        </a>
                    </div>

                </div>

                <?php
}



function pe_portfolio_filters($settings, $loop)
{ ?>

                <div class="pe--portfolio--filters <?php echo 'filters--' . esc_attr($settings['filters_style']) ?>">

                    <?php $filters = $settings['portfolio_filters'];

                    foreach ($filters as $filter) {


                        $taxonomy = $filter['select_taxonomy'];
                        $selectTaxonomies = $filter['select_' . $taxonomy];

                        $terms = get_terms([
                            'taxonomy' => $taxonomy,
                            'hide_empty' => true,
                            'include' => !empty($selectTaxonomies) ? $selectTaxonomies : 'all',
                        ]);


                        if (!is_wp_error($terms) && !empty($terms)) {

                            echo '<div class="portfolio--filter--group  elementor-repeater-item-' . $filter['_id'] . ' filters--style--' . $filter['filter_style'] . '">';
                            echo $filter['show_label'] ? '<p class="taxonomy--label">' . esc_html(get_taxonomy($taxonomy)->label) . '</p>' : '';
                            echo '<ul class="term--list term-list-' . esc_attr($taxonomy) . '">';

                            if ($filter['show_all_button'] === 'true') {
                                $count = $filter['show_counts'] === 'true' ? '<span class="term--count">(' . $loop->found_posts . ')</span>' : '';

                                echo '<li class="term-item pe--styled--object" data-term-id="all" data-taxonomy="' . esc_attr($taxonomy) . '">';
                                echo '<span class="term--item--inner">' . esc_html($filter['show_all_text']) . '</span>' . $count;
                                echo '</li>';

                            }

                            foreach ($terms as $term) {

                                $count = $filter['show_counts'] === 'true' ? '<span class="term--count">(' . $term->count . ')</span>' : '';


                                echo '<li class="term-item pe--styled--object" data-term-id="' . esc_attr($term->term_id) . '" data-taxonomy="' . esc_attr($taxonomy) . '">';
                                echo '<span class="term--item--inner">' . esc_html($term->name) . '</span>' . $count;
                                echo '</li>';
                            }

                            echo '</ul>';
                            echo '</div>';
                        }

                    }

                    if ($settings['clear_filters'] === 'yes') {

                        $icon = file_get_contents(get_template_directory() . '/assets/img/remove.svg');


                        echo '<span class="portfolio--clear--filters"><span>' . $icon . '</span>' . $settings['clear_filters_button_text'] . '</span>';

                    }

                    ?>

                </div>


<?php }


function pe_loader_transition_animations($widget)
{

    $widget->start_controls_section(
        'loader_transition_animations',
        [
            'label' => __('Loader/Transition Animations', 'pe-core'),
            'tab' => $widget->get_name() === 'container' ? \Elementor\Controls_Manager::TAB_LAYOUT : false
        ]
    );


    $widget->add_control(
        'intro_animation',
        [
            'label' => esc_html__('Intro Animation', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'render_type' => 'template',
            'prefix_class' => '',
            'default' => '',
            'options' => [
                '' => esc_html__('None', 'pe-core'),
                'intro--fade fade_in' => esc_html__('Fade In', 'pe-core'),
                'intro--fade fade_up' => esc_html__('Fade Up', 'pe-core'),
                'intro--fade fade_down' => esc_html__('Fade Down', 'pe-core'),
                'intro--fade fade_left' => esc_html__('Fade Left', 'pe-core'),
                'intro--fade fade_right' => esc_html__('Fade Right', 'pe-core'),
                'intro--slide slide_up' => esc_html__('Slide Up', 'pe-core'),
                'intro--slide slide_down' => esc_html__('Slide Down', 'pe-core'),
                'intro--slide slide_right' => esc_html__('Slide Right', 'pe-core'),
                'intro--slide slide_left' => esc_html__('Slide Left', 'pe-core'),
                'intro--block block_up' => esc_html__('Block Up', 'pe-core'),
                'intro--block block_down' => esc_html__('Block Down', 'pe-core'),
            ],
        ]
    );

    $widget->add_control(
        'out_animation',
        [
            'label' => esc_html__('Out Animation', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'render_type' => 'template',
            'prefix_class' => 'out--',
            'default' => 'fade fade_out_out',
            'options' => [
                'fade fade_out_out' => esc_html__('Fade Out', 'pe-core'),
                'fade fade_out_up' => esc_html__('Fade Up', 'pe-core'),
                'fade fade_out_down' => esc_html__('Fade Down ', 'pe-core'),
                'fade fade_out_left' => esc_html__('Fade Left ', 'pe-core'),
                'fade fade_out_right' => esc_html__('Fade Right', 'pe-core'),
                'slide slide_out_up' => esc_html__('Slide Up', 'pe-core'),
                'slide slide_out_down' => esc_html__('Slide Down', 'pe-core'),
                'slide slide_out_right' => esc_html__('Slide Right', 'pe-core'),
                'slide slide_out_left' => esc_html__('Slide Left', 'pe-core'),
                'block block_out_up' => esc_html__('Block Up', 'pe-core'),
                'block block_out_down' => esc_html__('Block Down', 'pe-core'),
            ],
            'condition' => [
                'intro_animation!' => ''
            ]
        ]
    );

    $widget->end_controls_section();


}

function zeynaLighboxOptions($widget, $prefix = '', $condition = false, $single = false)
{

    $widget->start_controls_section(
        $prefix . '_lightbox_options',
        [
            'label' => __('Lightbox Options', 'pe-core'),
            'condition' => $condition,
        ]
    );

    if (!$single) {
        $widget->add_control(
            $prefix . '_lightbox_style',
            [
                'label' => esc_html__('Lightbox Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'render_type' => 'template',
                'default' => 'slideshow',
                'options' => [
                    'carousel' => esc_html__('Carousel', 'pe-core'),
                    'slideshow' => esc_html__('Slideshow', 'pe-core'),
                ],
            ]
        );

        $widget->add_control(
            $prefix . '_navigation_buttons',
            [
                'label' => esc_html__('Navigation Buttons', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => 'true',
                'default' => 'true',
            ]
        );

        $widget->add_control(
            $prefix . '_navigation_buttons_style',
            [
                'label' => esc_html__('Buttons Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'render_type' => 'template',
                'default' => 'icons',
                'options' => [
                    'icons' => esc_html__('Icons', 'pe-core'),
                    'texts' => esc_html__('Texts', 'pe-core'),
                ],
                'condition' => [
                    $prefix . '_navigation_buttons' => 'true',
                ],
            ]
        );

        $widget->add_control(
            $prefix . '_navigation_use_custom_icons',
            [
                'label' => esc_html__('Use Custom Icons', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    $prefix . '_navigation_buttons_style' => 'icons',
                    $prefix . '_navigation_buttons' => 'true',


                ],
            ]
        );

        $widget->add_control(
            $prefix . '_navigation_prev_icon',
            [
                'label' => esc_html__('Prev Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'material-icons md-arrow_backward',
                    'library' => 'material-design-icons',
                ],
                'condition' => [
                    $prefix . '_navigation_use_custom_icons' => 'yes',
                    $prefix . '_navigation_buttons_style' => 'icons',


                ],
            ]
        );

        $widget->add_control(
            $prefix . '_navigation_next_icon',
            [
                'label' => esc_html__('Next Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'material-icons md-arrow_forward',
                    'library' => 'material-design-icons',
                ],
                'condition' => [
                    $prefix . '_navigation_use_custom_icons' => 'yes',
                    $prefix . '_navigation_buttons_style' => 'icons',

                ],
            ]
        );

        $widget->add_control(
            $prefix . '_navigation_prev_text',
            [
                'label' => esc_html__('Prev Button Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('PREV', 'pe-core'),
                'ai' => false,
                'condition' => [
                    $prefix . '_navigation_buttons_style' => 'texts',

                ],
            ]
        );

        $widget->add_control(
            $prefix . '_navigation_next_text',
            [
                'label' => esc_html__('Next Button Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('NEXT', 'pe-core'),
                'ai' => false,
                'condition' => [
                    $prefix . '_navigation_buttons_style' => 'texts',
                ],
            ]
        );

        $widget->add_control(
            $prefix . '_thumbnails',
            [
                'label' => esc_html__('Thumbnails', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => 'true',
                'default' => 'true',
            ]
        );

        $widget->add_control(
            $prefix . '_mousewheel_nav',
            [
                'label' => esc_html__('Mousewheel Navigation', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => 'true',
                'default' => 'true',
            ]
        );

        $widget->add_control(
            $prefix . '_overlay',
            [
                'label' => esc_html__('Overlay', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('Nos', 'pe-core'),
                'return_value' => 'true',
                'default' => 'true',
            ]
        );

        $widget->add_control(
            $prefix . '_overlay_style',
            [
                'label' => esc_html__('Overlay Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'render_type' => 'template',
                'default' => 'fade',
                'options' => [
                    'fade' => esc_html__('Fade', 'pe-core'),
                    'slide' => esc_html__('Slide', 'pe-core'),
                ],
                'condition' => [
                    $prefix . '_overlay' => 'true',
                ],
            ]
        );

        $widget->add_control(
            $prefix . '_overlay_color',
            [
                'label' => esc_html__('Overlay Color', 'pe-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .zeyna--lightbox--overlay' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    $prefix . '_overlay' => 'true',
                ],
            ]
        );


        $widget->add_control(
            $prefix . '_overlay_backdrop',
            [
                'label' => esc_html__('Overlay Bacdrop Blur', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('Nos', 'pe-core'),
                'return_value' => 'overlay--backdrop',
                'default' => '',
            ]
        );

        $widget->add_responsive_control(
            $prefix . '_overlay_backdrop_blur',
            [
                'label' => esc_html__('Bluriness', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'condition' => [
                    $prefix . '_overlay' => 'true',
                    $prefix . '_overlay_backdrop' => 'overlay--backdrop',
                ],
                'selectors' => [
                    '{{WRAPPER}} .zeyna--lightbox--overlay' => '--blur: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

    }


    $widget->end_controls_section();

}

function zeynaLighboxStyles($widget, $prefix = '', $condition = false, $single = false)
{
    $widget->start_controls_section(
        $prefix . '_lightbox_styles',
        [

            'label' => esc_html__('Lightbox Styles', 'pe-core'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => $condition,
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_lightbox_height',
        [
            'label' => esc_html__('Lightbox Height', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['em', 'rem', 'px', '%', 'vh', 'custom'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .lightbox--active>div:not(.zeyna--lightbox--controls)' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_lightbox_top_pos',
        [
            'label' => esc_html__('Lightbox Top Position', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['em', 'rem', 'px', '%', 'vh', 'custom'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .lightbox--active>div:not(.zeyna--lightbox--controls)' => 'top: {{SIZE}}{{UNIT}};',
            ],
        ]
    );


    $widget->add_responsive_control(
        $prefix . '_lightbox_left_pos',
        [
            'label' => esc_html__('Lightbox Top Position', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['em', 'rem', 'px', '%', 'vh', 'custom'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .lightbox--active>div:not(.zeyna--lightbox--controls)' => 'top: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                $prefix . '_lightbox_style' => 'slideshow',
            ],
        ]
    );

    objectStyles($widget, 'lightbox_controls', 'Controls', '.zeyna--lightbox--controls .pe--styled--object', true, false, false, false, false);


    if (!$single) {


        $widget->add_responsive_control(
            $prefix . '_thumbnails_width',
            [
                'label' => esc_html__('Thumbnails Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['em', 'rem', 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .zeyna--lightbox--thumbs>div' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    $prefix . '_thumbnails' => 'true',
                ],
            ]
        );

        $widget->add_responsive_control(
            $prefix . '_thumbnails_height',
            [
                'label' => esc_html__('Thumbnails Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['em', 'rem', 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .zeyna--lightbox--thumbs>div' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    $prefix . '_thumbnails' => 'true',
                ],
            ]
        );

        $widget->add_responsive_control(
            $prefix . '_thumbs_border-radius',
            [
                'label' => esc_html__('Thumbnails Radius', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} ..zeyna--lightbox--thumbs>div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    $prefix . '_thumbnails' => 'true',
                ],
            ]
        );

        $cond = [$prefix . '_thumbnails' => 'true'];
        objectAbsolutePositioning($widget, '.zeyna--lightbox--thumbs', 'thumbs_pos_', 'Thumbnails', $cond);
        flexOptions($widget, $cond, '.zeyna--lightbox--thumbs', 'thumbs', 'Thumbnails');

    }



    $widget->end_controls_section();
}


function zeynaLightBoxRender($widget, $prefix = '', $single = false, $images = false)
{

    $settings = $widget->get_settings_for_display();
    $style = $settings[$prefix . '_lightbox_style'];
    $navButtons = $settings[$prefix . '_navigation_buttons'];
    $thumbnails = $settings[$prefix . '_thumbnails'];
    $mouseWheel = $settings[$prefix . '_mousewheel_nav'];
    ?>

                <div class="lightbox--sett" data-mousewheel="<?php echo esc_attr($mouseWheel) ?>"
                    data-style="<?php echo esc_attr($style) ?>"></div>

                <div class="zeyna--lightbox--controls">

                    <span class="zeyna--lightbox--close pe--styled--object">
                        <?php echo file_get_contents(plugin_dir_url(__FILE__) . '../assets/img/close.svg'); ?></span>

                    <?php if ($navButtons === 'true') {
                        if ($settings[$prefix . '_navigation_buttons_style'] === 'icons') {
                            if ($settings[$prefix . '_navigation_use_custom_icons'] === 'yes') {

                                ob_start();
                                \Elementor\Icons_Manager::render_icon($settings[$prefix . '_navigation_prev_icon'], ['aria-hidden' => 'true']);
                                $prevButton = ob_get_clean();

                                ob_start();
                                \Elementor\Icons_Manager::render_icon($settings[$prefix . '_navigation_next_icon'], ['aria-hidden' => 'true']);
                                $nextButton = ob_get_clean();

                            } else {
                                $prevButton = file_get_contents(plugin_dir_url(__FILE__) . '../assets/img/arrow_back.svg');
                                $nextButton = file_get_contents(plugin_dir_url(__FILE__) . '../assets/img/arrow_forward.svg');
                            }

                        } else {
                            $prevButton = $settings[$prefix . '_navigation_prev_text'];
                            $nextButton = $settings[$prefix . '_navigation_next_text'];
                        }
                        ;

                        ?>
                                    <span class="zeyna--lightbox--prev zeyna--lightbox--nav pe--styled--object">
                                        <?php echo $prevButton ?></span>
                                    <span class="zeyna--lightbox--next zeyna--lightbox--nav pe--styled--object">
                                        <?php echo $nextButton; ?></span>
                    <?php } ?>

                    <?php if (($settings[$prefix . '_thumbnails'] === 'true') && (is_array($images))) { ?>

                                    <div class="zeyna--lightbox--thumbs">

                                        <?php foreach ($images as $key => $image) { ?>
                                                        <div class="zeyna--lightbox--thumb lightbox--thumb_<?php echo $key ?>" data-index="<?php echo $key ?>">
                                                            <?php echo wp_get_attachment_image($image['id'], 'thumbnail') ?>
                                                        </div>
                                        <?php } ?>
                                    </div>
                    <?php }

                    if (($settings[$prefix . '_overlay'] === 'true')) { ?>

                                    <span
                                        class="zeyna--lightbox--overlay <?php echo $settings[$prefix . '_overlay_style'] . ' ' . $settings[$prefix . '_overlay_backdrop'] ?>"></span>

                    <?php }
                    ?>

                </div>

<?php }


function image_overlay_opt($widget, $selector, $prefix, $condition)
{

    $widget->add_control(
        $prefix . '_cont_background_overlay',
        [
            'label' => esc_html__('Image Overlay', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => 'false',
            'render_type' => 'template',
            'condition' => $condition,
            'selectors' => [
                '{{WRAPPER}} ' . $selector . '::before' => 'content: "";position: absolute;display: block;width: 100%;height:100%;top:0;left: 0',
            ],

        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Background::get_type(),
        [
            'name' => $prefix . '_bg_overlay_background',
            'types' => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} ' . $selector . '::before',
            'condition' => [
                $prefix . '_cont_background_overlay' => 'true',
            ]
        ]
    );

    $widget->add_control(
        $prefix . '_bg_overlay_blend_mode',
        [
            'label' => esc_html__('Overlay Blend', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'normal' => esc_html__('Normal', 'pe-core'),
                'multiply' => esc_html__('Multiply', 'pe-core'),
                'color' => esc_html__('Color', 'pe-core'),
                'color-burn' => esc_html__('Color Burn', 'pe-core'),
                'darken' => esc_html__('Darken', 'pe-core'),
                'difference' => esc_html__('Difference', 'pe-core'),
                'hard-light' => esc_html__('Hard Light', 'pe-core'),
                'screen' => esc_html__('Screen', 'pe-core'),
                'overlay' => esc_html__('Overlay', 'pe-core'),
            ],
            'default' => 'normal',
            'condition' => [
                $prefix . '_cont_background_overlay' => 'true',
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . '::before' => 'mix-blend-mode: {{VALUE}}',
            ],
            'label_block' => false
        ]
    );

}


function get_grouped_page_options()
{
    $groups = [];

    // Pages
    $pages = get_pages();
    $page_options = [];
    foreach ($pages as $page) {
        $page_options[$page->ID] = $page->post_title;
    }
    if (!empty($page_options)) {
        $groups[] = [
            'label' => esc_html__('Pages', 'pe-core'),
            'options' => $page_options,
        ];
    }

    // Posts
    $posts = get_posts([
        'post_type' => 'post',
        'numberposts' => -1
    ]);
    $post_options = [];
    foreach ($posts as $post) {
        $post_options[$post->ID] = $post->post_title;
    }
    if (!empty($post_options)) {
        $groups[] = [
            'label' => esc_html__('Posts', 'pe-core'),
            'options' => $post_options,
        ];
    }

    // Custom Post Types (exclude 'post' and 'page')
    $custom_post_types = get_post_types(['public' => true, '_builtin' => false], 'objects');
    foreach ($custom_post_types as $cpt) {
        $items = get_posts(['post_type' => $cpt->name, 'numberposts' => -1]);
        $options = [];
        foreach ($items as $item) {
            $options[$item->ID] = $item->post_title;
        }
        if (!empty($options)) {
            $groups[] = [
                'label' => esc_html($cpt->labels->name, 'pe-core'),
                'options' => $options,
            ];
        }
    }

    // Taxonomies
    $taxonomies = get_taxonomies(['public' => true, '_builtin' => false], 'objects');
    foreach ($taxonomies as $taxonomy) {
        $terms = get_terms(['taxonomy' => $taxonomy->name, 'hide_empty' => false]);
        $options = [];
        foreach ($terms as $term) {
            $options['term_' . $term->term_id] = $term->name;
        }
        if (!empty($options)) {
            $groups[] = [
                'label' => esc_html($taxonomy->labels->name, 'pe-core'),
                'options' => $options,
            ];
        }
    }

    array_unshift($groups, [
        'label' => '',
        'options' => ['' => esc_html__('— None —', 'pe-core')],
    ]);

    return $groups;
}

function pe_icon_render($widget, $icon, $prefix = '', $hover = '')
{

    $trigger = '';
    if ($widget) {
        $settings = $widget->get_settings_for_display();
        $hover = $settings[$prefix . 'icon_hover'];

        if ($widget->get_name() === 'pecalltoaction') {
            $trigger = 'has--trigger';
        }
    }

    if (is_array($icon)) {
        ob_start();
        \Elementor\Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']);
        $icon = ob_get_clean();
    }

    if ($hover && ($hover !== 'none' || $hover !== 'rotate')) {
        $iconHTML = '<span data-icon-hover="' . $hover . '" class="pe--icon--hold ' . $trigger . '">
            <span class="button--icon--main">' . $icon . '</span>
            <span class="button--icon--hover">' . $icon . '</span>
            </span>';
    } else {
        $iconHTML = '<span class="pe--icon--hold">
            ' . $icon . '
            </span>';
    }
    return $iconHTML;

}


function pe_text_hover($settings, $text = '', $prefix = '')
{
    $hover = $settings[$prefix . 'text_hover'];

    if ($hover === 'none') {
        return $text;
    }

    $textHoverAttriutes = [
        'data-text-hover' => $hover,
        'data-text' => $text
    ];

    $attributesString = '';
    foreach ($textHoverAttriutes as $key => $value) {
        if (!empty($value)) {
            $attributesString .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }
    }

    ob_start(); ?>
                <span <?php echo $attributesString ?> class="text-hover">
                    <span><?php echo esc_html($text) ?></span>
                    <?php if (str_starts_with($hover, "chars-")) { ?>
                                    <span class="text--chars--wrap"><?php echo esc_html($text) ?></span>
                    <?php } ?>
                </span>
                <?php $render = ob_get_clean();

                return $render;

}

function pe_text_hover_settings($widget, $prefix = '', $condition = false)
{

    $widget->add_control(
        $prefix . 'text_hover',
        [
            'label' => esc_html__('Hover Effect', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none' => esc_html__('None', 'pe-core'),
                'slide-up' => esc_html__('Slide Up', 'pe-core'),
                'slide-down' => esc_html__('Slide Down', 'pe-core'),
                'slide-left' => esc_html__('Slide Left', 'pe-core'),
                'slide-right' => esc_html__('Slide Right', 'pe-core'),
                'chars-up' => esc_html__('Chars Up', 'pe-core'),
                'chars-down' => esc_html__('Chars Down', 'pe-core'),
                'chars-left' => esc_html__('Chars Left', 'pe-core'),
                'chars-right' => esc_html__('Chars Right', 'pe-core'),
            ],
            'condition' => $condition
        ]
    );

}

function pe_icon_hover_settings($widget, $prefix = '', $condition = false)
{


    $widget->add_control(
        $prefix . 'icon_hover',
        [
            'label' => esc_html__('Icon Hover Effect', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none' => esc_html__('None', 'pe-core'),
                'slide-up' => esc_html__('Slide Up', 'pe-core'),
                'slide-down' => esc_html__('Slide Down', 'pe-core'),
                'slide-left' => esc_html__('Slide Left', 'pe-core'),
                'slide-right' => esc_html__('Slide Right', 'pe-core'),
                'slide-up-right' => esc_html__('Slide Up-Right', 'pe-core'),
                'slide-up-left' => esc_html__('Slide Up-Left', 'pe-core'),
                'slide-down-right' => esc_html__('Slide Down-Right', 'pe-core'),
                'slide-down-left' => esc_html__('Slide Down-Left', 'pe-core'),
                'rotate' => esc_html__('Rotate', 'pe-core'),
            ],
            'condition' => $condition
        ]
    );

    $widget->add_control(
        $prefix . 'icon_hover_rotate_deg',
        [
            'label' => esc_html__('Rotate Deg', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['deg'],
            'range' => [
                'deg' => [
                    'min' => -360,
                    'max' => 360,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'deg',
                'size' => 90,
            ],
            'condition' => [
                $prefix . 'icon_hover' => 'rotate'
            ],
            'selectors' => [
                '{{WRAPPER}} .pe--icon--hover' => '--rotateDeg: {{SIZE}}deg;',
            ],
        ]
    );
}

function pe_icon_hover($settings, $icon = '', $prefix = '')
{
    $hover = $settings[$prefix . 'icon_hover'];

    if ($hover === 'none') {
        return $icon;
    }

    $textHoverAttriutes = [
        'data-icon-hover' => $hover,
    ];

    $attributesString = '';
    foreach ($textHoverAttriutes as $key => $value) {
        if (!empty($value)) {
            $attributesString .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }
    }

    ob_start(); ?>
                <span <?php echo $attributesString ?> class="pe--icon--hover">
                    <span><?php echo $icon ?></span>

                    <?php if ($hover !== 'rotate') { ?>
                                    <span class="icon--hover--wrap"><?php echo $icon ?></span>
                    <?php } ?>
                </span>
                <?php $render = ob_get_clean();

                return $render;

}

function pe_hover_effects($widget, $type = '', $prefix = '', $selector = '', $condition = false)
{

    if ($type === 'icon') {

        $widget->add_control(
            $prefix . 'icon_hover',
            [
                'label' => esc_html__('Hover Effect', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'slide-up' => esc_html__('Slide Up', 'pe-core'),
                    'slide-down' => esc_html__('Slide Down', 'pe-core'),
                    'slide-left' => esc_html__('Slide Left', 'pe-core'),
                    'slide-right' => esc_html__('Slide Right', 'pe-core'),
                    'slide-up-right' => esc_html__('Slide Up-Right', 'pe-core'),
                    'slide-up-left' => esc_html__('Slide Up-Left', 'pe-core'),
                    'slide-down-right' => esc_html__('Slide Down-Right', 'pe-core'),
                    'slide-down-left' => esc_html__('Slide Down-Left', 'pe-core'),
                    'rotate' => esc_html__('Rotate', 'pe-core'),
                ],
                'condition' => $condition
            ]
        );

        $widget->add_control(
            $prefix . 'icon_hover_rotate_deg',
            [
                'label' => esc_html__('Rotate Deg', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'range' => [
                    'deg' => [
                        'min' => -360,
                        'max' => 360,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'deg',
                    'size' => 90,
                ],
                'condition' => [
                    $prefix . 'icon_hover' => 'rotate'
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $selector . '.pe--icon--hold' => '--rotateDeg: {{SIZE}}deg;',
                ],
            ]
        );

    }

    if ($type === 'text') {


        $widget->add_control(
            $prefix . 'text_hover',
            [
                'label' => esc_html__('Hover Effect', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'slide-up' => esc_html__('Slide Up', 'pe-core'),
                    'slide-down' => esc_html__('Slide Down', 'pe-core'),
                    'slide-left' => esc_html__('Slide Left', 'pe-core'),
                    'slide-right' => esc_html__('Slide Right', 'pe-core'),
                    'chars-up' => esc_html__('Chars Up', 'pe-core'),
                    'chars-down' => esc_html__('Chars Down', 'pe-core'),
                    'chars-left' => esc_html__('Chars Left', 'pe-core'),
                    'chars-right' => esc_html__('Chars Right', 'pe-core'),
                ],
                'condition' => $condition
            ]
        );

    }

    if ($type === 'image') {

        $widget->add_control(
            $prefix . 'background_hover',
            [
                'label' => esc_html__('Background Hover', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'reveal' => esc_html__('Reveal', 'pe-core'),
                    'zoom-in' => esc_html__('Zoom - In', 'pe-core'),
                    'zoom-out' => esc_html__('Zoom - Out', 'pe-core'),
                    'filter-in' => esc_html__('Filter - In', 'pe-core'),
                    'filter-out' => esc_html__('Filter - Out', 'pe-core'),
                    'overlay-in' => esc_html__('Overlay - In', 'pe-core'),
                    'overlay-out' => esc_html__('Overlay - Out', 'pe-core'),
                ],
                'condition' => $condition
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => $prefix . 'hover_css_filters',
                'selector' => '{{WRAPPER}} div[data-image-hover=filter-out]>img, {{WRAPPER}} div.pe--hover--trigger:hover div[data-image-hover=filter-in]>img,
                {{WRAPPER}} div[data-image-hover=filter-in]:not(.has--trigger):hover>img',
                'condition' => [
                    $prefix . 'background_hover' => ['filter-in', 'filter-out'],
                ],
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'label' => esc_html__('Overlay', 'pe-core'),
                'name' => 'hover_overlay_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} div[data-image-hover=overlay-out]::after , {{WRAPPER}} div[data-image-hover=overlay-in]::after',
                'condition' => [
                    $prefix . 'background_hover' => ['overlay-in', 'overlay-out'],
                ]
            ]
        );


        $widget->add_control(
            $prefix . 'hover_overlay_blend_mode',
            [
                'label' => esc_html__('Overlay Blend', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'normal' => esc_html__('Normal', 'pe-core'),
                    'multiply' => esc_html__('Multiply', 'pe-core'),
                    'color' => esc_html__('Color', 'pe-core'),
                    'color-burn' => esc_html__('Color Burn', 'pe-core'),
                    'darken' => esc_html__('Darken', 'pe-core'),
                    'difference' => esc_html__('Difference', 'pe-core'),
                    'hard-light' => esc_html__('Hard Light', 'pe-core'),
                    'screen' => esc_html__('Screen', 'pe-core'),
                    'overlay' => esc_html__('Overlay', 'pe-core'),
                ],
                'default' => 'multiply',
                'condition' => [
                    $prefix . 'background_hover' => ['overlay-in', 'overlay-out'],
                ],
                'selectors' => [
                    '{{WRAPPER}} div[data-image-hover=overlay-out]::after' => 'mix-blend-mode: {{VALUE}}',
                    '{{WRAPPER}} div[data-image-hover=overlay-in]::after' => 'mix-blend-mode: {{VALUE}}'
                ],
                'label_block' => false
            ]
        );
    }
}

function pe_image_hover_settings($widget, $prefix = '', $condition = false, $selector = '')
{


    if ($widget->get_name() && $widget->get_name() === 'peportfolio') {
        $frontend = true;
    } else {
        $frontend = false;
    }

    $widget->add_control(
        $prefix . 'image_hover',
        [
            'label' => esc_html__('Image Hover', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none' => esc_html__('None', 'pe-core'),
                'zoom-in' => esc_html__('Zoom - In', 'pe-core'),
                'zoom-out' => esc_html__('Zoom - Out', 'pe-core'),
                'filter-in' => esc_html__('Filter - In', 'pe-core'),
                'filter-out' => esc_html__('Filter - Out', 'pe-core'),
                'overlay-in' => esc_html__('Overlay - In', 'pe-core'),
                'overlay-out' => esc_html__('Overlay - Out', 'pe-core'),
            ],
            'condition' => $condition,
            'frontend_available' => $frontend,

        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Css_Filter::get_type(),
        [
            'name' => $prefix . 'hover_css_filters',
            'selector' => '{{WRAPPER}} div' . $selector . '[data-image-hover=filter-out]>img, 
            {{WRAPPER}} div.pe--hover--trigger:hover div' . $selector . '[data-image-hover=filter-in]>img,
            {{WRAPPER}} div' . $selector . '[data-image-hover=filter-in]:not(.has--trigger):hover>img',
            'condition' => [
                $prefix . 'image_hover' => ['filter-in', 'filter-out'],
            ],
            'frontend_available' => $frontend,
        ]
    );

    $widget->add_group_control(
        \Elementor\Group_Control_Background::get_type(),
        [
            'label' => esc_html__('Overlay', 'pe-core'),
            'name' => 'hover_overlay_background',
            'types' => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} div' . $selector . '[data-image-hover=overlay-out]::after , 
            {{WRAPPER}} div' . $selector . '[data-image-hover=overlay-in]::after',
            'exclude' => ['image'],
            'show_label' => false,
            'condition' => [
                $prefix . 'image_hover' => ['overlay-in', 'overlay-out'],
            ],
            'frontend_available' => $frontend,
        ]
    );


    $widget->add_control(
        $prefix . 'hover_overlay_blend_mode',
        [
            'label' => esc_html__('Overlay Blend', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'normal' => esc_html__('Normal', 'pe-core'),
                'multiply' => esc_html__('Multiply', 'pe-core'),
                'color' => esc_html__('Color', 'pe-core'),
                'color-burn' => esc_html__('Color Burn', 'pe-core'),
                'darken' => esc_html__('Darken', 'pe-core'),
                'difference' => esc_html__('Difference', 'pe-core'),
                'hard-light' => esc_html__('Hard Light', 'pe-core'),
                'screen' => esc_html__('Screen', 'pe-core'),
                'overlay' => esc_html__('Overlay', 'pe-core'),
            ],
            'default' => 'multiply',
            'condition' => [
                $prefix . 'image_hover' => ['overlay-in', 'overlay-out'],
            ],
            'selectors' => [
                '{{WRAPPER}} div' . $selector . '[data-image-hover=overlay-out]::after' => 'mix-blend-mode: {{VALUE}}',
                '{{WRAPPER}} div' . $selector . '[data-image-hover=overlay-in]::after' => 'mix-blend-mode: {{VALUE}}'
            ],
            'label_block' => false
        ]
    );


}

function pe_image_hover($widget, $prefix = '')
{

    if (isset($_POST['request'])) {
        $settings = $widget;
    } else if (is_object($widget)) {
        $settings = $widget->get_settings_for_display();
    } else {
        $settings = $widget;
    }

    $hover = $settings[$prefix . 'image_hover'];
    if ($hover === 'none') {
        return;
    }

    return 'data-image-hover="' . $settings[$prefix . 'image_hover'] . '" ';

}

function pe_background_hover_settings($widget, $prefix = '', $condition = false)
{


    $widget->add_control(
        $prefix . 'background_hover',
        [
            'label' => esc_html__('Background Hover', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none' => esc_html__('None', 'pe-core'),
                'simple-fade' => esc_html__('Simple Fade', 'pe-core'),
                'block-up' => esc_html__('Block - Up', 'pe-core'),
                'block-down' => esc_html__('Block - Down', 'pe-core'),
                'block-right' => esc_html__('Block - Right', 'pe-core'),
                'block-left' => esc_html__('Block - Left', 'pe-core'),
                'circle-in' => esc_html__('Circle - In', 'pe-core'),
                'reveal' => esc_html__('Reveal', 'pe-core'),
                'conceal' => esc_html__('Conceal', 'pe-core'),
            ],
            'condition' => $condition,

        ]
    );

}

function pe_background_hover($widget, $prefix = '')
{

    if (is_object($widget)) {
        $settings = $widget->get_settings_for_display();
    } else {
        $settings = $widget;
    }

    $hover = $settings[$prefix . 'background_hover'];
    if ($hover === 'none') {
        return;
    }

    return 'data-background-hover="' . $settings[$prefix . 'background_hover'] . '" ';

}

function pe_svg_mask_settings($widget, $section = true, $prefix = '', $selector = '')
{

    if ($section) {

        $widget->start_controls_section(
            $prefix . '_masking',
            [
                'label' => __('Masking', 'pe-core'),
            ]
        );


    } else {
        $widget->add_control(
            $prefix . '_masks heading',
            [
                'label' => esc_html__('Masking', 'pe-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

    }

    if ('container' === $widget->get_name()) {

        $types = [
            'none' => esc_html__('None', 'pe-core'),
            'square' => esc_html__('Square', 'pe-core'),
            'circle' => esc_html__('Circle', 'pe-core'),
            'custom' => esc_html__('Custom', 'pe-core'),
        ];

    } else {

        $types = [
            'none' => esc_html__('None', 'pe-core'),
            'square' => esc_html__('Square', 'pe-core'),
            'circle' => esc_html__('Circle', 'pe-core'),
            'custom' => esc_html__('Custom', 'pe-core'),
            'svg' => esc_html__('SVG', 'pe-core'),
        ];

    }


    $widget->add_control(
        $prefix . '_mask_type',
        [
            'label' => esc_html__('Mask Type', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'options' => $types,
            'label_block' => true,
        ]
    );

    $widget->add_control(
        $prefix . '_mask_svg_set',
        [
            'label' => esc_html__('SVG', 'pe-core'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'condition' => [$prefix . '_mask_type' => 'svg'],
        ]
    );


    $widget->add_responsive_control(
        $prefix . '_mask_svg_x',
        [
            'label' => esc_html__('Position X', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw', 'custom'],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .mask--svg path' => ' --maskX: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [$prefix . '_mask_type' => 'svg'],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_mask_svg_y',
        [
            'label' => esc_html__('Position Y', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vh', 'custom'],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .mask--svg path' => ' --maskY: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [$prefix . '_mask_type' => 'svg'],
        ]
    );


    $widget->add_responsive_control(
        $prefix . '_mask_svg_scale',
        [
            'label' => esc_html__('Scale', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 50,
                    'step' => 0.1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .mask--svg path' => '--maskScale: {{SIZE}};',
            ],
            'condition' => [$prefix . '_mask_type' => 'svg'],
        ]
    );


    $widget->add_responsive_control(
        '_mask_svg_rotate',
        [
            'label' => esc_html__('Rotate', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -360,
                    'max' => 360,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' .mask--svg path' => ' --maskRotate: {{SIZE}}deg;',
            ],
            'condition' => [$prefix . '_mask_type' => 'svg'],
        ]
    );

    $widget->add_control(
        $prefix . '_mask_custom_set',
        [
            'label' => esc_html__('Clip Path', 'pe-core'),
            'description' => 'You can insert your custom clip-path here. You can use helpers like <a href="https://bennettfeely.com/clippy/" target="_blank">Clippy</a>',
            'type' => \Elementor\Controls_Manager::CODE,
            'language' => 'scss',
            'default' => 'polygon(75% 0%, 100% 50%, 75% 100%, 0% 100%, 25% 50%, 0% 0%);',
            'condition' => [$prefix . '_mask_type' => 'custom'],
            'rows' => 10,
            'ai' => false,
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' ' => 'clip-path: {{VALUE}}',
            ],
        ]
    );

    $widget->add_control(
        $prefix . '_mask_square_set',
        [
            'type' => \Elementor\Controls_Manager::HIDDEN,
            'default' => 'inset(20% 20% 10% 10%)',
            'condition' => [$prefix . '_mask_type' => 'square'],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' ' => 'clip-path: {{VALUE}}',
            ],
        ]
    );

    $widget->add_control(
        $prefix . '_mask_circle_set',
        [
            'type' => \Elementor\Controls_Manager::HIDDEN,
            'default' => 'circle(var(--circSize) at var(--circLeft) var(--circTop));--circSize: 20%;--circTop: 50%;--circLeft: 50%',
            'condition' => [$prefix . '_mask_type' => 'circle'],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' ' => 'clip-path: {{VALUE}}',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_square_mask',
        [
            'label' => esc_html__('Square Mask', 'pe-core'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['%'],
            'default' => [
                'top' => 10,
                'right' => 20,
                'bottom' => 23,
                'left' => 50,
                'unit' => '%',
                'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' ' => 'clip-path: inset({{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} round var(--round));',
            ],
            'condition' => [
                $prefix . '_mask_type' => 'square',
            ]
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_square_mask_radius',
        [
            'label' => esc_html__('Radius', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 0,
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' ' => '--round: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                $prefix . '_mask_type' => 'square',
            ]
        ]
    );



    $widget->add_responsive_control(
        $prefix . '_circle_size',
        [
            'label' => esc_html__('Circle Size', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 30,
            ],
            'condition' => [
                $prefix . '_mask_type' => 'circle',
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' ' => '--circSize: {{SIZE}}{{UNIT}}',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_circle_top_pos',
        [
            'label' => esc_html__('Circle Top Position', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 50,
            ],
            'condition' => [
                $prefix . '_mask_type' => 'circle',
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' ' => '--circTop: {{SIZE}}{{UNIT}}',
            ],
        ]
    );

    $widget->add_responsive_control(
        $prefix . '_circle_left_pos',
        [
            'label' => esc_html__('Circle Left Position', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 50,
            ],
            'condition' => [
                $prefix . '_mask_type' => 'circle',
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $selector . ' ' => '--circLeft: {{SIZE}}{{UNIT}}',
            ],
        ]
    );
    if ($section) {
        $widget->end_controls_section();
    }

}

function pe_svg_mask_render($widget, $prefix = '', $selector = '')
{

    $settings = $widget->get_settings_for_display();
    $id = $widget->get_id();

    if ($settings[$prefix . '_mask_type'] === 'svg' && isset($settings[$prefix . '_mask_svg_set'])) {

        if (!isset($settings[$prefix . '_mask_svg_set']['value']['url'])) {
            return false;
        }
        ;

        $svgPath = $settings[$prefix . '_mask_svg_set']['value']['url'];
        $svgContent = file_get_contents($svgPath);

        preg_match('/<svg[^>]*>(.*?)<\/svg>/is', $svgContent, $matches);
        $innerShapes = isset($matches[1]) ? $matches[1] : '';

        $innerShapes = preg_replace('/<\/?g[^>]*>/', '', $innerShapes); ?>


                                <svg class="mask--svg" width="0" height="0">
                                    <defs>
                                        <clipPath id="mask-<?php echo $id . '-' . $selector ?>">
                                            <?php echo $innerShapes; ?>
                                        </clipPath>
                                    </defs>
                                </svg>

                                <?php if (!empty($selector)) { ?>
                                                <style>
                                                <?php echo '.elementor-element-' . $id . ' .' . $selector;

                                                ?> {
                                                    clip-path: url(#mask-<?php echo $id . '-' . $selector; ?>);
                                                }

                                                <?php
                                } else {
                                    ?><style>.elementor-element-<?php echo $id;

                                    ?> {
                                                        clip-path: url(#mask-<?php echo $id . '-' . $selector; ?>);
                                                    }

                                                    <?php
                                }

                                ?>
                                </style>

                <?php }
}


function pe_slider_style_settings($widget, $section = false, $condition = false, $prefix = '')
{


    if ($condition) {
        $cond = $condition;
    }


    if ($section) {

        $widget->start_controls_section(
            $prefix . 'pe_slider_styles',
            [
                'label' => esc_html__('Slider Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => $condition,
            ]
        );

    }

    $widget->add_responsive_control(
        $prefix . 'slider_width',
        [
            'label' => esc_html__('Slider Width', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw', 'vh', 'em', 'custom'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .pe--slider--container.swiper-container' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => $condition,

        ]
    );

    $widget->add_responsive_control(
        $prefix . 'slider_height',
        [
            'label' => esc_html__('Slider Height', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw', 'vh', 'em', 'custom'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .pe--slider--wrapper.swiper-wrapper' => 'height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => $condition,

        ]
    );

    $widget->add_responsive_control(
        $prefix . 'items_height',
        [
            'label' => esc_html__('Items Height', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw', 'vh', 'em', 'custom'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .pe--slider--slide.swiper-slide' => 'height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .pe--slider--slide.swiper-slide > div' => 'height:100%;',
            ],
            'condition' => $condition,

        ]
    );

    $widget->add_responsive_control(
        $prefix . 'items_width',
        [
            'label' => esc_html__('Items Width', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw', 'vh', 'em', 'custom'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 1000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .pe--slider--slide.swiper-slide' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => $condition,
            'render_type' => 'template'

        ]
    );

    $widget->add_responsive_control(
        $prefix . 'align_items_row',
        [
            'label' => esc_html__('Align Items', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => esc_html__('Start', 'pe-core'),
                    'icon' => 'eicon-align-start-v'
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-align-center-v'
                ],
                'flex-end' => [
                    'title' => esc_html__('End', 'pe-core'),
                    'icon' => 'eicon-align-end-v'
                ],
                'space-between' => [
                    'title' => esc_html__('Strecth', 'pe-core'),
                    'icon' => 'eicon-align-stretch-v'
                ],
            ],

            'selectors' => [
                '{{WRAPPER}} .pe--slider--wrapper.swiper-wrapper' => 'align-items: {{VALUE}};',
            ],
            'condition' => $condition,
        ]
    );



    $widget->add_control(
        $prefix . 'fade_edges',
        [
            'label' => esc_html__('Fade Edges?', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'fade--edges',
            'prefix_class' => '',
            'default' => '',
        ]
    );

    $widget->add_control(
        $prefix . 'fade_color',
        [
            'label' => esc_html__('Fade Color', 'pe-core'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pe--slider' => '--fade--color: {{VALUE}}',
            ],
            'condition' => [
                $prefix . 'fade_edges' => 'fade--edges',
            ],
        ]
    );

    $widget->add_control(
        $prefix . 'pe_slider_breakpoints',
        [
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'label' => esc_html__('Breakpoints', 'pe-core'),
            'label_off' => esc_html__('Default', 'pe-core'),
            'label_on' => esc_html__('Custom', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
            'render_type' => 'template',
            'condition' => $condition,
        ]
    );


    $widget->start_popover();

    $active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

    foreach ($active_breakpoints as $breakpoint) {
        $name = $breakpoint->get_name();
        $label = $breakpoint->get_label();
        $value = $breakpoint->get_value();
        $direction = $breakpoint->get_direction();

        $widget->add_control(
            $prefix . 'breakpoint_' . $name,
            [
                'label' => sprintf(__('%s', 'pe-core'), $label),
                'description' => sprintf(__('%dpx %s-width', 'pe-core'), $value, $direction),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => $condition,
            ]
        );

        $widget->add_control(
            $prefix . 'breakpoint_size' . $name,
            [
                'label' => sprintf(__('%dpx %s-width', 'pe-core'), $value, $direction),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'default',
                'condition' => $condition,
            ]
        );

        $widget->add_control(
            $prefix . 'slides_per_view' . $name,
            [
                'label' => sprintf(__('Slides Per View', 'pe-core'), $label),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [' '],
                'range' => [
                    ' ' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 1,
                    ],
                ],
                'render_type' => 'template',
                'condition' => $condition,

            ]
        );

        $widget->add_control(
            $prefix . 'space_between' . $name,
            [
                'label' => sprintf(__('Space Between', 'pe-core'), $label),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                ],
                'render_type' => 'template',
                'condition' => $condition,
            ]
        );

        $widget->add_control(
            $prefix . 'slider_direction' . $name,
            [
                'label' => esc_html__('Direction', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'pe-core'),
                    'horizontal' => esc_html__('Horizontal', 'pe-core'),
                    'vertical' => esc_html__('Vertical', 'pe-core'),
                ],
                'condition' => $condition,
            ]
        );


    }


    $widget->end_popover();

    if ($section) {

        $widget->end_controls_section();
    }

}

function pe_slider_settings($widget, $section = true, $condition = false, $prefix = '')
{

    $cond = [];

    if ($condition && is_array($condition)) {
        $cond = $condition;
    }

    if ($section) {

        $widget->start_controls_section(
            $prefix . 'pe_slider_settings',
            [
                'label' => __('Slider', 'pe-core'),
                'condition' => $condition,
            ]
        );

    }


    $widget->add_control(
        $prefix . 'pe_slider_id',
        [
            'label' => esc_html__('Slider ID', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'ai' => false,
            'placeholder' => esc_html__('Eg: portfolioSlider', 'pe-core'),
            'description' => esc_html__('Required if you will going to use "Slider Controls" widget for this one.', 'pe-core'),
            'condition' => $condition,
        ]
    );


    $widget->add_control(
        $prefix . 'effect',
        [
            'label' => esc_html__('Effect', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'slide',
            'options' => [
                'slide' => esc_html__('Slide', 'pe-core'),
                'fade' => esc_html__('Fade', 'pe-core'),
                'cube' => esc_html__('Cube', 'pe-core'),
                'coverflow' => esc_html__('Coverflow', 'pe-core'),
                'cards' => esc_html__('Cards', 'pe-core'),
                'creative' => esc_html__('Creative', 'pe-core'),
            ],
            'prefix_class' => 'slider--effect--',
            'render_type' => 'template',
            'condition' => $condition,

        ]
    );

    $widget->add_control(
        $prefix . 'pe_slider_direction',
        [
            'label' => esc_html__('Direction', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'horizontal',
            'options' => [
                'horizontal' => esc_html__('Horizontal', 'pe-core'),
                'vertical' => esc_html__('Vertical', 'pe-core'),
            ],
            'condition' => $condition,
        ]
    );



    $widget->add_responsive_control(
        $prefix . 'slides_per_view',
        [
            'label' => esc_html__('Slides Per View', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [' '],
            'range' => [
                ' ' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => ' ',
                'size' => 3,
            ],
            'render_type' => 'template',
            'condition' => $condition,

        ]
    );

    $widget->add_control(
        $prefix . 'space_between',
        [
            'label' => esc_html__('Space Between', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 20,
            ],
            'render_type' => 'template',
            'condition' => $condition,
        ]
    );



    if ($widget->get_name() === 'peimagegallery' || $widget->get_name() === 'projectmedia') {

        $widget->add_control(
            $prefix . 'slider_parallax_images',
            [
                'label' => esc_html__('Parallax Images', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => '',
                'render_type' => 'template',
                'condition' => [
                    $prefix . 'effect' => 'slide',
                    ...$cond
                ]

            ]
        );


    } else {

        $widget->add_control(
            $prefix . 'slider_parallax_images',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => '',
                'condition' => [
                    $prefix . 'effect' => 'slide',
                    ...$cond
                ]

            ]
        );
    }

    $widget->add_control(
        $prefix . 'slider_parallax_strength',
        [
            'label' => esc_html__('Parallax Strength', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [' '],
            'range' => [
                ' ' => [
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.1,
                ],
            ],
            'default' => [
                'unit' => ' ',
                'size' => 0.5,
            ],
            'render_type' => 'template',
            'condition' => $condition,
            'condition' => [
                $prefix . 'effect' => 'slide',
                $prefix . 'slider_parallax_images' => 'true',
                ...$cond
            ]
        ]
    );

    $widget->add_control(
        $prefix . 'slider_play_scroll',
        [
            'label' => esc_html__('Play on Scroll', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
            'render_type' => 'template',
            'condition' => $condition,
        ]
    );

    $widget->add_control(
        $prefix . 'slider_pin',
        [
            'label' => esc_html__('Pin', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'render_type' => 'template',
            'default' => 'false',
            'description' => esc_html__('Animation will be pinned to window during animation.', 'pe-core'),
            'condition' => [
                $prefix . 'slider_play_scroll' => 'true',
                ...$cond
            ]
        ]
    );

    $widget->add_control(
        $prefix . 'slider_pin_target',
        [
            'label' => esc_html__('Pin Trigger', 'pe-core'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => esc_html__('Eg: #container2', 'pe-core'),
            'condition' => [
                $prefix . 'slider_play_scroll' => 'true',
                ...$cond
            ],
            'description' => esc_html__('You can enter a container id/class which the element will be pinned during animation.', 'pe-core'),

        ]
    );


    $widget->add_control(
        $prefix . 'slider_start_references',
        [
            'label' => esc_html__('Start References', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'after',
            'condition' => [
                $prefix . 'slider_play_scroll' => 'true',
                ...$cond
            ],
        ]
    );

    $widget->add_control(
        $prefix . 'slider_references_notice',
        [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
	           This references below are adjusts the animation start/end positions on the screen. <b>For Example: If you select <u>'Top' for item reference point</u> and <u>'Bottom' for the window reference point</u>; animation will start when item's top edge enters the window's bottom edge.</b></div>",
            'condition' => [
                $prefix . 'slider_play_scroll' => 'true',
                ...$cond
            ],

        ]
    );

    $widget->add_control(
        $prefix . 'slider_item_ref_start',
        [
            'label' => esc_html__('Item Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'default' => 'top',
            'toggle' => false,
            'condition' => [
                $prefix . 'slider_play_scroll' => 'true',
                ...$cond
            ],
        ]
    );

    $widget->add_control(
        $prefix . 'slider_window_ref_start',
        [
            'label' => esc_html__('Window Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'default' => 'bottom',
            'toggle' => false,
            'condition' => [
                $prefix . 'slider_play_scroll' => 'true',
                ...$cond
            ],
        ]
    );

    $widget->add_control(
        $prefix . 'slider_end_references',
        [
            'label' => esc_html__('End References', 'pe-core'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'after',
            'condition' => [
                $prefix . 'slider_play_scroll' => 'true',
                ...$cond
            ],
        ]
    );

    $widget->add_control(
        $prefix . 'slider_item_ref_end',
        [
            'label' => esc_html__('Item Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'default' => 'bottom',
            'toggle' => false,
            'condition' => [
                $prefix . 'slider_play_scroll' => 'true',
                ...$cond
            ],
        ]
    );

    $widget->add_control(
        $prefix . 'slider_window_ref_end',
        [
            'label' => esc_html__('Window Reference Point', 'pe-core'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'pe-core'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'pe-core'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'pe-core'),
                    'icon' => ' eicon-v-align-bottom',
                ],
            ],
            'default' => 'top',
            'toggle' => false,
            'condition' => [
                $prefix . 'slider_play_scroll' => 'true',
                ...$cond
            ],
        ]
    );

    $widget->add_control(
        $prefix . 'speed',
        [
            'label' => esc_html__('Speed (ms)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 10,
            'max' => 10000,
            'step' => 10,
            'default' => 750,
            'condition' => [
                ...$cond
            ]
        ]
    );



    $widget->add_control(
        $prefix . 'centered_slides',
        [
            'label' => esc_html__('Centered Slides', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
            'render_type' => 'template',
            'condition' => [
                ...$cond
            ]

        ]
    );

    $widget->add_control(
        $prefix . 'loop',
        [
            'label' => esc_html__('Loop', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
            'render_type' => 'template',
            'condition' => [
                $prefix . 'slider_play_scroll!' => 'true',
                ...$cond
            ]
        ]
    );

    $widget->add_control(
        $prefix . 'autoplay',
        [
            'label' => esc_html__('Autoplay', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
            'render_type' => 'template',
            'condition' => [
                $prefix . 'slider_play_scroll!' => 'true',
                ...$cond
            ]

        ]
    );


    $widget->add_control(
        $prefix . 'autoplay_delay',
        [
            'label' => esc_html__('Autoplay Delay (ms)', 'pe-core'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 10,
            'max' => 10000,
            'step' => 10,
            'default' => 2000,
            'condition' => [
                $prefix . 'autoplay' => 'true',
                $prefix . 'slider_play_scroll!' => 'true',
                ...$cond
            ]
        ]
    );

    $widget->add_control(
        $prefix . 'mouse_wheel',
        [
            'label' => esc_html__('Mousewheel', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
            'render_type' => 'template',
            'condition' => [
                $prefix . 'slider_play_scroll!' => 'true',
                ...$cond
            ]

        ]
    );

    $widget->add_control(
        $prefix . 'mouse_wheel_invert',
        [
            'label' => esc_html__('Invert Mousewheel?', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
            'render_type' => 'template',
            'condition' => [
                $prefix . 'slider_play_scroll!' => 'true',
                $prefix . 'mouse_wheel' => 'true',
                ...$cond
            ],

        ]
    );

    $widget->add_control(
        $prefix . 'highlight_active',
        [
            'label' => esc_html__('Highlight Active', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'slider--highlight--active',
            'default' => '',
            'render_type' => 'template',
            'prefix_class' => '',
            'condition' => $condition,

        ]
    );

    $widget->add_control(
        $prefix . 'slider_free_mode',
        [
            'label' => esc_html__('Free Mode', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
            'render_type' => 'template',
            'condition' => [
                $prefix . 'slider_play_scroll!' => 'true',
                $prefix . 'effect' => 'slide',
                ...$cond
            ]

        ]
    );

    $widget->add_control(
        $prefix . 'slider_free_mode_snap',
        [
            'label' => esc_html__('Snap', 'pe-core'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'pe-core'),
            'label_off' => esc_html__('No', 'pe-core'),
            'return_value' => 'true',
            'default' => '',
            'render_type' => 'template',
            'condition' => [
                $prefix . 'slider_play_scroll!' => 'true',
                $prefix . 'effect' => 'slide',
                $prefix . 'slider_free_mode' => 'true',
                ...$cond
            ]

        ]
    );

    $active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

    foreach ($active_breakpoints as $breakpoint) {
        $name = $breakpoint->get_name();
        $label = $breakpoint->get_label();
        $value = $breakpoint->get_value();
        $direction = $breakpoint->get_direction();

        $widget->add_control(
            $prefix . 'pin_disable_enable_' . $name,
            [
                'label' => sprintf(__('Disable pin for %s', 'pe-core'), $label),
                'description' => sprintf(__('%dpx %s-width', 'pe-core'), $value, $direction),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core'),
                'label_off' => __('No', 'pe-core'),
                'return_value' => 'yes',
                'render_type' => 'template',
                'default' => $name === 'mobile' ? 'yes' : '',
                'prefix_class' => 'pin-disabled-' . $name . '-',
                'condition' => [
                    $prefix . 'slider_play_scroll' => 'true',
                    $prefix . 'slider_pin' => 'true',
                ],
            ]
        );

    }

    if ($section) {
        $widget->end_controls_section();
    }

}


function pe_slider_render($widget, $objects, $prefix = '')
{

    $settings = $widget->get_settings_for_display();

    if (!$objects || !is_array($objects)) {
        return;
    }

    $swiperSettings = [
        "id" => $settings[$prefix . 'pe_slider_id'],
        "effect" => $settings[$prefix . 'effect'],
        "slidesPerView" => (isset($settings[$prefix . 'items_width']) && !empty($settings[$prefix . 'items_width']['size'])) ? 'auto' : $settings[$prefix . 'slides_per_view']['size'],
        "spaceBetween" => $settings[$prefix . 'space_between']['size'],
        "speed" => $settings[$prefix . 'speed'],
        "centeredSlides" => $settings[$prefix . 'centered_slides'],
        "loop" => $settings[$prefix . 'loop'],
        "autoplay" => $settings[$prefix . 'autoplay'],
        "autoplayDelay" => $settings[$prefix . 'autoplay_delay'],
        "mouseWheel" => $settings[$prefix . 'mouse_wheel'],
        "mouseWheelInvert" => $settings[$prefix . 'mouse_wheel_invert'],
        "direction" => $settings[$prefix . 'pe_slider_direction'],
        "parallax" => $settings[$prefix . 'slider_parallax_images'],
        "parallaxStrength" => isset($settings[$prefix . 'slider_parallax_strength']) ? $settings[$prefix . 'slider_parallax_strength']['size'] : 0,
        "freeMode" => $settings[$prefix . 'slider_free_mode'],
        "freeModeSnap" => $settings[$prefix . 'slider_free_mode_snap'],
        "playOnScroll" => $settings[$prefix . 'slider_play_scroll'],
        "pin" => $settings[$prefix . 'slider_pin'],
        "pinTarget" => $settings[$prefix . 'slider_pin_target'],
        "itemRefStart" => $settings[$prefix . 'slider_item_ref_start'],
        "windowRefStart" => $settings[$prefix . 'slider_window_ref_start'],
        "itemRefEnd" => $settings[$prefix . 'slider_item_ref_end'],
        "windowRefEnd" => $settings[$prefix . 'slider_window_ref_end'],
    ];



    $active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

    $breakpointsSettings = [];

    foreach ($active_breakpoints as $breakpoint) {
        $name = $breakpoint->get_name();
        $value = $breakpoint->get_value();

        $slides_key = $prefix . 'slides_per_view' . $name;
        $space_key = $prefix . 'space_between' . $name;
        $dir_key = $prefix . 'slider_direction' . $name;

        $slides_per_view = (isset($settings[$slides_key]['size']) && $settings[$slides_key]['size'] !== '')
            ? $settings[$slides_key]['size']
            : null;

        $space_between = (isset($settings[$space_key]['size']) && $settings[$space_key]['size'] !== '')
            ? $settings[$space_key]['size']
            : null;

        $direction = (isset($settings[$dir_key]) && $settings[$dir_key] !== 'default')
            ? $settings[$dir_key]
            : null;

        if ($slides_per_view !== null || $space_between !== null || $direction !== null) {
            $breakpointData = [];

            if ($slides_per_view !== null) {
                $breakpointData[$prefix . 'slidesPerView'] = $slides_per_view;
            }
            if ($space_between !== null) {
                $breakpointData[$prefix . 'spaceBetween'] = $space_between;
            }

            if ($direction !== null) {
                $breakpointData[$prefix . 'direction'] = $direction;
            }

            $breakpointsSettings[$value] = $breakpointData;
        }
    }

    if (!empty($breakpointsSettings)) {
        $swiperSettings['breakpoints'] = $breakpointsSettings;
    }

    $anim = '';

    if ($widget->get_name() === 'projectmedia') {
        $anim = pe_general_animation($widget);
    }

    ob_start();
    ?>


                <div class="pe--slider anim-multiple" <?php echo $anim ?> data-settings='<?php echo json_encode($swiperSettings) ?>'>

                    <div class="pe--slider--container swiper-container <?php echo $settings[$prefix . 'pe_slider_id'] ?>">

                        <div class="pe--slider--wrapper swiper-wrapper">

                            <?php foreach ($objects as $object) { ?>

                                            <div class="pe--slider--slide swiper-slide">


                                                <?php if ($settings[$prefix . 'slider_parallax_images'] === 'true') { ?>
                                                                <div class="swiper-parallax-wrap">
                                                                    <div class="slide-bgimg">
                                                        <?php }

                                                echo $object;
                                                ?>

                                                        <?php if ($settings[$prefix . 'slider_parallax_images'] === 'true') { ?>
                                                                    </div>
                                                                </div>
                                                <?php } ?>

                                            </div>

                            <?php } ?>


                        </div>


                    </div>

                </div>
                <?php

                $render = ob_get_clean();
                return $render;

}