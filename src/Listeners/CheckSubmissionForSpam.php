<?php

namespace Silentz\Akismet\Listeners;

use Illuminate\Support\Facades\Storage;
use nickurt\Akismet\Facade as AkismetAPI;
use Statamic\Events\FormSubmitted;
use Statamic\Exceptions\SilentFormFailureException;
use Statamic\Facades\Path;
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

            throw new SilentFormFailureException('Spam submitted');
        }

        return true;
    }

    private function shouldProcessForm(Submission $submission): bool
    {
        return in_array($submission->form->handle(), config('akismet.forms'));
    }

    public function detectSpam(Submission $submission)
    {
        return AkismetAPI::fill($this->convertSubmissionToAkismetData($submission))->isSpam();
    }

    private function convertSubmissionToAkismetData(Submission $submission): array
    {
        return array_merge(
            [
                'blog' => $this->siteUrl,
                'comment_type' => 'content-form',
            ],
            array_combine(
                [
                    'comment_author',
                    'comment_author_email',
                    'comment_content',
                ],
                array_only(
                    $submission->toArray(),
                    Arr::get(config('akismet.forms'), $submission->form->handle())
                )
            )
        );
    }

    private function addToQueue(Submission $submission)
    {
        Storage::put(
            Path::assemble('akismet', $submission->form->handle(), $submission->id()),
            serialize($submission->data())
        );
    }
}
