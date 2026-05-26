<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PePricingTable extends Widget_Base
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
        return 'pepricingtable';
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
        return __('Pricing Table', 'pe-core');
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
        return 'eicon-price-table pe-widget';
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
                'label' => __('Pricing Table', 'pe-core'),
            ]
        );


        if (class_exists('WooCommerce')) {
            $this->add_control(
                'content',
                [
                    'label' => esc_html__('Content', 'pe-core'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'manual',
                    'options' => [
                        'manual' => esc_html__('Manual', 'pe-core'),
                        'product' => esc_html__('Product', 'pe-core'),
                    ],

                ]

            );


        } else {
            $this->add_control(
                'content',
                [
                    'label' => esc_html__('Content', 'pe-core'),
                    'type' => \Elementor\Controls_Manager::HIDDEN,
                    'default' => 'manual',
                ]

            );

        }




        if (class_exists('WooCommerce')) {

            $options = [];

            $products = get_posts([
                'post_type' => 'product',
                'numberposts' => -1
            ]);

            foreach ($products as $product) {
                $options[$product->ID] = $product->post_title;
            }
            $this->add_control(
                'select_product',
                [
                    'label' => __('Select Product', 'pe-core'),
                    'label_block' => true,
                    'description' => __('Select product which will be used for the table.', 'pe-core'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $options,
                    'condition' => ['content' => 'product'],
                ]
            );
        }
        $this->add_control(
            'pt_title',
            [
                'label' => esc_html__('Title', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => esc_html__('Write title here', 'pe-core'),
                'default' => esc_html__('Example Service.', 'pe-core'),
                'ai' => false,
                'condition' => ['content' => 'manual'],
            ]
        );

        $this->add_control(
            'pt_price',
            [
                'label' => esc_html__('Price', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => esc_html__('Write price here', 'pe-core'),
                'default' => esc_html__('$87', 'pe-core'),
                'ai' => false,
                'condition' => ['content' => 'manual'],
            ]
        );

        $this->add_control(
            'pt_price_suffix',
            [
                'label' => esc_html__('Price Suffix', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => esc_html__('Eg. /YEAR', 'pe-core'),
                'ai' => false,
                'condition' => ['content' => 'manual'],
            ]
        );

        $this->add_control(
            'pt_short_desc',
            [
                'label' => esc_html__('Short Description', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'label_block' => true,
                'placeholder' => esc_html__('Write your short description here', 'pe-core'),
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. ', 'pe-core'),
                'rows' => 3,
                'ai' => false,
                'condition' => ['content' => 'manual'],
            ]
        );

        $this->add_control(
            'pt_desc',
            [
                'label' => esc_html__('Description', 'pe-core'),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'label_block' => true,
                'placeholder' => esc_html__('Write your description here', 'pe-core'),
                'condition' => ['content' => 'manual'],
            ]
        );


        $this->add_control(
            'pt_link',
            [
                'label' => esc_html__('Link', 'pe-core'),
                'type' => \Elementor\Controls_Manager::URL,
                'options' => ['url', 'is_external', 'nofollow', 'custom_attributes'],
                'default' => [
                    'is_external' => false,
                    'nofollow' => true,
                ],
                'label_block' => false,
                'description' => esc_html__('Leave it empty if you do not want to display link', 'pe-core'),
                'condition' => ['content' => 'manual'],
            ]
        );


        $this->add_control(
            'highlight_table',
            [
                'label' => esc_html__('Highlight Table', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'highlight_text',
            [
                'label' => esc_html__('Highlight Text', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__('Featured', 'pe-core'),
                'ai' => false,
                'condition' => ['highlight_table' => 'true'],
            ]
        );


        $this->end_controls_section();

        pe_button_settings($this, true, false, 'pr_button', true, '');

        $this->start_controls_section(
            'styles',
            [

                'label' => esc_html__('Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Title Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pricing--table--title h6',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'label' => esc_html__('Price Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} span.woocommerce-Price-amount.amount bdi',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typography',
                'label' => esc_html__('Description Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .pricing--table--short--desc p',
            ]
        );


        objectStyles($this, 'table', 'Table', '.pe--pricing--table.pe--styled--object', false, false, false, false, true);
        objectStyles($this, 'highlight', 'Hightlight', '.pricing--table--highlight.pe--styled--object', true, ['highlight_table' => 'true'], false, false, false);

        $this->end_controls_section();
        pe_text_animation_settings($this);
        pe_color_options($this);
        pe_cursor_settings($this);
        pe_button_style_settings($this, 'Button', 'pr_button', false, true, '.pr--button');


    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();



        $tag = 'p';
        // if (!empty($settings['cta_link']['url'])) {
        //     $this->add_link_attributes('cta_link', $settings['cta_link']);
        // }

        $content = $settings['content'];

        $title = $settings['pt_title'];
        $price = $settings['pt_price'];
        $priceSuffix = $settings['pt_price_suffix'];
        $shortDesc = $settings['pt_short_desc'];
        $description = $settings['pt_desc'];

        if (class_exists('WooCommerce')) {
            if ($content === 'product') {
                $id = $settings['select_product'];
                $product = wc_get_product($id);

                if ($product) {
                    $title = $product->get_title();
                    $price = $product->get_price_html();
                    $priceSuffix = $settings['pt_price_suffix'];
                    $shortDesc = $product->get_short_description();
                    $description = $product->get_description();

                    $is_subscription = get_post_meta($id, '_ywsbs_subscription', true);

                }

                $checkoutUrl = wc_get_checkout_url();
            }

        } else {
            $checkoutUrl = '';
        }
        ?>

        <div class="pe--pricing--table pe--styled--object">

            <?php if ($settings['highlight_table'] === 'true') { ?>
                <div class="pricing--table--highlight pe--styled--object">

                    <?php echo $settings['highlight_text'] ?>

                </div>
            <?php } ?>
            <div class="pricing--table--wrapper">

                <div class="pricing--table--title">
                    <h6><?php echo $title ?></h6>
                </div>
                <div class="pricing--table--price">
                    <?php if ($content === 'manual') { ?>
                        <p class="text-h2"><?php echo $price ?></p>
                        <span><?php echo $priceSuffix ?></span>
                    <?php } else {
                        echo $price;
                    } ?>
                </div>
                <div class="pricing--table--short--desc">
                    <p><?php echo $shortDesc ?>
                    </p>
                </div>
                <div class="pricing--table--desc">
                    <?php echo $description ?>

                </div>
                <div data-barba-prevent="all" class="pricing--table--button" data-checkout="<?php echo $checkoutUrl ?>">
                    <?php
                    if ($content === 'manual') {

                        pe_button_render($this, false, false, 'pr_button', false, false, 'pr--button');
                    } else if (class_exists('WooCommerce')) {
                        $id = $settings['select_product'];
                        $product = wc_get_product($id);

                        if (!$product) {
                            return false;
                        }

                        $buttonAttributes = [
                            'href' => esc_url($product->add_to_cart_url()),
                            'data-quantity' => esc_attr(isset($quantity) ? $quantity : 1),
                            'data-product_id' => esc_attr($product->get_id()),
                            'class' => 'pb--handle subscribe--button ajax_add_to_cart',
                        ];

                        pe_button_render($this, $buttonAttributes, false, 'pr_button', 'Get ' . $product->get_title(), false, 'pr--button');
                        ?>
                    <?php } ?>


                </div>

            </div>

        </div>


        <?php
    }

}
