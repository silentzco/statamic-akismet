<?php

namespace Silentz\Akismet\Exceptions;

use RuntimeException;

class FormException extends RuntimeException
{
    public static function missingForm(): self
    {
        return new self('Form settings must include a form handle.');
    }
}
