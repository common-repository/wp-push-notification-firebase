<?php
/*
Plugin Name: WP Firebase Push Notification
Plugin URI: https://wordpress.org/plugins/
Description: Admin UI for push notifications
Author:Skywave Infotech
Version: 1.2.0
Author URI: https://skywaveinfotech.com/
*/
/*
    Copyright (C) 2019  Skywave Infotech

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
if (!defined('ABSPATH')) {
  exit;
}
if (!defined("WFPN_VERSION_CURRENT")) define("WFPN_VERSION_CURRENT", '1');
if (!defined("WFPN_URL")) define("WFPN_URL", plugin_dir_url(__FILE__));
if (!defined("WFPN_PLUGIN_DIR")) define("WFPN_PLUGIN_DIR", plugin_dir_path(__FILE__));
if (!class_exists('WFPN')) {


  class WFPN
  {
    function wfpn_activate()
    {
      flush_rewrite_rules();
    }
    function wfpn_deactivate()
    {
      flush_rewrite_rules();
    }
    public $pre_name = 'wfpn';
    public function __construct()
    {
      // Installation and uninstallation hooks
      register_activation_hook(__FILE__, array($this, $this->pre_name . '_activate'));
      register_deactivation_hook(__FILE__, array($this, $this->pre_name . '_deactivate'));
      add_action('admin_menu', array($this, $this->pre_name . '_menu_pages'));
      add_action('admin_print_styles', array($this, $this->pre_name . '_add_stylesheet'));
      include(plugin_dir_path(__FILE__) . 'templates/settings.php');
    }

    function wfpn_add_stylesheet()
    {
      wp_enqueue_style('CustomCSS', plugins_url('/assets/css/style.css', __FILE__));
    }

    function wfpn_menu_pages()
    {
      add_menu_page('Push Notification', 'Push Notification', 'manage_options', 'notification', array($this, 'wfpn_brodcast_notification_message'), 'dashicons-email-alt', '9');
      add_submenu_page('notification', 'Settings', 'Settings', 'manage_options', 'notify-settings', 'wfpn_notification_settings');
    }

    function wfpn_brodcast_notification_message()
    {
      if (!empty($_POST)) {
        $post = [];
        $post['brodcast_notification_title'] = sanitize_text_field($_POST['brodcast_notification_title']);
        $post['brodcast_notification_message'] = sanitize_text_field($_POST['brodcast_notification_message']);

        $this->wfpn_send_notifiation_brodcast($post);
      }
      include(plugin_dir_path(__FILE__) . 'templates/brodcast_message.php');
    }

    /* custom notification */

    function wfpn_send_notifiation_brodcast($post)
    {
      $device_token = $this->wfpn_all_users();
      $title = $post['brodcast_notification_title'];
      $body = $post['brodcast_notification_message'];

      $this->wfpn_send_device_notifiation_brodcast($device_token, $title, $body);
      return true;
    }
    /* welcome notification */

    function wfpn_send_welcome_notification($user_id)
    {
      if (null !== (esc_attr(get_option('notify_welcome_enable'))) && (esc_attr(get_option('notify_welcome_enable'))) == '1') {
        $user = get_userdata($user_id);
        $access_token = get_user_meta($user->ID, 'device_token', true);
        if ($access_token) {
          $device_token[0] = $access_token;
        }
        $title = (esc_attr(get_option('notify_welcome_title')));
        $body = (esc_attr(get_option('notify_welcome_message')));

        $this->wfpn_send_device_notifiation_brodcast($device_token, $title, $body);
      } else {
        return;
      }
    }
    /*new post notification */
    function wfpn_send_new_post_notification($postID)
    {
      $post = get_post($postID);
      $checkstatus = get_post_meta($post->ID, 'is_it_new_post', true);

      if (empty($checkstatus)) {
        $postid = $post->ID;
        $post_content = apply_filters('the_content', $post->post_content);
        add_post_meta($postid, 'is_it_new_post', 1);
        $this->wfpn_new_post_notifiation();
      }
    }
    function wfpn_new_post_notifiation()
    {
      if (null !== (esc_attr(get_option('notify_new_post_enable'))) && (esc_attr(get_option('notify_new_post_enable'))) == '1') {
        $device_token = $this->wfpn_all_users();
        $title = (esc_attr(get_option('notify_new_post_title')));
        $body = (esc_attr(get_option('notify_new_post_message')));

        $this->wfpn_send_device_notifiation_brodcast($device_token, $title, $body);
      } else {
        return;
      }
    }
    function wfpn_all_users()
    {
      $getListOfUsers = get_users('orderby=ID');
      $mainArray = [];
      $device_token = array();
      $ii = 0;
      foreach ($getListOfUsers as $getListOfUserkey => $user) {
        $access_token = get_user_meta($user->ID, 'device_token', true);
        if ($access_token) {
          $device_token[$ii] = $access_token;
          $ii++;
        }
      }
      return $device_token;
    }
    /* send push notification */

    function wfpn_send_device_notifiation_brodcast($device_token, $title, $body)
    {

      if (!empty($device_token) && $device_token != 'NULL') {
        $device_token = json_decode(json_encode($device_token));

        $FIREBASE_API_KEY = esc_attr(get_option('notify_firebase_key'));
        $notification = array('title' => $title, 'body' => $body, 'sound' => 'default', 'badge' => '1');
        $fields = array('registration_ids' => $device_token, 'notification' => $notification, 'priority' => 'high', 'data' => $notification);
        $args = array(
          'timeout'   => 45,
          'redirection' => 5,
          'httpversion' => '1.1',
          'method'    => 'POST',
          'body'      => json_encode($fields),
          'sslverify'     => false,
          'headers'     => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . $FIREBASE_API_KEY,
          ),
          'cookies'     => array()
        );

        $response = wp_remote_post('https://fcm.googleapis.com/fcm/send', $args);

        return $response;
      }
    }
  }
  $wfpn = new WFPN();
}
