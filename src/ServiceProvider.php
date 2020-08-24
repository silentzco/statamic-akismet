<?php

namespace Silentz\Akismet;

use Silentz\Akismet\Listeners\CheckSubmissionForSpam;
use Statamic\CP\Navigation\Nav;
use Statamic\Events\FormSubmitted;
use Statamic\Facades\CP\Nav as NavAPI;
use Statamic\Facades\Form as FormAPI;
use Statamic\Forms\Form;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $publishAfterInstall = false;

    protected $listen = [
        // 'user.registered' => [
        //     'Silentz\Akismet\AddFromUser',
        // ],
        FormSubmitted::class => [CheckSubmissionForSpam::class],
    ];

    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    protected $scripts = [
        __DIR__.'/../resources/dist/js/cp.js',
    ];

    public function boot()
    {
        parent::boot();

        $this->bootNav();
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
}
