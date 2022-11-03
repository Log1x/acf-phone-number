<?php

namespace Log1x\AcfPhoneNumber;

use Locale;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberToCarrierMapper;
use libphonenumber\PhoneNumberToTimeZonesMapper;
use libphonenumber\geocoding\PhoneNumberOfflineGeocoder;

class PhoneNumber
{
    /**
     * The libphonenumber instance.
     *
     * @var \libphonenumber\PhoneNumberUtil;
     */
    protected $instance;

    /**
     * The parsed phone number object.
     *
     * @var object
     */
    protected $number;

    /**
     * The phone number country.
     *
     * @return string
     */
    protected $country;

    /**
     * Create a new phone number instance.
     *
     * @param  string $number
     * @return void
     */
    public function __construct($number = null)
    {
        $this->instance = PhoneNumberUtil::getInstance();

        if (! empty($number)) {
            $this->parse($number);
        }
    }

    /**
     * Validate and parse the provided phone number.
     *
     * @param  mixed $value
     * @return $this
     */
    public function parse($value = null)
    {
        if (
            ! is_array($value) ||
            empty($value['number']) ||
            empty($value['country'])
        ) {
            return $this;
        }

        try {
            $this->number = $this->instance->parse(
                $value['number'],
                $this->country = strtoupper($value['country'])
            );
        } catch (NumberParseException $e) {
            //
        }

        return $this;
    }

    /**
     * Retrieve the phone number formatted for URI use.
     *
     * @return string
     */
    public function uri()
    {
        if (! $this->isValid()) {
            return '';
        }

        return 'tel:' . $this->instance->format(
            $this->number,
            PhoneNumberFormat::E164
        );
    }

    /**
     * Retrieve the phone number formatted as E164.
     *
     * @return string
     */
    public function e164()
    {
        if (! $this->isValid()) {
            return '';
        }

        return $this->instance->format(
            $this->number,
            PhoneNumberFormat::E164
        );
    }

    /**
     * Retrieve the phone number formatted as RFC3966.
     *
     * @return string
     */
    public function rfc3966()
    {
        if (! $this->isValid()) {
            return '';
        }

        return $this->instance->format(
            $this->number,
            PhoneNumberFormat::RFC3966
        );
    }

    /**
     * Retrieve the phone number formatted for national use.
     *
     * @return string
     */
    public function national()
    {
        if (! $this->isValid()) {
            return '';
        }

        return $this->instance->format(
            $this->number,
            PhoneNumberFormat::NATIONAL
        );
    }

    /**
     * Retrieve the phone number formatted for international use.
     *
     * @return string
     */
    public function international()
    {
        if (! $this->isValid()) {
            return '';
        }

        return $this->instance->format(
            $this->number,
            PhoneNumberFormat::INTERNATIONAL
        );
    }

    /**
     * Retrieve the carrier for the current phone number.
     *
     * @return string
     */
    public function carrier()
    {
        if (! $this->isValid()) {
            return '';
        }

        return PhoneNumberToCarrierMapper::getInstance()
            ->getNameForNumber($this->number, 'en');
    }

    /**
     * Retrieve the location for the current phone number.
     *
     * @return string
     */
    public function location()
    {
        if (! $this->isValid()) {
            return '';
        }

        return PhoneNumberOfflineGeocoder::getInstance()
            ->getDescriptionForNumber($this->number, 'en_US');
    }

    /**
     * Retrieve the timezone for the current phone number.
     *
     * @return array
     */
    public function timezone()
    {
        if (! $this->isValid()) {
            return [];
        }

        return PhoneNumberToTimeZonesMapper::getInstance()
            ->getTimeZonesForNumber($this->number);
    }

    /**
     * Retrieve the country for the current phone number.
     *
     * @return string
     */
    public function country()
    {
        if (! $this->isValid()) {
            return '';
        }

        return Locale::getDisplayRegion('-' . $this->country, 'en');
    }

    /**
     * Determine whether the current phone number is valid.
     *
     * @return bool
     */
    public function isValid()
    {
        return ! empty($this->number) && $this->instance->isValidNumber($this->number);
    }

    /**
     * Return an array containing country locale.
     *
     * @return array
     */
    public function getCountries()
    {
        $countries = [];

        foreach ($this->instance->getSupportedRegions() as $value) {
            $countries[strtolower($value)] = Locale::getDisplayRegion('-' . $value, 'en');
        }

        return $countries;
    }

    /**
     * Convert the phone number properties to an array.
     *
     * @return array
     */
    public function toArray()
    {
        if (! $this->isValid()) {
            return [];
        }

        return [
            'carrier' => $this->carrier(),
            'country' => $this->country(),
            'e164' => $this->e164(),
            'international' => $this->international(),
            'location' => $this->location(),
            'national' => $this->national(),
            'number' => $this->national(),
            'rfc3966' => $this->rfc3966(),
            'timezone' => $this->timezone(),
            'uri' => $this->uri(),
        ];
    }

    /**
     * Convert the phone number properties to JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Dynamically retrieve the value of an attribute.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->{$key}();
    }

    /**
     * Retrieve the phone number as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->e164();
    }
}
