<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link      https://avalonbg.com/maxtrade-office/
 * @since     1.0.0
 *
 * @package   MT-Office
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

global $wpdb;

// Remove plugin options.
$options = array();
foreach ($options as $option) {
    delete_option($option);
    delete_site_option($option);
}

$table_tasks_name = $wpdb->prefix . 'mt_office_tasks';

$wpdb->query("DROP TABLE IF EXISTS $table_tasks_name;");

delete_option('mt_office_db_version');
delete_site_option('mt_office_db_version');

// Clear any cached data that has been removed.
wp_cache_flush();
