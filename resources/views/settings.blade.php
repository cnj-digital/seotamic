@extends('statamic::layout')

@section('content')
    <div class="flex items-center mb-3">
        <h1 class="flex-1">SEOtamic</h1>
    </div>
    <div class="flex items-center mb-3">
        <p>{{ __('seotamic::general.intro') }}</p>
    </div>

    <div>
        <publish-form title="Settings" action="{{ cp_route('cnj.seotamic.update') }}"
            :blueprint='@json($blueprint)' :meta='@json($meta)'
            :values='@json($values)' />

    </div>
@stop
