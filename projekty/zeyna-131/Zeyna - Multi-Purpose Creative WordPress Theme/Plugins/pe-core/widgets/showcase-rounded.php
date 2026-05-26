<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class PeShowcaseRounded extends Widget_Base
{
    public function get_name()
    {
        return 'peshowcaserounded';
    }

    public function get_title()
    {
        return __('Pe Showcase Rounded', 'pe-core');
    }

    public function get_icon()
    {
        return 'eicon-posts-carousel pe-widget';
    }

    public function get_categories()
    {
        return ['pe-showcase'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_project_title',
            [
                'label' => __('Showcase', 'pe-core'),
            ]
        );

        zeyna_project_query_selection($this, false, false);

        $this->add_control(
            'image_width',
            [
                'label' => esc_html__('Width', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'vw', 'custom'],
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
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--rounded .showcase--rounded--wrapper > div' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'image_height',
            [
                'label' => esc_html__('Height', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
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
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--rounded .showcase--project' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'perspective',
            [
                'label' => esc_html__('Perspective', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10000,
                        'step' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--rounded' => 'perspective: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'transformZ',
            [
                'label' => esc_html__('Transform Z', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 2000,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--rounded .showcase--rounded--wrapper' => 'transform: translateZ({{SIZE}}{{UNIT}});',
                ],
            ]
        );


        $this->add_control(
            'content_width',
            [
                'label' => esc_html__('Content Width', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'vw', 'custom'],
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
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--rounded .showcase--project .portfolio--project--wrapper .project--metas--wrap ' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );



        $this->end_controls_section();

        zeyna_project_settings($this, false);
        zeyna_project_styles($this, false);


        $this->start_controls_section(
            'navigation',
            [
                'label' => esc_html__('Navigation', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'nav_type',
            [
                'label' => esc_html__('Navigation Type', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'icon' => esc_html__('Icon', 'pe-core'),
                    'text' => esc_html__('Text', 'pe-core'),
                ],
                'default' => 'icon',
            ]
        );

        $this->add_control(
            'prev_text',
            [
                'label' => esc_html__('Prev Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'nav_type' => 'text'
                ],
                'default' => 'PREV'
            ]
        );

        $this->add_control(
            'next_text',
            [
                'label' => esc_html__('Next Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'nav_type' => 'text'
                ],
                'default' => 'NEXT'
            ]
        );

        $this->add_control(
            'prev_icon',
            [
                'label' => esc_html__('Prev Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'nav_type' => 'icon'
                ]
            ]
        );

        $this->add_control(
            'next_icon',
            [
                'label' => esc_html__('Next Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'nav_type' => 'icon'
                ]
            ]
        );
        objectAbsolutePositioning($this, '.showcase--navigation', 'rounded__nav', 'Icon');
        objectStyles($this, 'nav_buttons', 'Nav Buttons', '.nav--item.pe--styled--object', true, false, false, false, false, false);

        $this->add_control(
            'nav_buttons_gap',
            [
                'label' => esc_html__('Gap', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['vw', '%', 'px'],
                'range' => [
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 3000,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--rounded .showcase--navigation' => 'gap: {{SIZE}}{{UNIT}}'
                ] 
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'close',
            [
                'label' => esc_html__('Close', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'close_type',
            [
                'label' => esc_html__('Close Type', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'icon' => esc_html__('Icon', 'pe-core'),
                    'text' => esc_html__('Text', 'pe-core'),
                ],
                'default' => 'icon',
            ]
        );

        $this->add_control(
            'close_text',
            [
                'label' => esc_html__('Close Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'close_type' => 'text'
                ],
                'default' => 'PREV'
            ]
        );



        $this->add_control(
            'close_icon',
            [
                'label' => esc_html__('close Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'close_type' => 'icon'
                ]
            ]
        );





        objectAbsolutePositioning($this, '.close--button', 'rounded__close', 'Icon');
        objectStyles($this, 'close_button', 'Close Button', '.close--button.pe--styled--object', true, false, false, false, false, false);


        $this->end_controls_section();



        pe_general_animation_settings($this);
        pe_cursor_settings($this);
        pe_color_options($this);

    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();
        $id = $this->get_id();

        $cursor = pe_cursor($settings, $this);
        ?>

        <div class="showcase--rounded anim-multiple" <?php echo pe_general_animation($this) ?>>
            <div class="showcase--rounded--wrapper">
                <?php
                $loop = new \WP_Query(zeyna_project_query_args(($this)));
                while ($loop->have_posts()):
                    $loop->the_post();
                    $classes = 'showcase--project';
                    ?>
                    <div class="project--container">
                        <?php
                        
                        zeyna_project_render($this, $classes, $cursor, 'all', false);
                        ?>
                    </div>
                    <?php
                endwhile;
                wp_reset_query();
                ?>


            </div>

            <div class="showcase--navigation">
                <span class="nav--prev nav--item pe--styled--object">
                    <span class="nav--inner">
                        <?php if ($settings['nav_type'] === 'text') {
                            echo $settings['prev_text'];
                        } else {
                            \Elementor\Icons_Manager::render_icon($settings['prev_icon'], ['aria-hidden' => 'true']);
                        } ?>
                    </span>
                </span>
                <span class="nav--next nav--item pe--styled--object">
                    <span class="nav--inner">
                        <?php if ($settings['nav_type'] === 'text') {
                            echo $settings['next_text'];
                        } else {
                            \Elementor\Icons_Manager::render_icon($settings['next_icon'], ['aria-hidden' => 'true']);
                        } ?>
                    </span>

                </span>
            </div>

            <div class="close--button pe--styled--object">
                <span class="close--inner">
                    <?php if ($settings['close_type'] === 'text') {
                        echo $settings['close_text'];
                    } else {
                        \Elementor\Icons_Manager::render_icon($settings['close_icon'], ['aria-hidden' => 'true']);

                    } ?>
                </span>
            </div>
        </div>



        <?php
    }
}





