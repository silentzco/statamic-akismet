<?php

namespace Silentz\Akismet\Actions;

use Illuminate\Support\Collection;
use Silentz\Akismet\Spam\Submission as Spam;
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
        return $item instanceof Submission && request()->routeIs(['statamic.cp.akismet.api.index', 'statamic.cp.akismet.actions.bulk']);
    }

    /**
     * @param Collection $submissions
     * @param array $values
     * @return void
     */
    public function run($submissions, $values)
    {
        $submissions->mapInto(Spam::class)->each->makeHam();
    }
}
