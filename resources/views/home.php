

<?php $__sections['title'] = 'TemuJasa - Temukan Produk & Jasa Lokal'; ?>

<?php ob_start(); ?>

<section class="hero-section">
    <div class="hero-container">
        <div class="hero-content">
            <span class="hero-small-title">TEMUJASA</span>
            <h1>
                Temukan produk<br>
                <span>& jasa lokal</span><br>
                terdekat dari Anda
            </h1>
            <p>Dukung UMKM lokal dan dapatkan layanan terbaik di sekitar Anda.</p>

            <div class="main-search-box">
                <div class="search-input">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="mainSearch" placeholder="Cari apa hari ini?">
                </div>
                <div class="search-location" onclick="TemuJasa.openLocationModal()">
                    <i class="fa-solid fa-location-dot"></i>
                    <span id="heroLocationLabel">Lokasi saya</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <button type="button" onclick="searchTemuJasa()">Cari</button>
            </div>
        </div>

        <div class="hero-illustration">
            <div class="cloud cloud-one"></div>
            <div class="cloud cloud-two"></div>
            <div class="location-pin"><i class="fa-solid fa-location-dot"></i></div>
            <div class="shop">
                <div class="shop-roof"><span></span><span></span><span></span><span></span><span></span></div>
                <div class="shop-body">
                    <div class="shop-sign">TOKO</div>
                    <div class="shop-window"><div class="window-product"></div><div class="window-product"></div><div class="window-product"></div></div>
                </div>
            </div>
            <div class="person person-left"><div class="head"></div><div class="body"></div><div class="leg leg-one"></div><div class="leg leg-two"></div></div>
            <div class="person person-right"><div class="head"></div><div class="body"></div><div class="leg leg-one"></div><div class="leg leg-two"></div></div>
        </div>
    </div>
</section>

<section class="section-container">
    <div class="section-heading">
        <h2>Kategori</h2>
        <?php if(auth()->guard()->check()): ?>
        <a href="<?php echo e(route('pelanggan.cari-jasa')); ?>">Lihat semua</a>
        <?php else: ?>
        <a href="<?php echo e(route('login')); ?>">Lihat semua</a>
        <?php endif; ?>
    </div>
    <?php if($categories->isNotEmpty()): ?>
    <div class="category-grid">
        <?php foreach ($categories as $cat):  ?>
        <a href="<?php echo e(auth()->check() ? route('pelanggan.cari-jasa', ['category' => $cat->id]) : route('login')); ?>" class="category-card">
            <div class="category-icon"><i class="fa-solid fa-tag"></i></div>
            <span><?php echo e($cat->name); ?></span>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<section class="section-container recommendation-section">
    <div class="section-heading">
        <div>
            <h2>Penyedia Jasa</h2>
            <p>Penyedia jasa terdaftar di TemuJasa</p>
        </div>
        <?php if(auth()->guard()->check()): ?>
        <a href="<?php echo e(route('pelanggan.cari-jasa')); ?>">Lihat semua</a>
        <?php else: ?>
        <a href="<?php echo e(route('login')); ?>">Lihat semua</a>
        <?php endif; ?>
    </div>
    <?php if($providers->isNotEmpty()): ?>
    <div class="business-grid">
        <?php foreach ($providers as $p):  ?>
        <a class="business-card" href="<?php echo e(route('penyedia.profil-publik', $p->id)); ?>">
            <div class="business-image" style="background:#f0ecff;display:flex;align-items:center;justify-content:center;min-height:120px;">
                <i class="fa-solid fa-user-tie" style="font-size:48px;color:#4f2aa8;"></i>
            </div>
            <div class="business-info">
                <h3><?php echo e($p->name); ?></h3>
                <p>Penyedia Jasa</p>
                <div class="business-meta">
                    <span><i class="fa-solid fa-bag-shopping"></i> <?php echo e($p->orders_count); ?> pesanan</span>
                    <span><i class="fa-solid fa-eye"></i> Lihat profil</span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>


<script>
document.addEventListener('DOMContentLoaded', () => {
    if (!('geolocation' in navigator)) return;
    if (sessionStorage.getItem('geoAsked')) return;

    const simpanKeSession = (lat, lng) => {
        localStorage.setItem('geo.lat', lat);
        localStorage.setItem('geo.lng', lng);
        fetch('<?php echo e(route('lokasi.simpan')); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ lat, lng }),
        }).catch(() => {});
    };

    const askPermission = () => {
        sessionStorage.setItem('geoAsked', '1');
        navigator.geolocation.getCurrentPosition((pos) => {
            simpanKeSession(pos.coords.latitude.toFixed(7), pos.coords.longitude.toFixed(7));
        }, () => {
            /* izin ditolak → pengguna tetap bisa pakai fitur lokasi manual */
        }, { enableHighAccuracy: true, timeout: 10000 });
    };

    if (navigator.permissions) {
        navigator.permissions.query({ name: 'geolocation' }).then((st) => {
            if (st.state === 'granted' || st.state === 'prompt') askPermission();
        }).catch(() => {});
    } else {
        askPermission();
    }
});
</script>

<?php $__sections['content'] = ob_get_clean(); ?>

<?php require __DIR__ . '/layouts/app.php'; ?>