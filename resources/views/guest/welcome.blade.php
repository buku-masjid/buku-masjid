@extends('layouts.guest')

@section('title', __('app.welcome'))

@section('content')
<div class="jumbotron">
    <h1>{{ config('app.name', 'Laravel') }}</h1>
    <p class="lead">Buku masjid adalah aplikasi pencatatan keuangan masjid berbasis web.</p>
    <p>
        <a class="btn btn-lg btn-success mr-2" href="{{ route('public_reports.index') }}" role="button">Lihat laporan</a>
        <a class="btn btn-lg btn-primary" href="{{ route('login') }}" role="button">Login</a>
    </p>
</div>
@endsection
