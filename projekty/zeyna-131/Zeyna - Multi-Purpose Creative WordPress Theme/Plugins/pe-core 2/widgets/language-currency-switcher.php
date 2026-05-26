<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class peLanguageCurrencySwitcher extends Widget_Base
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
        return 'pelanguagecurrencyswitcher';
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
        return __('Pe Language/Currency Switcher', 'pe-core');
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
        return 'eicon-select pe-widget';
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
        return ['pe-header'];
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
                'label' => __('Language/Currency Switcher', 'pe-core'),
            ]
        );


        $this->add_control(
            'type',
            [
                'label' => esc_html__('Type', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'language',
                'options' => [
                    'language' => esc_html__('Language', 'pe-core'),
                    'currency' => esc_html__('Currency', 'pe-core'),
                ],
            ]
        );



        $this->add_control(
            'style',
            [
                'label' => esc_html__('Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'switcher',
                'render_type' => 'template',
                'prefix_class' => 'ls--switcher--',
                'options' => [
                    'switcher' => esc_html__('Switcher', 'pe-core'),
                    'dropdown' => esc_html__('Dropdown', 'pe-core'),
                    'basic' => esc_html__('Basic', 'pe-core'),
                ],
            ]
        );

        $this->add_responsive_control(
            'switcher_direction',
            [
                'label' => esc_html__('Direction', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'prefix_class' => 'switcher--',
                'render_type' => 'template',
                'default' => 'row',
                'options' => [
                    'row' => [
                        'title' => esc_html__('Horizontal', 'pe-core'),
                        'icon' => ' eicon-h-align-right',
                    ],
                    'column' => [
                        'title' => esc_html__('Vertical', 'pe-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--language--currency--switcher ul' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'switcher_sperator',
            [
                'label' => esc_html__('Seperator', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('/', 'pe-core'),
                'placeholder' => esc_html__('Eg: /', 'pe-core'),
                'description' => esc_html__('Leave it empty if you do not want to display seperator between metas.', 'pe-core'),
                'condition' => [
                    'style' => 'basic',
                ],
            ]
        );


        $this->add_control(
            'show_flags',
            [
                'label' => __('Show Flags', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core'),
                'label_off' => __('No', 'pe-core'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => ['type' => 'language']
            ]
        );

        $this->add_control(
            'active_item_style',
            [
                'label' => __('Active Item Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'render_type' => 'template',
                'prefix_class' => 'ls--active--',
                'options' => [
                    'default' => esc_html__('Default', 'pe-core'),
                    'dot' => esc_html__('Dot', 'pe-core'),
                    'spacer' => esc_html__('Spacer', 'pe-core'),
                    'custom' => esc_html__('Custom', 'pe-core'),
                ],
                'condition' => ['type' => 'language']
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'switcher_styles',
            [
                'label' => esc_html__('Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'items_typography',
                'label' => esc_html__('Items Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--language--currency--switcher',
            ]
        );

        $this->add_responsive_control(
            'items_padding',
            [
                'label' => esc_html__('Items Padding', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw'],
                'selectors' => [
                    '{{WRAPPER}} .pe--language--currency--switcher.lcs--switcher li.zeyna--wpml--lang a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'style' => 'switcher',
                ],
            ]
        );


        $cond = ['style' => 'switcher'];
        objectStyles($this, 'switchers_styles', 'Switcher', '.pe--language--currency--switcher.pe--styled--object', true, $cond, false, false, false);


        $cond2 = ['style' => 'dropdown'];
        objectStyles($this, 'items_styles', 'Items', '.pe--language--currency--switcher.lcs--dropdown li a.pe--styled--object', true, $cond2, false, false, false);

        $this->end_controls_section();

        pe_cursor_settings($this);
        pe_color_options($this);

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $type = $settings['type'];
        $style = $settings['style'];

        ?>

        <div data-barba-prevent="all"
            class="pe--language--currency--switcher pe--styled--object <?php echo 'lcs--' . $type . ' lcs--' . $style ?>">

            <span class="lcs--follower"></span>

            <?php if ($type === 'language') {

                $args = [
                    'type' => $style === 'dropdown' ? 'widget' : 'custom',
                    'flags' => $settings['show_flags'] === 'yes' ? 1 : 0,
                    'native' => 0,
                ];

                $languages = apply_filters('wpml_active_languages', NULL, ['skip_missing' => 0]);

                if (!empty($languages)) {
                    $i = -1;
                    echo '<ul class="zeyna--language--switcher zeyna--wpml--switch">';
                    foreach ($languages as $lang) {
                        $i++;
                        $class = $lang['active'] ? 'active--lang' : 'inactive--lang';
                        echo '<li style="--i:' . $i . '" class="zeyna--wpml--lang ' . esc_attr($class) . '">';
                        echo '<a class="pe--styled--object" href="' . esc_url($lang['url']) . '">';

                        if ($settings['show_flags'] === 'yes') {
                            echo '<img src="' . esc_url($lang['country_flag_url']) . '" alt="' . esc_attr($lang['native_name']) . '" />';
                        }
                        echo esc_html($lang['native_name']);
                        echo '</a></li>';
                        if (!empty($settings['switcher_sperator'])) {
                            echo '<span class="language--switcher--seperator">' . $settings['switcher_sperator'] . '</span>';
                        }

                    }
                    echo '</ul>';
                }

            } else if ($type === 'currency') {
                $format = [];
                // $format[] = '%name%';
                // $format[] = '%code%';
                $format[] = '%symbol%';

                // foreach ($settings['currency_show_elements'] as $element) {
                //     if ($element === 'name') {
    
                //     } elseif ($element === 'code') {
    
                //     } elseif ($element === 'symbol') {
    
                //     }
                // }
    
                $format_string = implode(' ', $format);

                $args = [
                    'format' => $format_string,
                    'switcher_style' => 'wcml-horizontal-list'
                ];
                do_action('wcml_currency_switcher', $args);


            } ?>


        </div>

        <?php
    }

}
