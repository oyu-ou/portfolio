<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <title><?php bloginfo('name'); ?></title>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <!-- Header -->
  <!-- wp:template-part {"slug":"header"} /-->

  <!-- Content -->
  <!-- wp:query {"queryId":1} /--> <!-- optional dynamic query -->

  <!-- Footer -->
  <!-- wp:template-part {"slug":"footer"} /-->
  <?php wp_footer(); ?>
</body>
</html>