

<?php $__sections['title'] = 'Daftar Penyedia Jasa'; ?>

<?php ob_start(); ?>
<div class="auth-shell">
    <div class="auth-card auth-card-register">
        <div class="auth-brand">
            <div class="logo-icon"><i class="fa-solid fa-location-dot"></i></div>
            <span>TemuJasa</span>
        </div>

        <div class="auth-header">
            <h2 class="auth-title">Daftar sebagai Penyedia Jasa</h2>
            <p class="auth-subtitle">Buat akun untuk menampilkan layanan Anda</p>
        </div>

        <?php if($errors->any()): ?>
            <div class="auth-alert" role="alert">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('register.process')); ?>" class="auth-form">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="role" value="penyedia">

            <div class="auth-form-group">
                <label for="name">Nama Lengkap</label>
                <input id="name" type="text" name="name" class="auth-input <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name')); ?>" placeholder="Masukkan nama lengkap" required>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="auth-error"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="auth-form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" class="auth-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email')); ?>" placeholder="nama@email.com" required>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="auth-error"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="auth-form-group">
                <label for="password">Password</label>
                <div class="auth-input-wrap">
                    <input id="password" type="password" name="password" class="auth-input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Minimal 6 karakter" required>
                    <button class="btn-toggle-pw" type="button" aria-label="Lihat password" onclick="TJDash.togglePassword(this)">
                        <i class="fa-regular fa-eye-slash"></i>
                    </button>
                </div>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="auth-error"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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

            <button type="submit" class="btn-auth-submit">Daftar sebagai Penyedia Jasa</button>
        </form>

        <div class="auth-footer-text">
            Sudah punya akun? <a href="<?php echo e(route('login')); ?>">Masuk di sini</a>
        </div>
    </div>
</div>
<?php $__sections['content'] = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/auth.php'; ?>