@extends('layouts.settings')

@section('title', __('user.profile'))

@section('content_settings')


<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="page-header">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">{{ __('user.profile') }}</h2>
                </div>
            </div>
        </div>
        <div class="page-body">
        <div class="card">
            <table class="table card-table table-sm">
                <tbody>
                    <tr><td>{{ __('user.name') }}</td><td>{{ $user->name }}</td></tr>
                    <tr><td>{{ __('user.email') }}</td><td>{{ $user->email }}</td></tr>
                    <tr><td>{{ __('user.role') }}</td><td>{{ $user->role }}</td></tr>
                    <tr><td>{{ __('user.status') }}</td><td>{{ $user->status }}</td></tr>
                </tbody>
            </table>
            <div class="card-footer">
                @can('update', $user)
                    {{ link_to_route('users.edit', __('user.edit'), [$user], ['id' => 'edit-user-' . $user->id, 'class' => 'btn btn-warning']) }}
                @endcan
                 {{ link_to_route('users.index', __('user.back_to_index'), [], ['class' => 'btn btn-link']) }}
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
