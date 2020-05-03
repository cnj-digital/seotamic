@extends('statamic::layout')

@section('content')
    <div class="flex items-center mb-3">
        <h1 class="flex-1">SEOtamic</h1>
    </div>
    <div class="flex items-center mb-3">
        <p>Control your SEO general settings here. Make sure to read the instructions on each input. This settings can be overidden on specific entries/pages.</p>
    </div>

    <div>
        <publish-form
                title="Settings"
                action="{{ cp_route('cnj.seotamic.update') }}"
                :blueprint='@json($blueprint)'
                :meta='@json($meta)'
                :values='@json($values)'
        />

    </div>
@stop
