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

    // private function loadFromQueue(Form $form, string $id)
    // {
    //     $this->submission = $form->makeSubmission()
    //             ->id($id)
    //             ->data(unserialize(Storage::get(Path::assemble('spam', $form->handle(), $id))))
    //             ->save();
    // }

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

    public function removeFromQueue(): void
    {
    }

    abstract protected function akismetData(): array;
}
