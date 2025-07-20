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
        wp_dequeue_style('wp-admin');
        wp_dequeue_style('common');
        wp_dequeue_style('admin-menu');
        wp_dequeue_style('dashicons');
        wp_dequeue_style('buttons');
        wp_dequeue_style('forms');
        wp_dequeue_style('wp-auth-check');
        wp_dequeue_style('editor-buttons');
        wp_dequeue_style('site-health');
        wp_dequeue_style('revisions');
        wp_dequeue_style('colors');

        wp_dequeue_script('jquery');
        wp_dequeue_script('utils');
        wp_dequeue_script('wp-util');
        wp_dequeue_script('common');
        wp_dequeue_script('admin-bar');
        wp_dequeue_script('heartbeat');
        wp_dequeue_script('wp-auth-check');
        wp_dequeue_script('site-health');

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
                'nonce' => wp_create_nonce('wp_rest'),
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

add_action('rest_api_init', function () {
    register_rest_route('mt-office/v1', '/tasks', [
        'methods' => 'GET',
        'callback' => 'mt_office_get_tasks',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        }
    ]);

    register_rest_route('mt-office/v1', '/tasks', [
        'methods'             => 'POST',
        'callback'            => 'mt_office_create_task',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
        'args' => [
            'name' => [
                'required' => true,
                'type'     => 'string',
            ],
            'value' => [
                'required' => false,
                'type'     => 'string',
            ],
            'status' => [
                'required' => false,
                'type'     => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
            ],
        ],
    ]);
});

function mt_office_get_tasks()
{
    global $wpdb;
    $table = $wpdb->prefix . 'mt_office_tasks';

    $results = $wpdb->get_results("SELECT * FROM $table ORDER BY created_at DESC", ARRAY_A);

    return rest_ensure_response($results);
}

function mt_office_create_task($request)
{
    global $wpdb;

    $name  = sanitize_text_field($request->get_param('name'));
    $value = sanitize_textarea_field($request->get_param('value'));
    $status = rest_sanitize_boolean($request->get_param('status'));


    if (empty($name)) {
        return new WP_Error(
            'name',
            __('The task name is required.', 'mt-office'),
            ['status' => 400]
        );
    }

    $table = $wpdb->prefix . 'mt_office_tasks';

    $result = $wpdb->insert($table, [
        'name'       => $name,
        'value'      => $value,
        'status'     => $status,
        'created_at' => current_time('mysql'),
        'updated_at' => current_time('mysql'),
    ]);

    if ($result === false) {
        return new WP_Error('db_insert_error', 'Could not insert task.', ['status' => 500]);
    }

    return rest_ensure_response([
        'id'    => $wpdb->insert_id,
        'name'  => $name,
        'value' => $value,
        'status' => 0,
    ]);
}
