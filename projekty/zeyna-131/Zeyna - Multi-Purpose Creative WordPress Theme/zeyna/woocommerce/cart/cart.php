<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');

?>
<div class="pe-section zeyna--cart--section" data-barba-prevent="all">
    <div class="pe-wrapper zeyna--cart--wrapper">
        <div class="form--col">

            <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                <?php do_action('woocommerce_before_cart_table'); ?>

                <div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">

                    <?php do_action('woocommerce_before_cart_contents'); ?>

                    <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item):

                        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                        if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)):
                            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                            ?>

                            <div class="woocommerce-cart-form__cart-item cart_item pe--styled--object">

                                <!-- Thumbnail -->
                                <div class="product-thumbnail">
                                    <?php
                                    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

                                    if (!$product_permalink) {
                                        echo wp_kses_post($thumbnail);
                                    } else {
                                        printf(
                                            '<a href="%s">%s</a>',
                                            esc_url($product_permalink),
                                            wp_kses_post($thumbnail)
                                        );
                                    }
                                    ?>
                                </div>

                                <!-- Product Info -->
                                <div class="product-name product-meta"
                                    data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
                                    <h6>
                                        <?php if (!$product_permalink) {
                                            echo wp_kses_post($_product->get_name());
                                        } else {
                                            echo wp_kses_post(sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()));
                                        } ?>
                                    </h6>

                                    <?php
                                    do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);


                                    $attributes = $_product->get_attributes();

                                    if (!empty($attributes)) {
                                        echo '<div class="product-attributes">';
                                        foreach ($attributes as $attribute) {
                                            if (is_object($attribute)) {

                                                $taxonomy = $attribute->get_name(); // örn: "pa_color"
                            
                                                // Sadece taxonomy tipindeki attribute’lerle ilgilenelim
                                                if ($attribute->is_taxonomy()) {
                                                    $terms = wc_get_product_terms($_product->get_id(), $taxonomy, array('fields' => 'all'));

                                                    if (!empty($terms)) {
                                                        foreach ($terms as $term) {
                                                            echo '<div class="product-attribute">';
                                                            echo '<span>' . wc_attribute_label($taxonomy) . ': </span>';
                                                            echo '<span>' . esc_html($term->name) . '</span>';
                                                            echo '</div>';
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                        echo '</div>';
                                    }

                                    // Meta data
                                    echo wc_get_formatted_cart_item_data($cart_item);

                                    // Price x Qty
                                    $quantity = $cart_item['quantity'];
                                    $price = wc_price($_product->get_price());
                                    echo '<p class="cart--item--price">' . $quantity . ' x ' . $price . '</p>';

                                    // Extra options
                                    if (isset($cart_item['extra_options'])) {
                                        echo '<div class="cart--item--extra--options">';
                                        foreach ($cart_item['extra_options'] as $option) {
                                            echo '<div class="cart--item--extra">';
                                            echo esc_html($option['label']);
                                            echo ' <strong>+' . wc_price($option['price']) . '</strong>';
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                    }

                                    // Backorder notice
                                    if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                        echo '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>';
                                    }
                                    ?>
                                </div>

                                <!-- Subtotal + Quantity + Remove -->
                                <div class="product-subtotal" data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>">
                                    <?php
                                    echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);

                                    // Quantity input
                                    if ($_product->is_sold_individually()) {
                                        $min_quantity = 1;
                                        $max_quantity = 1;
                                    } else {
                                        $min_quantity = 0;
                                        $max_quantity = $_product->get_max_purchase_quantity();
                                    }

                                    $product_quantity = woocommerce_quantity_input(
                                        array(
                                            'input_name' => "cart[{$cart_item_key}][qty]",
                                            'input_value' => $cart_item['quantity'],
                                            'max_value' => $max_quantity,
                                            'min_value' => $min_quantity,
                                            'product_name' => $_product->get_name(),
                                        ),
                                        $_product,
                                        false
                                    );
                                    ?>

                                    <div class="zeyna--quantity--control pe--styled--object">
                                        <span class="quantity--decrease pe--styled--object"><svg
                                                xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                                width="24px">
                                                <path d="M240-460v-40h480v40H240Z" />
                                            </svg></span>
                                        <span
                                            class="current--quantity pe--styled--object"><?php echo esc_html($cart_item['quantity']); ?></span>
                                        <span class="quantity--increase pe--styled--object"><svg
                                                xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                                width="24px">
                                                <path d="M460-460H240v-40h220v-220h40v220h220v40H500v220h-40v-220Z" />
                                            </svg></span>
                                    </div>

                                    <?php echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); ?>

                                    <div class="product-remove">
                                        <?php
                                        echo apply_filters(
                                            'woocommerce_cart_item_remove_link',
                                            sprintf(
                                                '<a href="%s" class="remove pe--styled--object" aria-label="%s" data-product_id="%s" data-product_sku="%s"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"><path d="M256-227.69 227.69-256l224-224-224-224L256-732.31l224 224 224-224L732.31-704l-224 224 224 224L704-227.69l-224-224-224 224Z"/></svg></a>',
                                                esc_url(wc_get_cart_remove_url($cart_item_key)),
                                                esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($_product->get_name()))),
                                                esc_attr($product_id),
                                                esc_attr($_product->get_sku())
                                            ),
                                            $cart_item_key
                                        );
                                        ?>
                                    </div>
                                </div>

                            </div><!-- .cart_item -->

                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php do_action('woocommerce_cart_contents'); ?>

                    <div class="zeyna--cart--actions">

                        <button type="submit" class="zeyna--update--cart button" name="update_cart"
                            value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>">
                            <?php esc_html_e('Update cart', 'woocommerce'); ?>
                        </button>

                        <?php do_action('woocommerce_cart_actions'); ?>
                        <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                    </div>

                    <?php do_action('woocommerce_after_cart_contents'); ?>

                </div>
                <?php do_action('woocommerce_after_cart_table'); ?>
            </form>



        </div>
        <div class="cart--totals--col">

            <?php do_action('woocommerce_before_cart_collaterals'); ?>

            <div class="cart-collaterals">
                <?php
                /**
                 * Cart collaterals hook.
                 *
                 * @hooked woocommerce_cross_sell_display
                 * @hooked woocommerce_cart_totals - 10
                 */
                do_action('woocommerce_cart_collaterals');
                ?>
            </div>

        </div>

    </div>
</div>



<?php do_action('woocommerce_after_cart'); ?>