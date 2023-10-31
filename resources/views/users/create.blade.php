@extends('layouts.settings')

@section('title', __('user.create'))

@section('content_settings')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="page-header">
            <h1 class="page-title">{{ __('user.create') }}</h1>
        </div>
        <div class="card">
            {{ Form::open(['route' => 'users.store']) }}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">{!! FormField::text('name', ['required' => true, 'label' => __('user.name')]) !!}</div>
                </div>
                <div class="row">
                    <div class="col-md-6">{!! FormField::email('email', ['required' => true, 'label' => __('user.email')]) !!}</div>
                    <div class="col-md-6">
                        {!! FormField::password('password', [
                            'info' => [
                                'text' => __('user.default_password_note', ['password' => config('auth.passwords.default')])
                            ]
                        ]) !!}
                    </div>
                </div>
                {!! FormField::radios('role_id', $roles, ['value' => App\User::ROLE_ADMIN, 'required' => true, 'label' => __('user.role')]) !!}
            </div>
            <div class="card-footer">
                {{ Form::submit(__('user.create'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('users.index', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
