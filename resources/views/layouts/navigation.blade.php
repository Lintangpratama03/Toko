<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
            <a href="{{ route('admin.profile.show') }}"
                class="d-block">{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @if (auth()->user()->is_admin)
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            {{ __('Dashboard') }}
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            {{ __('Users') }}
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.slides.index') }}" class="nav-link">
                        <i class="nav-icon fa fa-image"></i>
                        <p>
                            {{ __('Slide') }}
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-circle nav-icon"></i>
                        <p>
                            Kelola Produk
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('admin.categories.index') }}" class="nav-link">
                                <i class="fa fa-plus nav-icon"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.weight_units.index') }}" class="nav-link">
                                <i class="fa fa-balance-scale nav-icon"></i>
                                <p>Satuan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.products.index') }}" class="nav-link">
                                <i class="fa fa-plus nav-icon"></i>
                                <p>Produk</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-circle nav-icon"></i>
                        <p>
                            Kelola Pesanan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('admin.orders.index') }}" class="nav-link">
                                <i class="fa fa-plus nav-icon"></i>
                                <p>Pesanan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.shipments.index') }}" class="nav-link">
                                <i class="fa fa-plus nav-icon"></i>
                                <p>Pengiriman</p>
                            </a>
                        </li>
                    </ul>
                </li>
            @elseif(auth()->user()->is_warehouse)
                <!-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-circle nav-icon"></i>
                        <p>
                            Kelola Produk
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('gudang.products.index') }}" class="nav-link">
                                <i class="fa fa-plus nav-icon"></i>
                                <p>Produk</p>
                            </a>
                        </li>
                    </ul>
                </li> -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-circle nav-icon"></i>
                        <p>
                            Kelola Laporan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('gudang.reports.revenue') }}" class="nav-link">
                                <i class="fa fa-plus nav-icon"></i>
                                <p>Keuntungan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('gudang.reports.product') }}" class="nav-link">
                                <i class="fa fa-plus nav-icon"></i>
                                <p>Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('gudang.reports.inventory') }}" class="nav-link">
                                <i class="fa fa-plus nav-icon"></i>
                                <p>Inventori</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('gudang.reports.payment') }}" class="nav-link">
                                <i class="fa fa-plus nav-icon"></i>
                                <p>Payment</p>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
