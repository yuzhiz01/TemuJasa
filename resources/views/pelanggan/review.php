
<?php $__sections['title'] = 'Review & Rating'; ?>
<?php $user = auth()->user(); ?>
<?php $__sections['user-initials'] = strtoupper(substr($user->name, 0, 2)); ?>
<?php $__sections['user-name'] = $user->name; ?>
<?php $__sections['role-class'] = 'role-pelanggan'; ?>
<?php $__sections['role-label'] = 'Pelanggan'; ?>
<?php $__sections['profile-link'] = route('pelanggan.profil'); ?>
<?php $__sections['page-title'] = 'Review & Rating'; ?>
<?php $__sections['page-subtitle'] = 'Berikan ulasan untuk jasa yang telah Anda gunakan'; ?>

<?php ob_start(); ?>
<span class="sidebar-nav-section">Menu Utama</span>
<a href="<?php echo e(route('pelanggan.dashboard')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
<a href="<?php echo e(route('pelanggan.cari-jasa')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-magnifying-glass"></i><span>Cari Jasa</span></a>
<span class="sidebar-nav-section">Aktivitas</span>
<a href="<?php echo e(route('pelanggan.pesanan')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-bag-shopping"></i><span>Pesanan Saya</span></a>
<a href="<?php echo e(route('pelanggan.chat')); ?>" class="sidebar-nav-item"><i class="fa-regular fa-comment-dots"></i><span>Chat / Pesan</span></a>
<a href="<?php echo e(route('pelanggan.review')); ?>" class="sidebar-nav-item active"><i class="fa-solid fa-star"></i><span>Review & Rating</span></a>
<span class="sidebar-nav-section">Akun</span>
<a href="<?php echo e(route('pelanggan.profil')); ?>" class="sidebar-nav-item"><i class="fa-regular fa-user"></i><span>Profil Saya</span></a>
<?php $__sections['sidebar-menu'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<div class="two-col-grid">
    <!-- PESANAN SELESAI BELUM DIREVIEW -->
    <div class="dash-card">
        <div class="dash-card-header">
            <span class="dash-card-title"><i class="fa-regular fa-clock" style="color:#f59e0b;margin-right:6px;"></i>Menunggu Review</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;">
            <?php $_fe1 = 0; foreach ($pendingReview as $o): $_fe1++; ?>
            <div style="display:flex;align-items:center;gap:12px;padding:10px;border:1px solid #f0eef4;border-radius:8px;">
                <div style="width:40px;height:40px;border-radius:8px;background:#f0ecff;display:grid;place-items:center;flex-shrink:0;">
                    <i class="fa-solid fa-bag-shopping" style="color:#4f2aa8;"></i>
                </div>
                <div style="flex:1;">
                    <strong style="font-size:10px;"><?php echo e($o->service_name); ?></strong>
                    <p style="font-size:9px;color:#888;margin-top:2px;"><?php echo e($o->provider_name); ?> â€¢ <?php echo e($o->created_at?->format('d M Y')); ?></p>
                </div>
                <button class="btn-primary btn-sm" onclick="openReviewModal(<?php echo e($o->id); ?>, '<?php echo e(addslashes($o->service_name)); ?>', '<?php echo e(addslashes($o->provider_name)); ?>')">
                    Beri Review
                </button>
            </div>
            <?php endforeach; if (!$_fe1): ?>
            <p style="font-size:10px;color:#888;">Tidak ada pesanan yang menunggu review.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- REVIEW YANG SUDAH DIBERIKAN -->
    <div class="dash-card">
        <div class="dash-card-header">
            <span class="dash-card-title"><i class="fa-solid fa-star" style="color:#e3a72d;margin-right:6px;"></i>Review Saya</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;">
            <?php $_fe2 = 0; foreach ($myReviews as $r): $_fe2++; ?>
            <div style="padding:10px;border:1px solid #f0eef4;border-radius:8px;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <strong style="font-size:10px;"><?php echo e($r->order?->service_name); ?></strong>
                        <p style="font-size:9px;color:#888;margin-top:2px;"><?php echo e($r->order?->provider_name); ?></p>
                    </div>
                    <div style="color:#e3a72d;font-size:10px;">
                        <?php for ($i=0;$i<$r->rating;$i++): ?><i class="fa-solid fa-star"></i><?php endfor; ?>
                    </div>
                </div>
                <p style="font-size:9px;color:#555;margin-top:6px;line-height:1.5;"><?php echo e($r->body); ?></p>
                <small style="font-size:8px;color:#aaa;"><?php echo e($r->created_at?->format('d M Y')); ?></small>
            </div>

<!-- MODAL REVIEW -->
<div class="dash-modal-overlay" id="reviewModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:999;align-items:center;justify-content:center;">
    <div class="dash-modal" style="background:#fff;border-radius:12px;padding:24px;width:100%;max-width:440px;margin:16px;">
        <div class="dash-modal-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <h3 style="font-size:13px;">Beri Review</h3>
            <button class="btn-secondary btn-sm btn-icon" onclick="document.getElementById('reviewModal').style.display='none'"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" action="<?php echo e(route('pelanggan.review.store')); ?>">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" autocomplete="off">
            <input type="hidden" name="order_id" id="reviewOrderId">
            <input type="hidden" name="provider_id" id="reviewProviderId">
            <input type="hidden" name="rating" id="reviewRating" value="5">
            <div style="padding:12px;background:#f5f4fa;border-radius:8px;margin-bottom:16px;">
                <strong style="font-size:11px;" id="reviewServiceName"></strong>
                <p style="font-size:9px;color:#888;margin-top:2px;" id="reviewProviderName"></p>
            </div>
            <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label">Rating</label>
                <div style="display:flex;gap:6px;font-size:24px;" id="starRating">
                    <?php for ($i=1;$i<=5;$i++): ?>
                    <i class="fa-solid fa-star" style="cursor:pointer;color:#e3a72d;" onclick="setRating(<?php echo e($i); ?>)"></i>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Ulasan</label>
                <textarea name="body" class="form-textarea" placeholder="Ceritakan pengalaman Anda..." rows="4"></textarea>
            </div>
            <div style="display:flex;gap:8px;margin-top:16px;">
                <button type="button" class="btn-secondary" onclick="document.getElementById('reviewModal').style.display='none'">Batal</button>
                <button type="submit" class="btn-primary"><i class="fa-solid fa-paper-plane"></i> Kirim Review</button>
            </div>
        </form>
    </div>
</div>
<?php $__sections['content'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<script>
function setRating(n) {
    document.getElementById('reviewRating').value = n;
    document.querySelectorAll('#starRating i').forEach((s,i) => s.style.color = i < n ? '#e3a72d' : '#e2dfea');
}
function openReviewModal(orderId, serviceName, providerName) {
    document.getElementById('reviewOrderId').value = orderId;
    document.getElementById('reviewServiceName').textContent = serviceName;
    document.getElementById('reviewProviderName').textContent = providerName;
    document.getElementById('reviewModal').style.display = 'flex';
}
</script>
<?php $__sections['scripts'] = ob_get_clean(); ?>

            <?php endforeach; if (!$_fe2): ?>
            <p style="font-size:10px;color:#888;">Belum ada review yang diberikan.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/dashboard.php'; ?>

