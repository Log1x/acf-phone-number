# ACF Phone Number

![Latest Stable Version](https://img.shields.io/packagist/v/log1x/acf-phone-number?style=flat-square)
![Total Downloads](https://img.shields.io/packagist/dt/log1x/acf-phone-number?style=flat-square)
![Build Status](https://img.shields.io/github/workflow/status/log1x/acf-phone-number/Compatibility%20Checks?style=flat-square)

A real ACF phone number field powered by [libphonenumber](https://github.com/giggsey/libphonenumber-for-php) and [intl-tel-input](https://github.com/jackocnr/intl-tel-input)

![Screenshot](https://i.imgur.com/ILmsBHr.gif)

## Requirements

- [PHP](https://secure.php.net/manual/en/install.php) >= 7.2 (with [`php-intl`](https://www.php.net/manual/en/book.intl.php) installed)
- [Composer](https://getcomposer.org/download/)

## Installation

### Bedrock

Install via Composer:

```bash
$ composer require log1x/acf-phone-number
```

### Manual

Download the release `.zip` and install into `wp-content/plugins`.

## Usage

Pretty straight forward usage. You can optionally set a default country.

Calling the field will return an [arrayable](https://github.com/Log1x/acf-phone-number/blob/master/src/PhoneNumber.php#L254-L272) object containing everything you need about your number:

```php
^ array:10 [
  "carrier" => ""
  "country" => "United States"
  "e164" => "+14058675309"
  "international" => "+1 405-867-5309"
  "location" => "Oklahoma"
  "national" => "(405) 867-5309"
  "number" => "(405) 867-5309"
  "rfc3966" => "tel:+1-405-867-5309"
  "timezone" => array:1 [
    0 => "America/Chicago"
  ]
  "uri" => "tel:+14058675309"
]
```

### ACF Composer

If you are on Sage 10 and using my [ACF Composer](https://github.com/log1x/acf-composer) package:

```php
$field
  ->addField('my_number_field', 'phone_number')
    ->setConfig('default_country', 'us');
```

## Bug Reports

If you discover a bug in ACF Phone Number, please [open an issue](https://github.com/log1x/acf-phone-number/issues).

## Contributing

Contributing whether it be through PRs, reporting an issue, or suggesting an idea is encouraged and appreciated.

## License

ACF Phone Number is provided under the [MIT License](https://github.com/log1x/acf-phone-number/blob/master/LICENSE.md).
