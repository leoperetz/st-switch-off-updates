<?php
/*
Plugin Name: St. Disable updates
Plugin URI: http://www.softrest.eu/
Description: Disables update checks in order to speed up the site responsiveness, when you work in WP admin panel. It really helps with development.
Version: 1.5.2
Author: softrest
Author URI: http://www.softrest.eu/
 */

/*
Copyright 2015 Softrest ltd. (email: info@softrest.eu)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if (!defined("WPINC")) {
    die;
}

register_activation_hook(__FILE__, 'xareInit');

function xareInit()
{
    if (version_compare(get_bloginfo('version'), '2.8', '<')) {
        delete_option('dismissed_update_core');
        delete_option('update_core');
        delete_option('update_themes');
        delete_option('update_plugins');
    } else {
        delete_transient('update_core');
        delete_transient('update_themes');
        delete_transient('update_plugins');
    }
    global $userdata;
    if (!current_user_can('upgrade_plugins')) {
        remove_action('admin_notices', 'update_nag', 3);
    }
}

remove_action('init', 'wp_version_check');

/**
 * 2.3 to 2.7
 */
add_action('admin_init', function () {remove_action('admin_init', 'wp_update_plugins');}, 2);
add_action('admin_menu', function () {remove_action('load-plugins.php', 'wp_update_plugins');});
add_action('init', function () {remove_action('init', 'wp_update_plugins');}, 2);
add_action('init', function () {remove_action('init', 'wp_version_check');}, 2);
add_filter('pre_option_update_core', function () {return null;});
add_filter('pre_option_update_plugins', function () {return null;});

/**
 * 2.8 to 3.0"
 */
add_filter('pre_transient_update_core', function () {return null;});
add_filter('pre_transient_update_plugins', function () {return null;});
add_filter('pre_transient_update_plugins', function () {return null;});
add_filter('pre_transient_update_themes', function () {return null;});
remove_action('admin_init', '_maybe_update_core');
remove_action('admin_init', '_maybe_update_plugins');
remove_action('admin_init', '_maybe_update_themes');
remove_action('load-plugins.php', 'wp_update_plugins');
remove_action('load-themes.php', 'wp_update_themes');
remove_action('load-update.php', 'wp_update_plugins');
remove_action('load-update.php', 'wp_update_themes');
remove_action('wp_update_plugins', 'wp_update_plugins');
remove_action('wp_update_themes', 'wp_update_themes');
remove_action('wp_version_check', 'wp_version_check');
wp_clear_scheduled_hook('wp_update_plugins');
wp_clear_scheduled_hook('wp_update_themes');
wp_clear_scheduled_hook('wp_version_check');

/**
 * 3.0
 */
add_filter('pre_site_transient_update_core', function () {return null;});
add_filter('pre_site_transient_update_plugins', function () {return null;});
add_filter('pre_site_transient_update_themes', function () {return null;});
remove_action('load-update-core.php', 'wp_update_plugins');
remove_action('load-update-core.php', 'wp_update_themes');

add_filter('auto_update_plugin', function () {return null;});
add_filter('auto_update_theme', function () {return null;});
