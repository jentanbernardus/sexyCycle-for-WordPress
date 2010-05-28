<?php

/* 
Plugin Name: sexyCycle for WordPress
Plugin URI: http://github.com/linuslundahl/sexyCycle-for-WordPress/
Description: Uses <a href="http://suprb.com/apps/sexyCycle/">sexyCycle jQuery plugin</a> to cycle through gallery images. (sexyCycle created by <a href="http://suprb.com/">Andreas Pihlström</a>)
Version: 0.3.2
Author: Linus Lundahl
Author URI: http://unwise.se
*/

require_once(dirname(__FILE__).'/inc/admin.inc');

$scfw_settings = get_settings('scfw_settings');

if (!defined('SCFW_PLUGIN_BASENAME')) {
  define('SCFW_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

if (is_admin()) {
  add_action('admin_menu', 'scfw_menu', -999);
} else {
  add_action('wp_head', 'scfw_add_css');
  if (!$scfw_settings['scfw_jquery']) {
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', false, '1.4+', false);
  }
  wp_enqueue_script('easing', WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)) . "/inc/jquery.easing-packed.js", false, '1.3', false);
  wp_enqueue_script('sexycycle', WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)) . "/inc/jquery.sexyCycle-packed.js", false, '0.3', false);
  if ($scfw_settings['scfw_override']) {
    add_filter('post_gallery', 'scfw_gallery_shortcode', 10, 2);
  }
  add_shortcode('sexy-gallery', 'scfw_gallery_shortcode');
}

// Add CSS
function scfw_add_css() {
  echo '<link rel="stylesheet" href="' . WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)) . '/inc/sexyCycle.css' . '" type="text/css" media="screen" />'."\n";
}

