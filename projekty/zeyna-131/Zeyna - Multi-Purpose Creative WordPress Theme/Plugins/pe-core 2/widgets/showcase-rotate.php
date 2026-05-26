<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeShowcaseRotate extends Widget_Base
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
        return 'peshowcaserotate';
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
        return __('Pe Showcase Rotate', 'pe-core');
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
        return 'eicon-text-field pe-widget';
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
                'min' => 2000,
                'max' => 20000,
                'step' => 100,
                'default' => 5000,
            ]
        );

        $this->add_control(
            'intro__on',
            [
                'label' => esc_html__('Intro', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'intro__on',
                'render_type' => 'template',
                'prefix_class' => '',
                'default' => 'intro__on'
            ]
        );

        $this->end_controls_section();



        zeyna_project_settings($this, false);

        zeyna_project_styles($this, false);

        pe_button_style_settings($this, $name = 'Button', $prefix = '', $condition = false);
        pe_cursor_settings($this, $drag = false, $frontend = false);

        $this->start_controls_section(
            'navigation_section',
            [
                'label' => esc_html__('Navigation', 'pe-core')
            ]
        );

        $this->add_control(
            'first_text',
            [
                'label' => esc_html__('First Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Scroll', 'pe-core'),
            ]
        );

        $this->add_control(
            'last_text',
            [
                'label' => esc_html__('Second Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Explore', 'pe-core'),
            ]
        );

        $this->add_control(
            'nav_style_divider',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER
            ]
        );

        $this->add_control(
            'nav_item_padding',
            [
                'label' => esc_html__('Navigation Item Padding', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--rotate .showcase--rotate--navigation .navigation--item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'nav_padding',
            [
                'label' => esc_html__('Navigation Padding', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'default' => [
                    'top' => 5,
                    'right' => 5,
                    'bottom' => 5,
                    'left' => 5,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--rotate .showcase--rotate--navigation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'nav_border_radius',
            [
                'label' => esc_html__('Navigation Border Radius', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--rotate .showcase--rotate--navigation, {{WRAPPER}} .showcase--rotate .showcase--rotate--navigation .navigation--item, {{WRAPPER}} .showcase--rotate .showcase--rotate--navigation .active--item--bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'nav_item_typography',
                'selector' => '{{WRAPPER}} .showcase--rotate .showcase--rotate--navigation .navigation--item',
            ]
        );


        objectAbsolutePositioning($this, '.showcase--rotate .showcase--rotate--navigation', '', 'Navigation');



        $this->end_controls_section();

        $this->start_controls_section(
            'button_section',
            [
                'label' => esc_html__('Button', 'pe-core')
            ]
        );
        pe_button_settings($this, $link = false, $condition = false, $prefix = '', $section = false, $name = 'Shuffle');
        $this->end_controls_section();

        pe_general_animation_settings($this);

        $this->start_controls_section(
            'style',
            [
                'label' => esc_html__('Style', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
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
                    '{{WRAPPER}} .showcase--rotate .portfolio--project--wrapper' => 'width: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'project_filters',
                'label' => esc_html__('Project Image Filter'),
                'selector' => '{{WRAPPER}} .showcase--rotate .portfolio--project--wrapper .project--image'
            ]
        );


        $this->end_controls_section();


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

        <div class="showcase--rotate zoom--out" style="--progress: 0deg" data-speed="<?php echo $speed; ?>"
            data-pin-target="<?php echo $settings['pinned--elements']; ?>">
            <div class="showcase--rotate--wrapper zoom--out--wrapper">
                <?php
                $loop = new \WP_Query(zeyna_project_query_args(($this)));
                $count = 0;
                while ($loop->have_posts()):
                    $loop->the_post();
                    $count++;
                    $classes = 'portfolio__project__' . $count;
                    ;
                    zeyna_project_render($this, $classes, false);
                endwhile;
                wp_reset_query();
                ?>
            </div>

            <div class="showcase--rotate--navigation">
                <span class="navigation--item item--first nav--item--active"><?php echo $settings['first_text']; ?></span>
                <span class="navigation--item item--last"><?php echo $settings['last_text']; ?></span>
                <span class="active--item--bg"></span>
            </div>
        </div>




    <?php }



}
