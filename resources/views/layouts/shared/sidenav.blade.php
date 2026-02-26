<!-- Sidenav Menu Start -->
<div class="sidenav-menu">
    <div class="scrollbar" data-simplebar>

        <!-- User -->
        <div class="sidenav-user text-nowrap border border-dashed rounded-3">
            <a href="#!" class="sidenav-user-name d-flex align-items-center">
                <img src="https://ui-avatars.com/api/?background=007fff&color=fff&name={{ urlencode(auth()->user()->name) }}"
                    class="rounded-circle me-2 d-flex" width="32" alt="avatar">
                <span>
                    <h5 class="my-0 fw-semibold">{{ auth()->user()->name }}</h5>
                    <h6 class="my-0 text-muted">
                        {{ Str::of(auth()->user()->getRoleNames()->first() ?? 'Tidak Ada Role')->replace('-', ' ')->title() }}
                    </h6>
                </span>
            </a>
        </div>

        <ul class="side-nav">

            {{-- DASHBOARD --}}
            <li class="side-nav-item">
                <a href="{{ route('dashboard') }}" class="side-nav-link">
                    <span class="menu-icon"><i data-lucide="home"></i></span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            {{-- ================= MASTER DATA ================= --}}
            <li class="side-nav-title mt-2">Master Data</li>

            @can('products.view')
                <li class="side-nav-item">
                    <a href="{{ route('products.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="package"></i></span>
                        <span class="menu-text">Produk</span>
                    </a>
                </li>
            @endcan

            @can('categories.view')
                <li class="side-nav-item">
                    <a href="{{ route('categories.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="tag"></i></span>
                        <span class="menu-text">Kategori</span>
                    </a>
                </li>
            @endcan

            @can('units.view')
                <li class="side-nav-item">
                    <a href="{{ route('units.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="ruler"></i></span>
                        <span class="menu-text">Satuan</span>
                    </a>
                </li>
            @endcan

            @can('suppliers.view')
                <li class="side-nav-item">
                    <a href="{{ route('suppliers.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="truck"></i></span>
                        <span class="menu-text">Supplier</span>
                    </a>
                </li>
            @endcan

            @can('customers.view')
                <li class="side-nav-item">
                    <a href="{{ route('customers.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="users"></i></span>
                        <span class="menu-text">Pelanggan</span>
                    </a>
                </li>
            @endcan

            @can('payment_methods.view')
                <li class="side-nav-item">
                    <a href="{{ route('payment-methods.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="credit-card"></i></span>
                        <span class="menu-text">Metode Pembayaran</span>
                    </a>
                </li>
            @endcan

            {{-- ================= INVENTORY =================
            <li class="side-nav-title mt-2">Inventory</li>

            @can('stock.view')
                <li class="side-nav-item">
                    <a href="{{ route('stocks.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="boxes"></i></span>
                        <span class="menu-text">Stok Barang</span>
                    </a>
                </li>
            @endcan --}}

            {{--            @can('stock.adjust') --}}
            {{--                <li class="side-nav-item"> --}}
            {{--                    <a href="{{ route('stock-adjustments.index') }}" class="side-nav-link"> --}}
            {{--                        <span class="menu-icon"><i data-lucide="sliders"></i></span> --}}
            {{--                        <span class="menu-text">Penyesuaian Stok</span> --}}
            {{--                    </a> --}}
            {{--                </li> --}}
            {{--            @endcan --}}

            @can('stock.history')
                <li class="side-nav-item">
                    <a href="{{ route('stock-history.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="activity"></i></span>
                        <span class="menu-text">Riwayat Stok</span>
                    </a>
                </li>
            @endcan

            {{-- ================= PURCHASING ================= --}}
            <li class="side-nav-title mt-2">Pembelian</li>

            @can('purchases.view')
                <li class="side-nav-item">
                    <a href="{{ route('purchases.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="shopping-bag"></i></span>
                        <span class="menu-text">Pembelian</span>
                    </a>
                </li>
            @endcan

            @can('expenses.view')
                <li class="side-nav-item">
                    <a href="{{ route('expenses.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="receipt"></i></span>
                        <span class="menu-text">Biaya Pembelian</span>
                    </a>
                </li>
            @endcan


            {{--            @can('purchases.pay') --}}
            {{--                <li class="side-nav-item"> --}}
            {{--                    <a href="{{ route('supplier-payables.index') }}" class="side-nav-link"> --}}
            {{--                        <span class="menu-icon"><i data-lucide="file-text"></i></span> --}}
            {{--                        <span class="menu-text">Hutang Supplier</span> --}}
            {{--                    </a> --}}
            {{--                </li> --}}
            {{--            @endcan --}}

            {{-- ================= SALES / KASIR ================= --}}
            <li class="side-nav-title mt-2">Penjualan</li>

            @can('sales.view')
                <li class="side-nav-item">
                    <a href="{{ route('sales.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="shopping-cart"></i></span>
                        <span class="menu-text">Penjualan</span>
                    </a>
                </li>
            @endcan

            {{--            @can('sales.pay') --}}
            {{--                <li class="side-nav-item"> --}}
            {{--                    <a href="{{ route('customer-receivables.index') }}" class="side-nav-link"> --}}
            {{--                        <span class="menu-icon"><i data-lucide="credit-card"></i></span> --}}
            {{--                        <span class="menu-text">Piutang Customer</span> --}}
            {{--                    </a> --}}
            {{--                </li> --}}
            {{--            @endcan --}}

            {{--            @can('sales.return') --}}
            {{--                <li class="side-nav-item"> --}}
            {{--                    <a href="{{ route('sales-returns.index') }}" class="side-nav-link"> --}}
            {{--                        <span class="menu-icon"><i data-lucide="corner-up-left"></i></span> --}}
            {{--                        <span class="menu-text">Retur Penjualan</span> --}}
            {{--                    </a> --}}
            {{--                </li> --}}
            {{--            @endcan --}}

            <li class="side-nav-title mt-2">Akuntansi</li>

            @can('accounts.view')
                <li class="side-nav-item">
                    <a href="{{ route('accounts.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="book"></i></span>
                        <span class="menu-text">Master Akun</span>
                    </a>
                </li>
            @endcan

            @can('journals.view')
                <li class="side-nav-item">
                    <a href="{{ route('journals.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="edit-3"></i></span>
                        <span class="menu-text">Jurnal Umum</span>
                    </a>
                </li>
            @endcan

            @can('profit_loss.view')
                <li class="side-nav-item has-submenu">
                    <a href="javascript:void(0)" class="side-nav-link has-arrow">
                        <span class="menu-icon">
                            <i data-lucide="trending-up"></i>
                        </span>
                        <span class="menu-text">Laba Rugi</span>
                    </a>

                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('profit-loss.sales') }}">
                                Per Penjualan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profit-loss.customer') }}">
                                Per Customer
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan


            {{-- ================= REPORT ================= --}}
            <li class="side-nav-title mt-2">Laporan</li>

            @can('reports.view')
                <li class="side-nav-item">
                    <a href="{{ route('reports.supplier-payables.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="file-text"></i></span>
                        <span class="menu-text">Hutang Supplier</span>
                    </a>
                </li>
            @endcan

            @can('reports.view')
                <li class="side-nav-item">
                    <a href="{{ route('reports.customer-receivables.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="file-text"></i></span>
                        <span class="menu-text">Piutang Customer</span>
                    </a>
                </li>
            @endcan

            {{-- ================= SYSTEM ================= --}}
            <li class="side-nav-title mt-2">Sistem</li>

            @can('users.view')
                <li class="side-nav-item">
                    <a href="{{ route('users.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="user"></i></span>
                        <span class="menu-text">User</span>
                    </a>
                </li>
            @endcan

            @can('roles.view')
                <li class="side-nav-item">
                    <a href="{{ route('roles.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="shield"></i></span>
                        <span class="menu-text">Role</span>
                    </a>
                </li>
            @endcan

            {{--            @can('permissions.view') --}}
            {{--                <li class="side-nav-item"> --}}
            {{--                    <a href="{{ route('permissions.index') }}" class="side-nav-link"> --}}
            {{--                        <span class="menu-icon"><i data-lucide="key"></i></span> --}}
            {{--                        <span class="menu-text">Permission</span> --}}
            {{--                    </a> --}}
            {{--                </li> --}}
            {{--            @endcan --}}

            {{--            @can('settings.view') --}}
            {{--                <li class="side-nav-item"> --}}
            {{--                    <a href="{{ route('settings.index') }}" class="side-nav-link"> --}}
            {{--                        <span class="menu-icon"><i data-lucide="settings"></i></span> --}}
            {{--                        <span class="menu-text">Pengaturan</span> --}}
            {{--                    </a> --}}
            {{--                </li> --}}
            {{--            @endcan --}}

            @can('audit.view')
                <li class="side-nav-item">
                    <a href="{{ route('audit-logs.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="list"></i></span>
                        <span class="menu-text">Audit Log</span>
                    </a>
                </li>
            @endcan

        </ul>

    </div>
</div>
<!-- Sidenav Menu End -->
