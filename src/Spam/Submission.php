<?php

namespace Silentz\Akismet\Spam;

use Illuminate\Support\Facades\Storage;
use Statamic\Events\Event;
use Statamic\Facades\Path;
use Statamic\Facades\Site;
use Statamic\Facades\URL;
use Statamic\Forms\Submission as StatamicSubmission;
use Statamic\Support\Arr;

class Submission extends AbstractSpam
{
    private StatamicSubmission $submission;

    public static function createFromEvent(Event $event): Spam
    {
        return new self($event->submission);
    }

    public function __construct(StatamicSubmission $submission)
    {
        parent::__construct();

        $this->submission = $submission;
    }

    public function shouldProcess(): bool
    {
        return Arr::has(config('akismet.forms'), $this->submission->form->handle());
    }

    public function addToQueue(): void
    {
        Storage::put(
                Path::assemble('spam', $this->submission->form->handle(), $this->submission->id()),
                serialize($this->submission->data())
            );
    }

    public function removeFromQueue(): void
    {
        Storage::delete(Path::assemble('spam', $this->submission->form()->handle(), $this->submission->id()));
    }

    protected function akismetData(): array
    {
        $config = Arr::get(config('akismet.forms'), $this->submission->form->handle());

        return [
            'blog' => URL::makeAbsolute(Site::default()->url()),
            'comment_type' => 'contact-form',
            'comment_author' => $this->submission->get(Arr::get($config, 'author_field')),
            'comment_author_email' => $this->submission->get(Arr::get($config, 'email_field')),
            'comment_content' => $this->submission->get(Arr::get($config, 'content_field')),
        ];
    }
}
