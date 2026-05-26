<?php

wp_nav_menu([
	'menu'                 => 'primary',
	'theme_location'       => 'primary',
	'container'            => false,
	'container_class'      => "primary-menu",	                   // Set the ARIA label
	'menu_id'              => '',
	'menu_class'           => 'brandberry-mb-menu-items',
	'depth'                => 3,
	'walker'          => new \brandberry\Core\Brandberry_Walker_Nav(),
	'fallback_cb'     => '\brandberry\Core\Brandberry_Walker_Nav::fallback',
]);

