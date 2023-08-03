@extends('layouts.guest')

@section('title', __('app.welcome'))

@section('content')
@yield('title') - {{ config('app.name', 'Laravel') }}
@endsection
