<?php ($user = auth()->user()); ?>
<?php $__sections['title'] = 'Dashboard Penyedia'; ?>
<?php $__sections['user-initials'] = strtoupper(substr($user->name, 0, 2)); ?>
<?php $__sections['user-name'] = $user->name; ?>
<?php $__sections['role-class'] = 'role-penyedia'; ?>
<?php $__sections['role-label'] = 'Penyedia Jasa'; ?>
<?php $__sections['profile-link'] = route('penyedia.profil'); ?>
<?php $__sections['page-title'] = 'Dashboard'; ?>
<?php $__sections['page-subtitle'] = 'Selamat datang kembali, ' . $user->name; ?>

<?php ob_start(); ?>
<span class="sidebar-nav-section">Menu Utama</span>
<a href="<?php echo e(route('penyedia.dashboard')); ?>" class="sidebar-nav-item active"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
<a href="<?php echo e(route('penyedia.jasa-saya')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-briefcase"></i><span>Jasa Saya</span></a>
<span class="sidebar-nav-section">Aktivitas</span>
<a href="<?php echo e(route('penyedia.pesanan')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-bag-shopping"></i><span>Pesanan</span></a>
<a href="<?php echo e(route('penyedia.chat')); ?>" class="sidebar-nav-item"><i class="fa-regular fa-comment-dots"></i><span>Chat / Pesan</span></a>
<span class="sidebar-nav-section">Akun</span>
<a href="<?php echo e(route('penyedia.profil')); ?>" class="sidebar-nav-item"><i class="fa-regular fa-user"></i><span>Profil Saya</span></a>
<?php $__sections['sidebar-menu'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<a href="<?php echo e(route('penyedia.jasa-saya')); ?>" class="btn-primary"><i class="fa-solid fa-plus"></i> Tambah Jasa</a>
<?php $__sections['page-actions'] = ob_get_clean(); ?>

<?php ob_start(); ?>

<!-- STAT CARDS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon bg-primary-soft"><i class="fa-solid fa-bag-shopping"></i></div>
        <div class="stat-info">
            <span>Pesanan Masuk</span>
            <strong><?php echo e(isset($orders) ? $orders->count() : 0); ?></strong>
            <small>Pesanan masuk dari database</small>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-blue-soft"><i class="fa-solid fa-spinner"></i></div>
        <div class="stat-info">
            <span>Sedang Dikerjakan</span>
            <strong><?php echo e(isset($orders) ? $orders->where('status', 'Berjalan')->count() : 0); ?></strong>
            <small>Pesanan sedang berjalan</small>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-emerald-soft"><i class="fa-solid fa-wallet"></i></div>
        <div class="stat-info">
            <span>Pendapatan Bulan Ini</span>
            <strong>Rp <?php echo e(number_format(isset($orders) ? $orders->where('status', 'Selesai')->sum('total') : 0, 0, ',', '.')); ?></strong>
            <small>Total pesanan selesai</small>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-amber-soft"><i class="fa-solid fa-star"></i></div>
        <div class="stat-info">
            <span>Rating Rata-rata</span>
            <strong>0</strong>
            <small>Belum ada ulasan</small>
        </div>
    </div>
</div>

<div class="two-col-grid">
    <div class="dash-card">
        <div class="dash-card-header">
            <span class="dash-card-title">Pesanan Terbaru</span>
            <a href="<?php echo e(route('penyedia.pesanan')); ?>" style="font-size:10px;color:#4f2aa8;">Lihat semua</a>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;">
            <?php $__empty_1 = true; foreach ($orders ?? [] as $o):  $__empty_1 = false; ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #f0eef4;">
                <div>
                    <strong style="font-size:11px;"><?php echo e($o->customer?->name ?? 'Pelanggan'); ?></strong>
                    <p style="font-size:10px;color:#888;margin-top:2px;"><?php echo e($o->service_name); ?></p>
                </div>
                <span class="status-badge"><?php echo e($o->status); ?></span>
            </div>
            <?php endforeach; if ($__empty_1): ?>
            <p style="font-size:10px;color:#888;">Belum ada pesanan masuk.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="dash-card">
        <div class="dash-card-header"><span class="dash-card-title">Jasa Paling Laris</span></div>
        <div style="display:flex;flex-direction:column;gap:10px;">
            <?php $__empty_1 = true; foreach ($topServices ?? [] as $j):  $__empty_1 = false; ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #f0eef4;">
                <strong style="font-size:11px;"><?php echo e($j->service_name); ?></strong>
                <span style="font-size:10px;color:#888;"><?php echo e($j->total); ?> pesanan</span>
            </div>
            <?php endforeach; if ($__empty_1): ?>
            <p style="font-size:10px;color:#888;">Belum ada data.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__sections['content'] = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/dashboard.php'; ?>
