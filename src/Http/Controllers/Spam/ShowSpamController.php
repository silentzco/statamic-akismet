<?php

namespace Silentz\Akismet\Http\Controllers\Spam;

use Inertia\Inertia;
use Silentz\Akismet\Spam\Submission;
use Statamic\Forms\Form;
use Statamic\Http\Controllers\CP\CpController;

class ShowSpamController extends CpController
{
    public function __invoke(Form $form, string $id)
    {
        if (! $submission = Submission::createFromQueue($form, $id)->submission()) {
            return $this->pageNotFound();
        }

        $blueprint = $submission->blueprint();

        $fields = $blueprint->fields()->addValues(collect($submission->data())->all())->preProcess();

        return Inertia::render('forms/Submission', [
            'id' => $submission->id(),
            'formTitle' => $form->title(),
            'date' => $submission->date()->toIso8601String(),
            'blueprint' => $blueprint->toPublishArray(),
            'values' => $fields->values(),
            'meta' => $fields->meta(),
        ]);
    }
}
