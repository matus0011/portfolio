<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class PeShowcaseList extends Widget_Base
{
    public function get_name()
    {
        return 'peshowcaselist';
    }

    public function get_title()
    {
        return __('Pe Showcase List', 'pe-core');
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
        zeyna_project_query_selection($this, false, false); // Seçim
        

        $this->add_control(
            'scroll_direction',
            [
                'label' => esc_html__('Scroll Direction', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'bottom__to__top' => [
                        'title' => esc_html__('Bottom to Top', 'pe-core'),
                        'icon' => 'eicon-v-align-top'
                    ],
                    'top__to__bottom' => [
                        'title' => esc_html__('Top to Bottom', 'pe-core'),
                        'icon' => 'eicon-v-align-bottom'
                    ]
                ],
                'default' => 'top__to__bottom',
                'prefix_class' => '',
                'render_type' => 'template'
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => esc_html__('Speed', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1000,
                'max' => 20000,
                'step' => 100
            ]
        );

        $this->add_control(
            'pinned--elements',
            [
                'label' => esc_html__('Pinned Elements Class', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,
                'placeholder' => 'Eg. ".outer-widgets"',
                'description' => esc_html__('Elements which has the class you entered will be pinned during the showcase scroll. You can add elements classes via "Advances -> CSS Classes" on the widget options.', 'pe-core'),
            ]
        );

        // $this->add_control(
        //     'image_position_type',
        //     [
        //         'label' => esc_html__('Image Position Type', 'pe-core'),
        //         'type' => \Elementor\Controls_Manager::SELECT,
        //         'options' => [
        //             'cursor__image' => esc_html__('Follow Cursor', 'pe-core'),
        //             'static__image' => esc_html__('Static', 'pe-core'),
        //         ],
        //     ]
        // );

        // $this->add_responsive_control(
        //     'image_left',
        //     [
        //         'label' => esc_html__('Horizontal Position', 'pe-core'),
        //         'type' => \Elementor\Controls_Manager::SLIDER,
        //         'size_units' => ['px', '%', 'vw'],
        //         'range' => [
        //             'px' => [
        //                 'min' => 0,
        //                 'max' => 2000,
        //                 'step' => 1
        //             ],
        //             'vw' => [
        //                 'min' => 0,
        //                 'max' => 100,
        //                 'step' => 1
        //             ],
        //             '%' => [
        //                 'min' => 0,
        //                 'max' => 100,
        //                 'step' => 1
        //             ]
        //         ],
        //         'selectors' => [
        //             '{{WRAPPER}} .showcase--list.static__image .showcase--list--image--wrapper' => 'left: {{SIZE}}{{UNIT}}'
        //         ],
        //         'condition' => [
        //             'image_position_type' => 'static__image'
        //         ]
        //     ]
        // );

        // $this->add_responsive_control(
        //     'image_top',
        //     [
        //         'label' => esc_html__('Vertical Position', 'pe-core'),
        //         'type' => \Elementor\Controls_Manager::SLIDER,
        //         'size_units' => ['px', '%', 'vh'],
        //         'range' => [
        //             'px' => [
        //                 'min' => 0,
        //                 'max' => 1000,
        //                 'step' => 1
        //             ],
        //             'vh' => [
        //                 'min' => 0,
        //                 'max' => 100,
        //                 'step' => 1
        //             ],
        //             '%' => [
        //                 'min' => 0,
        //                 'max' => 100,
        //                 'step' => 1
        //             ]
        //         ],
        //         'selectors' => [
        //             '{{WRAPPER}} .showcase--list.static__image .showcase--list--image--wrapper' => 'top: {{SIZE}}{{UNIT}}'
        //         ],
        //         'condition' => [
        //             'image_position_type' => 'static__image'
        //         ]
        //     ]
        // );


        $this->end_controls_section();



        pe_cursor_settings($this);
        pe_general_animation_settings($this);

        $this->start_controls_section(
            'style',
            [
                'label' => esc_html__('Image', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_responsive_control(
            'project_width',
            [
                'label' => esc_html__('Image Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                        'step' => 5
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--list .showcase--project--image' => 'width: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_control(
            'equal_height',
            [
                'label' => esc_html__('Equal Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes'
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => esc_html__('Image Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                        'step' => 5
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--list .showcase--project--image' => 'height: {{SIZE}}{{UNIT}}'
                ],
                'condition' => [
                    'equal_height' => 'yes'
                ]
            ]
        );


        $this->add_control(
            'text_align',
            [
                'label' => esc_html__('Alignment', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'self-start' => [
                        'title' => esc_html__('Left', 'textdomain'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'textdomain'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'self-end' => [
                        'title' => esc_html__('Right', 'textdomain'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .showcase--list .zeyna--portfolio--project .portfolio--project--wrapper .project--main--wrap' => 'justify-content: {{VALUE}};',
                ],
            ]
        );



        $this->end_controls_section();

        zeyna_project_settings($this, false);

        zeyna_project_styles($this, false);

        pe_color_options($this);
    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();
        

        

        if ($settings['speed']) {
            $speed = $settings['speed'];
        } else {
            $speed = 5000;
        }
        ?>
        <div class="showcase--list anim-multiple" data-speed="<?php echo $speed; ?>"
            data-pin-target="<?php echo $settings['pinned--elements']; ?>" >

            <div class="showcase--list--image--wrapper">
            <?php
                $loop = new \WP_Query(zeyna_project_query_args(($this)));
                while ($loop->have_posts()):
                    $loop->the_post();
                    $classes = 'showcase--project--image';
                    zeyna_project_render($this, $classes, false);
                endwhile;
                wp_reset_query();
                ?>
            </div>

            <div class="showcase--list--title--wrapper">
            <?php
                $loop = new \WP_Query(zeyna_project_query_args(($this)));
                while ($loop->have_posts()):
                    $loop->the_post();
                    $classes = 'showcase--project--title';
                    zeyna_project_list_render($settings, $classes, false);
                endwhile;
                wp_reset_query();
                ?>
            </div>

        </div>
        <?php
    }
}
