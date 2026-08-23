<?php $__sections['title'] = 'Profil Saya'; ?>
<?php ($user = $user ?? auth()->user()); ?>
<?php $__sections['user-initials'] = strtoupper(substr($user->name, 0, 2)); ?>
<?php $__sections['user-name'] = $user->name; ?>
<?php $__sections['role-class'] = 'role-penyedia'; ?>
<?php $__sections['role-label'] = 'Penyedia Jasa'; ?>
<?php $__sections['profile-link'] = route('penyedia.profil'); ?>
<?php $__sections['page-title'] = 'Profil Saya'; ?>
<?php $__sections['page-subtitle'] = 'Kelola akun, rating, dan pendapatan Anda'; ?>

<?php ob_start(); ?>
<span class="sidebar-nav-section">Menu Utama</span>
<a href="<?php echo e(route('penyedia.dashboard')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
<a href="<?php echo e(route('penyedia.jasa-saya')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-briefcase"></i><span>Jasa Saya</span></a>
<span class="sidebar-nav-section">Aktivitas</span>
<a href="<?php echo e(route('penyedia.pesanan')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-bag-shopping"></i><span>Pesanan</span></a>
<a href="<?php echo e(route('penyedia.chat')); ?>" class="sidebar-nav-item"><i class="fa-regular fa-comment-dots"></i><span>Chat / Pesan</span></a>
<span class="sidebar-nav-section">Akun</span>
<a href="<?php echo e(route('penyedia.profil')); ?>" class="sidebar-nav-item active"><i class="fa-regular fa-user"></i><span>Profil Saya</span></a>
<?php $__sections['sidebar-menu'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<button class="btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
<?php $__sections['page-actions'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<?php if(session('success')): ?><div class="dash-card" style="margin-bottom:16px;color:#15803d;background:#f0fdf4;"><?php echo e(session('success')); ?></div><?php endif; ?>
<?php if($errors->any()): ?><div class="dash-card" style="margin-bottom:16px;color:#b91c1c;background:#fef2f2;"><?php echo e($errors->first()); ?></div><?php endif; ?>

<div class="profile-card" style="margin-bottom:16px;">
    <div class="profile-cover"></div>
    <div class="profile-card-body">
        <div class="profile-avatar-wrap">
            <div class="profile-avatar-lg"><?php echo e(strtoupper(substr($user->name, 0, 2))); ?></div>
            <div>
                <div class="profile-name"><?php echo e($user->name); ?></div>
                <div class="profile-meta">Penyedia Jasa â€¢ Bergabung sejak <?php echo e($user->created_at?->format('F Y')); ?></div>
            </div>
        </div>
        <div style="display:flex;gap:20px;flex-wrap:wrap;">
            <div style="text-align:center;padding:10px 16px;background:#f5f4fa;border-radius:8px;">
                <strong style="font-size:18px;color:#4f2aa8;"><?php echo e($completedOrders); ?></strong>
                <p style="font-size:9px;color:#888;margin-top:2px;">Pesanan Selesai</p>
            </div>
            <div style="text-align:center;padding:10px 16px;background:#f5f4fa;border-radius:8px;">
                <strong style="font-size:18px;color:#22c55e;">Rp <?php echo e(number_format($revenueThisMonth, 0, ',', '.')); ?></strong>
                <p style="font-size:9px;color:#888;margin-top:2px;">Pendapatan Bulan Ini</p>
            </div>
        </div>
    </div>
</div>

<!-- ULASAN TERBARU -->
<div class="dash-card" style="margin-bottom:16px;">
    <div class="dash-card-header"><span class="dash-card-title">Ulasan Terbaru</span></div>
    <div style="display:flex;flex-direction:column;gap:10px;">
        <?php $__empty_1 = true; foreach ($latestReviews as $r):  $__empty_1 = false; ?>
        <div style="padding:10px 0;border-bottom:1px solid #f0eef4;">
            <div style="display:flex;justify-content:space-between;">
                <strong style="font-size:11px;"><?php echo e($r->customer?->name); ?></strong>
                <span style="color:#e3a72d;font-size:10px;">
                    <?php for($i=0;$i<5;$i++): ?><i class="fa-solid fa-star" style="opacity:<?php echo e($i < $r->rating ? 1 : 0.25); ?>;"></i><?php endfor; ?>
                </span>
            </div>
            <p style="font-size:10px;color:#888;margin-top:4px;"><?php echo e($r->body); ?></p>
        </div>
        <?php endforeach; if ($__empty_1): ?>
        <p style="font-size:10px;color:#888;">Belum ada ulasan.</p>
        <?php endif; ?>
    </div>
</div>

<!-- RIWAYAT PENDAPATAN -->
<div class="dash-card" style="margin-bottom:16px;">
    <div class="dash-card-header"><span class="dash-card-title">Riwayat Pendapatan</span></div>
    <div style="display:flex;flex-direction:column;gap:10px;">
        <?php $__empty_1 = true; foreach ($latestOrders as $o):  $__empty_1 = false; ?>
        <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0eef4;">
            <div>
                <strong style="font-size:11px;"><?php echo e($o->service_name); ?> - <?php echo e($o->customer?->name); ?></strong>
                <p style="font-size:10px;color:#888;margin-top:2px;"><?php echo e($o->created_at?->format('d M Y')); ?></p>
            </div>
            <strong style="font-size:11px;color:#22c55e;">+Rp <?php echo e(number_format($o->total, 0, ',', '.')); ?></strong>
        </div>
        <?php endforeach; if ($__empty_1): ?>
        <p style="font-size:10px;color:#888;">Belum ada riwayat pendapatan.</p>
        <?php endif; ?>
    </div>
</div>

<!-- INFO AKUN & PENGATURAN -->
<div class="two-col-grid">
    <div class="dash-card">
        <div class="dash-card-header"><span class="dash-card-title">Informasi Usaha</span></div>
        <form method="POST" action="<?php echo e(route('penyedia.profil.update')); ?>" class="form-grid">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="form-group">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-input" value="<?php echo e(old('name', $user->name)); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" value="<?php echo e(old('email', $user->email)); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nomor WhatsApp</label>
                <input type="tel" name="phone" class="form-input" value="<?php echo e(old('phone', $user->phone)); ?>">
            </div>
            <div class="form-group"><button class="btn-primary btn-sm" type="submit">Simpan Perubahan</button></div>
        </form>
    </div>

    <div class="dash-card">
        <div class="dash-card-header"><span class="dash-card-title">Keamanan Akun</span></div>
        <form method="POST" action="<?php echo e(route('penyedia.password.update')); ?>" class="form-grid" style="grid-template-columns:1fr;">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="form-group">
                <label class="form-label">Password Saat Ini</label>
                <input type="password" name="current_password" class="form-input" placeholder="Masukkan password lama" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-input" placeholder="Min. 6 karakter" required>
            </div>
            <div class="form-group"><label class="form-label">Konfirmasi Password</label><input type="password" name="password_confirmation" class="form-input" required></div>
            <button class="btn-primary btn-sm" type="submit" style="width:fit-content;">Ubah Password</button>
        </form>
    </div>
</div>

<?php $__sections['content'] = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/dashboard.php'; ?>
