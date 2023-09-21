@extends('layouts.settings')

@section('title', __('auth.profile').' - '.$user->name)

@section('content_settings')

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="page-header">
            <h1 class="page-title">@yield('title')</h1>
        </div>
        <div class="card">
            <table class="table table-sm card-table">
                <tbody>
                    <tr><td>{{ __('user.name') }}</td><td>{{ $user->name }}</td></tr>
                    <tr><td>{{ __('user.email') }}</td><td>{{ $user->email }}</td></tr>
                    <tr><td>{{ __('user.role') }}</td><td>{{ $user->role }}</td></tr>
                    <tr><td>{{ __('app.switch_lang') }}</td><td>@include ('layouts.partials.lang_switcher')</td></tr>
                </tbody>
            </table>
            <div class="card-footer">
                <a href="{{ route('profile.edit') }}" class="btn btn-success">{{ __('user.profile_edit') }}</a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="float-right">
                    <button type="submit" class="btn btn-danger"><i class="fe fe-log-out"></i> {{ __('auth.logout') }}</button>
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
