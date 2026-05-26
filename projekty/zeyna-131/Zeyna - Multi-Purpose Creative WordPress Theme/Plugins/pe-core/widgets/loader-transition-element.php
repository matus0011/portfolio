<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeLoaderTransitionElement extends Widget_Base
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
        return 'peloadertransitionelement';
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
        return __('Loader/Transition Element', 'pe-core');
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
        return 'eicon-loading pe-widget pe--lt--widget';
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


        // if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
        //     $document = \Elementor\Plugin::$instance->documents->get_current();
        //     $id = \Elementor\Plugin::$instance->documents->get_current()->get_id();

        //     if ( $document ) {
        //         $template_type = $document->get_type(); // örn: 'header', 'page', 'section'

        //         if ( $template_type === 'pe-loader-transitions' ) {
        //             $this->add_control(
        //                 'my_control_id',
        //                 [
        //                     'label' => __( 'My Control', 'plugin-name' ),
        //                     'type' => \Elementor\Controls_Manager::TEXT,
        //                 ]
        //             );
        //         }
        //     }
        // }




        // Tab Title Control
        $this->start_controls_section(
            'section_tab_title',
            [
                'label' => __('Loader/Transition Element', 'pe-core'),
            ]
        );

        $this->add_control(
            'used_for',
            [
                'label' => esc_html__('Used For', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'render_type' => 'template',
                'default' => 'loader',
                'prefix_class' => 'used--for--',
                'options' => [
                    'loader' => esc_html__('Loader', 'pe-core'),
                    'transitions' => esc_html__('Transitions', 'pe-core'),
                ]
            ]
        );

        $this->add_control(
            'element_type',
            [
                'label' => esc_html__('Element', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'render_type' => 'template',
                'default' => 'caption',
                'prefix_class' => 'element--',
                'options' => [
                    'caption' => esc_html__('Caption', 'pe-core'),
                    'counter' => esc_html__('Counter', 'pe-core'),
                    'logo' => esc_html__('Logo', 'pe-core'),
                    'progressbar' => esc_html__('Progressbar', 'pe-core'),
                ]
            ]
        );

        $this->add_control(
            'logo_style',
            [
                'label' => esc_html__('Logo Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'render_type' => 'template',
                'default' => 'fill',
                'prefix_class' => 'logo--',
                'options' => [
                    'fill' => esc_html__('Fill', 'pe-core'),
                    'draw' => esc_html__('Draw', 'pe-core'),
                ],
                'condition' => [
                    'element_type' => 'logo',
                ],
            ]
        );

        $this->add_control(
            'svg_logo',
            [
                'label' => esc_html__('SVG', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'element_type' => 'logo',
                    'logo_style' => 'draw',
                ],
            ]
        );

        $this->add_control(
            'counter_style',
            [
                'label' => esc_html__('Counter Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'render_type' => 'template',
                'default' => 'simple',
                'prefix_class' => 'counter--',
                'options' => [
                    'simple' => esc_html__('Simple', 'pe-core'),
                    'animated' => esc_html__('Animated', 'pe-core'),
                ],
                'condition' => [
                    'element_type' => 'counter',

                ],
            ]
        );

        $this->add_control(
            'loader_caption',
            [
                'label' => esc_html__('Caption', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('Loading...', 'pe-core'),
                'ai' => false,
                'condition' => [
                    'element_type' => 'caption',
                    'caption_style!' => 'repeater',
                ],
            ]
        );

        $this->add_control(
            'caption_style',
            [
                'label' => esc_html__('Caption Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'render_type' => 'template',
                'default' => 'simple',
                'prefix_class' => 'caption--style--',
                'options' => [
                    'simple' => esc_html__('Simple', 'pe-core'),
                    'marquee' => esc_html__('Marquee', 'pe-core'),
                    'repeater' => esc_html__('Repeater', 'pe-core'),
                ],
                'condition' => [
                    'element_type' => 'caption',
                ],
            ]
        );

        $captionsRepeater = new \Elementor\Repeater();

        $captionsRepeater->add_control(
            'repeater_caption',
            [
                'label' => __('Caption', 'pe-core '),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'captions_repeater',
            [
                'label' => esc_html__('Captions', 'pe-core'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $captionsRepeater->get_controls(),
                'show_label' => false,
                'condition' => [
                    'element_type' => 'caption',
                    'caption_style' => 'repeater',
                ],
                'title_field' => '{{{ repeater_caption }}}',
                'default' => [
                    [
                        'repeater_caption' => esc_html__('Loading', 'pe-core'),
                    ],
                    [
                        'repeater_caption' => esc_html__('Please', 'pe-core'),
                    ],
                    [
                        'repeater_caption' => esc_html__('Wait', 'pe-core'),
                    ],

                ],
            ]
        );


        $this->add_control(
            'caption_animation',
            [
                'label' => esc_html__('Caption Animation', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'render_type' => 'template',
                'default' => 'none',
                'prefix_class' => 'caption--animation--',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'fill' => esc_html__('Fill', 'pe-core'),
                    'chars' => esc_html__('Chars', 'pe-core'),
                    'words' => esc_html__('Words', 'pe-core'),
                ],
                'condition' => [
                    'element_type' => 'caption',
                    'caption_style' => 'simple',
                ],
            ]
        );


        $this->add_control(
            'loader_image',
            [
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'element_type' => 'logo',
                    'logo_style' => 'fill',
                ],

            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'image_size',
                'exclude' => [],
                'include' => [],
                'default' => 'full',
                'condition' => [
                    'element_type' => 'logo',
                ],
            ]
        );


        $this->add_responsive_control(
            'logo_width',
            [
                'label' => esc_html__('Logo Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'vw', 'rem', 'custom'],
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
                    '{{WRAPPER}} .pe--lt--element.element--logo' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'element_type' => 'logo',
                ],
            ]
        );



        $this->add_control(
            'loading_direction',
            [
                'label' => esc_html__('Direction', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'horizontal' => [
                        'title' => esc_html__('Horizontal', 'pe-core'),
                        'icon' => ' eicon-arrow-right',
                    ],
                    'vertical' => [
                        'title' => esc_html__('Vertical', 'pe-core'),
                        'icon' => ' eicon-arrow-up',
                    ],
                ],
                'default' => 'horizontal',
                'render_type' => 'template',
                'prefix_class' => 'logo--direction--',
                'toggle' => false,
                'condition' => [
                    'element_type' => 'logo',
                ],
            ]
        );

        $this->add_control(
            'progressbar_direction',
            [
                'label' => esc_html__('Progressbar Direction', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'horizontal' => [
                        'title' => esc_html__('Horizontal', 'pe-core'),
                        'icon' => ' eicon-arrow-right',
                    ],
                    'vertical' => [
                        'title' => esc_html__('Vertical', 'pe-core'),
                        'icon' => ' eicon-arrow-up',
                    ],
                    'circle' => [
                        'title' => esc_html__('Circle', 'pe-core'),
                        'icon' => ' eicon-circle-o',
                    ],
                ],
                'default' => 'horizontal',
                'render_type' => 'template',
                'prefix_class' => 'progressbar--direction--',
                'toggle' => false,
                'condition' => [
                    'element_type' => 'progressbar',
                ],
            ]
        );

        $this->add_control(
            'display_progress',
            [
                'label' => esc_html__('Display Progress', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => [
                    'element_type' => 'progressbar',
                    'progressbar_direction!' => 'circles',
                ],
            ]
        );

        $this->add_responsive_control(
            'progressbar_width',
            [
                'label' => esc_html__('Progressbar Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'vw', 'rem', 'custom'],
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
                    '{{WRAPPER}} .loader--progress--bar' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'element_type' => 'progressbar',
                    'progressbar_direction!' => 'circle'
                ],
            ]
        );

        $this->add_responsive_control(
            'progressbar_height',
            [
                'label' => esc_html__('Progressbar Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'vh', 'rem', 'custom'],
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
                    '{{WRAPPER}} .loader--progress--bar' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'element_type' => 'progressbar',
                    'progressbar_direction!' => 'circle'
                ],
            ]
        );

        $this->add_responsive_control(
            'progress_circle_size',
            [
                'label' => esc_html__('Size', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'vw', 'rem', 'custom'],
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
                    '{{WRAPPER}} .loader--progress--bar:has(svg)' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'element_type' => 'progressbar',
                    'progressbar_direction' => 'circle'
                ],
            ]
        );

        $this->add_control(
            'show_percentage',
            [
                'label' => esc_html__('Percentage', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => [
                    'element_type' => 'counter',
                ],
            ]
        );

        $this->add_responsive_control(
            'alignment',
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
                    'justify' => [
                        'title' => esc_html__('Justify', 'pe-core'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => is_rtl() ? 'right' : 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .pe--lt--element' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_type',
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
                'toggle' => false,
                'condition' => [
                    'element_type' => 'counter',
                ],
                'selectors' => [
                    '{{WRAPPER}} .loader--count' => 'font: var(--{{VALUE}});letter-spacing: var(--{{VALUE}}-letter-spacing)',
                ],
            ]
        );
        $this->add_responsive_control(
            'heading_size',
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
                'condition' => ['text_type' => 'text-h1', 'element_type' => 'counter',],
                'selectors' => [
                    '{{WRAPPER}} .loader--count' => 'font: var(--text-{{VALUE}});letter-spacing: var(--text-{{VALUE}}-letter-spacing)',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'counter_typography',
                'label' => esc_html__('Counter Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .loader--counter',
                'condition' => [
                    'element_type' => 'counter',
                ],
            ]
        );

        $this->add_responsive_control(
            'caption_text_type',
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
                    '{{WRAPPER}} .element--caption' => 'font: var(--{{VALUE}});letter-spacing: var(--{{VALUE}}-letter-spacing)',
                ],
            ]
        );

        $this->add_responsive_control(
            'caption_paragraph_size',
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
                    '{{WRAPPER}} .element--caption' => 'font: var(--text-{{VALUE}});letter-spacing: var(--text-{{VALUE}}-letter-spacing)',
                ],
            ]
        );

        $this->add_responsive_control(
            'caption_heading_size',
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
                'condition' => ['tsub_ext_type' => 'text-h1'],
                'selectors' => [
                    '{{WRAPPER}} .element--caption' => 'font: var(--text-{{VALUE}});letter-spacing: var(--text-{{VALUE}}-letter-spacing)',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'caption_typography',
                'label' => esc_html__('Caption Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .element--caption',
                'condition' => [
                    'element_type' => 'caption',
                ],
            ]
        );




        $this->end_controls_section();


        $this->start_controls_section(
            'animations',
            [
                'label' => __('Animations', 'pe-core'),
            ]
        );

        $this->add_control(
            'intro_animation',
            [
                'label' => esc_html__('Intro Animation', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'render_type' => 'template',
                'prefix_class' => 'intro--',
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'fade fade_in' => esc_html__('Fade In', 'pe-core'),
                    'fade fade_up' => esc_html__('Fade Up', 'pe-core'),
                    'fade fade_down' => esc_html__('Fade Down', 'pe-core'),
                    'fade fade_left' => esc_html__('Fade Left', 'pe-core'),
                    'fade fade_right' => esc_html__('Fade Right', 'pe-core'),
                    'slide slide_up' => esc_html__('Slide Up', 'pe-core'),
                    'slide slide_down' => esc_html__('Slide Down', 'pe-core'),
                    'slide slide_right' => esc_html__('Slide Right', 'pe-core'),
                    'slide slide_left' => esc_html__('Slide Left', 'pe-core'),
                    'block block_up' => esc_html__('Block Up', 'pe-core'),
                    'block block_down' => esc_html__('Block Down', 'pe-core'),
                ],
            ]
        );

        $this->add_control(
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
            ]
        );

        $this->end_controls_section();

        pe_color_options($this);


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();


        $listIcon = '';

        $type = $settings['element_type'];
        ?>

        <div class="pe--lt--element element--<?php echo $type ?>">

            <?php if ($type === 'counter') { ?>

                <div class="loader--counter">

                    <?php if ($settings['counter_style'] === 'simple') {

                        echo '<span class="loader--count">0</span>';
                        echo $settings['show_percentage'] === 'true' ? '<span class="counter--percentage">%</span>' : '';


                    } else if ($settings['counter_style'] === 'animated') {

                        echo '<span class="count--chars count--char--unit"><span>1</span></span>';

                        echo '<span class="count--chars count--char--1">';
                        for ($c = 0; $c < 10; $c++) {

                            echo '<span>' . $c . '</span>';
                            echo $c == 9 ? '<span>0</span>' : '';

                        }
                        echo '</span>';

                        echo '<span class="count--chars count--char--2">';
                        for ($c = 0; $c < 10; $c++) {

                            echo '<span>' . $c . '</span>';
                            echo $c == 9 ? '<span>0</span>' : '';

                        }
                        echo '</span>';
                        echo $settings['show_percentage'] === 'true' ? '<span class="counter--percentage"s>%</span>' : '';



                    } ?>

                </div>


            <?php } else if ($type === 'logo') {

                if ($settings['logo_style'] === 'fill') {

                    echo '<div class="loader--logo">' . \Elementor\Group_Control_Image_Size::get_attachment_image_html($settings, 'image_size', 'loader_image') . '</div>';

                    echo '<div class="loader--logo loader--logo--clone">' . \Elementor\Group_Control_Image_Size::get_attachment_image_html($settings, 'image_size', 'loader_image') . '</div>';


                } else {
                    ob_start();
                    \Elementor\Icons_Manager::render_icon($settings['svg_logo'], ['aria-hidden' => 'true']);
                    $icon = ob_get_clean();

                    echo '<div class="loader--svg--logo">' . $icon . '</div>';
                }



            } else if ($type === 'caption') {

                $caption_style = $settings['caption_style'];
                $caption = $settings['loader_caption'];

                if ($caption_style === 'simple') {
                    $animation = $settings['caption_animation'];

                    echo '<div class="loader--caption">' . $settings['loader_caption'] . '</div>';

                    if ($animation === 'fill' || $animation === 'chars' || $animation === 'words') {

                        echo '<div class="loader--caption caption--clone">' . $settings['loader_caption'] . '</div>';
                    }


                } else if ($caption_style === 'marquee') {
                    echo '<div class="loader--caption">'; ?>
                                <div class="pb--marquee no-button">
                                    <div class="pt--element--wrap">
                                        <div class="pb--marquee--wrap right-to-left" aria-hidden="true">
                                            <div class="pb--marquee__inner">
                                                <span><?php echo esc_html($caption) ?><i aria-hidden="true"
                                                        class="material-icons md-fiber_manual_record" LOADING, PLEASE WAIT.
                                                        data-md-icon="fiber_manual_record"></i></span>
                                                <span><?php echo esc_html($caption) ?><i aria-hidden="true"
                                                        class="material-icons md-fiber_manual_record" data-md-icon="fiber_manual_record"></i></span>
                                                <span><?php echo esc_html($caption) ?><i aria-hidden="true"
                                                        class="material-icons md-fiber_manual_record" data-md-icon="fiber_manual_record"></i></span>
                                                <span><?php echo esc_html($caption) ?><i aria-hidden="true"
                                                        class="material-icons md-fiber_manual_record" data-md-icon="fiber_manual_record"></i></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                    <?php echo '</div>';


                } else if ($caption_style === 'repeater') {

                    echo '<div class="loader--caption">
                    <div class="caption--repeater--wrap"><div class="capt--repeater--inner">';

                    $texts = $settings['captions_repeater'];

                    foreach ($texts as $text) {
                        echo '<span class="caption--repeater--text">' . $text['repeater_caption'] . '</span>';
                    }
                    echo '<span class="caption--repeater--text">' . $texts[0]['repeater_caption'] . '</span>';
                    ?>

                    <?php echo '</div></div></div>';


                }

            } else if ($type === 'progressbar') {

                if ($settings['progressbar_direction'] === 'circle') { ?>

                                <div class="loader--progress--bar">
                                    <svg height="100%" width="100%" viewbox="-1 -1 101 102">
                                        <circle class="main-circle" cx="50" cy="50" r="50" />
                                    </svg>
                                    <svg class="fill" height="100%" width="100%" viewbox="-1 -1 101 102">
                                        <circle class="main-circle" cx="50" cy="50" r="50" />
                                    </svg>
                                </div>

                <?php } else { ?>

                                <div class="loader--progress--bar">

                        <?php if ($settings['display_progress'] === 'true') {
                            echo '<span class="loader--progress-count"><span class="lpc-count">00</span>%</span>';
                        } ?>

                                    <span class="loader-progress"></span>
                                    <span class="loader-progress progress--fill"></span>

                                </div>


                <?php } ?>


            <?php } ?>


        </div>


    <?php }

}