<?php

namespace Silentz\Akismet\Http\Controllers\Spam;

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

        return view('akismet::cp.spam.show', [
            'form' => $form,
            'submission' => $submission,
            'blueprint' => $blueprint->toPublishArray(),
            'values' => $fields->values(),
            'meta' => $fields->meta(),
            'title' => $submission->date()->format('M j, Y @ H:i'),
        ]);
    }
}
