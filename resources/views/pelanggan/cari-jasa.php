<?php $__sections['title'] = 'Cari Jasa'; ?>
<?php ($user = auth()->user()); ?>
<?php $__sections['user-initials'] = strtoupper(substr($user->name, 0, 2)); ?>
<?php $__sections['user-name'] = $user->name; ?>
<?php $__sections['role-class'] = 'role-pelanggan'; ?>
<?php $__sections['role-label'] = 'Pelanggan'; ?>
<?php $__sections['profile-link'] = route('pelanggan.profil'); ?>
<?php $__sections['page-title'] = 'Cari Jasa'; ?>
<?php $__sections['page-subtitle'] = 'Temukan jasa terbaik di sekitar Anda'; ?>

<?php ob_start(); ?>
<span class="sidebar-nav-section">Menu Utama</span>
<a href="<?php echo e(route('pelanggan.dashboard')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
<a href="<?php echo e(route('pelanggan.cari-jasa')); ?>" class="sidebar-nav-item active"><i class="fa-solid fa-magnifying-glass"></i><span>Cari Jasa</span></a>
<span class="sidebar-nav-section">Aktivitas</span>
<a href="<?php echo e(route('pelanggan.pesanan')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-bag-shopping"></i><span>Pesanan Saya</span></a>
<a href="<?php echo e(route('pelanggan.chat')); ?>" class="sidebar-nav-item"><i class="fa-regular fa-comment-dots"></i><span>Chat / Pesan</span></a>
<a href="<?php echo e(route('pelanggan.review')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-star"></i><span>Review & Rating</span></a>
<span class="sidebar-nav-section">Akun</span>
<a href="<?php echo e(route('pelanggan.profil')); ?>" class="sidebar-nav-item"><i class="fa-regular fa-user"></i><span>Profil Saya</span></a>
<?php $__sections['sidebar-menu'] = ob_get_clean(); ?>

<?php ob_start(); ?>

<!-- SEARCH BAR -->
<div class="dash-card" style="margin-bottom:16px;">
    <form method="GET" action="<?php echo e(route('pelanggan.cari-jasa')); ?>" id="formCariJasa" style="display:flex;gap:10px;flex-wrap:wrap;">
        <?php if($selectedCategory): ?>
        <input type="hidden" name="category" value="<?php echo e($selectedCategory); ?>">
        <?php endif; ?>
        <input type="hidden" name="lat" id="inputLat" value="<?php echo e($userLat ?? ''); ?>">
        <input type="hidden" name="lng" id="inputLng" value="<?php echo e($userLng ?? ''); ?>">
        <div class="dash-search-bar" style="flex:1;min-width:200px;">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="q" value="<?php echo e($q); ?>" placeholder="Cari nama jasa, kategori, atau penyedia..." style="width:100%;">
        </div>
        <select name="lokasi" class="form-select" style="width:140px;">
            <option value="">Semua Lokasi</option>
            <?php foreach (($locations ?? collect()) as $loc):  ?>
            <option value="<?php echo e($loc); ?>" <?php echo e($lokasi === $loc ? 'selected' : ''); ?>><?php echo e($loc); ?></option>
            <?php endforeach; ?>
        </select>
        <select name="urutkan" class="form-select" style="width:160px;">
            <option value="relevan" <?php echo e($urutkan === 'relevan' ? 'selected' : ''); ?>>Urutkan: Relevan</option>
            <option value="harga-rendah" <?php echo e($urutkan === 'harga-rendah' ? 'selected' : ''); ?>>Harga Terendah</option>
            <option value="harga-tinggi" <?php echo e($urutkan === 'harga-tinggi' ? 'selected' : ''); ?>>Harga Tertinggi</option>
            <option value="terbaru" <?php echo e($urutkan === 'terbaru' ? 'selected' : ''); ?>>Terbaru</option>
        </select>
        <button type="submit" class="btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
        <button type="button" class="btn-secondary" onclick="cariTerdekat()" title="Urutkan jasa dari yang terdekat dengan posisi Anda">
            <i class="fa-solid fa-location-crosshairs"></i> <?php echo e(($userLat && $userLng) ? 'Lokasi Aktif' : 'Gunakan Lokasi Saya'); ?>

        </button>
    </form>
</div>

<script>
function cariTerdekat() {
    const latInput = document.getElementById('inputLat');
    const lngInput = document.getElementById('inputLng');

    if (!('geolocation' in navigator)) { alert('Browser tidak mendukung geolokasi.'); return; }

    navigator.geolocation.getCurrentPosition((pos) => {
        latInput.value = pos.coords.latitude.toFixed(7);
        lngInput.value = pos.coords.longitude.toFixed(7);
        document.querySelector('select[name="urutkan"]').value = 'terdekat';
        document.getElementById('formCariJasa').submit();
    }, (err) => {
        alert(err.code === 1
            ? 'Izin lokasi ditolak. Aktifkan izin lokasi di pengaturan browser Anda.'
            : 'Gagal mengambil lokasi. Pastikan GPS aktif dan coba lagi.');
    }, { enableHighAccuracy: true, timeout: 10000 });
}

