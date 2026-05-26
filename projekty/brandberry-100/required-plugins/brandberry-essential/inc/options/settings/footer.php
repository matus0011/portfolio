<?php

// Footer
CSF::createSection(BRANDBERRY_ESSENTIAL_OPTION_KEY, array(
	'id'    => 'footer_tab',
	'title' => esc_html__('Footer', 'brandberry'),
	'icon'  => 'fa fa-cog',
	'fields' => array(
		array(
            'type'    => 'subheading',
            'content' => esc_html__('Elementor Footer Builder', 'brandberry'),
        ),
        
		array(
			'type'     => 'callback',
			'function' => 'brandberry_footer_style',
		),
	)
));

// Callback function
function brandberry_footer_style()
{

	if (! class_exists('WCF_ADDONS_Plugin')) {
?>
		<?php esc_html_e("To Customize Footer, Please Install animation addons plugin", 'brandberry'); ?>
	<?php
		return;
	}
	?>
	<style>
		.wcf-hf-btn {
			padding: 18px 24px;
			font-weight: 500;
			font-size: 16px;
			color: #fff;
			background: #121212;
			transition: all 0.3s;
			border-radius: 5px;
			text-decoration: none;
			display: inline-flex;
			align-items: center;
			gap: 10px;
		}

		.wcf-hf-btn:hover {
			color: #fff;
			background: #FC5A11;
		}
	</style>
		
	<h6 class="global-options">Brandberry allows you to create your site footer using Elementor and assign it globally or to specific pages.</h6>
	<a class="wcf-hf-btn" href="<?php echo admin_url('edit.php?post_type=wcf-addons-template&template_type=footer'); ?>">
		<i class="csf-tab-icon fa fa-cog"></i>
		<?php esc_html_e("Customize Footer", 'brandberry'); ?>
	</a>
<?php
}
