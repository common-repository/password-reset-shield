<?php
/**
 * Plugin name: Password Reset Shield
 * Author: Premium WP Suite
 * Author URI: http://www.premiumwpsuite.com
 * Version: 2.18.18
 * Description: Simple and easy way to protect WordPress Password Reset functionality.
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

// Die if it's called directly
if (!defined('ABSPATH')) {
  exit;
}

define('WPS_PRS_TEXTDOMAIN', 'wps_prs_lng');
define('WPS_PRS_VERSION', '2.18.18');
define('WPS_PRS_SLUG', 'wps_prs');
define('WPS_PRS_URI', plugin_dir_url(__FILE__));
define('WPS_PRS_DIR', plugin_dir_path(__FILE__));


class wps_prs {


  public static function init() {

    if (is_admin()) {
      self::register_admin_menu();
      self::register_enqueue();
    } else {
      self::password_reset_hook();
    }

  } // init


  public static function register_admin_menu() {
    add_action('admin_menu', array(__CLASS__, 'admin_menu'));
  }


  public static function register_enqueue() {
    add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_styles'));
  }


  public static function password_reset_hook() {
    $option = get_option('wps_prs');

    if ($option['enable_reset'] == 'disabled') {
      add_action('lost_password', array(__CLASS__, 'lost_password_disabled'));
      add_filter('show_password_fields', array(__CLASS__, 'disable_password_reset'));
      add_filter('allow_password_reset', array(__CLASS__, 'disable_password_reset'));
      add_filter('gettext', array(__CLASS__, 'remove_recovery_link'));
    }

  }


  public static function admin_styles() {
    wp_enqueue_style('wps-prs-admin', WPS_PRS_URI . 'assets/admin.css', array(), WPS_PRS_VERSION);
  }


  public static function lost_password_disabled() {
    $option = get_option('wps_prs');

    if ($option['gather_details'] == 'enabled') {
      $message = "";
      $message .= "Someone tried to open password recovery page on site " . site_url() . "\r\n";
      $message .= "== Details == \r\n";
      $message .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\r\n";
      $message .= "Time: " . current_time('mysql') . "\r\n";
      $message .= "Browser: " . $_SERVER['HTTP_USER_AGENT'] . "\r\n";
      wp_mail(get_bloginfo('admin_email'), 'Someone opened Password Recovery Page on site ' . site_url(), $message);
    }

    if ($option['enable_reset'] == 'disabled') {
      echo 'Password recovery is disabled.';
      exit;
    }
  }


  public static function remove_recovery_link($text) {
    return str_replace(array('Lost your password?', 'Lost your password'), '', trim($text, '?'));
  }


  public static function disable_password_reset() {
    if (is_admin()) {
      $userdata = wp_get_current_user();
      $user = new WP_User($userdata->ID);
      if (!empty($user->roles) && is_array($user->roles) && $user->roles[0] == 'administrator') {
        return true;
      }
    }

    return false;
  }


  public static function admin_menu() {
    add_menu_page(
      __('Password Shield', WPS_PRS_TEXTDOMAIN),
      'Password Shield',
      'manage_options',
      'wps_prs_dashboard',
      array(__CLASS__, 'wps_prs_dashboard'),
      plugins_url('assets/icon-32.png', __FILE__));
  }


  public static function wps_prs_dashboard() {

    if (!empty($_POST['wps_prs']) && wp_verify_nonce($_POST['wps_prs_dashboard'], 'save_dashboard')) {
      $sanitized = array();
      $options = $_POST['wps_prs'];

      foreach ($_POST['wps_prs'] as $key => $option) {
        $sanitized[$key] = sanitize_text_field($option);
      }

      update_option('wps_prs', $sanitized);
    }

    include_once 'admin/dashboard.php';
  }


}


function wps_prs_activate() {
  update_option('wps_prs', array('enable_reset' => 'enabled', 'gather_details' => 'enabled'));
}

add_action('init', array('wps_prs', 'init'));
register_activation_hook(__FILE__, 'wps_prs_activate');