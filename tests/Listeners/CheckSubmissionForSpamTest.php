<?php

use Illuminate\Support\Facades\Event;
use Silentz\Akismet\Listeners\CheckSubmissionForSpam;
use Statamic\Events\FormSubmitted;

it('listens for customer subscription created', function () {
    Event::fake();

    Event::assertListening(FormSubmitted::class, CheckSubmissionForSpam::class);
});
