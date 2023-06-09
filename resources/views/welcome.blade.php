@extends('layouts.guest')

@section('title', __('app.welcome'))

@section('content')
<div class="jumbotron">
    <h1>{{ config('app.name', 'Laravel') }}</h1>
    <p class="lead">Dompet is a personal bookkeeping web application, built with Laravel. It designed for easy bookkeeping for personal income and spending. </p>
    <p>
        <a class="btn btn-lg btn-success mr-2" href="{{ route('register') }}" role="button">Sign up today</a>
        <a class="btn btn-lg btn-primary" href="{{ route('login') }}" role="button">Login</a>
    </p>
</div>

<div class="row">
    <div class="col-lg-4">
        <h4>Objective</h4>
        <p>Easy bookkeeping for personal income and spending (amount of money).</p>
    </div>
    <div class="col-lg-8">
        <h4>Concept</h4>
        <p>To aquire our objective, we need this features on the application:</p>
        <ul>
            <li>User can register.</li>
            <li>User can see transaction history by date.</li>
            <li>User add transactions for income and spending.</li>
            <li>User can categorize the transaction.</li>
            <li>User can see transaction summary on each month or a year.</li>
        </ul>
    </div>
</div>
@endsection
