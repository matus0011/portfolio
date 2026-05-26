<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeImageGallery extends Widget_Base
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
        return 'peimagegallery';
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
        return __('Pe Image Gallery', 'pe-core');
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
        return 'eicon-posts-carousel pe-widget';
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
        return ['pe-showcase'];
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

        $registered = wp_get_nav_menus();
        $menus = [];

        if ($registered) {
            foreach ($registered as $menu) {

                $name = $menu->name;
                $id = $menu->term_id;

                $menus[$name] = $name;

            }
        }

        $this->start_controls_section(
            'section_project_title',
            [
                'label' => __('Settings', 'pe-core'),
            ]
        );

        $this->add_control(
            'image_gallery',
            [
                'label' => esc_html__('Image Gallery', 'pe-core'),
                'type' => \Elementor\Controls_Manager::GALLERY,
                'show_label' => false,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'image',
                'exclude' => [],
                'include' => [],
                'default' => 'medium_large',
            ]
        );


        $this->add_control(
            'gallery_type',
            [
                'label' => esc_html__('Gallery Type', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'grid',
                'render_type' => 'template',
                'prefix_class' => 'pe--gallery--',
                'options' => [
                    'grid' => esc_html__('Grid', 'pe-core'),
                    'slider' => esc_html__('Slider', 'pe-core'),
                ],

            ]
        );

        pe_slider_settings($this, false, ['gallery_type' => 'slider']);

        $this->add_responsive_control(
            'grid_columns',
            [
                'label' => esc_html__('Grid Columns', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 12,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--image--gallery--wrapper' => '--columns:{{SIZE}};'
                ],
                'condition' => ['gallery_type' => 'grid',]
            ]
        );


        $this->add_control(
            'parallax_images',
            [
                'label' => esc_html__('Parallax Images', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'parallax_images',
                'prefix_class' => '',
                'render_type' => 'template',
                'condition' => ['gallery_type' => 'grid',]
            ]
        );

        $this->add_control(
            'parallax_items',
            [
                'label' => esc_html__('Parallax Items', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'parallax_items',
                'prefix_class' => '',
                'render_type' => 'template',
                'condition' => ['gallery_type' => 'grid',]
            ]
        );

        $this->add_control(
            'parallax_from',
            [
                'label' => esc_html__('Parallax From', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'start',
                'render_type' => 'template',
                'prefix_class' => 'parallax--',
                'options' => [
                    'start' => esc_html__('Start', 'pe-core'),
                    'center' => esc_html__('Center', 'pe-core'),
                    'end' => esc_html__('End', 'pe-core'),
                    'random' => esc_html__('Random', 'pe-core'),
                ],
                'condition' => [
                    'parallax_items' => 'parallax_items',
                    'gallery_type' => 'grid'
                ]

            ]
        );

        $this->add_control(
            'parallax_strength',
            [
                'label' => esc_html__('Parallax Strength', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [''],
                'render_type' => 'template',
                'range' => [
                    '' => [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'parallax_items' => 'parallax_items',
                    'gallery_type' => 'grid'
                ]
            ]
        );

        $this->add_control(
            'randomized',
            [
                'label' => esc_html__('Randomized', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'random--items',
                'condition' => [
                    'gallery_type' => 'grid'
                ]
            ]
        );

        $this->add_control(
            'lightbox_gallery',
            [
                'label' => esc_html__('Lightbox Gallery', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'prefix_class' => 'ligbtbox--gallery--',
                'condition' => [
                    'gallery_type' => 'grid'
                ]
            ]
        );

        $this->add_control(
            'grid_spacings',
            [
                'label' => esc_html__('Add Spacings', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'gallery_type' => 'grid'
                ]
            ]
        );

        $spacingsRepeater = new \Elementor\Repeater();

        $spacingsRepeater->add_control(
            'spacing_col',
            [
                'label' => esc_html__('Spacing Column', 'pe-core'),
                'placeholder' => esc_html__('Eg. 2', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,

            ]
        );

        $spacingsRepeater->add_control(
            'spacing_row',
            [
                'label' => esc_html__('Spacing Row', 'pe-core'),
                'placeholder' => esc_html__('Eg. 5', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,
            ]
        );

        $this->add_control(
            'spacings',
            [
                'label' => esc_html__('Spacings', 'pe-core'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $spacingsRepeater->get_controls(),
                'show_label' => false,
                'title_field' => 'Spaced: {{{ spacing_col }}} / {{{ spacing_row }}}',
                'condition' => [
                    'grid_spacings' => 'yes',
                    'gallery_type' => 'grid'
                ],
            ]
        );

        $this->end_controls_section();

        pe_slider_style_settings($this, true, ['gallery_type' => 'slider']);
        zeynaLighboxOptions($this, 'gallery', ['lightbox_gallery' => 'yes']);

        $this->start_controls_section(
            'grid_animations',
            [
                'label' => esc_html__('Grid Animations', 'pe-core'),
                'condition' => [
                    'gallery_type' => 'grid'
                ]
            ]
        );


        $this->add_control(
            'select_build_type',
            [
                'label' => esc_html__('Select Build Type', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'slide-up' => esc_html__('Slide Up', 'pe-core'),
                    'slide-down' => esc_html__('Slide Down', 'pe-core'),
                    'slide-left' => esc_html__('Slide Left', 'pe-core'),
                    'slide-right' => esc_html__('Slide Right', 'pe-core'),
                    'scale-up' => esc_html__('Scale Up', 'pe-core'),
                    'fade' => esc_html__('Simple Fade', 'pe-core'),
                    '3d--scale--in' => esc_html__('3D Scale In', 'pe-core'),
                    '3d--scale--out' => esc_html__('3D Scale Out', 'pe-core'),
                    'rotate--up' => esc_html__('Rotate Up', 'pe-core')
                ],
                'default' => 'none',
                'prefix_class' => 'build_type_',
                'render_type' => 'template',
            ]
        );


        $this->add_control(
            'build_on_scroll_target',
            [
                'label' => esc_html__('Parent Target', 'pe-core'),
                'placeholder' => esc_html__('Eg. #worksContainer', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => esc_html__('A parent container is required to make it work.', 'pe-core'),
                'ai' => false,
                'prefix_class' => 'build_pin_container_',
                'condition' => [
                    'select_build_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'build_scrub',
            [
                'label' => esc_html__('Scrub', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => '',
                'condition' => [
                    'select_build_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'build_pin',
            [
                'label' => esc_html__('Pin', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => '',
                'condition' => [
                    'select_build_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'build_stagger_from',
            [
                'label' => esc_html__('Start From', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'start',
                'options' => [
                    'start' => esc_html__('Start', 'pe-core'),
                    'center' => esc_html__('Center', 'pe-core'),
                    'end' => esc_html__('End', 'pe-core'),
                ],
                'label_block' => false,
                'condition' => [
                    'select_build_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'build_stagger',
            [
                'label' => esc_html__('Stagger', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => -10,
                'max' => 10,
                'step' => 0.1,
                'default' => 0.5,
                'condition' => [
                    'select_build_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'build_speed',
            [
                'label' => esc_html__('Building Speed', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 20000,
                'step' => 100,
                'default' => 1000,
                'condition' => [
                    'select_build_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'build_duration',
            [
                'label' => esc_html__('Duration', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 60,
                'step' => 0.1,
                'default' => 3,
                'condition' => [
                    'select_build_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'build_start_references',
            [
                'label' => esc_html__('Start References', 'pe-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => [
                    'select_build_type!' => 'none',

                ],
            ]
        );

        $this->add_control(
            'build_references_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
                   This references below are adjusts the animation start/end positions on the screen. <b>For Example: If you select <u>'Top' for item reference point</u> and <u>'Bottom' for the window reference point</u>; animation will start when item's top edge enters the window's bottom edge.</b></div>",
                'condition' => [
                    'select_build_type!' => 'none',

                ],


            ]
        );

        $this->add_control(
            'build_item_ref_start',
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
                'default' => 'center',
                'toggle' => false,
                'condition' => [
                    'select_build_type!' => 'none',

                ],
            ]
        );

        $this->add_control(
            'build_window_ref_start',
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
                'default' => 'center',
                'toggle' => false,
                'condition' => [
                    'select_build_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'build_end_references',
            [
                'label' => esc_html__('End References', 'pe-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => [
                    'select_build_type!' => 'none',

                ],
            ]
        );

        $this->add_control(
            'build_end_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>For scrubbed/pinned animations only.</div>",
                'condition' => [
                    'select_build_type!' => 'none',

                ],
            ]
        );

        $this->add_control(
            'build_item_ref_end',
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
                    'select_build_type!' => 'none',

                ],
            ]
        );

        $this->add_control(
            'build_window_ref_end',
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
                    'select_build_type!' => 'none',

                ],
            ]
        );


        $this->end_controls_section();

        // pe_cursor_settings($this);
        pe_general_animation_settings($this);

        $this->start_controls_section(
            'style',
            [
                'label' => esc_html__('Grid Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'gallery_type' => 'grid'
                ]
            ]
        );

        $this->add_responsive_control(
            'align_items',
            [
                'label' => esc_html__('Align Items', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Start', 'pe-core'),
                        'icon' => 'eicon-align-start-v'
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'pe-core'),
                        'icon' => 'eicon-align-center-v'
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'pe-core'),
                        'icon' => 'eicon-align-end-v'
                    ],
                    'stretch' => [
                        'title' => esc_html__('Strecth', 'pe-core'),
                        'icon' => 'eicon-align-stretch-v'
                    ],
                ],
                'prefix_class' => 'align--items--',
                'selectors' => [
                    '{{WRAPPER}} .pe--image--gallery--wrapper' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'randomized!' => 'random--items',
                ]

            ]
        );

        $this->add_responsive_control(
            'justify_items',
            [
                'label' => esc_html__('Justify Items', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Start', 'pe-core'),
                        'icon' => 'eicon-align-start-h'
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'pe-core'),
                        'icon' => 'eicon-align-center-h'
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'pe-core'),
                        'icon' => 'eicon-align-end-h'
                    ],
                ],
                'prefix_class' => 'justify--items--',
                'selectors' => [
                    '{{WRAPPER}} .pe--image--gallery--wrapper' => 'justify-items: {{VALUE}};',
                ],
                'condition' => [
                    'randomized!' => 'random--items',
                ]
            ]
        );

        $this->add_responsive_control(
            'row_height',
            [
                'label' => esc_html__('Row Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', '%'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1
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
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--image--gallery--wrapper' => '--rowHeight: {{SIZE}}{{UNIT}}'
                ]
            ]
        );


        $this->add_responsive_control(
            '_columns_gap',
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
                    '{{WRAPPER}} .pe--image--gallery--wrapper' => '--column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_rows_gap',
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
                    '{{WRAPPER}} .pe--image--gallery--wrapper' => '--row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );





        $this->end_controls_section();

        $this->start_controls_section(
            'item_styles',
            [
                'label' => esc_html__('Item Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_responsive_control(
            'galery_',
            [
                'label' => esc_html__('Width', 'pe-core'),
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
                    '{{WRAPPER}} .pe--image--gallery--item' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'galery_items_height',
            [
                'label' => esc_html__('Height', 'pe-core'),
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
                    '{{WRAPPER}} .pe--image--gallery--item' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pe--image--gallery--item img' => 'height: 100%;object-fit: cover',
                ],
            ]
        );

        $this->add_responsive_control(
            'items_radius',
            [
                'label' => esc_html__('Border Radius', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .pe--image--gallery--item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'item_filters',
                'label' => esc_html__('Filter', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--image--gallery--item',

            ]
        );

        $this->add_control(
            'items_shadow',
            [
                'label' => esc_html__('Box Shadow', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'items--box--shadow',
                'default' => 'items--box--shadow',
                'prefix_class' => '',
            ]
        );


        $this->add_control(
            'box_shadow_popover',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__('Box Shadow', 'pe-core'),
                'label_off' => esc_html__('Default', 'pe-core'),
                'label_on' => esc_html__('Custom', 'pe-core'),
                'return_value' => 'galery--shadow',
                'prefix_class' => '',
                'condition' => [
                    'items_shadow' => 'items--box--shadow',
                ]
            ]
        );

        $this->add_control(
            'gallery_images_fit',
            [
                'label' => esc_html__('Images Fit', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover' => esc_html__('Cover', 'pe-core'),
                    'contain' => esc_html__('Contain', 'pe-core'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--slider--slide.swiper-slide .pe--image--gallery--item img' => 'object-fit: {{VALUE}};',
                ],

            ]
        );

        $this->start_popover();

        $this->add_control(
            'custom_box_shadow',
            [
                'label' => esc_html__('Box Shadow', 'pe-core'),
                'type' => \Elementor\Controls_Manager::BOX_SHADOW,
                'selectors' => [
                    '{{WRAPPER}}.galery--shadow .pe--image--gallery--item' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
                ],
            ]
        );

        $this->end_popover();


        $this->end_controls_section();

        zeynaLighboxStyles($this, 'gallery', ['lightbox_gallery' => 'yes']);
        pe_color_options($this);


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $columns = isset($settings['grid_columns']) ? $settings['grid_columns']['size'] : 3;
        $type = $settings['gallery_type'];


        $this->add_render_attribute(
            'gallery_attributes',
            [
                'data-parallax-strength' => $settings['parallax_strength'] ? $settings['parallax_strength']['size'] : false,

            ]
        );

        $this->add_render_attribute(
            'build_attributes',
            [
                'data-build-type' => $settings['select_build_type'],
                'data-build-target' => $settings['build_on_scroll_target'],
                'data-build-pin' => $settings['build_pin'],
                'data-build-duration' => $settings['build_duration'],
                'data-build-scrub' => $settings['build_scrub'],
                'data-build-stagger' => $settings['build_stagger'],
                'data-build-stagger-from' => $settings['build_stagger_from'],
                'data-build-start' => $settings['build_item_ref_start'] . ' ' . $settings['build_window_ref_start'],
                'data-build-end' => $settings['build_item_ref_end'] . '+=' . $settings['build_speed'] . ' ' . $settings['build_window_ref_end'],
            ]
        );

        if ($settings['select_build_type'] !== 'none') {
            $build = $this->get_render_attribute_string('build_attributes');
        } else {
            $build = '';
        }

        ?>





        <div <?php echo $this->get_render_attribute_string('gallery_attributes') . ' ' . $build ?> class="pe--image--gallery"
            data-columns="<?php echo esc_attr($columns) ?>">

            <?php if ($type === 'grid') { ?>

                <div class="pe--image--gallery--wrapper">
                    <?php foreach ($settings['image_gallery'] as $key => $image) {

                        if ($settings['randomized']) {
                            $pos = ['start', 'end', 'center'];
                            $rk1 = array_rand($pos);
                            $rk2 = array_rand($pos);
                            $selfPos = ';align-self:' . $pos[$rk1] . ';justify-self:' . $pos[$rk2] . ';';
                        } else {
                            $selfPos = '';
                        }
                        ?>

                        <div style="--i:<?php echo $key . $selfPos ?>" class="pe--image--gallery--item galler--item_<?php echo $key ?>"
                            data-index="<?php echo $key ?>">
                            <?php echo wp_get_attachment_image($image['id'], $settings['image_size']) ?>
                        </div>

                        <?php

                    }

                    if ($settings['spacings']) {

                        foreach ($settings['spacings'] as $span) {
                            echo '<span style="grid-area:' . $span['spacing_col'] . '/' . $span['spacing_row'] . '" class="grid-template-area elementor-repeater-item-' . $span['_id'] . '"></span>';
                        }

                    }

                    ?>
                </div>

                <?php if ($settings['lightbox_gallery'] === 'yes') {
                    zeynaLightBoxRender($this, 'gallery', false, $settings['image_gallery']);
                } ?>


            <?php } else if ($type === 'slider') {

                $arr = [];

                foreach ($settings['image_gallery'] as $key => $image) {

                    ob_start();
                    ?>
                        <div style="--i:<?php echo $key ?>" class="pe--image--gallery--item galler--item_<?php echo $key ?>"
                            data-index="<?php echo $key ?>">
                        <?php echo wp_get_attachment_image($image['id'], $settings['image_size']) ?>
                        </div>
                        <?php

                        $html = ob_get_clean();
                        $arr[] = $html;

                }

                echo pe_slider_render($this, $arr);

            } ?>



        </div>

    <?php }



}
