<?php

namespace Silentz\Akismet\Concerns;

use Statamic\Facades\Blueprint;
use Statamic\Facades\Path;
use Statamic\Facades\YAML;

trait HasBlueprint
{
    private function getBlueprint()
    {
        // @TODO gotta be a better way to do this
        $addonPath = Path::tidy(__DIR__.'/../../');
        $path = Path::assemble($addonPath, 'resources', 'blueprints', 'config.yaml');

        return Blueprint::makeFromFields(YAML::file($path)->parse());
    }
}
