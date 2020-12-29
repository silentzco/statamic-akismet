<?php

namespace Silentz\Akismet\Http\Controllers;

use Edalzell\Forma\ConfigController as BaseController;
use Illuminate\Support\Arr;

class ConfigController extends BaseController
{
    protected function preProcess(string $handle): array
    {
        $config = config($handle);

        return array_merge(
            $config,
            ['forms' => collect(Arr::get($config, 'forms'))
                ->map(fn ($data, $key) => Arr::set($data, 'handle', $key))
                ->values()
                ->all(),
            ]
        );
    }

    protected function postProcess(array $values): array
    {
        $forms = collect(Arr::get($values, 'forms'))->mapWithKeys(fn ($data) => [$data['handle'] => $data]);

        return array_merge($values, ['forms' => $forms->all()]);
    }
}
