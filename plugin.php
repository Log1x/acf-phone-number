<?php

/**
 * Plugin Name: Advanced Custom Fields: Phone Number
 * Plugin URI:  https://github.com/log1x/acf-phone-number
 * Description: A real ACF phone number field.
 * Version:     1.2.2
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
        $field = new PhoneNumberField(
            plugin_dir_url(__FILE__) . $this->assetPath,
            plugin_dir_path(__FILE__) . $this->assetPath
        );

        acf_register_field_type($field);

        add_filter("acf/rest/format_value_for_rest/type={$field->name}", function ($formatted, $post, $field, $value) {
            return (new PhoneNumber($value))->toArray();
        }, 10, 4);
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
