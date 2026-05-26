<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeClients extends Widget_Base
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
        return 'peclients';
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
        return __('Clients', 'pe-elementor');
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
        return 'eicon-logo pe-widget';
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
                'label' => __('Clients', 'pe-core'),
            ]
        );

        $this->add_control(
            'type',
            [
                'label' => esc_html__('Type', 'pe-core'),
                'description' => esc_html__('Select display type.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'pe--clients--grid',
                'options' => [
                    'pe--clients--grid' => esc_html__('Grid', 'pe-core'),
                    'pe--clients--carousel' => esc_html__('Carousel', 'pe-core'),

                ],
            ]
        );


        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'client_logo',
            [
                'label' => esc_html__('Client Logo', 'pe-core'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => 'https://s.wordpress.org/style/images/codeispoetry.png',
                ],
            ]
        );

        $repeater->add_control(
            'client_title',
            [
                'type' => \Elementor\Controls_Manager::TEXT,
                'label' => esc_html__('Client Title', 'pe-core'),

            ]
        );

        $repeater->add_control(
            'client_url',
            [
                'label' => esc_html__('Client URL', 'pe-core'),
                'type' => \Elementor\Controls_Manager::URL,
                'options' => ['url', 'is_external', 'nofollow'],
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true
                ],
                'label_block' => true
            ]
        );

        $repeater->add_control(
            'secondary_logo',
            [
                'label' => __('Secondary Logo', 'pe-core '),
                'description' => __('Required when layout switcher or hover logo switch enabled.', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'true',
                'default' => 'true',

            ]
        );

        $repeater->add_control(
            'client_secondary_logo',
            [
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),

                ],
                'condition' => ['secondary_logo' => 'true'],
            ]
        );



        $this->add_control(
            'client',
            [
                'label' => esc_html__('Clients', 'pe-core'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'client_title' => esc_html__('Client #1', 'pe-core'),
                    ],
                    [
                        'client_title' => esc_html__('Client #2', 'pe-core'),
                    ],
                    [
                        'client_title' => esc_html__('Client #3', 'pe-core'),
                    ],
                ],
                'title_field' => '{{{ client_title }}}',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'image',
                'exclude' => [],
                'include' => [],
                'default' => 'thumbnail',
            ]
        );


        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__('Columns', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 12,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--clients.pe--clients--grid .pe--clients--wrapper' => '--columns: {{SIZE}};'
                ],
                'condition' => [
                    'type' => 'pe--clients--grid'
                ]
            ]
        );

        $this->add_responsive_control(
            'columns_spacing',
            [
                'label' => esc_html__('Columns Spacing', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
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
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--clients.pe--clients--grid .pe--clients--wrapper' => 'column-gap: {{SIZE}}{{UNIT}}'
                ],
                'condition' => [
                    'type' => 'pe--clients--grid'
                ]
            ]
        );

        $this->add_responsive_control(
            'rows_spacing',
            [
                'label' => esc_html__('Rows Gap', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
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
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--clients.pe--clients--grid .pe--clients--wrapper' => 'row-gap: {{SIZE}}{{UNIT}}'
                ],
                'condition' => [
                    'type' => 'pe--clients--grid'
                ]
            ]
        );



        $this->add_control(
            'fade_edges',
            [
                'label' => __('Fade Edges', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'fade--edges',
                'default' => '',
                'prefix_class' => '',
            ]
        );

        $this->add_control(
            'project_images_overlay_color',
            [
                'label' => esc_html__('Edge Color', 'pe-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pe--clients ' => '--fade--color: {{VALUE}}',
                ],
                'condition' => [
                    'type' => 'pe--clients--carousel',
                    'fade_edges' => 'fade--edges'
                ],
            ]
        );


        $this->add_control(
            'captions',
            [
                'label' => __('Captions', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'show-captions',
                'default' => 'true',
                'condition' => [
                    'type' => 'pe--clients--carousel'
                ]
            ]
        );



        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .pe--clients--wrapper .pe-client .client-caption',
                'condition' => [
                    'captions' => 'show-captions'
                ]
            ]
        );


        $this->add_control(
            'carousel_direction',
            [
                'label' => esc_html__('Carousel Direction', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left-to-right' => [
                        'title' => esc_html__('Left To Right', 'pe-core'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'right-to-left' => [
                        'title' => esc_html__('Right To Left', 'pe-core'),
                        'icon' => 'eicon-h-align-left',
                    ],
                ],
                'default' => 'left-to-right',
                'toggle' => true,
                'label_block' => true,
                'condition' => [
                    'type' => 'pe--clients--carousel'
                ]
            ]
        );


        $this->add_control(
            'carousel_speed',
            [
                'label' => esc_html__('Speed', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 20,
                'condition' => [
                    'type' => 'pe--clients--carousel'
                ]
            ]
        );

        $this->add_control(
            'stop_hover',
            [
                'label' => esc_html__('Stop on Hover?', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => true,
                'default' => false,
                'description' => esc_html__('Animation will follow scrolling behavior of the page.', 'pe-core'),
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'styles',
            [
                'label' => esc_html__('Style', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'items_width',
            [
                'label' => esc_html__('Items Width', 'pe-core'),
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
                    '{{WRAPPER}} .pe--clients .pe--clients--wrapper>div' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'items_height',
            [
                'label' => esc_html__('Items Height', 'pe-core'),
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
                    '{{WRAPPER}} .pe--clients .pe--clients--wrapper img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'align_items',
            [
                'label' => esc_html__('Align Items', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'pe-core'),
                        'icon' => 'eicon-align-start-v'
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'pe-core'),
                        'icon' => 'eicon-align-center-v'
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'pe-core'),
                        'icon' => 'eicon-align-end-v'
                    ],
                    'stretch' => [
                        'title' => esc_html__('Strecth', 'pe-core'),
                        'icon' => 'eicon-align-stretch-v'
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--clients .pe--clients--wrapper' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} .pe--clients .pe--clients--wrapper .pe-client>*' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'items_gap',
            [
                'label' => esc_html__('Items Gap', 'pe-core'),
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
                    '{{WRAPPER}} .pe--clients--wrapper' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        objectStyles($this, 'clients_styles', 'Client ', '.pe-client', false, false, false, false, false);



        $this->add_control(
            'corner_borders',
            [
                'label' => esc_html__('Corner Borders', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'border--corners',
                'default' => '',
                'prefix_class' => '',
                'render_type' => 'template',
            ]
        );

        $this->add_responsive_control(
            'corner_borders_size',
            [
                'label' => esc_html__('Borders Size', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'corner_borders' => 'border--corners'
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe-client.pe--styled--object' => '--borderSize: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'corner_borders_width',
            [
                'label' => esc_html__('Borders Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'corner_borders' => 'border--corners'
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe-client.pe--styled--object' => '--borderWidth: {{SIZE}}{{UNIT}}',
                ],
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

        array_push($classes, [$settings['type'], $settings['captions']]);
        $mainClasses = implode(' ', array_filter($classes[0]));


        //Scroll Button Attributes
        $this->add_render_attribute(
            'carousel-sett',
            [
                'data-speed' => $settings['carousel_speed'],
                'data-direction' => $settings['carousel_direction'],
                'data-hover' => $settings['stop_hover']

            ]
        );
        $carouselSett = $settings['type'] === 'pe--clients--carousel' ? $this->get_render_attribute_string('carousel-sett') : '';

        $cursor = pe_cursor($settings, $this);



        ?>


        <!-- Pe Clients Grid -->
        <div class="pe--clients <?php echo esc_attr($mainClasses); ?>" <?php echo $carouselSett; ?>>

            <div class="pe--clients--wrapper">

                <?php if ($settings['client']) {

                    foreach ($settings['client'] as $key => $item) {

                        $target = $item['client_url']['is_external'] ? '_blank' : '_self';

                        ?>

                        <!-- Client -->
                        <div class="pe-client pe--styled--object">

                            <a target="<?php echo $target ?>" href="<?php echo esc_url($item['client_url']['url']) ?>" <?php echo $cursor ?>>

                                <?php if ($item['secondary_logo'] === 'true') { ?>
                                    <?php  echo wp_get_attachment_image($item['client_secondary_logo']['id'], $settings['image_size'] , "", ["class" => "secondary-img"]) ?>
                                <?php } ?>

                                <?php  echo wp_get_attachment_image($item['client_logo']['id'], $settings['image_size'] , "", ["class" => "main-img"]) ?>

                                <span class="client-caption"><?php echo esc_html($item['client_title']) ?></span>

                            </a>

                        </div>
                        <!--/ Client -->

                    <?php }
                } ?>

            </div>


        </div>
        <!--/ Pe Clients Grid -->


        <?php
    }

}
