<?php

namespace Silentz\Akismet\Actions;

use Silentz\Akismet\Concerns\Akismet;
use Statamic\Actions\Action;
use Statamic\Forms\Submission;

class MarkAsSpam extends Action
{
    use Akismet;

    protected $dangerous = true;

    public function authorize($user, $item)
    {
        return true; //$user->can('edit', $item);
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

    public function filter($item)
    {
        return $item instanceof Submission;
    }

    /**
     * @param \Illuminate\Support\Collection $submissions
     * @param array $values
     * @return void
     */
    public function run($submissions, $values)
    {
        $submissions->each(function (Submission $submission) {

            // @TODO refactor this, it be ugly
            $this->loadFromSubmission($submission);
            $this->addToQueue();
            // submit as spam
            $submission->delete();
        });
    }
}
