<?php

use nickurt\Akismet\Akismet;
use Silentz\Akismet\Spam\Submission;
use Statamic\Contracts\Addons\SettingsRepository;
use Statamic\Facades\Form;
use Statamic\Forms\Submission as StatamicSubmission;

it('handles form submissions', function (string $form, bool $expectation) {
    $this->mock(SettingsRepository::class)
        ->shouldReceive('find')
        ->with('silentz/akismet')
        ->andReturn(settings(['forms' => ['contact_us' => []]]))
        ->once();

    $submission = tap(new StatamicSubmission)->form(Form::make($form));

    expect(new Submission($submission))->shouldProcess()->toBe($expectation);
})->with([
    ['not_configured', false],
    ['contact_us', true],
]);

it('sets akismet data properly', function () {
    $this->mock(SettingsRepository::class)
        ->shouldReceive('find')
        ->with('silentz/akismet')
        ->andReturn(settings(['forms' => ['contact_us' => [
            'email_field' => 'email',
            'name_field' => 'name',
            'content_field' => 'message',
        ]]]))->twice();

    $fillData = [
        'blog' => 'http://localhost',
        'comment_type' => 'contact-form',
        'comment_author' => 'akismet-guaranteed-spam',
        'comment_author_email' => 'akismet-guaranteed-spam@example.com',
        'comment_content' => 'akismet-guaranteed-spam',
    ];

    $this->mock(Akismet::class)
        ->shouldReceive('fill')->with($fillData)->andReturnSelf()
        ->shouldReceive('isSpam')->andReturn(false);

    $submission = tap(new StatamicSubmission)
        ->form(Form::make('contact_us'))
        ->data([
            'email' => 'akismet-guaranteed-spam@example.com',
            'name' => 'akismet-guaranteed-spam',
            'message' => 'akismet-guaranteed-spam',
        ]);

    (new Submission($submission))->isSpam();
});

it('can detect email only spam', function () {
    $this->mock(SettingsRepository::class, fn ($mock) => $mock
        ->shouldReceive('find')
        ->with('silentz/akismet')
        ->andReturn(settings(['forms' => ['contact_us' => ['email_field' => 'email']]]))
        ->once()
    );

    $submission = tap(new StatamicSubmission)
        ->form(Form::make('contact_us'))
        ->data(['email' => 'akismet-guaranteed-spam@example.com']);

    expect(new Submission($submission))->isSpam()->toBeTrue();
});
