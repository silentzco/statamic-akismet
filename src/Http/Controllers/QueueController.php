<?php

namespace Silentz\Akismet\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Statamic\CP\Column;
use Statamic\Forms\Form;
use Statamic\Forms\Submission;
use Statamic\Http\Controllers\Controller;

class QueueController extends Controller
{
    public function __invoke(Form $form)
    {
        $columns = [
            Column::make('name')->label(__('akismet::cp.name')),
            Column::make('email')->label(__('akismet::cp.email')),
            Column::make('content')->label(__('akismet::cp.content')),
        ];

        $paths = Storage::files("spam/{$form->handle()}");

        $spam = collect($paths)->map(function ($path) use ($form) {
            return tap(new Submission(), function ($submission) use ($path, $form) {
                $submission->id(basename($path));
                $submission->form($form);
                $submission->data(unserialize(Storage::get($path)));
            });
        });

        return view('akismet::cp.queues.show', [
            'columns' => $columns,
            'form' => $form,
            'spam' => $spam,
        ]);
    }
}
