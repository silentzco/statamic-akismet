<?php

namespace Silentz\Akismet;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Silentz\Akismet\Listeners\CheckSubmissionForSpam;
use Statamic\CP\Navigation\Nav;
use Statamic\Events\FormSubmitted;
use Statamic\Facades\CP\Nav as NavFacade;
use Statamic\Facades\Form as FormFacade;
use Statamic\Facades\Path;
use Statamic\Facades\Permission;
use Statamic\Forms\Form;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $listen = [
        FormSubmitted::class => [CheckSubmissionForSpam::class],
    ];

    protected $vite = [
        'input' => [
            'resources/js/cp.js',
            'resources/css/cp.css',
        ],
        'publicDirectory' => 'resources/dist',
    ];

    public function bootAddon()
    {
        $this->bootNav()->bootPermissions();
    }

    private function bootNav(): self
    {
        $forms = collect(Arr::pluck($this->getAddon()->setting('forms', []), 'form'))
            ->map(fn(string $handle) => FormFacade::find($handle))
            ->filter();

        NavFacade::extend(fn (Nav $nav) => $nav
            ->content('Spam Queue')
            ->section('Akismet')
            ->route('akismet.queues.index')
            ->icon('shield-key')
            ->can('manage spam')
            ->children($this->menuItems($forms))
        );

        return $this;
    }

    private function bootPermissions(): self
    {
        Permission::group('forms', function () {
            Permission::register('manage spam')->label('Manage Spam');
        });

        return $this;
    }

    private function menuItems(Collection $forms): array {
        return $forms->flatMap(fn (Form $form) => [
            $this->menuTitle($form) => $this->spamQueueRoute($form->handle())
        ])->all();
    }

    private function menuTitle(Form $form): string {
        return $form->title().' ('.count(Storage::files(Path::assemble('spam', $form->handle()))).')';
    }

    private function spamQueueRoute(string $formHandle): string {
        return cp_route('akismet.spam.index', ['form' => $formHandle]);
    }
}
