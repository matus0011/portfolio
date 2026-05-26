<?php

if (!class_exists('acf_field_image_gallery')):

class acf_field_image_gallery extends acf_field {

    // ✅ PHP 8.2 için gerekli property tanımlamaları
    public $name;
    public $label;
    public $category;
    public $defaults;

    public function __construct() {
        $this->name = 'image_gallery';
        $this->label = __('Image Gallery ( Pe-Core )', 'pe-core');
        $this->category = 'content';
        $this->defaults = [];

        parent::__construct();
    }

    public function render_field($field) {
        $images = is_array($field['value']) ? $field['value'] : [];
        ?>
        <div class="acf-image-gallery-wrapper" data-name="<?php echo esc_attr($field['name']); ?>">
            <ul class="acf-image-gallery-list">
                <?php foreach ($images as $image_id) : 
                    $url = wp_get_attachment_image_url($image_id, 'thumbnail'); ?>
                    <li class="acf-image-gallery-item" data-id="<?php echo esc_attr($image_id); ?>">
                        <img src="<?php echo esc_url($url); ?>" />
                        <a href="#" class="acf-image-gallery-remove">×</a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="button acf-image-gallery-add">Add Images</button>
            <input type="hidden" name="<?php echo esc_attr($field['name']); ?>" value="<?php echo esc_attr(implode(',', $images)); ?>" />
        </div>
        <?php
    }

    public function update_value($value, $post_id, $field) {
        if (is_string($value)) {
            $value = explode(',', $value);
        }
        return $value;
    }

    public function load_value($value, $post_id, $field) {
        if (is_string($value)) {
            $value = explode(',', $value);
        }
        return $value;
    }
}

acf_register_field_type('acf_field_image_gallery');

endif;