@extends('statamic::layout')

@section('content')
    <publish-form
        title="Configuration"
        action="{{ cp_route('akismet.config.update') }}"
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
    ></publish-form>
@stop
