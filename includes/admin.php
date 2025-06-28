<?php

/**
 * Register plugin settings page in the WordPress admin menu.
 *
 * Adds an options page menu where plugin configuration can be managed.
 * Access is restricted to users with 'manage_options' capability.
 *
 * @since 1.0.0
 *
 * @return void
 */
function mt_office_admin_actions()
{
    if (! current_user_can('manage_options')) {
        return;
    }

    // Main menu (points to the settings page)
    add_menu_page(
        __('MT Office', 'mt-office'),
        __('MT Office', 'mt-office'),
        'manage_options',
        'mt-office-overview',
        'mt_office_overview',
        'dashicons-admin-generic',
        60
    );

    // Submenu: Overview
    add_submenu_page(
        'mt-office-overview',
        __('Overview', 'mt-office'),
        __('Overview', 'mt-office'),
        'manage_options',
        'mt-office-overview',
        'mt_office_overview'
    );

    // Submenu: Tasks Application (SPA)
    add_submenu_page(
        'mt-office-overview',
        __('Office', 'mt-office'),
        __('Office', 'mt-office'),
        'manage_options',
        'mt-office',
        'mt_office'
    );

    // Submenu: Settings (duplicates the main one)
    add_submenu_page(
        'mt-office-overview',
        __('Settings', 'mt-office'),
        __('Settings', 'mt-office'),
        'manage_options',
        'mt-office-settings',
        'mt_office_admin_options'
    );
}

/**
 * Display plugin overview page content.
 *
 * Checks user capabilities before displaying the interface.
 *
 * @since 1.0.0
 *
 * @return void
 */
function mt_office_overview()
{
    echo "Overview";
}

/**
 * Display plugin tasks page content.
 *
 * Checks user capabilities before displaying the interface.
 *
 * @since 1.0.0
 *
 * @return void
 */
function mt_office()
{
?>
    <style>
        #wpcontent {
            margin-left: 0 !important;
            padding: 0;
        }

        #wpadminbar,
        #adminmenumain,
        #adminmenuwrap,
        #wpfooter,
        .wrap>h1,
        .notice {
            display: none !important;
        }

        html,
        body,
        #vue-admin-app {
            height: 100%;
            margin: 0;
        }

        .mt-office-back-link {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 9999;
        }
    </style>
    <a href="<?php echo admin_url('admin.php?page=mt-office-settings'); ?>" class="button mt-office-back-link">⬅ <?php echo __('Обратно към MT Office', 'mt-office'); ?></a>
    <div id=q-app></div>
<?php
}

/**
 * Display plugin admin options page content.
 *
 * Checks user capabilities before displaying the admin interface.
 * Includes the admin options form from 'mt_office_import_admin.php'.
 * Displays an error message if the file is not found.
 *
 * @since 1.0.0
 *
 * @return void
 */
function mt_office_admin_options()
{
    if (! current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have sufficient rights to access this page.', 'mt-office'));
    }

    $file_path = plugin_dir_path(__FILE__) . 'mt_office_import_admin.php';

    if (file_exists($file_path)) {
        require_once $file_path;
    } else {
        echo '<div class="error"><p>' . esc_html__('The file mt_office_import_admin.php was not found!', 'mt-office') . '</p></div>';
    }
}
