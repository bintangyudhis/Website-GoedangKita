<!-- Sidebar-right -->
<!-- Sidebar kanan (biasanya untuk notifikasi / chat / timeline) -->
<div class="sidebar sidebar-right sidebar-animate">
    <!-- NOTE: wrapper sidebar kanan.
         `sidebar-right` biasanya dipakai untuk posisi kanan.
         `sidebar-animate` untuk efek transisi saat buka/tutup. -->

    <!-- Panel utama sidebar kanan -->
    <div class="panel panel-primary card mb-0 shadow-none border-0">
        <!-- NOTE: panel + card styling.
             `mb-0` biar tidak ada margin bawah.
             `shadow-none border-0` untuk tampilan flat tanpa bayangan & border. -->

        <!-- Header sidebar kanan -->
        <div class="tab-menu-heading border-0 d-flex p-3">
            <!-- NOTE: area header (atas) untuk judul + tombol close.
                 `d-flex` agar judul dan tombol bisa sejajar dalam 1 baris. -->

            <!-- Judul sidebar -->
            <div class="card-title mb-0">
                <!-- NOTE: icon bell menandakan notifikasi. -->
                <i class="fe fe-bell me-2"></i>
                <!-- NOTE: `pulse` biasanya animasi titik/lingkaran kecil untuk indikator notifikasi baru. -->
                <span class="pulse"></span>
                Notifications
            </div>

            <!-- Tombol close sidebar kanan -->
            <div class="card-options ms-auto">
                <!-- NOTE: `ms-auto` mendorong tombol ke paling kanan (flex). -->
                <a href="javascript:void(0);"
                   class="sidebar-icon text-end float-end me-3 mb-1"
                   data-bs-toggle="sidebar-right"
                   data-target=".sidebar-right">
                    <!-- NOTE: biasanya attribute ini dipakai oleh script template/Bootstrap custom
                         untuk toggle buka/tutup sidebar kanan, targetnya `.sidebar-right`. -->
                    <i class="fe fe-x text-white"></i>
                </a>
            </div>
        </div>

        <!-- Body sidebar kanan -->
        <div class="panel-body tabs-menu-body latest-tasks p-0 border-0">
            <!-- NOTE: kontainer isi sidebar.
                 `p-0` supaya padding default hilang.
                 `tabs-menu-body` dan `latest-tasks` biasanya class bawaan template untuk styling. -->

            <!-- Tab menu (Feeds, Chat, Timeline) -->
            <div class="tabs-menu border-bottom">
                <!-- NOTE: area tombol tab di atas konten, dibatasi garis bawah (`border-bottom`). -->

                <!-- Tabs -->
                <ul class="nav panel-tabs">
                    <!-- NOTE: `nav panel-tabs` = styling list tab (mirip bootstrap nav-tabs). -->

                    <li class="">
                        <a href="#side1" class="active" data-bs-toggle="tab">
                            <!-- NOTE: `href="#side1"` harus match dengan id tab-pane. -->
                            <i class="fe fe-settings me-1"></i>Feeds
                        </a>
                    </li>
                    <li>
                        <a href="#side2" data-bs-toggle="tab">
                            <i class="fe fe-message-circle"></i> Chat
                        </a>
                    </li>
                    <li>
                        <a href="#side3" data-bs-toggle="tab">
                            <i class="fe fe-anchor me-1"></i>Timeline
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Konten tab -->
            <div class="tab-content">
                <!-- NOTE: kontainer isi tab (Bootstrap). Hanya 1 `.tab-pane` yang active akan terlihat. -->

                <!-- TAB 1: FEEDS -->
                <div class="tab-pane active" id="side1">
                    <!-- NOTE: tab feeds ditandai `active` default.
                         id `side1` harus sama dengan href tab "Feeds". -->

                    <!-- Judul section Feeds -->
                    <div class="p-3 fw-semibold ps-5">Feeds</div>
                    <!-- NOTE: padding kiri `ps-5` untuk memberi indent, `fw-semibold` untuk font tebal sedang. -->

                    <!-- List feeds (contoh: new user, order, task, dsb) -->
                    <div class="card-body pt-2">
                        <!-- NOTE: `pt-2` mengatur jarak atas konten list. -->
                        <div class="browser-stats">
                            <!-- NOTE: `browser-stats` ini class template, dipakai sebagai wrapper item-item. -->

                            <!-- Item feed 1 -->
                            <div class="row mb-4">
                                <!-- NOTE: tiap feed item dibuat dengan grid row: kiri icon (2 kolom) kanan konten (10 kolom). -->
                                <div class="col-sm-2 mb-sm-0 mb-3">
                                    <!-- NOTE: `mb-3` untuk mobile agar ada jarak bawah; di sm ke atas dihapus (`mb-sm-0`). -->
                                    <span class="feeds avatar-circle brround bg-primary-transparent">
                                        <!-- NOTE: `avatar-circle brround` buat icon bulat.
                                             `bg-*-transparent` untuk background transparan sesuai warna tema. -->
                                        <i class="fe fe-user text-primary"></i>
                                    </span>
                                </div>
                                <div class="col-sm-10 ps-sm-0">
                                    <!-- NOTE: `ps-sm-0` menghapus padding kiri di ukuran sm+ agar align rapi. -->
                                    <div class="d-flex align-items-end justify-content-between ms-2">
                                        <!-- NOTE: flex untuk menaruh judul di kiri dan action icon di kanan. -->
                                        <h6 class="">New user registered</h6>
                                        <div>
                                            <!-- NOTE: icon action (settings & close) masih dummy karena href javascript:void(0). -->
                                            <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                            <a href="javascript:void(0)"><i class="fe fe-x"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Item feed 2 -->
                            <div class="row mb-4">
                                <div class="col-sm-2 mb-sm-0 mb-3">
                                    <span class="feeds avatar-circle avatar-circle-secondary brround bg-secondary-transparent">
                                        <!-- NOTE: variasi warna secondary untuk tipe feed berbeda. -->
                                        <i class="fe fe-shopping-cart text-secondary"></i>
                                    </span>
                                </div>
                                <div class="col-sm-10 ps-sm-0">
                                    <div class="d-flex align-items-end justify-content-between ms-2">
                                        <h6 class="">New order delivered</h6>
                                        <div>
                                            <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                            <a href="javascript:void(0)"><i class="fe fe-x"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Item feed 3 -->
                            <div class="row mb-4">
                                <div class="col-sm-2 mb-sm-0 mb-3">
                                    <span class="feeds avatar-circle avatar-circle-danger brround bg-danger-transparent">
                                        <i class="fe fe-bell text-danger"></i>
                                    </span>
                                </div>
                                <div class="col-sm-10 ps-sm-0">
                                    <div class="d-flex align-items-end justify-content-between ms-2">
                                        <h6 class="">You have pending tasks</h6>
                                        <div>
                                            <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                            <a href="javascript:void(0)"><i class="fe fe-x"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Item feed 4 -->
                            <div class="row mb-4">
                                <div class="col-sm-2 mb-sm-0 mb-3">
                                    <span class="feeds avatar-circle avatar-circle-warning brround bg-warning-transparent">
                                        <i class="fe fe-gitlab text-warning"></i>
                                    </span>
                                </div>
                                <div class="col-sm-10 ps-sm-0">
                                    <div class="d-flex align-items-end justify-content-between ms-2">
                                        <h6 class="">New version arrived</h6>
                                        <div>
                                            <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                            <a href="javascript:void(0)"><i class="fe fe-x"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Item feed 5 -->
                            <div class="row mb-4">
                                <div class="col-sm-2 mb-sm-0 mb-3">
                                    <span class="feeds avatar-circle avatar-circle-pink brround bg-pink-transparent">
                                        <i class="fe fe-database text-pink"></i>
                                    </span>
                                </div>
                                <div class="col-sm-10 ps-sm-0">
                                    <div class="d-flex align-items-end justify-content-between ms-2">
                                        <h6 class="">Server #1 overloaded</h6>
                                        <div>
                                            <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                            <a href="javascript:void(0)"><i class="fe fe-x"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Item feed 6 -->
                            <div class="row">
                                <div class="col-sm-2 mb-sm-0 mb-3">
                                    <span class="feeds avatar-circle avatar-circle-info brround bg-info-transparent">
                                        <i class="fe fe-check-circle text-info"></i>
                                    </span>
                                </div>
                                <div class="col-sm-10 ps-sm-0">
                                    <div class="d-flex align-items-end justify-content-between ms-2">
                                        <h6 class="">New project launched</h6>
                                        <div>
                                            <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                            <a href="javascript:void(0)"><i class="fe fe-x"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Judul section Settings -->
                    <div class="p-3 fw-semibold ps-5">Settings</div>
                    <!-- NOTE: section kedua pada tab feeds, isinya shortcut-setting. -->

                    <!-- List setting shortcut (dummy template) -->
                    <div class="card-body pt-2">
                        <div class="browser-stats">

                            <!-- Settings item 1 -->
                            <div class="row mb-4">
                                <div class="col-sm-2 mb-sm-0 mb-3">
                                    <span class="feeds avatar-circle brround bg-primary-transparent">
                                        <i class="fe fe-settings text-primary"></i>
                                    </span>
                                </div>
                                <div class="col-sm-10 ps-sm-0">
                                    <div class="d-flex align-items-end justify-content-between ms-2">
                                        <h6 class="">General Settings</h6>
                                        <div>
                                            <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Settings item 2 -->
                            <div class="row mb-4">
                                <div class="col-sm-2 mb-sm-0 mb-3">
                                    <span class="feeds avatar-circle avatar-circle-secondary brround bg-secondary-transparent">
                                        <i class="fe fe-map-pin text-secondary"></i>
                                    </span>
                                </div>
                                <div class="col-sm-10 ps-sm-0">
                                    <div class="d-flex align-items-end justify-content-between ms-2">
                                        <h6 class="">Map Settings</h6>
                                        <div>
                                            <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Settings item 3 -->
                            <div class="row mb-4">
                                <div class="col-sm-2 mb-sm-0 mb-3">
                                    <span class="feeds avatar-circle avatar-circle-danger brround bg-danger-transparent">
                                        <i class="fe fe-headphones text-danger"></i>
                                    </span>
                                </div>
                                <div class="col-sm-10 ps-sm-0">
                                    <div class="d-flex align-items-end justify-content-between ms-2">
                                        <h6 class="">Support Settings</h6>
                                        <div>
                                            <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Settings item 4 -->
                            <div class="row mb-4">
                                <div class="col-sm-2 mb-sm-0 mb-3">
                                    <span class="feeds avatar-circle avatar-circle-warning brround bg-warning-transparent">
                                        <i class="fe fe-credit-card text-warning"></i>
                                    </span>
                                </div>
                                <div class="col-sm-10 ps-sm-0">
                                    <div class="d-flex align-items-end justify-content-between ms-2">
                                        <h6 class="">Payment Settings</h6>
                                        <div>
                                            <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Settings item 5 -->
                            <div class="row mb-4">
                                <div class="col-sm-2 mb-sm-0 mb-3">
                                    <span class="feeds avatar-circle avatar-circle-pink brround bg-pink-transparent">
                                        <i class="fe fe-bell text-pink"></i>
                                    </span>
                                </div>
                                <div class="col-sm-10 ps-sm-0">
                                    <div class="d-flex align-items-end justify-content-between ms-2">
                                        <h6 class="">Notification Settings</h6>
                                        <div>
                                            <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- TAB 2: CHAT -->
                <div class="tab-pane" id="side2">
                    <!-- NOTE: tab chat (id side2). Kontennya list kontak/percakapan. -->

                    <!-- List chat (dummy template / contoh UI chat) -->
                    <div class="list-group list-group-flush">
                        <!-- NOTE: `list-group-flush` menghilangkan border luar list-group agar menyatu dengan card. -->

                        <!-- Section Today -->
                        <div class="pt-3 fw-semibold ps-5">Today</div>

                        <!-- Item chat -->
                        <div class="list-group-item d-flex align-items-center">
                            <!-- NOTE: satu item chat: avatar + nama + snippet pesan. -->
                            <div class="me-2">
                                <span class="avatar avatar-md brround cover-image" data-bs-image-src="../assets/images/users/2.jpg"></span>
                                <!-- NOTE: avatar pakai `cover-image` + data-bs-image-src untuk set background image via script. -->
                            </div>
                            <div class="">
                                <a href="chat.html">
                                    <!-- NOTE: link menuju halaman chat detail. -->
                                    <div class="fw-semibold text-dark" data-bs-toggle="modal" data-target="#chatmodel">
                                        <!-- NOTE: attribute modal ini tampak campuran: `data-bs-toggle` (BS5)
                                             tapi `data-target` (BS4). Pastikan versi Bootstrap/template konsisten. -->
                                        Addie Minstra
                                    </div>
                                    <p class="mb-0 fs-12 text-muted"> Hey! there I' am available.... </p>
                                </a>
                            </div>
                        </div>

                        <!-- (dst... item chat lainnya) -->
                        <!-- NOTE: blok berikutnya tetap sama, hanya tidak saya ubah -->

                        <div class="list-group-item d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md brround cover-image" data-bs-image-src="../assets/images/users/11.jpg"><span class="avatar-status bg-success"></span></span>
                                <!-- NOTE: `avatar-status bg-success` biasanya indikator online/aktif. -->
                            </div>
                            <div class="">
                                <a href="chat.html">
                                    <div class="fw-semibold text-dark" data-bs-toggle="modal" data-target="#chatmodel">Rose Bush</div>
                                    <p class="mb-0 fs-12 text-muted"> Okay...I will be waiting for you </p>
                                </a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md brround cover-image" data-bs-image-src="../assets/images/users/10.jpg"></span>
                            </div>
                            <div class="">
                                <a href="chat.html">
                                    <div class="fw-semibold text-dark" data-bs-toggle="modal" data-target="#chatmodel">Claude Strophobia</div>
                                    <p class="mb-0 fs-12 text-muted"> Hi we can explain our new project......</p>
                                </a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md brround cover-image" data-bs-image-src="../assets/images/users/13.jpg"></span>
                            </div>
                            <div class="">
                                <a href="chat.html">
                                    <div class="fw-semibold text-dark" data-bs-toggle="modal" data-target="#chatmodel">Eileen Dover</div>
                                    <p class="mb-0 fs-12 text-muted"> New product Launching... </p>
                                </a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md brround cover-image" data-bs-image-src="../assets/images/users/12.jpg"><span class="avatar-status bg-success"></span></span>
                            </div>
                            <div class="">
                                <a href="chat.html">
                                    <div class="fw-semibold text-dark" data-bs-toggle="modal" data-target="#chatmodel">Willie Findit</div>
                                    <p class="mb-0 fs-12 text-muted"> Okay...I will be waiting for you </p>
                                </a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md brround cover-image" data-bs-image-src="../assets/images/users/15.jpg"></span>
                            </div>
                            <div class="">
                                <a href="chat.html">
                                    <div class="fw-semibold text-dark" data-bs-toggle="modal" data-target="#chatmodel">Manny Jah</div>
                                    <p class="mb-0 fs-12 text-muted"> Hi we can explain our new project......</p>
                                </a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md brround cover-image" data-bs-image-src="../assets/images/users/4.jpg"></span>
                            </div>
                            <div class="">
                                <a href="chat.html">
                                    <div class="fw-semibold text-dark" data-bs-toggle="modal" data-target="#chatmodel">Cherry Blossom</div>
                                    <p class="mb-0 fs-12 text-muted"> Hey! there I' am available....</p>
                                </a>
                            </div>
                        </div>

                        <!-- Section Yesterday -->
                        <div class="pt-3 fw-semibold ps-5">Yesterday</div>

                        <!-- (dst... item chat kemarin, tetap sama) -->

                        <div class="list-group-item d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md brround cover-image" data-bs-image-src="../assets/images/users/7.jpg"><span class="avatar-status bg-success"></span></span>
                            </div>
                            <div class="">
                                <a href="chat.html">
                                    <div class="fw-semibold text-dark" data-bs-toggle="modal" data-target="#chatmodel">Simon Sais</div>
                                    <p class="mb-0 fs-12 text-muted">Schedule Realease...... </p>
                                </a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md brround cover-image" data-bs-image-src="../assets/images/users/9.jpg"></span>
                            </div>
                            <div class="">
                                <a href="chat.html">
                                    <div class="fw-semibold text-dark" data-bs-toggle="modal" data-target="#chatmodel">Laura Biding</div>
                                    <p class="mb-0 fs-12 text-muted"> Hi we can explain our new project......</p>
                                </a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md brround cover-image" data-bs-image-src="../assets/images/users/2.jpg"><span class="avatar-status bg-success"></span></span>
                            </div>
                            <div class="">
                                <a href="chat.html">
                                    <div class="fw-semibold text-dark" data-bs-toggle="modal" data-target="#chatmodel">Addie Minstra</div>
                                    <p class="mb-0 fs-12 text-muted">Contact me for details....</p>
                                </a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md brround cover-image" data-bs-image-src="../assets/images/users/9.jpg"></span>
                            </div>
                            <div class="">
                                <a href="chat.html">
                                    <div class="fw-semibold text-dark" data-bs-toggle="modal" data-target="#chatmodel">Ivan Notheridiya</div>
                                    <p class="mb-0 fs-12 text-muted"> Hi we can explain our new project......</p>
                                </a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md brround cover-image" data-bs-image-src="../assets/images/users/14.jpg"></span>
                            </div>
                            <div class="">
                                <a href="chat.html">
                                    <div class="fw-semibold text-dark" data-bs-toggle="modal" data-target="#chatmodel">Dulcie Veeta</div>
                                    <p class="mb-0 fs-12 text-muted"> Okay...I will be waiting for you </p>
                                </a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md brround cover-image" data-bs-image-src="../assets/images/users/11.jpg"></span>
                            </div>
                            <div class="">
                                <a href="chat.html">
                                    <div class="fw-semibold text-dark" data-bs-toggle="modal" data-target="#chatmodel">Florinda Carasco</div>
                                    <p class="mb-0 fs-12 text-muted">New product Launching...</p>
                                </a>
                            </div>
                        </div>

                        <div class="list-group-item d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md brround cover-image" data-bs-image-src="../assets/images/users/4.jpg"><span class="avatar-status bg-success"></span></span>
                            </div>
                            <div class="">
                                <a href="chat.html">
                                    <div class="fw-semibold text-dark" data-bs-toggle="modal" data-target="#chatmodel">Cherry Blossom</div>
                                    <p class="mb-0 fs-12 text-muted">cherryblossom@gmail.com</p>
                                    <!-- NOTE: contoh snippet bisa berupa teks email/preview pesan. -->
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- TAB 3: TIMELINE -->
                <div class="tab-pane" id="side3">
                    <!-- NOTE: tab timeline untuk histori aktivitas/task, ditampilkan dalam list vertikal. -->

                    <!-- Timeline task (dummy template / histori aktivitas) -->
                    <ul class="task-list timeline-task">
                        <!-- NOTE: list timeline. Biasanya ada styling garis vertikal / icon di kiri. -->

                        <!-- Item timeline -->
                        <li class="d-sm-flex mt-4">
                            <!-- NOTE: 1 item timeline: konten kiri + action kanan.
                                 `d-sm-flex` jadi flex mulai ukuran sm, mobile tetap stack. -->
                            <div>
                                <i class="task-icon1"></i>
                                <!-- NOTE: icon marker timeline (biasanya bulatan/ikon kecil). -->
                                <h6 class="fw-semibold">
                                    Task Finished
                                    <span class="text-muted fs-11 mx-2 fw-normal">09 July 2021</span>
                                    <!-- NOTE: tanggal aktivitas (dummy). -->
                                </h6>
                                <p class="text-muted fs-12">
                                    Adam Berry finished task on
                                    <a href="javascript:void(0)" class="fw-semibold"> Project Management</a>
                                </p>
                            </div>
                            <div class="ms-auto d-md-flex me-3">
                                <!-- NOTE: action di kanan (edit/trash) masih dummy link. -->
                                <a href="javascript:void(0)" class="text-muted me-2"><span class="fe fe-edit"></span></a>
                                <a href="javascript:void(0)" class="text-muted"><span class="fe fe-trash-2"></span></a>
                            </div>
                        </li>

                        <!-- (dst... item timeline lainnya tetap sama) -->

                        <li class="d-sm-flex">
                            <div>
                                <i class="task-icon1"></i>
                                <h6 class="fw-semibold">New Comment<span class="text-muted fs-11 mx-2 fw-normal">05 July 2021</span></h6>
                                <p class="text-muted fs-12">Victoria commented on Project <a href="javascript:void(0)" class="fw-semibold"> AngularJS Template</a></p>
                            </div>
                            <div class="ms-auto d-md-flex me-3">
                                <a href="javascript:void(0)" class="text-muted me-2"><span class="fe fe-edit"></span></a>
                                <a href="javascript:void(0)" class="text-muted"><span class="fe fe-trash-2"></span></a>
                            </div>
                        </li>

                        <li class="d-sm-flex">
                            <div>
                                <i class="task-icon1"></i>
                                <h6 class="fw-semibold">New Comment<span class="text-muted fs-11 mx-2 fw-normal">25 June 2021</span></h6>
                                <p class="text-muted fs-12">Victoria commented on Project <a href="javascript:void(0)" class="fw-semibold"> AngularJS Template</a></p>
                            </div>
                            <div class="ms-auto d-md-flex me-3">
                                <a href="javascript:void(0)" class="text-muted me-2"><span class="fe fe-edit"></span></a>
                                <a href="javascript:void(0)" class="text-muted"><span class="fe fe-trash-2"></span></a>
                            </div>
                        </li>

                        <li class="d-sm-flex">
                            <div>
                                <i class="task-icon1"></i>
                                <h6 class="fw-semibold">Task Overdue<span class="text-muted fs-11 mx-2 fw-normal">14 June 2021</span></h6>
                                <p class="text-muted mb-0 fs-12">Petey Cruiser finished task <a href="javascript:void(0)" class="fw-semibold"> Integrated management</a></p>
                            </div>
                            <div class="ms-auto d-md-flex me-3">
                                <a href="javascript:void(0)" class="text-muted me-2"><span class="fe fe-edit"></span></a>
                                <a href="javascript:void(0)" class="text-muted"><span class="fe fe-trash-2"></span></a>
                            </div>
                        </li>

                        <li class="d-sm-flex">
                            <div>
                                <i class="task-icon1"></i>
                                <h6 class="fw-semibold">Task Overdue<span class="text-muted fs-11 mx-2 fw-normal">29 June 2021</span></h6>
                                <p class="text-muted mb-0 fs-12">Petey Cruiser finished task <a href="javascript:void(0)" class="fw-semibold"> Integrated management</a></p>
                            </div>
                            <div class="ms-auto d-md-flex me-3">
                                <a href="javascript:void(0)" class="text-muted me-2"><span class="fe fe-edit"></span></a>
                                <a href="javascript:void(0)" class="text-muted"><span class="fe fe-trash-2"></span></a>
                            </div>
                        </li>

                        <li class="d-sm-flex">
                            <div>
                                <i class="task-icon1"></i>
                                <h6 class="fw-semibold">Task Finished<span class="text-muted fs-11 mx-2 fw-normal">09 July 2021</span></h6>
                                <p class="text-muted fs-12">Adam Berry finished task on<a href="javascript:void(0)" class="fw-semibold"> Project Management</a></p>
                            </div>
                            <div class="ms-auto d-md-flex me-3">
                                <a href="javascript:void(0)" class="text-muted me-2"><span class="fe fe-edit"></span></a>
                                <a href="javascript:void(0)" class="text-muted"><span class="fe fe-trash-2"></span></a>
                            </div>
                        </li>

                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>
<!--/Sidebar-right-->
