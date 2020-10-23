@extends('statamic::layout')
@section('title', Statamic::crumb('Submission ' . $submission->id(), $submission->form->title(), 'Forms'))

@section('content')

    @include('statamic::partials.breadcrumb', [
        'url' => route('statamic.cp.akismet.queues.show', $submission->form->handle()),
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