// Custom gallery output
function scfw_gallery_shortcode($output, $attr) {
  global $post, $wp_locale, $scfw_settings;

  extract(shortcode_atts(array(
    'order'         => 'ASC',
    'orderby'       => 'menu_order ID',
    'id'            => $post->ID,
    'itemtag'       => 'ul',
    'icontag'       => 'li',
    'captiontag'    => 'span',
    'size'          => $scfw_settings['scfw_img_size'] ? $scfw_settings['scfw_img_size'] : 'large',
    'prev'          => $scfw_settings['scfw_prev'] ? $scfw_settings['scfw_prev'] : 'Prev',
    'next'          => $scfw_settings['scfw_next'] ? $scfw_settings['scfw_next'] : 'Next',
    'stop'          => $scfw_settings['scfw_stop'] ? $scfw_settings['scfw_stop'] : 'Stop',
    'animation'     => $scfw_settings['scfw_animation'] ? $scfw_settings['scfw_animation'] : 'easeOutExpo',
    'controls'      => $scfw_settings['scfw_controls'] ? $scfw_settings['scfw_controls'] : '0',
    'controls_stop' => $scfw_settings['scfw_controls_stop'] ? $scfw_settings['scfw_controls_stop'] : NULL,
    'speed'         => $scfw_settings['scfw_speed'] ? $scfw_settings['scfw_speed'] : '400',
    'interval'      => $scfw_settings['scfw_interval'] ? $scfw_settings['scfw_interval'] : '',
    'caption'       => $scfw_settings['scfw_caption'] ? $scfw_settings['scfw_caption'] : '0',
    'cycle'         => $scfw_settings['scfw_cycle'] ? $scfw_settings['scfw_cycle'] : NULL
  ), $attr));

  $id = intval($id);

  $attachments = get_children(array(
    'post_parent'     => $id,
    'post_status'     => 'inherit',
    'post_type'       => 'attachment',
    'post_mime_type'  => 'image',
    'order'           => $order,
    'orderby'         => $orderby,
    'size'            => $size,
    'prev'            => $prev,
    'next'            => $next,
    'stop'            => $stop,
    'controls'        => $controls,
    'controls_stop'   => $controls_stop,
    'animation'       => $animation,
    'speed'           => $speed,
    'interval'        => $interval,
    'caption'         => $caption,
    'cycle'           => $cycle
  ));

  if (empty($attachments)) {
    return '';
  }

  if (is_feed()) {
    $output = "\n";
    foreach ( $attachments as $att_id => $attachment ) {
      $output .= wp_get_attachment_link($att_id, 'small', true) . "\n";
    }
  }

  if (!$output) {
    $itemtag = tag_escape($itemtag);

    // Build JS settings
    if ($speed || $animation || $controls || $cycle) {
      $js = "{";

      if ($speed) {
        $js .= "speed: " . $speed . "," ;
      }

      if ($animation) {
        $js .= "easing: '" . $animation . "',";
      }

      if ($controls) {
        $js .= "next: '#next-$id',prev: '#prev-$id',";
      }

      if ($cycle) {
        $js .= "cycle: false,";
      }

      if ($interval) {
        $js .= "interval: " . $interval . ",";
      }

      if ($controls_stop) {
        $js .= "stop: '#stop-$id',";
      }

      $js = rtrim($js, ',');

      $js .= "}";
    }

    // Get user defined classes
    $class_gallery = $scfw_settings['scfw_class_gallery'] ? ' ' . str_replace('.', '', $scfw_settings['scfw_class_gallery']) : '';
    $class_galleryw = $scfw_settings['scfw_class_galleryw'] ? ' ' . str_replace('.', '', $scfw_settings['scfw_class_galleryw']) : '';
    $class_cbefore = $scfw_settings['scfw_class_cbefore'] ? ' ' . str_replace('.', '', $scfw_settings['scfw_class_cbefore']) : '';
    $class_cafter = $scfw_settings['scfw_class_cafter'] ? ' ' . str_replace('.', '', $scfw_settings['scfw_class_cafter']) : '';
    $class_cunder = $scfw_settings['scfw_class_cunder'] ? ' ' . str_replace('.', '', $scfw_settings['scfw_class_cunder']) : '';

    // Begin gallery output
    $output .= "<div class=\"gallery" . $class_gallery . "\">\n";

    // Add JS for each gallery
    $output .= apply_filters('gallery_style', "<script type=\"text/javascript\">jQuery(function($) { $(\"#box-$id\").sexyCycle($js); });</script>\n");

    // Controls (prev)
    if ($controls == 'beforeafter') {
      $output .= "  <div class=\"controllers before" . $class_cbefore . "\"><span id=\"prev-$id\" class=\"prev cursor\">" . $prev . "</span></div>\n";
    }

    $output .= "<div class=\"gallery-wrapper" . $class_galleryw . "\">\n";

    $output .= "<div class=\"sexyCycle\" id=\"box-$id\">\n";
    $output .= "  <div class=\"sexyCycle-wrap\">\n";
    $output .= "  <{$itemtag} class=\"sexyCycle-content\">\n";

    // Create list items with images
    foreach ( $attachments as $gallery_id => $attachment ) {
      $link = wp_get_attachment_image($gallery_id, $size, false, false);
      $output .= "    <{$icontag}>$link";

      // Caption
      if ($caption == 'caption' && trim($attachment->post_excerpt)) {
        $output .= "<{$captiontag} class='gallery-caption'>" . wptexturize($attachment->post_excerpt) . "</{$captiontag}>";
      }
      else if ($caption == 'desc' && trim($attachment->post_content)) {
        $output .= "<{$captiontag} class='gallery-caption'>" . wptexturize($attachment->post_content) . "</{$captiontag}>";
      }

      $output .= "</{$icontag}>\n";
    }

    $output .= "  </{$itemtag}>\n";
    $output .= "  </div>\n";
    $output .= "</div>\n";

    // Controls (prev / next)
    if ($controls == 'under') {
      $output .= "  <div class=\"controllers under" . $class_cunder . "\"><span id=\"prev-$id\" class=\"prev cursor\">" . $prev . "</span><span id=\"next-$id\" class=\"next cursor\">" . $next . "</span></div>";
    }

    // Controls (stop)
    if ($controls_stop) {
      $output .= "  <div class=\"controllers stop\"><span id=\"stop-$id\" class=\"stop cursor\">" . $stop . "</span></div>";
    }

    $output .= "</div>\n";

    // Controls (next)
    if ($controls == 'beforeafter') {
      $output .= "  <div class=\"controllers after" . $class_cafter . "\"><span id=\"next-$id\" class=\"next cursor\">" . $next . "</span></div>\n";
    }

    // End gallery output
    $output .= "</div>\n";

  }

  return $output;
}

?>