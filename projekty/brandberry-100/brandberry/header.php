<?php
/**
 * The header template for the theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2">
	    <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?> >
        <?php
        wp_body_open();
        $is_elementorpro_exist = function_exists( 'elementor_theme_do_location' );        
        if ( ! $is_elementorpro_exist || ! elementor_theme_do_location( 'header' ) ) {
            get_template_part( 'template-parts/headers/header' );
        }
