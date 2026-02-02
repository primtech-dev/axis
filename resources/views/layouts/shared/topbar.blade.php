<!-- Topbar Start -->
<header class="app-topbar">
    <div class="container-fluid topbar-menu">
        <div class="d-flex align-items-center justify-content-center gap-2">
            <!-- Topbar Brand Logo -->
            <div class="logo-topbar ms-3">
                <a href="/" class="logo-dark">
                    <span class="d-flex align-items-center gap-1">
                        <img src="/images/logo.png" height="35" alt="Logo">
                    </span>
                </a>
                <a href="/" class="logo-light">
                    <span class="d-flex align-items-center gap-1">
                        <img src="/images/logo.png" height="35" alt="Logo">
                    </span>
                </a>
            </div>

            <div class="d-lg-none d-flex mx-1">
                <a href="/">
                    <img src="/images/logo.png" height="28" alt="Logo">
                </a>
            </div>

            <!-- Sidebar Hover Menu Toggle Button -->
            <button class="button-collapse-toggle d-xl-none">
                <i data-lucide="menu" class="fs-22 align-middle"></i>
            </button>
        </div> <!-- .d-flex-->

        <div class="d-flex align-items-center gap-2">
            <div class="topbar-item">
                <div class="dropdown" data-dropdown="custom">
                    <button class="topbar-link  fw-semibold" data-bs-toggle="dropdown" data-bs-offset="0,19"
                            type="button" aria-haspopup="false" aria-expanded="false">
                        <img data-trigger-img src="/images/themes/corporate.svg" alt="user-image"
                             class="w-100 rounded me-2"
                             height="18">
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-1">
                        <div class="h-100" style="max-height: 250px;" data-simplebar>
                            <div class="row g-0">
                                <div class="col-md-6">
                                    <button class="dropdown-item position-relative drop-custom-active"
                                            data-skin="shadcn">
                                        <img src="/images/themes/shadcn.svg" alt="" class="me-1 rounded"
                                             height="18">
                                        <span class="align-middle">Shadcn</span>
                                    </button>

                                    <button class="dropdown-item position-relative" data-skin="corporate">
                                        <img src="/images/themes/corporate.svg" alt="" class="me-1 rounded"
                                             height="18">
                                        <span class="align-middle">Corporate</span>
                                    </button>

                                    <button class="dropdown-item position-relative" data-skin="spotify">
                                        <img src="/images/themes/spotify.svg" alt="" class="me-1 rounded"
                                             height="18">
                                        <span class="align-middle">Spotify</span>
                                    </button>

                                    <button class="dropdown-item position-relative" data-skin="saas">
                                        <img src="/images/themes/saas.svg" alt="" class="me-1 rounded"
                                             height="18">
                                        <span class="align-middle">SaaS</span>
                                    </button>

                                    <button class="dropdown-item position-relative" data-skin="nature">
                                        <img src="/images/themes/nature.svg" alt="" class="me-1 rounded"
                                             height="18">
                                        <span class="align-middle">Nature</span>
                                    </button>

                                    <button class="dropdown-item position-relative" data-skin="vintage">
                                        <img src="/images/themes/vintage.svg" alt="" class="me-1 rounded"
                                             height="18">
                                        <span class="align-middle">Vintage</span>
                                    </button>

                                    <button class="dropdown-item position-relative" data-skin="leafline">
                                        <img src="/images/themes/leafline.svg" alt="" class="me-1 rounded"
                                             height="18">
                                        <span class="align-middle">Leafline</span>
                                    </button>
                                </div>

                                <div class="col-md-6">
                                    <button class="dropdown-item position-relative" data-skin="ghibli">
                                        <img src="/images/themes/ghibli.svg" alt="" class="me-1 rounded"
                                             height="18">
                                        <span class="align-middle">Ghibli</span>
                                    </button>

                                    <button class="dropdown-item position-relative" data-skin="slack">
                                        <img src="/images/themes/slack.svg" alt="" class="me-1 rounded"
                                             height="18">
                                        <span class="align-middle">Slack</span>
                                    </button>

                                    <button class="dropdown-item position-relative" data-skin="material">
                                        <img src="/images/themes/material.svg" alt="" class="me-1 rounded"
                                             height="18">
                                        <span class="align-middle">Material Design</span>
                                    </button>

                                    <button class="dropdown-item position-relative" data-skin="flat">
                                        <img src="/images/themes/flat.svg" alt="" class="me-1 rounded"
                                             height="18">
                                        <span class="align-middle">Flat</span>
                                    </button>

                                    <button class="dropdown-item position-relative" data-skin="pastel">
                                        <img src="/images/themes/pastel.svg" alt="" class="me-1 rounded"
                                             height="18">
                                        <span class="align-middle">Pastel Pop</span>
                                    </button>

                                    <button class="dropdown-item position-relative" data-skin="caffieine">
                                        <img src="/images/themes/caffieine.svg" alt="" class="me-1 rounded"
                                             height="18">
                                        <span class="align-middle">Caffieine</span>
                                    </button>

                                    <button class="dropdown-item position-relative" data-skin="redshift">
                                        <img src="/images/themes/redshift.svg" alt="" class="me-1 rounded"
                                             height="18">
                                        <span class="align-middle">Redshift</span>
                                    </button>
                                </div>
                            </div> <!-- end row-->


                        </div> <!-- end .h-100-->
                    </div> <!-- .dropdown-menu-->

                </div> <!-- end dropdown-->
            </div>

            <!-- Button Trigger Customizer Offcanvas -->
            <div class="topbar-item d-none d-sm-flex">
                <button class="topbar-link" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas"
                        type="button">
                    <i data-lucide="settings" class="fs-xxl"></i>
                </button>
            </div>

            <!-- Light/Dark Mode Button -->
            <div class="topbar-item d-none d-sm-flex">
                <button class="topbar-link" id="light-dark-mode" type="button">
                    <i data-lucide="moon" class="fs-xxl mode-light-moon"></i>
                    <i data-lucide="sun" class="fs-xxl mode-light-sun"></i>
                </button>
            </div>

            <!-- User Dropdown -->
            <div class="topbar-item nav-user">
                <div class="dropdown">
                    <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown"
                       data-bs-offset="0,13" href="#!" aria-haspopup="false" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?background=007fff&color=fff&name={{ urlencode(auth()->user()->name) }}"
                             class="rounded-circle d-flex" width="32" alt="avatar">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- Header -->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome back!</h6>
                        </div>

                        <a href="javascript:void(0);"
                           class="dropdown-item"
                           data-bs-toggle="modal"
                           data-bs-target="#changePasswordModal">
                            <i class="ti ti-key me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Change Password</span>
                        </a>


                        <!-- Divider -->
                        <div class="dropdown-divider"></div>

                        <a href="#" class="dr   opdown-item text-danger fw-semibold"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ti ti-logout-2 me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Log Out</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>
