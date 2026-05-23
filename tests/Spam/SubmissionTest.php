<?php

use nickurt\Akismet\Akismet;
use Silentz\Akismet\Spam\Submission;
use Statamic\Contracts\Addons\SettingsRepository;
use Statamic\Facades\Form;
use Statamic\Forms\Submission as StatamicSubmission;

it('handles form submissions', function (string $form, bool $expectation) {
    mockSettings(['form' => 'contact_us'])->once();

    $submission = tap(new StatamicSubmission)->form(Form::make($form));

    expect(new Submission($submission))->shouldProcess()->toBe($expectation);
})->with([
    ['not_configured', false],
    ['contact_us', true],
]);

it('sets akismet data properly', function () {
    mockSettings([
        'form' => 'test_form',
        'email_field' => 'email',
        'name_field' => 'name',
        'content_field' => 'message',
    ])->twice();

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
        ->form(Form::make('test_form'))
        ->data([
            'email' => 'akismet-guaranteed-spam@example.com',
            'name' => 'akismet-guaranteed-spam',
            'message' => 'akismet-guaranteed-spam',
        ]);

    (new Submission($submission))->isSpam();
});

it('can detect email only spam', function () {
    mockSettings([
        'form' => 'test_form',
        'email_field' => 'email',
    ])->twice();

    $submission = tap(new StatamicSubmission)
        ->form(Form::make('test_form'))
        ->data(['email' => 'akismet-guaranteed-spam@example.com']);

    expect(new Submission($submission))->isSpam()->toBeTrue();
});

it('uses first_name_field and last_name_field to build author name', function () {
    mockSettings([
        'form' => 'test_form',
        'first_name_field' => 'first_name',
        'last_name_field' => 'last_name',
        'email_field' => 'email',
        'content_field' => 'message',
    ])->twice();

    $fillData = [
        'blog' => 'http://localhost',
        'comment_type' => 'contact-form',
        'comment_author' => 'John Doe',
        'comment_author_email' => 'john@example.com',
        'comment_content' => 'Hello',
    ];

    $this->mock(Akismet::class)
        ->shouldReceive('fill')->with($fillData)->andReturnSelf()
        ->shouldReceive('isSpam')->andReturn(false);

    $submission = tap(new StatamicSubmission)
        ->form(Form::make('test_form'))
        ->data([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'message' => 'Hello',
        ]);

    (new Submission($submission))->isSpam();
});
