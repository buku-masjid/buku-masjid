<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xxl">
        {{--
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        --}}

        <a class="navbar-brand" href="{{ route('home') }}">
            @guest
                {{ config('app.name', 'Laravel') }}
            @else
                {{ auth()->user()->name }}
            @endguest
        </a>

        <a class="ms-auto text-decoration-none d-block d-sm-none {{ in_array(Request::segment(1), [null]) ? 'text-primary strong' : 'text-dark' }}" href="{{ url('/') }}">
            <i class="ti ti-home"></i> {{ __('app.public_home') }}
        </a>
        @auth
        @if (auth()->activeBook())
            @include ('layouts._top_nav_active_book')
        @endif
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
                            <i class="ti ti-home fs-1 d-inline d-lg-none"></i>
                            <span class="d-none d-lg-inline"><i class="ti ti-home"></i>  &nbsp;{{ __('app.public_home') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('transactions.index') }}" title="{{ __('transaction.transaction') }}">
                            <i class="ti ti-repeat fs-1 d-inline d-lg-none"></i>
                            <span class="d-none d-lg-inline"><i class="ti ti-repeat"></i> {{ __('transaction.transaction') }}</span>
                        </a>
                    </li>
                    @if (Route::has('donors.index'))
                        @can('view-any', new App\Models\Partner)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('donors.index') }}" title="{{ __('partner.partner_type_donor') }}">
                                    <i class="ti ti-brand-pocket fs-1 d-inline d-lg-none"></i>
                                    <span class="d-none d-lg-inline"><i class="ti ti-brand-pocket"></i> {{ __('partner.partner_type_donor') }}</span>
                                </a>
                            </li>
                        @endcan
                    @endif
                    @if (Route::has('partners.index'))
                        @can('view-any', new App\Models\Partner)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('partners.index') }}" title="{{ __('partner.partner') }}">
                                    <i class="ti ti-users fs-1 d-inline d-lg-none"></i>
                                    <span class="d-none d-lg-inline"><i class="ti ti-users"></i> {{ __('partner.partner') }}</span>
                                </a>
                            </li>
                        @endcan
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reports.index') }}" title="{{ __('report.report') }}">
                            <i class="ti ti-chart-bar fs-1 d-inline d-lg-none"></i>
                            <span class="d-none d-lg-inline"><i class="ti ti-chart-bar"></i>&nbsp;{{ __('report.report') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.show') }}" title="{{ __('settings.settings') }}">
                            <i class="ti ti-settings fs-1 d-inline d-lg-none"></i>
                            <span class="d-none d-lg-inline"><i class="ti ti-settings"></i>&nbsp;{{ __('settings.settings') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}" title="{{ __('auth.logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            <i class="ti ti-logout fs-1 d-inline d-lg-none"></i>
                            <i class="ti ti-logout d-none d-lg-inline"></i>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            <input type="submit" value="{{ __('auth.logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</header>

{{-- Mobile Navigation --}}
<header class="navbar navbar-expand-md fixed-bottom d-print-none d-block d-sm-none border-top">
    <div class="row text-center small justify-content-center">
        <a class="col px-1 border-end border-primary" href="{{ route('transactions.index') }}" title="{{ __('transaction.transaction') }}">
            <div><i class="ti ti-repeat fs-1"></i></div>
            {{ __('transaction.transaction') }}
        </a>
        @if (Route::has('donors.index'))
            @can('view-any', new App\Models\Partner)
                <a class="col px-1 border-end border-primary" href="{{ route('donors.index') }}" title="{{ __('donor.donor') }}">
                    <div><i class="ti ti-brand-pocket fs-1"></i></div>
                    {{ __('donor.donor') }}
                </a>
            @endcan
        @endif
        @if (Route::has('partners.index'))
            @can('view-any', new App\Models\Partner)
                <a class="col px-1 border-end border-primary" href="{{ route('partners.index') }}" title="{{ __('partner.partner') }}">
                    <div><i class="ti ti-users fs-1"></i></div>
                    {{ __('partner.partner') }}
                </a>
            @endcan
        @endif
        <a class="col px-1 border-end border-primary" href="{{ route('reports.index') }}" title="{{ __('report.report') }}">
            <div><i class="ti ti-chart-bar fs-1"></i></div>
            {{ __('report.report') }}
        </a>
        <a class="col px-1" href="{{ route('profile.show') }}" title="{{ __('settings.settings') }}">
            <div><i class="ti ti-settings fs-1"></i></div>
            {{ __('settings.settings') }}
        </a>
    </div>
</header>
