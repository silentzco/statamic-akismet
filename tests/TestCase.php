<?php

namespace Tests;

use nickurt\Akismet\ServiceProvider as AkismetServiceProvider;
use Silentz\Akismet\ServiceProvider;
use Statamic\Testing\AddonTestCase;
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;

class TestCase extends AddonTestCase
{
    use PreventsSavingStacheItemsToDisk;

    protected string $addonServiceProvider = ServiceProvider::class;

    protected function getPackageProviders($app)
    {
        $serviceProviders = parent::getPackageProviders($app);

        return array_merge($serviceProviders, [AkismetServiceProvider::class]);
    }
}
