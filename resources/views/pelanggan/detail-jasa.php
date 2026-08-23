
<?php $user = auth()->user(); ?>
<?php $__sections['title'] = 'Detail Jasa'; ?>
<?php $__sections['user-initials'] = strtoupper(substr($user->name, 0, 2)); ?>
<?php $__sections['user-name'] = $user->name; ?>
<?php $__sections['role-class'] = 'role-pelanggan'; ?>
<?php $__sections['role-label'] = 'Pelanggan'; ?>
<?php $__sections['profile-link'] = route('pelanggan.profil'); ?>
<?php $__sections['page-title'] = 'Detail Jasa'; ?>
<?php $__sections['page-subtitle'] = 'Informasi lengkap tentang jasa ini'; ?>

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
<a href="<?php echo e(route('pelanggan.cari-jasa')); ?>" class="btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
<?php if ($service): ?>
<a href="<?php echo e(route('pelanggan.pesan-jasa', $service->id)); ?>" class="btn-primary" id="btnPesanTop"><i class="fa-solid fa-bag-shopping"></i> Pesan Sekarang</a>
<?php endif; ?>
<?php $__sections['page-actions'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<?php if (!$service): ?>
<div class="dash-card" style="text-align:center;padding:40px;color:#888;">
    <i class="fa-solid fa-briefcase" style="font-size:36px;display:block;margin-bottom:12px;color:#d1d5db;"></i>
    <p>Jasa tidak ditemukan.</p>
    <a href="<?php echo e(route('pelanggan.cari-jasa')); ?>" class="btn-primary btn-sm" style="margin-top:12px;">Cari Jasa Lain</a>
</div>
<?php else: ?>
<div class="sidebar-content-grid">
    <div style="grid-column:1/-1;">
        <div class="dash-card" style="padding:0;overflow:hidden;">
            <?php if ($service->image): ?>
            <img src="<?php echo e(asset('storage/'.$service->image)); ?>" style="width:100%;height:220px;object-fit:cover;">
            <?php else: ?>
            <div style="width:100%;height:220px;background:#f0ecff;display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid fa-image" style="font-size:48px;color:#c4b5fd;"></i>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div style="grid-column:1/2;">
        <div class="dash-card">
            <div style="margin-bottom:16px;">
                <?php if ($service->category): ?>
                <span class="badge badge-purple"><?php echo e($service->category->name); ?></span>
                <?php endif; ?>
                <h2 style="font-size:16px;font-weight:800;margin-top:6px;"><?php echo e($service->title); ?></h2>
                <p style="font-size:10px;color:#888;margin-top:4px;">
                    <i class="fa-solid fa-store" style="color:#4f2aa8;"></i> <?php echo e($service->shop_name); ?>
                    &nbsp;â€¢&nbsp; Oleh <strong style="color:#4f2aa8;"><?php echo e($service->provider?->name); ?></strong>
                </p>
                <?php if ($service->location): ?>
                <p style="font-size:10px;color:#888;margin-top:4px;">
                    <i class="fa-solid fa-location-dot" style="color:#4f2aa8;"></i> <?php echo e($service->location); ?>
                </p>
                <?php endif; ?>
            </div>

            <?php if ($service->description): ?>
            <div style="border-top:1px solid #f0eef4;padding-top:14px;margin-top:14px;">
                <h4 style="font-size:11px;font-weight:700;margin-bottom:8px;">Deskripsi Jasa</h4>
                <p style="font-size:10px;color:#555;line-height:1.7;white-space:pre-line;"><?php echo e($service->description); ?></p>
            </div>
            <?php endif; ?>

            <?php if ($service->options->isNotEmpty()): ?>
            <div style="border-top:1px solid #f0eef4;padding-top:14px;margin-top:14px;">
                <h4 style="font-size:11px;font-weight:700;margin-bottom:10px;">Pilihan Paket</h4>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <?php foreach ($service->options as $opt): ?>
                    <label id="label-opt-<?php echo e($opt->id); ?>" style="display:flex;justify-content:space-between;align-items:center;padding:10px 12px;border:1.5px solid #e5e7eb;border-radius:8px;cursor:pointer;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <input type="radio" name="opt_preview" value="<?php echo e($opt->id); ?>" onchange="selectOption(this, <?php echo e($opt->price); ?>)">

                            <?php if ($opt->description): ?>
                            <p style="font-size:9px;color:#888;margin:2px 0 0;"><?php echo e($opt->description); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <span style="font-size:11px;font-weight:800;color:#4f2aa8;white-space:nowrap;margin-left:8px;">Rp <?php echo e(number_format($opt->price, 0, ',', '.')); ?></span>
                </label>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <div style="border-top:1px solid #f0eef4;padding-top:14px;margin-top:14px;">
                <h4 style="font-size:11px;font-weight:700;margin-bottom:10px;">Ulasan Pelanggan</h4>
                <?php $_fe1 = 0; foreach ($reviews as $r): $_fe1++; ?>
                <div style="display:flex;gap:10px;padding:10px 0;border-bottom:1px solid #f5f4f7;">
                    <div class="td-avatar" style="flex-shrink:0;"><?php echo e(strtoupper(substr($r->customer?->name ?? 'U', 0, 2))); ?></div>
                    <div>
                        <strong style="font-size:10px;"><?php echo e($r->customer?->name); ?></strong>
                        <div style="color:#e3a72d;font-size:9px;margin:2px 0;">
                            <?php for ($i=0;$i<$r->rating;$i++): ?><i class="fa-solid fa-star"></i><?php endfor; ?>
                        </div>
                        <p style="font-size:10px;color:#555;"><?php echo e($r->body); ?></p>
                        <small style="font-size:8px;color:#aaa;"><?php echo e($r->created_at?->format('d M Y')); ?></small>
                    </div>
                </div>
                <?php endforeach; if (!$_fe1): ?>
                <p style="font-size:10px;color:#888;">Belum ada ulasan untuk jasa ini.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div>
        <div class="dash-card" style="position:sticky;top:70px;">
            <h4 style="font-size:12px;font-weight:700;margin-bottom:14px;">Pesan Jasa Ini</h4>
            <div style="font-size:22px;font-weight:800;color:#4f2aa8;margin-bottom:4px;" id="displayPrice">
                Rp <?php echo e(number_format($service->price, 0, ',', '.')); ?>
            </div>
            <p style="font-size:9px;color:#888;margin-bottom:16px;">Harga mulai dari</p>
            <a href="<?php echo e(route('pelanggan.pesan-jasa', $service->id)); ?>" class="btn-primary" style="width:100%;justify-content:center;" id="btnPesan">
                <i class="fa-solid fa-bag-shopping"></i> Pesan Sekarang
            </a>
            <a href="<?php echo e(route('pelanggan.chat')); ?>" class="btn-secondary" style="width:100%;justify-content:center;margin-top:8px;">
                <i class="fa-regular fa-comment-dots"></i> Chat Penyedia
            </a>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__sections['content'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<?php if ($service): ?>
<script>
const baseUrl = '<?php echo e(route("pelanggan.pesan-jasa", $service->id)); ?>';
function selectOption(radio, price) {
    document.querySelectorAll('[id^="label-opt-"]').forEach(el => {
        el.style.borderColor = '#e5e7eb';
        el.style.background = '';
    });
    const label = document.getElementById('label-opt-' + radio.value);
    if (label) { label.style.borderColor = '#4f2aa8'; label.style.background = '#f5f3ff'; }
    document.getElementById('displayPrice').textContent = 'Rp ' + Number(price).toLocaleString('id-ID');
    const url = baseUrl + '?option=' + radio.value;
    document.getElementById('btnPesan').href = url;
    const btnTop = document.getElementById('btnPesanTop');
    if (btnTop) btnTop.href = url;
}
</script>
<?php endif; ?>
<?php $__sections['scripts'] = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/dashboard.php'; ?>

