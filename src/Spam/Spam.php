<?php

namespace Silentz\Akismet\Spam;

use Statamic\Events\Event;

interface Spam
{
    public static function createFromEvent(Event $event): self;

    // private function loadFromQueue(Form $form, string $id)
    // {
    //     $this->submission = $form->makeSubmission()
    //             ->id($id)
    //             ->data(unserialize(Storage::get(Path::assemble('spam', $form->handle(), $id))))
    //             ->save();
    // }

    public function shouldProcess(): bool;

    public function isSpam(): bool;

    public function addToQueue(): void;

    public function removeFromQueue(): void;

    public function submitHam(): bool;

    public function submitSpam(): bool;
}
