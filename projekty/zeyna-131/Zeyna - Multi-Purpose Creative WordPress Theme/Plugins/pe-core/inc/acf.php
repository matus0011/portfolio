<?php

if (!class_exists('acf_field'))
	return;

add_action('acf/include_field_types', function () {

	include plugin_dir_path(__FILE__) . '../inc/acf-fields/project-metas-repeater.php';
	include plugin_dir_path(__FILE__) . '../inc/acf-fields/image-gallery.php';
});


add_action('acf/include_fields', function () {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group(array(
		'key' => 'group_674defddcbf26',
		'title' => 'Brand Featured Image',
		'fields' => array(
			array(
				'key' => 'field_674defdea7ff2',
				'label' => 'Brand Image',
				'name' => 'brand_image',
				'aria-label' => '',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'array',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
				'allow_in_bindings' => 0,
				'preview_size' => 'medium',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => 'brand',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	));

	acf_add_local_field_group(array(
		'key' => 'group_65eef8ee870fc',
		'title' => 'Header/Footer Settings',
		'fields' => array(
			array(
				'key' => 'field_65eef8eecc0ff',
				'label' => 'Header Behavior',
				'name' => 'header_behavior',
				'aria-label' => '',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'default' => 'Use Global',
					'static' => 'Static',
					'fixed' => 'Fixed',
					'sticky' => 'Sticky',
					'hide' => 'Hide',
				),
				'default_value' => 'default',
				'return_format' => 'value',
				'multiple' => 0,
				'allow_null' => 0,
				'ui' => 0,
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_6636eb6564175',
				'label' => 'Show Footer',
				'name' => 'show_footer',
				'aria-label' => '',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 1,
				'ui_on_text' => 'Show',
				'ui_off_text' => 'Hide',
				'ui' => 1,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
				),
			),
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'portfolio',
				),
			),
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'product',
				),
			),
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'side',
		'style' => 'seamless',
		'label_placement' => 'left',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	));

	acf_add_local_field_group(array(
		'key' => 'group_663d350c70cee',
		'title' => 'Menu Options',
		'fields' => array(
			array(
				'key' => 'field_663d350cf169a',
				'label' => 'Add sub-menu template',
				'name' => 'add_sub',
				'aria-label' => '',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
				'ui_on_text' => 'Yes',
				'ui_off_text' => 'No',
				'ui' => 1,
			),
			array(
				'key' => 'field_663d356cf4a1f',
				'label' => 'Select Template',
				'name' => 'select_template',
				'aria-label' => '',
				'type' => 'post_object',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_663d350cf169a',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'post_type' => array(
					0 => 'elementor_library',
				),
				'post_status' => '',
				'taxonomy' => array(
					0 => 'elementor_library_type:pe-menu',
				),
				'return_format' => 'object',
				'multiple' => 0,
				'allow_null' => 0,
				'bidirectional' => 0,
				'ui' => 1,
				'bidirectional_target' => array(
				),
			),
			array(
				'key' => 'field_674ecfdf09fe3',
				'label' => 'Reveal Style',
				'name' => 'sub_reveal_style',
				'aria-label' => '',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_663d350cf169a',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'slide' => 'Slide',
					'expand' => 'Expand',
				),
				'default_value' => 'slide',
				'return_format' => 'value',
				'multiple' => 0,
				'allow_null' => 0,
				'allow_in_bindings' => 0,
				'ui' => 0,
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_674eda521d08d',
				'label' => 'Overlay at hover',
				'name' => 'overlay_at_hover',
				'aria-label' => '',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_663d350cf169a',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
				'allow_in_bindings' => 0,
				'ui' => 1,
				'ui_on_text' => 'Yes',
				'ui_off_text' => 'No',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'nav_menu_item',
					'operator' => '==',
					'value' => 'all',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	));

	acf_add_local_field_group(array(
		'key' => 'group_65eef1c6b3354',
		'title' => 'Page Settings',
		'fields' => array(
			array(
				'key' => 'field_66378fcb6ccd0',
				'label' => 'Page Layout',
				'name' => 'page_layout',
				'aria-label' => '',
				'type' => 'select',
				'instructions' => 'You can adjust default and switched colors via "Theme Options" panel.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'layout--default' => 'Default',
					'layout--switched' => 'Switched',
				),
				'default_value' => false,
				'return_format' => 'value',
				'multiple' => 0,
				'allow_null' => 0,
				'ui' => 0,
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_6asd6378fcb6ccd0',
				'label' => 'Header Layout',
				'name' => 'header_layout',
				'aria-label' => '',
				'type' => 'select',
				'instructions' => 'You can adjust default and switched colors via "Theme Options" panel.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'use--global' => 'Use Global',
					'default' => 'Default',
					'switched' => 'Switched',
				),
				'default_value' => false,
				'return_format' => 'value',
				'multiple' => 0,
				'allow_null' => 0,
				'ui' => 0,
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_65eef1c6601b6',
				'label' => 'Page Title',
				'name' => 'page_title',
				'aria-label' => '',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
				'ui_on_text' => 'Show',
				'ui_off_text' => 'Hide',
				'ui' => 1,
			),

		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
				),
			),
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'portfolio',
				),
			),
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'product',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'side',
		'style' => 'default',
		'label_placement' => 'left',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	));

	acf_add_local_field_group(array(
		'key' => 'group_68022ea2bc973',
		'title' => 'Category Image',
		'fields' => array(
			array(
				'key' => 'field_68022ea2d0aaa',
				'label' => 'Category Thumbnail',
				'name' => 'category_thumbnail',
				'aria-label' => '',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'id',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
				'allow_in_bindings' => 0,
				'preview_size' => 'medium',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => 'project-categories',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	));

	$option = get_option('pe-redux');
	$defaultMetas = '[{ "label": "Status", "content": "" },
	{ "label": "Role", "content": "" }]';

	if (isset($option['project_metas_repeater'])) {
		$metasRepeater = $option['project_metas_repeater'];

		if (!empty($metasRepeater['meta_label']) && is_array($metasRepeater['meta_label'])) {
			$defaults = [];

			foreach ($metasRepeater['meta_label'] as $key => $label) {
				$content = isset($metasRepeater['meta_content'][$key]) ? $metasRepeater['meta_content'][$key] : '';
				$defaults[] = [
					'label' => $label,
					'content' => $content
				];
			}

			$defaultMetas = json_encode($defaults, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
		}
	}

	acf_add_local_field_group(array(
		'key' => 'group_660a7b6264d3e',
		'title' => 'Project Details',
		'fields' => array(
			array(
				'key' => 'field_6846a1eb3f307',
				'label' => 'Project Metas',
				'name' => 'project_metas',
				'aria-label' => '',
				'type' => 'project_metas_repeater',
				'instructions' => 'You can add/edit default project metas via <a href="' . admin_url('admin.php?page=pe-redux_options&tab=3') . '"><u>Theme Options -> Posts/Pages -> Portfolio Settings -> Metas</u>.</a>',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_rows' => $defaultMetas,
				'allow_in_bindings' => 0,
			),
			array(
				'key' => 'field_660a7bacb9950',
				'label' => 'Excerpt',

				'name' => 'excerpt',
				'aria-label' => '',
				'type' => 'textarea',
				'instructions' => 'Leave it empty if you don\'t want to display project excerpt.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '100',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'maxlength' => '',
				'rows' => '',
				'placeholder' => '',
				'new_lines' => '',
			),
						array(
				'key' => 'field_6846ef73sss7cb1s',
				'label' => 'Project External Link',
				'name' => 'project_external_link_text',
				'aria-label' => '',
				'type' => 'message',
				'instructions' => '',
				'required' => 0,
				'wrapper' => array(
					'width' => '100',
					'class' => 'project--opt--seperator',
					'id' => '',
				),
				'message' => 'Usefull if you want to view an external link button on project pages.',
				'new_lines' => '',
				'esc_html' => 0,
			),
			array(
				'key' => 'field_684846b89d317',
				'label' => '',
				'name' => 'external_link_label',
				'aria-label' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '30',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'maxlength' => '',
				'allow_in_bindings' => 0,
				'placeholder' => 'Link Label',
				'prepend' => '',
				'append' => '',
			),
			array(
				'key' => 'field_684846d59d318',
				'label' => '',
				'name' => 'external_link_url',
				'aria-label' => '',
				'type' => 'url',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '70',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'allow_in_bindings' => 0,
				'placeholder' => 'https://',
			),

		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'portfolio',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	));

	acf_add_local_field_group(array(
		'key' => 'group_660ab17685255',
		'title' => 'Project Options',
		'fields' => array(
			array(
				'key' => 'field_6846ef73ss7cb1s',
				'label' => '',
				'name' => '',
				'aria-label' => '',
				'type' => 'message',
				'instructions' => '',
				'required' => 0,
				'wrapper' => array(
					'width' => '100',
					'class' => 'project--opt--seperator',
					'id' => '',
				),
				'message' => 'Global project hero can be selected via <a href="' . admin_url('admin.php?page=pe-redux_options&tab=3') . '"><u>Theme Options -> Posts/Pages -> Portfolio Settings -> Global Project Hero Template</u></a>',
				'new_lines' => '',
				'esc_html' => 0,
			),
			array(
				'key' => 'field_660a7e1209eaf',
				'label' => 'Hero Style',
				'name' => 'hero_style',
				'aria-label' => '',
				'type' => 'select',

				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'global' => 'Use Global',
					'template' => 'Template',
					'default' => 'Default',
					'none' => 'No Hero',
				),
				'default_value' => 'global',
				'return_format' => 'value',
				'multiple' => 0,
				'allow_null' => 0,
				'ui' => 0,
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_660a80836b499',
				'label' => 'Select Hero Template',
				'name' => 'hero_template',
				'aria-label' => '',
				'type' => 'post_object',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_660a7e1209eaf',
							'operator' => '==',
							'value' => 'template',
						),
					),
				),
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'post_type' => array(
					0 => 'elementor_library',
				),
				'post_status' => '',
				'taxonomy' => '',
				'return_format' => 'id',
				'multiple' => 0,
				'allow_null' => 0,
				'bidirectional' => 0,
				'ui' => 1,
				'bidirectional_target' => array(
				),
			),

			array(
				'key' => 'field_660ab176740f1',
				'label' => 'Featured Media',
				'name' => 'media_type',
				'aria-label' => '',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'image' => 'Image',
					'video' => 'Video',
				),
				'default_value' => 'image',
				'return_format' => 'value',
				'multiple' => 0,
				'allow_null' => 0,
				'ui' => 0,
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_660ab22ff1a44',
				'label' => 'Featured Image',
				'name' => 'image',
				'aria-label' => '',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_660ab176740f1',
							'operator' => '==',
							'value' => 'image',
						),
					),
				),
				'wrapper' => array(
					'width' => '100',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'id',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
				'preview_size' => 'medium_large',
			),
			array(
				'key' => 'field_660ab29e87e1b',
				'label' => 'Video Provider',
				'name' => 'video_provider',
				'aria-label' => '',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_660ab176740f1',
							'operator' => '==',
							'value' => 'video',
						),
					),
				),
				'wrapper' => array(
					'width' => '33',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'self' => 'Self hosted',
					'vimeo' => 'Vimeo',
					'youtube' => 'YouTube',
					'stream' => 'Stream',
				),
				'default_value' => 'self',
				'return_format' => 'value',
				'multiple' => 0,
				'allow_null' => 0,
				'ui' => 0,
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_660ab2c0bf8ca',
				'label' => 'Stream URL',
				'name' => 'stream_url',
				'aria-label' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_660ab29e87e1b',
							'operator' => '==',
							'value' => 'stream',
						),
					),
				),
				'wrapper' => array(
					'width' => '33',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'maxlength' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array(
				'key' => 'field_660ab2c0bf8bb',
				'label' => 'Video ID',
				'name' => 'video_id',
				'aria-label' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_660ab29e87e1b',
							'operator' => '==',
							'value' => 'vimeo',
						),
					),
					array(
						array(
							'field' => 'field_660ab29e87e1b',
							'operator' => '==',
							'value' => 'youtube',
						),
					),
				),
				'wrapper' => array(
					'width' => '33',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'maxlength' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array(
				'key' => 'field_660ab4826a3d4',
				'label' => 'Video',
				'name' => 'self_video',
				'aria-label' => '',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_660ab29e87e1b',
							'operator' => '==',
							'value' => 'self',
						),
					),
				),
				'wrapper' => array(
					'width' => '33',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'array',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => 'mp4',
				'preview_size' => 'medium',
			),
			array(
				'key' => 'field_68468d70be64e',
				'label' => 'Video Cover Image',
				'name' => 'video_cover',
				'aria-label' => '',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_660ab176740f1',
							'operator' => '==',
							'value' => 'video',
						),
					),
				),
				'wrapper' => array(
					'width' => '33',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'array',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
				'allow_in_bindings' => 0,
				'preview_size' => 'medium',
			),
			array(
				'key' => 'field_6846ef737cb11',
				'label' => '',
				'name' => '',
				'aria-label' => '',
				'type' => 'message',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => 'project--opt--seperator',
					'id' => '',
				),
				'message' => '',
				'new_lines' => '',
				'esc_html' => 0,
			),
			array(
				'key' => 'field_662f8fe585x41',
				'label' => 'Add Image Gallery',
				'name' => 'add_gallery',
				'aria-label' => '',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
				'ui_on_text' => '',
				'ui_off_text' => '',
				'ui' => 1,
			),
			array(
				'key' => 'field_662f8fe585e7e',
				'label' => 'Add Inner Video',
				'name' => 'video_project',
				'aria-label' => '',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '33',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
				'ui_on_text' => '',
				'ui_off_text' => '',
				'ui' => 1,
			),
			array(
				'key' => 'field_6846e2c9c22b8',
				'label' => 'Project Gallery',
				'name' => 'project_gallery',
				'aria-label' => '',
				'type' => 'image_gallery',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_662f8fe585x41',
							'operator' => '==',
							'value' => 1,
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'allow_in_bindings' => 0,
			),
			array(
				'key' => 'field_6846ef73ss7cb11',
				'label' => '',
				'name' => '',
				'aria-label' => '',
				'type' => 'message',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_662f8fe585e7e',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => 'project--opt--seperator',
					'id' => '',
				),
				'message' => '<i>The video in this section will not be used as <b>"Featured Video"</b> of the project. It will be used for Project Media Player on project hero section (if inserted). If you want to insert a featured video for the project select <b>"Video"</b> for <b>"Featured Media"/</b> section above.</i>',
				'new_lines' => '',
				'esc_html' => 0,
			),

			array(
				'key' => 'field_662f92c549f59',
				'label' => 'Video Provider',
				'name' => 'ph_video_provider',
				'aria-label' => '',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_662f8fe585e7e',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array(
					'width' => '33',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'self' => 'Self-Hosted',
					'vimeo' => 'Vimeo',
					'youtube' => 'YouTube',
				),
				'default_value' => 'self',
				'return_format' => 'value',
				'multiple' => 0,
				'allow_null' => 0,
				'ui' => 1,
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_662f9321ce788',
				'label' => 'Video ID',
				'name' => 'ph_video_id',
				'aria-label' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_662f92c549f59',
							'operator' => '==',
							'value' => 'vimeo',
						),
					),
					array(
						array(
							'field' => 'field_662f92c549f59',
							'operator' => '==',
							'value' => 'youtube',
						),
					),
				),
				'wrapper' => array(
					'width' => '33',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'maxlength' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array(
				'key' => 'field_662f9363ce78b',
				'label' => 'Self Video',
				'name' => 'ph_self_video',
				'aria-label' => '',
				'type' => 'file',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_662f92c549f59',
							'operator' => '==',
							'value' => 'self',
						),
					),
				),
				'wrapper' => array(
					'width' => '33',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'url',
				'library' => 'all',
				'min_size' => '',
				'max_size' => '',
				'mime_types' => 'mp4',
			),
			array(
				'key' => 'field_68468d70be45x',
				'label' => 'Video Cover Image',
				'name' => 'ph_video_cover',
				'aria-label' => '',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_662f8fe585e7e',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array(
					'width' => '33',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'array',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
				'allow_in_bindings' => 0,
				'preview_size' => 'medium',
			),

		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'portfolio',
				),
			),
		),
		'menu_order' => 50,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	));



	acf_add_local_field_group(array(
		'key' => 'group_66d07c66e8dae',
		'title' => 'Product Hero',
		'fields' => array(
			array(
				'key' => 'field_66d07c67eb683',
				'label' => 'Product Layout',
				'name' => 'product_layout',
				'aria-label' => '',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'global' => 'Use Global',
					'default' => 'Theme Default',
					'custom' => 'Custom',
				),
				'default_value' => false,
				'return_format' => 'value',
				'multiple' => 0,
				'allow_null' => 0,
				'ui' => 0,
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_66d07ca3eb684',
				'label' => 'Product Template',
				'name' => 'product_template',
				'aria-label' => '',
				'type' => 'post_object',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_66d07c67eb683',
							'operator' => '==',
							'value' => 'custom',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'post_type' => array(
					0 => 'elementor_library',
				),
				'post_status' => array(
					0 => 'publish',
				),
				'taxonomy' => '',
				'return_format' => 'id',
				'multiple' => 0,
				'allow_null' => 0,
				'bidirectional' => 0,
				'ui' => 1,
				'bidirectional_target' => array(
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'product',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'side',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	));

	acf_add_local_field_group(array(
		'key' => 'group_66d07d1e51570',
		'title' => 'Term Fields',
		'fields' => array(
			array(
				'key' => 'field_66d07d1ea1134',
				'label' => 'Term Color',
				'name' => 'term_color',
				'aria-label' => '',
				'type' => 'color_picker',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'enable_opacity' => 0,
				'return_format' => 'string',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'taxonomy',
					'operator' => '!=',
					'value' => 'category',
				),
				array(
					'param' => 'taxonomy',
					'operator' => '!=',
					'value' => 'post_tag',
				),
				array(
					'param' => 'taxonomy',
					'operator' => '!=',
					'value' => 'post_format',
				),
				array(
					'param' => 'taxonomy',
					'operator' => '!=',
					'value' => 'elementor_library_type',
				),
				array(
					'param' => 'taxonomy',
					'operator' => '!=',
					'value' => 'elementor_library_category',
				),
				array(
					'param' => 'taxonomy',
					'operator' => '!=',
					'value' => 'project-categories',
				),
				array(
					'param' => 'taxonomy',
					'operator' => '!=',
					'value' => 'product_type',
				),
				array(
					'param' => 'taxonomy',
					'operator' => '!=',
					'value' => 'product_visibility',
				),
				array(
					'param' => 'taxonomy',
					'operator' => '!=',
					'value' => 'product_cat',
				),
				array(
					'param' => 'taxonomy',
					'operator' => '!=',
					'value' => 'product_tag',
				),
				array(
					'param' => 'taxonomy',
					'operator' => '!=',
					'value' => 'product_shipping_class',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	));

	acf_add_local_field_group(array(
		'key' => 'group_66f95b01452d9',
		'title' => 'Product Video',
		'fields' => array(
			array(
				'key' => 'field_66f95b013cc70',
				'label' => 'Product Video',
				'name' => 'product_video',
				'aria-label' => '',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'none' => 'None',
					'self' => 'Self-Hosted',
					'vimeo' => 'Vimeo',
					'youtube' => 'Youtube',
				),
				'default_value' => 'none',
				'return_format' => 'value',
				'multiple' => 0,
				'allow_null' => 0,
				'ui' => 0,
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_66f95b513cc71',
				'label' => 'Self Hosted Video',
				'name' => 'self_hosted_video',
				'aria-label' => '',
				'type' => 'file',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_66f95b013cc70',
							'operator' => '==',
							'value' => 'self',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'url',
				'library' => 'all',
				'min_size' => '',
				'max_size' => '',
				'mime_types' => '',
			),
			array(
				'key' => 'field_66f95b6d3cc72',
				'label' => 'Video ID',
				'name' => 'video_id',
				'aria-label' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_66f95b013cc70',
							'operator' => '==',
							'value' => 'vimeo',
						),
					),
					array(
						array(
							'field' => 'field_66f95b013cc70',
							'operator' => '==',
							'value' => 'youtube',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'maxlength' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array(
				'key' => 'field_675d4eede4d1d',
				'label' => 'Use as featured media?',
				'name' => 'use_as_featured_media',
				'aria-label' => '',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_66f95b013cc70',
							'operator' => '!=',
							'value' => 'none',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 1,
				'allow_in_bindings' => 0,
				'ui_on_text' => 'Yes',
				'ui_off_text' => 'No',
				'ui' => 1,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'product',
				),
			),
		),
		'menu_order' => 10,
		'position' => 'side',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	));

	acf_add_local_field_group( array(
		'key' => 'group_68f7d0eeb7d5c',
		'title' => 'Tags Opts',
		'fields' => array(
			array(
				'key' => 'field_68f7d11238ee0',
				'label' => 'Tag Image',
				'name' => 'tag_image',
				'aria-label' => '',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'array',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
				'allow_in_bindings' => 0,
				'preview_size' => 'medium',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => 'post_tag',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	) );

});

