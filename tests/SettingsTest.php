<?php

use Silentz\Akismet\FormSettings;
use Silentz\Akismet\Settings;

it('returns true from isConfigured() when form exists', function () {
    mockSettings(['form' => 'test_form'])->once();

    expect(Settings::isConfigured('test_form'))->toBeTrue();
});

it('returns false from isConfigured() when form does not exist', function () {
    mockSettings(['form' => 'other_form'])->once();

    expect(Settings::isConfigured('not_configured'))->toBeFalse();
});

it('returns a FormSettings DTO from forForm()', function () {
    $formConfig = [
        'form' => 'test_form',
        'name_field' => 'name',
        'email_field' => 'email',
    ];

    mockSettings($formConfig)->once();

    $settings = Settings::forForm('test_form');

    expect($settings)->toBeInstanceOf(FormSettings::class)
        ->and($settings->name())->toBe('name')
        ->and($settings->email())->toBe('email');
});

it('returns null from forForm() when form is not configured', function () {
    mockSettings(['form' => 'other_form'])->once();

    expect(Settings::forForm('not_configured'))->toBeNull();
});
