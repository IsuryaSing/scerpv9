<aside class="erp-sidebar" id="erp-sidebar">
    <div class="erp-sidebar-inner">
        <ul class="erp-menu-list erp-menu-root">
            <li class="erp-menu-item">
                <a href="{{ route('selectmodule') }}" class="erp-menu-link {{ request()->routeIs('selectmodule') ? 'is-current' : '' }}">
                    <i class="fa fa-home"></i>
                    <span>Terms</span>
                </a>
            </li>
            <li class="erp-menu-item">
                <a href="{{ route('dashboard') }}" class="erp-menu-link {{ request()->routeIs('dashboard') ? 'is-current active' : '' }}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        @if (!empty($sidebarMenu))
            <div class="erp-sidebar-module">
                @include('partials.erp-sidebar-node', ['items' => $sidebarMenu, 'level' => 0])
            </div>
        @endif
    </div>
</aside>
