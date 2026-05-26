<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

if (!defined('ABSPATH')) {
	exit;
}

global $product;

if (class_exists('Redux')) {

	$option = get_option('pe-redux');
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
	echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
	return;
}


?>

<!-- <span class="zeyna--checkut--form--progress">
	<span></span>
</span> -->

<?php

$enable_checkout_login_reminder = get_option('woocommerce_enable_checkout_login_reminder');
if (!is_user_logged_in() && $enable_checkout_login_reminder === 'yes' && !WC()->cart->is_empty()) { ?>
	<div class="pe-section zeyna--checkout--section zeyna--checkout--login" data-barba-prevent="all">
		<div class="pe-wrapper zeyna--checkout--wrapper">
			<div class="pe-col-4 sm-12 order--col"></div>
			<div class="pe-col-8 sm-12 form--col login--form--col">
				<?php
				echo do_shortcode('[zeyna_login_register]'); ?>

			</div>
		</div>
	</div>
<?php } ?>


<?php if (function_exists('WC') && WC()->checkout && !WC()->cart->is_empty()) { ?>

	<form name="checkout" method="post" class="checkout zeyna--checkout--form woocommerce-checkout"
		action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

		<div class="pe-section zeyna--checkout--section" data-barba-prevent="all">
			<div class="pe-wrapper zeyna--checkout--wrapper">

				<div class="pe-col-4 sm-12 order--col order--col--main">
					<?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

					<p id="order_review_heading"><?php esc_html_e('Order Summary', 'woocommerce'); ?></p>

					<?php do_action('woocommerce_checkout_before_order_review'); ?>

					<div id="order_review" class="woocommerce-checkout-review-order">
						<?php do_action('woocommerce_checkout_order_review'); ?>
					</div>

					<?php do_action('woocommerce_checkout_after_order_review'); ?>
				</div>

				<div class="pe-col-8 sm-12 form--col">

					<div class="zeyna--checkout--tabs--titles">

					</div>

					<div class="zeyna--checkout--accordion">

						<?php if ($checkout->get_checkout_fields()): ?>

							<div class="checkout--accordion--field active">
								<?php do_action('woocommerce_checkout_before_customer_details'); ?>

								<div class="checkout--accordion--title">
									<h6><?php esc_html_e('Contact Information', 'woocommerce'); ?>
										<span class="field--checked">
											<?php
											echo file_get_contents(get_template_directory_uri() . '/assets/img/check.svg');
											?>
										</span>
									</h6>

								</div>

								<div class="checkout--accordion--content">
									<div class="zeyna--woocommerce--mail">
										<?php woocommerce_form_field('billing_email', ['type' => 'email', 'label' => __('E-mail', 'woocommerce'), 'required' => true,], $checkout->get_value('billing_email')); ?>
									</div>
									<div class="zeyna--woocommerce--phone">
										<?php woocommerce_form_field('billing_phone', ['type' => 'tel', 'label' => __('Phone', 'woocommerce'), 'required' => true,], $checkout->get_value('billing_phone')); ?>
									</div>

									<p class="zeyna--accordion--button">
										<?php esc_html_e('Continue Billing Address', 'woocommerce'); ?>
										<svg xmlns="http://www.w3.org/2000/svg" height="1.3em" viewBox="0 -960 960 960"
											width="1.3em" fill="var(--mainBackground)">
											<path d="M444-768v438L243-531l-51 51 288 288 288-288-51-51-201 201v-438h-72Z" />
										</svg>
									</p>
								</div>

							</div>

							<div class="checkout--accordion--field">
								<div class="checkout--accordion--title">
									<h6><?php esc_html_e('Billing Address', 'woocommerce'); ?>
										<span class="field--checked">
											<?php
											echo file_get_contents(get_template_directory_uri() . '/assets/img/check.svg');
											?>
										</span>
									</h6>
								</div>

								<div class="checkout--accordion--content">
									<?php do_action('woocommerce_checkout_billing'); ?>
									<?php do_action('woocommerce_checkout_after_customer_details'); ?>

									<p class="zeyna--accordion--button">
										<?php esc_html_e('Continue Shipping Address', 'woocommerce'); ?>
										<svg xmlns="http://www.w3.org/2000/svg" height="1.3em" viewBox="0 -960 960 960"
											width="1.3em" fill="var(--mainBackground)">
											<path d="M444-768v438L243-531l-51 51 288 288 288-288-51-51-201 201v-438h-72Z" />
										</svg>
									</p>
								</div>

							</div>

							<div class="checkout--accordion--field">
								<div class="checkout--accordion--title">
									<h6><?php esc_html_e('Shipping Address', 'woocommerce'); ?>
										<span class="field--checked">
											<?php
											echo file_get_contents(get_template_directory_uri() . '/assets/img/check.svg');
											?>
										</span>
									</h6>
								</div>

								<div class="checkout--accordion--content">

									<?php do_action('woocommerce_checkout_shipping'); ?>
									<?php do_action('woocommerce_checkout_after_customer_details'); ?>

									<p class="zeyna--accordion--button">
										<?php esc_html_e('Continue Shipping Options', 'woocommerce'); ?>
										<svg xmlns="http://www.w3.org/2000/svg" height="1.3em" viewBox="0 -960 960 960"
											width="1.3em" fill="var(--mainBackground)">
											<path d="M444-768v438L243-531l-51 51 288 288 288-288-51-51-201 201v-438h-72Z" />
										</svg>
									</p>
								</div>

							</div>

							<div class="checkout--accordion--field">
								<div class="checkout--accordion--title">
									<h6><?php esc_html_e('Shipping Options', 'woocommerce'); ?>
										<span class="field--checked">
											<?php
											echo file_get_contents(get_template_directory_uri() . '/assets/img/check.svg');
											?>
										</span>
									</h6>
								</div>
								<div class="checkout--accordion--content">
									<div class="zeyna--woocommerce--shipping">

										<?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()): ?>

											<?php do_action('woocommerce_review_order_before_shipping'); ?>

											<?php wc_cart_totals_shipping_html(); ?>

											<?php do_action('woocommerce_review_order_after_shipping'); ?>

										<?php endif; ?>
									</div>
									<p class="zeyna--accordion--button">
										<?php esc_html_e('Continue Payment', 'woocommerce'); ?>
										<svg xmlns="http://www.w3.org/2000/svg" height="1.3em" viewBox="0 -960 960 960"
											width="1.3em" fill="var(--mainBackground)">
											<path d="M444-768v438L243-531l-51 51 288 288 288-288-51-51-201 201v-438h-72Z" />
										</svg>
									</p>
								</div>
							</div>
							<div class="checkout--accordion--field field--payment">
								<div class="checkout--accordion--title">
									<h6><?php esc_html_e('Payment', 'woocommerce'); ?></h6>
								</div>
								<div class="checkout--accordion--content">
									<div class="zeyna--woocommerce--payment">
										<?php woocommerce_checkout_payment(); ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					</div>

				</div>



	</form>

