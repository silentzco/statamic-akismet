<?php

namespace Silentz\Akismet;

use Illuminate\Support\Arr;
use Silentz\Akismet\Exceptions\FormException;
use Statamic\Facades\Addon;

class Settings
{
    public static function forForm(string $form): FormSettings
    {
        $forms = Addon::get('silentz/akismet')->setting('forms', []);
        $config = Arr::first($forms, fn (array $config) => Arr::get($config, 'form') == $form);

        if (! $config) {
            throw FormException::missingForm();
        }

        return FormSettings::fromArray($config);
    }
}
