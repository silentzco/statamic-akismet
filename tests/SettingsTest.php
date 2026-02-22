<?php

use Silentz\Akismet\Settings;
use Statamic\Contracts\Addons\SettingsRepository;

it('can check for form settings', function (string $form, bool $expectation) {
    $formConfig = [
        'form' => 'test_form',
        'name_field' => 'name',
        'email_field' => 'email',
    ];

    $this->mock(SettingsRepository::class)
        ->shouldReceive('find')
        ->with('silentz/akismet')
        ->andReturn(settings(['forms' => [$formConfig]]))
        ->once();

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

    $this->mock(SettingsRepository::class)
        ->shouldReceive('find')
        ->with('silentz/akismet')
        ->andReturn(settings(['forms' => [$formConfig]]))
        ->once();

    expect(Settings::forForm('test_form'))->toEqual($formConfig);
});
