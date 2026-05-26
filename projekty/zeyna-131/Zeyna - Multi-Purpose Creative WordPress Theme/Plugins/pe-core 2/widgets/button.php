<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeButton extends Widget_Base
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
        return 'pebutton';
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
        return __('Button', 'pe-core');
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
        return 'eicon-button pe-widget';
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

    protected function get_grouped_page_options()
    {
        $groups = [];

        // Pages
        $pages = get_pages();
        $page_options = [];
        foreach ($pages as $page) {
            $page_options[$page->ID] = $page->post_title;
        }
        if (!empty($page_options)) {
            $groups[] = [
                'label' => esc_html__('Pages', 'pe-core'),
                'options' => $page_options,
            ];
        }

        // Posts
        $posts = get_posts([
            'post_type' => 'post',
            'numberposts' => -1
        ]);
        $post_options = [];
        foreach ($posts as $post) {
            $post_options[$post->ID] = $post->post_title;
        }
        if (!empty($post_options)) {
            $groups[] = [
                'label' => esc_html__('Posts', 'pe-core'),
                'options' => $post_options,
            ];
        }

        // Custom Post Types (exclude 'post' and 'page')
        $custom_post_types = get_post_types(['public' => true, '_builtin' => false], 'objects');
        foreach ($custom_post_types as $cpt) {
            $items = get_posts(['post_type' => $cpt->name, 'numberposts' => -1]);
            $options = [];
            foreach ($items as $item) {
                $options[$item->ID] = $item->post_title;
            }
            if (!empty($options)) {
                $groups[] = [
                    'label' => esc_html($cpt->labels->name, 'pe-core'),
                    'options' => $options,
                ];
            }
        }

        // Taxonomies
        $taxonomies = get_taxonomies(['public' => true, '_builtin' => false], 'objects');
        foreach ($taxonomies as $taxonomy) {
            $terms = get_terms(['taxonomy' => $taxonomy->name, 'hide_empty' => false]);
            $options = [];
            foreach ($terms as $term) {
                $options['term_' . $term->term_id] = $term->name;
            }
            if (!empty($options)) {
                $groups[] = [
                    'label' => esc_html($taxonomy->labels->name, 'pe-core'),
                    'options' => $options,
                ];
            }
        }

        return $groups;
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

        if (class_exists("Redux")) {
            $option = get_option('pe-redux');
        } else {
            $option = false;
        }

        $this->start_controls_section(
            'pe_button_popup_options',
            [
                'label' => __('Popup Options', 'pe-core'),
                'condition' => [
                    'interaction' => 'open-popup',
                ],
            ]
        );
        popupOptions($this, ['interaction' => 'open-popup']);
        $this->end_controls_section();

        $this->start_controls_section(
            'pe_button_section',
            [
                'label' => __('Button', 'pe-core'),
            ]
        );
        pe_button_settings($this, true, false, '', false, 'Button');

        $this->end_controls_section();
        $this->start_controls_section(
            'popup_styles_sec',
            [
                'label' => esc_html__('Popup Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['interaction' => 'open-popup'],
            ]
        );

        popupStyles($this, ['interaction' => 'open-popup']);
        $this->end_controls_section();

        pe_button_style_settings($this, 'Button', '', false, true, '');

        pe_cursor_settings($this);
        pe_color_options($this);
        pe_general_animation_settings($this);

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        pe_button_render($this, false, false, '', false, false, '');

        if ($settings['interaction'] === 'open-popup') { ?>

            <?php if ($settings['back_overlay'] === 'true') { ?>
                <span class="pop--overlay"></span>
            <?php } ?>

            <div class="pe--styled--popup">

                <span class="pop--close">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                        <path
                            d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z" />
                    </svg>
                </span>

                <div class="zeyna--popup--template" data-lenis-prevent>

                    <?php

                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($settings['select_template']);

                    ?>

                </div>
            </div>

        <?php }
    }


}
