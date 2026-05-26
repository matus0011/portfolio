<?php
use \brandberry\Core\Brandberry_Walker_Nav;

wp_nav_menu([
	'menu'            => 'primary',
	'theme_location'  => 'primary',
	'container'       => false,
	'menu_id'         => '',
	'menu_class'      => '',
	'depth'           => 3,
	'walker'          => new \brandberry\Core\Brandberry_Walker_Nav(),
	'fallback_cb'     => '\brandberry\Core\Brandberry_Walker_Nav::fallback',
]);