<?php } else { ?>

	<div class="zeyna--cart--empty">

		<div class="zeyna--empty--cart--wrap">

			<div class="empty--cart--icon">
				<?php echo do_shortcode(file_get_contents(get_template_directory_uri() . '/assets/img/box_open.svg')) ?>
			</div>

			<div class="empty--cart--text">

				<p><?php echo esc_html('Looks like you have not added anything to you cart. Go ahead and explore our top products.') ?>
				</p>

			</div>


			<?php if (wc_get_page_id('shop') > 0): ?>
				<p class="return-to-shop">

					<?php

					$href = '';

					if (isset($option['empty_cart_button_url'])) {
						$href = esc_url($option['empty_cart_button_url']);
					} else {

						$href = esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop')));
					}

					?>

					<a class="button wc-backward<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"
						href="<?php echo esc_url($href); ?>">
						<?php
						/**
						 * Filter "Return To Shop" text.
						 *
						 * @since 4.6.0
						 * @param string $default_text Default text.
						 */

						if (isset($option['empty_cart_button_text'])) {

							echo esc_html($option['empty_cart_button_text']);


						} else {

							echo esc_html(apply_filters('woocommerce_return_to_shop_text', __('Return to shop', 'woocommerce')));

						}


						?>
					</a>
				</p>
			<?php endif; ?>

		</div>

	</div>

<?php } ?>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>