// â”€â”€ MINTA IZIN GPS OTOMATIS SAAT HALAMAN DIBUKA â”€â”€
document.addEventListener('DOMContentLoaded', () => {
    const latInput = document.getElementById('inputLat');
    const lngInput = document.getElementById('inputLng');

    // sudah punya koordinat di URL â†’ jangan tanya lagi
    if (latInput.value && lngInput.value) return;
    // sesi ini sudah pernah diminta â†’ jangan ganggu pengguna berulang kali
    if (sessionStorage.getItem('geoAsked')) return;

    if (!('geolocation' in navigator)) return;

    const askPermission = () => {
        sessionStorage.setItem('geoAsked', '1');
        navigator.geolocation.getCurrentPosition((pos) => {
            latInput.value = pos.coords.latitude.toFixed(7);
            lngInput.value = pos.coords.longitude.toFixed(7);
            document.querySelector('select[name="urutkan"]').value = 'terdekat';
            document.getElementById('formCariJasa').submit(); // reload dengan hasil terdekat
        }, () => {
            /* izin ditolak â†’ biarkan user pakai tombol manual */
        }, { enableHighAccuracy: true, timeout: 10000 });
    };

    // kalau izin sebelumnya sudah diberikan/diizinkan, langsung ambil tanpa prompt
    if (navigator.permissions) {
        navigator.permissions.query({ name: 'geolocation' }).then((st) => {
            if (st.state === 'granted') {
                askPermission();
            } else if (st.state === 'prompt') {
                askPermission(); // browser menampilkan popup izin
            }
            // 'denied' â†’ jangan tanya, hormati pilihan user
        }).catch(() => {});
    } else {
        askPermission();
    }
});
</script>

<!-- KATEGORI CEPAT -->
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;">
    <a href="<?php echo e(route('pelanggan.cari-jasa', array_filter(['q' => $q, 'lokasi' => $lokasi, 'urutkan' => $urutkan, 'lat' => $userLat, 'lng' => $userLng]))); ?>"
       class="btn-secondary btn-sm" style="border-radius:20px;text-decoration:none;<?php echo e(!$selectedCategory ? 'background:#4f2aa8;color:#fff;' : ''); ?>">
        <i class="fa-solid fa-border-all"></i> Semua
    </a>
    <?php foreach ($categories ?? [] as $cat):  ?>
    <a href="<?php echo e(route('pelanggan.cari-jasa', array_filter(['category' => $cat->id, 'q' => $q, 'lokasi' => $lokasi, 'urutkan' => $urutkan, 'lat' => $userLat, 'lng' => $userLng]))); ?>"
       class="btn-secondary btn-sm" style="border-radius:20px;text-decoration:none;<?php echo e($selectedCategory === $cat->id ? 'background:#4f2aa8;color:#fff;' : ''); ?>">
        <i class="fa-solid fa-tag"></i> <?php echo e($cat->name); ?>

    </a>
    <?php endforeach; ?>
</div>

<!-- RESULTS -->
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
    <span style="font-size:10px;color:#888;">
        <?php echo e($services->count()); ?> jasa ditemukan<?php echo e($selectedCategory ? ' pada kategori ' . ($categories->firstWhere('id', $selectedCategory)->name ?? '-') : ''); ?>.
    </span>
</div>

<div class="three-col-grid">
    <?php $__empty_1 = true; foreach ($services ?? [] as $s):  $__empty_1 = false; ?>
    <div class="service-card">
        <div class="service-card-img">
            <?php if($s->image): ?>
            <img src="<?php echo e(asset('storage/'.$s->image)); ?>" alt="<?php echo e($s->title); ?>">
            <?php else: ?>
            <div style="width:100%;height:100%;background:#f0ecff;display:grid;place-items:center;">
                <i class="fa-solid fa-briefcase" style="font-size:32px;color:#4f2aa8;"></i>
            </div>
            <?php endif; ?>
        </div>
        <div class="service-card-body">
            <span style="font-size:8px;color:#4f2aa8;font-weight:700;"><?php echo e($s->category->name ?? '-'); ?></span>
            <h4 style="margin-top:4px;"><?php echo e($s->title); ?></h4>
            <p><?php echo e($s->provider?->name); ?></p>
            <div class="service-card-meta">
                <span class="service-card-price">Rp <?php echo e(number_format($s->price, 0, ',', '.')); ?></span>
                <?php if($userLat && $userLng && !is_null($s->distance)): ?>
                <span style="font-size:9px;color:#888;"><i class="fa-solid fa-location-dot"></i> <?php echo e(number_format($s->distance, 1)); ?> km</span>
                <?php endif; ?>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:10px;">
                <a href="<?php echo e(route('pelanggan.detail-jasa', $s->id)); ?>" class="btn-primary btn-sm">Lihat Detail</a>
            </div>
        </div>
    </div>
    <?php endforeach; if ($__empty_1): ?>
    <div style="grid-column:1/-1;text-align:center;padding:40px;color:#888;">
        <i class="fa-solid fa-briefcase" style="font-size:32px;margin-bottom:12px;display:block;"></i>
        <p>Belum ada jasa yang tersedia.</p>
    </div>
    <?php endif; ?>
</div>
<?php $__sections['content'] = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/dashboard.php'; ?>
