<?php

namespace Silentz\Akismet\Http\Controllers\Config;

use Illuminate\Support\Arr;
use Silentz\Akismet\Concerns\HasBlueprint;
use Statamic\Http\Controllers\Controller;

class EditConfigController extends Controller
{
    use HasBlueprint;

    public function __invoke()
    {
        $blueprint = $this->getBlueprint();

        $fields = $blueprint
            ->fields()
            ->addValues($this->preProcess())
            ->preProcess();

        return view('akismet::cp.config.edit', [
                'blueprint' => $blueprint->toPublishArray(),
                'values' => $fields->values(),
                'meta' => $fields->meta(),
            ]);
    }

    private function preProcess(): array
    {
        $config = config('akismet');

        return Arr::set(
            $config,
            'forms',
            collect(Arr::get($config, 'forms'))
                ->map(fn ($data, $key) => Arr::set($data, 'handle', $key))
                ->values()
                ->all()
        );
    }
}
