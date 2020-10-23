<?php

namespace Silentz\Akismet\Actions;

use Illuminate\Support\Str;
use Silentz\Akismet\Spam\Submission as SubmissionSpam;
use Statamic\Actions\Action;
use Statamic\Forms\Submission;

class MarkAsHam extends Action
{
    protected $dangerous = true;

    public function authorize($user, $item)
    {
        return$user->can('manage spam', $item);
    }

    public function buttonText()
    {
        /* @translation */
        return 'Ham|Ham';
    }

    public function confirmationText()
    {
        /* @translation */
        return 'Are you sure this submission is ham?|Are you sure these :count submissions are ham?';
    }

    public function visibleTo($item)
    {
        return $item instanceof Submission && Str::of(request()->url())->contains('akismet/queues');
    }

    /**
     * @param \Illuminate\Support\Collection $submissions
     * @param array $values
     * @return void
     */
    public function run($submissions, $values)
    {
        $submissions->each(function (Submission $submission) {
            $spam = new SubmissionSpam($submission);

            $spam->removeFromQueue();
            $spam->submitHam();

            $submission->save();
        });
    }
}
