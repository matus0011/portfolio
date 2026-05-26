<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class peCartBlock extends Widget_Base
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
        return 'pecartblock';
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
        return __('Cart (Block)', 'pe-core');
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
        return 'eicon-woo-cart pe-widget';
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
        return ['zeyna-content'];
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
                'label' => __('Cart (Block)', 'pe-core'),
            ]
        );

        $this->add_control(
            'zeyna_refresh_widget',
            [
                'label' => esc_html__('Refresh Widget', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'refresh' => [
                        'title' => esc_html__('Refresh Widget', 'pe-core'),
                        'icon' => 'eicon-sync',
                    ],
                ],
                'default' => 'refresh',
                'render_type' => 'template',
                'toggle' => true,

            ]
        );

        $this->add_control(
            'account_block_notice_1',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-error'>	
	           <span>If the preview not showing up refresh the page via the button above.</span></div>",

            ]
        );

        flexOptions($this, false, '.pe-wrapper.zeyna--cart--wrapper', 'cart_block_', 'Cart', false, false);


        $this->add_responsive_control(
            'cart_products_width',
            [
                'label' => esc_html__('Products Block Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'vw', 'custom'],
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
                    'em' => [
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
                    '{{WRAPPER}} .pe-wrapper.zeyna--cart--wrapper>.form--col' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'cart_totals_width',
            [
                'label' => esc_html__('Totals Block Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'vw', 'custom'],
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
                    'em' => [
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
                    '{{WRAPPER}} .pe-wrapper.zeyna--cart--wrapper>.cart--totals--col' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'products_styles',
            [
                'label' => __('Products Styles', 'pe-core'),
            ]
        );

        $this->add_responsive_control(
            'images_width',
            [
                'label' => esc_html__('Images Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'vw', 'custom'],
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
                    'em' => [
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
                    '{{WRAPPER}} .woocommerce-cart-form__cart-item.cart_item .product-thumbnail' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'images_height',
            [
                'label' => esc_html__('Images Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'vh', 'custom'],
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
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form__cart-item.cart_item .product-thumbnail' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        objectStyles($this, 'product_', 'Product', '.woocommerce-cart-form__cart-item.cart_item', false, false, false, false, false);

        flexOptions($this, false, '.woocommerce-cart-form__cart-item.cart_item', 'products_flex_', 'Products', false, '.woocommerce-cart-form__cart-item.cart_item > div');

        flexOptions($this, false, '.woocommerce-cart-form__cart-item.cart_item .product-name', 'metas_', 'Product Metas', true);
        flexOptions($this, false, '.woocommerce-cart-form__cart-item.cart_item .product-subtotal', 'controls_', 'Product Controls   ', true);

        $this->end_controls_section();


        $this->start_controls_section(
            'products_section',
            [
                'label' => __('Products Section', 'pe-core'),
            ]
        );

        flexOptions($this, false, '.shop_table.shop_table_responsive.cart.woocommerce-cart-form__contents', 'products_section_', 'Products Section', false, '.woocommerce-cart-form__cart-item.cart_item');


        $this->end_controls_section();

        $this->start_controls_section(
            'totals_section',
            [
                'label' => __('Totals Section', 'pe-core'),
            ]
        );

        // flexOptions($this, false, '.shop_table.shop_table_responsive.cart.woocommerce-cart-form__contents', 'products_section_', 'Products Section', false, '.woocommerce-cart-form__cart-item.cart_item');


        $this->end_controls_section();


        $this->start_controls_section(
            'totals_block_styles',
            [
                'label' => esc_html__('Totals Block Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'cart_title_typography',
                'label' => esc_html__('Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .zeyna--cart--title h4'
            ]
        );

        $this->add_responsive_control(
            'totals_width',
            [
                'label' => esc_html__('Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw', '%', 'rem'],
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
                    '0' => [
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
                    '{{WRAPPER}} .pe-col-4.sm-12.cart--totals--col' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'totals_height',
            [
                'label' => esc_html__('Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw', '%', 'rem', 'custom'],
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
                    '0' => [
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
                    '{{WRAPPER}} .pe-col-4.sm-12.cart--totals--col .cart-collaterals' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'totals_button_border',
                'selector' => '{{WRAPPER}} .cart-collaterals',
            ]
        );


        $this->add_responsive_control(
            'totals_border-radius',
            [
                'label' => esc_html__('Border Radius', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .cart_totals.pe--styled--object' => '--radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'totals_margins',
            [
                'label' => esc_html__('Margins', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .cart--totals--col' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'totals_paddings',
            [
                'label' => esc_html__('Paddings', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .cart_totals.pe--styled--object' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'totals_items_border-radius',
            [
                'label' => esc_html__('Inner Items Radius', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .zeyna--cart--section ul#shipping_method li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .zeyna--coupon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .pe-wrapper.zeyna--cart--wrapper a.checkout-button.button.alt.wc-forward' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .zeyna--cart--wrapper form.woocommerce-shipping-calculator .form-row select, {{WRAPPER}} .zeyna--cart--wrapper form.woocommerce-shipping-calculator .form-row input.input-text, {{WRAPPER}} .zeyna--cart--wrapper form.woocommerce-shipping-calculator .form-row textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'totals_buttons_border',
                'label' => esc_html__('Buttons Border', 'pe-core'),
                'selector' => '{{WRAPPER}} a.button.checkout-button',
            ]
        );


        $this->add_responsive_control(
            'totals_buttons_border-radius',
            [
                'label' => esc_html__('Buttons Radius', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} a.button.checkout-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );


        $this->add_responsive_control(
            'totals_buttons_paddings',
            [
                'label' => esc_html__('Buttons Paddings', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} a.button.checkout-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );



        $this->add_control(
            'totals_colors',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__('Colors', 'pe-core'),
                'label_off' => esc_html__('Default', 'pe-core'),
                'label_on' => esc_html__('Custom', 'pe-core'),
                'return_value' => 'adv--styled',
            ]
        );

        $this->start_popover();

        pe_color_options($this, '.cart--totals--col', 'totals_', false);

        $this->end_popover();


        $this->end_controls_section();


        $this->start_controls_section(
            'products_block_styles',
            [
                'label' => esc_html__('Products Block Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'products_block_width',
            [
                'label' => esc_html__('Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw', '%', 'rem'],
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
                    '0' => [
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
                    '{{WRAPPER}} .pe-col-8.sm-12.form--col' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'products_gap',
            [
                'label' => esc_html__('Gap', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw', '%', 'rem'],
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
                    '0' => [
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
                    '{{WRAPPER}} .zeyna--cart--wrapper .woocommerce-cart-form table.shop_table tbody' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'product_blocks_button_border',
                'selector' => '{{WRAPPER}} .pe-col-8.sm-12.form--col',
            ]
        );


        $this->add_responsive_control(
            'product_blocks_border-radius',
            [
                'label' => esc_html__('Border Radius', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pe-col-8.sm-12.form--col' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'product_blocks_margins',
            [
                'label' => esc_html__('Margins', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pe-col-8.sm-12.form--col' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'product_blocks_paddings',
            [
                'label' => esc_html__('Paddings', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pe-col-8.sm-12.form--col' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );



        $this->add_control(
            'product_blocks_colors',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__('Colors', 'pe-core'),
                'label_off' => esc_html__('Default', 'pe-core'),
                'label_on' => esc_html__('Custom', 'pe-core'),
                'return_value' => 'adv--styled',
            ]
        );

        $this->start_popover();

        pe_color_options($this, '.pe-col-8.sm-12.form--col', 'products_blocks_', false);

        $this->end_popover();


        $this->end_controls_section();

        $this->start_controls_section(
            'cart_product_styles',
            [
                'label' => esc_html__('Product Styles', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        flexOptions($this, false, '.zeyna--cart--wrapper .woocommerce-cart-form table.shop_table tr', 'product', 'Product', true, '.zeyna--cart--wrapper .woocommerce-cart-form table.shop_table tr td');

        $this->add_responsive_control(
            'product_image_width',
            [
                'label' => esc_html__('Image Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw', '%', 'rem'],
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
                    '0' => [
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
                    '{{WRAPPER}} .zeyna--cart--wrapper .woocommerce-cart-form table.shop_table tr td.product-thumbnail' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'product_image_height',
            [
                'label' => esc_html__('Image Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw', '%', 'rem'],
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
                    '0' => [
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
                    '{{WRAPPER}} .zeyna--cart--wrapper .woocommerce-cart-form table.shop_table tr td.product-thumbnail a' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'items_gap',
            [
                'label' => esc_html__('Columns Gap', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw', '%', 'rem'],
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
                    '0' => [
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
                    '{{WRAPPER}} .zeyna--cart--wrapper .woocommerce-cart-form table.shop_table tr' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'products_border',
                'selector' => '{{WRAPPER}} .zeyna--cart--wrapper .woocommerce-cart-form table.shop_table tr',
            ]
        );


        $this->add_responsive_control(
            'products_border-radius',
            [
                'label' => esc_html__('Border Radius', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .zeyna--cart--wrapper .woocommerce-cart-form table.shop_table tr' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'products__image_border-radius',
            [
                'label' => esc_html__('Image Border Radius', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .zeyna--cart--wrapper .woocommerce-cart-form table.shop_table tr td.product-thumbnail a img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'products_paddings',
            [
                'label' => esc_html__('Paddings', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .zeyna--cart--wrapper .woocommerce-cart-form table.shop_table tr' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'products_margins',
            [
                'label' => esc_html__('Margins', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .zeyna--cart--wrapper .woocommerce-cart-form table.shop_table tr' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Titles Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .zeyna--cart--wrapper table.shop_table tbody:first-child td.product-name h6'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'variations_typography',
                'label' => esc_html__('Variations Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .zeyna--cart--wrapper td.product-name dl.variation dd p'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'prices_typography',
                'label' => esc_html__('Prices Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} p.cart--item--price, {{WRAPPER}} span.woocommerce-Price-amount.amount'
            ]
        );

        $this->add_responsive_control(
            'contents_paddings',
            [
                'label' => esc_html__('Content Paddings', 'pe-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .zeyna--cart--wrapper .woocommerce-cart-form table.shop_table tr td:not(.product-thumbnail)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .zeyna--cart--wrapper .woocommerce-cart-form table.shop_table tr .product-remove' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_order',
            [
                'label' => esc_html__('Image Order', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 3,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .zeyna--cart--wrapper .woocommerce-cart-form table.shop_table tr td.product-thumbnail' => 'order: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'details_order',
            [
                'label' => esc_html__('Details Order', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 3,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .zeyna--cart--wrapper table.shop_table tbody:first-child td.product-name' => 'order: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'totals_order',
            [
                'label' => esc_html__('Totals Order', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 3,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .zeyna--cart--wrapper .woocommerce-cart-form table.shop_table tr td.product-subtotal' => 'order: {{VALUE}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'metas_width',
            [
                'label' => esc_html__('Metas Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw', '%', 'rem'],
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
                    '0' => [
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
                    '{{WRAPPER}} .zeyna--cart--wrapper .woocommerce-cart-form table.shop_table tr td.product-name' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'subtotal_width',
            [
                'label' => esc_html__('Subtotal Width', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw', '%', 'rem'],
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
                    '0' => [
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
                    '{{WRAPPER}} .zeyna--cart--wrapper .woocommerce-cart-form table.shop_table tr td.product-subtotal' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        pe_color_options($this);


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        echo do_shortcode('[woocommerce_cart]');



    }

}
