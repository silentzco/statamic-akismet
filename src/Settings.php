<?php

namespace Silentz\Akismet;

use Statamic\Facades\Addon;

class Settings
{
    public static function get(string $key, mixed $default = null): mixed
    {
        return Addon::get('silentz/akismet')->setting($key, $default);
    }
}