<!-- Topbar End -->

{{-- Change Password Modal --}}
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('password.update') }}" method="POST" id="changePasswordForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    {{-- Current Password --}}
                    <div class="mb-3">
                        <label for="current_password" class="form-label">
                            Current Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group" data-password="">
                            <input type="password"
                                   class="form-control form-password @error('current_password', 'updatePassword') is-invalid @enderror"
                                   id="current_password"
                                   name="current_password"
                                   placeholder="Enter current password"
                                   value="{{ old('current_password') }}"
                                   required>
                            <button class="btn btn-light btn-icon" type="button">
                                <i class="ti ti-eye password-eye-on"></i>
                                <i class="ti ti-eye-closed d-none password-eye-off"></i>
                            </button>
                            @error('current_password', 'updatePassword')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- New Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            New Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group" data-password="">
                            <input type="password"
                                   class="form-control form-password @error('password', 'updatePassword') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="Enter new password"
                                   required>
                            <button class="btn btn-light btn-icon" type="button">
                                <i class="ti ti-eye password-eye-on"></i>
                                <i class="ti ti-eye-closed d-none password-eye-off"></i>
                            </button>
                            @error('password', 'updatePassword')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Password must be at least 8 characters long</small>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">
                            Confirm New Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group" data-password="">
                            <input type="password"
                                   class="form-control form-password @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   placeholder="Re-enter new password"
                                   required>
                            <button class="btn btn-light btn-icon" type="button">
                                <i class="ti ti-eye password-eye-on"></i>
                                <i class="ti ti-eye-closed d-none password-eye-off"></i>
                            </button>
                            @error('password_confirmation', 'updatePassword')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle Sidebar for Mobile/Tablet
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.querySelector('.button-collapse-toggle');
        const htmlElement = document.documentElement;
        const sidenavMenu = document.querySelector('.sidenav-menu');

        // Function untuk create backdrop
        function createBackdrop() {
            const backdrop = document.createElement('div');
            backdrop.className = 'sidebar-backdrop';
            document.body.appendChild(backdrop);

            // Force reflow untuk animasi
            backdrop.offsetHeight;
            backdrop.classList.add('show');

            return backdrop;
        }

        // Function untuk remove backdrop
        function removeBackdrop() {
            const backdrop = document.querySelector('.sidebar-backdrop');
            if (backdrop) {
                backdrop.classList.remove('show');
                setTimeout(() => {
                    backdrop.remove();
                }, 250); // Match dengan durasi animasi
            }
        }

        // Function untuk close sidebar
        function closeSidebar() {
            htmlElement.classList.remove('sidebar-enable');
            removeBackdrop();
        }

        // Toggle button click
        if (toggleButton) {
            toggleButton.addEventListener('click', function(e) {
                e.stopPropagation();

                if (htmlElement.classList.contains('sidebar-enable')) {
                    closeSidebar();
                } else {
                    htmlElement.classList.add('sidebar-enable');

                    // Tunggu sidebar animation selesai baru create backdrop
                    setTimeout(() => {
                        const backdrop = createBackdrop();

                        // Backdrop click handler
                        backdrop.addEventListener('click', function(e) {
                            e.stopPropagation();
                            closeSidebar();
                        }, { once: true }); // once: true = auto remove listener setelah 1x klik
                    }, 50);
                }
            });
        }

        // Close sidebar saat klik menu item (tanpa submenu)
        if (sidenavMenu) {
            sidenavMenu.addEventListener('click', function(e) {
                const link = e.target.closest('.side-nav-link');
                if (link && !link.querySelector('.menu-arrow')) {
                    // Delay sedikit supaya navigation berjalan dulu
                    setTimeout(() => {
                        closeSidebar();
                    }, 100);
                }
            });
        }

        // ESC key untuk close sidebar
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && htmlElement.classList.contains('sidebar-enable')) {
                closeSidebar();
            }
        });

        // Cleanup saat window resize ke desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1200) { // xl breakpoint
                closeSidebar();
            }
        });
    });
