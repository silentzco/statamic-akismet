# Use Akismet to Stop Spam
[![Latest Version](https://img.shields.io/github/release/silentzco/statamic-akismet)](https://github.com/silentzco/statamic-akismet/releases)
[![Commercial License](https://img.shields.io/badge/license-Commercial-success?style=flat-square)](#)

This package provides an easy way stop submission spam via Akismet.

## Requirements

* PHP 8.3+
* Statamic v6
* Laravel 11+

## Installation

You can install this package via composer using:

```bash
composer require silentz/akismet
```

The package will automatically register itself.

## Configuration

### .env
Set your Akismet API Key in your `.env` file. You can get it from: https://akismet.com/account/.

```yaml
AKISMET_API_KEY=your-key-here
```

### Permission
In order to manage your spam, you'll either have to be a super user or have the `manage spam` permission:

[![Permission](https://github.com/silentzco/statamic-akismet/blob/main/images/permission.png?raw=true)

### Settings

Set your API key in your `.env`: `AKISMET_API_KEY=your-key-here`

Update your settings from the settings page:
![Configuration](https://github.com/silentzco/statamic-akismet/blob/main/images/config.png?raw=true)

## Usage

Create your Statamic [forms](https://statamic.dev/forms#content) as usual. When a submission is created it is checked for spam.

If it is spam, it gets put into that form's spam queue.

![Spam Queues](https://github.com/silentzco/statamic-akismet/blob/main/images/menu.png?raw=true)

From there you can delete it, or mark it ham (not spam). Marking it as ham it will put it back into the normal submissions and tell Akismet that it is not spam (to help with learning).

![Mark as Ham](https://github.com/silentzco/statamic-akismet/blob/main/images/mark-as-ham.png?raw=true)

If you find a submission that is spam, you can mark it as spam. This will put it in the spam queue and tell Akismet it is spam.

![Mark As Spam](https://github.com/silentzco/statamic-akismet/blob/main/images/mark-as-spam.png?raw=true)

Both of those can be done one at a time, or in bulk via the bulk selection.

## Testing Akismet Locally

To confirm Akismet is working, use either `akismet-guaranteed-spam@example.com` as the email on a test submission, or `viagra-test-123` as the name. Submissions with that in it will always be flagged as spam.

## Testing

Run the tests with:
```bash
vendor/bin/pest
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email [addon-security@silentz.co](mailto:addon-security@silentz.co) instead of using the issue tracker.

## License

This is commercial software. You may use the package for your sites. Each site requires it's own license.
