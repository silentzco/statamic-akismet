<?php

namespace Silentz\Akismet\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Statamic\Facades\Form;
use Statamic\Http\Controllers\Controller;
use Statamic\Support\Str;

class QueuesController extends Controller
{
    public function __invoke()
    {
        $spamQueues = collect(Storage::directories('spam'))
            ->map(fn ($path) => [
                    'form' => Form::find(Str::removeLeft($path, 'spam'.DIRECTORY_SEPARATOR)),
                    'spam' => count(Storage::files($path)),
                ]
            )->filter(fn ($queue) => $queue['spam']);

        return view(
            'akismet::cp.queues.index',
            [
                'queues' => $spamQueues,
            ]
        );
    }
}
