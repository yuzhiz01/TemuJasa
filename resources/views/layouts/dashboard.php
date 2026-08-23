<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__sections['title'] ?? 'Dashboard' ?> — TemuJasa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/temujasa.css">
    <link rel="stylesheet" href="/css/dashboard.css">
</head>
<body class="dash-body">

    <!-- SIDEBAR -->
    <aside class="dash-sidebar" id="dashSidebar">
        <div class="sidebar-header">
            <a href="<?php echo e(route('home')); ?>" class="sidebar-logo">
                <div class="logo-icon"><i class="fa-solid fa-location-dot"></i></div>
                <span>TemuJasa</span>
            </a>
            <button class="btn-sidebar-close" onclick="TJDash.toggleSidebar()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="sidebar-user-card">
            <div class="sidebar-avatar"><?php echo $__sections['user-initials'] ?? 'U' ?></div>
            <div class="sidebar-user-info">
                <strong><?php echo $__sections['user-name'] ?? 'Pengguna' ?></strong>
                <span class="role-badge <?php echo $__sections['role-class'] ?? 'role-pelanggan' ?>"><?php echo $__sections['role-label'] ?? 'Pelanggan' ?></span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <?php echo $__sections['sidebar-menu'] ?? '' ?>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="<?php echo e(route('logout')); ?>" class="sidebar-logout-form">
                <?php echo csrf_field(); ?>
                <button type="submit" class="sidebar-nav-item logout-item">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="TJDash.toggleSidebar()"></div>

    <!-- MAIN WRAPPER -->
    <div class="dash-main" id="dashMain">

        <!-- TOP HEADER -->
        <header class="dash-header">
            <div class="dash-header-left">
                <button class="btn-menu-toggle" onclick="TJDash.toggleSidebar()">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="dash-breadcrumb">
                    <span class="breadcrumb-root">TemuJasa</span>
                    <i class="fa-solid fa-chevron-right"></i>
                    <span class="breadcrumb-current"><?php echo $__sections['page-title'] ?? 'Dashboard' ?></span>
                </div>
            </div>
            <div class="dash-header-right">
                <button class="dash-icon-btn" onclick="TJDash.toggleNotif()" title="Notifikasi">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notif-dot" id="notifDot" style="<?php echo e(\App\Models\Message::where('recipient_id', auth()->id())->whereNull('read_at')->exists() ? '' : 'display:none'); ?>"></span>
                </button>
                <div class="dash-notif-dropdown" id="dashNotifDropdown">
                    <div class="notif-dd-header">
                        <strong>Notifikasi</strong>
                        <span onclick="TJDashMarkAllRead()" style="cursor:pointer;">Tandai dibaca</span>
                    </div>
                    <div class="notif-dd-list" id="notifList">
                        
                    </div>
                </div>
                <a href="<?php echo $__sections['profile-link'] ?? '#' ?>" class="dash-avatar-btn" title="Profil">
                    <span><?php echo $__sections['user-initials'] ?? 'U' ?></span>
                </a>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="dash-content">
            <div class="dash-page-header">
                <div>
                    <h1 class="dash-page-title"><?php echo $__sections['page-title'] ?? 'Dashboard' ?></h1>
                    <p class="dash-page-subtitle"><?php echo $__sections['page-subtitle'] ?? '' ?></p>
                </div>
                <div class="dash-page-actions"><?php echo $__sections['page-actions'] ?? '' ?></div>
            </div>
            <?php echo $__sections['content'] ?? '' ?>
        </main>
    </div>

    <script src="/js/temujasa.js" defer></script>
    <script src="/js/dashboard.js" defer></script>
    <?php echo $__sections['scripts'] ?? '' ?>

    
    <script>
    (function () {
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const dot = document.getElementById('notifDot');
        const list = document.getElementById('notifList');

        function escapeHtml(s) {
            const d = document.createElement('div');
            d.textContent = s ?? '';
            return d.innerHTML;
        }

        async function refreshNotif() {
            try {
                const res = await fetch('<?php echo e((auth()->user()->role === 'penyedia') ? route('penyedia.chat.poll') : route('pelanggan.chat.poll')); ?>', { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const data = await res.json();

                // titik merah pada ikon lonceng
                if (dot) dot.style.display = data.unread > 0 ? '' : 'none';

                if (!list) return;
                const chatUrl = '<?php echo e((auth()->user()->role === 'penyedia') ? route('penyedia.chat') : route('pelanggan.chat')); ?>';
                if (data.items.length === 0) {
                    list.innerHTML = '<p style="font-size:10px;color:#888;padding:14px;text-align:center;">Tidak ada pesan baru.</p>';
                    return;
                }
                list.innerHTML = data.items.map(n => `
                    <div class="notif-dd-item unread">
                        <div class="notif-dd-icon bg-primary-soft"><i class="fa-solid fa-comment-dots"></i></div>
                        <div><a href="${chatUrl}" style="color:inherit;text-decoration:none;"><p><strong>${escapeHtml(n.sender)}:</strong> ${escapeHtml(n.body)}</p></a><small>${escapeHtml(n.time)}</small></div>
                    </div>
                `).join('');
            } catch (e) { /* offline / sesi habis → coba lagi di tick berikutnya */ }
        }

        window.TJDashMarkAllRead = async function () {
            await fetch('<?php echo e((auth()->user()->role === 'penyedia') ? route('penyedia.chat.read') : route('pelanggan.chat.read')); ?>', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            });
            refreshNotif();
        };

        refreshNotif();                 // saat halaman dibuka
        setInterval(refreshNotif, 10000); // polling tiap 10 detik
    })();
    </script>
</body>
</html>
