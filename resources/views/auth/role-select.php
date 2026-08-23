<?php $__sections['title'] = 'Pilih Jenis Akun'; ?>

<?php ob_start(); ?>
<div class="auth-shell">
    <div class="auth-card auth-card-register">
        <div class="auth-brand">
            <div class="logo-icon"><i class="fa-solid fa-location-dot"></i></div>
            <span>TemuJasa</span>
        </div>

        <div class="auth-header">
            <h2 class="auth-title">Pilih Jenis Akun</h2>
            <p class="auth-subtitle">Pilih jenis akun yang ingin Anda buat</p>
        </div>

        <div class="auth-role-grid">
            <a href="<?php echo e(route('register.pelanggan')); ?>" class="auth-role-card">
                <div class="auth-role-icon"><i class="fa-solid fa-user"></i></div>
                <strong>Pelanggan</strong>
                <span>Cari dan pesan jasa sesuai kebutuhan</span>
            </a>

            <a href="<?php echo e(route('register.penyedia')); ?>" class="auth-role-card">
                <div class="auth-role-icon"><i class="fa-solid fa-screwdriver-wrench"></i></div>
                <strong>Penyedia Jasa</strong>
                <span>Tawarkan layanan dan kelola pesanan</span>
            </a>
        </div>

        <div class="auth-footer-text auth-footer-inline">
            <a href="<?php echo e(route('login')); ?>"><i class="fa-solid fa-arrow-left"></i> Kembali ke Login</a>
        </div>
    </div>
</div>
<?php $__sections['content'] = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/auth.php'; ?>