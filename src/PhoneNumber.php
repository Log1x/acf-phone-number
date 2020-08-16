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
     * The phone number attributes.
     *
     * @var array
     */
    protected $attributes = [];

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
     * Create a new phone number instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->instance = PhoneNumberUtil::getInstance();
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
            empty($field['number']) ||
            empty($field['country'])
        ) {
            return $this;
        }

        try {
            $this->number = $this->instance->parse($field['number'], $field['country']);
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
        return PhoneNumberToTimeZonesMapper::getInstance()
            ->getTimeZonesForNumber($this->number);
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
     * Dynamically retrieve the value of an attribute.
     *
     * @param  string  $key
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
