<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'home.index' ? 'active' : 'collapsed' }}"
                href="{{ route('home.index') }}">
                <i class="bi bi-grid"></i>
                <span>Beranda</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-heading">Data</li>
        <li class="nav-item">
            <a class="nav-link {{ in_array(Route::currentRouteName(), ['menus.index', 'menus.create', 'menus.edit']) ? 'active' : 'collapsed' }}"
                href="{{ route('menus.index') }}">
                <i class="bi bi-journal-text"></i>
                <span>Menu</span>
            </a>
        </li><!-- End Menu Nav -->

        <li class="nav-item">
            <a class="nav-link {{ in_array(Route::currentRouteName(), ['transactions.index', 'transactions.create', 'transactions.edit']) ? 'active' : 'collapsed' }}"
                href="{{ route('transactions.index') }}">
                <i class="ri-receipt-line"></i>
                <span>Transaksi</span>
            </a>
        </li><!-- End Transaction Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'users.index' ? 'active' : 'collapsed' }}"
                href="{{ url('/users') }}">
                <i class="ri-group-line"></i>
                <span>Pengguna</span>
            </a>
        </li><!-- End User Nav -->

        <li class="nav-heading">Generate</li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('apriori') ? 'active' : 'collapsed' }}"
                href="{{ route('apriori.index') }}">
                <i class="ri-ai-generate"></i>
                <span>Paket Menu</span>
            </a>
        </li><!-- End Generate Package Menu Nav -->

        <li class="nav-heading">Layanan</li>
        <li class="nav-item">
            <a class="nav-link {{ in_array(Route::currentRouteName(), ['menu-services.index', 'validate.menu', 'confirm.order']) ? 'active' : 'collapsed' }}"
                href="{{ route('menu-services.index') }}">
                <i class="ri-survey-line"></i>
                <span>Pilih Menu</span>
            </a>
        </li><!-- End Menu Services Nav -->
    </ul>
</aside>
