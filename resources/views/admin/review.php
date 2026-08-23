
<?php $__sections['title'] = 'Data Review'; ?>
<?php $user = auth()->user(); ?>
<?php $__sections['user-initials'] = strtoupper(substr($user->name, 0, 2)); ?>
<?php $__sections['user-name'] = $user->name; ?>
<?php $__sections['role-class'] = 'role-admin'; ?>
<?php $__sections['role-label'] = 'Admin'; ?>
<?php $__sections['profile-link'] = '#'; ?>
<?php $__sections['page-title'] = 'Data Review'; ?>
<?php $__sections['page-subtitle'] = 'Rekap ulasan & rating pada platform'; ?>

<?php ob_start(); ?>
<span class="sidebar-nav-section">Menu Utama</span>
<a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
<a href="<?php echo e(route('admin.pengguna')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-users"></i><span>Kelola Pengguna</span></a>
<a href="<?php echo e(route('admin.konten')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-layer-group"></i><span>Kelola Konten</span></a>
<span class="sidebar-nav-section">Laporan</span>
<a href="<?php echo e(route('admin.pesanan')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-bag-shopping"></i><span>Data Pesanan</span></a>
<a href="<?php echo e(route('admin.review')); ?>" class="sidebar-nav-item active"><i class="fa-solid fa-star"></i><span>Data Review</span></a>
<?php $__sections['sidebar-menu'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<div class="dash-card">
    <p style="font-size:11px;color:#888;margin-bottom:14px;">
        Halaman rekapitulasi data review & rating. Gunakan halaman ini untuk memantau kualitas layanan yang diberikan penyedia jasa.
    </p>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>
</div>
<?php $__sections['content'] = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/dashboard.php'; ?>

