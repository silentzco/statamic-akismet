<?php

namespace Silentz\Akismet\Http\Controllers\Ham;

use Silentz\Akismet\Spam\Submission;
use Statamic\Forms\Form;
use Statamic\Http\Controllers\Controller;

class StoreHamController extends Controller
{
    public function __invoke(Form $form, string $id)
    {
        $spam = Submission::createFromQueue($form, $id);

        $spam->addToSubmissions();

        $spam->submitHam();

        return response()->noContent();
    }
}
