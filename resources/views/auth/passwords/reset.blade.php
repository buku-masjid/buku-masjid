@extends('layouts.guest')

@section('title', __('auth.reset_password'))

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header"><h3 class="card-title">{{ __('auth.reset_password') }}</h3></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.request') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3{{ $errors->has('email') ? ' is-invalid' : '' }}">
                            <label for="email" class="form-label">{{ __('auth.email') }}</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="mb-3{{ $errors->has('password') ? ' is-invalid' : '' }}">
                            <label for="password" class="form-label">{{ __('auth.new_password') }}</label>
                            <input id="password" type="password" class="form-control" name="password" required>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="mb-3{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}">
                            <label for="password-confirm" class="form-label">{{ __('auth.new_password_confirmation') }}</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            @if ($errors->has('password_confirmation'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                {{ __('auth.reset_password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
