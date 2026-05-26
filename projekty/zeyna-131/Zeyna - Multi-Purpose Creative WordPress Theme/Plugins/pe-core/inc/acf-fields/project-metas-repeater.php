<?php

if (!class_exists('acf_field_project_metas_repeater'))
    return;

class acf_field_project_metas_repeater extends acf_field
{

    public $name;
    public $label;
    public $category;
    public $defaults;
    public $settings;

    public function __construct($settings = [])
    {
        $this->name = 'project_metas_repeater';
        $this->label = __('Project Metas Repeater ( Pe-Core )', 'text-domain');
        $this->category = 'layout';
        $this->defaults = [
            'default_rows' => '[
                { "label": "Client", "content": "OpenAI" },
                { "label": "Year", "content": "2025" }
            ]',
        ];

        $this->settings = $settings;

        parent::__construct();
    }

    public function render_field($field)
    {
        ?>
        <div class="project-metas-repeater-wrapper" data-name="<?php echo esc_attr($field['name']); ?>">
            <div class="repeater-items">
                <?php if (!empty($field['value'])): ?>
                    <?php foreach ($field['value'] as $row_index => $row_value): ?>
                        <div class="repeater-item">
                            <input type="text" name="<?php echo esc_attr($field['name']); ?>[<?php echo $row_index; ?>][label]"
                                value="<?php echo esc_attr($row_value['label'] ?? ''); ?>" placeholder="Label" />

                            <input type="text" name="<?php echo esc_attr($field['name']); ?>[<?php echo $row_index; ?>][content]"
                                value="<?php echo esc_attr($row_value['content'] ?? ''); ?>" placeholder="" />

                            <a href="#" class="remove-row">X</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <a href="#" class="acf-button button add-row"><?php echo esc_html('Add Meta', 'pe-core') ?></a>
        </div>
        <?php
    }

    public function render_field_settings($field)
    {
        acf_render_field_setting($field, [
            'label' => __('Default Rows', 'pe-core'),
            'instructions' => __('Enter default rows as JSON. Example: [{"label":"Client","content":"Acme Inc."}]'),
            'type' => 'textarea',
            'name' => 'default_rows',
        ]);
    }

    public function update_value($value, $post_id, $field)
    {
        return $value;
    }

    public function load_value($value, $post_id, $field)
    {
        if (empty($value) && !empty($field['default_rows'])) {
            $decoded = json_decode($field['default_rows'], true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return $value;
    }

    public function format_value($value, $post_id, $field)
    {
        return $value;
    }
}

acf_register_field_type('acf_field_project_metas_repeater');