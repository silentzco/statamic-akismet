<?php

namespace Silentz\Akismet\Actions;

use Illuminate\Support\Collection;
use Silentz\Akismet\Spam\Submission as Spam;
use Statamic\Actions\Action;
use Statamic\Forms\Submission;

class MarkAsSpam extends Action
{
    protected $dangerous = true;

    public function authorize($user, $item)
    {
        return $user->can('manage spam', $item);
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

    public function icon(): string
    {
        return file_get_contents(__DIR__.'/../../resources/svg/inbox-block.svg');
    }

    /**
     * @param  Submission  $item
     * @return bool
     */
    public function visibleTo($item)
    {
        return $item instanceof Submission &&
            request()->routeIs(['statamic.cp.forms.submissions.index', 'statamic.cp.forms.submissions.actions.bulk']);
    }

    /**
     * @param  Collection  $submissions
     * @param  array  $values
     * @return void
     */
    public function run($submissions, $values)
    {
        $submissions->mapInto(Spam::class)->each->makeSpam();
    }
}
