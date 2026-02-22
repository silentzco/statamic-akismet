<?php

use Silentz\Akismet\Spam\Submission;
use Statamic\Contracts\Addons\SettingsRepository;
use Statamic\Facades\Addon;
use Statamic\Facades\Form;
use Statamic\Forms\Submission as StatamicSubmission;

it('handles form submissions', function (string $form, bool $expectation) {
    $this->mock(SettingsRepository::class, fn ($mock) => $mock
        ->shouldReceive('find')
        ->with('silentz/akismet')
        ->andReturn(settings(['forms' => ['contact_us' => []]]))
        ->once()
    );

    $submission = tap(new StatamicSubmission)->form(Form::make($form));

    expect(new Submission($submission))->shouldProcess()->toBe($expectation);
})->with([
    ['not_configured', false],
    ['contact_us', true],
]);

it('can detect email only spam', function () {
    $settings = new Settings(
        Addon::get('silentz/akismet'),
        ['forms' => ['contact_us' => ['email_field' => 'email']]]
    );

    $this->mock(SettingsRepository::class, function ($mock) use ($settings) {
        $mock
            ->shouldReceive('find')
            ->with('silentz/akismet')
            ->andReturn($settings)
            ->once();
    });

    $submission = tap(new StatamicSubmission)
        ->form(Form::make('contact_us'))
        ->data(['email' => 'akismet-guaranteed-spam@example.com']);

    expect(new Submission($submission))->isSpam()->toBeTrue();
});
