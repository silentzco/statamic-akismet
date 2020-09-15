<?php

namespace Silentz\Akismet;

use Illuminate\Support\Facades\Log;
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
use Statamic\Statamic;

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
    }

    private function bootNav()
    {
        $handles = array_keys(config('akismet.forms', []));

        if (! count($handles)) {
            return;
        }

        NavAPI::extend(function (Nav $nav) use ($handles) {
            $nav->content('Spam Queue')
                ->section('Tools')
                ->route('akismet.index')
                ->icon('shield-key')
                ->children(collect($handles)->flatMap(function (string $handle) {
                    /** @var Form */
                    $form = FormAPI::find($handle);

                    return [$form->title() => cp_route('akismet.show', ['form' => $form->handle()])];
                })->all());
        });
    }

    private function bootPermissions()
    {
        Statamic::booted(function () {
            // Log::debug(Permission::all());
            // Permission::get('edit blade entries')->addChild(
            //     Permission::register('tweet {form} entries')
            // );
        });
    }
}
