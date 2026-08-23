<?php $__sections['title'] = 'Chat'; ?>
<?php ($user = auth()->user()); ?>
<?php $__sections['user-initials'] = strtoupper(substr($user->name, 0, 2)); ?>
<?php $__sections['user-name'] = $user->name; ?>
<?php $__sections['role-class'] = 'role-penyedia'; ?>
<?php $__sections['role-label'] = 'Penyedia Jasa'; ?>
<?php $__sections['profile-link'] = route('penyedia.profil'); ?>
<?php $__sections['page-title'] = 'Chat / Pesan'; ?>
<?php $__sections['page-subtitle'] = 'Komunikasi langsung dengan pelanggan'; ?>

<?php ob_start(); ?>
<span class="sidebar-nav-section">Menu Utama</span>
<a href="<?php echo e(route('penyedia.dashboard')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
<a href="<?php echo e(route('penyedia.jasa-saya')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-briefcase"></i><span>Jasa Saya</span></a>
<span class="sidebar-nav-section">Aktivitas</span>
<a href="<?php echo e(route('penyedia.pesanan')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-bag-shopping"></i><span>Pesanan</span></a>
<a href="<?php echo e(route('penyedia.chat')); ?>" class="sidebar-nav-item active"><i class="fa-regular fa-comment-dots"></i><span>Chat / Pesan</span></a>
<span class="sidebar-nav-section">Akun</span>
<a href="<?php echo e(route('penyedia.profil')); ?>" class="sidebar-nav-item"><i class="fa-regular fa-user"></i><span>Profil Saya</span></a>
<?php $__sections['content'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<?php ($user = auth()->user()); ?>
<div class="chat-layout">
    <!-- CHAT LIST -->
    <div class="chat-list">
        <div class="chat-list-header">
            <h3>Pesan</h3>
            <div class="dash-search-bar" style="margin-top:8px;height:28px;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Cari percakapan..." style="width:100%;">
            </div>
        </div>
        <?php if($contact): ?>
        <div class="chat-item active">
            <div class="chat-item-avatar"><?php echo e(strtoupper(substr($contact->name, 0, 2))); ?></div>
            <div class="chat-item-info">
                <strong><?php echo e($contact->name); ?></strong>
                <span><?php echo e($messages->last()?->body ?? 'Belum ada pesan'); ?></span>
            </div>
            <div class="chat-item-meta">
                <small><?php echo e($messages->last()?->created_at?->format('H:i') ?? '-'); ?></small>
            </div>
        </div>
        <?php else: ?>
        <p style="padding:16px;font-size:10px;color:#888;">Belum ada kontak chat.</p>
        <?php endif; ?>
    </div>

    <!-- CHAT WINDOW -->
    <div class="chat-window">
        <div class="chat-window-header">
            <div class="chat-item-avatar"><?php echo e($contact ? strtoupper(substr($contact->name, 0, 2)) : '--'); ?></div>
            <div>
                <strong><?php echo e($contact?->name ?? 'Belum ada kontak'); ?></strong>
                <span><?php echo e($contact ? 'Percakapan' : 'Mulai dari pesanan pelanggan'); ?></span>
            </div>
            <div style="margin-left:auto;display:flex;gap:6px;">
                <button class="btn-secondary btn-sm btn-icon"><i class="fa-solid fa-phone"></i></button>
                <button class="btn-secondary btn-sm btn-icon"><i class="fa-solid fa-ellipsis-vertical"></i></button>
            </div>
        </div>

        <div class="chat-messages">
            <?php $__empty_1 = true; foreach ($messages as $message):  $__empty_1 = false; ?>
            <div class="chat-msg <?php echo e($message->sender_id === $user->id ? 'sent' : 'received'); ?>">
                <div class="chat-msg-avatar"><?php echo e(strtoupper(substr($message->sender->name, 0, 2))); ?></div>
                <div><div class="chat-bubble"><?php echo e($message->body); ?></div><div class="chat-msg-time"><?php echo e($message->created_at->format('H:i')); ?></div></div>
            </div>
            <?php endforeach; if ($__empty_1): ?>
            <p style="margin:auto;font-size:10px;color:#888;">Belum ada pesan.</p>
            <?php endif; ?>
        </div>

        <?php if($contact): ?><form method="POST" action="<?php echo e(route('penyedia.chat.store')); ?>" class="chat-input-bar">
            <?php echo csrf_field(); ?> <input type="hidden" name="recipient_id" value="<?php echo e($contact->id); ?>">
            <button class="btn-secondary btn-sm btn-icon"><i class="fa-solid fa-paperclip"></i></button>
            <input type="text" name="body" placeholder="Ketik pesan..." required>
            <button class="btn-send" type="submit"><i class="fa-solid fa-paper-plane"></i></button>
        </form><?php endif; ?>
    </div>
</div>
<?php $__sections['sidebar-menu'] = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/dashboard.php'; ?>