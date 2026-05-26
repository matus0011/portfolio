<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class PePortfolioCategories extends Widget_Base
{
    public function get_name()
    {
        return 'peportfoliocategories';
    }

    public function get_title()
    {
        return __('Portfolio Categories', 'pe-core');
    }

    public function get_icon()
    {
        return 'eicon-kit-details pe-widget';
    }

    public function get_categories()
    {
        return ['pe-content'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_project_title',
            [
                'label' => __('Portfolio Categories', 'pe-core'),
            ]
        );

        $productCats = array();

        $args = array(
            'hide_empty' => true,
            'taxonomy' => 'project-categories'
        );

        $categories = get_categories($args);

        foreach ($categories as $key => $category) {
            $productCats[$category->term_id] = $category->name;
        }

        $this->add_control(
            'select_categories',
            [
                'label' => __('Categories', 'pe-core'),
                'description' => __('Select portfolio categories.', 'pe-core'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $productCats,

            ]
        );

        flexOptions($this, false, '.pe--portfolio--categories ul', 'categories_list', '');

        $this->add_control(
            'category_images',
            [
                'label' => __('Images', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'pe-core '),
                'label_off' => __('Hide', 'pe-core '),
                'return_value' => 'true',
                'default' => 'true',
            ]
        );

        $this->add_control(
            'images_style',
            [
                'label' => esc_html__('Images Style', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'static',
                'render_type' => 'template',
                'prefix_class' => 'category--images--',
                'options' => [
                    'static' => esc_html__('Static', 'pe-core'),
                    'hover' => esc_html__('Hover', 'pe-core'),
                ],
                'condition' => [
                    'category_images' => 'true',
                ]

            ]
        );

        $this->add_control(
            'cat_icons',
            [
                'label' => __('Icons', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'true',
                'default' => '',
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'pe-core'),
                'description' => esc_html__('Only Material Icons allowed, do not select Font Awesome icons.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'material-icons md-arrow_outward',
                    'library' => 'material-design-icons',
                ],
                'condition' => ['cat_icons' => 'true'],

            ]
        );

        $this->add_control(
            'category_counts',
            [
                'label' => __('Counts', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'pe-core '),
                'label_off' => __('Hide', 'pe-core '),
                'return_value' => 'true',
                'default' => '',
            ]
        );

        $this->add_control(
            'cats_linked',
            [
                'label' => __('Linked?', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'true',
                'default' => '',
            ]
        );

        $this->add_control(
            'cats_seperator',
            [
                'label' => __('Seperator', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'true',
                'default' => '',
            ]
        );

        $this->add_control(
            'seperator_text',
            [
                'label' => esc_html__('Seperator', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Eg: /', 'pe-core'),
                'default' => esc_html__('/', 'pe-core'),
                'condition' => [
                    'cats_seperator' => 'true',
                ],
            ]
        );

        $this->add_control(
            'cats_underline',
            [
                'label' => __('Underline', 'pe-core '),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pe-core '),
                'label_off' => __('No', 'pe-core '),
                'return_value' => 'true',
                'prefix_class' => 'cats--underline--',
                'default' => '',
            ]
        );

        $this->end_controls_section();


        pe_text_animation_settings($this);
        pe_cursor_settings($this);

        $this->start_controls_section(
            'style',
            [
                'label' => esc_html__('Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_type',
            [
                'label' => esc_html__('Text Size', 'pe-core'),
                'description' => esc_html__('This option will not change HTML tag of the element, this option only for typographic scaling.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'text-p' => [
                        'title' => esc_html__('P', 'pe-core'),
                        'icon' => ' eicon-editor-paragraph',
                    ],
                    'text-h1' => [
                        'title' => esc_html__('H1', 'pe-core'),
                        'icon' => ' eicon-editor-h1',
                    ],
                    'text-h2' => [
                        'title' => esc_html__('H2', 'pe-core'),
                        'icon' => ' eicon-editor-h2',
                    ],
                    'text-h3' => [
                        'title' => esc_html__('H3', 'pe-core'),
                        'icon' => ' eicon-editor-h3',
                    ],
                    'text-h4' => [
                        'title' => esc_html__('H4', 'pe-core'),
                        'icon' => ' eicon-editor-h4',
                    ],
                    'text-h5' => [
                        'title' => esc_html__('H5', 'pe-core'),
                        'icon' => ' eicon-editor-h5',
                    ],
                    'text-h6' => [
                        'title' => esc_html__('H6', 'pe-core'),
                        'icon' => ' eicon-editor-h6',
                    ]
                ],
                'default' => 'text-h3',
                'toggle' => true,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'titles_typography',
                'label' => esc_html__('Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--portfolio--categories ul li.portfolio--category p'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'counts_typography',
                'label' => esc_html__('Counts Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--portfolio--categories ul li span.portfolio--category--count'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'seperator_typography',
                'label' => esc_html__('Seperators Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pe--portfolio--categories ul li span.cats--seperator'
            ]
        );


        flexOptions($this, false, '.pe--portfolio--categories ul li.portfolio--category p', 'items', 'Items');

        $this->add_responsive_control(
            'items_padding',
            [
                'label' => esc_html__('Padding', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pe--portfolio--categories ul li.portfolio--category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'images_border-radius',
            [
                'label' => esc_html__('Border Radius', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pe--portfolio--categories .category--images' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .pe--portfolio--categories ul li span.cat--image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'images_width',
            [
                'label' => esc_html__('Images Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw', 'rem', 'em', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                        'step' => 5
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--portfolio--categories .category--images' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .pe--portfolio--categories ul li span.cat--image' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'images_height',
            [
                'label' => esc_html__('Images Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw', 'rem', 'em', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                        'step' => 5
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .pe--portfolio--categories .category--images' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .pe--portfolio--categories ul li span.cat--image' => 'height: {{SIZE}}{{UNIT}}'
                ],
            ]
        );

        $this->end_controls_section();

        pe_color_options($this);
    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();

        $selectCats = $settings['select_categories'];

        $terms = get_terms([
            'taxonomy' => 'project-categories',
            'hide_empty' => true,
            'include' => !empty($selectCats) ? $selectCats : 'all',
        ]);


        $animation = pe_text_animation($this);
        $cursor = pe_cursor($settings, $this);

        ?>

        <div class="pe--portfolio--categories">

            <?php if ($selectCats) { ?>
                <ul class="categories--list">
                    <?php foreach ($terms as $key => $cat) {
                        $id = $cat->term_id;
                        $name = $cat->name;
                        $count = $cat->count;
                        $link = get_term_link($cat);
                        $term_image_id = get_field('category_thumbnail', 'project-categories_' . $cat->term_id);

                        ?>

                        <li class="portfolio--category" data-id="<?php echo esc_attr($id) ?>" <?php echo $animation ?>>

                            <?php if ($settings['cats_linked'] === 'true') { ?>
                                <a href="<?php echo esc_url($link) ?>" <?php echo $cursor ?>>
                                <?php } ?>

                                <p class="<?php echo esc_attr($settings['text_type']) ?> no-margin">

                                    <?php if ($settings['images_style'] === 'static' && $term_image_id) {
                                        echo '<span class="cat--image">' . wp_get_attachment_image($term_image_id, 'medium_large', false) . '</span>';
                                    } ?>

                                    <span class="cat--name"><?php echo esc_html($name) ?></span>
                                    <?php if ($settings['category_counts'] === 'true') { ?>
                                        <span class="portfolio--category--count">( <?php echo esc_html($count); ?> ) </span>

                                        <?php if ($settings['cats_seperator'] === 'true') { ?>
                                            <span class="cats--seperator"> <?php echo esc_html($settings['seperator_text']) ?> </span>
                                        <?php }

                                        if ($settings['cat_icons'] === 'true') {

                                            ob_start();
                                            \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']);
                                            $icon = ob_get_clean();
                                            ?>
                                            <span class="cats--icon"> <?php echo $icon; ?> </span>
                                        <?php } ?>

                                    </p>

                                <?php }
                                    if ($settings['cats_linked'] === 'true') { ?>
                                </a>
                            <?php } ?>

                        </li>

                    <?php } ?>

                </ul>

                <?php if ($settings['images_style'] === 'hover') { ?>
                    <div class="category--images">

                        <?php foreach ($terms as $key => $cat) {
                            $id = $cat->term_id;
                            $term_image_id = get_field('category_thumbnail', 'project-categories_' . $cat->term_id);

                            if ($term_image_id) {
                                ?>

                                <div class="portfolio--category--image category--image_<?php echo $id ?>">
                                    <?php echo wp_get_attachment_image($term_image_id, 'medium_large', false); ?>
                                </div>

                            <?php }
                        } ?>

                    </div>

                <?php }
            } ?>

        </div>


    <?php }
}