@extends('layouts.guest')

@section('title', __('auth.reset_password'))

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header"><div class="card-title">{{ __('auth.reset_password') }}</div></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        @csrf
                        {!! FormField::email('email', ['label' => __('auth.email')]) !!}

                        <div class="form-group">
                            {{ Form::submit(__('auth.send_reset_password_link'), ['class' => 'btn btn-primary']) }}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
