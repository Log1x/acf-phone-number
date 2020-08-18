<?php

/**
 * Plugin Name: Advanced Custom Fields: Phone Number
 * Plugin URI:  https://github.com/log1x/acf-phone-number
 * Description: A real ACF phone number field.
 * Version:     1.0.7
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

        /**
         * Add basic Admin Columns Pro support to the phone number field.
         *
         * @param  mixed $value
         * @param  int $id
         * @param  \ACA\ACF\Column $column
         * @return mixed
         */
        add_filter('ac/column/value', function ($value, $id, $column) {
            if (
                ! is_a($column, '\ACA\ACF\Column') ||
                $column->get_acf_field_option('type') !== 'phone_number'
            ) {
                return $value;
            }

            return get_field($column->get_meta_key())->national ?? $value;
        }, 10, 3);
    }
}, 100);
