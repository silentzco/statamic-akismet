<?php

namespace Silentz\Akismet\Spam;

use nickurt\Akismet\Akismet;

abstract class AbstractSpam implements Spam
{
    private Akismet $akismetApi;

    public function __construct()
    {
        $this->akismetApi = app(Akismet::class);
    }

    public function addToQueue(): void
    {
    }

    public function addToSubmissions(): void
    {
    }

    public function delete(): void
    {
    }

    public function isSpam(): bool
    {
        return $this->akismetApi->fill($this->akismetData())->isSpam();
    }

    public function submitHam(): bool
    {
        return $this->akismetApi->fill($this->akismetData())->reportHam();
    }

    public function submitSpam(): bool
    {
        return $this->akismetApi->fill($this->akismetData())->reportSpam();
    }

    abstract protected function akismetData(): array;
}
