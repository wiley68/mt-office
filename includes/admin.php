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
?>
    <div class="mt_office_container">
        <div class="bg-white flex-grow">
            <header class="absolute inset-x-0 top-0 z-50">
                <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
                    <div class="flex lg:flex-1">
                        <a href="https://avalonbg.com" target="_blank" class="flex justify-center items-center gap-1 -m-1.5 p-1.5">
                            <span class="sr-only"><?php echo __('Avalon Ltd.', 'mt-office'); ?></span>
                            <img class="h-12 w-auto" src="<?php echo MT_OFFICE_IMAGES_URI; ?>/logo.png" alt="<?php echo __('Avalon', 'mt-office'); ?>" />
                        </a>
                    </div>
                    <div class="flex lg:hidden">
                        <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                            <span class="sr-only"><?php echo __('Open main menu', 'mt-office'); ?></span>
                            <img class="h-8 w-auto" src="<?php echo MT_OFFICE_IMAGES_URI; ?>/logo.png" alt="<?php echo __('Avalon', 'mt-office'); ?>" />
                        </button>
                    </div>
                    <div class="hidden lg:flex lg:gap-x-12">
                        <a href="#" class="text-sm/6 font-semibold text-gray-900"><?php echo __('MT Office', 'mt-office'); ?></a>
                        <a href="#" class="text-sm/6 font-semibold text-gray-900"><?php echo __('Marketplace', 'mt-office'); ?></a>
                        <a href="https://avalonbg.com" target="_blank" class="text-sm/6 font-semibold text-gray-900"><?php echo __('Company', 'mt-office'); ?></a>
                    </div>
                    <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                        <a href="<?php echo admin_url(''); ?>" class="text-sm/6 font-semibold text-gray-900"><?php echo __('Dashboard', 'mt-office'); ?> <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </nav>
                <div class="lg:hidden" role="dialog" aria-modal="true">
                    <div class="fixed inset-0 z-50"></div>
                    <div class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                        <div class="flex items-center justify-between py-3">
                            <a href="https://avalonbg.com" target="_blank" class="-m-1.5 p-1.5">
                                <span class="sr-only"><?php echo __('Avalon Ltd.', 'mt-office'); ?></span>
                                <img class="h-12 w-auto" src="<?php echo MT_OFFICE_IMAGES_URI; ?>/logo.png" alt="<?php echo __('Avalon', 'mt-office'); ?>" />
                            </a>
                            <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700">
                                <span class="sr-only">Close menu</span>
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-6 flow-root">
                            <div class="-my-6 divide-y divide-gray-500/10">
                                <div class="space-y-2 py-6">
                                    <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50"><?php echo __('MT Office', 'mt-office'); ?></a>
                                    <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50"><?php echo __('Marketplace', 'mt-office'); ?></a>
                                    <a href="https://avalonbg.com" target="_blank" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50"><?php echo __('Company', 'mt-office'); ?></a>
                                </div>
                                <div class="py-6">
                                    <a href="<?php echo admin_url(''); ?>" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50"><?php echo __('Dashboard', 'mt-office'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="relative isolate px-6 pt-14 lg:px-8">
                <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
                    <div class="relative left-[calc(50%-11rem)] aspect-1155/678 w-144.5 -translate-x-1/2 rotate-30 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-288.75" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
                </div>
                <div class="mx-auto max-w-2xl py-16 sm:py-24 lg:py-32">
                    <div class="hidden sm:mb-8 sm:flex sm:justify-center">
                        <div class="relative rounded-full px-3 py-1 text-sm/6 text-gray-600 ring-1 ring-gray-900/10 hover:ring-gray-900/20">
                            <?php echo __('Announcing a new software version.', 'mt-office'); ?> <a href="#" class="font-semibold text-indigo-600"><span class="absolute inset-0" aria-hidden="true"></span><?php echo __('Read more', 'mt-office'); ?> <span aria-hidden="true">&rarr;</span></a>
                        </div>
                    </div>
                    <div class="text-center">
                        <h1 class="text-5xl font-semibold tracking-tight text-balance text-gray-900 sm:text-7xl"><?php echo __('Maxtrade Office', 'mt-office'); ?></h1>
                        <p class="mt-8 text-lg font-medium text-pretty text-gray-500 sm:text-xl/8"><?php echo __('Utility for managing office activities in an WordPress/WooCommerce e-store.', 'mt-office'); ?></p>
                        <div class="mt-10 flex items-center justify-center gap-x-6">
                            <a href="<?php echo admin_url('admin.php?page=mt-office'); ?>" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"><?php echo __('OFFICE', 'mt-office'); ?></a>
                            <a href="<?php echo admin_url('admin.php?page=mt-office-settings'); ?>" class="text-sm/6 font-semibold text-gray-900"><?php echo __('Settings', 'mt-office'); ?> <span aria-hidden="true">→</span></a>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
                    <div class="relative left-[calc(50%+3rem)] aspect-1155/678 w-144.5 -translate-x-1/2 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-288.75" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
                </div>
            </div>
        </div>
    </div>
<?php
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
    <a href="<?php echo admin_url('admin.php?page=mt-office-overview'); ?>" class="button mt-office-back-link">⬅ <?php echo __('Back to MT Office', 'mt-office'); ?></a>
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
