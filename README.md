# ACF Phone Number

![Latest Stable Version](https://img.shields.io/packagist/v/log1x/acf-phone-number?style=flat-square)
![Total Downloads](https://img.shields.io/packagist/dt/log1x/acf-phone-number?style=flat-square)

A real ACF phone number field powered by [libphonenumber](https://github.com/giggsey/libphonenumber-for-php) and [intl-tel-input](https://github.com/jackocnr/intl-tel-input)

![Screenshot](https://i.imgur.com/ILmsBHr.gif)

## Requirements

- [PHP](https://secure.php.net/manual/en/install.php) >= 7.2
- [PHP intl extension](https://www.php.net/manual/en/book.intl.php)
- [Composer](https://getcomposer.org/download/)

## Installation

### Bedrock

Install via Composer:

```bash
$ composer require log1x/acf-phone-number
```

### Trellis

This package requires the use of PHP's intl extension. Add the following to `group_vars/all/main.yml` and reprovision your environments:

```yaml
php_extensions_custom:
  php7.4-intl: "{{ apt_package_state }}"
```

## Usage

Pretty straight forward usage. You can optionally set a default country.

The field will return a handy object containing everything you need about your number:

```php
{
  +"number": "+1 405-867-5309"
  +"country": "us"
  +"uri": "tel:+14058675309"
  +"e164": "+14058675309"
  +"rfc3966": "tel:+1-405-867-5309"
  +"national": "(405) 867-5309"
  +"international": "+1 405-867-5309"
  +"carrier": ""
  +"location": "Oklahoma"
  +"timezone": array:1 [▼
    0 => "America/Chicago"
  ]
}
```

### ACF Composer

```php
$field->addField('my_number_field', 'phone_number', ['default_country' => 'us']);
```

## Bug Reports

If you discover a bug in ACF Phone Number, please [open an issue](https://github.com/log1x/acf-phone-number/issues).

## Contributing

Contributing whether it be through PRs, reporting an issue, or suggesting an idea is encouraged and appreciated.

## License

ACF Phone Number is provided under the [MIT License](https://github.com/log1x/acf-phone-number/blob/master/LICENSE.md).
