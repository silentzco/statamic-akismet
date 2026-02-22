<?php

namespace Silentz\Akismet\UpdateScripts;

use Statamic\Facades\Addon;
use Statamic\UpdateScripts\UpdateScript;

class MigrateConfigToSettings extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('6.0');
    }

    public function update()
    {
        $settings = collect(config('akismet.forms', []));

        $settings->transform(function (array $config) {
            $config['form'] = $config['handle'];
            unset($config['handle']);

            return $config;
        });

        Addon::get('silentz/akismet')
            ->settings()
            ->set('forms', $settings->all())
            ->save();

        $this->console()->info('Configuration migrated!');
    }
}
