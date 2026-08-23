
<?php $user = auth()->user(); ?>
<?php $__sections['title'] = 'Pesanan Berhasil'; ?>
<?php $__sections['user-initials'] = strtoupper(substr($user->name, 0, 2)); ?>
<?php $__sections['user-name'] = $user->name; ?>
<?php $__sections['role-class'] = 'role-pelanggan'; ?>
<?php $__sections['role-label'] = 'Pelanggan'; ?>
<?php $__sections['profile-link'] = route('pelanggan.profil'); ?>
<?php $__sections['page-title'] = 'Konfirmasi Pesanan'; ?>
<?php $__sections['page-subtitle'] = 'Pesanan Anda telah berhasil dibuat'; ?>

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
<div class="dash-card" style="max-width:520px;margin:0 auto;text-align:center;padding:40px 28px;">
    <div style="width:80px;height:80px;border-radius:50%;background:#f0fdf4;display:grid;place-items:center;margin:0 auto 18px;">
        <i class="fa-solid fa-circle-check" style="font-size:44px;color:#16a34a;"></i>
    </div>

    <h2 style="font-size:16px;font-weight:800;color:#17152b;margin-bottom:6px;">Konfirmasi Berhasil! ðŸŽ‰</h2>
    <p style="font-size:11px;color:#888;margin-bottom:22px;">
        Pesanan Anda telah dibuat dan sedang menunggu konfirmasi dari penyedia jasa.
    </p>

    <div style="background:#f5f4fa;border-radius:10px;padding:14px 16px;text-align:left;margin-bottom:22px;">
        <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:10px;color:#555;">
            <span>No. Pesanan</span>
            <strong>#<?php echo e(str_pad($order->id, 5, '0', STR_PAD_LEFT)); ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:10px;color:#555;">
            <span>Jasa</span>
            <strong style="text-align:right;"><?php echo e($order->service_name); ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:10px;color:#555;">
            <span>Penyedia</span>
            <strong><?php echo e($order->provider_name); ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:10px;color:#555;">
            <span>Total</span>
            <strong style="color:#4f2aa8;">Rp <?php echo e(number_format($order->total, 0, ',', '.')); ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:10px;color:#555;">
            <span>Status</span>
            <strong style="color:#b45309;"><?php echo e($order->status); ?></strong>
        </div>
    </div>

    <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
        <a href="<?php echo e(route('pelanggan.pesanan')); ?>" class="btn-primary" style="text-decoration:none;">
            <i class="fa-solid fa-bag-shopping"></i> Lihat Pesanan Saya
        </a>
        <a href="<?php echo e(route('pelanggan.cari-jasa')); ?>" class="btn-secondary" style="text-decoration:none;">
            <i class="fa-solid fa-magnifying-glass"></i> Cari Jasa Lagi
        </a>
    </div>
</div>
<?php $__sections['content'] = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/dashboard.php'; ?>

