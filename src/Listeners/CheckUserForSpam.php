<?php

namespace Silentz\Akismet\Listeners;

use Silentz\Akismet\Concerns\HandlesSpam;
use Silentz\Akismet\Spam\User;
use Statamic\Events\Event;

class CheckUserForSpam
{
    use HandlesSpam;

    public function handle(Event $event)
    {
        return $this->handleSpam(User::createFromEvent($event));
    }
}
