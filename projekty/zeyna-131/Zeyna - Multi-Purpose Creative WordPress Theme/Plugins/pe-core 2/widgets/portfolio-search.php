<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PePortfolioSearch extends Widget_Base
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
        return 'peportfoliosearch';
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
        return __('Portfolio Search', 'pe-core');
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
        return 'eicon-search pe-widget';
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
        return ['pe-dynamic'];
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

        // Tab Title Control
        $this->start_controls_section(
            'section_tab_title',
            [
                'label' => __('Portfolios Search', 'pe-core'),
            ]
        );

        $taxonomiesRepeater = new \Elementor\Repeater();


        $post_type = 'portfolio';
        $taxonomies = get_object_taxonomies($post_type, 'objects');
        $taxonomy_options = [];

        if (!empty($taxonomies) && !is_wp_error($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                $taxonomy_options[$taxonomy->name] = $taxonomy->label;
            }
        }

        $taxonomiesRepeater->start_controls_tabs(
            'taxonomies_repeater_tabs'
        );

        $taxonomiesRepeater->start_controls_tab(
            'tax_content_tab',
            [
                'label' => esc_html__('Content', 'pe-core'),
            ]
        );

        $taxonomiesRepeater->add_control(
            'select_taxonomy',
            [
                'label' => __('Select Taxonomy', 'pe-core'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::SELECT,
                'multiple' => true,
                'options' => $taxonomy_options,

            ]
        );


        foreach ($taxonomies as $key => $tax) {

            $termsArray = [];
            $terms = get_terms([
                'taxonomy' => $tax->name,
                'hide_empty' => false,
            ]);

            if (!is_wp_error($terms) && !empty($terms)) {

                foreach ($terms as $term) {
                    $termsArray[$term->term_id] = $term->name;
                }
            }

            $taxonomiesRepeater->add_control(
                'select_' . $tax->name,
                [
                    'label' => __('Select ' . $tax->label, 'pe-core'),
                    'description' => __('Leave it empty if you want to display all.', 'pe-core'),
                    'label_block' => true,
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => $termsArray,
                    'condition' => [
                        'select_taxonomy' => $tax->name,
                    ],
                ]
            );
        }


        $taxonomiesRepeater->add_control(
            'tax_title',
            [
                'label' => esc_html__('Title', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => esc_html__('Leave it empty if you want to use default taxonomy title.', 'pe-core'),
            ]
        );

        $taxonomiesRepeater->add_control(
            'tax_default_text',
            [
                'label' => esc_html__('Default text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Any', 'pe-core'),
            ]
        );


        $taxonomiesRepeater->add_control(
            'is_multiple',
            [
                'label' => __('Is Multiple', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core'),
                'label_off' => __('No', 'pe-core'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $taxonomiesRepeater->add_control(
            'checkboxes_style',
            [
                'label' => esc_html__('Checboxes Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'pe-core'),
                    'basic' => esc_html__('Basic', 'pe-core'),

                ],
                'condition' => ['is_multiple' => 'yes'],
            ]
        );

        $taxonomiesRepeater->end_controls_tab();

        $taxonomiesRepeater->start_controls_tab(
            'tax_style_tab',
            [
                'label' => esc_html__('Style', 'pe-core'),
            ]
        );

        $taxonomiesRepeater->add_responsive_control(
            'item_width',
            [
                'label' => esc_html__('Input Width', 'pe-core'),
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
                    '{{WRAPPER}} form#zeyna--portfolio--search--form:has(.pe--custom--select)> {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $taxonomiesRepeater->add_control(
            'list_flex_head',
            [
                'label' => esc_html__('List Flex Options', 'pe-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        flexOptions($taxonomiesRepeater, false, '{{CURRENT_ITEM}} .pe--form--list', 'form_list', 'List', false, '.pe--form--list--item');

        objectStyles($taxonomiesRepeater, 'form_list', 'List', '{{CURRENT_ITEM}} .pe--form--list.pe--styled--object', true, false, false, false, false);

        objectStyles($taxonomiesRepeater, 'form_list_items', 'Items', '{{CURRENT_ITEM}} .pe--form--list--item', true, false, false, false, false);


        $taxonomiesRepeater->end_controls_tab();

        $taxonomiesRepeater->end_controls_tabs();


        $this->add_control(
            'taxonomies_list',
            [
                'label' => esc_html__('Taxonomies', 'pe-core'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $taxonomiesRepeater->get_controls(),
                'title_field' => '{{{ select_taxonomy }}}',
                'show_label' => false,
            ]
        );

        $this->add_control(
            'text_input',
            [
                'label' => __('Text Input', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core'),
                'label_off' => __('No', 'pe-core'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'placeholder',
            [
                'label' => esc_html__('Input Placeholder', 'pe-core'),
                'default' => esc_html__('Search Projects...', 'pe-core'),
                'condition' => [
                    'text_input' => 'yes',

                ],
            ]
        );

        $this->add_control(
            'select_page',
            [
                'label' => esc_html__('Select Portfolio Page', 'pe-core'),
                'description' => esc_html__('Required!', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'groups' => $this->get_grouped_page_options(),
            ]
        );



        $this->end_controls_section();


        pe_button_settings($this, false, false, 'search_button', true, 'Search ');

        $this->start_controls_section(
            'button_settings',
            [
                'label' => esc_html__('Load More Button Settings', 'pe-core'),
                'condition' => [
                    'load_more' => 'yes',

                ],


            ]
        );



        $this->end_controls_section();

        pe_cursor_settings($this);

        $this->start_controls_section(
            'search_styles',
            [
                'label' => esc_html__('Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        objectStyles($this, 'search_wrapper', 'Search Wrapper', '.zeyna--portfolios--search', false, false, false, false, false);

        flexOptions($this, false, 'form#zeyna--portfolio--search--form', 'form_', 'Form', false, 'form#zeyna--portfolio--search--form > div');


        $this->end_controls_section();

        objectStyles($this, 'form_items', 'Inputs', '.pe--form--list--label.pe--styled--object', true, false, true, false, false);


        objectStyles($this, 'text_input', 'Text Input', '.pe--portfolio--search--text input', true, ['text_input' => 'yes'], true, false, true);


        pe_button_style_settings($this, 'Search Button', 'search_button', false);
        pe_color_options($this);


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();


        ?>

        <div class="zeyna--portfolios--search pe--styled--object" data-results-count="<?php echo $settings['results_count'] ?>">

            <form id="zeyna--portfolio--search--form" method="get"
                action="<?php echo get_the_permalink($settings['select_page']) ?>">

                <?php


                if ($settings['text_input'] === 'yes') {
                    echo '<div class="pe--portfolio--search--text">';
                    echo '<input class="pe--styled--object" type="text" name="sfilters[keyword]" placeholder="' . $settings['placeholder'] . '" />';
                    echo '</div>';
                }

                $taxonomyRepeater = $settings['taxonomies_list'];

                foreach ($taxonomyRepeater as $tax) {

                    $taxonomy = $tax['select_taxonomy'];
                    $selectTaxonomies = $tax['select_' . $taxonomy];

                    $terms = get_terms([
                        'taxonomy' => $taxonomy,
                        'hide_empty' => false,
                        'include' => !empty($selectTaxonomies) ? $selectTaxonomies : 'all',
                    ]);

                    if ($tax['is_multiple'] === 'yes') {

                        $title = !empty($tax['tax_title']) ? $tax['tax_title'] : get_taxonomy($taxonomy)->labels->singular_name;
                        $checkboxStyle = 'checkboxes--' . $tax['checkboxes_style'];

                        echo '<div class="pe--checkbox--field--set--wrapper ' . $checkboxStyle . ' elementor-repeater-item-' . $tax['_id'] . '">
                                    <div class="pe--checkbox--field--set--label pe--form--list--label pe--styled--object  checkbox--selected">
                                    <span class="pe--checbox--field--set--label--title">' . $title . '</span>
                                    <span class="pe--checbox--field--set--selection"><span class="pe--checbox--field--default">' . $tax['tax_default_text'] . '</span></span>' . file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/chevron_down.svg') . '</div>
                                ';

                        if (!is_wp_error($terms) && !empty($terms)) {
                            echo '<div class="pe--checkbox--field--set pe--form--list pe--styled--object">';
                            foreach ($terms as $term) {
                                echo '<label class="pe--form--list--item pe--styled--object"><input type="checkbox" name="sfilters[' . get_taxonomy($taxonomy)->name . '][]" value="' . esc_attr($term->term_id) . '">' . '<span>' . esc_html($term->name) . '</span></label> ';
                            }
                            echo '</div>';
                        }

                        echo '</div>';

                    } else { ?>

                        <div class="pe--custom--select <?php echo 'elementor-repeater-item-' . $tax['_id']; ?>">

                            <div class="pe--select--label pe--form--list--label pe--styled--object select--selected">
                                <span class="pe--select--label--title">
                                    <?php echo '' . !empty($tax['tax_title']) ? $tax['tax_title'] : get_taxonomy($taxonomy)->labels->singular_name ?></span>
                                <span class="pe--select--selection"><?php echo $tax['tax_default_text'] ?></span>
                                <?php echo file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/chevron_down.svg') ?>
                            </div>

                            <div class="pe--select--items hide pe--form--list pe--styled--object">

                                <div data-val="" class="pe--select--item pe--form--list--item pe--styled--object item--default">
                                    <?php echo $tax['tax_default_text']; ?>
                                </div>

                                <?php
                                if (!is_wp_error($terms) && !empty($terms)) {
                                    foreach ($terms as $term) {
                                        echo '<div data-val="' . esc_attr($term->term_id) . '" class="pe--select--item pe--form--list--item pe--styled--object item--' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</div>';
                                    }
                                }
                                ?>

                            </div>

                            <?php echo '<select name="sfilters[' . get_taxonomy($taxonomy)->name . '][]" class="portfolio--search--tax">';
                            echo '<option value="">' . $tax['tax_default_text'] . '</option>';

                            if (!is_wp_error($terms) && !empty($terms)) {

                                foreach ($terms as $term) {
                                    echo '<option value="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</option>';
                                }

                            }
                            echo '</select>'; ?>

                        </div>

                        <?php

                    }

                }

                echo '<div class="pe--portfolio--search--button">';
                pe_button_render($this, false, false, 'search_button', false);
                echo '</div>';

                ?>


            </form>


        </div>



    <?php }

}
