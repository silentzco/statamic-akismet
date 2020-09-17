<?php

namespace Silentz\Akismet\Spam;

use nickurt\Akismet\Akismet as AkismetAPI;

abstract class AbstractSpam implements Spam
{
    private AkismetAPI $akismetApi;

    public function __construct()
    {
        $this->akismetApi = app('Akismet');
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

    public function addToQueue(): void
    {
    }

    public function addToSubmissions(): void
    {
    }

    public function removeFromQueue(): void
    {
    }

    abstract protected function akismetData(): array;
}
