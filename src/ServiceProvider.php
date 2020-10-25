<?php

namespace Silentz\Akismet;

use Silentz\Akismet\Actions\MarkAsHam;
use Silentz\Akismet\Actions\MarkAsSpam;
use Silentz\Akismet\Listeners\CheckForSpam;
use Silentz\Akismet\Listeners\CheckSubmissionForSpam;
use Statamic\CP\Navigation\Nav;
use Statamic\Events\FormSubmitted;
use Statamic\Facades\CP\Nav as NavAPI;
use Statamic\Facades\Form as FormAPI;
use Statamic\Facades\Permission;
use Statamic\Forms\Form;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $publishAfterInstall = true;

    protected $listen = [
        // UserRegistering::class => [CheckForSpam::class],
        FormSubmitted::class => [CheckSubmissionForSpam::class],
    ];

    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    protected $scripts = [
        __DIR__.'/../dist/js/cp.js',
    ];

    public function boot()
    {
        parent::boot();

        $this->bootActions();
        $this->bootNav();
        $this->bootPermissions();
    }

    private function bootActions()
    {
        MarkAsSpam::register();
        MarkAsHam::register();
    }

    private function bootNav()
    {
        $handles = array_keys(config('akismet.forms', []));

        if (! count($handles)) {
            return;
        }

        NavAPI::extend(function (Nav $nav) use ($handles) {
            $nav->content('Spam Queue')
                ->section('Akismet')
                ->route('akismet.queues.index')
                ->icon('shield-key')
                ->can('manage spam')
                ->children(collect($handles)->flatMap(function (string $handle) {
                    /* @var Form */
                    if (! $form = FormAPI::find($handle)) {
                        return;
                    }

                    return [$form->title() => cp_route('akismet.spam.index', ['form' => $form->handle()])];
                })->filter()
                ->all());
        });

        NavAPI::extend(function (Nav $nav) use ($handles) {
            $nav->content('Config')
                ->section('Akismet')
                ->route('akismet.config.edit')
                ->icon('settings-horizontal');
        });
    }

    private function bootPermissions()
    {
        Permission::group('forms', function () {
            Permission::register('manage spam')->label('Manage Spam');
        });
    }
}
