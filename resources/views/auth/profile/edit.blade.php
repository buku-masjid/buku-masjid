@extends('layouts.settings')

@section('title', __('user.profile_edit'))

@section('content_settings')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="page-header"><h1 class="page-title">@yield('title')</h1></div>
        <div class="card">
            {{ Form::model($user, ['route' => 'profile.update', 'method' => 'patch']) }}
                <div class="card-body">
                    {!! FormField::text('name', ['required' => true, 'label' => __('user.name')]) !!}
                    {!! FormField::email('email', ['required' => true, 'label' => __('user.email')]) !!}
                </div>
                <div class="card-footer">
                    {{ Form::submit(__('user.profile_update'), ['class' => 'btn btn-success']) }}
                    {{ link_to_route('profile.show', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
