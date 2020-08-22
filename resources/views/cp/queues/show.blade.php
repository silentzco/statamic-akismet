@extends('statamic::layout')
@section('title', Statamic::crumb($form->title(), 'Queue'))

@section('content')

    <header class="mb-3">
        @include('statamic::partials.breadcrumb', [
            'url' => cp_route('akismet.index'),
            'title' => __('Forms')
        ])
        <div class="flex items-center">
            <h1 class="flex-1">
                {{ $form->title() }}
            </h1>
        </div>
    </header>


    <spam-listing
        form="{{ $form->handle() }}"
        :rows="{{ json_encode($spam) }}"
        :columns="{{ json_encode($columns) }}"
        run-action-url="{{ cp_route('forms.submissions.actions.run', $form->handle()) }}"
        bulk-actions-url="{{ cp_route('forms.submissions.actions.bulk', $form->handle()) }}"
        initial-sort-column="datestamp"
        initial-sort-direction="desc"
        v-cloak
    >
        <div slot="no-results" class="text-center border-2 border-dashed rounded-lg">
            <div class="max-w-md px-4 py-8 mx-auto">
                @svg('empty/form')
                <h1 class="my-3">{{ __('No submissions') }}</h1>
            </div>
        </div>
    </spam-listing>

@endsection
