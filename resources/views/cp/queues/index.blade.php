@extends('statamic::layout')
@section('title', __('Forms'))

@section('content')

    <div class="flex items-center justify-between mb-3">
        <h1>@yield('title')</h1>
    </div>

    @foreach ($forms as $form)
        @if ($loop->first)
        <h3 class="pl-0 mb-1 little-heading">{{ __('Forms') }}</h3>
        <div class="p-0 mb-2 card">
            <table class="data-table">
        @endif
                <tr>
                    <td>
                        <div class="flex items-center">
                            <div class="w-4 h-4 mr-2">@svg('drawer-file')</div>
                            <a href="{{ cp_route('akismet.show', ['form' => $form->handle()]) }}">{{ $form->title() }}</a>
                        </div>
                    </td>
        @if ($loop->last)
                </tr>
        </table>
        @endif
    </div>
    @endforeach
@endsection
