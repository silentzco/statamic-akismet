<?php

namespace Silentz\Akismet\Spam;

use Illuminate\Support\Facades\Storage;
use Silentz\Akismet\Settings;
use Statamic\Events\Event;
use Statamic\Facades\Path;
use Statamic\Facades\Site;
use Statamic\Facades\URL;
use Statamic\Facades\YAML;
use Statamic\Forms\Form;
use Statamic\Forms\Submission as StatamicSubmission;

class Submission extends AbstractSpam
{
    private StatamicSubmission $submission;

    public static function createFromEvent(Event $event): Spam
    {
        return new self($event->submission);
    }

    public static function createFromQueue(Form $form, string $id): self
    {
        /** @var \Statamic\Forms\Submission */
        $submission = $form
            ->makeSubmission()
            ->id($id)
            ->data(YAML::parse(Storage::get(self::path($form->handle(), $id))));

        return new self($submission);
    }

    public static function path($handle, $id): string
    {
        return Path::assemble('spam', $handle, $id.'.yaml');
    }

    public function __construct(StatamicSubmission $submission)
    {
        parent::__construct();

        $this->submission = $submission;
    }

    public function makeHam(): void
    {
        $this->delete();
        $this->submitHam();

        $this->addToSubmissions();
    }

    public function makeSpam(): void
    {
        $this->addToQueue();
        $this->submitSpam();

        $this->submission()->delete();
    }

    public function addToQueue(): void
    {
        Storage::put(
            self::path($this->submission->form->handle(), $this->submission->id()),
            YAML::dump(collect($this->submission->data())->all())
        );
    }

    public function addToSubmissions(): void
    {
        $this->submission->save();
    }

    public function delete(): void
    {
        Storage::delete(self::path($this->submission->form()->handle(), $this->submission->id()));
    }

    public function submission(): StatamicSubmission
    {
        return $this->submission;
    }

    public function shouldProcess(): bool
    {
        return Settings::isConfigured($this->submission->form->handle());
    }

    protected function akismetData(): array
    {
        $settings = Settings::forForm($this->submission->form->handle());

        return [
            'blog' => URL::makeAbsolute(Site::default()->url()),
            'comment_type' => 'contact-form',
            'comment_author' => $this->getName(),
            'comment_author_email' => $this->submission->get($settings->email()),
            'comment_content' => $this->submission->get($settings->content()),
        ];
    }

    private function getName(): string
    {
        $settings = Settings::forForm($this->submission->form->handle());

        if ($name = $settings->name()) {
            return trim($this->submission->get($name));
        }

        if (! $settings->hasFirstAndLastName()) {
            return '';
        }

        $firstName = $this->submission->get($settings->firstName());
        $lastName = $this->submission->get($settings->lastName());

        return trim($firstName.' '.$lastName);
    }
}
