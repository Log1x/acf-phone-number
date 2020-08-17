<?php

/**
 * Plugin Name: Advanced Custom Fields: Phone Number
 * Plugin URI:  https://github.com/log1x/acf-phone-number
 * Description: A real ACF phone number field.
 * Version:     1.0.6
 * Author:      Brandon Nifong
 * Author URI:  https://github.com/log1x
 */

namespace Log1x\AcfPhoneNumber;

if (file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    require $composer;
}

if (! class_exists('\libphonenumber\PhoneNumberUtil')) {
    return;
}

add_filter('after_setup_theme', new class
{
    /**
     * Invoke the plugin.
     *
     * @return void
     */
    public function __invoke()
    {
        foreach (['acf/include_field_types', 'acf/register_fields'] as $hook) {
            add_filter($hook, function () {
                return new PhoneNumberField(
                    plugin_dir_url(__FILE__) . 'dist',
                    plugin_dir_path(__FILE__) . 'dist'
                );
            });
        }
    }
}, 100);
