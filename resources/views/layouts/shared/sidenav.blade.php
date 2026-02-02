<!-- Sidenav Menu Start -->
<div class="sidenav-menu">
    <div class="scrollbar" data-simplebar>

        <!-- User -->
        <div class="sidenav-user text-nowrap border border-dashed rounded-3">
            <a href="#!" class="sidenav-user-name d-flex align-items-center">
                <img
                    src="https://ui-avatars.com/api/?background=007fff&color=fff&name={{ urlencode(auth()->user()->name) }}"
                    class="rounded-circle me-2 d-flex" width="32" alt="avatar">
                <span>
                    <h5 class="my-0 fw-semibold">
                        {{ Auth::user()->name ?? 'Tamu' }}
                    </h5>
                    <h6 class="my-0 text-muted">
                        {{ Str::of(Auth::user()->getRoleNames()->first() ?? 'Tidak Ada Peran')->replace('-', ' ')->title() }}
                    </h6>
                </span>
            </a>
        </div>

        <!-- Sidenav Menu -->
        <ul class="side-nav">

            <li class="side-nav-item">
                <a href="{{route('dashboard')}}" class="side-nav-link">
                    <span class="menu-icon"><i data-lucide="home"></i></span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            <!-- Produk & Katalog -->
            <li class="side-nav-title mt-2">Produk & Katalog</li>

            <li class="side-nav-item">
                <a href="{{route('products.index')}}" class="side-nav-link">
                    <span class="menu-icon"><i data-lucide="package"></i></span>
                    <span class="menu-text">Produk</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{route("categories.index")}}" class="side-nav-link">
                    <span class="menu-icon"><i data-lucide="tag"></i></span>
                    <span class="menu-text">Kategori</span>
                </a>
            </li>


            <li class="side-nav-item">
                <a href="{{ route('units.index') }}" class="side-nav-link">
                    <span class="menu-icon"><i data-lucide="ruler"></i></span>
                    <span class="menu-text">Satuan / Unit</span>
                </a>
            </li>

            @can('home_banners.view')
                <li class="side-nav-item">
                    <a href="{{ route('admin.home-banners.index') }}"
                       class="side-nav-link {{ request()->routeIs('admin.home-banners.*') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i data-lucide="image"></i>
                        </span>
                        <span class="menu-text">Home Banner</span>
                    </a>
                </li>
            @endcan

            <!-- Persediaan & Gudang -->
            <li class="side-nav-title mt-2">Persediaan & Gudang</li>

            <li class="side-nav-item">
                <a href="{{route('branches.index')}}" class="side-nav-link">
                    <span class="menu-icon"><i data-lucide="map-pin"></i></span>
                    <span class="menu-text">Cabang</span>
                </a>
            </li>

            {{--            <li class="side-nav-item">--}}
            {{--                <a href="" class="side-nav-link">--}}
            {{--                    <span class="menu-icon"><i data-lucide="box"></i></span>--}}
            {{--                    <span class="menu-text">Inventory</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            <li class="side-nav-item">
                <a href="{{route('stock_receipts.index')}}" class="side-nav-link">
                    <span class="menu-icon"><i data-lucide="inbox"></i></span>
                    <span class="menu-text">Penerimaan Stok</span>
                </a>
            </li>

            {{--            <li class="side-nav-item">--}}
            {{--                <a href="" class="side-nav-link">--}}
            {{--                    <span class="menu-icon"><i data-lucide="slack"></i></span>--}}
            {{--                    <span class="menu-text">Penyesuaian Stok</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            {{--            <li class="side-nav-item">--}}
            {{--                <a href="" class="side-nav-link">--}}
            {{--                    <span class="menu-icon"><i data-lucide="activity"></i></span>--}}
            {{--                    <span class="menu-text">Riwayat Stok</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            <!-- Pesanan & Pelanggan -->
            <li class="side-nav-title mt-2">Pesanan & Pelanggan</li>

            <li class="side-nav-item">
                <a href="{{route('admin.orders.index')}}" class="side-nav-link">
                    <span class="menu-icon"><i data-lucide="shopping-cart"></i></span>
                    <span class="menu-text">Pesanan</span>
                </a>
            </li>

            {{--            <li class="side-nav-item">--}}
            {{--                <a href="" class="side-nav-link">--}}
            {{--                    <span class="menu-icon"><i data-lucide="truck"></i></span>--}}
            {{--                    <span class="menu-text">Pengiriman</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            {{--            <li class="side-nav-item">--}}
            {{--                <a href="" class="side-nav-link">--}}
            {{--                    <span class="menu-icon"><i data-lucide="corner-up-left"></i></span>--}}
            {{--                    <span class="menu-text">Retur</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            <li class="side-nav-item">
                <a href="{{route('customers.index')}}" class="side-nav-link">
                    <span class="menu-icon"><i data-lucide="users"></i></span>
                    <span class="menu-text">Pelanggan</span>
                </a>
            </li>

            {{--            <li class="side-nav-item">--}}
            {{--                <a href="" class="side-nav-link">--}}
            {{--                    <span class="menu-icon"><i data-lucide="shopping-bag"></i></span>--}}
            {{--                    <span class="menu-text">Keranjang</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            <!-- Keuangan & Pembayaran -->
            {{--            <li class="side-nav-title mt-2">Keuangan & Pembayaran</li>--}}

            {{--            <li class="side-nav-item">--}}
            {{--                <a href="" class="side-nav-link">--}}
            {{--                    <span class="menu-icon"><i data-lucide="credit-card"></i></span>--}}
            {{--                    <span class="menu-text">Pembayaran</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            {{--            <li class="side-nav-item">--}}
            {{--                <a href="" class="side-nav-link">--}}
            {{--                    <span class="menu-icon"><i data-lucide="file-text"></i></span>--}}
            {{--                    <span class="menu-text">Invoice / Faktur</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            {{--            <li class="side-nav-item">--}}
            {{--                <a href="" class="side-nav-link">--}}
            {{--                    <span class="menu-icon"><i data-lucide="bank"></i></span>--}}
            {{--                    <span class="menu-text">Metode Pembayaran</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            <!-- Promosi & Kupon -->
            {{--            <li class="side-nav-title mt-2">Promosi & Kupon</li>--}}

            {{--            <li class="side-nav-item">--}}
            {{--                <a href="" class="side-nav-link">--}}
            {{--                    <span class="menu-icon"><i data-lucide="gift"></i></span>--}}
            {{--                    <span class="menu-text">Promosi</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            {{--            <li class="side-nav-item">--}}
            {{--                <a href="" class="side-nav-link">--}}
            {{--                    <span class="menu-icon"><i data-lucide="ticket"></i></span>--}}
            {{--                    <span class="menu-text">Kupon</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            <!-- Pengiriman & Fulfillment -->
            {{--            <li class="side-nav-title mt-2">Pengiriman & Fulfillment</li>--}}

            {{--            <li class="side-nav-item">--}}
            {{--                <a href="" class="side-nav-link">--}}
            {{--                    <span class="menu-icon"><i data-lucide="globe"></i></span>--}}
            {{--                    <span class="menu-text">Provider Pengiriman</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            <!-- Pengguna & Akses -->
            <li class="side-nav-title mt-2">Pengguna & Akses</li>

            <li class="side-nav-item">
                <a href="{{route('users.index')}}" class="side-nav-link">
                    <span class="menu-icon"><i data-lucide="user"></i></span>
                    <span class="menu-text">Admin / Pengguna</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{route('roles.index')}}" class="side-nav-link">
                    <span class="menu-icon"><i data-lucide="shield-check"></i></span>
                    <span class="menu-text">Role</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{route('permissions.index')}}" class="side-nav-link">
                    <span class="menu-icon"><i data-lucide="key"></i></span>
                    <span class="menu-text">Permission</span>
                </a>
            </li>

            <!-- Pengaturan / Settings -->
            {{--            <li class="side-nav-title mt-2">Pengaturan</li>--}}

            {{--            <li class="side-nav-item">--}}
            {{--                <a href="" class="side-nav-link">--}}
            {{--                    <span class="menu-icon"><i data-lucide="percent"></i></span>--}}
            {{--                    <span class="menu-text">Pengaturan Pajak</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            {{--            <li class="side-nav-item">--}}
            {{--                <a href="" class="side-nav-link">--}}
            {{--                    <span class="menu-icon"><i data-lucide="settings"></i></span>--}}
            {{--                    <span class="menu-text">Pengaturan Toko</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            <!-- Log, Integrasi & Audit -->
            {{--            <li class="side-nav-title mt-2">Log & Integrasi</li>--}}

            {{--            <li class="side-nav-item">--}}
            {{--                <a href="" class="side-nav-link">--}}
            {{--                    <span class="menu-icon"><i data-lucide="list"></i></span>--}}
            {{--                    <span class="menu-text">Audit Logs</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            {{--            <li class="side-nav-item">--}}
            {{--                <a href="" class="side-nav-link">--}}
            {{--                    <span class="menu-icon"><i data-lucide="zap"></i></span>--}}
            {{--                    <span class="menu-text">Webhook Logs</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            <!-- Optional: Reviews & Wishlist -->
            <li class="side-nav-title mt-2">Umpan Balik</li>

            <li class="side-nav-item">
                <a href="{{route('admin.reviews.index')}}" class="side-nav-link">
                    <span class="menu-icon"><i data-lucide="star"></i></span>
                    <span class="menu-text">Ulasan</span>
                </a>
            </li>

{{--            <li class="side-nav-item">--}}
{{--                <a href="" class="side-nav-link">--}}
{{--                    <span class="menu-icon"><i data-lucide="heart"></i></span>--}}
{{--                    <span class="menu-text">Wishlist</span>--}}
{{--                </a>--}}
{{--            </li>--}}

        </ul>
    </div>
</div>
<!-- Sidenav Menu End -->
