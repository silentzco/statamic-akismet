<?php

namespace Silentz\Akismet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Statamic\Facades\Blueprint;
use Statamic\Facades\Path;
use Statamic\Facades\YAML;
use Statamic\Http\Controllers\Controller;
use Stillat\Proteus\Support\Facades\ConfigWriter;

class ConfigurationController extends Controller
{
    public function edit()
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

    public function update(Request $request)
    {
        $blueprint = $this->getBlueprint();

        // Get a Fields object, and populate it with the submitted values.
        $fields = $blueprint->fields()->addValues($request->all());

        // Perform validation. Like Laravel's standard validation, if it fails,
        // a 422 response will be sent back with all the validation errors.
        $fields->validate();

        ConfigWriter::writeMany('akismet', $this->postProcess($fields->process()->values()->toArray()));
    }

    private function getBlueprint()
    {
        // @TODO gotta be a better way to do this
        $addonPath = Path::tidy(__DIR__.'/../../../');
        $path = Path::assemble($addonPath, 'resources', 'blueprints', 'config.yaml');

        return Blueprint::makeFromFields(YAML::file($path)->parse());
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

    private function postProcess(array $values): array
    {
        $forms = Arr::get($values, 'forms');

        $forms = collect($forms)->mapWithKeys(function ($data) {
            return [$data['handle'] => $data];
        });

        return Arr::set($values, 'forms', $forms->all());
    }
}
