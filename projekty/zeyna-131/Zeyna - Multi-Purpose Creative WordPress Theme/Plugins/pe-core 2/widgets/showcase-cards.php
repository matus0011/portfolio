<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeShowcaseCards extends Widget_Base
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
        return 'peshowcasecards';
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
        return __('Pe Showcase Cards', 'pe-core');
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
        return 'eicon-posts-carousel pe-widget';
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
            'speed',
            [
                'label' => esc_html__('Speed', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1000,
                'max' => 30000,
                'step' => 100,
            ]
        );

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
            'infinite',
            [
                'label' => esc_html__('Infinite', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'infinite__active',
                'render_type' => 'template',
                'prefix_class' => ''
            ]
        );

        $this->add_responsive_control(
            'perspective_offset',
            [
                'label' => esc_html__('Perspective Offset', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 10,
                'default' => 100,
                'max' => 1000,
                'step' => 10
            ]
        );

        $this->add_responsive_control(
            'perspective_depth',
            [
                'label' => esc_html__('Perspective Depth', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'default' => 2,
                'max' => 10,
                'step' => 1
            ]
        );


        $this->end_controls_section();



        zeyna_project_settings($this, false);

        zeyna_project_styles($this, false);



        $this->start_controls_section(
            'button_section',
            [
                'label' => esc_html__('Button', 'pe-core')
            ]
        );
        pe_button_settings($this);
        $this->end_controls_section();

        pe_cursor_settings($this);
        pe_general_animation_settings($this);

        $this->start_controls_section(
            'style',
            [
                'label' => esc_html__('Style', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );



        $this->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__('Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw', '%'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 2500,
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
                    '{{WRAPPER}} .showcase--cards .showcase--cards--wrapper' => 'width: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => esc_html__('Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', '%'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
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
                    '{{WRAPPER}} .showcase--cards .showcase--cards--wrapper' => 'height: {{SIZE}}{{UNIT}}'
                ]
            ]
        );




        $this->end_controls_section();


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

        if ($settings['speed']) {
            $speed = $settings['speed'];
        } else {
            $speed = 10000;
        }


        $cursor = pe_cursor($settings, $this);

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


        ?>

        <div class="showcase--cards" style="--yProgress: 0px; --zProgress: 0px" data-speed="<?php echo $speed; ?>"
            data-pin-target="<?php echo $settings['pinned--elements']; ?>"
            data-offset="<?php echo $settings['perspective_offset'] ?>"
            data-depth="<?php echo $settings['perspective_depth'] ?>">
            <div class="showcase--cards--wrapper">
                <?php
                $loop = new \WP_Query(zeyna_project_query_args(($this)));
                $count = 0;
                while ($loop->have_posts()):
                    $loop->the_post();
                    $count++;

                    $classes = 'showcase--project';
                    zeyna_project_render($this, $classes, $cursor);
                endwhile;

                wp_reset_query();
                ?>

                <?php
                if ($settings['infinite'] === 'infinite__active') {

                    if ($count > 0) {

                        $cloneLength = 10 / $count;
                        if ($count < 1) {
                            $count = 1;
                        }
                        for ($e = 0; $e < $cloneLength; $e++) {
                            while ($loop->have_posts()):
                                $loop->the_post();
                                $count++;

                                $classes = 'clone--project';
                                zeyna_project_render($this, $classes, $cursor);
                            endwhile;
                        }

                    }


                }


                ?>
            </div>


        </div>



        </script>






    <?php }



}
