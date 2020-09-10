<?php

namespace Silentz\Akismet\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Statamic\Facades\Path;
use Statamic\Forms\Form;
use Statamic\Http\Controllers\Controller;

class DiscardSpamController extends Controller
{
    public function __invoke(Form $form, string $id)
    {
        Storage::delete(Path::assemble('spam', $form->handle(), $id));

        return response()->noContent();
    }
}
