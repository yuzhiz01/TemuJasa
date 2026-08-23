<?php ($user = auth()->user()); ?>
<?php $__sections['title'] = 'Pesanan'; ?>
<?php $__sections['user-initials'] = strtoupper(substr($user->name, 0, 2)); ?>
<?php $__sections['user-name'] = $user->name; ?>
<?php $__sections['role-class'] = 'role-penyedia'; ?>
<?php $__sections['role-label'] = 'Penyedia Jasa'; ?>
<?php $__sections['profile-link'] = route('penyedia.profil'); ?>
<?php $__sections['page-title'] = 'Pesanan'; ?>
<?php $__sections['page-subtitle'] = 'Kelola pesanan masuk, berjalan, dan selesai'; ?>

<?php ob_start(); ?>
<span class="sidebar-nav-section">Menu Utama</span>
<a href="<?php echo e(route('penyedia.dashboard')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
<a href="<?php echo e(route('penyedia.jasa-saya')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-briefcase"></i><span>Jasa Saya</span></a>
<span class="sidebar-nav-section">Aktivitas</span>
<a href="<?php echo e(route('penyedia.pesanan')); ?>" class="sidebar-nav-item active"><i class="fa-solid fa-bag-shopping"></i><span>Pesanan</span></a>
<a href="<?php echo e(route('penyedia.chat')); ?>" class="sidebar-nav-item"><i class="fa-regular fa-comment-dots"></i><span>Chat / Pesan</span></a>
<span class="sidebar-nav-section">Akun</span>
<a href="<?php echo e(route('penyedia.profil')); ?>" class="sidebar-nav-item"><i class="fa-regular fa-user"></i><span>Profil Saya</span></a>
<?php $__sections['sidebar-menu'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<?php if(session('success')): ?><div class="dash-card" style="margin-bottom:16px;color:#15803d;background:#f0fdf4;"><?php echo e(session('success')); ?></div><?php endif; ?>
<!-- TABS -->
<div style="display:flex;gap:0;border-bottom:2px solid #f0eef4;margin-bottom:16px;">
    <button onclick="switchTab(this)" class="tab-btn" style="padding:10px 16px;border:0;background:transparent;font-size:10px;font-weight:700;color:#4f2aa8;border-bottom:2px solid #4f2aa8;margin-bottom:-2px;cursor:pointer;">
        Semua <span style="background:#4f2aa8;color:#fff;padding:1px 6px;border-radius:10px;font-size:8px;margin-left:4px;"><?php echo e($countSemua); ?></span>
    </button>
    <button onclick="switchTab(this)" class="tab-btn" style="padding:10px 16px;border:0;background:transparent;font-size:10px;font-weight:500;color:#888;border-bottom:2px solid transparent;margin-bottom:-2px;cursor:pointer;">
        Berjalan <span style="background:#e2dfea;color:#888;padding:1px 6px;border-radius:10px;font-size:8px;margin-left:4px;"><?php echo e($countBerjalan); ?></span>
    </button>
    <button onclick="switchTab(this)" class="tab-btn" style="padding:10px 16px;border:0;background:transparent;font-size:10px;font-weight:500;color:#888;border-bottom:2px solid transparent;margin-bottom:-2px;cursor:pointer;">
        Selesai <span style="background:#e2dfea;color:#888;padding:1px 6px;border-radius:10px;font-size:8px;margin-left:4px;"><?php echo e($countSelesai); ?></span>
    </button>
</div>

<div style="display:flex;flex-direction:column;gap:12px;">
    <?php $__empty_1 = true; foreach ($orders ?? [] as $o):  $__empty_1 = false; ?>
    <div class="dash-card">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <strong style="font-size:12px;"><?php echo e($o->customer?->name ?? 'Pelanggan'); ?></strong>
                <p style="font-size:10px;color:#888;margin-top:2px;"><?php echo e($o->service_name); ?> â€¢ Rp <?php echo e(number_format($o->total, 0, ',', '.')); ?></p>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="status-badge"><?php echo e($o->status); ?></span>
                <a href="<?php echo e(route('penyedia.chat')); ?>" class="btn-secondary btn-sm"><i class="fa-regular fa-comment-dots"></i> Chat</a>
                <?php if($o->status !== 'Selesai'): ?>
                <form method="POST" action="<?php echo e(route('penyedia.pesanan.status', $o)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?><input type="hidden" name="status" value="<?php echo e($o->status === 'Menunggu' ? 'Berjalan' : 'Selesai'); ?>"><button class="btn-primary btn-sm" type="submit"><?php echo e($o->status === 'Menunggu' ? 'Terima' : 'Selesaikan'); ?></button></form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; if ($__empty_1): ?>
    <div class="dash-card">Belum ada pesanan masuk.</div>
    <?php endif; ?>
</div>

<div class="dash-pagination">
    <span>Menampilkan <?php echo e($orders?->count() ?? 0); ?> pesanan</span>
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
