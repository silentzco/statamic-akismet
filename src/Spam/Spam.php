<?php

namespace Silentz\Akismet\Spam;

use Statamic\Events\Event;

interface Spam
{
    public static function createFromEvent(Event $event): self;

    public function shouldProcess(): bool;

    public function isSpam(): bool;

    public function addToQueue(): void;

    public function addToSubmissions(): void;

    public function removeFromQueue(): void;

    public function submitHam(): bool;

    public function submitSpam(): bool;
}
