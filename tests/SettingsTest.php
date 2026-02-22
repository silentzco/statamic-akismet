<?php

use Silentz\Akismet\Settings;

it('can check for form settings', function (string $form, bool $expectation) {
    mockSettings(['form' => 'test_form'])->once();

    expect(Settings::isConfigured('test_form'))->toBeTrue();
})->with([
    ['not_configured', false],
    ['test_form', true],
]);

it('can get form settings', function () {
    $formConfig = [
        'form' => 'test_form',
        'name_field' => 'name',
        'email_field' => 'email',
    ];

    mockSettings($formConfig)->once();

    expect(Settings::forForm('test_form'))->toEqual($formConfig);
});
