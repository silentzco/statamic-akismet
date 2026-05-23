<?php

namespace Silentz\Akismet;

use Illuminate\Support\Arr;
use Statamic\Facades\Addon;

class Settings
{
    public static function forForm(string $form): ?FormSettings
    {
        $forms = Addon::get('silentz/akismet')->setting('forms', []);
        $config = Arr::first($forms, fn (array $config) => Arr::get($config, 'form') == $form);

        return $config ? FormSettings::fromArray($config) : null;
    }

    public static function isConfigured(string $form): bool
    {
        return static::forForm($form) !== null;
    }
}
