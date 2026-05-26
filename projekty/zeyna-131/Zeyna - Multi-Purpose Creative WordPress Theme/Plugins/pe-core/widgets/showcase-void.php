<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeShowcaseVoid extends Widget_Base
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
        return 'peshowcasevoid';
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
        return __('Pe Showcase Void', 'pe-core');
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
        return 'eicon-photo-library pe-widget';
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
                'prefix_class' => '',
                'render_type' => 'template'
            ]
        );



        $this->end_controls_section();



        zeyna_project_settings($this, false);

        zeyna_project_styles($this, false);



        // $this->start_controls_section(
        //     'button_section',
        //     [
        //         'label' => esc_html__('Button', 'pe-core')
        //     ]
        // );
        // pe_button_settings($this);
        // $this->end_controls_section();

        // pe_cursor_settings($this);
        pe_general_animation_settings($this);

        $this->start_controls_section(
            'style',
            [
                'label' => esc_html__('Style', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );



        $this->add_control(
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
                    '{{WRAPPER}} .showcase--void .zeyna--portfolio--project' => 'width: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_control(
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
                    '{{WRAPPER}} .showcase--void .zeyna--portfolio--project' => 'height: {{SIZE}}{{UNIT}}'
                ]
            ]
        );




        $this->end_controls_section();
        pe_cursor_settings($this);


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

        <div class="showcase--void"
            style="--yProgress: 0px; --zProgress: 0px; ; --opacityProg: 0; --filterProg: 0px; --innerOpacityProg: 0"
            data-speed="<?php echo $speed; ?>" data-pin-target="<?php echo $settings['pinned--elements']; ?>">
            <div class="showcase--void--wrapper">
                <?php
                $loop = new \WP_Query(zeyna_project_query_args(($this)));
                $count = 0;
                $projects = [];
                while ($loop->have_posts()):
                    $loop->the_post();
                    $count++;
                    $projects[] = get_the_ID();
                    $classes = 'showcase--project';
                    ?>
                    <div class="project--container main--project">
                    <?php
                    zeyna_project_render($this, $classes, $cursor, 'all', null);
                    ?>
</div>
                    <?php
                endwhile;

                if ($settings['infinite'] === 'infinite__active') {

                    if ($count % 4 !== 0) {
                        for ($cloneLength = $count % 4; $cloneLength <= 3; $cloneLength++) {
                            if ($count % 2 === 0) {
                                $id = floor(($count / 2) - 1) + ($cloneLength - 2);
                            } else if ($count % 4 === 1) {
                                $id = floor(($count / 2)) + ($cloneLength - 2);
                            } else if ($count % 4 === 3) {
                                $id = floor($count / 2);
                            }

                            $classes = 'showcase--project';
                            ?>
                            <div class="project--container main--project">
                                <?php
                                zeyna_project_render($this, $classes, $cursor, 'all', $projects[$id]);
                                ?>
                            </div>
                            <?php
                        }
                    }

                    for ($loopClone = 0; $loopClone <= 3; $loopClone++) {
                        $classes = 'clone__project clone--project_' . $loopClone;
                        ?>
                        <div class='project--container <?php echo $classes; ?>'>
                            <?php
                            zeyna_project_render($this, '', $cursor, 'all', $projects[$loopClone]);
                            ?>
                        </div>
                        <?php
                    }


                }

                wp_reset_query();


                ?>


                <?php





                ?>
            </div>
        </div>



        </script>






    <?php }



}
