<?php
defined('ABSPATH') || exit;

$slug = sanitize_title(wp_unslash($_GET['elementor_library'] ?? ''));
$template_post = $slug ? get_page_by_path($slug, OBJECT, 'elementor_library') : null;

if (!$template_post) {
  status_header(404);
  nocache_headers();
  echo 'Template not found.';
  exit;
}

global $post;
$post = $template_post;
setup_postdata($post);

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
  <style>html,body{margin:0;padding:0;}</style>
</head>
<body <?php body_class('bb-elementor-library-preview'); ?>>

<?php
// This will render Elementor content properly
echo apply_filters('the_content', $post->post_content);
wp_reset_postdata();
wp_footer();
?>

</body>
</html>
