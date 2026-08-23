
<?php $__sections['title'] = 'Dashboard Admin'; ?>
<?php $user = auth()->user(); ?>
<?php $__sections['user-initials'] = strtoupper(substr($user->name, 0, 2)); ?>
<?php $__sections['user-name'] = $user->name; ?>
<?php $__sections['role-class'] = 'role-admin'; ?>
<?php $__sections['role-label'] = 'Admin'; ?>
<?php $__sections['profile-link'] = '#'; ?>
<?php $__sections['page-title'] = 'Dashboard Admin'; ?>
<?php $__sections['page-subtitle'] = 'Ringkasan performa platform TemuJasa'; ?>

<?php ob_start(); ?>
<span class="sidebar-nav-section">Menu Utama</span>
<a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-nav-item active"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
<a href="<?php echo e(route('admin.pengguna')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-users"></i><span>Kelola Pengguna</span></a>
<a href="<?php echo e(route('admin.konten')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-layer-group"></i><span>Kelola Konten</span></a>
<span class="sidebar-nav-section">Laporan</span>
<a href="<?php echo e(route('admin.pesanan')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-bag-shopping"></i><span>Data Pesanan</span></a>
<a href="<?php echo e(route('admin.review')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-star"></i><span>Data Review</span></a>
<?php $__sections['sidebar-menu'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<!-- STAT CARDS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon bg-primary-soft"><i class="fa-solid fa-users"></i></div>
        <div class="stat-info">
            <span>Total Pengguna</span>
            <strong><?php echo e($totalUsers); ?></strong>
            <small>+<?php echo e($newUsersThisMonth); ?> bulan ini</small>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-emerald-soft"><i class="fa-solid fa-store"></i></div>
        <div class="stat-info">
            <span>Penyedia Jasa</span>
            <strong><?php echo e($totalProviders); ?></strong>
            <small>Mitra terdaftar</small>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-amber-soft"><i class="fa-solid fa-wallet"></i></div>
        <div class="stat-info">
            <span>Pendapatan Bulan Ini</span>
            <strong>Rp <?php echo e(number_format($revenueThisMonth, 0, ',', '.')); ?></strong>
            <small>Dari pesanan selesai</small>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-blue-soft"><i class="fa-solid fa-clock"></i></div>
        <div class="stat-info">
            <span>Pesanan Menunggu</span>
            <strong><?php echo e($pendingOrders); ?></strong>
            <small><?php echo e($activeOrders); ?> berjalan â€¢ <?php echo e($completedOrders); ?> selesai</small>
        </div>
    </div>
</div>

<!-- PERTUMBUHAN PENGGUNA -->
<div class="dash-card">
    <div class="dash-card-header">
        <span class="dash-card-title">Pertumbuhan Pengguna (6 Bulan Terakhir)</span>
    </div>
    <div class="dash-table-wrap">
        <table class="dash-table">
            <thead>
                <tr><th>Bulan</th><th>Pengguna Baru</th></tr>
            </thead>
            <tbody>
                <?php $_fe1 = 0; foreach ($userGrowth as $g): $_fe1++; ?>
                <tr>
                    <td><?php echo e($g->bulan); ?></td>
                    <td><strong style="color:#4f2aa8;"><?php echo e($g->total); ?></strong></td>
                </tr>
                <?php endforeach; if (!$_fe1): ?>
                <tr><td colspan="2" style="text-align:center;color:#888;padding:20px;">Belum ada data.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- RINGKASAN PESANAN -->
<div class="two-col-grid">
    <div class="dash-card">
        <div class="dash-card-header"><span class="dash-card-title">Pesanan Bulan Ini</span></div>
        <p style="font-size:24px;font-weight:800;color:#4f2aa8;"><?php echo e($ordersThisMonth); ?></p>
        <p style="font-size:10px;color:#888;">Total pesanan dibuat pada bulan berjalan</p>
    </div>
    <div class="dash-card">
        <div class="dash-card-header"><span class="dash-card-title">Status Pesanan</span></div>
        <div style="display:flex;gap:14px;flex-wrap:wrap;">
            <span class="badge badge-info">Menunggu: <?php echo e($pendingOrders); ?></span>
            <span class="badge badge-purple">Berjalan: <?php echo e($activeOrders); ?></span>
            <span class="badge badge-success">Selesai: <?php echo e($completedOrders); ?></span>
        </div>
    </div>
</div>
<?php $__sections['content'] = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/dashboard.php'; ?>

