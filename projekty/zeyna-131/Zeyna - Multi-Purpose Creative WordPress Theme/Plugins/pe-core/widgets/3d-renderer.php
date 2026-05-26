<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class Pe3DRenderer extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'pe3drenderer';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('3D Renderer', 'pe-core');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-ehp-hero pe-widget';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['pe-content'];
    }


    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function _register_controls()
    {

        // Tab Title Control
        $this->start_controls_section(
            '3d_renderer',
            [
                'label' => __('3D Renderer', 'pe-core'),
            ]
        );

        $this->add_control(
            '3d_online_docs',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-warning'>	
	           <span><i class='eicon-play-o' aria-hidden='true'></i> Video tutorial for this widget here; <a href='https://www.3daistudio.com/svgTo3D' target='_blank'>3D Renderer</a>. </span></div>",
            ]
        );

        $this->add_control(
            'input_type',
            [
                'label' => esc_html__('Input', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'url',
                'options' => [
                    'url' => esc_html__('URL', 'pe-core'),
                    'upload' => esc_html__('Upload', 'pe-core'),
                ],
            ]
        );

        $this->add_control(
            '3d_file',
            [
                'label' => esc_html__('Upload Model', 'pe-core'),
                'description' => '.GLB and .GLTF files allowed only.',
                'pe-core',
                'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => ['glb', 'gltf'],
                'condition' => ['input_type' => 'upload'],
            ]
        );


        $this->add_control(
            '3d_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
	           <span>For the best practices of 3D rendering use <a href='https://www.blender.org' target='_blank'>Blender</a> to prepare your models. If you do not have enough knowledge about Blender; you can use converting tools like; <a href='https://www.3daistudio.com/svgTo3D' target='_blank'>3D AI Studio</a></span></div>",
            ]
        );

        $this->add_control(
            '3d_file_url',
            [
                'label' => esc_html__('File URL', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => esc_html__('.GLB and .GLTF files allowed only. ', 'pe-core'),
                'ai' => false,
                'default' => 'https://threejs.org/examples/models/gltf/DamagedHelmet/glTF/DamagedHelmet.gltf',
                'condition' => ['input_type' => 'url'],
            ]
        );

        $this->add_control(
            'scene_background',
            [
                'label' => esc_html__('Background', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "true",
                'default' => "",
            ]
        );

        $this->add_control(
            'orbit_controls',
            [
                'label' => esc_html__('Orbit Controls', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "true",
                'default' => "false",
            ]
        );

        $this->add_control(
            'transform_controls',
            [
                'label' => esc_html__('Transform (Object) Controls', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "true",
                'default' => "false",
            ]
        );

        $this->add_control(
            'edit_mode',
            [
                'label' => esc_html__('Edit Mode', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "true",
                'default' => "false",
            ]
        );

        $this->add_control(
            'scroll_sequence',
            [
                'label' => esc_html__('Scroll Sequence', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "true",
                'default' => "false",
            ]
        );

        $this->add_control(
            'sequence_json',
            [
                'label' => esc_html__('Sequence JSON', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'json',
                'rows' => 10,
                'ai' => false,
                'condition' => [
                    'scroll_sequence' => 'true',
                ],
            ]
        );

        $this->add_control(
            'custom_json',
            [
                'label' => esc_html__('Custom JSON', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'json',
                'rows' => 10,
                'ai' => false,
            ]
        );




        $this->end_controls_section();

        $this->start_controls_section(
            'section_animations',
            [
                'label' => __('Animations', 'pe-core'),
            ]
        );

        $this->add_control(
            'reveal',
            [
                'label' => esc_html__('Reveal Animation', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'up' => esc_html__('Up', 'pe-core'),
                    'down' => esc_html__('Down', 'pe-core'),
                    'left' => esc_html__('Left', 'pe-core'),
                    'right' => esc_html__('Right', 'pe-core'),
                    'top-left' => esc_html__('Top-Left', 'pe-core'),
                    'top-right' => esc_html__('Top-Right', 'pe-core'),
                    'bottom-left' => esc_html__('Bottom-Left', 'pe-core'),
                    'bottom-right' => esc_html__('Bottom-Right', 'pe-core'),
                    'zoom-in' => esc_html__('Zoom-In', 'pe-core'),
                    'zoom-out' => esc_html__('Zoom-Out', 'pe-core'),
                ],
                'label_block' => false,
            ]
        );

        $this->add_control(
            'mouse',
            [
                'label' => esc_html__('Mouse Interactions', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'look-at' => esc_html__('Object Look At', 'pe-core'),
                    'camera rotate' => esc_html__('Camera Rotate', 'pe-core'),
                    'object rotate' => esc_html__('Object Rotate', 'pe-core'),
                    'camera zoom' => esc_html__('Zoom', 'pe-core'),
                ],
                'label_block' => false,
                'condition' => [
                    'parallax' => 'none',
                    'motion' => 'none',
                ],
            ]
        );

        $this->add_control(
            'parallax',
            [
                'label' => esc_html__('Parallax Effect', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'up' => esc_html__('Up', 'pe-core'),
                    'down' => esc_html__('Down', 'pe-core'),
                    'left' => esc_html__('Left', 'pe-core'),
                    'right' => esc_html__('Right', 'pe-core'),

                ],
                'label_block' => false,
                'condition' => [
                    'mouse' => 'none',
                    'motion' => 'none',
                ],
            ]
        );


        $this->add_control(
            'motion',
            [
                'label' => esc_html__('Motion Animation', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'object rotate' => esc_html__('Object Rotate', 'pe-core'),
                ],
                'label_block' => false,
                'condition' => [
                    'mouse' => 'none',
                    'parallax' => 'none',
                ],
            ]
        );

        $this->add_control(
            'mouse_events_target',
            [
                'label' => esc_html__('Mouse Events Target', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Eg: #container2', 'pe-core'),
                'description' => esc_html__('"window" for whole page events..', 'pe-core'),
                'condition' => [
                    'mouse!' => 'none',
                ],

            ]
        );


        $this->add_control(
            'object_anim_rotate_on',
            [
                'label' => esc_html__('Rotate On Axis?', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "axis",
                'default' => "self",
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'mouse',
                            'operator' => '===',
                            'value' => 'object rotate',
                        ],
                        [
                            'name' => 'motion',
                            'operator' => '===',
                            'value' => 'object rotate',
                        ],
                    ],
                ],

            ]
        );


        $this->add_control(
            'object_anim_rotation_x',
            [
                'label' => esc_html__('Rotate X', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "X",
                'default' => '',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'mouse',
                            'operator' => '===',
                            'value' => 'object rotate',
                        ],
                        [
                            'name' => 'motion',
                            'operator' => '===',
                            'value' => 'object rotate',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'object_anim_rotation_y',
            [
                'label' => esc_html__('Rotate Y', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "Y",
                'default' => '',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'mouse',
                            'operator' => '===',
                            'value' => 'object rotate',
                        ],
                        [
                            'name' => 'motion',
                            'operator' => '===',
                            'value' => 'object rotate',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'object_anim_rotation_z',
            [
                'label' => esc_html__('Rotate Z', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "Z",
                'default' => 'Z',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'mouse',
                            'operator' => '===',
                            'value' => 'object rotate',
                        ],
                        [
                            'name' => 'motion',
                            'operator' => '===',
                            'value' => 'object rotate',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            '3d_animation_options',
            [
                'label' => esc_html__('Animation Options', 'pe-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'speed_up_on_scroll',
            [
                'label' => esc_html__('Speed up on scroll?', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "true",
                'default' => "null",
            ]
        );


        $this->start_controls_tabs(
            '3d_animation_options_tabs'
        );

        $this->start_controls_tab(
            '3d_basic_tab',
            [
                'label' => esc_html__('Basic', 'pe-core'),
            ]
        );

        $this->add_control(
            '3d_duration',
            [
                'label' => esc_html__('Duration', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0.1,
                'step' => 0.1,
                'default' => 3
            ]
        );

        $this->add_control(
            '3d_delay',
            [
                'label' => esc_html__('Delay', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 0.1,
                'default' => 0
            ]
        );


        $this->add_control(
            '3d_scrub',
            [
                'label' => esc_html__('Scrub', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "true",
                'default' => "null",
                'description' => esc_html__('Animation will follow scrolling behavior of the page.', 'pe-core'),
            ]
        );


        $this->add_control(
            '3d_pin',
            [
                'label' => esc_html__('Pin', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "true",
                'default' => "null",
                'description' => esc_html__('Animation will be pinned to window during animation.', 'pe-core'),
            ]
        );

        $this->add_control(
            '3d_repeat',
            [
                'label' => esc_html__('Repeat', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => 'null',
                'render_type' => 'template',
                'description' => esc_html__('Animation will be restarted when leaveing/entering the viewport.', 'pe-core'),

            ]
        );

        $this->add_control(
            'reveal_repeat',
            [
                'label' => esc_html__('Reveal Repeat', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => 'null',
                'render_type' => 'template',
                'description' => esc_html__('Animation will be restarted when leaveing/entering the viewport.', 'pe-core'),

            ]
        );

        $this->add_control(
            '3d_mobile_pin',
            [
                'label' => esc_html__('Pin Mobile Devices', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'prefix_class' => 'mobile__pin__',
                'default' => 'false',
                'condition' => [
                    'pin' => 'true',
                ],
                'description' => esc_html__('Pinning large sections/containers at mobile devices may cause issues.', 'pe-core'),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '3d_advanced_tab',
            [
                'label' => esc_html__('Advanced', 'pe-core'),
            ]
        );

        $this->add_control(
            '3d_animation_easing',
            [
                'label' => esc_html__('Easing', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'null',
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

        $this->add_control(
            '3d_easing_behavior',
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
                    '3d_animation_easing!' => ['default', 'none'],
                ]
            ]
        );

        $this->add_control(
            '3d_pinned_target',
            [
                'label' => esc_html__('Pin Target', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Eg: #container2', 'pe-core'),
                'description' => esc_html__('You can enter a container id/class which the element will be pinned during animation.', 'pe-core'),

            ]
        );


        $this->add_control(
            '3d_start_references',
            [
                'label' => esc_html__('Start References', 'pe-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            '3d_references_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
                   This references below are adjusts the animation start/end positions on the screen. <b>For Example: If you select <u>'Top' for item reference point</u> and <u>'Bottom' for the window reference point</u>; animation will start when item's top edge enters the window's bottom edge.</b></div>",
            ]
        );

        $this->add_control(
            '3d_item_ref_start',
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

        $this->add_control(
            '3d_window_ref_start',
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

        $this->add_control(
            '3d_end_references',
            [
                'label' => esc_html__('End References', 'pe-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            '3d_end_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>For scrubbed/pinned animations only.</div>",
            ]
        );

        $this->add_control(
            '3d_item_ref_end',
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

        $this->add_control(
            '3d_window_ref_end',
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



        $this->add_control(
            '3d_animate_out',
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

        $this->add_control(
            'show_markers',
            [
                'label' => esc_html__('Show Markers', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "true",
                'default' => "null",
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_additional_opt',
            [
                'label' => __('Additionl Options', 'pe-core'),
            ]
        );

        $this->add_control(
            'hdr_file',
            [
                'label' => esc_html__('Upload HDR', 'pe-core'),
                'description' => esc_html__('.HDR files allowed only. ', 'pe-core'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => ['hdr'],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'renderer_styles',
            [
                'label' => esc_html__('Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'renderer_width',
            [
                'label' => esc_html__('Renderer Width', 'pe-core'),
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
                    '{{WRAPPER}} .renderer--container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'renderer_height',
            [
                'label' => esc_html__('Renderer Height', 'pe-core'),
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
                    '{{WRAPPER}} .renderer--container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();
        pe_cursor_settings($this);


        pe_color_options($this);

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();



        $file = '';
        if ($settings['input_type'] === 'url') {
            $file = $settings['3d_file_url'];
        } else {
            $file = $settings['3d_file']['url'];
        }

        $hdr = '';
        if ($settings['hdr_file']['url']) {
            $hdr = $settings['hdr_file']['url'];
        } else {
            $hdr = plugins_url('../assets/3d/christmas_photo_studio_01_1k.hdr', __FILE__);
        }

        $anims = [
            "reveal" => $settings['reveal'] !== 'none' ? $settings['reveal'] : null,
            "mouse" => $settings['mouse'] !== 'none' ? $settings['mouse'] : null,
            "parallax" => $settings['parallax'] !== 'none' ? $settings['parallax'] : null,
            "motion" => $settings['motion'] !== 'none' ? $settings['motion'] : null,
        ];

        $easing = $settings['3d_animation_easing'];

        $c = 0;
        ob_start();
        foreach ($anims as $key => $anim) {

            if ($anim) {
                $c++;
                echo '"anim_' . $c . '": {
                    "type" :"' . $key . '",
                    "direction" : "' . $anim . '",
                    "rotateDir" : "' . $settings['object_anim_rotation_x'] . $settings['object_anim_rotation_y'] . $settings['object_anim_rotation_z'] . '",
                    "duration" : ' . $settings['3d_duration'] . ',
                    "delay" : ' . $settings['3d_delay'] . ',
                    "repeat"  : ' . ($settings['3d_repeat'] === "true" ? "true" : '""') . ',
                    "easing" : "' . $easing . '",
                    "pinTarget" : "' . $settings['3d_pinned_target'] . '",
                    "scrollRepeat" : ' . ($settings['reveal_repeat'] === "true" ? "true" : '""') . ',
                    "eventsTarget" : "' . $settings['mouse_events_target'] . '",
                    "speedUpScroll" : ' . ($settings['speed_up_on_scroll'] === "true" ? "true" : '""') . ',
                    "rotateOn" : "' . $settings['object_anim_rotate_on'] . '",
                    "scroll" : {
                      "trigger" : null,
                      "scrub"  : ' . ($settings['3d_scrub'] === "true" ? "true" : '""') . ',
                      "pin"  : ' . ($settings['3d_pin'] === "true" ? "true" : '""') . ',
                      "start" : "' . $settings['3d_item_ref_start'] . ' ' . $settings['3d_window_ref_start'] . '",
                      "end" : "' . $settings['3d_item_ref_end'] . ' ' . $settings['3d_window_ref_end'] . '",
                      "markers"  : ' . ($settings['show_markers'] === "true" ? "true" : '""') . '
                    }
                }';

                if (count(array_filter($anims)) !== $c) {
                    echo ',';
                }
                ;

            }

        }
        $animations = ob_get_clean();
        // echo '<pre>';
        // echo $animations;
        // echo '<pre>';




        $rendererSettings = '{
      		"scene": {
                "background" : ' . ($settings['scene_background'] === "true" ? "true" : '""') . ',
                "orbitControls" : ' . ($settings['orbit_controls'] === "true" ? "true" : '""') . ',
                "transformControls" : ' . ($settings['transform_controls'] === "true" ? "true" : '""') . '
              },
           "animations" : { 
            ' . $animations . '
           },
            "MixerAnimation" : { },
            "sequenceBuilder" :' . ($settings['scroll_sequence'] === "true" && $settings['edit_mode'] === "true" ? "true" : '""') . ',
            "editMode"  : ' . ($settings['edit_mode'] === "true" && $settings['scroll_sequence'] !== "true" ? "true" : '""') . '
            
          }';

        $customJSON = !empty($settings['custom_json']) ? $settings['custom_json'] : "null";
        $sequenceJSON = !empty($settings['sequence_json']) ? $settings['sequence_json'] : "null";


        ?>

        <div data-scroll-sequence='<?php echo $sequenceJSON ?>' data-hdr="<?php echo $hdr ?>"
            data-object="<?php echo esc_attr($file) ?>" data-settings='<?php echo $rendererSettings ?>'
            data-custom-json='<?php echo $customJSON ?>' class="pe--3d--renderer">
            <div class="renderer--container">

            </div>
        </div>



    <?php }

}
