<?php

namespace Silentz\Akismet\Actions;

use Illuminate\Support\Collection;
use Silentz\Akismet\Spam\Submission as Spam;
use Statamic\Actions\Action;

class DeleteSpam extends Action
{
    protected $dangerous = true;

    public function authorize($user, $item)
    {
        return$user->can('manage spam', $item);
    }

    public function visibleTo($item)
    {
        return false;
    }

    /**
     * @param Collection $submissions
     * @param array $values
     * @return void
     */
    public function run($submissions, $values)
    {
        $submissions->mapInto(Spam::class)->each->delete();
    }
}
