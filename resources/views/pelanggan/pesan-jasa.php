
<?php 
    $user = auth()->user();
    $selectedOption = $service ? $service->options->find(request('option')) : null;
    $displayPrice = $selectedOption ? $selectedOption->price : ($service ? $service->price : 0);
 ?>
<?php $__sections['title'] = 'Pesan Jasa'; ?>
<?php $__sections['user-initials'] = strtoupper(substr($user->name, 0, 2)); ?>
<?php $__sections['user-name'] = $user->name; ?>
<?php $__sections['role-class'] = 'role-pelanggan'; ?>
<?php $__sections['role-label'] = 'Pelanggan'; ?>
<?php $__sections['profile-link'] = route('pelanggan.profil'); ?>
<?php $__sections['page-title'] = 'Pesan Jasa'; ?>
<?php $__sections['page-subtitle'] = 'Lengkapi detail pemesanan Anda'; ?>

<?php ob_start(); ?>
<span class="sidebar-nav-section">Menu Utama</span>
<a href="<?php echo e(route('pelanggan.dashboard')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
<a href="<?php echo e(route('pelanggan.cari-jasa')); ?>" class="sidebar-nav-item active"><i class="fa-solid fa-magnifying-glass"></i><span>Cari Jasa</span></a>
<span class="sidebar-nav-section">Aktivitas</span>
<a href="<?php echo e(route('pelanggan.pesanan')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-bag-shopping"></i><span>Pesanan Saya</span></a>
<a href="<?php echo e(route('pelanggan.chat')); ?>" class="sidebar-nav-item"><i class="fa-regular fa-comment-dots"></i><span>Chat / Pesan</span></a>
<a href="<?php echo e(route('pelanggan.review')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-star"></i><span>Review & Rating</span></a>
<span class="sidebar-nav-section">Akun</span>
<a href="<?php echo e(route('pelanggan.profil')); ?>" class="sidebar-nav-item"><i class="fa-regular fa-user"></i><span>Profil Saya</span></a>
<?php $__sections['sidebar-menu'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<?php if ($service): ?>
<a href="<?php echo e(route('pelanggan.detail-jasa', $service->id)); ?>" class="btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
<?php else: ?>
<a href="<?php echo e(route('pelanggan.cari-jasa')); ?>" class="btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
<?php endif; ?>
<?php $__sections['page-actions'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<?php if (!$service): ?>
<div class="dash-card" style="text-align:center;padding:40px;color:#888;">
    <p>Jasa tidak ditemukan.</p>
</div>
<?php else: ?>
<div class="sidebar-content-grid">
    <div>
        <div class="dash-card">
            <h4 style="font-size:12px;font-weight:700;margin-bottom:16px;"><i class="fa-solid fa-map-location-dot" style="color:#4f2aa8;margin-right:6px;"></i>Alamat Layanan</h4>
            <form method="POST" action="<?php echo e(route('pelanggan.pesanan.store')); ?>" id="formPesan">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" autocomplete="off">
                <input type="hidden" name="service_id" value="<?php echo e($service->id); ?>">
                <input type="hidden" name="provider_id" value="<?php echo e($service->provider_id); ?>">
                <input type="hidden" name="service_name" value="<?php echo e($service->title); ?>">
                <input type="hidden" name="provider_name" value="<?php echo e($service->shop_name); ?>">
                <input type="hidden" name="total" id="inputTotal" value="<?php echo e($displayPrice); ?>">
                <input type="hidden" name="option_id" id="inputOptionId" value="<?php echo e($selectedOption?->id); ?>">

                <?php if ($service->options->isNotEmpty()): ?>
                <div style="margin-bottom:16px;">
                    <label class="form-label" style="margin-bottom:8px;">Pilih Paket <span style="color:red">*</span></label>
                    <?php foreach ($service->options as $opt): ?>

                            <?php if ($opt->description): ?>
                            <p style="font-size:9px;color:#888;margin:2px 0 0;"><?php echo e($opt->description); ?></p>
                            <?php endif; ?>
                        </div>
                        <span style="font-size:11px;font-weight:800;color:#4f2aa8;white-space:nowrap;margin-left:8px;">Rp <?php echo e(number_format($opt->price, 0, ',', '.')); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Alamat Layanan <span style="color:red">*</span></label>
                    <textarea name="address" class="form-textarea" placeholder="Tulis alamat lengkap tempat layanan dikerjakan..." rows="2" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">No. WhatsApp <span style="color:red">*</span></label>
                    <input type="tel" name="phone" class="form-input" placeholder="08xxxxxxxxxx" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Pelaksanaan <span style="color:red">*</span></label>
                    <input type="date" name="schedule_date" class="form-input" required>
                </div>
                <div class="form-group full">
                    <label class="form-label">Catatan Tambahan</label>
                    <textarea name="notes" class="form-textarea" placeholder="Informasi tambahan untuk penyedia jasa..." rows="3"></textarea>
                </div>
            </div>
            </form>
        </div>
    </div>

    <div>
        <div class="dash-card" style="position:sticky;top:70px;">
            <h4 style="font-size:12px;font-weight:700;margin-bottom:14px;">Ringkasan Pesanan</h4>
            <div style="display:flex;gap:10px;padding:10px;background:#f5f4fa;border-radius:8px;margin-bottom:14px;">
                <?php if ($service->image): ?>
                <img src="<?php echo e(asset('storage/'.$service->image)); ?>" style="width:48px;height:48px;border-radius:8px;object-fit:cover;">
                <?php else: ?>
                <div style="width:48px;height:48px;border-radius:8px;background:#f0ecff;display:grid;place-items:center;">
                    <i class="fa-solid fa-briefcase" style="color:#4f2aa8;"></i>
                </div>
                <?php endif; ?>
                <div>
                    <strong style="font-size:10px;"><?php echo e($service->title); ?></strong>
                    <p style="font-size:9px;color:#888;margin-top:2px;"><?php echo e($service->shop_name); ?></p>
                </div>
            </div>

            <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:10px;color:#555;border-bottom:1px solid #f5f4f7;">
                <span id="summaryName"><?php echo e($selectedOption ? $selectedOption->name : $service->title); ?></span>
                <span id="summaryPrice">Rp <?php echo e(number_format($displayPrice, 0, ',', '.')); ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:10px 0 0;font-size:12px;font-weight:800;color:#17152b;">
                <span>Total</span>
                <span style="color:#4f2aa8;" id="summaryTotal">Rp <?php echo e(number_format($displayPrice, 0, ',', '.')); ?></span>
            </div>

            <button type="submit" form="formPesan" class="btn-primary" style="width:100%;justify-content:center;margin-top:16px;">
                <i class="fa-solid fa-shield-halved"></i> Konfirmasi Pesanan
            </button>
            <p style="font-size:8px;color:#aaa;text-align:center;margin-top:8px;">Dengan memesan, Anda menyetujui syarat & ketentuan TemuJasa</p>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__sections['content'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<?php if ($service): ?>
<script>
function pickOption(id, price, name) {
    document.getElementById('inputTotal').value = price;
    document.getElementById('inputOptionId').value = id;
    document.getElementById('summaryName').textContent = name;
    const fmt = 'Rp ' + Number(price).toLocaleString('id-ID');
    document.getElementById('summaryPrice').textContent = fmt;
    document.getElementById('summaryTotal').textContent = fmt;
    document.querySelectorAll('[id^="po-"]').forEach(el => {
        el.style.borderColor = '#e5e7eb';
        el.style.background = '';
    });
    const lbl = document.getElementById('po-' + id);
    if (lbl) { lbl.style.borderColor = '#4f2aa8'; lbl.style.background = '#f5f3ff'; }
}
// highlight opsi yang sudah dipilih saat load
<?php if ($selectedOption): ?>
document.addEventListener('DOMContentLoaded', () => {
    const lbl = document.getElementById('po-<?php echo e($selectedOption->id); ?>');
    if (lbl) { lbl.style.borderColor = '#4f2aa8'; lbl.style.background = '#f5f3ff'; }
});
<?php endif; ?>
</script>
<?php endif; ?>
<?php $__sections['scripts'] = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/dashboard.php'; ?>
