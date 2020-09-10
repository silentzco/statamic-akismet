<?php

namespace Silentz\Akismet\Concerns;

use Silentz\Akismet\Spam\Spam;

trait HandlesSpam
{
    public function handleSpam(Spam $spam): bool
    {
        if ($spam->shouldProcess() && $spam->isSpam()) {
            $spam->addToQueue();

            return false;
        }

        return true;
    }
}
