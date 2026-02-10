<?php

namespace Silentz\Akismet\Http\Controllers\Spam;

use Inertia\Inertia;
use Statamic\Forms\Form;
use Statamic\Http\Controllers\Controller;

class ListSpamController extends Controller
{
    public function __invoke(Form $form)
    {
        return Inertia::render(
            'akismet::SpamListing',
            [
                'columns' => ['name', 'email'],
                'form' => $form->handle(),
            ]
        );
    }
}
