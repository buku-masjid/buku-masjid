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
    <li class="nav-item">
        {!! link_to_route('categories.index', __('category.category'), [], ['class' => 'nav-link'.(Request::segment(1) == 'categories' ? ' active' : '')]) !!}
    </li>
    <li class="nav-item">
        {!! link_to_route('books.index', __('book.book'), [], ['class' => 'nav-link'.(Request::segment(1) == 'books' ? ' active' : '')]) !!}
    </li>
</ul>

@yield('content_settings')
@endsection
