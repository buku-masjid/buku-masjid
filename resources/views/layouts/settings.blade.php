@extends('layouts.app')

@section('content')
<!-- Nav tabs -->
<ul class="nav nav-tabs">
    <li class="nav-item">
        {!! link_to_route('profile.show', __('user.profile'), [], ['class' => 'nav-link'.(Request::segment(1) == 'profile' ? ' active' : '')]) !!}
    </li>
    <li class="nav-item">
        {!! link_to_route('masjid_profile.show', __('masjid_profile.masjid_profile'), [], ['class' => 'nav-link'.(Request::segment(1) == 'masjid_profile' ? ' active' : '')]) !!}
    </li>
    <li class="nav-item">
        {!! link_to_route('password.change', __('auth.change_password'), [], ['class' => 'nav-link'.(Request::segment(1) == 'change-password' ? ' active' : '')]) !!}
    </li>
    @if (Route::has('lecturings.index'))
        @can('view-any', new App\Models\Lecturing)
            <li class="nav-item">
                {!! link_to_route('lecturings.index', __('lecturing.lecturing'), [], ['class' => 'nav-link'.(in_array(Request::segment(1), ['lecturings', 'friday_lecturings']) ? ' active' : '')]) !!}
            </li>
        @endcan
    @endif
    @can('view-any', new App\Models\BankAccount)
        <li class="nav-item">
            {!! link_to_route('bank_accounts.index', __('bank_account.bank_account'), [], ['class' => 'nav-link'.(Request::segment(1) == 'bank_accounts' ? ' active' : '')]) !!}
        </li>
    @endcan
    @can('view-any', new App\Models\Category)
        <li class="nav-item">
            {!! link_to_route('categories.index', __('category.category'), [], ['class' => 'nav-link'.(Request::segment(1) == 'categories' ? ' active' : '')]) !!}
        </li>
    @endcan
    @can('view-any', new App\Models\Book)
        <li class="nav-item">
            {!! link_to_route('books.index', __('book.book'), [], ['class' => 'nav-link'.(Request::segment(1) == 'books' ? ' active' : '')]) !!}
        </li>
    @endcan
    @can('view-any', new App\User)
        <li class="nav-item">
            {!! link_to_route('users.index', __('user.user'), [], ['class' => 'nav-link'.(Request::segment(1) == 'users' ? ' active' : '')]) !!}
        </li>
    @endcan
    @can('manage_database_backup')
        <li class="nav-item">
            {!! link_to_route('database_backups.index', __('database_backup.list'), [], ['class' => 'nav-link'.(Request::segment(1) == 'database_backups' ? ' active' : '')]) !!}
        </li>
    @endcan
    <li class="nav-item">
        {!! link_to_route('system_info.index', __('settings.system_info'), [], ['class' => 'nav-link'.(Request::segment(1) == 'system_info' ? ' active' : '')]) !!}
    </li>
</ul>

@yield('content_settings')
@endsection
