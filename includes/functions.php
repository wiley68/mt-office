<?php

/**
 * Load plugin textdomain for internationalization.
 *
 * This function loads the translation files for the plugin based on the current locale.
 * Translation files must be placed in the /languages directory of the plugin.
 *
 * @since 1.0.0
 *
 * @return void
 */
function mt_office_load_textdomain()
{
    $locale = apply_filters('plugin_locale', determine_locale(), 'mt-office');
    $mofile = 'mt-office' . '-' . $locale . '.mo';
    load_textdomain('mt-office', MT_OFFICE_PLUGIN_DIR . '/languages/' . $mofile);
}

function mt_office_create_tables()
{
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $table_tasks_name = $wpdb->prefix . 'mt_office_tasks';
    $charset_collate = $wpdb->get_charset_collate();

    $sql_tasks = "CREATE TABLE $table_tasks_name (
        `id` MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(256) NOT NULL,
        `value` TEXT NULL,
        `status` TINYINT UNSIGNED NOT NULL DEFAULT 0,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    dbDelta($sql_tasks);

    update_option('mt_office_db_version', MT_OFFICE_DB_VERSION);
}

function mt_office_add_meta_admin($hook)
{
    if ($hook === 'toplevel_page_mt-office-overview') {
        wp_enqueue_style(
            'mt-office-css',
            MT_OFFICE_CSS_URI . '/mt-office-overview.css',
            array(),
            filemtime(MT_OFFICE_PLUGIN_DIR . '/css/mt-office-overview.css'),
            'all'
        );
    }

    if ($hook === 'mt-office_page_mt-office') {
        wp_enqueue_script(
            'mt-office-script',
            plugins_url('../quasar-office/dist/spa/assets/index.js', __FILE__),
            array(),
            filemtime(MT_OFFICE_PLUGIN_DIR . '/quasar-office/dist/spa/assets/index.js'),
            true
        );

        wp_localize_script(
            'mt-office-script',
            'mt_office_rest',
            array(
                'root' => esc_url_raw(rest_url()),
                'nonce' => wp_create_nonce('mt_office_rest'),
                'siteName' => get_bloginfo('name'),
                'pluginName' => 'MT Office',
                'pluginVersion' => MT_OFFICE_VERSION,
                'locale' => determine_locale(),
                'user' => array(
                    'id' => get_current_user_id(),
                    'name' => wp_get_current_user()->display_name,
                    'email' => wp_get_current_user()->user_email,
                ),
                'wordPressVersion' => get_bloginfo('version'),
            )
        );
    }

    if ($hook === 'mt-office_page_mt-office-settings') {
    }
}
