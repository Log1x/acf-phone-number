<?php

/**
 * Plugin Name: Advanced Custom Fields: Phone Number
 * Plugin URI:  https://github.com/log1x/acf-phone-number
 * Description: A real ACF phone number field.
 * Version:     1.1.2
 * Author:      Brandon Nifong
 * Author URI:  https://github.com/log1x
 */

namespace Log1x\AcfPhoneNumber;

add_filter('after_setup_theme', new class
{
    /**
     * The asset public path.
     *
     * @var string
     */
    protected $assetPath = 'public';

    /**
     * Invoke the plugin.
     *
     * @return void
     */
    public function __invoke()
    {
        if (file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
            require_once $composer;
        }

        $this->register();

        if (defined('ACP_FILE')) {
            $this->hookAdminColumns();
        }
    }

    /**
     * Register the Phone Number field type with ACF.
     *
     * @return void
     */
    protected function register()
    {
        foreach (['acf/include_field_types', 'acf/register_fields'] as $hook) {
            add_filter($hook, function () {
                return new PhoneNumberField(
                    plugin_dir_url(__FILE__) . $this->assetPath,
                    plugin_dir_path(__FILE__) . $this->assetPath
                );
            });
        }
    }

    /**
     * Hook the Admin Columns Pro plugin to provide basic field support
     * if detected on the current WordPress installation.
     *
     * @return void
     */
    protected function hookAdminColumns()
    {
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
});
