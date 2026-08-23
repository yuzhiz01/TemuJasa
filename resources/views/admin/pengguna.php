
<?php $__sections['title'] = 'Kelola Pengguna'; ?>
<?php $user = auth()->user(); ?>
<?php $__sections['user-initials'] = strtoupper(substr($user->name, 0, 2)); ?>
<?php $__sections['user-name'] = $user->name; ?>
<?php $__sections['role-class'] = 'role-admin'; ?>
<?php $__sections['role-label'] = 'Admin'; ?>
<?php $__sections['profile-link'] = '#'; ?>
<?php $__sections['page-title'] = 'Kelola Pengguna'; ?>
<?php $__sections['page-subtitle'] = 'Daftar seluruh pelanggan & penyedia jasa'; ?>

<?php ob_start(); ?>
<span class="sidebar-nav-section">Menu Utama</span>
<a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
<a href="<?php echo e(route('admin.pengguna')); ?>" class="sidebar-nav-item active"><i class="fa-solid fa-users"></i><span>Kelola Pengguna</span></a>
<a href="<?php echo e(route('admin.konten')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-layer-group"></i><span>Kelola Konten</span></a>
<span class="sidebar-nav-section">Laporan</span>
<a href="<?php echo e(route('admin.pesanan')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-bag-shopping"></i><span>Data Pesanan</span></a>
<a href="<?php echo e(route('admin.review')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-star"></i><span>Data Review</span></a>
<?php $__sections['sidebar-menu'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<?php if (session('success')): ?><div class="dash-card" style="margin-bottom:16px;color:#15803d;background:#f0fdf4;"><?php echo e(session('success')); ?></div><?php endif; ?>
<?php if ($errors->any()): ?><div class="dash-card" style="margin-bottom:16px;color:#b91c1c;background:#fef2f2;"><?php echo e($errors->first()); ?></div><?php endif; ?>

<!-- RINGKASAN -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon bg-primary-soft"><i class="fa-solid fa-user"></i></div>
        <div class="stat-info">
            <span>Pelanggan</span>
            <strong><?php echo e($totalCustomers); ?></strong>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-emerald-soft"><i class="fa-solid fa-store"></i></div>
        <div class="stat-info">
            <span>Penyedia Jasa</span>
            <strong><?php echo e($totalProviders); ?></strong>
        </div>
    </div>
</div>

<!-- FORM TAMBAH PENGGUNA -->
<div class="dash-card" style="margin-bottom:16px;">
    <div class="dash-card-header"><span class="dash-card-title">Tambah Pengguna Baru</span></div>
    <form method="POST" action="<?php echo e(route('admin.pengguna.store')); ?>" class="form-grid">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" autocomplete="off">
        <div class="form-group">
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-input" required>
        </div>
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" required>
        </div>
        <div class="form-group">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="pelanggan">Pelanggan</option>
                <option value="penyedia">Penyedia Jasa</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-input" required>
        </div>
        <div class="form-group"><button class="btn-primary btn-sm" type="submit">Simpan</button></div>
    </form>
</div>

<!-- FILTER -->
<div class="filter-row" style="margin-bottom:16px;">
    <form method="GET" action="<?php echo e(route('admin.pengguna')); ?>" style="display:flex;gap:10px;width:100%;">
        <div class="dash-search-bar" style="flex:1;">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="q" value="<?php echo e($filters['q'] ?? ''); ?>" placeholder="Cari nama atau email...">
        </div>
        <select name="role" class="form-select" style="width:150px;">
            <option value="">Semua Role</option>
            <option value="pelanggan" <?php echo e(($filters['role'] ?? '') === 'pelanggan' ? 'selected' : ''); ?>>Pelanggan</option>
            <option value="penyedia" <?php echo e(($filters['role'] ?? '') === 'penyedia' ? 'selected' : ''); ?>>Penyedia</option>
        </select>
        <button type="submit" class="btn-primary btn-sm">Filter</button>
    </form>
</div>

<!-- TABEL PENGGUNA -->
<div class="dash-card">
    <div class="dash-table-wrap">
        <table class="dash-table">
            <thead>
                <tr><th>Nama</th><th>Email</th><th>Role</th><th>Bergabung</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php $_fe1 = 0; foreach ($users as $u): $_fe1++; ?>
                <tr>
                    <td class="td-name"><?php echo e($u->name); ?></td>
                    <td><?php echo e($u->email); ?></td>
                    <td><span class="badge <?php echo e($u->role === 'penyedia' ? 'badge-purple' : 'badge-info'); ?>"><?php echo e(ucfirst($u->role)); ?></span></td>
                    <td style="color:#888;"><?php echo e($u->created_at?->format('d M Y')); ?></td>
                    <td>
                        <form method="POST" action="<?php echo e(route('admin.pengguna.destroy', $u->id)); ?>" onsubmit="return confirm('Hapus pengguna ini?')" style="display:inline;">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" autocomplete="off"> <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn-secondary btn-sm" style="color:#b91c1c;">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; if (!$_fe1): ?>
                <tr><td colspan="5" style="text-align:center;color:#888;padding:20px;">Tidak ada pengguna.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__sections['content'] = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/dashboard.php'; ?>

