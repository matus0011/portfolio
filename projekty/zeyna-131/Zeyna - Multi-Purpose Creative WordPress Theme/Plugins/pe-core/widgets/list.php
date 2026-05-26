<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeList extends Widget_Base
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
        return 'pelist';
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
        return __('List', 'pe-core');
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
        return 'eicon-bullet-list pe-widget';
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


        $registered = wp_get_nav_menus();
        $menus = [];

        if ($registered) {
            foreach ($registered as $menu) {

                $name = $menu->name;
                $id = $menu->term_id;

                $menus[$name] = $name;

            }
        }

        // Tab Title Control
        $this->start_controls_section(
            'section_tab_title',
            [
                'label' => __('List', 'pe-core'),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'item_type',
            [
                'label' => esc_html__('Item Type', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'icon' => esc_html__('Icon', 'pe-core'),
                    'text' => esc_html__('Text', 'pe-core'),
                ],
            ]
        );

        $repeater->add_control(
            'sub_items_before',
            [
                'label' => __('Sub Texts (Before)', 'plugin-name'),
                'type' => 'nested_repeater',

            ]
        );

        $repeater->add_control(
            'list_text',
            [
                'label' => esc_html__('Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Lorem ipsum dolor sit amet.', 'pe-core'),
                'label_block' => true,
                'condition' => ['item_type' => 'text']
            ]
        );

        $repeater->add_control(
            'list_icon',
            [
                'label' => esc_html__('Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => ['item_type' => 'icon']
            ]
        );

        $repeater->add_control(
            'sub_items_after',
            [
                'label' => __('Sub Texts (After)', 'plugin-name'),
                'type' => 'nested_repeater',

            ]
        );


        $repeater->add_control(
            'linked',
            [
                'label' => esc_html__('Linked', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'yes',
                'deafult' => 'no'
            ]
        );

        $repeater->add_control(
            'select_page',
            [
                'label' => esc_html__('Link to Page / Post', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'condition' => ['linked' => 'yes'],
                'groups' => get_grouped_page_options(),
            ]
        );


        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'pe-core'),
                'type' => \Elementor\Controls_Manager::URL,
                'options' => ['url', 'is_external', 'nofollow'],
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                    // 'custom_attributes' => '',
                ],
                'label_block' => true,
                'condition' => [
                    'linked' => 'yes',
                ],
            ]
        );


        $repeater->add_control(
            'add_image',
            [
                'label' => esc_html__('Image?', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'yes',
                'deafult' => 'no'
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'pe-core'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'add_image' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'list',
            [
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'item_type' => 'text',
                        'list_text' => esc_html__('Lorem ipsum dolor sit amet...', 'pe-core'),
                    ],
                    [
                        'item_type' => 'text',
                        'list_text' => esc_html__('Lorem ipsum dolor sit amet...', 'pe-core'),
                    ],
                ],
                'title_field' => '{{{ list_text }}}',
            ]
        );

        $this->add_control(
            'list_type',
            [
                'label' => esc_html__('List Type', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'render_type' => 'template',
                'default' => 'list--type--classic',
                'prefix_class' => '',
                'options' => [
                    'list--type--classic' => esc_html__('Classic', 'pe-core'),
                    'list--type--cylinder' => esc_html__('Cylinder', 'pe-core'),
                    'list--type--circle' => esc_html__('Circle', 'pe-core'),
                ],
            ]
        );

        $this->add_responsive_control(
            'list_perspective',
            [
                'label' => esc_html__('List Perspective', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%', 'px', 'vw', 'custom'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'px' => [
                        'min' => 1000,
                        'max' => 10000,
                        'step' => 1,
                    ],
                    'vw' => [
                        'min' => 50,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--list' => 'perspective: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'list_type' => 'list--type--cylinder',
                ]
            ]
        );


        $this->add_responsive_control(
            'list_height',
            [
                'label' => esc_html__('List Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%', 'px', 'vh', 'custom'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'px' => [
                        'min' => 1000,
                        'max' => 10000,
                        'step' => 1,
                    ],
                    'vw' => [
                        'min' => 50,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--list' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'list_type' => 'list--type--cylinder',
                ]
            ]
        );

        $this->add_control(
            'cylinder_pin_target',
            [
                'label' => esc_html__('Pin Target', 'pe-core'),
                'placeholder' => esc_html__('Eg. #servicesContainer', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => esc_html__('Normally the container pin itself but in some cases, a custom trigger may required.', 'pe-core'),
                'ai' => false,
                'condition' => [
                    'list_type' => 'list--type--cylinder',
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'list_images',
                'exclude' => [],
                'include' => [],
                'default' => 'medium',
            ]
        );


        $this->add_control(
            'list_style',
            [
                'label' => esc_html__('List Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'render_type' => 'template',
                'default' => 'none',
                'prefix_class' => 'list--style--',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'disc' => esc_html__('Disc', 'pe-core'),
                    'decimal' => esc_html__('Decimal', ' pe-core'),
                    'custom' => esc_html__('Custom', 'pe-core'),
                ],
            ]
        );


        $this->add_control(
            'images_style',
            [
                'label' => esc_html__('Images Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'prefix_class' => 'images--style--',
                'options' => [
                    'default' => esc_html__('Default', 'pe-core'),
                    'static' => esc_html__('Static', 'pe-core'),
                    'follow-y' => esc_html__('Follow Mouse Y', 'pe-core'),
                ],
            ]
        );


        $this->add_responsive_control(
            'images_offset_x',
            [
                'label' => esc_html__('Images Offset Left', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.images--style--follow-y .pe--list--item--image' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'images_style' => 'follow-y',
                ]
            ]
        );

        $this->add_control(
            'list_style_icon',
            [
                'label' => esc_html__('Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'list_style' => 'custom'
                ],
            ]
        );

        $this->add_control(
            'items_seperator',
            [
                'label' => esc_html__('Seperator', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Eg: /', 'pe-core'),
                'selectors' => [
                    '{{WRAPPER}}  .pe--list ul li a::after ' => 'content: "{{VALUE}}";display: inline-block;margin-left: -1.5rem;',
                ],
            ]
        );


        $this->add_control(
            'hover_effect',
            [
                'label' => esc_html__('Hover Effect', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'render_type' => 'template',
                'prefix_class' => 'hover--',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'underline' => esc_html__('Underline', 'pe-core'),
                    'chars-up' => esc_html__('Chars Up', 'pe-core'),
                    'words-up' => esc_html__('Words Up', 'pe-core'),
                    'dot' => esc_html__('Dot', 'pe-core'),
                    'spacer' => esc_html__('Spacer', 'pe-core'),
                    'opacity' => esc_html__('Opacity', 'pe-core'),
                    'font-swap' => esc_html__('Font Swap', 'pe-core'),
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'items_hover_typography',
                'label' => esc_html__('Items Hover Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--list ul li >a:hover',
                'condition' => [
                    'hover_effect' => 'font-swap',
                    'hover_use_secondary_font!' => 'true'
                ],
            ]
        );


        $this->add_control(
            'hover_use_secondary_font',
            [
                'label' => esc_html__('Use Secondary Font at Hover', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pe--list ul li >a:hover' => '
                font-family: var(--sec_typo-font-family);
                font-size: var(--sec_typo-font-size);
                line-height: var(--sec_typo-line-height);
                letter-spacing: var(--sec_typo-letter-spacing);
                font-weight: var(--sec_typo-font-weight);
           text-transform: var(--sec_typo-text-transform);',
                ],
                'condition' => ['hover_effect' => 'font-swap'],
            ]
        );


        pe_icon_hover_settings($this);

        $this->end_controls_section();

        $this->start_controls_section(
            'list_styles',
            [
                'label' => __('List Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,

            ]
        );

        $this->add_control(
            'items_color',
            [
                'label' => esc_html__('Items Color', 'pe-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pe--list ul li:not(:has(a)) , {{WRAPPER}} .pe--list ul li:has(a) a' => '--mainColor: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'items_hover_color',
            [
                'label' => esc_html__('Items Color (Hover)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pe--list ul li:not(:has(a)):hover , {{WRAPPER}} .pe--list ul li:has(a) a:hover ' => 'color: {{VALUE}}',
                ]
            ]
        );


        $this->add_responsive_control(
            'items_size',
            [
                'label' => esc_html__('Items Size', 'pe-core'),
                'description' => esc_html__('This option will not change HTML tag of the element, this option only for typographic scaling.', 'pe-core'),
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
                'default' => 'text-p',
                'toggle' => false,
                'selectors' => [
                    '{{WRAPPER}} ul li' => 'font: var(--{{VALUE}});letter-spacing: var(--{{VALUE}}-letter-spacing)',
                ],
            ]
        );


        flexOptions($this, false, '.pe--list ul', 'list', 'List', false, '.pe--list ul li');




        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'items_typography',
                'label' => esc_html__('Items Typohraphy', 'pe-core'),
                'selector' => '{{WRAPPER}} ul > li',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'sub_texts_typography',
                'label' => esc_html__('Sub Texts Typohraphy', 'pe-core'),
                'selector' => '{{WRAPPER}} ul > li .list--sub--text',
            ]
        );


        $this->add_responsive_control(
            'list_images_width',
            [
                'label' => esc_html__('Images Width', 'pe-core'),
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
                    '{{WRAPPER}} .pe--list--item--image' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'list_images_height',
            [
                'label' => esc_html__('Images Height', 'pe-core'),
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
                    '{{WRAPPER}} .pe--list--item--image' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'images_z_index_boost',
            [
                'label' => esc_html__('Images Z Index Boost', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 99,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pe--list--item--image' => 'z-index: {{VALUE}} !important;',
                ],
            ]
        );


        $this->add_responsive_control(
            'underline_height',
            [
                'label' => esc_html__('Underline Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['em', 'px'],
                'range' => [
                    'px' => [
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
                    '{{WRAPPER}} .pe--list ul>li::after' => 'height: {{SIZE}}{{UNIT}};',

                ],
                'condition' => [
                    'hover_effect' => 'underline',
                ]
            ]
        );

        $this->add_responsive_control(
            'underline_width',
            [
                'label' => esc_html__('Underline Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['em', 'px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--list ul>li:hover::after' => 'width: {{SIZE}}{{UNIT}};',

                ],
                'condition' => [
                    'hover_effect' => 'underline',
                ]
            ]
        );

        $this->add_responsive_control(
            'underline_pos_y',
            [
                'label' => esc_html__('Underline Position', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['em', 'px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--list ul>li::after' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'hover_effect' => 'underline',
                ]
            ]
        );


        objectStyles($this, 'list_style_', 'List Icon', 'span.list--icon.pe--styled--object', false, false, false, false, false, true);

        $this->add_responsive_control(
            'list_icon_size',
            [
                'label' => esc_html__('List Icon Size', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['em', 'px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} span.list--icon.pe--styled--object' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        pe_cursor_settings($this);
        pe_text_animation_settings($this, true);

        $this->start_controls_section(
            'items_styles',
            [
                'label' => __('Items Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,

            ]
        );
        objectStyles($this, 'pe__list_item_', 'Items', '.pe--list--item.pe--styled--object', true, false, false);

        flexOptions($this, false, '.pe--list ul li:has(.list--sub--text , a) > a, {{WRAPPER}} .pe--list ul li:has(.list--sub--text)', 'pe__list_items', 'Items');

        $this->add_responsive_control(
            'sub_text_type',
            [
                'label' => esc_html__('Text Size', 'pe-core'),
                'description' => esc_html__('This option will not change HTML tag of the element, this option only for typographic scaling.', 'pe-core'),
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
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .list--sub--text' => 'font: var(--{{VALUE}});letter-spacing: var(--{{VALUE}}-letter-spacing)',
                ],
            ]
        );

        $this->add_responsive_control(
            'sub_paragraph_size',
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
                'condition' => ['sub_text_type' => 'text-p'],
                'selectors' => [
                    '{{WRAPPER}} .list--sub--text' => 'font: var(--text-{{VALUE}});letter-spacing: var(--text-{{VALUE}}-letter-spacing)',
                ],
            ]
        );

        $this->add_responsive_control(
            'sub_heading_size',
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
                'condition' => ['sub_text_type' => 'text-h1'],
                'selectors' => [
                    '{{WRAPPER}} .list--sub--text' => 'font: var(--text-{{VALUE}});letter-spacing: var(--text-{{VALUE}}-letter-spacing)',
                ],
            ]
        );
        objectStyles($this, 'sub_items', 'Sub Texts', '.pe--list ul li:has(.list--sub--text) span.list--sub--text', false, false, false, false, true, true);

        pe_background_hover_settings($this, '');

        $this->add_responsive_control(
            'list_style_gap',
            [
                'label' => esc_html__('List Style Gap', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--list ul li:has(.list--sub--text, a)>a, {{WRAPPER}}s .pe--list ul li:has(.list--sub--text)' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'list_style' => 'custom',
                ]
            ]
        );

        pe_icon_hover_settings($this, 'list_style', ['list_style' => 'custom']);


        $this->end_controls_section();

        $this->start_controls_section(
            'wrapper_Styles',
            [
                'label' => __('Wrapper Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['images_style' => 'static']

            ]
        );


        $this->add_responsive_control(
            'list_images_wrapper_width',
            [
                'label' => esc_html__('Images Wrapper Width', 'pe-core'),
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
                    '{{WRAPPER}} .pe--list .pe--list--images--wrap' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'list_images_wrapper_height',
            [
                'label' => esc_html__('Images Wrapper Height', 'pe-core'),
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
                    '{{WRAPPER}} .pe--list .pe--list--images--wrap' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'list_titles_wrapper_width',
            [
                'label' => esc_html__('Titles Wrapper Width', 'pe-core'),
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
                    '{{WRAPPER}} .pe--list:has(.pe--list--images--wrap) ul' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        flexOptions($this, false, '.pe--list:has(.pe--list--images--wrap)', 'static_wrap', 'Wrapper', false, false);

        objectAbsolutePositioning($this, '.pe--list .pe--list--images--wrap .pe--list--item--image', 'static_images', 'Images', ['images_style' => 'static']);
        $this->end_controls_section();





        pe_color_options($this);


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $cursor = pe_cursor($settings, $this);
        $imagesStyle = $settings['images_style'];

        $listIcon = '';

        if ($settings['list_style'] === 'custom') {

            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['list_style_icon'], ['aria-hidden' => 'true']);
            $listIcon = '<span class="list--icon pe--styled--object">' . pe_icon_hover($settings, ob_get_clean(), 'list_style') . '</span>';

        }

        $animation = pe_text_animation($this);

        $pinTarget = '';

        if ($settings['list_type'] === 'list--type--cylinder' && !empty($settings['cylinder_pin_target'])) {
            $pinTarget = ' data-pin-target="' . $settings['cylinder_pin_target'] . '"';
        }

        ?>

        <div <?php echo $animation . $pinTarget ?> class="pe--list text--anim--multi">

            <?php if ($imagesStyle === 'static') { ?>

                <div class="pe--list--images--wrap">

                    <?php foreach ($settings['list'] as $key => $item) { ?>

                        <div class="pe--list--item--image <?php echo 'image--' . $key ?>">

                            <?php echo wp_get_attachment_image($item['image']['id'], $settings['list_images_size']) ?>
                        </div>

                    <?php } ?>
                </div>
            <?php } ?>

            <ul class="pe--list--wrapper">
                <?php foreach ($settings['list'] as $key => $item) {
                    $object = '';
                    $key++;
                    if ($item['item_type'] === 'icon') {
                        ob_start();
                        \Elementor\Icons_Manager::render_icon($item['list_icon'], ['aria-hidden' => 'true']);
                        $object = ob_get_clean();

                    } else {
                        $object = '<div class="list--main">' . $item['list_text'] . '</div>';
                    }

                    if ($settings['list_style'] === 'decimal') {
                        $listIcon = '<span class="list--icon pe--styled--object">' . $key . '.</span>';

                    }

                    ?>
                    <li data-index="<?php echo $key ?>" <?php echo pe_background_hover($this, '') ?> class=" pe--list--item pe--hover--trigger
                        pe--styled--object text--anim--inner">
                        <?php

                        if ($item['add_image'] === 'yes' && ($imagesStyle === 'default' || $imagesStyle === 'follow-y')) { ?>

                            <div class="pe--list--item--image">

                                <?php echo wp_get_attachment_image($item['image']['id'], $settings['list_images_size']) ?>
                            </div>

                        <?php }

                        if (!empty($item['link']['url']) || $item['select_page']) {
                            $url = $item['select_page'] ? get_the_permalink($item['select_page']) : $item['link']['url'];
                            $target = $item['link']['is_external'] ? '_blank' : '_self';

                            echo '<a ' . $cursor . ' target="' . $target . '" href="' . $url . '">';
                        }

                        if (!empty($item['sub_items_before'])) {
                            $sub_items_before = $item['sub_items_before'];
                            foreach ($sub_items_before as $sub) {
                                echo '<span class="list--sub--text pe--styled--object">' . esc_html($sub) . '</span>';
                            }
                        }

                        if ($item['item_type'] === 'icon') {
                            echo $listIcon . pe_icon_hover($settings, $object);
                        } else {
                            echo $listIcon . $object;
                        }


                        if (!empty($item['sub_items_after'])) {
                            $sub_items_after = $item['sub_items_after'];
                            foreach ($sub_items_after as $sub) {
                                echo '<span class="list--sub--text pe--styled--object">' . esc_html($sub) . '</span>';
                            }
                        }
                        if (!empty($item['link']['url']) || $item['select_page']) {
                            $url = $item['select_page'] ? get_the_permalink($item['select_page']) : $item['link']['url'];
                            $target = $item['link']['is_external'] ? '_blank' : '_self';
                            echo '</a>';
                        }

                        ?>


                    </li>
                <?php } ?>
            </ul>


        </div>


    <?php }

}
