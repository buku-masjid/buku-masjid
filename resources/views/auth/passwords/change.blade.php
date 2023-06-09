@extends('layouts.settings')

@section('title', __('auth.change_password'))

@section('content_settings')
<div class="row">
    <div class="col-md-4 offset-md-4">
        <div class="page-header"><h1 class="page-title">@yield('title')</h1></div>
        <div class="card">
            {!! Form::open(['route' => 'password.change', 'method' => 'patch']) !!}
            <div class="card-body">
                {!! FormField::password('old_password', ['label'=> __('auth.old_password')]) !!}
                {!! FormField::password('password', ['label' => __('auth.new_password')]) !!}
                {!! FormField::password('password_confirmation', ['label' => __('auth.new_password_confirmation')]) !!}
            </div>
            <div class="card-footer">
                {!! Form::submit(__('auth.change_password'), ['class' => 'btn btn-info']) !!}
                {!! link_to_route('home', __('app.cancel'), [], ['class' => 'btn btn-secondary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
