<nav class="navbar navbar-expand-sm navbar-light bg-white shadow-sm">
    <div class="container">
        <div class="navbar-header">
            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ route('home') }}">
                @guest
                    {{ config('app.name', 'Laravel') }}
                @else
                    {{ auth()->user()->name }}
                @endguest
            </a>
        </div>
        <a class="d-block d-sm-none {{ in_array(Request::segment(1), [null]) ? 'text-primary strong' : 'text-dark' }}" href="{{ url('/') }}">
            <i class="ti ti-home"></i> {{ __('app.public_home') }}
        </a>
        @auth
            @if (auth()->activeBook())
                @include ('layouts._top_nav_active_book')
            @endif
        @endauth

        <!-- Right Side Of Navbar -->
        <div class="nav navbar-nav ms-auto d-none d-sm-block">
            <a class="xs-navbar me-4" href="{{ url('/') }}">
                <i class="ti ti-home h3 d-inline d-lg-none"></i>
                <span class="d-none d-lg-inline"><i class="ti ti-home"></i> {{ __('app.public_home') }}</span>
            </a>
            <!-- Authentication Links -->
            <a class="xs-navbar me-4" href="{{ route('transactions.index') }}" title="{{ __('transaction.transaction') }}">
                <i class="ti ti-repeat h3 d-inline d-lg-none"></i>
                <span class="d-none d-lg-inline"><i class="ti ti-repeat"></i> {{ __('transaction.transaction') }}</span>
            </a>
            @if (Route::has('donors.index'))
                @can('view-any', new App\Models\Partner)
                    <a class="xs-navbar me-4" href="{{ route('donors.index') }}" title="{{ __('partner.partner_type_donor') }}">
                        <i class="ti ti-pocket h3 d-inline d-lg-none"></i>
                        <span class="d-none d-lg-inline"><i class="ti ti-pocket"></i> {{ __('partner.partner_type_donor') }}</span>
                    </a>
                @endcan
            @endif
            @if (Route::has('partners.index'))
                @can('view-any', new App\Models\Partner)
                    <a class="xs-navbar me-4" href="{{ route('partners.index') }}" title="{{ __('partner.partner') }}">
                        <i class="ti ti-users h3 d-inline d-lg-none"></i>
                        <span class="d-none d-lg-inline"><i class="ti ti-users"></i> {{ __('partner.partner') }}</span>
                    </a>
                @endcan
            @endif
            <a class="xs-navbar me-4" href="{{ route('reports.index') }}" title="{{ __('report.report') }}">
                <i class="ti ti-bar-chart-2 h3 d-inline d-lg-none"></i>
                <span class="d-none d-lg-inline"><i class="ti ti-bar-chart-2"></i> {{ __('report.report') }}</span>
            </a>
            <a class="xs-navbar me-4" href="{{ route('profile.show') }}" title="{{ __('settings.settings') }}">
                <i class="ti ti-settings h3 d-inline d-lg-none"></i>
                <span class="d-none d-lg-inline"><i class="ti ti-settings"></i> {{ __('settings.settings') }}</span>
            </a>
            <a class="xs-navbar me-4" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                 <i class="ti ti-log-out h3 d-inline d-lg-none"></i>
                <i class="ti ti-log-out d-none d-lg-inline"></i>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                <input type="submit" value="{{ __('auth.logout') }}" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>
</nav>

<!-- Mobile Navigation -->
<nav class="navbar fixed-bottom navbar-light bg-white d-block d-sm-none border-top">
    <div class="row text-center small justify-content-center">
        <a class="col px-1 border-end border-primary" href="{{ route('transactions.index') }}" title="{{ __('transaction.transaction') }}">
            <div><i class="ti ti-repeat h3"></i></div>
            {{ __('transaction.transaction') }}
        </a>
        @if (Route::has('donors.index'))
            @can('view-any', new App\Models\Partner)
                <a class="col px-1 border-end border-primary" href="{{ route('donors.index') }}" title="{{ __('donor.donor') }}">
                    <div><i class="ti ti-pocket h3"></i></div>
                    {{ __('donor.donor') }}
                </a>
            @endcan
        @endif
        @if (Route::has('partners.index'))
            @can('view-any', new App\Models\Partner)
                <a class="col px-1 border-end border-primary" href="{{ route('partners.index') }}" title="{{ __('partner.partner') }}">
                    <div><i class="ti ti-users h3"></i></div>
                    {{ __('partner.partner') }}
                </a>
            @endcan
        @endif
        <a class="col px-1 border-end border-primary" href="{{ route('reports.index') }}" title="{{ __('report.report') }}">
            <div><i class="ti ti-bar-chart-2 h3"></i></div>
            {{ __('report.report') }}
        </a>
        <a class="col px-1" href="{{ route('profile.show') }}" title="{{ __('settings.settings') }}">
            <div><i class="ti ti-settings h3"></i></div>
            {{ __('settings.settings') }}
        </a>
    </div>
</nav>
