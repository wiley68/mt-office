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
                        <a href="#" class="flex justify-center items-center gap-1 -m-1.5 p-1.5">
                            <span class="sr-only">Avalon Ltd.</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-8 text-indigo-600" fill="currentColor">
                                <path d="M12,15C12.81,15 13.5,14.7 14.11,14.11C14.7,13.5 15,12.81 15,12C15,11.19 14.7,10.5 14.11,9.89C13.5,9.3 12.81,9 12,9C11.19,9 10.5,9.3 9.89,9.89C9.3,10.5 9,11.19 9,12C9,12.81 9.3,13.5 9.89,14.11C10.5,14.7 11.19,15 12,15M12,2C14.75,2 17.1,3 19.05,4.95C21,6.9 22,9.25 22,12V13.45C22,14.45 21.65,15.3 21,16C20.3,16.67 19.5,17 18.5,17C17.3,17 16.31,16.5 15.56,15.5C14.56,16.5 13.38,17 12,17C10.63,17 9.45,16.5 8.46,15.54C7.5,14.55 7,13.38 7,12C7,10.63 7.5,9.45 8.46,8.46C9.45,7.5 10.63,7 12,7C13.38,7 14.55,7.5 15.54,8.46C16.5,9.45 17,10.63 17,12V13.45C17,13.86 17.16,14.22 17.46,14.53C17.76,14.84 18.11,15 18.5,15C18.92,15 19.27,14.84 19.57,14.53C19.87,14.22 20,13.86 20,13.45V12C20,9.81 19.23,7.93 17.65,6.35C16.07,4.77 14.19,4 12,4C9.81,4 7.93,4.77 6.35,6.35C4.77,7.93 4,9.81 4,12C4,14.19 4.77,16.07 6.35,17.65C7.93,19.23 9.81,20 12,20H17V22H12C9.25,22 6.9,21 4.95,19.05C3,17.1 2,14.75 2,12C2,9.25 3,6.9 4.95,4.95C6.9,3 9.25,2 12,2Z" />
                            </svg><span class="font-medium text-3xl">Avalon</span>
                        </a>
                    </div>
                    <div class="flex lg:hidden">
                        <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                            <span class="sr-only">Open main menu</span>
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                    </div>
                    <div class="hidden lg:flex lg:gap-x-12">
                        <a href="#" class="text-sm/6 font-semibold text-gray-900">Product</a>
                        <a href="#" class="text-sm/6 font-semibold text-gray-900">Features</a>
                        <a href="#" class="text-sm/6 font-semibold text-gray-900">Marketplace</a>
                        <a href="#" class="text-sm/6 font-semibold text-gray-900">Company</a>
                    </div>
                    <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                        <a href="#" class="text-sm/6 font-semibold text-gray-900">Log in <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </nav>
                <div class="lg:hidden" role="dialog" aria-modal="true">
                    <div class="fixed inset-0 z-50"></div>
                    <div class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                        <div class="flex items-center justify-between">
                            <a href="#" class="-m-1.5 p-1.5">
                                <span class="sr-only">Your Company</span>
                                <img class="h-8 w-auto" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="" />
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
                                    <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Product</a>
                                    <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Features</a>
                                    <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Marketplace</a>
                                    <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Company</a>
                                </div>
                                <div class="py-6">
                                    <a href="#" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Log in</a>
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
                            Announcing our next round of funding. <a href="#" class="font-semibold text-indigo-600"><span class="absolute inset-0" aria-hidden="true"></span>Read more <span aria-hidden="true">&rarr;</span></a>
                        </div>
                    </div>
                    <div class="text-center">
                        <h1 class="text-5xl font-semibold tracking-tight text-balance text-gray-900 sm:text-7xl">Data to enrich your online business</h1>
                        <p class="mt-8 text-lg font-medium text-pretty text-gray-500 sm:text-xl/8">Anim aute id magna aliqua ad ad non deserunt sunt. Qui irure qui lorem cupidatat commodo. Elit sunt amet fugiat veniam occaecat.</p>
                        <div class="mt-10 flex items-center justify-center gap-x-6">
                            <a href="#" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Get started</a>
                            <a href="#" class="text-sm/6 font-semibold text-gray-900">Learn more <span aria-hidden="true">→</span></a>
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
