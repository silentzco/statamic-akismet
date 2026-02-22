<?php

namespace Silentz\Akismet;

use Illuminate\Support\Arr;
use Statamic\Facades\Addon;

class Settings
{
    public static function forForm(string $form): array
    {
        $forms = Addon::get('silentz/akismet')->setting('forms', []);

        return Arr::first(
            $forms,
            fn (array $config) => Arr::get($config, 'form') == $form,
            []
        );
    }

    public static function isConfigured(string $form): bool
    {
        return ! empty(static::forForm($form));
    }
}
