@extends('statamic::layout')

@section('title', Statamic::crumb(__('Spam'), $submission->form->title(), __('Spam Queues')))

@section('content')

    @include('statamic::partials.breadcrumb', [
        'url' => cp_route('akismet.spam.index', ['form' => $submission->form->handle()]),
        'title' => $submission->form->title()
    ])

    <publish-form
        title="{{ $title }}"
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
        read-only
    ></publish-form>

@endsection
