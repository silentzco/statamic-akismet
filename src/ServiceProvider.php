<?php

namespace Silentz\Akismet;

use Edalzell\Forma\Forma;
use Illuminate\Support\Facades\Storage;
use Silentz\Akismet\Actions\MarkAsHam;
use Silentz\Akismet\Actions\MarkAsSpam;
use Silentz\Akismet\Commands\AddExtension;
use Silentz\Akismet\Commands\Convert;
use Silentz\Akismet\Http\Controllers\ConfigController;
use Silentz\Akismet\Listeners\CheckForSpam;
use Silentz\Akismet\Listeners\CheckSubmissionForSpam;
use Statamic\CP\Navigation\Nav;
use Statamic\Events\FormSubmitted;
use Statamic\Facades\CP\Nav as NavAPI;
use Statamic\Facades\Form as FormAPI;
use Statamic\Facades\Path;
use Statamic\Facades\Permission;
use Statamic\Forms\Form;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $publishAfterInstall = true;

    protected $actions = [
        MarkAsSpam::class,
        MarkAsHam::class,
    ];

    protected $commands = [
        AddExtension::class,
        Convert::class,
    ];

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

        $this->app->booted(function () {
            Forma::add('silentz/akismet', ConfigController::class);
        });

        $this->bootNav();
        $this->bootPermissions();
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

                    $title = $form->title().' ('.count(Storage::files(Path::assemble('spam', $form->handle()))).')';

                    return [$title => cp_route('akismet.spam.index', ['form' => $form->handle()])];
                })->filter()
                ->all());
        });
    }

    private function bootPermissions()
    {
        Permission::group('forms', function () {
            Permission::register('manage spam')->label('Manage Spam');
        });
    }
}
