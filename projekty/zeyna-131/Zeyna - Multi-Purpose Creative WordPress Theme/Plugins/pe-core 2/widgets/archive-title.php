<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeArchiveTitle extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *s
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'pearchivetitle';
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
        return __('Archive Title', 'pe-core');
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
        return 'eicon-site-title pe-widget';
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
            'section_tab_title',
            [
                'label' => __('Archive Title', 'pe-core'),
            ]
        );

        flexOptions($this, false, '.pe--archive--title', 'title', 'Title');


        $this->add_control(
            'show_archive_desc',
            [
                'label' => esc_html__('Description', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => 'true',
                'default' => '',

            ]
        );


        $this->add_control(
            'heading_tag',
            [
                'label' => esc_html__('Tag', 'pe-core'),
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
                    ]

                ],
                'default' => 'h6',
                'toggle' => true,

            ]
        );


        $this->add_control(
            'paragraph_size',
            [
                'label' => esc_html__('Paragraph Size', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Normal', 'pe-core'),
                    'p-small' => esc_html__('Small', 'pe-core'),
                    'p-large' => esc_html__('Large', 'pe-core'),

                ],
                'label_block' => true,
                'condition' => ['text_type' => 'text-p'],
            ]
        );

        $this->add_control(
            'heading_size',
            [
                'label' => esc_html__('Heading Size', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Normal', 'pe-core'),
                    'md-title' => esc_html__('Medium', 'pe-core'),
                    'big-title' => esc_html__('Large', 'pe-core'),

                ],
                'label_block' => true,
                'condition' => ['text_type' => 'text-h1'],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => esc_html__('Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .text-wrapper p , {{WRAPPER}} .text-wrapper > *',
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label' => esc_html__('Use Secondary Color', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'use--sec--color',
                'default' => '',

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
                    '{{WRAPPER}} .text-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_align_last',
            [
                'label' => esc_html__('Justify Last Line?', 'pe-core'),
                'description' => esc_html__('On mobile screens "br" tags will be removed.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'justify-last',
                'default' => false,
                'condition' => ['alignment' => 'justify'],
            ]
        );


        $this->add_responsive_control(
            'width',
            [
                'label' => esc_html__('Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
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
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'remove_breaks',
            [
                'label' => esc_html__('Remove Breaks on Mobile', 'pe-core'),
                'description' => esc_html__('On mobile screens "br" tags will be removed.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'hide-br-mobile',
                'default' => '',

            ]
        );


        $this->add_control(
            'remove_margins',
            [
                'label' => esc_html__('Remove Margins', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'no-margin',
                'default' => '',

            ]
        );


        $this->add_responsive_control(
            'text-indent',
            [
                'label' => esc_html__('Text Indent', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .text-wrapper' => '--indent: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'text_stroke',
            [
                'label' => esc_html__('Text Stroke', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'yes',
                'prefix_class' => 'text-stroke-',
                'default' => 'no',
            ]
        );


        $this->end_controls_section();


        pe_text_animation_settings($this);

        objectStyles($this, 'text_wrapper_', 'Text', '.text-wrapper', false);

        pe_color_options($this);


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $text = $settings['text_wrapper'];


        ?>

        <div class="pe--archive--title">
            <div class="text-wrapper archive--title">
                <?php the_archive_title('<' . $settings['heading_tag'] . ' class="page-title" ' . pe_text_animation($this) . '>', '</' . $settings['heading_tag'] . '>'); ?>
            </div>

            <?php if ($settings['show_archive_desc'] === 'true') { ?>
                <div class="text-wrapper archive--desc">
                    <?php
                    if (!\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                        the_archive_description();
                    } else {
                        echo 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec aliquam magna a augue vehicula, in placerat orci tincidunt. Nullam aliquam, arcu hendrerit placerat pretium, nisl sem lobortis augue, eget dapibus massa nulla in tellus. In posuere nunc in ligula dictum pharetra. Praesent ac tempus eros.';
                    }
                    ?>
                </div>

            <?php } ?>

        </div>




        <?php
    }

}
