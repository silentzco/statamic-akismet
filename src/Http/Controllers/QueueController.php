<?php

namespace Silentz\Akismet\Http\Controllers;

use Statamic\CP\Column;
use Statamic\Forms\Form;
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

        return view('akismet::cp.queues.show', [
            'columns' => $columns,
            'form' => $form,
            'spam' => $form->submissions()->map(function ($submission) {
                return [
                    'name' => $submission->get('first_name'),
                    'email' => $submission->get('email'),
                    'content' => $submission->get('content'),
                ];
            }),
        ]);
    }
}
