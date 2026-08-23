/* TemuJasa Dashboard JS */
const TJDash = {
    toggleSidebar() {
        const s = document.getElementById('dashSidebar');
        const o = document.getElementById('sidebarOverlay');
        if (s) s.classList.toggle('open');
        if (o) o.classList.toggle('show');
    },
    toggleNotif() {
        const d = document.getElementById('dashNotifDropdown');
        if (d) d.classList.toggle('show');
    },
    markAllRead() {
        document.querySelectorAll('.notif-dd-item.unread').forEach(el => el.classList.remove('unread'));
        document.querySelectorAll('.notif-dot').forEach(el => el.style.display = 'none');
        TJDash.toggleNotif();
    },
    openModal(id) {
        const m = document.getElementById(id);
        if (m) m.classList.add('show');
    },
    closeModal(id) {
        const m = document.getElementById(id);
        if (m) m.classList.remove('show');
    },
    selectRole(el, role) {
        document.querySelectorAll('.auth-role-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        const inp = document.getElementById('selectedRole');
        if (inp) inp.value = role;
    },
    togglePassword(btnEl) {
        const inp = btnEl.previousElementSibling || btnEl.parentElement.querySelector('input');
        if (!inp) return;
        const isText = inp.type === 'text';
        inp.type = isText ? 'password' : 'text';
        btnEl.querySelector('i').className = isText ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';
    },
    goToRole() {
        const role = document.getElementById('selectedRole')?.value;
        if (!role) { alert('Pilih role terlebih dahulu'); return; }
        const map = { pelanggan: 'pelanggan/dashboard/', penyedia: 'penyedia/dashboard/', admin: 'admin/dashboard/' };
        const base = (typeof TJAuth !== 'undefined' && TJAuth.getBaseUrl) ? TJAuth.getBaseUrl() : '../../';
        window.location.href = base + (map[role] || '');
    },
    init() {
        document.addEventListener('click', e => {
            const dd = document.getElementById('dashNotifDropdown');
            if (dd && dd.classList.contains('show') && !e.target.closest('.dash-header-right')) {
                dd.classList.remove('show');
            }
        });
    }
};
document.addEventListener('DOMContentLoaded', () => TJDash.init());
