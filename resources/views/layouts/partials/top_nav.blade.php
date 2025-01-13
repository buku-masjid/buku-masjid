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
            <i class="fe fe-home"></i> {{ __('app.public_home') }}
        </a>
        @auth
            @if (auth()->activeBook())
                @include ('layouts._top_nav_active_book')
            @endif
        @endauth

        <!-- Right Side Of Navbar -->
        <div class="nav navbar-nav ml-auto d-none d-sm-block">
            <a class="xs-navbar mr-4" href="{{ url('/') }}">
                <i class="fe fe-home h3 d-inline d-lg-none"></i>
                <span class="d-none d-lg-inline"><i class="fe fe-home"></i> {{ __('app.public_home') }}</span>
            </a>
            <!-- Authentication Links -->
            <a class="xs-navbar mr-4" href="{{ route('transactions.index') }}" title="{{ __('transaction.transaction') }}">
                <i class="fe fe-repeat h3 d-inline d-lg-none"></i>
                <span class="d-none d-lg-inline"><i class="fe fe-repeat"></i> {{ __('transaction.transaction') }}</span>
            </a>
            @if (Route::has('donors.index'))
                @can('view-any', new App\Models\Partner)
                    <a class="xs-navbar mr-4" href="{{ route('donors.index') }}" title="{{ __('partner.partner_type_donor') }}">
                        <i class="fe fe-pocket h3 d-inline d-lg-none"></i>
                        <span class="d-none d-lg-inline"><i class="fe fe-pocket"></i> {{ __('partner.partner_type_donor') }}</span>
                    </a>
                @endcan
            @endif
            @if (Route::has('partners.index'))
                @can('view-any', new App\Models\Partner)
                    <a class="xs-navbar mr-4" href="{{ route('partners.index') }}" title="{{ __('partner.partner') }}">
                        <i class="fe fe-users h3 d-inline d-lg-none"></i>
                        <span class="d-none d-lg-inline"><i class="fe fe-users"></i> {{ __('partner.partner') }}</span>
                    </a>
                @endcan
            @endif
            <a class="xs-navbar mr-4" href="{{ route('reports.index') }}" title="{{ __('report.report') }}">
                <i class="fe fe-bar-chart-2 h3 d-inline d-lg-none"></i>
                <span class="d-none d-lg-inline"><i class="fe fe-bar-chart-2"></i> {{ __('report.report') }}</span>
            </a>
            <a class="xs-navbar mr-4" href="{{ route('profile.show') }}" title="{{ __('settings.settings') }}">
                <i class="fe fe-settings h3 d-inline d-lg-none"></i>
                <span class="d-none d-lg-inline"><i class="fe fe-settings"></i> {{ __('settings.settings') }}</span>
            </a>
            <a class="xs-navbar mr-4" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                 <i class="fe fe-log-out h3 d-inline d-lg-none"></i>
                <i class="fe fe-log-out d-none d-lg-inline"></i>
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
        <a class="col px-1 border-right border-primary" href="{{ route('transactions.index') }}" title="{{ __('transaction.transaction') }}">
            <div><i class="fe fe-repeat h3"></i></div>
            {{ __('transaction.transaction') }}
        </a>
        @if (Route::has('donors.index'))
            @can('view-any', new App\Models\Partner)
                <a class="col px-1 border-right border-primary" href="{{ route('donors.index') }}" title="{{ __('donor.donor') }}">
                    <div><i class="fe fe-pocket h3"></i></div>
                    {{ __('donor.donor') }}
                </a>
            @endcan
        @endif
        @if (Route::has('partners.index'))
            @can('view-any', new App\Models\Partner)
                <a class="col px-1 border-right border-primary" href="{{ route('partners.index') }}" title="{{ __('partner.partner') }}">
                    <div><i class="fe fe-users h3"></i></div>
                    {{ __('partner.partner') }}
                </a>
            @endcan
        @endif
        <a class="col px-1 border-right border-primary" href="{{ route('reports.index') }}" title="{{ __('report.report') }}">
            <div><i class="fe fe-bar-chart-2 h3"></i></div>
            {{ __('report.report') }}
        </a>
        <a class="col px-1" href="{{ route('profile.show') }}" title="{{ __('settings.settings') }}">
            <div><i class="fe fe-settings h3"></i></div>
            {{ __('settings.settings') }}
        </a>
    </div>
</nav>
