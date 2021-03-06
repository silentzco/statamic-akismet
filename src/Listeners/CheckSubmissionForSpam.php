<?php

namespace Silentz\Akismet\Listeners;

use Silentz\Akismet\Concerns\HandlesSpam;
use Silentz\Akismet\Spam\Submission;
use Statamic\Events\Event;

class CheckSubmissionForSpam
{
    use HandlesSpam;

    public function handle(Event $event)
    {
        return $this->handleSpam(Submission::createFromEvent($event));
    }
}
