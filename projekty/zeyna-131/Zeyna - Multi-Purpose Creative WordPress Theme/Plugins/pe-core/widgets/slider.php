<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeSlider extends Widget_Base
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
        return 'peslider';
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
        return __('Pe Slider', 'pe-core');
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
        return 'eicon-accordion pe-widget';
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
                'label' => __('Slider', 'pe-core'),
            ]
        );


        $this->add_control(
            'slider_type',
            [
                'label' => esc_html__('Type', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'projects',
                'options' => [
                    'projects' => esc_html__('Projects', 'pe-core'),
                    'products' => esc_html__('Products', 'pe-core'),
                ],
            ]
        );


        zeyna_project_query_selection($this, false, ['slider_type' => 'projects']);
        zeyna_product_query_selection($this, false, ['slider_type' => 'products']);

        pe_slider_settings($this, false);

        $this->end_controls_section();

        pe_slider_style_settings($this, true);

        $this->start_controls_section(
            'product_settings',
            [
                'label' => esc_html__('Products Settings', 'pe-core'),
                'condition' => ['slider_type' => 'products',],
            ]
        );

        pe_product_controls($this);

        $this->end_controls_section();

        zeyna_project_settings($this, ['slider_type' => 'projects']);

        zeyna_project_styles($this, ['slider_type' => 'projects']);

        pe_product_styles($this, ['slider_type' => 'products']);

        pe_cursor_settings($this);

        pe_general_animation_settings($this);

        pe_color_options($this);

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $args = [];

        if ($settings['slider_type'] === 'projects') {
            $args = zeyna_project_query_args(($this));
        } else {
            $args = zeyna_product_query_args(($this));
        }

        $arr = [];

        $cursor  = pe_cursor($settings , $this);

        $loop = new \WP_Query($args);
        while ($loop->have_posts()):
            ob_start();
            $loop->the_post();
            if ($settings['slider_type'] === 'projects') {
                $classes = 'slider--project';
            } else {
                $classes = 'zeyna--single--product inner--anim ' . $settings['product_style'];
            }


            if ($settings['slider_type'] === 'projects') {
                zeyna_project_render($this, $classes, $cursor);
            } else {
                zeynaProductRender($settings, wc_get_product(), $classes, $cursor);
            }

            $html = ob_get_clean();
            $arr[] = $html;
        endwhile;
        wp_reset_query();

        echo pe_slider_render($this, $arr);

        ?>



        <?php
    }

}
