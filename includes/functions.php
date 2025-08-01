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
        },
        'args' => [
            'page' => [
                'type' => 'integer',
                'default' => 1,
            ],
            'per_page' => [
                'type' => 'integer',
                'default' => 10,
            ],
        ],
    ]);

    register_rest_route('mt-office/v1', '/tasks', [
        'methods' => 'POST',
        'callback' => 'mt_office_create_task',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
        'args' => [
            'name' => [
                'required' => true,
                'type' => 'string',
            ],
            'value' => [
                'required' => false,
                'type' => 'string',
            ],
            'status' => [
                'required' => false,
                'type' => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
            ],
        ],
    ]);

    register_rest_route('mt-office/v1', '/tasks/(?P<id>\d+)', [
        'methods' => 'GET',
        'callback' => 'mt_office_get_task',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
        'args' => [
            'id' => [
                'required' => true,
                'type' => 'integer',
            ],
        ],
    ]);

    register_rest_route('mt-office/v1', '/tasks/(?P<id>\d+)', [
        'methods' => 'PUT',
        'callback' => 'mt_office_update_task',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
        'args' => [
            'name' => [
                'required' => true,
                'type' => 'string',
            ],
            'value' => [
                'required' => false,
                'type' => 'string',
            ],
            'status' => [
                'required' => false,
                'type' => 'boolean',
            ],
        ],
    ]);

    register_rest_route('mt-office/v1', '/tasks/(?P<id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'mt_office_delete_task',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
        'args' => [
            'id' => [
                'required' => true,
                'type' => 'integer',
            ],
        ],
    ]);
});

function mt_office_get_tasks(WP_REST_Request $request)
{
    global $wpdb;

    $page = max(1, (int) $request->get_param('page'));
    $per_page = max(1, (int) $request->get_param('per_page'));
    $offset = ($page - 1) * $per_page;
    $search = $request->get_param('search');
    $sort_by = $request->get_param('sort_by');
    $sort_desc = $request->get_param('sort_desc') === '1';

    $allowed_sort_fields = ['id', 'name', 'status'];
    $order_by = in_array($sort_by, $allowed_sort_fields) ? $sort_by : 'id';
    $order_dir = $sort_desc ? 'DESC' : 'ASC';

    $where = '1=1';
    $params = [];

    if (!empty($search)) {
        $where .= " AND (name LIKE %s OR value LIKE %s)";
        $search_term = '%' . $wpdb->esc_like($search) . '%';
        $params[] = $search_term;
        $params[] = $search_term;
    }

    $args = array_merge($params, [$per_page, $offset]);
    $query = $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}mt_office_tasks
         WHERE $where
         ORDER BY $order_by $order_dir
         LIMIT %d OFFSET %d",
        ...$args
    );
    $results = $wpdb->get_results($query, ARRAY_A);

    $count_query = $wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->prefix}mt_office_tasks WHERE $where",
        ...$params
    );
    $total = (int) $wpdb->get_var($count_query);

    return rest_ensure_response([
        'data' => $results,
        'total' => $total,
    ]);
}

function mt_office_create_task($request)
{
    global $wpdb;

    $name = sanitize_text_field($request->get_param('name'));
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
        'name' => $name,
        'value' => $value,
        'status' => $status,
        'created_at' => current_time('mysql'),
        'updated_at' => current_time('mysql'),
    ]);

    if ($result === false) {
        return new WP_Error('db_insert_error', 'Could not insert task.', ['status' => 500]);
    }

    return rest_ensure_response([
        'id' => $wpdb->insert_id,
        'name' => $name,
        'value' => $value,
        'status' => 0,
    ]);
}

function mt_office_get_task($request)
{
    global $wpdb;
    $id = intval($request['id']);

    $table = $wpdb->prefix . 'mt_office_tasks';
    $task = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id), ARRAY_A);

    if (!$task) {
        return new WP_Error('task_not_found', __('Task not found.', 'mt-office'), ['status' => 404]);
    }

    return rest_ensure_response($task);
}

function mt_office_update_task(\WP_REST_Request $request)
{
    global $wpdb;
    $table = $wpdb->prefix . 'mt_office_tasks';

    $id = (int) $request['id'];
    $name = sanitize_text_field($request['name']);
    $value = sanitize_textarea_field($request['value']);
    $status = isset($request['status']) ? (int) (bool) $request['status'] : 0;

    $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE id = %d", $id));
    if (!$exists) {
        return new WP_Error('task_not_found', __('Task not found', 'mt-office'), ['status' => 404]);
    }

    $updated = $wpdb->update(
        $table,
        [
            'name' => $name,
            'value' => $value,
            'status' => $status,
            'updated_at' => current_time('mysql'),
        ],
        ['id' => $id],
        ['%s', '%s', '%d', '%s'],
        ['%d']
    );

    if ($updated === false) {
        return new WP_Error('db_error', __('Database error during update', 'mt-office'), ['status' => 500]);
    }

    return rest_ensure_response([
        'success' => true,
        'message' => __('Task updated successfully', 'mt-office'),
    ]);
}

function mt_office_delete_task($request)
{
    global $wpdb;

    $id = intval($request['id']);
    $table = $wpdb->prefix . 'mt_office_tasks';

    $deleted = $wpdb->delete($table, ['id' => $id], ['%d']);

    if ($deleted === false) {
        return new WP_Error('db_error', __('An error occurred while deleting from the database.', 'mt-office'), ['status' => 500]);
    }

    if ($deleted === 0) {
        return new WP_Error('not_found', __('Task not found.', 'mt-office'), ['status' => 404]);
    }

    return rest_ensure_response(['success' => true, 'message' => __('The task has been deleted.', 'mt-office')]);
}
