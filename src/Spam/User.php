<?php

namespace Silentz\Akismet\Spam;

use Statamic\Auth\User as StatamicUser;
use Statamic\Events\Event;
use Statamic\Facades\Site;
use Statamic\Facades\URL;
use Statamic\Support\Arr;

class User extends AbstractSpam
{
    private StatamicUser $user;

    public static function createFromEvent(Event $event): Spam
    {
        return $event->user;
    }

    public function __construct(StatamicUser $user)
    {
        parent::__construct();

        $this->user = $user;
    }

    public function shouldProcess(): bool
    {
        return true;
    }

    protected function akismetData(): array
    {
        $config = config('akismet.user');

        return [
            'blog' => URL::makeAbsolute(Site::default()->url()),
            'comment_type' => 'signup',
            'comment_author' => $this->user->get(Arr::get($config, 'name_field')),
            'comment_author_email' => $this->submission->get(Arr::get($config, 'email_field')),
        ];
    }
}
