<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeSinglePost extends Widget_Base
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
        return 'pesinglepost';
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
        return __('Single Post', 'pe-core');
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
        return 'eicon-post-content pe-widget';
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
            'post_type' => 'post',
            'numberposts' => -1
        ]);

        foreach ($projects as $project) {
            $options[$project->ID] = $project->post_title;
        }

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Single Post', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'select_post',
            [
                'label' => __('Select Post', 'pe-core'),
                'label_block' => true,
                'description' => __('Select post which will display in the widget.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $options,
            ]
        );


        $this->add_control(
            'posts_style',
            [
                'label' => esc_html__('Post Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'pe-core'),
                    'card' => esc_html__('Card', 'pe-core'),
                    'metro' => esc_html__('Metro', 'pe-core'),
                    'horizontal' => esc_html__('Horizontal', 'pe-core'),
                    'hidden-image' => esc_html__('Hidden Image', 'pe-core'),
                ],

            ]
        );

        $this->add_control(
            'images_hover',
            [
                'label' => esc_html__('Images Hover', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'zoom-in',
                'prefix_class' => 'hover--',
                'options' => [
                    'none' => esc_html__('None', 'pe-core'),
                    'zoom-in' => esc_html__('Zoom-In', 'pe-core'),
                    'zoom-out' => esc_html__('Zoom-Out', 'pe-core'),
                ],
            ]
        );

        $this->add_control(
            'post_image_overlay',
            [
                'label' => esc_html__('Image Overlay', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "metro--image--overlay",
                'default' => "metro--image--overlay",
                'prefix_class' => "",
                'condition' => [
                    'posts_style' => 'metro',
                ],
            ]
        );

        $this->add_control(
            'post_hidden_image_overlay',
            [
                'label' => esc_html__('Image Overlay', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => "hidden--image--overlay",
                'default' => "hidden--image--overlay",
                'prefix_class' => "",
                'condition' => [
                    'posts_style' => 'hidden-image',
                ],
            ]
        );

        $this->add_control(
            'post_thumbnail',
            [
                'label' => esc_html__('Thumbnail', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => "show",
                'default' => "show",


            ]
        );

        $this->add_control(
            'post_date',
            [
                'label' => esc_html__('Date', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => "show",
                'default' => "show",


            ]
        );

        $this->add_control(
            'post_cat',
            [
                'label' => esc_html__('Categories', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => "show",
                'default' => "show",


            ]
        );

        $this->add_control(
            'post_excerpt',
            [
                'label' => esc_html__('Excerpt', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => "show",
                'default' => "show",


            ]
        );

        $this->add_control(
            'post_author',
            [
                'label' => esc_html__('Author', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => "show",
                'default' => "show",


            ]
        );

        $this->add_control(
            'post_button',
            [
                'label' => esc_html__('Read More Button', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'pe-core'),
                'label_off' => esc_html__('Hide', 'pe-core'),
                'return_value' => "show",
                'default' => "show",
            ]
        );


        $this->end_controls_section();


        $this->start_controls_section(
            'post_styles',
            [

                'label' => esc_html__('Post Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_type',
            [
                'label' => esc_html__('Title Tag', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'h1' => [
                        'title' => esc_html__('H1', 'pe-core'),
                        'icon' => ' eicon-editor-h1',
                    ],
                    'h2' => [
                        'title' => esc_html__('H2', 'pe-core'),
                        'icon' => ' eicon-editor-h2',
                    ],
                    'h3' => [
                        'title' => esc_html__('H3', 'pe-core'),
                        'icon' => ' eicon-editor-h3',
                    ],
                    'h4' => [
                        'title' => esc_html__('H4', 'pe-core'),
                        'icon' => ' eicon-editor-h4',
                    ],
                    'h5' => [
                        'title' => esc_html__('H5', 'pe-core'),
                        'icon' => ' eicon-editor-h5',
                    ],
                    'h6' => [
                        'title' => esc_html__('H6', 'pe-core'),
                        'icon' => ' eicon-editor-h6',
                    ]

                ],
                'default' => 'h5',
                'toggle' => true,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'post_title_typography',
                'label' => esc_html__('Title Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .post-title.entry-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'post_metas_typography',
                'label' => esc_html__('Metas Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--post--meta',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'post_excerpt_typography',
                'label' => esc_html__('Excerpt Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--post--excerpt',
            ]
        );

        flexOptions($this, false, '.pe--single--post--wrapper', 'post', 'Post');
        flexOptions($this, false, '.pe--single--post--details', 'post_details', 'Details');

        $this->add_responsive_control(
            'post_images_height',
            [
                'label' => esc_html__('Images Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post--image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'post_images_width',
            [
                'label' => esc_html__('Images Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post--image' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'posts_style' => 'horizontal',

                ],
            ]
        );

        $this->add_responsive_control(
            'post_details_width',
            [
                'label' => esc_html__('Details Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post--details' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'posts_style' => 'horizontal',

                ],
            ]
        );

        $this->add_responsive_control(
            'post_details_height',
            [
                'label' => esc_html__('Details Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post--details' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'posts_style' => 'hidden-image',

                ],
            ]
        );

        $this->add_control(
            'posts_has_bg',
            [
                'label' => esc_html__('Background', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'has--bg',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post::before' => 'content: ""',
                ],
            ]
        );

        $this->add_control(
            'posts_has_backdrop',
            [
                'label' => esc_html__('Backdrop Filter', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'has--backdrop',
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post::before' => 'content: ""; background-color: rgba(255, 255, 255, 0.2);',
                ],

                'default' => '',
            ]
        );


        $this->add_responsive_control(
            'posts_bg_backdrop_blur',
            [
                'label' => esc_html__('Bluriness', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'condition' => [
                    'posts_has_backdrop' => 'has--backdrop',
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post::before' => '--backdropBlur: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pe--single--post::before' => 'backdrop-filter: blur(var(--backdropBlur));',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'posts_border',
                'selector' => '{{WRAPPER}} .pe--single--post',
                'important' => true,
            ]
        );


        $this->add_responsive_control(
            'images_border-radius',
            [
                'label' => esc_html__('Border Radius (Images)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post--image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_border-radius',
            [
                'label' => esc_html__('Border Radius (Box)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post.psp--elementor' => '--radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'post_padding',
            [
                'label' => esc_html__('Padding (Post)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post.psp--elementor' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'post_details_padding',
            [
                'label' => esc_html__('Padding (Details)', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pe--single--post--details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important  ;',
                ],
            ]
        );

        $this->add_control(
            'project_order_items',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__('Order Items', 'pe-core'),
                'label_off' => esc_html__('Default', 'pe-core'),
                'label_on' => esc_html__('Ordered', 'pe-core'),
                'return_value' => 'post--items--ordered',
                'prefix_class' => '',
            ]
        );

        $this->start_popover();

        $this->add_control(
            'post_title_order',
            [
                'label' => esc_html__('Title', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}}.post--items--ordered .pe--post--title' => 'order: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'post_metas_order',
            [
                'label' => esc_html__('Title', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}}.post--items--ordered .pe--post--meta' => 'order: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'post_excerpt_order',
            [
                'label' => esc_html__('Excerpt', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}}.post--items--ordered .pe--post--excerpt' => 'order: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'post_button_order',
            [
                'label' => esc_html__('Button', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}}.post--items--ordered .pe--post--button' => 'order: {{VALUE}};',
                ],

            ]
        );

        $this->end_popover();

        objectStyles($this, 'metas_', 'Metas', '.pe--post--meta > .pe--styled--object', true, false, false, false, false, true);
        objectStyles($this, 'excerpt_', 'Excerpt', '.pe--post--excerpt', true, false, false, false, false, true);

        $this->end_controls_section();


        pe_button_settings($this, false, ['post_button' => 'show'], 'post_button', true, 'Post Button');
        pe_button_style_settings($this, 'Post Button', 'post_button', ['post_button' => 'show'], true, '');

        pe_cursor_settings($this);
        pe_general_animation_settings($this);
        pe_color_options($this);



    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $animation = $settings['select_animation'] !== 'none' ? $settings['select_animation'] : '';
        $classes = [];

        $classes[] = 'pe--single--post pe--styled--object psp--elementor post--' . $settings['posts_style'];

        $id = $settings['select_post'];

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 1,
            'post__in' => array($id),
            'post__not_in' => get_option("sticky_posts"),
        );


        $loop = new \WP_Query($args);

        $cursor = pe_cursor($settings, $this);

        ?>

        <?php while ($loop->have_posts()):
            $loop->the_post(); ?>


            <article id="post-<?php the_ID(); ?>" <?php post_class($classes); ?>>

                <?php if ($settings['posts_style'] === 'metro' || $settings['posts_style'] === 'card' || $settings['posts_style'] === 'hidden-image') {
                    echo '<a ' . $cursor . ' class="post--link--wrap" href="' . get_the_permalink() . '"></a>';
                } ?>
                <div class="pe--single--post--wrapper">

                    <?php if ($settings['post_thumbnail'] === 'show') { ?>
                        <div class="pe--single--post--image">
                            <?php pe_post_thumbnail(); ?>
                        </div>
                    <?php } ?>

                    <div class="pe--single--post--details">

                        <div class="pe--post--meta">

                            <?php if ($settings['post_cat'] === 'show') { ?>
                                <div class="post--categories pe--styled--object"><?php
                                if ('post' === get_post_type()) {

                                    $categories_list = get_the_category_list(esc_html__(', ', 'pe-core'));
                                    if ($categories_list) {
                                        printf('<span class="cat-links">' . esc_html__('%1$s', 'pe-core') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    }
                                } ?>
                                </div>
                            <?php } ?>

                            <?php if ($settings['post_date'] === 'show') { ?>
                                <div class="post--date pe--styled--object">
                                    <?php pe_posted_on(); ?>
                                </div>
                            <?php } ?>

                            <?php if ($settings['post_author'] === 'show') { ?>
                                <div class="post--author pe--styled--object">
                                    <?php the_author(); ?>
                                </div>
                            <?php } ?>

                        </div>


                        <div class="pe--post--title">
                            <?php echo '<' . $settings['text_type'] . ' class="post-title entry-title">' . get_the_title() . '</' . $settings['text_type'] . '>'; ?>
                        </div>



                        <?php if ($settings['post_excerpt'] === 'show') { ?>
                            <div class="pe--post--excerpt pe--styled--object">
                                <?php
                                if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                                    echo wp_trim_words(get_the_excerpt(), 20, '...');
                                } else {
                                    the_excerpt();
                                }
                                ?>
                            </div>
                        <?php } ?>


                        <?php if ($settings['post_button'] === 'show') { ?>
                            <div class="pe--post--button">

                                <?php
                                $attr = [
                                    'href' => get_permalink(),
                                    'class' => 'pb--handle'
                                ];
                                pe_button_render($this, $attr, $cursor, 'post_button', ''); ?>
                            </div>
                        <?php } ?>

                    </div>
                </div>

            </article>

        <?php endwhile;
        wp_reset_query(); ?>


        <?php
    }

}
