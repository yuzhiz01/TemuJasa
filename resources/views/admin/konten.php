
<?php $__sections['title'] = 'Kelola Konten'; ?>
<?php $user = auth()->user(); ?>
<?php $__sections['user-initials'] = strtoupper(substr($user->name, 0, 2)); ?>
<?php $__sections['user-name'] = $user->name; ?>
<?php $__sections['role-class'] = 'role-admin'; ?>
<?php $__sections['role-label'] = 'Admin'; ?>
<?php $__sections['profile-link'] = '#'; ?>
<?php $__sections['page-title'] = 'Kelola Konten'; ?>
<?php $__sections['page-subtitle'] = 'Kelola kategori & jasa pada platform'; ?>

<?php ob_start(); ?>
<span class="sidebar-nav-section">Menu Utama</span>
<a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
<a href="<?php echo e(route('admin.pengguna')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-users"></i><span>Kelola Pengguna</span></a>
<a href="<?php echo e(route('admin.konten')); ?>" class="sidebar-nav-item active"><i class="fa-solid fa-layer-group"></i><span>Kelola Konten</span></a>
<span class="sidebar-nav-section">Laporan</span>
<a href="<?php echo e(route('admin.pesanan')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-bag-shopping"></i><span>Data Pesanan</span></a>
<a href="<?php echo e(route('admin.review')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-star"></i><span>Data Review</span></a>
<?php $__sections['sidebar-menu'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<?php if (session('success')): ?><div class="dash-card" style="margin-bottom:16px;color:#15803d;background:#f0fdf4;"><?php echo e(session('success')); ?></div><?php endif; ?>
<?php if ($errors->any()): ?><div class="dash-card" style="margin-bottom:16px;color:#b91c1c;background:#fef2f2;"><?php echo e($errors->first()); ?></div><?php endif; ?>

<div class="two-col-grid">
    <!-- KATEGORI -->
    <div class="dash-card">
        <div class="dash-card-header"><span class="dash-card-title">Kategori</span></div>
        <form method="POST" action="<?php echo e(route('admin.kategori.store')); ?>" style="display:flex;gap:8px;margin-bottom:14px;">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" autocomplete="off">
            <input type="text" name="name" class="form-input" placeholder="Nama kategori baru..." required>
            <button type="submit" class="btn-primary btn-sm">Tambah</button>
        </form>
        <div style="display:flex;flex-direction:column;gap:8px;">
            <?php $_fe1 = 0; foreach ($categories as $cat): $_fe1++; ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 10px;border:1px solid #f0eef4;border-radius:8px;">
                <span style="font-size:10px;font-weight:700;"><?php echo e($cat->name); ?></span>
                <div style="display:flex;gap:6px;align-items:center;">
                    <form method="POST" action="<?php echo e(route('admin.kategori.update', $cat->id)); ?>" style="display:flex;gap:4px;">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" autocomplete="off"> <input type="hidden" name="_method" value="PUT">
                        <input type="text" name="name" value="<?php echo e($cat->name); ?>" class="form-input" style="width:120px;padding:4px 8px;font-size:9px;">
                        <button type="submit" class="btn-secondary btn-sm">Ubah</button>
                    </form>
                    <form method="POST" action="<?php echo e(route('admin.kategori.destroy', $cat->id)); ?>" onsubmit="return confirm('Hapus kategori ini?')">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" autocomplete="off"> <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn-secondary btn-sm" style="color:#b91c1c;">Hapus</button>
                    </form>
                </div>
            </div>
            <?php endforeach; if (!$_fe1): ?>
            <p style="font-size:10px;color:#888;">Belum ada kategori.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- JASA -->
    <div class="dash-card">
        <div class="dash-card-header"><span class="dash-card-title">Semua Jasa</span></div>
        <div style="display:flex;flex-direction:column;gap:8px;">
            <?php $_fe2 = 0; foreach ($services as $s): $_fe2++; ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 10px;border:1px solid #f0eef4;border-radius:8px;">
                <div>
                    <div style="font-size:10px;font-weight:700;"><?php echo e($s->title); ?></div>
                    <div style="font-size:9px;color:#888;">Rp <?php echo e(number_format($s->price, 0, ',', '.')); ?> â€¢ <?php echo e($s->provider?->name ?? '-'); ?></div>
                </div>
                <div style="display:flex;gap:6px;">
                    <form method="POST" action="<?php echo e(route('admin.jasa.toggle', $s->id)); ?>">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" autocomplete="off"> <input type="hidden" name="_method" value="PATCH">
                        <button type="submit" class="btn-secondary btn-sm"><?php echo e($s->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?></button>
                    </form>
                    <form method="POST" action="<?php echo e(route('admin.jasa.destroy', $s->id)); ?>" onsubmit="return confirm('Hapus jasa ini?')">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" autocomplete="off"> <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn-secondary btn-sm" style="color:#b91c1c;">Hapus</button>
                    </form>
                </div>
            </div>
            <?php endforeach; if (!$_fe2): ?>
            <p style="font-size:10px;color:#888;">Belum ada jasa.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__sections['content'] = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/dashboard.php'; ?>

