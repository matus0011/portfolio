<?php

// Header
CSF::createSection(BRANDBERRY_ESSENTIAL_OPTION_KEY, array(
	'id'     => 'header_tab',
	'title'  => esc_html__('Header', 'brandberry'),
	'icon'   => 'fa fa-home',
	'fields' => array(
		array(
            'type'    => 'subheading',
            'content' => esc_html__('Elementor Header Builder', 'brandberry'),
        ),
	        
		array(
			'type'     => 'callback',
			'function' => 'brandberry_header_options',
		),
	)
));

// Callback function
function brandberry_header_options()
{

	if (! class_exists('WCF_ADDONS_Plugin')) {
?>
		<?php esc_html_e("To Customize Header install animation addons plugin", 'brandberry'); ?>
	<?php
		return;
	}
	?>
		
	<h6 class="global-options">Brandberry allows you to create your site header using Elementor and assign it globally or to specific pages.</h6>
	
	<a class="wcf-hf-btn" href="<?php echo admin_url('edit.php?post_type=wcf-addons-template&template_type=header'); ?>">
		<i class="csf-tab-icon fa fa-cog"></i>
		<?php esc_html_e(" Customize Header", 'brandberry'); ?>
	</a>
<?php
}
