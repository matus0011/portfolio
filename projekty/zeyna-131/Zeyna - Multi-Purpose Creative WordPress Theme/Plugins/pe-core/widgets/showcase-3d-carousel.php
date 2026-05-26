<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class PeShowcase3DCarousel extends Widget_Base
{
    public function get_name()
    {
        return 'peshowcase3dcarousel';
    }

    public function get_title()
    {
        return __('Pe Showcase 3D Carousel', 'pe-core');
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
            'pinned--elements',
            [
                'label' => esc_html__('Pinned Elements Class', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,
                'placeholder' => 'Eg. ".outer-widgets"',
                'description' => esc_html__('Elements which has the class you entered will be pinned during the showcase scroll. You can add elements classes via "Advances -> CSS Classes" on the widget options.', 'pe-core'),
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => esc_html__('Speed', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1000,
                'max' => 25000,
                'step' => 100,
                'default' => 5000,
            ]
        );

        $this->add_control(
            'infinite',
            [
                'label' => esc_html__('Infinite', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'infinite__active',
                'render_type' => 'template',
                'prefix_class' => ''
            ]
        );




        $this->end_controls_section();




        zeyna_project_settings($this, false);

        zeyna_project_styles($this, false);



        pe_general_animation_settings($this);
        pe_cursor_settings($this);

        $this->start_controls_section(
            'style',
            [
                'label' => esc_html__('Style', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );


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
                    '{{WRAPPER}} .showcase--3d--carousel .zeyna--portfolio--project' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase--3d--carousel .zeyna--portfolio--project .portfolio--project--wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'project_spacing',
            [
                'label' => esc_html__('Spacing', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 2000,
                        'step' => 10
                    ]
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .showcase--3d--carousel .showcase--3d--carousel--wrapper' => '--spacing: {{SIZE}}'
                ]
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_filter',
                'selector' => '{{WRAPPER}} .showcase--3d--carousel .project--image',
            ]
        );



        $this->end_controls_section();


        pe_color_options($this);

    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();
        $id = $this->get_id();
        $speed = $settings['speed'];

        if (!$settings['speed']) {
            $speed = 5000;
        }

        $cursor = pe_cursor($settings, $this);

        ?>

        <div class="showcase--3d--carousel" data-speed="<?php echo $speed; ?>"
            data-pin-target="<?php echo $settings['pinned--elements']; ?>">
            <div class="showcase--3d--carousel--wrapper">

                <?php
                $loop = new \WP_Query(zeyna_project_query_args(($this)));
                $count = 0;
                while ($loop->have_posts()):
                    $loop->the_post();
                    $count++;

                    $classes = 'showcase--project';
                    ?>
                    <div class="project--container">
                    <?php
                    zeyna_project_render($this, $classes, $cursor);
                    ?>
                    </div>
                    <?php
                endwhile;
                wp_reset_query();
                ?>



                <?php
                $cloneLength = 20 / $count;
                for ($index = 0; $index < $cloneLength; $index++) {


                    $loop = new \WP_Query(zeyna_project_query_args(($this)));
                    while ($loop->have_posts()):
                        $loop->the_post();
                        $classes = 'clone--project';
                        ?>
                    <div class="project--container clone--container">
                    <?php

                        zeyna_project_render($this, $classes, $cursor);
                        ?>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_query();
                }
                ?>

            </div>


        </div>



        <?php
    }
}





