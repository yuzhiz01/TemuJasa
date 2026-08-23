

<?php $__sections['title'] = 'Daftar Akun'; ?>

<?php ob_start(); ?>
<div class="auth-shell">
    <div class="auth-card auth-card-register">
        <div class="auth-brand">
            <div class="logo-icon">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <span>TemuJasa</span>
        </div>

        <div class="auth-header">
            <h2 class="auth-title">Buat Akun Baru</h2>
            <p class="auth-subtitle">Bergabung dan temukan jasa lokal terbaik</p>
        </div>

        <?php if ($errors->any()): ?>
            <div class="auth-alert" role="alert">
                <?php echo e($errors->first()); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('register.process')); ?>" class="auth-form">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" autocomplete="off">

            <div class="auth-form-group">
                <label for="name">Nama Lengkap</label>
                <input id="name" type="text" name="name" class="auth-input <?php $message = ($errors->any() && !is_null($errors->first('name'))) ? $errors->first('name') : null; if ($message): ?> is-invalid <?php endif; unset($message); ?>" placeholder="Masukkan nama lengkap" value="<?php echo e(old('name')); ?>" required>
                <?php $message = ($errors->any() && !is_null($errors->first('name'))) ? $errors->first('name') : null; if ($message): ?>
                    <span class="auth-error"><?php echo e($message); ?></span>
                <?php endif; unset($message); ?>
            </div>

            <div class="auth-form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" class="auth-input <?php $message = ($errors->any() && !is_null($errors->first('email'))) ? $errors->first('email') : null; if ($message): ?> is-invalid <?php endif; unset($message); ?>" placeholder="nama@email.com" value="<?php echo e(old('email')); ?>" required>
                <?php $message = ($errors->any() && !is_null($errors->first('email'))) ? $errors->first('email') : null; if ($message): ?>
                    <span class="auth-error"><?php echo e($message); ?></span>
                <?php endif; unset($message); ?>
            </div>

            <div class="auth-form-group">
                <label for="password">Password</label>
                <div class="auth-input-wrap">
                    <input id="password" type="password" name="password" class="auth-input <?php $message = ($errors->any() && !is_null($errors->first('password'))) ? $errors->first('password') : null; if ($message): ?> is-invalid <?php endif; unset($message); ?>" placeholder="Minimal 6 karakter" required>
                    <button class="btn-toggle-pw" type="button" aria-label="Lihat password" onclick="TJDash.togglePassword(this)">
                        <i class="fa-regular fa-eye-slash"></i>
                    </button>
                </div>
                <?php $message = ($errors->any() && !is_null($errors->first('password'))) ? $errors->first('password') : null; if ($message): ?>
                    <span class="auth-error"><?php echo e($message); ?></span>
                <?php endif; unset($message); ?>
            </div>

            <div class="auth-form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <div class="auth-input-wrap">
                    <input id="password_confirmation" type="password" name="password_confirmation" class="auth-input" placeholder="Ulangi password" required>
                    <button class="btn-toggle-pw" type="button" aria-label="Lihat password" onclick="TJDash.togglePassword(this)">
                        <i class="fa-regular fa-eye-slash"></i>
                    </button>
                </div>
            </div>

            <div class="auth-form-group">
                <label for="role">Daftar Sebagai</label>
                <select id="role" name="role" class="auth-input <?php $message = ($errors->any() && !is_null($errors->first('role'))) ? $errors->first('role') : null; if ($message): ?> is-invalid <?php endif; unset($message); ?>" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="pelanggan" <?php echo e(old('role') == 'pelanggan' ? 'selected' : ''); ?>>Pelanggan</option>
                    <option value="penyedia" <?php echo e(old('role') == 'penyedia' ? 'selected' : ''); ?>>Penyedia Jasa</option>
                </select>
                <?php $message = ($errors->any() && !is_null($errors->first('role'))) ? $errors->first('role') : null; if ($message): ?>
                    <span class="auth-error"><?php echo e($message); ?></span>
                <?php endif; unset($message); ?>
            </div>

            <button type="submit" class="btn-auth-submit">Buat Akun</button>
        </form>

        <div class="auth-footer-text">
            Sudah punya akun? <a href="<?php echo e(route('login')); ?>">Masuk di sini</a>
        </div>
    </div>
</div>
<?php $__sections['content'] = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/auth.php'; ?>

