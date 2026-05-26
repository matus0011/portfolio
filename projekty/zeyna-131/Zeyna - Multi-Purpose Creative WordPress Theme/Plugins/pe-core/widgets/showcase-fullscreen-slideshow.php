<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeShowcaseFullscreenSlideshow extends Widget_Base
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
        return 'peshowcasefullscreenslideshow';
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
        return __('Showcase Fullscreen Slideshow', 'pe-core');
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

        $this->start_controls_section(
            'section_project_title',
            [
                'label' => __('Query', 'pe-core'),
            ]
        );

        zeyna_project_query_selection($this, false, false); // Seçim


        $this->end_controls_section();

        $this->start_controls_section(
            'showcase_options',
            [
                'label' => __('Showcase Options', 'pe-core'),
            ]
        );

        $this->add_control(
            'slide_duration',
            [
                'label' => esc_html__('Slide Duration', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 60000,
                'step' => 1,
            ]
        );


        $this->add_control(
            'swiper_autoplay',
            [
                'label' => esc_html__('Autoplay', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'render_type' => 'template',
                'default' => '',
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label' => esc_html__('Autoplay Delay', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 60000,
                'step' => 1,
                'condition' => [
                    'swiper_autoplay' => 'true'
                ],
            ]
        );

        $this->add_control(
            'swiper_loop',
            [
                'label' => esc_html__('Loop', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'render_type' => 'template',
                'default' => '',
            ]
        );

        $this->add_control(
            'swiper_mousewheel',
            [
                'label' => esc_html__('Mousewheel', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'render_type' => 'template',
                'default' => '',
            ]
        );

        $this->add_control(
            'swiper_parallax',
            [
                'label' => esc_html__('Parallax', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'render_type' => 'template',
                'default' => '',
            ]
        );

        $this->add_control(
            'swiper_thumbnails',
            [
                'label' => esc_html__('Thumbnails', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => 'true',
                'render_type' => 'template',
                'default' => '',
            ]
        );



        $this->add_control(
            'swiper_arrows',
            [
                'label' => esc_html__('Arrows', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => 'true',
                'render_type' => 'template',
                'default' => '',
            ]
        );


        $this->end_controls_section();

        zeyna_project_settings($this, false);

        zeyna_project_styles($this, false);


        $this->start_controls_section(
            'style',
            [
                'label' => esc_html__('Style', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );



        $this->add_control(
            'slide_image_width',
            [
                'label' => esc_html__('Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw', '%'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 750,
                        'step' => 1
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 50,
                        'step' => 1,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .fullscreen--carousel .fullscreen--carousel--nav--image--wrapper .nav--image' => 'width: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_control(
            'slide_image_height',
            [
                'label' => esc_html__('Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', '%'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 750,
                        'step' => 1
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 50,
                        'step' => 1,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .fullscreen--carousel .fullscreen--carousel--nav--image--wrapper .nav--image' => 'height: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_control(
            'slide_image_gap',
            [
                'label' => esc_html__('Gap', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fullscreen--carousel .fullscreen--carousel--nav--image--wrapper' => 'gap: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'slide_image_filters',
                'label' => esc_html__('Slide Image Filter'),
                'selector' => '{{WRAPPER}} .fullscreen--carousel .fullscreen--carousel--nav--image--wrapper .nav--image',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'project_image_filter',
                'label' => esc_html__('Project Image Filter'),
                'selector' => '{{WRAPPER}} .fullscreen--carousel .fullscreen--carousel--project--wrapper .project--image',
            ]
        );

        $this->add_control(
            'style_divider',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER
            ]
        );

        $this->add_control(
            'slide_left',
            [
                'label' => esc_html__('Image Left Space', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 50,
                        'step' => 1,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .fullscreen--carousel .fullscreen--carousel--nav--image--wrapper' => 'left: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_control(
            'image_top_space',
            [
                'label' => esc_html__('Image Top Space', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 50,
                        'step' => 1,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .fullscreen--carousel .fullscreen--carousel--nav--image--wrapper' => 'top: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_control(
            'style_divider_2',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER
            ]
        );




        $this->add_control(
            'vertical_align',
            [
                'label' => esc_html__('Title Vertical Align', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'pe-core'),
                        'icon' => 'eicon-v-align-top'
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'pe-core'),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'pe-core'),
                        'icon' => 'eicon-v-align-bottom'
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fullscreen--carousel .fullscreen--carousel--project--wrapper .showcase--project .project--meta' => 'justify-content: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'horizontal_align',
            [
                'label' => esc_html__('Title Horizontal Align', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'pe-core'),
                        'icon' => 'eicon-h-align-left'
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'pe-core'),
                        'icon' => 'eicon-h-align-center'
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'pe-core'),
                        'icon' => 'eicon-h-align-right'
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fullscreen--carousel .fullscreen--carousel--project--wrapper .showcase--project' => 'justify-content: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'text_align',
            [
                'label' => esc_html__('Text Align', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'pe-core'),
                        'icon' => 'eicon-h-align-left'
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'pe-core'),
                        'icon' => 'eicon-h-align-center'
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'pe-core'),
                        'icon' => 'eicon-h-align-right'
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fullscreen--carousel .fullscreen--carousel--project--wrapper .showcase--project .project--meta' => 'text-align: {{VALUE}}'
                ]
            ]
        );





        $this->end_controls_section();


        pe_color_options($this);
        pe_general_animation_settings($this);
        pe_cursor_settings($this, false);


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $loop = new \WP_Query(zeyna_project_query_args(($this)));
        $cursor = pe_cursor($settings, $this);

        $this->add_render_attribute(
            'swiper_attributes',
            [
                'data-parallax' => $settings['swiper_parallax'],
                'data-duration' => $settings['slide_duration'],
                'data-mousewheel' => $settings['swiper_mousewheel'],
                'data-loop' => $settings['swiper_loop'],
                'data-autoplay' => $settings['swiper_autoplay'],
                'data-autoplay-delay' => $settings['autoplay_delay'],
            ]
        );

        ?>

        <div class="showcase--fullscreen--slideshow swiper-container" <?php echo $this->get_render_attribute_string('swiper_attributes') ?>>



            <div class="showcase--fullscreen--slideshow--wrapper swiper-wrapper">

                <?php

                while ($loop->have_posts()):
                    $loop->the_post();
                    $classes = 'swiper-slide';
                    zeyna_project_render($this, $classes, false, 'image');
                endwhile;
                wp_reset_query();

                ?>
            </div>

            <?php if ($settings['swiper_arrows'] === 'true') { ?>

                <div class="showcase--fullscreen--slideshow--arrows">

                    <span class="swiper--prev">PREV</span>
                    <span class="swiper--next">NEXT</span>

                </div>

            <?php } ?>

        </div>

        <?php if ($settings['swiper_thumbnails'] === 'true') { ?>

            <div class="showcase--fullscreen--slideshow--thumbs swiper-container" <?php echo $this->get_render_attribute_string('swiper_attributes') ?>>

                <span class="thumbs--active--hold"></span>
                <div class="showcase--fullscreen--slideshow--thumbs--wrapper swiper-wrapper">

                    <?php
                    $index = -1;
                    while ($loop->have_posts()):
                        $loop->the_post();
                        $index++;
                        $id = get_the_ID();
                        ?>
                        <div
                            class="showcase--fullscreen--slideshow--thumb swiper-slide swiper-slide-<?php echo $index; ?> zeyna--portfolio--project">
                            <div class="portfolio--project--wrapper">

                                <?php
                                $attr = [
                                    'href' => get_permalink(),
                                    'class' => 'pb--handle barba--trigger',
                                    'data-id' => $id
                                ];

                                pe_button_render($this, $attr, $cursor, 'project__button_', false, false, '', true); ?>

                                <?php pe_project_title_wrap($this, $id) ?>
                                <div class="project--image inner--anim project__image__<?php echo $id; ?>">
                                    <?php if ($settings['project_images_overlay'] === 'true') {
                                        echo '<span class="project--image--overlay"></span>';
                                    } ?>
                                    <?php pe_project_image($id, false, false) ?>

                                </div>
                                <?php pe_project_details_wrap($this, $id) ?>
                            </div>

                        </div>
                    <?php endwhile;
                    wp_reset_query();

                    ?>
                </div>

            </div>

        <?php } ?>

    <?php }



}
