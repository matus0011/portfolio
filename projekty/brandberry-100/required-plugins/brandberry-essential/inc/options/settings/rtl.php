<?php
// RTL
CSF::createSection(BRANDBERRY_ESSENTIAL_OPTION_KEY, array(
	'id'    => 'wcf_rtl_tab',                     // Set a unique slug-like ID
	'title' => esc_html__('RTL', 'brandberry'),
	'icon'  => 'eicon-rtl',
	'fields' => array(
		array(
			'id'      => 'wcf_rtl_activator',
			'type'    => 'switcher',
			'title'   => esc_html__('Enable RTL (Frontend)', 'brandberry'),
			'default' => false,
		),
	)
));
