<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeInfoBox extends Widget_Base
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
        return 'peinfobox';
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
        return __('Info Box', 'pe-core');
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
        return 'eicon-info-box pe-widget';
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
                'label' => __('Info Box', 'pe-core'),
            ]
        );

        $this->add_control(
            'info_box_title',
            [
                'label' => esc_html__('Info Box Title', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => esc_html__('Write title here', 'pe-core'),
                'default' => esc_html__('Lorem ipsum .', 'pe-core'),
                'description' => esc_html__('Leave it empty if you do not want to display title', 'pe-core'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'info_box_text',
            [
                'label' => esc_html__('Info Box Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'label_block' => true,
                'placeholder' => esc_html__('Write your text here', 'pe-core'),
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla consequat egestas nisi. Vestibulum malesuada fermentum nibh. Donec venenatis, neque et pellentesque efficitur, lectus est preti.', 'pe-core'),
                'description' => esc_html__('Leave it empty if you do not want to display text', 'pe-core'),
                'rows' => 5,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'info_box_interest_type',
            [
                'label' => esc_html__('Info Box Interest Type', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'image' => esc_html__('Image', 'pe-core'),
                    'video' => esc_html__('Video', 'pe-core'),
                    'icon' => esc_html__('Icon', 'pe-core'),
                ],

            ]
        );

        $this->add_control(
            'info_box_icon',
            [
                'label' => esc_html__('Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'description' => esc_html__('Leave it empty if you do not want to display icon', 'pe-core'),
                'condition' => ['info_box_interest_type' => 'icon'],
            ]
        );

        $this->add_control(
            'info_box_image',
            [
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => ['info_box_interest_type' => 'image'],
            ]
        );


        pe_video_settings($this, 'info_box_interest_type', 'video');


        $this->end_controls_section();

        $this->start_controls_section(
            'styles',
            [

                'label' => esc_html__('Box Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_responsive_control(
            'texts_align',
            [
                'label' => esc_html__('Text Align', 'pe-core'),
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
                    '{{WRAPPER}} .pe--info--box' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'interest_order',
            [
                'label' => esc_html__('Interest Order', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => -10,
                'max' => 10,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .pe--infobox--interest' => 'order: {{SIZE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_order',
            [
                'label' => esc_html__('Title  Order', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => -10,
                'max' => 10,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .pe--infobox--title' => 'order: {{SIZE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_order',
            [
                'label' => esc_html__('Content Order', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => -10,
                'max' => 10,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .pe--infobox--content' => 'order: {{SIZE}};',
                ],

            ]
        );


        $this->add_control(
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
                'toggle' => true,
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Title Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--infobox--title p',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => esc_html__('Text Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--infobox--content p',
            ]
        );

        $this->add_responsive_control(
            'icon_size',
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
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--infobox--interest.interest--icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'texts_spacings',
            [
                'label' => esc_html__('Texts Spacing', 'pe-core'),
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
                    '{{WRAPPER}} .pe--infobox--wrap' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'texts_width',
            [
                'label' => esc_html__('Texts Width', 'pe-core'),
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
                    '{{WRAPPER}} .pe--infobox--wrap' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        objectStyles($this, 'box_', 'Box', '.pe--info--box--wrapper.pe--styled--object', false, false, false, false, true);

        flexOptions($this, false, '.pe--info--box .pe--info--box--wrapper', 'box_flex_', 'Box ', true, false);


        $this->end_controls_section();

        $this->start_controls_section(
            'interest_styles',
            [

                'label' => esc_html__('Interest Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['info_box_interest_type!' => 'none'],
            ]
        );

        objectStyles($this, 'interest_', 'Interest', '.pe--infobox--interest.pe--styled--object', false, false, false, false, true);
        objectAbsolutePositioning($this, '.pe--infobox--interest', 'interest_pos_', 'Interest');

        $this->add_control(
            'svg_fill',
            [
                'label' => esc_html__('Fill with site colors? (SVG)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'icon--fill--yes',
                'default' => '',
                'prefix_class' => '',
                'condition' => ['info_box_interest_type' => ['icon']],
            ]
        );



        $this->end_controls_section();

        pe_text_animation_settings($this);
        pe_color_options($this);


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $text = $settings['info_box_text'];

        $tag = 'p';
        if (!empty($settings['info_box_link']['url'])) {
            $this->add_link_attributes('info_box_link', $settings['info_box_link']);
        }


        $interestType = $settings['info_box_interest_type'];
        $animation = pe_text_animation($this)
            ?>

        <div class="pe--info--box">

            <div class="pe--info--box--wrapper pe--styled--object">

                <?php if ($interestType !== 'none') { ?>
                    <div class="pe--infobox--interest pe--styled--object interest--<?php echo $interestType ?>">
                        <?php if ($interestType === 'icon') {
                            ob_start();
                            \Elementor\Icons_Manager::render_icon($settings['info_box_icon'], ['aria-hidden' => 'true']);
                            $icon = ob_get_clean();
                            echo $icon;
                        } else if ($interestType === 'video') {
                            echo pe_video_render($this, false);
                        } else if ($interestType === 'image') {

                            $alt = isset($settings['info_box_image']['alt']) ? 'alt="' . $settings['info_box_image']['alt'] . '"' : '';
                            echo '<img ' . $alt . ' src="' . $settings['info_box_image']['url'] . '">';
                        } ?>
                    </div>
                <?php } ?>


                <div class="pe--infobox--wrap">

                    <?php if ($settings['info_box_title']) { ?>
                        <div class="pe--infobox--title">
                            <?php echo '<' . $settings['title_tag'] . ' . ' . $animation . ' >' . $settings['info_box_title'] . '</' . $settings['title_tag'] . '>'; ?>
                        </div>
                    <?php } ?>

                    <?php if ($settings['info_box_text']) { ?>
                        <div class="pe--infobox--content">
                            <p <?php echo $animation ?>><?php echo $settings['info_box_text'] ?></p>
                        </div>
                    <?php } ?>


                </div>


            </div>



        </div>

        <?php
    }

}
