<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="description" content="TemuJasa - Platform Temukan Produk & Jasa UMKM Lokal Terdekat">

    <title><?php echo $__sections['title'] ?? 'TemuJasa - Temukan Produk & Jasa Lokal' ?></title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/temujasa.css">
    <script src="/js/temujasa.js" defer></script>
</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <header class="navbar" id="mainNavbar">
        <div class="navbar-container">

            <!-- LOGO -->
            <a href="<?php echo e(route('home')); ?>" class="logo" title="TemuJasa Beranda">
                <div class="logo-icon">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <span>TemuJasa</span>
            </a>

            <!-- NAVBAR SEARCH -->
            <div class="navbar-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input
                    type="text"
                    id="navSearchInput"
                    placeholder="Cari produk, toko, atau jasa lokal..."
                    value="<?php echo e(request('q') ?? ''); ?>"
                >
                <button type="button" class="nav-search-clear" id="navSearchClear" style="display: none;" title="Hapus pencarian">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- NAVBAR RIGHT MENU -->
            <div class="navbar-right">

                <!-- LOCATION SELECTOR -->
                <button type="button" class="location-btn" id="locationBtn" onclick="TemuJasa.openLocationModal()" title="Ubah lokasi">
                    <i class="fa-solid fa-location-dot"></i>
                    <span id="currentLocationText">Bintan Timur</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>

                <!-- NOTIFICATION: pesan chat nyata dari database -->
                <?php if(auth()->guard()->check()): ?>
                <div class="dropdown-wrap">
                    <button type="button" class="icon-button" id="notifBtn" onclick="TemuJasa.toggleDropdown('notifDropdown')" title="Pesan">
                        <i class="fa-regular fa-bell"></i>
                        <span class="badge-dot" id="headerNotifDot" style="<?php echo e(\App\Models\Message::where('recipient_id', auth()->id())->whereNull('read_at')->exists() ? '' : 'display:none'); ?>"></span>
                    </button>
                    <div class="dropdown-menu notif-dropdown" id="notifDropdown">
                        <div class="dropdown-header">
                            <h4>Pesan Baru</h4>
                        </div>
                        <div class="notif-list" id="headerNotifList">
                            <p style="font-size:10px;color:#888;padding:14px;text-align:center;">Tidak ada pesan baru.</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- SHOPPING CART -->
                <button type="button" class="icon-button cart-toggle-btn" id="cartToggleBtn" onclick="TemuJasa.toggleCart()" title="Keranjang Belanja">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="badge-count" id="cartBadgeCount">0</span>
                </button>

                <?php if(auth()->guard()->guest()): ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn-header-auth btn-header-login">Login</a>
                    <a href="<?php echo e(route('register')); ?>" class="btn-header-auth btn-header-register">Daftar</a>
                <?php endif; ?>

                <?php if(auth()->guard()->check()): ?>
                    <?php
                        $dashboardRoute = match (Auth::user()->role) {
                            'admin' => 'admin.dashboard',
                            'penyedia' => 'penyedia.dashboard',
                            default => 'pelanggan.dashboard',
                        };
                    ?>
                    <a href="<?php echo e(route($dashboardRoute)); ?>" class="btn-header-auth btn-header-login">Dashboard</a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="header-logout-form">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn-header-auth btn-header-logout">Logout</button>
                    </form>
                <?php endif; ?>

                <!-- USER PROFILE -->
                <?php if(auth()->guard()->check()): ?>
                <div class="dropdown-wrap">
                    <?php
                        $profileRoute = match (Auth::user()->role) {
                            'penyedia' => 'penyedia.profil',
                            default => 'pelanggan.profil',
                        };
                    ?>
                    <a href="<?php echo e(route($profileRoute)); ?>" class="profile-button" title="Profil Pengguna">
                        <i class="fa-solid fa-user"></i>
                    </a>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </header>


    <!-- ================= MAIN CONTENT ================= -->
    <main class="main-content">
        <?php echo $__sections['content'] ?? '' ?>
    </main>


    <!-- ================= FOOTER ================= -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">

                <!-- BRAND INFO -->
                <div class="footer-col brand-col">
                    <div class="footer-logo">
                        <div class="logo-icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <span>TemuJasa</span>
                    </div>
                    <p class="footer-desc">
                        TemuJasa adalah platform digital lokal untuk menemukan dan memberdayakan UMKM, produk unggulan, serta jasa profesional terbaik di sekitar Anda.
                    </p>
                    <div class="footer-socials">
                        <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                        <a href="#" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                    </div>
                </div>

                <!-- NAVIGATION -->
                <div class="footer-col">
                    <h4 class="footer-title">Jelajahi</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo e(route('home')); ?>">Beranda</a></li>
                        <?php if(auth()->guard()->check()): ?>
                        <li><a href="<?php echo e(route('pelanggan.cari-jasa')); ?>">Cari Jasa</a></li>
                        <li><a href="<?php echo e(route('pelanggan.pesanan')); ?>">Pesanan Saya</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- CATEGORIES -->
                <div class="footer-col">
                    <h4 class="footer-title">Kategori Populer</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo e(route('pelanggan.cari-jasa')); ?>">Kuliner & Makanan</a></li>
                        <li><a href="<?php echo e(route('pelanggan.cari-jasa')); ?>">Jasa Servis & Montir</a></li>
                        <li><a href="<?php echo e(route('pelanggan.cari-jasa')); ?>">Laundry & Kebersihan</a></li>
                        <li><a href="<?php echo e(route('pelanggan.cari-jasa')); ?>">Tenaga Harian Lepas</a></li>
                        <li><a href="<?php echo e(route('pelanggan.cari-jasa')); ?>">Sembako & Kebutuhan</a></li>
                    </ul>
                </div>

                <!-- CONTACT & SUPPORT -->
                <div class="footer-col">
                    <h4 class="footer-title">Pusat Bantuan</h4>
                    <ul class="footer-contact">
                        <li><i class="fa-solid fa-headset"></i> Layanan CS: +6285171686630</li>
                        <li><i class="fa-regular fa-envelope"></i> halo@temujasa.id</li>
                        <li><i class="fa-solid fa-map-location-dot"></i> Bintan, Kepulauan Riau, Indonesia</li>
                        <li><i class="fa-solid fa-clock"></i> Buka: 08.00 - 22.00 WIB</li>
                    </ul>
                    <div class="footer-badge-mitra">
                        <span>Punya usaha UMKM atau Jasa?</span>
                        <?php if(auth()->guard()->guest()): ?>
                            <a href="<?php echo e(route('register')); ?>" class="btn-join-mitra">Daftar Jadi Mitra</a>
                        <?php endif; ?>
                        <?php if(auth()->guard()->check()): ?>
                            <button type="button" class="btn-join-mitra" onclick="TemuJasa.openMitraModal()">Daftar Jadi Mitra</button>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <!-- COPYRIGHT -->
            <div class="footer-bottom">
                <p>&copy; <?php echo e(date('Y')); ?> <strong>TemuJasa</strong>. Seluruh hak cipta dilindungi. Bangga Dukung UMKM Lokal.</p>
                <div class="footer-bottom-links">
                    <a href="#">Syarat & Ketentuan</a>
                    <span>•</span>
                    <a href="#">Kebijakan Privasi</a>
                    <span>•</span>
                    <a href="#">Bantuan</a>
                </div>
            </div>
        </div>
    </footer>


    <!-- ================= MOBILE BOTTOM NAVIGATION ================= -->
    <nav class="mobile-navigation" id="mobileNav">
        <a href="<?php echo e(route('home')); ?>" class="mobile-nav-item <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>">
            <i class="fa-solid fa-house"></i>
            <span>Beranda</span>
        </a>

        <?php if(auth()->guard()->check()): ?>
        <a href="<?php echo e(route('pelanggan.cari-jasa')); ?>" class="mobile-nav-item <?php echo e(request()->routeIs('pelanggan.cari-jasa') ? 'active' : ''); ?>">
            <i class="fa-solid fa-magnifying-glass"></i>
            <span>Cari</span>
        </a>

        <a href="<?php echo e(route('pelanggan.pesanan')); ?>" class="mobile-nav-item <?php echo e(request()->routeIs('pelanggan.pesanan') ? 'active' : ''); ?>">
            <i class="fa-solid fa-bag-shopping"></i>
            <span>Pesanan</span>
        </a>

        <a href="<?php echo e(route('pelanggan.profil')); ?>" class="mobile-nav-item <?php echo e(request()->routeIs('pelanggan.profil') ? 'active' : ''); ?>">
            <i class="fa-regular fa-user"></i>
            <span>Profil</span>
        </a>
        <?php else: ?>
        <a href="<?php echo e(route('login')); ?>" class="mobile-nav-item">
            <i class="fa-solid fa-right-to-bracket"></i>
            <span>Login</span>
        </a>
        <?php endif; ?>
    </nav>


    <!-- ================= CART DRAWER (OFFCANVAS) ================= -->
    <div class="cart-overlay" id="cartOverlay" onclick="TemuJasa.closeCart()"></div>
    <div class="cart-drawer" id="cartDrawer">
        <div class="cart-drawer-header">
            <div class="cart-title">
                <i class="fa-solid fa-cart-shopping"></i>
                <h3>Keranjang Belanja</h3>
                <span class="cart-count-badge" id="cartDrawerCount">0 item</span>
            </div>
            <button type="button" class="btn-close-drawer" onclick="TemuJasa.closeCart()" title="Tutup Keranjang">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="cart-drawer-body" id="cartDrawerItems">
            <!-- Rendered dynamically by JavaScript -->
            <div class="empty-cart-state" id="emptyCartState">
                <div class="empty-cart-icon">
                    <i class="fa-solid fa-basket-shopping"></i>
                </div>
                <h4>Keranjang masih kosong</h4>
                <p>Yuk cari produk kuliner atau jasa lokal favoritmu sekarang!</p>
                <a href="<?php echo e(route('home')); ?>" class="btn-shop-now" onclick="TemuJasa.closeCart()">Mulai Belanja</a>
            </div>
        </div>

        <div class="cart-drawer-footer" id="cartDrawerFooter" style="display: none;">
            <div class="cart-summary-row">
                <span>Subtotal</span>
                <strong id="cartSubtotalText">Rp 0</strong>
            </div>
            <div class="cart-summary-row">
                <span>Biaya Layanan</span>
                <span class="text-free">Gratis</span>
            </div>
            <div class="cart-summary-total">
                <span>Total Pembayaran</span>
                <h4 id="cartTotalText">Rp 0</h4>
            </div>
            <button type="button" class="btn-checkout" onclick="TemuJasa.checkoutCart()">
                <i class="fa-solid fa-shield-halved"></i>
                Checkout Pesanan
            </button>
        </div>
    </div>


    <!-- ================= LOCATION PICKER MODAL ================= -->
    <div class="modal-overlay" id="locationModalOverlay" onclick="TemuJasa.closeLocationModal()"></div>
    <div class="modal-dialog" id="locationModal">
        <div class="modal-header">
            <div class="modal-header-title">
                <i class="fa-solid fa-map-location-dot"></i>
                <h3>Pilih Lokasi Anda</h3>
            </div>
            <button type="button" class="btn-close-modal" onclick="TemuJasa.closeLocationModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <p class="modal-subtitle">Pilih wilayah untuk menampilkan UMKM dan jasa terdekat di sekitar Anda.</p>
            <div class="location-search-input">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="locSearchInput" placeholder="Cari kecamatan / kelurahan..." oninput="TemuJasa.filterLocations(this.value)">
            </div>
            <div class="location-list" id="locationList">
                <button type="button" class="loc-item active" onclick="TemuJasa.selectLocation('Bintan Timur', 'Bintan')">
                    <div class="loc-item-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="loc-item-info">
                        <strong>Bintan Timur</strong>
                        <span>Kabupaten Bintan, Kepulauan Riau</span>
                    </div>
                    <i class="fa-solid fa-check loc-check"></i>
                </button>
                <button type="button" class="loc-item" onclick="TemuJasa.selectLocation('Tanjungpinang Kota', 'Tanjungpinang')">
                    <div class="loc-item-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="loc-item-info">
                        <strong>Tanjungpinang Kota</strong>
                        <span>Kota Tanjungpinang, Kepulauan Riau</span>
                    </div>
                    <i class="fa-solid fa-check loc-check"></i>
                </button>
                <button type="button" class="loc-item" onclick="TemuJasa.selectLocation('Bintan Utara', 'Bintan')">
                    <div class="loc-item-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="loc-item-info">
                        <strong>Bintan Utara (Tanjung Uban)</strong>
                        <span>Kabupaten Bintan, Kepulauan Riau</span>
                    </div>
                    <i class="fa-solid fa-check loc-check"></i>
                </button>
                <button type="button" class="loc-item" onclick="TemuJasa.selectLocation('Toapaya', 'Bintan')">
                    <div class="loc-item-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="loc-item-info">
                        <strong>Toapaya</strong>
                        <span>Kabupaten Bintan, Kepulauan Riau</span>
                    </div>
                    <i class="fa-solid fa-check loc-check"></i>
                </button>
                <button type="button" class="loc-item" onclick="TemuJasa.selectLocation('Batam Kota', 'Batam')">
                    <div class="loc-item-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="loc-item-info">
                        <strong>Batam Kota</strong>
                        <span>Kota Batam, Kepulauan Riau</span>
                    </div>
                    <i class="fa-solid fa-check loc-check"></i>
                </button>
            </div>
        </div>
    </div>


    <!-- ================= MODAL DAFTAR MITRA ================= -->
    <div class="modal-overlay" id="mitraModalOverlay" onclick="TemuJasa.closeMitraModal()"></div>
    <div class="modal-dialog mitra-modal" id="mitraModal">

        <!-- STEP 1: FORM -->
        <div id="mitraFormStep">
            <div class="modal-header">
                <div class="modal-header-title">
                    <i class="fa-solid fa-store"></i>
                    <h3>Daftar Jadi Mitra TemuJasa</h3>
                </div>
                <button type="button" class="btn-close-modal" onclick="TemuJasa.closeMitraModal()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <p class="modal-subtitle">Isi data usaha Anda. Tim kami akan menghubungi dalam 1x24 jam.</p>

                <form id="mitraForm" onsubmit="TemuJasa.submitMitraForm(event)" novalidate>

                    <div class="mitra-form-grid">
                        <div class="mitra-field">
                            <label for="mitraNama">Nama Pemilik <span class="required">*</span></label>
                            <input type="text" id="mitraNama" placeholder="Contoh: Budi Santoso" maxlength="80">
                            <span class="field-error" id="errNama"></span>
                        </div>

                        <div class="mitra-field">
                            <label for="mitraHP">Nomor WhatsApp <span class="required">*</span></label>
                            <input type="tel" id="mitraHP" placeholder="Contoh: 08123456789" maxlength="15">
                            <span class="field-error" id="errHP"></span>
                        </div>

                        <div class="mitra-field full-width">
                            <label for="mitraNamaUsaha">Nama Usaha <span class="required">*</span></label>
                            <input type="text" id="mitraNamaUsaha" placeholder="Contoh: Warung Bu Sari" maxlength="100">
                            <span class="field-error" id="errNamaUsaha"></span>
                        </div>

                        <div class="mitra-field">
                            <label for="mitraKategori">Kategori Usaha <span class="required">*</span></label>
                            <select id="mitraKategori">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Kuliner & Makanan">Kuliner & Makanan</option>
                                <option value="Minuman & Kopi">Minuman & Kopi</option>
                                <option value="Jasa Servis & Montir">Jasa Servis & Montir</option>
                                <option value="Laundry & Kebersihan">Laundry & Kebersihan</option>
                                <option value="Tenaga Harian">Tenaga Harian</option>
                                <option value="Sembako & Kebutuhan">Sembako & Kebutuhan</option>
                                <option value="Fashion & Pakaian">Fashion & Pakaian</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <span class="field-error" id="errKategori"></span>
                        </div>

                        <div class="mitra-field">
                            <label for="mitraLokasi">Wilayah / Kecamatan <span class="required">*</span></label>
                            <select id="mitraLokasi">
                                <option value="">-- Pilih Wilayah --</option>
                                <option value="Bintan Timur">Bintan Timur</option>
                                <option value="Tanjungpinang Kota">Tanjungpinang Kota</option>
                                <option value="Bintan Utara">Bintan Utara</option>
                                <option value="Toapaya">Toapaya</option>
                                <option value="Batam Kota">Batam Kota</option>
                            </select>
                            <span class="field-error" id="errLokasi"></span>
                        </div>

                        <div class="mitra-field full-width">
                            <label for="mitraDeskripsi">Deskripsi Singkat Usaha</label>
                            <textarea id="mitraDeskripsi" rows="3" placeholder="Ceritakan usaha Anda secara singkat..." maxlength="300"></textarea>
                            <span class="field-char-count" id="mitraCharCount">0 / 300</span>
                        </div>
                    </div>

                    <div class="mitra-form-footer">
                        <button type="button" class="btn-mitra-cancel" onclick="TemuJasa.closeMitraModal()">Batal</button>
                        <button type="submit" class="btn-mitra-submit" id="btnMitraSubmit">
                            <i class="fa-brands fa-whatsapp"></i>
                            Kirim via WhatsApp
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- STEP 2: SUKSES -->
        <div id="mitraSuccessStep" style="display:none;">
            <div class="mitra-success-body">
                <div class="mitra-success-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <h3>Pendaftaran Terkirim!</h3>
                <p>Data usaha Anda telah dikirim ke tim TemuJasa via WhatsApp. Kami akan menghubungi Anda dalam <strong>1x24 jam</strong>.</p>
                <div class="mitra-success-info" id="mitraSuccessInfo"></div>
                <button type="button" class="btn-mitra-submit" onclick="TemuJasa.closeMitraModal()">
                    Tutup
                </button>
            </div>
        </div>

    </div>

    <!-- ================= TOAST NOTIFICATION CONTAINER ================= -->
    <div class="toast-container" id="toastContainer"></div>

    <?php if(auth()->guard()->check()): ?>
    
    <script>
    (function () {
        const dot = document.getElementById('headerNotifDot');
        const list = document.getElementById('headerNotifList');
        if (!dot || !list) return;

        const chatUrl = '<?php echo e(auth()->user()->role === 'penyedia' ? route('penyedia.chat') : route('pelanggan.chat')); ?>';
        const pollUrl = '<?php echo e(auth()->user()->role === 'penyedia' ? route('penyedia.chat.poll') : route('pelanggan.chat.poll')); ?>';

        function escapeHtml(s) {
            const d = document.createElement('div');
            d.textContent = s ?? '';
            return d.innerHTML;
        }

        async function refresh() {
            try {
                const res = await fetch(pollUrl, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const data = await res.json();

                dot.style.display = data.unread > 0 ? '' : 'none';

                if (data.items.length === 0) {
                    list.innerHTML = '<p style="font-size:10px;color:#888;padding:14px;text-align:center;">Tidak ada pesan baru.</p>';
                    return;
                }
                list.innerHTML = data.items.map(n => `
                    <div class="notif-item unread">
                        <div class="notif-icon promo"><i class="fa-solid fa-comment-dots"></i></div>
                        <div class="notif-info">
                            <a href="${chatUrl}" style="color:inherit;text-decoration:none;">
                                <p class="notif-title"><strong>${escapeHtml(n.sender)}:</strong> ${escapeHtml(n.body)}</p>
                            </a>
                            <span class="notif-time">${escapeHtml(n.time)}</span>
                        </div>
                    </div>
                `).join('');
            } catch (e) { /* coba lagi pada tick berikutnya */ }
        }

        refresh();
        setInterval(refresh, 10000);
    })();
    </script>
    <?php endif; ?>

</body>
</html>
