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
