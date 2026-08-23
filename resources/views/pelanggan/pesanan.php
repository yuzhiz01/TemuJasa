
<?php $user = auth()->user(); ?>
<?php $__sections['title'] = 'Pesanan Saya'; ?>
<?php $__sections['user-initials'] = strtoupper(substr($user->name, 0, 2)); ?>
<?php $__sections['user-name'] = $user->name; ?>
<?php $__sections['role-class'] = 'role-pelanggan'; ?>
<?php $__sections['role-label'] = 'Pelanggan'; ?>
<?php $__sections['profile-link'] = route('pelanggan.profil'); ?>
<?php $__sections['page-title'] = 'Pesanan Saya'; ?>
<?php $__sections['page-subtitle'] = 'Kelola semua pesanan jasa Anda'; ?>

<?php ob_start(); ?>
<span class="sidebar-nav-section">Menu Utama</span>
<a href="<?php echo e(route('pelanggan.dashboard')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
<a href="<?php echo e(route('pelanggan.cari-jasa')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-magnifying-glass"></i><span>Cari Jasa</span></a>
<span class="sidebar-nav-section">Aktivitas</span>
<a href="<?php echo e(route('pelanggan.pesanan')); ?>" class="sidebar-nav-item active"><i class="fa-solid fa-bag-shopping"></i><span>Pesanan Saya</span></a>
<a href="<?php echo e(route('pelanggan.chat')); ?>" class="sidebar-nav-item"><i class="fa-regular fa-comment-dots"></i><span>Chat / Pesan</span></a>
<a href="<?php echo e(route('pelanggan.review')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-star"></i><span>Review & Rating</span></a>
<span class="sidebar-nav-section">Akun</span>
<a href="<?php echo e(route('pelanggan.profil')); ?>" class="sidebar-nav-item"><i class="fa-regular fa-user"></i><span>Profil Saya</span></a>
<?php $__sections['sidebar-menu'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<?php if (session('success')): ?><div class="dash-card" style="margin-bottom:16px;color:#15803d;background:#f0fdf4;"><?php echo e(session('success')); ?></div><?php endif; ?>
<!-- TABS -->
<div style="display:flex;gap:0;border-bottom:2px solid #f0eef4;margin-bottom:16px;">
    <button onclick="switchTab(this)" class="tab-btn" style="padding:10px 16px;border:0;background:transparent;font-size:10px;font-weight:700;color:#4f2aa8;border-bottom:2px solid #4f2aa8;margin-bottom:-2px;cursor:pointer;">
        Semua <span style="background:#4f2aa8;color:#fff;padding:1px 6px;border-radius:10px;font-size:8px;margin-left:4px;"><?php echo e($countSemua); ?></span>
    </button>
    <button onclick="switchTab(this)" class="tab-btn" style="padding:10px 16px;border:0;background:transparent;font-size:10px;font-weight:500;color:#888;border-bottom:2px solid transparent;margin-bottom:-2px;cursor:pointer;">
        Menunggu <span style="background:#e2dfea;color:#888;padding:1px 6px;border-radius:10px;font-size:8px;margin-left:4px;"><?php echo e($countMenunggu); ?></span>
    </button>
    <button onclick="switchTab(this)" class="tab-btn" style="padding:10px 16px;border:0;background:transparent;font-size:10px;font-weight:500;color:#888;border-bottom:2px solid transparent;margin-bottom:-2px;cursor:pointer;">
        Berjalan <span style="background:#e2dfea;color:#888;padding:1px 6px;border-radius:10px;font-size:8px;margin-left:4px;"><?php echo e($countBerjalan); ?></span>
    </button>
    <button onclick="switchTab(this)" class="tab-btn" style="padding:10px 16px;border:0;background:transparent;font-size:10px;font-weight:500;color:#888;border-bottom:2px solid transparent;margin-bottom:-2px;cursor:pointer;">
        Selesai <span style="background:#e2dfea;color:#888;padding:1px 6px;border-radius:10px;font-size:8px;margin-left:4px;"><?php echo e($countSelesai); ?></span>
    </button>
</div>

<!-- FILTER -->
<div class="filter-row">
    <div class="dash-search-bar">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Cari pesanan...">
    </div>
    <select class="form-select" style="width:140px;">
        <option>Semua Status</option>
        <option>Menunggu</option>
        <option>Berjalan</option>
        <option>Selesai</option>
    </select>
    <select class="form-select" style="width:140px;">
        <option>7 Hari Terakhir</option>
        <option>30 Hari Terakhir</option>
        <option>3 Bulan Terakhir</option>
    <select class="form-select" style="width:140px;">
        <option>7 Hari Terakhir</option>
        <option>30 Hari Terakhir</option>
        <option>3 Bulan Terakhir</option>
    </select>
</div>

<div style="display:flex;flex-direction:column;gap:10px;">
    <?php $_fe1 = 0; foreach ($orders as $o): $_fe1++; ?>
    <div class="dash-card" style="margin-bottom:0;padding:14px;">
        <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
            <div class="stat-icon bg-primary-soft" style="width:52px;height:52px;"><i class="fa-solid fa-bag-shopping"></i></div>
            <div style="flex:1;min-width:160px;">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <strong style="font-size:11px;"><?php echo e($o->service_name); ?></strong>
                    <span class="badge badge-info"><?php echo e($o->status); ?></span>
                </div>
                <p style="font-size:9px;color:#888;margin-top:3px;"><?php echo e($o->provider_name); ?> â€¢ <?php echo e($o->created_at?->format('d M Y H:i')); ?></p>
                <p style="font-size:9px;color:#888;margin-top:2px;">ID Pesanan: <strong style="color:#4f2aa8;">#<?php echo e($o->id); ?></strong></p>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <strong style="font-size:13px;color:#4f2aa8;">Rp <?php echo e(number_format($o->total, 0, ',', '.')); ?></strong>
                <div style="display:flex;gap:6px;margin-top:8px;justify-content:flex-end;flex-wrap:wrap;">
                    <?php if ($o->status == 'Selesai'): ?>
                    <a href="<?php echo e(route('pelanggan.review')); ?>" class="btn-secondary btn-sm">Beri Review</a>
                    <button class="btn-primary btn-sm">Pesan Lagi</button>
                    <?php elseif ($o->status == 'Menunggu'): ?>
                    <a href="<?php echo e(route('pelanggan.chat')); ?>" class="btn-secondary btn-sm">Chat</a>
                    <?php else: ?>
                    <a href="<?php echo e(route('pelanggan.chat')); ?>" class="btn-secondary btn-sm">Chat</a>
                    <button class="btn-primary btn-sm">Lacak</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; if (!$_fe1): ?>
    <div class="dash-card">Belum ada pesanan. Silakan cari jasa untuk membuat pesanan pertama.</div>
    <?php endif; ?>
</div>

<div class="dash-pagination">
    <span>Menampilkan <?php echo e($orders->count()); ?> pesanan</span>
</div>

<?php $__sections['content'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<script>
function switchTab(el, tabId) {
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.style.color = '#888'; b.style.fontWeight = '500';
        b.style.borderBottomColor = 'transparent';
    });
    el.style.color = '#4f2aa8'; el.style.fontWeight = '700';
    el.style.borderBottomColor = '#4f2aa8';
}
</script>
<?php $__sections['scripts'] = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/dashboard.php'; ?>

