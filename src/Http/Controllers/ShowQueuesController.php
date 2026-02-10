<?php

namespace Silentz\Akismet\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Statamic\Facades\Form;
use Statamic\Http\Controllers\Controller;
use Statamic\Support\Str;

class ShowQueuesController extends Controller
{
    public function __invoke()
    {
        $spamQueues = collect(Storage::directories('spam'))
            ->map(function (string $path) {
                $form = Form::find(Str::removeLeft($path, 'spam/'));

                return [
                    'count' => count(Storage::files($path)),
                    'handle' => $form->handle(),
                    'link' => cp_route('akismet.spam.index', ['form' => $form->handle()]),
                    'title' => $form->title(),
                ];
            })->filter(fn (array $queue) => $queue['count']);

        return Inertia::render('akismet::Queues', ['queues' => $spamQueues]);
    }
}
