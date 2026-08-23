<?php $__sections['title'] = 'Profil Penyedia Jasa - ' . $provider->name; ?>

<?php ob_start(); ?>
<section class="section-container">
    <!-- HEADER PROFIL -->
    <div class="dash-card" style="display:flex;gap:20px;align-items:center;flex-wrap:wrap;padding:24px;">
        <div style="width:80px;height:80px;border-radius:50%;background:#f0ecff;display:grid;place-items:center;flex-shrink:0;">
            <i class="fa-solid fa-user-tie" style="font-size:36px;color:#4f2aa8;"></i>
        </div>
        <div style="flex:1;min-width:220px;">
            <h1 style="font-size:18px;font-weight:800;color:#17152b;"><?php echo e($provider->name); ?></h1>
            <p style="font-size:11px;color:#888;margin-top:2px;">
                Penyedia Jasa • Bergabung <?php echo e($provider->created_at?->format('M Y')); ?>

                <?php if($provider->location ?? false): ?> • <?php echo e($provider->address); ?><?php endif; ?>
            </p>
            <?php if($provider->bio): ?>
            <p style="font-size:11px;color:#555;margin-top:8px;line-height:1.6;"><?php echo e($provider->bio); ?></p>
            <?php endif; ?>
        </div>
        <div style="display:flex;gap:14px;text-align:center;">
            <div style="padding:10px 16px;background:#f5f4fa;border-radius:10px;">
                <strong style="font-size:14px;color:#4f2aa8;"><?php echo e($totalPesanan); ?></strong>
                <div style="font-size:9px;color:#888;">Pesanan</div>
            </div>
            <div style="padding:10px 16px;background:#f5f4fa;border-radius:10px;">
                <strong style="font-size:14px;color:#e3a72d;"><?php echo e($avgRating ?: '-'); ?> <i class="fa-solid fa-star" style="font-size:10px;"></i></strong>
                <div style="font-size:9px;color:#888;"><?php echo e($totalReview); ?> ulasan</div>
            </div>
            <div style="padding:10px 16px;background:#f5f4fa;border-radius:10px;">
                <strong style="font-size:14px;color:#16a34a;"><?php echo e($services->count()); ?></strong>
                <div style="font-size:9px;color:#888;">Jasa aktif</div>
            </div>
        </div>
    </div>

    <!-- DAFTAR JASA -->
    <div class="section-heading" style="margin-top:28px;">
        <div>
            <h2>Jasa yang Ditawarkan</h2>
            <p>Semua layanan aktif dari <?php echo e($provider->name); ?></p>
        </div>
    </div>

    <div class="three-col-grid">
        <?php $__empty_1 = true; foreach ($services as $s):  $__empty_1 = false; ?>
        <div class="service-card">
            <div class="service-card-img">
                <?php if($s->image): ?>
                <img src="<?php echo e(asset('storage/'.$s->image)); ?>" alt="<?php echo e($s->title); ?>">
                <?php else: ?>
                <div style="width:100%;height:100%;background:#f0ecff;display:grid;place-items:center;">
                    <i class="fa-solid fa-briefcase" style="font-size:32px;color:#4f2aa8;"></i>
                </div>
                <?php endif; ?>
            </div>
            <div class="service-card-body">
                <span style="font-size:8px;color:#4f2aa8;font-weight:700;"><?php echo e($s->category->name ?? '-'); ?></span>
                <h4 style="margin-top:4px;"><?php echo e($s->title); ?></h4>
                <p><?php echo e($s->shop_name); ?></p>
                <div class="service-card-meta">
                    <span class="service-card-price">Rp <?php echo e(number_format($s->price, 0, ',', '.')); ?></span>
                    <?php if(!is_null($s->avg_rating) && $s->review_count > 0): ?>
                    <span style="font-size:9px;color:#888;"><i class="fa-solid fa-star" style="color:#e3a72d;"></i> <?php echo e($s->avg_rating); ?> (<?php echo e($s->review_count); ?>)</span>
                    <?php endif; ?>
                </div>
                <div style="margin-top:10px;">
                    <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('pelanggan.detail-jasa', $s->id)); ?>" class="btn-primary btn-sm">Lihat Detail</a>
                    <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn-primary btn-sm">Login untuk memesan</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; if ($__empty_1): ?>
        <div style="grid-column:1/-1;text-align:center;padding:40px;color:#888;">
            <i class="fa-solid fa-briefcase" style="font-size:32px;margin-bottom:12px;display:block;"></i>
            <p>Penyedia ini belum memiliki jasa aktif.</p>
        </div>
        <?php endif; ?>
    </div>

    <div style="text-align:center;margin-top:24px;">
        <a href="<?php echo e(route('home')); ?>" class="btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda</a>
    </div>
</section>
<?php $__sections['content'] = ob_get_clean(); ?>

<?php require __DIR__ . '/layouts/app.php'; ?>