<?php

namespace Silentz\Akismet\Http\Controllers\Spam;

use Statamic\Forms\Form;
use Statamic\Http\Controllers\Controller;

class ListSpamController extends Controller
{
    public function __invoke(Form $form)
    {
        return view('akismet::cp.spam.index', ['form' => $form]);
    }
}
