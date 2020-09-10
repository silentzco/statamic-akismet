<?php

namespace Silentz\Akismet\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Statamic\Facades\Path;
use Statamic\Forms\Form;
use Statamic\Http\Controllers\Controller;

class ApproveHamController extends Controller
{
    public function __invoke(Form $form, string $id)
    {
        // move back to form directory
        // submit ham

        return response()->noContent();
    }
}
