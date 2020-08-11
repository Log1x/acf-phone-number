<?php

namespace Log1x\AcfPhoneNumber;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberToCarrierMapper;
use libphonenumber\PhoneNumberToTimeZonesMapper;
use libphonenumber\geocoding\PhoneNumberOfflineGeocoder;

class Phone
{
    /**
     * Create a new phone number instance.
     *
     * @param  array $field
     * @return void
     */
    public function __construct($field = [])
    {
        $this->phone = PhoneNumberUtil::getInstance();

        try {
            $this->number = $this->phone->parse($field['number'], $field['country']);
        } catch (NumberParseException $e) {
            return;
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
        return 'tel:' . $this->phone->format(
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
        return $this->phone->format(
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
        return $this->phone->format(
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
        return $this->phone->format(
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
        return $this->phone->format(
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
     * Detmerine whether the current phone number is valid.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->phone->isValidNumber($this->number);
    }

    /**
     * Determine whether a phone number is set.
     *
     * @return bool
     */
    public function exists()
    {
        return ! empty($this->number);
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
        return $this->rfc3966();
    }
}
