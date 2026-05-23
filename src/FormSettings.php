<?php

namespace Silentz\Akismet;

use Silentz\Akismet\Exceptions\FormException;
use Statamic\Support\Arr;

class FormSettings
{
    public function __construct(
        public readonly string $form,
        public readonly ?string $email = null,
        public readonly ?string $content = null,
        public readonly ?string $name = null,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
    ) {}

    public static function fromArray(array $config): self
    {
        $form = Arr::get($config, 'form');

        if (empty($form)) {
            throw FormException::missingForm();
        }

        return new self(
            form:      $form,
            email:     Arr::get($config, 'email_field'),
            content:   Arr::get($config, 'content_field'),
            name:      Arr::get($config, 'name_field') ?? Arr::get($config, 'author_field'),
            firstName: Arr::get($config, 'first_name_field'),
            lastName:  Arr::get($config, 'last_name_field'),
        );
    }

    public function hasFirstAndLastName(): bool
    {
        return $this->firstName !== null && $this->lastName !== null;
    }
}
