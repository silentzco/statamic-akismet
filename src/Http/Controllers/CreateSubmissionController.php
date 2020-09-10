<?php

namespace Silentz\Akismet\Http\Controllers;

use Silentz\Akismet\Concerns\Akismet;
use Statamic\Forms\Form;
use Statamic\Http\Controllers\Controller;

class CreateSubmissionController extends Controller
{
    use Akismet;

    public function __invoke(Form $form, string $id)
    {
        $this->loadFromQueue($form, $id);

        $this->removeFromQueue();

        $this->submitHam();

        return response()->noContent();
    }
}
