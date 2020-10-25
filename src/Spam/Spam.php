<?php

namespace Silentz\Akismet\Spam;

use Statamic\Events\Event;

interface Spam
{
    public static function createFromEvent(Event $event): self;

    public function addToSubmissions(): void;

    public function addToQueue(): void;

    public function isSpam(): bool;

    public function delete(): void;

    public function shouldProcess(): bool;

    public function submitHam(): bool;

    public function submitSpam(): bool;
}
