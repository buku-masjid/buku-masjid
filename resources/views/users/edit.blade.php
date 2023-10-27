@extends('layouts.settings')

@section('title', __('user.edit'))

@section('content_settings')
<div class="row justify-content-center">
    <div class="col-md-6">
        @if (request('action') == 'delete' && $user)
        @can('delete', $user)
            <div class="page-header">
                <h1 class="page-title">{{ __('user.delete') }}</h1>
            </div>
            <div class="card">
                <div class="card-header">{{ __('user.delete') }}</div>
                <div class="card-body">
                    <label class="control-label text-primary">{{ __('user.name') }}</label>
                    <p>{{ $user->name }}</p>
                    <label class="control-label text-primary">{{ __('user.email') }}</label>
                    <p>{{ $user->email }}</p>
                    <label class="control-label text-primary">{{ __('user.role') }}</label>
                    <p>{{ $user->role }}</p>
                    {!! $errors->first('user_id', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="card-body text-danger">{{ __('user.delete_confirm') }}</div>
                <div class="card-footer">
                    <form method="POST" action="{{ route('users.destroy', $user) }}" accept-charset="UTF-8" onsubmit="return confirm(&quot;{{ __('app.delete_confirm') }}&quot;)" class="del-form float-right" style="display: inline;">
                        {{ csrf_field() }} {{ method_field('delete') }}
                        <input name="user_id" type="hidden" value="{{ $user->id }}">
                        <button type="submit" class="btn btn-danger">{{ __('app.delete_confirm_button') }}</button>
                    </form>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-link">{{ __('app.cancel') }}</a>
                </div>
            </div>
        @endcan
        @else
        <div class="page-header">
            <h1 class="page-title">{{ __('user.edit') }}</h1>
        </div>
        <div class="card">
            {{ Form::model($user, ['route' => ['users.update', $user], 'method' => 'patch']) }}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">{!! FormField::text('name', ['required' => true, 'label' => __('user.name')]) !!}</div>
                </div>
                <div class="row">
                    <div class="col-md-12">{!! FormField::email('email', ['required' => true, 'label' => __('user.email')]) !!}</div>
                </div>
                <div class="row">
                    <div class="col-md-6">{!! FormField::radios('role_id', $roles, ['value' => $user->role_id, 'required' => true, 'label' => __('user.role'), 'list_style' => 'unstyled']) !!}</div>
                    <div class="col-md-6">{!! FormField::radios('is_active', $statuses, ['value' => $user->is_active, 'required' => true, 'label' => __('user.status')]) !!}</div>
                </div>
                <div class="row">
                    <div class="col-md-12">{!! FormField::password('password', ['info' => ['text' => __('user.password_form_note')]]) !!}</div>
                </div>
            </div>
            <div class="card-footer">
                {{ Form::submit(__('user.update'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('users.show', __('app.cancel'), [$user], ['class' => 'btn btn-link']) }}
                @can('delete', $user)
                    {{ link_to_route('users.edit', __('app.delete'), [$user, 'action' => 'delete'], ['class' => 'btn btn-danger float-right', 'id' => 'del-user-'.$user->id]) }}
                @endcan
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endif
@endsection
