@extends('layouts.guest')

@section('title', __('auth.register'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col col-login mx-auto">
            <div class="card">
                <div class="card-header"><h3 class="card-title">{{ __('auth.register') }}</h3></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label">{{ __('app.name') }}</label>
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                            @if ($errors->has('name'))
                                <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label">{{ __('auth.email') }}</label>

                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                            @if ($errors->has('email'))
                                <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label">{{ __('auth.new_password') }}</label>

                            <input id="password" type="password" class="form-control" name="password" required>
                            @if ($errors->has('password'))
                                <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="control-label">{{ __('auth.new_password_confirmation') }}</label>

                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">{{ __('auth.register') }}</button>
                            {{ link_to_route('login', __('auth.have_account'), [], ['class' => 'btn btn-link btn-block']) }}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
