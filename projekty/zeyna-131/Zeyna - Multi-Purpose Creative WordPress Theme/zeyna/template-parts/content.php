<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Zeyna
 */

		if ( is_singular() ) :
		get_template_part( 'template-parts/content', 'post-singular' );
		else :
			get_template_part( 'template-parts/content', 'post-archive' );
		endif;
?>
