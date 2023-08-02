@extends('layouts.guest')

@section('title', __('app.welcome'))

@section('content')
<div class="jumbotron p-4 p-md-5 text-white rounded bg-dark">
    <div class="col-md-6 px-0">
        <h1 class="display-4 font-italic">{{ config('app.name', 'Laravel') }}</h1>
        <p class="lead mb-0">
            <a class="btn btn-lg btn-success mr-2" href="{{ route('public_reports.index') }}" role="button">{{ __('report.view_report') }}</a>
            <a class="btn btn-lg btn-primary" href="{{ route('login') }}" role="button">{{ __('auth.login') }}</a>
        </p>
    </div>
</div>
@endsection
