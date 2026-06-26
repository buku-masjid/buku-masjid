<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xxl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand" href="{{ route('home') }}">
            @guest
                {{ config('app.name', 'Laravel') }}
            @else
                {{ auth()->user()->name }}
            @endguest
        </a>

        @auth
        @if (auth()->activeBook())
            @include ('layouts._top_nav_active_book')
        @endif
        <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item d-flex">
                <a id="userNavbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    <span class="avatar avatar-sm ms-2 ms-lg-0"><i class="ti ti-user"></i></span>
                    <span class="d-none d-xl-block ps-2">
                        {{ Auth::user()->name }}
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userNavbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        @endauth

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}" title="{{ __('app.public_home') }}">
                            <i class="ti ti-home"></i> &nbsp;{{ __('app.public_home') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('transactions.index') }}"><i class="ti ti-repeat"></i>&nbsp;{{ __('transaction.transaction') }}</a>
                    </li>
                    @if (Route::has('donors.index'))
                        @can('view-any', new App\Models\Partner)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('donors.index') }}"><i class="ti ti-brand-pocket"></i>&nbsp;{{ __('partner.partner_type_donor') }}</a>
                            </li>
                        @endcan
                    @endif
                    @if (Route::has('partners.index'))
                        @can('view-any', new App\Models\Partner)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('partners.index') }}"><i class="ti ti-users"></i>&nbsp;{{ __('partner.partner') }}</a>
                            </li>
                        @endcan
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reports.index') }}"><i class="ti ti-chart-bar"></i>&nbsp;{{ __('report.report') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.show') }}"><i class="ti ti-settings"></i>&nbsp;{{ __('settings.settings') }}</a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</header>
