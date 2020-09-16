# Use Akismet to stop spam
[![Latest Version](https://img.shields.io/github/release/silentzco/statamic-akismet)](https://github.com/silentzco/statamic-akismet/releases)
[![Commercial License](https://img.shields.io/badge/license-Commercial-success?style=flat-square)](#)

This package provides an easy way stop submission spam via Akismet.

## Requirements

* PHP 7.4+
* Statamic v3
* Laravel 7+

## Installation

You can install this package via composer using:

```bash
composer require silentz/akismet
```

The package will automatically register itself.

## Configuration

Set your Akismet API Key in your `.env` file. You can get it from: https://akismet.com/account/.

```yaml
AKISMET_API_KEY=your-key-here
```

To publish the config file to `config/akismet.php` run:

```bash
php artisan vendor:publish --tag="akismet-config"
```

This will publish a file `akismet.php` in your config directory with the following contents:
```php

return [

    'api_key' => env('AKISMET_API_KEY'),

    // these are the form handles you'd like to check for spam
    'forms' => [

        // the handle of your form
        'contact_us' => [

            // use `name_field` if you store both first and last name
            'name_field' => 'name',   // `name` in this case is the field in your form

            // use `first_name_field` & `last_name_field` if you store them separately
            'first_name_field' => 'first_name',
            'last_name_field' => 'last_name',
            'email_field' => 'email',
            'content_field' => 'message',
        ],
        'other_form' => [],
    ],
];

```

## Usage

Create your Statamic [forms](https://statamic.dev/forms#content) as usual. When a submission is created it is checked for spam.

If it is spam, it gets put into that form's spam queue.

![Spam Queues](https://github.com/silentzco/statamic-akismet/blob/main/images/menu.png?raw=true)

From there you can discard (delete) it, or approve it. Approving it will put it back into the normal submissions and tell Akismet that it is not spam (to help with learning).

![Approval/Discarding](https://github.com/silentzco/statamic-akismet/blob/main/images/approval-discarding.png?raw=true)

If you find a submission that is spam, you can mark it as spam (image). This will put it in the spam queue and tell Akismet it is spam.

![Mark As Spam](https://github.com/silentzco/statamic-akismet/blob/main/images/mark-as-spam.png?raw=true)

## Testing

Run the tests with:
```bash
vendor/bin/phpunit
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email [addon-security@silentz.co](mailto:addon-security@silentz.co) instead of using the issue tracker.

## License

This is commercial software. You may use the package for your sites. Each site requires it's own license.
