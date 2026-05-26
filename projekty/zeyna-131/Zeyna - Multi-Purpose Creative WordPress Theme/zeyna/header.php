<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Zeyna
 */

if (class_exists("Redux")) {
    $option = get_option('pe-redux');
    $noise = $option['grain_overlay'];
    $disableAdmin = $option['disable--for--admin'];
} else {
    $noise = false;
}
$disableAdmin = true;

?>
<!doctype html>
<html class="first--load ajax--first" <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php echo zeyna_barba(true) ?>>
    <?php wp_body_open(); ?>
    <span hidden class="layout--colors"></span>
    <?php if ($noise) { ?>
        <canvas id="bg--noise" class="bg--noise"></canvas>
    <?php } ?>

    <?php zeyna_popups() ?>

    <div id="page" class="site">

        <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'zeyna'); ?></a>

        <?php
        zeyna_mouse_cursor();
        zeyna_page_transitions();
        zeyna_grid_layout_bg();

        if (class_exists("Redux") && $option['only--home']) {
            is_front_page() ? zeyna_page_loader() : '';
        } else if (!is_woocommerce_page() && !is_404()) {
            if ($disableAdmin && !current_user_can('administrator')) {
                zeyna_page_loader();
            } else if (!$disableAdmin) {
                zeyna_page_loader();
            }
        }

        if (zeyna_header_template()) { ?>

            <header id="masthead" class="site-header header--template pe-items-center <?php echo zeyna_header_classes() ?>">

                <?php echo zeyna_header_template() ?>

            </header><!-- #masthead -->

        <?php } else { ?>

            <div class="pe-section header--default">

                <header id="masthead" class="site-header pe-wrapper pe-items-center <?php zeyna_header_classes() ?>">
                    <div class="pe-col-6">

                        <div class="site-branding">
                            <?php the_custom_logo(); ?>

                            <h5 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                    rel="home"><?php bloginfo('name'); ?></a></h5>

                            <?php

                            $zeyna_description = get_bloginfo('description', 'display');
                            if ($zeyna_description || is_customize_preview()):
                                ?>
                                <p class="site-description">
                                    <?php echo esc_html($zeyna_description); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </p>
                            <?php endif; ?>
                        </div><!-- .site-branding -->

                    </div>

                    <div class="pe-col-6 pe-items-right">

                        <nav id="site-navigation" class="main-navigation">
                            <button class="menu-toggle" aria-controls="primary-menu"
                                aria-expanded="false"><?php esc_html_e('Primary Menu', 'zeyna'); ?></button>
                            <?php
                            wp_nav_menu(
                                array(
                                    'theme_location' => 'menu-1',
                                    'menu_id' => 'primary-menu',
                                )
                            );
                            ?>
                        </nav><!-- #site-navigation -->

                    </div>

                </header><!-- #masthead -->

            </div>

        <?php } ?>