<?php

// Memanggil model yang dibutuhkan untuk akses menu dan submenu
use App\Models\Admin\AksesModel;
use App\Models\Admin\MenuModel;
use App\Models\Admin\SubmenuModel;
use Illuminate\Support\Facades\Session;

// Mengambil semua data menu dan mengurutkannya berdasarkan kolom menu_sort
$menu = MenuModel::orderBy('menu_sort', 'ASC')->get();
?>

<!-- APP-SIDEBAR -->
<!-- Wrapper sidebar agar posisinya tetap (sticky) -->
<div class="sticky">

    <!-- Overlay sidebar untuk mode mobile -->
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>

    <!-- Container utama sidebar -->
    <div class="app-sidebar">

        <!-- Header sidebar (logo aplikasi) -->
        <div class="side-header">
            <a class="header-brand1" href="{{ url('/admin') }}">

                <!-- Kondisi jika logo default -->
                @if ($web->web_logo == '' || $web->web_logo == 'default.png')

                    <!-- Logo kecil saat sidebar collapse -->
                    <img src="{{ url('/assets/default/web/default.png') }}"
                         height="100px"
                         class="header-brand-img toggle-logo"
                         alt="logo">

                    <!-- Logo desktop (mode gelap) -->
                    <div class="header-brand-img desktop-logo">
                        <div class="d-flex align-items-center">
                            <img src="{{ url('/assets/default/web/default.png') }}"
                                 height="100px"
                                 class="me-1"
                                 alt="logo">
                            <h4 class="fw-bold mt-4 text-white text-uppercase text-truncate">
                                {{ $web->web_nama }}
                            </h4>
                        </div>
                    </div>

                    <!-- Logo kecil mode terang -->
                    <img src="{{ url('/assets/default/web/default.png') }}"
                         height="100px"
                         class="header-brand-img light-logo"
                         alt="logo">

                    <!-- Logo desktop mode terang -->
                    <div class="header-brand-img light-logo1">
                        <div class="d-flex align-items-center">
                            <img src="{{ url('/assets/default/web/default.png') }}"
                                 height="100px"
                                 class="me-1"
                                 alt="logo">
                            <h4 class="fw-bold mt-4 text-black text-uppercase text-truncate">
                                {{ $web->web_nama }}
                            </h4>
                        </div>
                    </div>

                @else
                    <!-- Jika logo diatur oleh admin -->

                    <!-- Logo kecil -->
                    <img src="{{ asset('storage/web/' . $web->web_logo) }}"
                         height="100px"
                         class="header-brand-img toggle-logo"
                         alt="logo">

                    <!-- Logo desktop gelap -->
                    <div class="header-brand-img desktop-logo">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/web/' . $web->web_logo) }}"
                                 height="100px"
                                 class="me-1"
                                 alt="logo">
                            <h4 class="fw-bold mt-4 text-white text-uppercase text-truncate">
                                {{ $web->web_nama }}
                            </h4>
                        </div>
                    </div>

                    <!-- Logo kecil terang -->
                    <img src="{{ asset('storage/web/' . $web->web_logo) }}"
                         height="100px"
                         class="header-brand-img light-logo"
                         alt="logo">

                    <!-- Logo desktop terang -->
                    <div class="header-brand-img light-logo1">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/web/' . $web->web_logo) }}"
                                 height="100px"
                                 class="me-1"
                                 alt="logo">
                            <h4 class="fw-bold mt-4 text-black text-uppercase text-truncate">
                                {{ $web->web_nama }}
                            </h4>
                        </div>
                    </div>
                @endif
            </a>
            <!-- LOGO -->
        </div>

        <!-- Isi menu sidebar -->
        <div class="main-sidemenu">

            <!-- Tombol geser kiri menu -->
            <div class="slide-left disabled" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                     width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg>
            </div>

            <!-- Daftar menu -->
            <ul class="side-menu">

                <!-- Judul Menu -->
                @if (count($menu) > 0)
                    <li class="sub-category">
                        <h3>Menu</h3>
                    </li>
                @endif

                <!-- Loop data menu -->
                @foreach ($menu as $m)

                    <!-- Cek hak akses menu (view) -->
                    <?php
                    $getMenu = AksesModel::where([
                        'role_id' => Session::get('user')->role_id,
                        'menu_id' => $m->menu_id,
                        'akses_type' => 'view'
                    ])->count();
                    ?>

                    <!-- Menu tanpa submenu -->
                    @if ($m->menu_type == 1)
                        @if ($getMenu > 0)
                            <li class="slide">
                                <a class="side-menu__item {{ $title == $m->menu_judul ? 'active' : '' }}"
                                   href="{{ url('/admin') . $m->menu_redirect }}">
                                    <i class="side-menu__icon fe fe-{{ $m->menu_icon }}"></i>
                                    <span class="side-menu__label">{{ $m->menu_judul }}</span>
                                </a>
                            </li>
                        @endif

                    <!-- Menu dengan submenu -->
                    @elseif($m->menu_type == 2)
                        @if ($getMenu > 0)

                            <!-- Ambil submenu berdasarkan menu -->
                            <?php
                            $submenu = SubmenuModel::where('menu_id', $m->menu_id)
                                ->orderBy('submenu_sort', 'ASC')
                                ->get();

                            // Cek apakah submenu aktif sesuai halaman
                            $checkMenu = SubmenuModel::join('tbl_menu', 'tbl_menu.menu_id', '=', 'tbl_submenu.menu_id')
                                ->where([
                                    'tbl_menu.menu_judul' => $m->menu_judul,
                                    'tbl_submenu.submenu_judul' => $title
                                ])->count();
                            ?>

                            <li class="slide {{ $checkMenu > 0 ? 'is-expanded' : '' }}">
                                <a class="side-menu__item {{ $checkMenu > 0 ? 'active' : '' }}"
                                   href="javascript:void(0)">
                                    <i class="side-menu__icon fe fe-{{ $m->menu_icon }}"></i>
                                    <span class="side-menu__label">{{ $m->menu_judul }}</span>
                                    <i class="angle fe fe-chevron-right"></i>
                                </a>

                                <!-- Daftar submenu -->
                                <ul class="slide-menu">
                                    @foreach ($submenu as $sub)

                                        <!-- Cek hak akses submenu -->
                                        <?php
                                        $getSubmenu = AksesModel::where([
                                            'role_id' => Session::get('user')->role_id,
                                            'submenu_id' => $sub->submenu_id,
                                            'akses_type' => 'view'
                                        ])->count();
                                        ?>

                                        @if ($getSubmenu > 0)
                                            <li>
                                                <a href="{{ url('/admin') . $sub->submenu_redirect }}"
                                                   class="slide-item {{ $title == $sub->submenu_judul ? 'active' : '' }}">
                                                    {{ $sub->submenu_judul }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endif
                @endforeach

                <!-- Menu Other -->
                <li class="sub-category">
                    <h3>Other</h3>
                </li>

                <!-- Menu logout -->
                <li class="slide">
                    <a class="side-menu__item"
                       data-bs-effect="effect-super-scaled"
                       data-bs-toggle="modal"
                       href="#modalLogout">
                        <i class="side-menu__icon fe fe-log-out"></i>
                        <span class="side-menu__label">Log Out</span>
                    </a>
                </li>

            </ul>

            <!-- Tombol geser kanan menu -->
            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                     width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg>
            </div>

        </div>
    </div>
    <!-- /APP-SIDEBAR -->
</div>
