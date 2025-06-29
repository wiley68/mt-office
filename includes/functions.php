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

function mt_office_add_meta_admin($hook)
{
    if ($hook === 'toplevel_page_mt-office-overview') {
        wp_enqueue_style(
            'mt-office-css',
            MT_OFFICE_CSS_URI . '/mt-office.css',
            array(),
            filemtime(MT_OFFICE_PLUGIN_DIR . '/css/mt-office.css'),
            'all'
        );
    }

    if ($hook === 'mt-office_page_mt-office') {
        wp_enqueue_script(
            'mt-office-script',
            plugins_url('../quasar-office/dist/spa/assets/index.js', __FILE__),
            array(),
            filemtime(get_theme_file_path('../quasar-office/dist/spa/assets/index.js')),
            true
        );

        wp_localize_script(
            'mt-office',
            'mt_office_rest',
            array(
                'root'  => esc_url_raw(rest_url()),
                'nonce' => wp_create_nonce('mt_office_rest')
            )
        );
    }

    if ($hook === 'mt-office_page_mt-office-settings') {
    }
}
