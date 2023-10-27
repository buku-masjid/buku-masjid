@extends('layouts.settings')

@section('title', __('user.list'))

@section('content_settings')

<div class="page-header">
    <h1 class="page-title">{{ __('user.list') }}</h1>
    <div class="page-subtitle">{{ __('app.total') }} : {{ $users->count() }} {{ __('user.user') }}</div>
    <div class="page-options d-flex">
        @can('create', new App\User)
            {{ link_to_route('users.create', __('user.create'), [], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
                {!! FormField::text('q', ['label' => __('user.search'), 'placeholder' => __('user.search_text'), 'class' => 'mx-sm-2', 'style' => 'width: 250px', 'value' => request('q')]) !!}
                <div class="form-group">
                    {{ Form::submit(__('user.search'), ['class' => 'btn btn-secondary']) }}
                    {{ link_to_route('users.index', __('app.reset'), [], ['class' => 'btn btn-link']) }}
                </div>
                {{ Form::close() }}
            </div>
            <table class="table table-sm table-responsive-sm table-hover">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th class="text-nowrap">{{ __('user.name') }}</th>
                        <th>{{ __('user.email') }}</th>
                        <th class="text-center">{{ __('user.role') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                    <tr>
                        <td class="text-center">{{ $users->firstItem() + $key }}</td>
                        <td>{!! $user->name !!}</td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">{{ $user->role }}</td>
                        <td class="text-center text-nowrap">
                            @can('view', $user)
                                {{ link_to_route('users.show', __('app.show'), $user) }}
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-body">{{ $users->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
</div>
@endsection
