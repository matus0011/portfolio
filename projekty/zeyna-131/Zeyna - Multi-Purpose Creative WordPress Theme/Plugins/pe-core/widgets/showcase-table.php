<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeShowcaseTable extends Widget_Base
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
        return 'peshowcasetable';
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
        return __('Pe Showcase Table', 'pe-core');
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
        return 'eicon-posts-masonry pe-widget';
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
                'label' => __('Showcase', 'pe-core'),
            ]
        );

        zeyna_project_query_selection($this, false, false); // Seçim


        $this->end_controls_section();



        zeyna_project_settings($this, false);

        $this->start_controls_section(
            'style',
            [
                'label' => esc_html__('Style', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'canvas_width',
            [
                'label' => esc_html__('Canvas Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw', '%', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 750,
                        'step' => 1
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--table' => 'width: {{SIZE}}{{UNIT}}'
                ]
            ]
        );


        $this->add_responsive_control(
            'canvas_height',
            [
                'label' => esc_html__('Canvas Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', '%', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 750,
                        'step' => 1
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--table' => 'height: {{SIZE}}{{UNIT}}'
                ]
            ]
        );




        $this->add_responsive_control(
            'project_width',
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
                    '{{WRAPPER}} .showcase--table .zeyna--portfolio--project' => 'width: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'project_filters',
                'label' => esc_html__('Project Image Filter'),
                'selector' => '{{WRAPPER}} .showcase--table .zeyna--portfolio--project .project--image'
            ]
        );

        $this->add_control(
            'divider',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER
            ]
        );

        objectAbsolutePositioning($this, '.shuffle--button', '', 'Shuffle Buton');




        $this->end_controls_section();


        zeyna_project_styles($this, false);

        pe_button_style_settings($this, $name = 'Button', $prefix = '', $condition = false);
        pe_cursor_settings($this, $drag = false, $frontend = false);
        $this->start_controls_section(
            'button_section',
            [
                'label' => esc_html__('Button', 'pe-core')
            ]
        );
        pe_button_settings($this, $link = false, $condition = false, $prefix = '', $section = false, $name = 'Shuffle');
        $this->end_controls_section();

        pe_general_animation_settings($this);



        pe_color_options($this);


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $classes = [];


        ob_start();

        \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']);

        $icon = ob_get_clean();
        if ((isset($_GET['offset']))) {

            $offset = $_GET['offset'];

        } else {
            $offset = 0;
        }




        $args = array(
            'post_type' => 'portfolio',
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'project-categories',
                    'field' => 'term_id'
                )
            )
        );

        $cursor = pe_cursor($settings, $this);

        ?>

        <div class="showcase--table">
            <div class="showcase--table--wrapper">
                <?php
                $loop = new \WP_Query(zeyna_project_query_args(($this)));
                while ($loop->have_posts()):
                    $loop->the_post();
                    $classes = 'needs--handle';
                    zeyna_project_render($this, $classes, $cursor);
                endwhile;
                wp_reset_query();


                ?>
            </div>
            <div class="shuffle--button">
                <?php pe_button_render($this, $attributes = false, $cursor = false, $prefix = '', $customText = false); ?>
            </div>


        </div>









    <?php }



}
