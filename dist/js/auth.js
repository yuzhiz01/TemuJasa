/* TemuJasa Auth - InfinityFree MySQL Database & Hybrid Authentication System */
const TJAuth = {
    STORAGE_KEY: 'temujasa_users',
    SESSION_KEY: 'temujasa_session',
    API_URL_KEY: 'temujasa_api_url',

    // Default API URL pointing directly to InfinityFree Database API
    getApiUrl() {
        return localStorage.getItem(this.API_URL_KEY) || 'https://temujasa.great-site.net/api';
    },

    setApiUrl(url) {
        if (url) {
            url = url.replace(/\/+$/, ''); // Hapus trailing slash
            localStorage.setItem(this.API_URL_KEY, url);
        } else {
            localStorage.removeItem(this.API_URL_KEY);
        }
    },

    getUsers() {
        try { return JSON.parse(localStorage.getItem(this.STORAGE_KEY)) || []; }
        catch { return []; }
    },

    saveUsers(users) {
        localStorage.setItem(this.STORAGE_KEY, JSON.stringify(users));
    },

    getSession() {
        try { return JSON.parse(localStorage.getItem(this.SESSION_KEY)); }
        catch { return null; }
    },

    setSession(user) {
        localStorage.setItem(this.SESSION_KEY, JSON.stringify(user));
    },

    getBaseUrl() {
        const href = window.location.href;
        const path = window.location.pathname;

        // 1. Jika URL memuat /dist/, jadikan folder /dist/ sebagai root aplikasi
        const distIdx = href.indexOf('/dist/');
        if (distIdx !== -1) {
            return href.substring(0, distIdx + 6);
        }

        // 2. Jika di-host pada GitHub Pages (contoh: https://user.github.io/repo/)
        if (window.location.hostname.endsWith('.github.io')) {
            const match = path.match(/^(\/[^\/]+)/);
            const repoPath = match ? match[1] : '';
            return window.location.origin + repoPath + '/';
        }

        // 3. Jika di-host lokal pada XAMPP (contoh: http://localhost/TemuJasa/)
        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
            const parts = path.split('/').filter(Boolean);
            if (parts.length > 0 && parts[0].toLowerCase() === 'temujasa') {
                return window.location.origin + '/' + parts[0] + '/dist/';
            }
        }

        // 4. Default untuk Vercel atau Domain Utama
        return '/';
    },

    logout() {
        localStorage.removeItem(this.SESSION_KEY);
        window.location.href = this.getBaseUrl();
    },

    isLoggedIn() {
        return !!this.getSession();
    },

    /**
     * Register User (Mencoba ke InfinityFree Database API terlebih dahulu)
     */
    async register(name, email, password, role) {
        if (password.length < 6) {
            return { success: false, message: 'Password minimal 6 karakter.' };
        }

        const apiUrl = this.getApiUrl();
        const payload = {
            name: name.trim(),
            email: email.trim().toLowerCase(),
            password: password,
            role: role || 'pelanggan'
        };

        // 1. Coba kirim ke InfinityFree Database API
        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 6000);

            // Coba endpoint register.php atau /register
            const endpoint = apiUrl.endsWith('.php') ? apiUrl : (apiUrl + '/register.php');
            const res = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
                signal: controller.signal
            });
            clearTimeout(timeoutId);

            const data = await res.json();
            if (res.ok && data.success) {
                const user = data.user || payload;
                this.setSession(user);
                
                // Cache user lokal
                const users = this.getUsers();
                if (!users.find(u => u.email === user.email)) {
                    users.push(user);
                    this.saveUsers(users);
                }

                return { success: true, user: user, source: 'database' };
            } else {
                return { success: false, message: data.message || 'Gagal mendaftar ke database.' };
            }
        } catch (err) {
            console.warn('API database tidak dapat dijangkau, menggunakan penyimpanan lokal:', err);
        }

        // 2. Fallback jika API belum di-host / offline (Penyimpanan Lokal)
        const users = this.getUsers();
        if (users.find(u => u.email === payload.email)) {
            return { success: false, message: 'Email sudah terdaftar! Silakan login.' };
        }

        const newUser = {
            id: Date.now(),
            name: payload.name,
            email: payload.email,
            password: payload.password,
            role: payload.role,
            created_at: new Date().toISOString()
        };
        users.push(newUser);
        this.saveUsers(users);
        this.setSession(newUser);
        return { success: true, user: newUser, source: 'local' };
    },

    /**
     * Login User (Mencoba ke InfinityFree Database API terlebih dahulu)
     */
    async login(email, password) {
        const cleanEmail = email.trim().toLowerCase();
        const apiUrl = this.getApiUrl();

        // 1. Coba login via InfinityFree Database API
        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 6000);

            const endpoint = apiUrl.endsWith('.php') ? apiUrl : (apiUrl + '/login.php');
            const res = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: cleanEmail, password: password }),
                signal: controller.signal
            });
            clearTimeout(timeoutId);

            const data = await res.json();
            if (res.ok && data.success) {
                this.setSession(data.user);
                return { success: true, user: data.user, source: 'database' };
            } else if (res.status === 401 || res.status === 400 || res.status === 409) {
                return { success: false, message: data.message || 'Email atau password salah.' };
            }
        } catch (err) {
            console.warn('API database tidak dapat dijangkau, memeriksa akun lokal:', err);
        }

        // 2. Fallback cek akun lokal jika offline
        const users = this.getUsers();
        const user = users.find(u => u.email === cleanEmail && u.password === password);
        if (user) {
            this.setSession(user);
            return { success: true, user: user, source: 'local' };
        }

        return { success: false, message: 'Email atau password salah.' };
    },

    showNotification(message, type) {
        let existing = document.querySelector('.tj-notification');
        if (existing) existing.remove();

        const div = document.createElement('div');
        div.className = 'tj-notification tj-notification-' + type;
        div.innerHTML = '<i class="fa-solid ' + (type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle') + '"></i> ' + message;
        document.body.appendChild(div);
        requestAnimationFrame(() => div.classList.add('show'));
        setTimeout(() => {
            div.classList.remove('show');
            setTimeout(() => div.remove(), 300);
        }, 3500);
    },

    handleRegisterForm(form) {
        if (form.dataset.authAttached) return;
        form.dataset.authAttached = 'true';

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            e.stopPropagation();
            const submitBtn = form.querySelector('button[type="submit"]');
            const origText = submitBtn ? submitBtn.innerText : '';

            const name = form.querySelector('#name')?.value || '';
            const email = form.querySelector('#email')?.value || '';
            const password = form.querySelector('#password')?.value || '';
            const passwordConfirm = form.querySelector('#password_confirmation')?.value || '';
            const role = form.querySelector('input[name="role"]')?.value || 'pelanggan';

            if (!name || !email || !password) {
                TJAuth.showNotification('Semua field wajib diisi.', 'error');
                return false;
            }
            if (password !== passwordConfirm) {
                TJAuth.showNotification('Password dan konfirmasi tidak cocok.', 'error');
                return false;
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerText = 'Menghubungkan ke Database...';
            }

            try {
                const result = await TJAuth.register(name, email, password, role);
                if (result.success) {
                    const msg = result.source === 'database'
                        ? 'Registrasi berhasil ke database InfinityFree! Mengalihkan ke Dashboard...'
                        : 'Registrasi berhasil! Mengalihkan ke Dashboard...';
                    TJAuth.showNotification(msg, 'success');
                    const targetDashboard = (result.user?.role === 'penyedia' || role === 'penyedia')
                        ? 'penyedia/dashboard/'
                        : 'pelanggan/dashboard/';
                    setTimeout(() => { window.location.href = TJAuth.getBaseUrl() + targetDashboard; }, 1200);
                } else {
                    TJAuth.showNotification(result.message, 'error');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerText = origText;
                    }
                }
            } catch (err) {
                TJAuth.showNotification('Terjadi kendala saat registrasi: ' + err.message, 'error');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerText = origText;
                }
            }
            return false;
        });
    },

    handleLoginForm(form) {
        if (form.dataset.authAttached) return;
        form.dataset.authAttached = 'true';

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            e.stopPropagation();
            const submitBtn = form.querySelector('button[type="submit"]');
            const origText = submitBtn ? submitBtn.innerText : '';

            const email = form.querySelector('#email')?.value || '';
            const password = form.querySelector('#password')?.value || '';

            if (!email || !password) {
                TJAuth.showNotification('Email dan password wajib diisi.', 'error');
                return false;
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerText = 'Memverifikasi ke Database...';
            }

            try {
                const result = await TJAuth.login(email, password);
                if (result.success) {
                    TJAuth.showNotification('Login berhasil! Selamat datang, ' + result.user.name, 'success');
                    const targetDashboard = result.user?.role === 'penyedia'
                        ? 'penyedia/dashboard/'
                        : 'pelanggan/dashboard/';
                    setTimeout(() => { window.location.href = TJAuth.getBaseUrl() + targetDashboard; }, 1200);
                } else {
                    TJAuth.showNotification(result.message, 'error');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerText = origText;
                    }
                }
            } catch (err) {
                TJAuth.showNotification('Terjadi kendala saat login: ' + err.message, 'error');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerText = origText;
                }
            }
            return false;
        });
    },

    updateNavbar() {
        const session = this.getSession();
        if (!session) return;

        const dashPath = session.role === 'penyedia' ? 'penyedia/dashboard/' : 'pelanggan/dashboard/';

        // Replace login/register buttons with user info
        const loginBtn = document.querySelector('.btn-header-login');
        const registerBtn = document.querySelector('.btn-header-register');
        
        if (loginBtn || registerBtn) {
            const parent = (loginBtn || registerBtn).parentElement;
            if (loginBtn) loginBtn.remove();
            if (registerBtn) registerBtn.remove();

            const userEl = document.createElement('div');
            userEl.className = 'navbar-user-menu';
            userEl.innerHTML = `
                <button type="button" class="navbar-user-btn" onclick="this.nextElementSibling.classList.toggle('show')">
                    <div class="navbar-user-avatar"><i class="fa-solid fa-user"></i></div>
                    <span class="navbar-user-name">${session.name}</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="navbar-user-dropdown">
                    <div class="navbar-user-info">
                        <strong>${session.name}</strong>
                        <small>${session.email}</small>
                        <span class="navbar-user-role">${session.role === 'penyedia' ? 'Penyedia Jasa' : 'Pelanggan'}</span>
                    </div>
                    <div class="navbar-user-divider"></div>
                    <a href="${TJAuth.getBaseUrl()}${dashPath}" class="navbar-user-item"><i class="fa-solid fa-gauge-high"></i> Dashboard Saya</a>
                    <a href="${TJAuth.getBaseUrl()}jasa/cari/" class="navbar-user-item"><i class="fa-solid fa-magnifying-glass"></i> Cari Jasa</a>
                    <a href="${TJAuth.getBaseUrl()}" class="navbar-user-item"><i class="fa-solid fa-home"></i> Beranda</a>
                    <button type="button" class="navbar-user-item navbar-user-logout" onclick="TJAuth.logout()"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button>
                </div>
            `;
            parent.appendChild(userEl);
        }

        // Update mobile nav login link to point to user dashboard
        const mobileLogin = document.querySelector('.mobile-nav-item[href*="/login"], .mobile-nav-item[href*="login"], #mobileNavAuth, #mobileAuthLink');
        if (mobileLogin) {
            mobileLogin.href = `${TJAuth.getBaseUrl()}${dashPath}`;
            const span = mobileLogin.querySelector('span');
            if (span) span.innerText = 'Dashboard';
            const icon = mobileLogin.querySelector('i');
            if (icon) icon.className = 'fa-solid fa-gauge-high';
        }
    },

    init() {
        // Intercept register forms across all possible paths
        document.querySelectorAll('form[action*="register"], form.auth-form-register, .auth-card-register form').forEach(f => {
            this.handleRegisterForm(f);
        });

        // Intercept login forms across all possible paths
        document.querySelectorAll('form[action*="login"], form.auth-form-login, .auth-card-login form').forEach(f => {
            this.handleLoginForm(f);
        });

        // Universal catch-all for any form with class .auth-form
        document.querySelectorAll('form.auth-form').forEach(f => {
            if (!f.dataset.authAttached) {
                if (f.querySelector('input[name="role"]') || f.querySelector('#password_confirmation')) {
                    this.handleRegisterForm(f);
                } else if (f.querySelector('#email') && f.querySelector('#password')) {
                    this.handleLoginForm(f);
                }
            }
        });

        // Update navbar if logged in
        this.updateNavbar();

        // Close user dropdown on outside click
        document.addEventListener('click', (e) => {
            const dropdown = document.querySelector('.navbar-user-dropdown.show');
            if (dropdown && !e.target.closest('.navbar-user-menu')) {
                dropdown.classList.remove('show');
            }
        });
    }
};

document.addEventListener('DOMContentLoaded', () => TJAuth.init());
// Run immediately in case DOM is already parsed
if (document.readyState !== 'loading') {
    TJAuth.init();
}
