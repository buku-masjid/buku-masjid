@extends('layouts.guest')

@section('title', __('Login'))

@section('content')
<section class="bg-white">
    <div class="container-md py-5 px-4">
        <div class="row align-items-center justify-content-center">
            <div class="me-lg-3 d-sm-none d-flex justify-content-center">
              @if (Setting::get('masjid_logo_path'))
                  <div class="mb-3"><img src="{{ Storage::url(Setting::get('masjid_logo_path'))}}" style="width: 100px"></div>
              @endif
            </div>
            <div class="col-lg-3 me-lg-3 d-none d-sm-inline">
              @if (Setting::get('masjid_logo_path'))
                  <div class="mb-3"><img src="{{ Storage::url(Setting::get('masjid_logo_path'))}}" style="width: 150px"></div>
              @endif
              <div>
                  <span class="fs-2">Assalamu'alaikum</span><br>
                  <a class="fs-1 fw-bold lh-sm text-dark" href="{{ url('/') }}">Masjid {{ Setting::get('masjid_name', config('masjid.name')) }}</a>
              </div>
              @if (Setting::get('masjid_address'))
              <div class="mt-3 pe-5 fs-5 text-black-50">{!! nl2br(htmlentities(Setting::get('masjid_address'))) !!}</div>
              @endif
              <div class="mt-3 pt-3 border-top">
                <!-- Facebook -->
                <div class="mb-2"><i class="ti">&#xf7e6;</i> <span class="fs-4"> NurulHidayah</span></div>
                <!-- Instagram -->
                <div class="mb-2"><i class="ti">&#xec20;</i> <span class="fs-4"> NurulHidayah</span></div>
                <!-- Youtube -->
                <div><i class="ti">&#xfc22;</i> <span class="fs-4"> NurulHidayah</span></div>
                </div>
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0 card p-4 shadow-lg">
                <h2>{{ __('auth.login') }} {{ config('app.name') }}</h3>
                <div class="my-3">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email" class="form-label">{{ __('auth.email') }}</label>

                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group mt-2">
                            <label for="password" class="form-label">
                                @if (Route::has('password.request'))
                                    <a class="float-right small" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                                @endif
                                {{ __('auth.password') }}
                            </label>

                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group pt-2">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="custom-control-label" for="remember">
                                    {{ __('auth.remember_me') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary bm-btn bm-bg-primary btn-block">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
