<?php

use Silentz\Akismet\Exceptions\FormException;
use Silentz\Akismet\FormSettings;
use Silentz\Akismet\Settings;

it('returns a FormSettings DTO from forForm()', function () {
    mockSettings([
        'form' => 'test_form',
        'name_field' => 'name',
        'email_field' => 'email',
    ])->once();

    $settings = Settings::forForm('test_form');

    expect($settings)->toBeInstanceOf(FormSettings::class)
        ->and($settings->name)->toBe('name')
        ->and($settings->email)->toBe('email');
});

it('throws FormException from forForm() when form is not configured', function () {
    mockSettings(['form' => 'other_form'])->once();

    Settings::forForm('not_configured');
})->throws(FormException::class);
