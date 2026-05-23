<?php

use Silentz\Akismet\Exceptions\FormException;
use Silentz\Akismet\FormSettings;

it('maps all fields from array', function () {
    $settings = FormSettings::fromArray([
        'form' => 'contact',
        'email_field' => 'email',
        'content_field' => 'message',
        'name_field' => 'name',
        'first_name_field' => 'first_name',
        'last_name_field' => 'last_name',
    ]);

    expect($settings->email)->toBe('email')
        ->and($settings->content)->toBe('message')
        ->and($settings->name)->toBe('name')
        ->and($settings->firstName)->toBe('first_name')
        ->and($settings->lastName)->toBe('last_name');
});

it('throws when form key is absent', function () {
    FormSettings::fromArray(['email_field' => 'email']);
})->throws(FormException::class);

it('throws when form key is empty string', function () {
    FormSettings::fromArray(['form' => '']);
})->throws(FormException::class);

it('resolves name from name_field', function () {
    $settings = FormSettings::fromArray(['form' => 'f', 'name_field' => 'full_name', 'author_field' => 'author']);

    expect($settings->name)->toBe('full_name');
});

it('falls back to author_field when name_field is absent', function () {
    $settings = FormSettings::fromArray(['form' => 'f', 'author_field' => 'author']);

    expect($settings->name)->toBe('author');
});

it('name is null when neither name_field nor author_field is set', function () {
    $settings = FormSettings::fromArray(['form' => 'f']);

    expect($settings->name)->toBeNull();
});

it('hasFirstAndLastName() is true when both are set', function () {
    $settings = FormSettings::fromArray(['form' => 'f', 'first_name_field' => 'fn', 'last_name_field' => 'ln']);

    expect($settings->hasFirstAndLastName())->toBeTrue();
});

it('hasFirstAndLastName() is false when only first_name_field is set', function () {
    $settings = FormSettings::fromArray(['form' => 'f', 'first_name_field' => 'fn']);

    expect($settings->hasFirstAndLastName())->toBeFalse();
});

it('hasFirstAndLastName() is false when only last_name_field is set', function () {
    $settings = FormSettings::fromArray(['form' => 'f', 'last_name_field' => 'ln']);

    expect($settings->hasFirstAndLastName())->toBeFalse();
});

it('hasFirstAndLastName() is false when neither is set', function () {
    $settings = FormSettings::fromArray(['form' => 'f']);

    expect($settings->hasFirstAndLastName())->toBeFalse();
});
