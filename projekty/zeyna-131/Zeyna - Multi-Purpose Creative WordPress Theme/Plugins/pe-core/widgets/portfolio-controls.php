<?php

namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PePortfolioControls extends Widget_Base
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
        return 'peportfoliocontrols';
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
        return __('Portfolio Controls', 'pe-core');
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
        return 'eicon-taxonomy-filter pe-widget';
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



        $this->start_controls_section(
            'portfolio_controls_settings',
            [
                'label' => __('Controls ', 'pe-core'),
            ]
        );

        $this->add_control(
            'query_id',
            [
                'label' => esc_html__('Query ID (Required)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,
                'placeholder' => esc_html__('Eg: portfolioQuery', 'pe-core'),
                'description' => esc_html__('The query of the portfolio posts widget id needs to be inserted here.', 'pe-core'),

            ]
        );

        $this->add_control(
            'filters',
            [
                'label' => __('Filters', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'true',
                'default' => '',
            ]
        );

        $this->add_control(
            'filters_style',
            [
                'label' => esc_html__('Filters Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => false,
                'render_type' => 'template',
                'default' => 'block',
                'prefix_class' => 'filters--style--',
                'options' => [
                    'block' => esc_html__('Block', 'pe-core'),
                    'dropdown' => esc_html__('Dropdown', 'pe-core'),
                    'popup' => esc_html__('Popup', 'pe-core'),
                    'sidebar' => esc_html__('Sidebar', 'pe-core'),
                ],
                'condition' => [
                    'filters' => 'true',

                ]
            ]
        );

        $this->add_control(
            'filters_button_text',
            [
                'label' => esc_html__('Button Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,
                'default' => esc_html__('Filters', 'pe-core'),
                'condition' => [
                    'filters' => 'true',
                    'filters_style' => 'popup',
                ]
            ]
        );

        $this->add_control(
            'filters_button_icon',
            [
                'label' => esc_html__('Button Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'material-icons md-add',
                    'library' => 'material-design-icons',
                ],
            ]
        );



        $this->add_responsive_control(
            'filters_pos',
            [
                'label' => esc_html__('Filters Position', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => esc_html__('Left', 'pe-core'),
                        'icon' => 'eicon-align-start-h',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__('Right', 'pe-core'),
                        'icon' => 'eicon-align-end-h',
                    ],
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .pe--portfolio:has(.pe--portfolio--filters.filters--sidebar)' => 'flex-direction: {{VALUE}};',
                ],
                'condition' => ['filters_style' => 'sidebar'],
            ]
        );

        $post_type = 'portfolio';
        $taxonomies = get_object_taxonomies($post_type, 'objects');
        $taxonomy_options = [];

        if (!empty($taxonomies) && !is_wp_error($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                $taxonomy_options[$taxonomy->name] = $taxonomy->label;
            }
        }

        $portfolioFilters = new \Elementor\Repeater();

        $portfolioFilters->add_control(
            'select_taxonomy',
            [
                'label' => __('Select Taxonomy', 'pe-core'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::SELECT,
                'multiple' => true,
                'options' => $taxonomy_options,

            ]
        );


        $portfolioFilters->start_controls_tabs(
            'filter_options_tabs'
        );

        $portfolioFilters->start_controls_tab(
            'content_tab',
            [
                'label' => esc_html__('Content', 'pe-core'),
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

            $portfolioFilters->add_control(
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

        $portfolioFilters->add_control(
            'show_label',
            [
                'label' => __('Show Label', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'true',
                'default' => '',
            ]
        );

        $portfolioFilters->add_control(
            'show_all_button',
            [
                'label' => __('Show All Button', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'pe-core '),
                'label_off' => __('Hide', 'pe-core '),
                'return_value' => 'true',
                'default' => '',
            ]
        );


        $portfolioFilters->add_control(
            'show_all_text',
            [
                'label' => esc_html__('Show All Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('All ', 'pe-core'),
                'ai' => false,
                'condition' => [
                    'show_all_button' => 'true'
                ],
            ]
        );

        $portfolioFilters->add_control(
            'show_counts',
            [
                'label' => __('Show Project Counts', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'true',
                'default' => '',
            ]
        );


        $portfolioFilters->add_control(
            'filter_style',
            [
                'label' => esc_html__('Filter Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'button',
                'options' => [
                    'button' => esc_html__('Button', 'pe-core'),
                    'checkbox' => esc_html__('Checkbox', 'pe-core'),
                    'dropdown' => esc_html__('Dropdown', 'pe-core'),
                ],
            ]
        );


        $portfolioFilters->end_controls_tab();

        $portfolioFilters->start_controls_tab(
            'styles_tab',
            [
                'label' => esc_html__('Styles', 'pe-core'),
            ]
        );

        $portfolioFilters->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'labels_typography',
                'label' => esc_html__('Labels Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .taxonomy--label',
            ]
        );

        $portfolioFilters->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'terms_typography',
                'label' => esc_html__('Terms Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .term-item',
            ]
        );

        flexOptions($portfolioFilters, false, '{{CURRENT_ITEM}} ul.term--list', 'term_list', 'Terms', false);


        $portfolioFilters->end_controls_tab();

        $portfolioFilters->end_controls_tabs();

        $this->add_control(
            'portfolio_filters',
            [
                'label' => esc_html__('Filters', 'pe-core'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $portfolioFilters->get_controls(),
                'show_label' => false,
                'condition' => [
                    'filters' => 'true',
                ],
                'title_field' => '{{{ select_taxonomy }}}',
            ]
        );

        $this->add_control(
            'pinned_filters',
            [
                'label' => __('Pinned Filters', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'prefix_class' => '',
                'return_value' => 'filters--pinned',
                'render_type' => 'template',
                'default' => '',
                'condition' => ['filters_style' => 'sidebar'],
            ]
        );


        $this->add_control(
            'clear_filters',
            [
                'label' => __('Clear Filters Button', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'pe-core '),
                'label_off' => __('Hide', 'pe-core '),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'clear_filters_button_text',
            [
                'label' => esc_html__('Clear Button Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Clear Filters', 'pe-core'),
                'condition' => [
                    'clear_filters' => 'yes',
                    'filters' => 'true',
                ],
            ]
        );

        $this->add_control(
            'style_switcher',
            [
                'label' => __('Style Switcher', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'true',
                'default' => '',
            ]
        );

        $this->add_control(
            'switcher_styles',
            [
                'label' => esc_html__('Switcher Styles', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => [
                    'grid' => esc_html__('Grid', 'pe-core'),
                    'list' => esc_html__('List', 'pe-core'),
                    'masonry' => esc_html__('Masonry', 'pe-core'),
                ],
                'condition' => [
                    'style_switcher' => 'true',

                ]
            ]
        );

        $this->add_control(
            'switcher_buttons',
            [
                'label' => esc_html__('Switcher Buttons', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text' => esc_html__('Text', 'pe-core'),
                    'icon' => esc_html__('Icon', 'pe-core'),
                ],
            ]
        );

        $this->add_control(
            'grid_text',
            [
                'label' => esc_html__('Grid Text', 'pe-core'),
                'default' => esc_html__('Grid', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => false,
                'ai' => false,
                'condition' => [
                    'switcher_buttons' => 'text',
                    'switcher_styles' => 'grid',
                ],
            ]
        );

        $this->add_control(
            'grid_icon',
            [
                'label' => esc_html__('Grid Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'skin' => 'inline',
                'condition' => [
                    'switcher_buttons' => 'icon',
                    'switcher_styles' => 'grid',
                ],

            ]
        );

        $this->add_control(
            'list_text',
            [
                'label' => esc_html__('List Text', 'pe-core'),
                'default' => esc_html__('List', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => false,
                'ai' => false,
                'condition' => [
                    'switcher_buttons' => 'text',
                    'switcher_styles' => 'list',
                ],
            ]
        );

        $this->add_control(
            'list_icon',
            [
                'label' => esc_html__('List Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'skin' => 'inline',
                'condition' => [
                    'switcher_buttons' => 'icon',
                    'switcher_styles' => 'list',
                ],

            ]
        );

        $this->add_control(
            'masonry_text',
            [
                'label' => esc_html__('Masonry Text', 'pe-core'),
                'default' => esc_html__('Masonry', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => false,
                'ai' => false,
                'condition' => [
                    'switcher_buttons' => 'text',
                    'switcher_styles' => 'masonry',
                ],
            ]
        );

        $this->add_control(
            'masonry_icon',
            [
                'label' => esc_html__('Masonry Icon', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'skin' => 'inline',
                'condition' => [
                    'switcher_buttons' => 'icon',
                    'switcher_styles' => 'masonry',
                ],

            ]
        );

        $this->add_control(
            'grid_style_switcher',
            [
                'label' => __('Grid Layout Switcher', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'true',
                'default' => '',
                'condition' => [
                    'portfolio_style' => 'grid',
                ],
            ]
        );

        $this->add_control(
            'grid_switch_columns',
            [
                'label' => esc_html__('Grid Switch Columns', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => [
                    '1' => esc_html__('1 Column', 'pe-core'),
                    '2' => esc_html__('2 Columns', 'pe-core'),
                    '3' => esc_html__('3 Columns', 'pe-core'),
                    '4' => esc_html__('4 Columns', 'pe-core'),
                    '5' => esc_html__('5 Columns', 'pe-core'),
                    '6' => esc_html__('6 Columns', 'pe-core'),
                ],
                'default' => ['2-column', '3-column'],
                'condition' => [
                    'portfolio_style' => 'grid',
                    'grid_style_switcher' => 'true',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'filter_popup_opts',
            [
                'label' => esc_html__('Popup Options', 'pe-core'),
                'condition' => [
                    'filters_style' => 'popup',
                ],
            ]
        );

        popupOptions($this, ['filters_style' => 'popup']);

        $this->end_controls_section();




        $this->start_controls_section(
            'controls_styles',
            [
                'label' => esc_html__('Controls Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        flexOptions($this, false, '.pe--portfolio--controls', 'controls', 'Controls');

        // objectStyles($this , 'pop_button' , 'Button' , '')


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'controls_border',
                'selector' => '{{WRAPPER}} .pe--portfolio--controls',
            ]
        );

        $this->add_responsive_control(
            'controls_border-radius',
            [
                'label' => esc_html__('Border Radius', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pe--portfolio--controls' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'controls_padding',
            [
                'label' => esc_html__('Padding', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pe--portfolio--controls' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'controls_margin',
            [
                'label' => esc_html__('Margin', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pe--portfolio--controls' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'filters_styles',
            [
                'label' => esc_html__('Filters Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        flexOptions($this, false, '.pe--portfolio--filters', 'filters', 'Filters');


        objectStyles($this, 'filter_item_', 'Items', '.term-item.pe--styled--object', true, false, false, false, false, false);


        $this->end_controls_section();

        $this->start_controls_section(
            'filters_popup_styles_sec',
            [
                'label' => esc_html__('Filters Popup Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['filters_style' => 'popup'],
            ]
        );

        objectStyles($this, 'filters_button', 'Popup Button', '.filters--button.pe--pop--button', false, false, false, false);
        popupStyles($this, ['filters_style' => 'popup']);
        $this->end_controls_section();


        $this->start_controls_section(
            'style_switcher_styles',
            [
                'label' => esc_html__('Style Switcher Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['style_switcher' => 'true'],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'switchers_typography',
                'selector' => '{{WRAPPER}} .pe--portfolio--style--switcher ul li',
            ]
        );

        $this->add_control(
            'switchers_sperator',
            [
                'label' => esc_html__('Seperator', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Eg: /', 'pe-core'),
                'description' => esc_html__('Leave it empty if you do not want to display seperator between metas.', 'pe-core'),
                'condition' => [
                    'style_switcher' => 'true',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Title Typography (for Grid view)', 'pe-core'),
                'name' => 'grid_title_typography',
                'condition' => [
                    'switcher_styles' => 'grid',
                ],
                'selector' => '{{WRAPPER}}.portfolio--style--grid .project--title',
            ]
        );



        $this->end_controls_section();

        pe_color_options($this);
    }

    protected function render()
    {
        $settings = $this->get_settings();
        $style = 'list';
        global $wp_query;
        $loop = $wp_query;

        ?>

        <div data-id="<?php echo esc_attr($settings['query_id']) ?>" class="pe--portfolio--filters--widget">

            <div class="pe--portfolio--controls">

                <?php if ($settings['filters'] === 'true') {

                    if ($settings['filters_style'] === 'block' || $settings['filters_style'] === 'sidebar') {

                        pe_portfolio_filters($settings, $loop);

                    } else if ($settings['filters_style'] === 'dropdown' || $settings['filters_style'] === 'popup') { ?>

                            <div class="filters--button pe--styled--object pe--pop--button">
                                <?php

                                if (!empty($settings['filters_button_icon']['value'])) {

                                    ob_start();
                                    \Elementor\Icons_Manager::render_icon($settings['filters_button_icon'], ['aria-hidden' => 'true']);
                                    $button = ob_get_clean();

                                } else {
                                    $button = file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/filter.svg');
                                }

                                echo wp_kses_post( $button );
                                echo esc_html($settings['filters_button_text']) ?>

                            </div>

                    <?php }


                } ?>

                <?php if ($settings['style_switcher'] === 'true') { ?>
                    <div class="pe--portfolio--style--switcher">

                        <ul class="pe--switcher switcher--styles">
                            <?php

                            $x = 0;
                            foreach ($settings['switcher_styles'] as $switcherStyle) {
                                $x++;

                                if ($settings['switcher_buttons'] === 'text') {
                                    $object = $settings[$switcherStyle . '_text'];
                                } else {
                                    ob_start();
                                    \Elementor\Icons_Manager::render_icon($settings[$switcherStyle . '_icon'], ['aria-hidden' => 'true']);
                                    $object = ob_get_clean();
                                }
                                $active = $style === $switcherStyle ? 'active' : '';
                                ?>

                                <li data-style="<?php echo esc_attr($switcherStyle) ?>"
                                    class="switch--item pe--styled--object <?php echo esc_attr($switcherStyle . ' ' . $active) ?>">
                                    <?php echo $object;
                                    if (!empty($settings['switchers_sperator']) && (count(($settings['switcher_styles'])) !== $x)) {
                                        echo '<span class="switchers--seperator">' . $settings['switchers_sperator'] . '</span>';
                                    } ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>

                <?php } ?>

                <?php if ($settings['grid_style_switcher'] === 'true') { ?>
                    <div class="pe--portfolio--style--switcher grid--switcher">

                        <ul class="pe--switcher switcher--styles">
                            <?php foreach ($settings['grid_switch_columns'] as $key => $col) {

                                if ($col == $settings['grid_columns']['size']) {
                                    $act = 'switch--active';
                                } else {
                                    $act = '';
                                }

                                $svgPath = plugin_dir_path(__FILE__) . '../assets/img/grid-col-' . $col . '.svg';
                                $icon = file_get_contents($svgPath);

                                echo '<li data-switch-cols="' . $col . '" class="switch--item ' . $col . '--col ' . $act . '">' . $icon . '</li>';

                            } ?>
                        </ul>
                    </div>

                <?php } ?>


            </div>

            <?php if ($settings['filters'] === 'true') {

                if ($settings['filters_style'] === 'dropdown' || $settings['filters_style'] === 'popup') {

                    if ($settings['filters_style'] === 'popup') { ?>
                        <?php if ($settings['back_overlay'] === 'true') { ?>
                            <span class="pop--overlay"></span>
                        <?php } ?>

                        <div class="pe--filters--popup pe--styled--popup">

                            <span class="pop--close">

                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                                    <path
                                        d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z" />
                                </svg>

                            </span>

                            <?php pe_portfolio_filters($settings, $loop); ?>

                        </div>

                    <?php } else {
                        pe_portfolio_filters($settings, $loop);
                    }



                }
            }
            ?>


        </div>






        <?php
    }
}
