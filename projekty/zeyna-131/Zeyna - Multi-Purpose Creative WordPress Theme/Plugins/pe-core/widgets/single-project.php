<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeSingleProject extends Widget_Base
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
        return 'pesingleproject';
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
        return __('Single Project', 'pe-core');
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
        return 'eicon-image-box pe-widget';
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


        $options = [];

        $projects = get_posts([
            'post_type' => 'portfolio',
            'numberposts' => -1
        ]);

        foreach ($projects as $project) {
            $options[$project->ID] = $project->post_title;
        }

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Single Project', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'select_post',
            [
                'label' => __('Select Project', 'pe-core'),
                'label_block' => true,
                'description' => __('Select project which will display in the widget.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $options,
            ]
        );

        $this->end_controls_section();

        zeyna_project_settings($this);

        pe_cursor_settings($this, false, true);
        pe_general_animation_settings($this);

        zeyna_project_styles($this);

        pe_color_options($this);


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $id = $settings['select_post'];

        $args = array(
            'post_type' => 'portfolio',
            'posts_per_page' => 1,
            'post__in' => array($id),
            'post__not_in' => get_option("sticky_posts"),
        );

        $loop = new \WP_Query($args);
        $cursor = pe_cursor($settings, $this);


        ?>

        <?php while ($loop->have_posts()):
            $loop->the_post();
            zeyna_project_render($this, '', $cursor);
        endwhile;
        wp_reset_query(); ?>


        <?php
    }

}
