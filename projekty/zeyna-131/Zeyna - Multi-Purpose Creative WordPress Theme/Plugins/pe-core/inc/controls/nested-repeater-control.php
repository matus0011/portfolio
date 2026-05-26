<?php
if (!defined('ABSPATH'))
    exit;

use Elementor\Base_Data_Control;

class Nested_Repeater_Control extends Base_Data_Control
{

    public function get_type()
    {
        return 'nested_repeater';
    }   

    public function enqueue()
    {
        // wp_enqueue_script(
        //     'nested-repeater-control',
        //     plugin_dir_url(__FILE__) . 'nested-repeater-control.js',
        //     [ 'jquery', 'elementor-editor' ],
        //     '1.1',
        //     true
        // );
        // wp_enqueue_style(
        //     'nested-repeater-control',
        //     plugin_dir_url(__FILE__) . 'nested-repeater-control.css',
        //     [],
        //     '1.1'
        // );
    }

    protected function get_default_settings()
    {
        return [
            'label_block' => true,
            'show_label' => true,
        ];
    }

    public function content_template()
    {
        ?>
        <div class="elementor-control-field">
            <# if ( data.label ) { #>
                <label class="elementor-control-title 
                    <# if ( data.label_block ) { #> elementor-control-title-block <# } #>">
                    {{{ data.label }}}
                </label>
                <# } #>

                    <div class="nested-repeater-wrapper" data-setting="{{ data.name }}">
                        <button class="elementor-button button add-sub-item">+ Add Sub Text</button>
                    </div>
        </div>
        <?php
    }
}