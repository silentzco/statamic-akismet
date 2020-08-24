<?php

namespace Silentz\Akismet\Http\Controllers;

use Statamic\Facades\Form;
use Statamic\Http\Controllers\Controller;
use Statamic\Support\Arr;

class QueuesController extends Controller
{
    public function __invoke()
    {
        $config = config('akismet.forms');
        $forms = Form::all()->filter(function ($form) use ($config) {
            return Arr::has($config, $form->handle());
        });

        return view(
            'akismet::cp.queues.index',
            [
                'forms' => $forms,
            ]
        );
    }
}
