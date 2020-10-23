<?php

namespace Silentz\Akismet\Http\Controllers;

use Statamic\Forms\Form;
use Statamic\Http\Controllers\Controller;

class QueueController extends Controller
{
    public function __invoke(Form $form)
    {
        return view('akismet::cp.queues.show', ['form' => $form]);
    }
}
