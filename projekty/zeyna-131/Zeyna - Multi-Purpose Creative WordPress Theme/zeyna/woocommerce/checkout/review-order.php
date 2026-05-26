<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined('ABSPATH') || exit;
?>
<div class="woocommerce-checkout-review-order">
    <div class="order-items">
        <?php
        do_action('woocommerce_review_order_before_cart_contents');

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

            if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                ?>
                <div class="order-item">
                    <div class="product-thumbnail">
                        <?php
                        $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);

                        if (!$product_permalink) {
                            echo esc_html($thumbnail);
                        } else {
                            printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                        }
                        ?>
                    </div>
                    <div class="product-info">
                        <span class="product-name"><?php echo wp_kses_post($_product->get_name()); ?></span>
                        <span class="product-quantity">&times;<?php echo esc_html($cart_item['quantity']); ?></span>
                        <span
                            class="product-subtotal"><?php echo WC()->cart->get_product_subtotal($_product, $cart_item['quantity']); ?></span>
                    </div>
                </div>
                <?php
            }
        }

        do_action('woocommerce_review_order_after_cart_contents');
        ?>
    </div>

    <?php if (wc_coupons_enabled()) { ?>
        <div class="cart-row zeyna--coupon--register">
            <div class="zeyna--coupon">
                <input type="text" name="zeyna_coupon_code" class="input-text" id="zeyna_coupon_code" value=""
                    placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" />
                <button type="submit" class="button" name="apply_coupon"
                    value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_html_e('Apply coupon', 'woocommerce'); ?></button>
            </div>
        </div>
    <?php } ?>

    <div class="order-summary">
        <div class="order-subtotal">
            <span class="label"><?php esc_html_e('Subtotal', 'woocommerce'); ?>:</span>
            <span class="value"><?php wc_cart_totals_subtotal_html(); ?></span>
        </div>

        <?php foreach (WC()->cart->get_coupons() as $code => $coupon): ?>
            <div class="order-discount">
                <span class="label"><?php wc_cart_totals_coupon_label($coupon); ?></span>
                <span class="value"><?php wc_cart_totals_coupon_html($coupon); ?></span>
            </div>
        <?php endforeach; ?>

        <?php $chosen_method = WC()->session->get('chosen_shipping_methods')[0] ?? '';
        foreach (WC()->shipping->get_packages() as $package) {
            foreach ($package['rates'] as $method_id => $rate) {
                if ($method_id === $chosen_method) {
                    ?>
                    <div class="order-shipping">
                        <span class="label"><?php esc_html_e('Shipping', 'woocommerce'); ?>:</span>
                        <span
                            class="value"><?php echo wc_price($rate->get_cost()) . ' (' . esc_html($rate->get_label()) . ')'; ?></span>
                    </div>
                    <?php
                    break;
                }
            }
        } ?>

        <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()): ?>
            <div class="order-tax">
                <span class="label"><?php echo esc_html(WC()->countries->tax_or_vat()); ?>:</span>
                <span class="value"><?php wc_cart_totals_taxes_total_html(); ?></span>
            </div>
        <?php endif; ?>

        <div class="order-total">
            <span class="label"><?php esc_html_e('Total', 'woocommerce'); ?>:</span>
            <span class="value"><?php wc_cart_totals_order_total_html(); ?></span>
        </div>
    </div>
</div>