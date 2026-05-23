<?php

namespace Silentz\Akismet;

use Silentz\Akismet\Exceptions\FormException;
use Statamic\Support\Arr;

class FormSettings
{
    public function __construct(
        private readonly string $form,
        private readonly ?string $email = null,
        private readonly ?string $content = null,
        private readonly ?string $name = null,
        private readonly ?string $author = null,
        private readonly ?string $firstName = null,
        private readonly ?string $lastName = null,
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
            name:      Arr::get($config, 'name_field'),
            author:    Arr::get($config, 'author_field'),
            firstName: Arr::get($config, 'first_name_field'),
            lastName:  Arr::get($config, 'last_name_field'),
        );
    }

    public function email(): ?string
    {
        return $this->email;
    }

    public function content(): ?string
    {
        return $this->content;
    }

    public function name(): ?string
    {
        return $this->name ?? $this->author;
    }

    public function hasFirstAndLastName(): bool
    {
        return $this->firstName !== null && $this->lastName !== null;
    }

    public function firstName(): ?string
    {
        return $this->firstName;
    }

    public function lastName(): ?string
    {
        return $this->lastName;
    }
}
