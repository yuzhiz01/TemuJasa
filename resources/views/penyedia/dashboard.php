
<?php $__sections['title'] = 'Dashboard Penyedia'; ?>
<?php $user = auth()->user(); ?>
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
<a href="<?php echo e(route('penyedia.jasa-saya')); ?>" class="btn-primary"><i class="fa-solid fa-plus"></i> Kelola Jasa</a>
<?php $__sections['page-actions'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<!-- STAT CARDS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon bg-primary-soft"><i class="fa-solid fa-bag-shopping"></i></div>
        <div class="stat-info">
            <span>Pesanan Terbaru</span>
            <strong><?php echo e($orders->count()); ?></strong>
            <small>Seluruh pengguna terdaftar</small>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-emerald-soft"><i class="fa-solid fa-fire"></i></div>
        <div class="stat-info">
            <span>Jasa Terlaris</span>
            <strong><?php echo e($topServices->first()->total ?? 0); ?></strong>
            <small>Kategori jasa paling sering dipesan</small>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-amber-soft"><i class="fa-solid fa-clock"></i></div>
        <div class="stat-info">
            <span>Pesanan Masuk</span>
            <strong><?php echo e($orders->count()); ?></strong>
            <small>5 pesanan terbaru ditampilkan</small>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-blue-soft"><i class="fa-solid fa-fire"></i></div>
        <div class="stat-info">
            <span>Jasa Terlaris</span>
            <strong><?php echo e($topServices->first()->total ?? 0); ?></strong>
            <small><?php echo e($topServices->first()->service_name ?? 'Belum ada data'); ?></small>
        </div>
    </div>
</div>

<div class="two-col-grid">
    <!-- PESANAN TERBARU -->
    <div class="dash-card">
        <div class="dash-card-header">
            <span class="dash-card-title"><i class="fa-solid fa-bag-shopping" style="color:#4f2aa8;margin-right:6px;"></i>Pesanan Terbaru</span>
            <a href="<?php echo e(route('penyedia.pesanan')); ?>" class="dash-card-action">Lihat semua</a>
        </div>
        <div style="display:flex;flex-direction:column;gap:8px;">
            <?php $_fe1 = 0; foreach ($orders as $o): $_fe1++; ?>
            <div style="display:flex;align-items:center;gap:10px;padding:8px;border-radius:8px;" onmouseover="this.style.background='#faf8ff'" onmouseout="this.style.background='transparent'">
                <div style="width:40px;height:40px;border-radius:8px;background:#f0ecff;display:grid;place-items:center;color:#4f2aa8;flex-shrink:0;">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:10px;font-weight:700;color:#17152b;"><?php echo e($o->service_name); ?></div>
                    <div style="font-size:9px;color:#888;margin-top:1px;">#<?php echo e($o->id); ?> â€¢ <?php echo e($o->created_at?->format('d M Y H:i')); ?></div>
                </div>
                <span class="badge badge-info"><?php echo e($o->status); ?></span>
            </div>
            <?php endforeach; if (!$_fe1): ?>
            <p style="font-size:10px;color:#888;">Belum ada pesanan masuk.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- JASA TERLARIS -->
    <div class="dash-card">
        <div class="dash-card-header">
            <span class="dash-card-title"><i class="fa-solid fa-trophy" style="color:#e3a72d;margin-right:6px;"></i>Jasa Terlaris</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:8px;">
            <?php $_fe2 = 0; foreach ($topServices as $t): $_fe2++; ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 10px;border:1px solid #f0eef4;border-radius:8px;">
                <span style="font-size:10px;font-weight:700;color:#17152b;"><?php echo e($t->service_name); ?></span>
                <span class="badge badge-purple"><?php echo e($t->total); ?>x dipesan</span>
            </div>
            <?php endforeach; if (!$_fe2): ?>
            <p style="font-size:10px;color:#888;">Belum ada data pemesanan.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__sections['content'] = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/dashboard.php'; ?>