</script>

<script>
    // Skin Dropdown
    document.querySelectorAll('[data-dropdown="custom"]').forEach(dropdown => {
        const trigger = dropdown.querySelector('a[data-bs-toggle="dropdown"], button[data-bs-toggle="dropdown"]');
        const items = dropdown.querySelectorAll('button[data-skin]');

        const triggerImg = trigger.querySelector('[data-trigger-img]');
        const triggerLabel = trigger.querySelector('[data-trigger-label]');

        const config = JSON.parse(JSON.stringify(window.config?? {}));

        const currentSkin = config.skin;

        items.forEach(item => {
            const itemSkin = item.getAttribute('data-skin');
            const itemImg = item.querySelector('img')?.getAttribute('src');
            const itemText = item.querySelector('span')?.textContent.trim();

            // Set active on load
            if (itemSkin === currentSkin) {
                item.classList.add('drop-custom-active');
                if (triggerImg && itemImg) triggerImg.setAttribute('src', itemImg);
                if (triggerLabel && itemText) triggerLabel.textContent = itemText;
            } else {
                item.classList.remove('drop-custom-active');
            }

            // Click handler
            item.addEventListener('click', function () {
                items.forEach(i => i.classList.remove('drop-custom-active'));
                this.classList.add('drop-custom-active');

                const newImg = this.querySelector('img')?.getAttribute('src');
                const newText = this.querySelector('span')?.textContent.trim();

                if (triggerImg && newImg) triggerImg.setAttribute('src', newImg);
                if (triggerLabel && newText) triggerLabel.textContent = newText;

                if (typeof layoutCustomizer !== 'undefined') {
                    layoutCustomizer.changeSkin(itemSkin);
                }
            });
        });
    });

    @if($errors->updatePassword->any())
    document.addEventListener('DOMContentLoaded', function() {
        const changePasswordModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
        changePasswordModal.show();
    });
    @endif
</script>
