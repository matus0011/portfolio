<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class peShowcaseExplore extends Widget_Base
{
    public function get_name()
    {
        return 'peshowcaseexplore';
    }

    public function get_title()
    {
        return __('Pe Showcase Explore', 'pe-core');
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

        pe_cursor_settings($this, false, true);
        pe_general_animation_settings($this);

        $this->start_controls_section(
            'style',
            [

                'label' => esc_html__('Showcase Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'wrapper_width',
            [
                'label' => esc_html__('Wrapper Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%', 'px', 'vw', 'em', 'rem', 'custom'],
                'render_type' => 'template',
                'range' => [
                    'px' => [
                        'min' => 2000,
                        'max' => 10000,
                        'step' => 100,
                    ],
                    '%' => [
                        'min' => 100,
                        'max' => 300,
                        'step' => 1,
                    ],
                    'vw' => [
                        'min' => 100,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--explore .showcase--explore--wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_height',
            [
                'label' => esc_html__('Wrapper Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%', 'px', 'vh', 'em', 'rem', 'custom'],
                'render_type' => 'template',
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
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--explore .showcase--explore--wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'items_width',
            [
                'label' => esc_html__('Items Gap', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%', 'px', 'vh', 'em', 'rem', 'custom'],
                'render_type' => 'template',
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
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--explore .showcase--explore--wrapper .zeyna--portfolio--project' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );



        $this->end_controls_section();



       zeyna_project_styles($this, false);
        pe_color_options($this);
    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();

        ob_start();
        \Elementor\Icons_Manager::render_icon($settings['head_icon'], ['aria-hidden' => 'true']);
        $icon = ob_get_clean();
        $classes = [];

        // $cursor = pe_cursor($settings, $this);
        // $animation = pe_general_animation($this);
        $loop = new \WP_Query(zeyna_project_query_args(($this)));
        $count = $loop->post_count;

        ?>



        <div class="showcase--explore anim-multiple" style="--xProgress: 0px; --yProgress: 0px">


            <div class="showcase--explore--wrapper"
                style="--gridLength: <?php echo ceil((sqrt($count))); ?>; --width: <?php ceil((sqrt($count))) * 500 ?>px --height: <?php ceil((sqrt($count))) * 300 ?>px">
                <?php
                $loop = new \WP_Query(zeyna_project_query_args(($this)));

                while ($loop->have_posts()):
                    $loop->the_post();

                    $classes = '';
                    ?>
                    <div class="project--container">
                    <?php 
                   zeyna_project_render($this, $classes, false);
                    ?>
</div>
                    <?php
                endwhile;
                wp_reset_query();

                ?>
            </div>

            <?php if ($settings['infinite'] === 'infinite__active') { ?>
                <div class="showcase--explore--wrapper"
                    style="--gridLength: <?php echo ceil((sqrt($count))); ?>; --width: <?php ceil((sqrt($count))) * 500 ?>px --height: <?php ceil((sqrt($count))) * 300 ?>px">
                    <?php
                    $loop = new \WP_Query(zeyna_project_query_args(($this)));

                    while ($loop->have_posts()):
                        $loop->the_post();

                        $classes = '';
                        ?>
                        <div class="project--container">
                        <?php 
                       zeyna_project_render($this, $classes, false);
                        ?>
    </div>
                        <?php

                    endwhile;
                    wp_reset_query();

                    ?>
                </div>
                <div class="showcase--explore--wrapper"
                    style="--gridLength: <?php echo ceil((sqrt($count))); ?>; --width: <?php ceil((sqrt($count))) * 500 ?>px --height: <?php ceil((sqrt($count))) * 300 ?>px">
                    <?php
                    $loop = new \WP_Query(zeyna_project_query_args(($this)));

                    while ($loop->have_posts()):
                        $loop->the_post();

                        $classes = '';
                        ?>
                        <div class="project--container">
                        <?php 
                       zeyna_project_render($this, $classes, false);
                        ?>
    </div>
                        <?php
                    endwhile;
                    wp_reset_query();

                    ?>
                </div>
                <div class="showcase--explore--wrapper"
                    style="--gridLength: <?php echo ceil((sqrt($count))); ?>; --width: <?php ceil((sqrt($count))) * 500 ?>px --height: <?php ceil((sqrt($count))) * 300 ?>px">
                    <?php
                    $loop = new \WP_Query(zeyna_project_query_args(($this)));

                    while ($loop->have_posts()):
                        $loop->the_post();

                        $classes = '';
                        ?>
                        <div class="project--container">
                        <?php 
                       zeyna_project_render($this, $classes, false);
                        ?>
    </div>
                        <?php
                    endwhile;
                    wp_reset_query();

                    ?>
                </div>

            <?php } ?>

        </div>

        <?php

    }
}
