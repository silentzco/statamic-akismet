<?php

namespace Silentz\Akismet\Actions;

use Silentz\Akismet\Spam\Submission as SubmissionSpam;
use Statamic\Actions\Action;
use Statamic\Forms\Submission;

class MarkAsSpam extends Action
{
    protected $dangerous = true;

    public function authorize($user, $item)
    {
        return$user->can('manage spam', $item);
    }

    public function buttonText()
    {
        /* @translation */
        return 'Spam|Spam';
    }

    public function confirmationText()
    {
        /* @translation */
        return 'Are you sure this submission is spam?|Are you sure these :count submissions are spam?';
    }

    public function visibleTo($item)
    {
        return $item instanceof Submission && ! request()->routeIs('statamic.cp.akismet.api.index');
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

            $spam->addToQueue();
            // $spam->submitSpam();

            $submission->delete();
        });
    }
}
