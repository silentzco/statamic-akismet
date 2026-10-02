<?php

use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Statamic\Facades\Form;
use Statamic\Facades\User;

it('skips queues whose form no longer exists', function () {
    Storage::fake();
    Storage::put('spam/contact_us/1.yaml', 'spam');
    Storage::put('spam/deleted_form/1.yaml', 'spam');

    Form::make('contact_us')->title('Contact Us')->save();

    $this->actingAs(tap(User::make()->makeSuper())->save())
        ->get(cp_route('akismet.queues.index'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('akismet::Queues')
            ->has('queues', 1)
            ->where('queues.0.handle', 'contact_us')
            ->where('queues.0.count', 1));
});
