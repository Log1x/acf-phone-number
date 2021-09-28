<?php

namespace Log1x\AcfPhoneNumber;

class PhoneNumberField extends \acf_field
{
    /**
     * The field name.
     *
     * @var string
     */
    public $name = 'phone_number';

    /**
     * The field label.
     *
     * @var string
     */
    public $label = 'Phone Number';

    /**
     * The field category.
     *
     * @var string
     */
    public $category = 'basic';

    /**
     * The field defaults.
     *
     * @var array
     */
    public $defaults = [
        'country' => 'us',
        'uri_scheme' => 'tel',
    ];

    /**
     * Create a new phone number field instance.
     *
     * @param  string $uri
     * @param  string $path
     * @return void
     */
    public function __construct($uri, $path)
    {
        $this->uri = $uri;
        $this->path = $path;

        parent::__construct();
    }

    /**
     * Create the HTML interface for your field.
     *
     * @param  array $field
     * @return void
     */
    public function render_field($field)
    {
        if (! is_array($field['value']) || empty($field['value']['number'])) {
            $field['value'] = ['number' => $value ?? null];
        }

        if (empty($field['value']['country'])) {
            $field['value']['country'] = $field['default_country'] ?? $this->defaults['country'];
        }

        if (empty($field['value']['uri_scheme'])) {
            $field['value']['uri_scheme'] = $field['default_uri_scheme'] ?? $this->defaults['uri_scheme'];
        }

        echo sprintf(
            '<input type="tel" name="%s[number]" value="%s" />',
            $field['name'],
            $field['value']['number']
        );

        echo sprintf(
            '<input data-default-country="%s" type="hidden" name="%s[country]" value="%s" />',
            $field['default_country'] ?? $this->defaults['country'],
            $field['name'],
            $field['value']['country'] ?? $field['default_country'] ?? $this->defaults['country']
        );

        echo sprintf(
            '<input data-default-uri-scheme="%s" type="hidden" name="%s[uri_scheme]" value="%s" />',
            $field['default_uri_scheme'] ?? $this->defaults['uri_scheme'],
            $field['name'],
            $field['value']['uri_scheme'] ?? $field['default_uri_scheme'] ?? $this->defaults['uri_scheme']
        );
    }

    /**
     * Create extra settings for your field. These are visible when editing a field.
     *
     * @param  array $field
     * @return void
     */
    public function render_field_settings($field)
    {
        acf_render_field_setting($field, [
            'label' => __('Default Country', 'acf-phone-number'),
            'instructions' => __('The default country value for the phone number.', 'acf-phone-number'),
            'type' => 'select',
            'ui' => 1,
            'name' => 'default_country',
            'default_value' => $this->defaults['country'],
            'choices' => (new PhoneNumber())->getCountries()
        ]);

        acf_render_field_setting($field, [
            'label' => __('Default URI Scheme', 'acf-phone-number'),
            'instructions' => __('The default URI scheme for the phone number.', 'acf-phone-number'),
            'type' => 'select',
            'ui' => 1,
            'name' => 'default_uri_scheme',
            'default_value' => $this->defaults['uri_scheme'],
            'choices' => [
                'tel' => 'Telephone',
                'fax' => 'Fax',
            ]
        ]);
    }

    /**
     * This action is called in the admin_enqueue_scripts action on the edit screen where
     * your field is created.
     *
     * @return void
     */
    public function input_admin_enqueue_scripts()
    {
        wp_enqueue_script('acf-' . $this->name, $this->asset('/js/field.js'), ['acf-input'], null);
        wp_enqueue_style('acf-' . $this->name, $this->asset('/css/field.css'), [], null);
    }

    /**
     * This filter is applied to the $value after it is loaded from the database and
     * before it is returned to the template.
     *
     * @param  mixed $value
     * @param  mixed $post_id
     * @param  array $field
     * @return mixed
     */
    public function format_value($value, $post_id, $field)
    {
        return new PhoneNumber($value);
    }

    /**
     * This filter is applied to the $value before it is saved in the database.
     *
     * @param  mixed $value
     * @param  mixed $post_id
     * @param  array $field
     * @return mixed
     */
    public function update_value($value, $post_id, $field)
    {
        if (! is_array($value) || empty($value['number'])) {
            return;
        }

        return $value;
    }

    /**
     * This filter is used to perform validation on the value prior to saving.
     *
     * @param  boolean $valid
     * @param  mixed   $value
     * @param  array   $field
     * @param  array   $input
     * @return boolean
     */
    public function validate_value($valid, $value, $field, $input)
    {
        if (! is_array($value) || empty($value['number'])) {
            return $valid;
        }

        if (empty($value['country'])) {
            return __('The phone number country specified is not valid.', 'acf-phone-number');
        }

        return (new PhoneNumber($value))->isValid() ?
            $valid : __('The phone number specified is not valid.', 'acf-phone-number');
    }

    /**
     * Resolve the URI for an asset using the manifest.
     *
     * @return string
     */
    public function asset($asset = '', $manifest = 'mix-manifest.json')
    {
        if (! file_exists($manifest = $this->path . $manifest)) {
            return $this->uri . $asset;
        }

        $manifest = json_decode(file_get_contents($manifest), true);

        return $this->uri . ($manifest[$asset] ?? $asset);
    }
}
