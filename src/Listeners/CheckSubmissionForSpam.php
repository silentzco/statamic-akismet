<?php

namespace Silentz\Akismet\Listeners;

use Illuminate\Support\Facades\Storage;
use nickurt\Akismet\Facade as AkismetAPI;
use Statamic\Events\FormSubmitted;
use Statamic\Facades\Path;
use Statamic\Facades\Site;
use Statamic\Facades\URL;
use Statamic\Forms\Submission;
use Statamic\Support\Arr;

class CheckSubmissionForSpam
{
    public function handle(FormSubmitted $event)
    {
        /** @var Submission */
        $submission = $event->form;

        // only do something if we're on the right formset & it's spam
        if ($this->shouldProcessForm($submission) && ($this->detectSpam($submission))) {
            $this->addToQueue($submission);

            return false;
        }

        return true;
    }

    private function shouldProcessForm(Submission $submission): bool
    {
        return Arr::has(config('akismet.forms'), $submission->form->handle());
    }

    public function detectSpam(Submission $submission)
    {
        return AkismetAPI::fill($this->convertSubmissionToAkismetData($submission))->isSpam();
    }

    private function convertSubmissionToAkismetData(Submission $submission): array
    {
        $config = Arr::get(config('akismet.forms'), $submission->form->handle());

        return [
            'blog' => URL::makeAbsolute(Site::default()->url()),
            'comment_type' => 'content-form',
            'comment_author' => $submission->get(Arr::get($config, 'author_field')),
            'comment_author_email' => $submission->get(Arr::get($config, 'email_field')),
            'comment_content' => $submission->get(Arr::get($config, 'content_field')),
        ];
    }

    private function addToQueue(Submission $submission)
    {
        Storage::put(
            Path::assemble('akismet', $submission->form->handle(), $submission->id()),
            serialize($submission->data())
        );
    }
}
