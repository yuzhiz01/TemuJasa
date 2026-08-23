/* TemuJasa Auth - Client-Side Authentication System */
const TJAuth = {
    STORAGE_KEY: 'temujasa_users',
    SESSION_KEY: 'temujasa_session',

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

    logout() {
        localStorage.removeItem(this.SESSION_KEY);
        window.location.href = '/';
    },

    isLoggedIn() {
        return !!this.getSession();
    },

    register(name, email, password, role) {
        const users = this.getUsers();
        if (users.find(u => u.email === email)) {
            return { success: false, message: 'Email sudah terdaftar! Silakan login.' };
        }
        if (password.length < 6) {
            return { success: false, message: 'Password minimal 6 karakter.' };
        }
        const newUser = {
            id: Date.now(),
            name: name.trim(),
            email: email.trim().toLowerCase(),
            password: password,
            role: role || 'pelanggan',
            created_at: new Date().toISOString()
        };
        users.push(newUser);
        this.saveUsers(users);
        this.setSession(newUser);
        return { success: true, user: newUser };
    },

    login(email, password) {
        const users = this.getUsers();
        const user = users.find(u => u.email === email.trim().toLowerCase() && u.password === password);
        if (!user) {
            return { success: false, message: 'Email atau password salah.' };
        }
        this.setSession(user);
        return { success: true, user: user };
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
        }, 3000);
    },

    handleRegisterForm(form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const name = form.querySelector('#name')?.value || '';
            const email = form.querySelector('#email')?.value || '';
            const password = form.querySelector('#password')?.value || '';
            const passwordConfirm = form.querySelector('#password_confirmation')?.value || '';
            const role = form.querySelector('input[name="role"]')?.value || 'pelanggan';

            if (!name || !email || !password) {
                TJAuth.showNotification('Semua field wajib diisi.', 'error');
                return;
            }
            if (password !== passwordConfirm) {
                TJAuth.showNotification('Password dan konfirmasi tidak cocok.', 'error');
                return;
            }

            const result = TJAuth.register(name, email, password, role);
            if (result.success) {
                TJAuth.showNotification('Registrasi berhasil! Mengalihkan...', 'success');
                setTimeout(() => { window.location.href = '/'; }, 1200);
            } else {
                TJAuth.showNotification(result.message, 'error');
            }
        });
    },

    handleLoginForm(form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const email = form.querySelector('#email')?.value || '';
            const password = form.querySelector('#password')?.value || '';

            if (!email || !password) {
                TJAuth.showNotification('Email dan password wajib diisi.', 'error');
                return;
            }

            const result = TJAuth.login(email, password);
            if (result.success) {
                TJAuth.showNotification('Login berhasil! Selamat datang, ' + result.user.name, 'success');
                setTimeout(() => { window.location.href = '/'; }, 1200);
            } else {
                TJAuth.showNotification(result.message, 'error');
            }
        });
    },

    updateNavbar() {
        const session = this.getSession();
        if (!session) return;

        // Replace login/register buttons with user info
        const loginBtn = document.querySelector('.btn-header-login');
        const registerBtn = document.querySelector('.btn-header-register');
        
        if (loginBtn && registerBtn) {
            const parent = loginBtn.parentElement;
            loginBtn.remove();
            registerBtn.remove();

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
                    <a href="/" class="navbar-user-item"><i class="fa-solid fa-home"></i> Beranda</a>
                    <button type="button" class="navbar-user-item navbar-user-logout" onclick="TJAuth.logout()"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button>
                </div>
            `;
            parent.appendChild(userEl);
        }

        // Update mobile nav login link
        const mobileLogin = document.querySelector('.mobile-nav-item[href="/login"]');
        if (mobileLogin) {
            mobileLogin.outerHTML = `<a href="javascript:void(0)" class="mobile-nav-item" onclick="TJAuth.logout()">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Keluar</span>
            </a>`;
        }
    },

    init() {
        // Intercept register forms
        const registerForm = document.querySelector('form[action="/register"]');
        if (registerForm) {
            this.handleRegisterForm(registerForm);
        }

        // Intercept login forms  
        const loginForm = document.querySelector('form[action="/login"]');
        if (loginForm) {
            this.handleLoginForm(loginForm);
        }

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
