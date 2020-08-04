<?php

/**
 * Plugin Name: Advanced Custom Fields: Phone Number
 * Plugin URI:  https://github.com/log1x/acf-phone-number
 * Description: A real ACF phone number field.
 * Version:     1.0.1
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

add_filter('after_setup_theme', new class {
    /**
     * Invoke the plugin.
     *
     * @return void
     */
    public function __invoke()
    {
        foreach (['acf/include_field_types', 'acf/register_fields'] as $hook) {
            add_filter($hook, function () {
                foreach (glob(__DIR__ . '/src/*.php') as $field) {
                    $class = __NAMESPACE__ . '\\' . basename($field, '.php');

                    spl_autoload_register(function () use ($field) {
                        include_once $field;
                    });

                    return new $class([
                        'uri' => plugin_dir_url(__FILE__),
                        'path' => plugin_dir_path(__FILE__)
                    ]);
                }
            });
        }
    }
}, 100);
