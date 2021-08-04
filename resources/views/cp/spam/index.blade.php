@extends('statamic::layout')

@section('title', Statamic::crumb($form->title(), __('Spam Queues')))

@section('content')

    <header class="mb-3">
        @include('statamic::partials.breadcrumb', [
            'url' => cp_route('akismet.queues.index'),
            'title' => __('Spam Queues')
        ])
        <div class="flex items-center">
            <h1 class="flex-1">
                {{ $form->title() }}
            </h1>
        </div>
    </header>

    <spam-listing
        form="{{ $form->handle() }}"
        action-url="{{ cp_route('akismet.actions.run', $form->handle()) }}"
        initial-sort-column="datestamp"
        initial-sort-direction="desc"
        v-cloak
    >
        <div slot="no-results" class="text-center border-2 border-dashed rounded-lg">
            <div class="max-w-md px-4 py-8 mx-auto">
                @cp_svg('empty/form')
                <h1 class="my-3">{{ __('No spam') }}</h1>
            </div>
        </div>
    </spam-listing>

@endsection
