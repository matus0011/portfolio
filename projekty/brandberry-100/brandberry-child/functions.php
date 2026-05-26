<?php
/**
 * Theme functions and definitions.
 */
function brandberry_child_enqueue_styles() {
    
    wp_enqueue_style( 'brandberry-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        [],
        wp_get_theme()->get('Version')
    );
    
}

add_action(  'wp_enqueue_scripts', 'brandberry_child_enqueue_styles', 16);