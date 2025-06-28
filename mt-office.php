<?php

/**
 * Plugin Name:       MT Office
 * Plugin URI:        https://avalonbg.com/maxtrade-office/
 * Description:       Utility for managing office activities in an e-store.
 * Version:           1.0.0
 * Author:            Avalon Ltd.
 * Author URI:        https://avalonbg.com
 * Text Domain:       mt-office
 * Domain Path:       /languages
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package MT-Office
 */

// Exit if accessed directly.
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Define plugin constants.
 *
 * MT_OFFICE_PLUGIN_DIR   - Absolute path to the plugin's root directory.
 * MT_OFFICE_INCLUDES_DIR - Absolute path to the plugin's includes subdirectory.
 * MT_OFFICE_CSS_URI      - URL to the plugin's CSS directory.
 * MT_OFFICE_JS_URI       - URL to the plugin's JS directory.
 * MT_OFFICE_VERSION      - Plugin version.
 */
define('MT_OFFICE_PLUGIN_DIR', untrailingslashit(dirname(__FILE__)));
define('MT_OFFICE_INCLUDES_DIR', MT_OFFICE_PLUGIN_DIR . '/includes');
define('MT_OFFICE_CSS_URI', WP_CONTENT_URL . '/plugins/mt-office/css');
define('MT_OFFICE_JS_URI', WP_CONTENT_URL . '/plugins/mt-office/js');
define('MT_OFFICE_VERSION', '1.0.0');

/**
 * Include plugin core files.
 *
 * functions.php - General helper functions and AJAX handlers.
 * admin.php     - Admin interface, settings page and admin hooks.
 */
require_once MT_OFFICE_INCLUDES_DIR . '/functions.php';
require_once MT_OFFICE_INCLUDES_DIR . '/admin.php';

/**
 * Initialize plugin hooks after plugins are loaded.
 */
add_action('plugins_loaded', function () {
	// Load plugin translations.
	mt_office_load_textdomain();
});
