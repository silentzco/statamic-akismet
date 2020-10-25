<?php

namespace Silentz\Akismet\Http\Controllers\Spam;

use Illuminate\Support\Facades\Storage;
use Statamic\Facades\Path;
use Statamic\Forms\Form;
use Statamic\Http\Controllers\Controller;

class DestroySpamController extends Controller
{
    public function __invoke(Form $form, string $id)
    {
        Storage::delete(Path::assemble('spam', $form->handle(), $id));

        return response()->noContent();
    }
}
