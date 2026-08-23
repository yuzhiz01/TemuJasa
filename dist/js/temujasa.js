/* =========================================
   TEMUJASA - JavaScript Utama
========================================= */

/* ---- SEARCH HERO ---- */
function searchTemuJasa() {
    const input = document.getElementById('mainSearch');
    if (!input) return;
    const keyword = input.value.trim();
    if (!keyword) {
        TemuJasa.showToast('Silakan masukkan produk atau jasa yang ingin dicari.', 'warning');
        input.focus();
        return;
    }
    const base = (typeof TJAuth !== 'undefined' && TJAuth.getBaseUrl) ? TJAuth.getBaseUrl() : './';
    window.location.href = base + 'jasa/cari/?q=' + encodeURIComponent(keyword);
}

/* ---- CART STATE ---- */
const _cart = [];

/* =========================================
   TEMUJASA NAMESPACE
========================================= */
window.TemuJasa = {

    /* ---- TOAST ---- */
    showToast(message, type = 'info') {
        const container = document.getElementById('toastContainer');
        if (!container) { console.log(message); return; }

        const icons = { success: 'fa-circle-check', error: 'fa-circle-xmark', warning: 'fa-triangle-exclamation', info: 'fa-circle-info' };
        const colors = { success: '#22c55e', error: '#ef4444', warning: '#f59e0b', info: '#6366f1' };

        const toast = document.createElement('div');
        toast.className = 'tj-toast';
        toast.innerHTML = `<i class="fa-solid ${icons[type] || icons.info}" style="color:${colors[type] || colors.info}"></i><span>${message}</span>`;
        container.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    },

    /* ---- DROPDOWN ---- */
    toggleDropdown(id) {
        const menu = document.getElementById(id);
        if (!menu) return;
        const isOpen = menu.classList.contains('show');
        document.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
        if (!isOpen) menu.classList.add('show');
    },

    /* ---- LOCATION MODAL ---- */
    openLocationModal() {
        this.closeCart();
        const modal = document.getElementById('locationModal');
        const overlay = document.getElementById('locationModalOverlay');
        if (!modal || !overlay) return;
        modal.style.display = 'block';
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
    },
    closeLocationModal() {
        const modal = document.getElementById('locationModal');
        const overlay = document.getElementById('locationModalOverlay');
        if (modal) modal.style.display = 'none';
        if (overlay) overlay.style.display = 'none';
        document.body.style.overflow = '';
    },
    selectLocation(name, region) {
        document.querySelectorAll('.loc-item').forEach(el => el.classList.remove('active'));
        event.currentTarget.classList.add('active');
        const texts = document.querySelectorAll('#currentLocationText, #heroLocationLabel');
        texts.forEach(el => { if (el) el.textContent = name; });
        this.showToast(`Lokasi diubah ke ${name}`, 'success');
        setTimeout(() => this.closeLocationModal(), 600);
    },
    filterLocations(query) {
        const q = query.toLowerCase();
        document.querySelectorAll('.loc-item').forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(q) ? 'flex' : 'none';
        });
    },

    /* ---- CART ---- */
    toggleCart() {
        this.closeLocationModal();
        const drawer = document.getElementById('cartDrawer');
        const overlay = document.getElementById('cartOverlay');
        const isOpen = drawer.style.display === 'block';
        drawer.style.display = isOpen ? 'none' : 'block';
        overlay.style.display = isOpen ? 'none' : 'block';
        if (!isOpen) document.body.style.overflow = 'hidden';
        else document.body.style.overflow = '';
    },
    closeCart() {
        const drawer = document.getElementById('cartDrawer');
        const overlay = document.getElementById('cartOverlay');
        if (drawer) drawer.style.display = 'none';
        if (overlay) overlay.style.display = 'none';
        document.body.style.overflow = '';
    },
    addToCart(name, price) {
        const existing = _cart.find(i => i.name === name);
        if (existing) {
            existing.qty++;
        } else {
            _cart.push({ name, price, qty: 1 });
        }
        this._renderCart();
        this.showToast(`${name} ditambahkan ke keranjang`, 'success');
    },
    removeFromCart(name) {
        const idx = _cart.findIndex(i => i.name === name);
        if (idx > -1) _cart.splice(idx, 1);
        this._renderCart();
    },
    changeQty(name, delta) {
        const item = _cart.find(i => i.name === name);
        if (!item) return;
        item.qty += delta;
        if (item.qty <= 0) this.removeFromCart(name);
        else this._renderCart();
    },
    _renderCart() {
        const body = document.getElementById('cartDrawerItems');
        const footer = document.getElementById('cartDrawerFooter');
        const emptyState = document.getElementById('emptyCartState');
        const countBadge = document.getElementById('cartBadgeCount');
        const drawerCount = document.getElementById('cartDrawerCount');

        const totalQty = _cart.reduce((s, i) => s + i.qty, 0);
        const subtotal = _cart.reduce((s, i) => s + i.price * i.qty, 0);

        if (countBadge) countBadge.textContent = totalQty;
        if (drawerCount) drawerCount.textContent = `${totalQty} item`;

        if (_cart.length === 0) {
            if (emptyState) emptyState.style.display = 'block';
            if (footer) footer.style.display = 'none';
            return;
        }

        if (emptyState) emptyState.style.display = 'none';
        if (footer) footer.style.display = 'block';

        const itemsHtml = _cart.map(item => `
            <div class="cart-item-row">
                <div class="cart-item-info">
                    <strong>${item.name}</strong>
                    <span>${this._formatRp(item.price)}</span>
                </div>
                <div class="cart-item-qty">
                    <button onclick="TemuJasa.changeQty('${item.name}', -1)"><i class="fa-solid fa-minus"></i></button>
                    <span>${item.qty}</span>
                    <button onclick="TemuJasa.changeQty('${item.name}', 1)"><i class="fa-solid fa-plus"></i></button>
                </div>
                <button class="cart-item-remove" onclick="TemuJasa.removeFromCart('${item.name}')"><i class="fa-solid fa-trash"></i></button>
            </div>
        `).join('');

        body.innerHTML = itemsHtml;

        const subtotalEl = document.getElementById('cartSubtotalText');
        const totalEl = document.getElementById('cartTotalText');
        if (subtotalEl) subtotalEl.textContent = this._formatRp(subtotal);
        if (totalEl) totalEl.textContent = this._formatRp(subtotal);
    },
    checkoutCart() {
        if (_cart.length === 0) {
            this.showToast('Keranjang masih kosong', 'warning');
            return;
        }
        this.showToast('Pesanan berhasil dibuat! Terima kasih.', 'success');
        _cart.length = 0;
        this._renderCart();
        this.closeCart();
    },
    _formatRp(num) {
        return 'Rp ' + num.toLocaleString('id-ID');
    },

    /* ---- FAVORITE ---- */
    toggleFavorite(id, name, cat) {
        const btn = event.currentTarget;
        const icon = btn.querySelector('i');
        const isFav = icon && icon.classList.contains('fa-solid');
        if (icon) {
            icon.classList.toggle('fa-solid', !isFav);
            icon.classList.toggle('fa-regular', isFav);
            if (!isFav) icon.style.color = '#ef4444';
            else icon.style.color = '';
        }
        this.showToast(isFav ? `${name} dihapus dari favorit` : `${name} disimpan ke favorit`, isFav ? 'info' : 'success');
    },

    /* ---- SHARE ---- */
    shareStore(name) {
        if (navigator.share) {
            navigator.share({ title: name, url: window.location.href });
        } else {
            navigator.clipboard.writeText(window.location.href).then(() => {
                this.showToast('Link toko disalin ke clipboard!', 'success');
            });
        }
    },

    /* ---- SEARCH PAGE ---- */
    handleLiveSearch(event) {
        if (event.key === 'Enter') this.applySearch();
        const val = event.target.value;
        const clearBtn = document.getElementById('btnClearSearch');
        if (clearBtn) clearBtn.style.display = val ? 'flex' : 'none';
    },
    clearSearchInput() {
        const input = document.getElementById('searchPageInput');
        if (input) { input.value = ''; input.focus(); }
        const clearBtn = document.getElementById('btnClearSearch');
        if (clearBtn) clearBtn.style.display = 'none';
        this.applySearch();
    },
    applySearch() {
        const input = document.getElementById('searchPageInput');
        const q = input ? input.value.trim() : '';
        const heading = document.getElementById('searchResultHeading');
        if (heading) heading.textContent = q ? `Hasil: "${q}"` : 'Semua Mitra & Produk';
        this._filterCards();
    },
    setSearchTag(tag) {
        const input = document.getElementById('searchPageInput');
        if (input) { input.value = tag; }
        this.applySearch();
    },

    /* ---- FILTER ---- */
    toggleMobileFilter(forceClose) {
        const sidebar = document.getElementById('filterSidebar');
        const overlay = document.getElementById('filterOverlay');
        if (!sidebar) return;
        const isOpen = sidebar.classList.contains('mobile-open');
        if (forceClose === false || isOpen) {
            sidebar.classList.remove('mobile-open');
            if (overlay) overlay.style.display = 'none';
            document.body.style.overflow = '';
        } else {
            sidebar.classList.add('mobile-open');
            if (overlay) overlay.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    },
    onRadiusChange(val) {
        const display = document.getElementById('radiusValueDisplay');
        if (display) display.textContent = `${val} km`;
        this.applyFilters();
    },
    setPriceRange(min, max) {
        const minEl = document.getElementById('minPriceInput');
        const maxEl = document.getElementById('maxPriceInput');
        if (minEl) minEl.value = min || '';
        if (maxEl) maxEl.value = max || '';
        this.applyFilters();
    },
    applyFilters() {
        this._filterCards();
        this._updateFilterChips();
    },
    resetFilters() {
        document.querySelectorAll('[name="catFilter"]').forEach(cb => cb.checked = false);
        document.querySelectorAll('[name="ratingFilter"]').forEach(rb => { rb.checked = rb.value === '0'; });
        const minEl = document.getElementById('minPriceInput');
        const maxEl = document.getElementById('maxPriceInput');
        const radiusEl = document.getElementById('radiusRange');
        const openEl = document.getElementById('filterOpenOnly');
        if (minEl) minEl.value = '';
        if (maxEl) maxEl.value = '';
        if (radiusEl) { radiusEl.value = 25; this.onRadiusChange(25); }
        if (openEl) openEl.checked = false;
        this._filterCards();
        this._updateFilterChips();
        this.showToast('Filter direset', 'info');
    },
    _filterCards() {
        const q = (document.getElementById('searchPageInput')?.value || '').toLowerCase();
        const checkedCats = [...document.querySelectorAll('[name="catFilter"]:checked')].map(cb => cb.value);
        const minRating = parseFloat(document.querySelector('[name="ratingFilter"]:checked')?.value || 0);
        const minPrice = parseFloat(document.getElementById('minPriceInput')?.value || 0);
        const maxPrice = parseFloat(document.getElementById('maxPriceInput')?.value || Infinity);
        const openOnly = document.getElementById('filterOpenOnly')?.checked;
        const radius = parseFloat(document.getElementById('radiusRange')?.value || 25);

        let visible = 0;
        document.querySelectorAll('.search-card').forEach(card => {
            const name = (card.dataset.name || '').toLowerCase();
            const cat = card.dataset.category || '';
            const price = parseFloat(card.dataset.price || 0);
            const rating = parseFloat(card.dataset.rating || 0);
            const dist = parseFloat(card.dataset.distance || 0);
            const isOpen = card.dataset.open === 'true';

            const matchQ = !q || name.includes(q);
            const matchCat = checkedCats.length === 0 || checkedCats.includes(cat);
            const matchRating = rating >= minRating;
            const matchPrice = price >= minPrice && price <= maxPrice;
            const matchOpen = !openOnly || isOpen;
            const matchRadius = dist <= radius;

            const show = matchQ && matchCat && matchRating && matchPrice && matchOpen && matchRadius;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        const countEl = document.getElementById('resultsCountText');
        if (countEl) countEl.textContent = `Menampilkan ${visible} mitra & produk`;

        const emptyState = document.getElementById('emptyResultsState');
        const pagination = document.getElementById('paginationWrapper');
        if (emptyState) emptyState.style.display = visible === 0 ? 'flex' : 'none';
        if (pagination) pagination.style.display = visible === 0 ? 'none' : 'flex';
    },
    _updateFilterChips() {
        const row = document.getElementById('activeFilterChipsRow');
        const list = document.getElementById('activeChipsList');
        const mobileCount = document.getElementById('mobileFilterCount');
        if (!row || !list) return;

        const chips = [];
        document.querySelectorAll('[name="catFilter"]:checked').forEach(cb => {
            chips.push({ label: cb.parentElement.querySelector('span')?.textContent || cb.value, key: 'cat', val: cb.value });
        });
        const rating = document.querySelector('[name="ratingFilter"]:checked');
        if (rating && rating.value !== '0') chips.push({ label: `Rating ≥ ${rating.value}`, key: 'rating', val: rating.value });

        list.innerHTML = chips.map(c =>
            `<span class="active-chip">${c.label} <button onclick="TemuJasa._removeChip('${c.key}','${c.val}')"><i class="fa-solid fa-xmark"></i></button></span>`
        ).join('');

        row.style.display = chips.length ? 'flex' : 'none';
        if (mobileCount) mobileCount.textContent = chips.length;
    },
    _removeChip(key, val) {
        if (key === 'cat') {
            const cb = document.querySelector(`[name="catFilter"][value="${val}"]`);
            if (cb) cb.checked = false;
        } else if (key === 'rating') {
            const rb = document.querySelector('[name="ratingFilter"][value="0"]');
            if (rb) rb.checked = true;
        }
        this.applyFilters();
    },

    /* ---- SORT ---- */
    applySorting(mode) {
        const grid = document.getElementById('searchResultsGrid');
        if (!grid) return;
        const cards = [...grid.querySelectorAll('.search-card')];
        cards.sort((a, b) => {
            if (mode === 'nearest') return parseFloat(a.dataset.distance) - parseFloat(b.dataset.distance);
            if (mode === 'rating') return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
            if (mode === 'price-asc') return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            if (mode === 'price-desc') return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            return 0;
        });
        cards.forEach(c => grid.appendChild(c));
        this.showToast('Hasil diurutkan', 'info');
    },

    /* ---- VIEW MODE ---- */
    setViewMode(mode) {
        const grid = document.getElementById('searchResultsGrid');
        const btnGrid = document.getElementById('btnViewGrid');
        const btnList = document.getElementById('btnViewList');
        if (!grid) return;
        grid.className = grid.className.replace(/view-\w+/g, '').trim();
        grid.classList.add(`view-${mode}`);
        if (btnGrid) btnGrid.classList.toggle('active', mode === 'grid');
        if (btnList) btnList.classList.toggle('active', mode === 'list');
    },

    /* ---- MODAL DAFTAR MITRA ---- */
    openMitraModal() {
        const modal = document.getElementById('mitraModal');
        const overlay = document.getElementById('mitraModalOverlay');
        const formStep = document.getElementById('mitraFormStep');
        const successStep = document.getElementById('mitraSuccessStep');
        const form = document.getElementById('mitraForm');
        const charCounter = document.getElementById('mitraCharCount');
        const firstNameInput = document.getElementById('mitraNama');

        if (!modal || !overlay || !formStep || !successStep || !form || !charCounter || !firstNameInput) {
            return;
        }

        this.closeCart();
        this.closeLocationModal();
        formStep.style.display = 'block';
        successStep.style.display = 'none';
        form.reset();
        charCounter.textContent = '0 / 300';
        this._clearMitraErrors();
        modal.style.display = 'block';
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
        setTimeout(() => firstNameInput.focus(), 100);
    },
    closeMitraModal() {
        const modal = document.getElementById('mitraModal');
        const overlay = document.getElementById('mitraModalOverlay');
        if (!modal || !overlay) return;
        modal.style.display = 'none';
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    },
    _clearMitraErrors() {
        ['errNama', 'errHP', 'errNamaUsaha', 'errKategori', 'errLokasi'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = '';
        });
        document.querySelectorAll('.mitra-field input, .mitra-field select, .mitra-field textarea')
            .forEach(el => el.classList.remove('input-error'));
    },
    _validateMitraForm() {
        this._clearMitraErrors();
        let valid = true;
        const rules = [
            { id: 'mitraNama', errId: 'errNama', label: 'Nama pemilik', min: 3 },
            { id: 'mitraHP', errId: 'errHP', label: 'Nomor WhatsApp', min: 9, pattern: /^[0-9+\-\s]{9,15}$/ },
            { id: 'mitraNamaUsaha', errId: 'errNamaUsaha', label: 'Nama usaha', min: 3 },
            { id: 'mitraKategori', errId: 'errKategori', label: 'Kategori usaha', select: true },
            { id: 'mitraLokasi', errId: 'errLokasi', label: 'Wilayah', select: true },
        ];
        rules.forEach(r => {
            const el = document.getElementById(r.id);
            const errEl = document.getElementById(r.errId);
            const val = el.value.trim();
            if (!val) {
                errEl.textContent = `${r.label} wajib diisi.`;
                el.classList.add('input-error');
                valid = false;
            } else if (r.min && val.length < r.min) {
                errEl.textContent = `${r.label} minimal ${r.min} karakter.`;
                el.classList.add('input-error');
                valid = false;
            } else if (r.pattern && !r.pattern.test(val)) {
                errEl.textContent = 'Format nomor WhatsApp tidak valid.';
                el.classList.add('input-error');
                valid = false;
            }
        });
        return valid;
    },
    submitMitraForm(e) {
        e.preventDefault();
        if (!this._validateMitraForm()) return;

        const nama = document.getElementById('mitraNama').value.trim();
        const hp = document.getElementById('mitraHP').value.trim();
        const namaUsaha = document.getElementById('mitraNamaUsaha').value.trim();
        const kategori = document.getElementById('mitraKategori').value;
        const lokasi = document.getElementById('mitraLokasi').value;
        const deskripsi = document.getElementById('mitraDeskripsi').value.trim();

        // Simpan ke localStorage sebagai backup
        const data = { nama, hp, namaUsaha, kategori, lokasi, deskripsi, waktu: new Date().toLocaleString('id-ID') };
        const existing = JSON.parse(localStorage.getItem('temujasa_mitra_leads') || '[]');
        existing.push(data);
        localStorage.setItem('temujasa_mitra_leads', JSON.stringify(existing));

        // Kirim via WhatsApp
        const pesan = `Halo TemuJasa! Saya ingin mendaftar sebagai Mitra.%0A%0A` +
            `*Nama Pemilik:* ${nama}%0A` +
            `*No. WhatsApp:* ${hp}%0A` +
            `*Nama Usaha:* ${namaUsaha}%0A` +
            `*Kategori:* ${kategori}%0A` +
            `*Wilayah:* ${lokasi}%0A` +
            (deskripsi ? `*Deskripsi:* ${deskripsi}%0A` : '') +
            `%0ATerima kasih!`;

        const waUrl = `https://wa.me/6281234567890?text=${pesan}`;

        // Tampilkan step sukses
        document.getElementById('mitraFormStep').style.display = 'none';
        document.getElementById('mitraSuccessStep').style.display = 'block';
        document.getElementById('mitraSuccessInfo').innerHTML =
            `<div class="success-detail-row"><i class="fa-solid fa-store"></i><span>${namaUsaha}</span></div>` +
            `<div class="success-detail-row"><i class="fa-solid fa-layer-group"></i><span>${kategori}</span></div>` +
            `<div class="success-detail-row"><i class="fa-solid fa-location-dot"></i><span>${lokasi}</span></div>`;

        // Buka WhatsApp di tab baru
        window.open(waUrl, '_blank');
    },
    _initMitraCharCount() {
        const textarea = document.getElementById('mitraDeskripsi');
        const counter = document.getElementById('mitraCharCount');
        if (!textarea || !counter) return;
        textarea.addEventListener('input', () => {
            counter.textContent = `${textarea.value.length} / 300`;
        });
    },

    /* ---- UMKM TABS ---- */
    switchUmkmTab(tabId, btn) {
        document.querySelectorAll('.umkm-tab-panel').forEach(p => p.style.display = 'none');
        document.querySelectorAll('.umkm-tab-btn').forEach(b => b.classList.remove('active'));
        const panel = document.getElementById(tabId);
        if (panel) panel.style.display = 'block';
        if (btn) btn.classList.add('active');
    },

    /* ---- NAVBAR SEARCH CLEAR ---- */
    _initNavSearch() {
        const input = document.getElementById('navSearchInput');
        const clearBtn = document.getElementById('navSearchClear');
        if (!input || !clearBtn) return;
        input.addEventListener('input', () => {
            clearBtn.style.display = input.value ? 'flex' : 'none';
        });
        clearBtn.addEventListener('click', () => {
            input.value = '';
            clearBtn.style.display = 'none';
            input.focus();
        });
        input.addEventListener('keypress', e => {
            if (e.key === 'Enter' && input.value.trim()) {
                const base = (typeof TJAuth !== 'undefined' && TJAuth.getBaseUrl) ? TJAuth.getBaseUrl() : './';
                window.location.href = base + 'jasa/cari/?q=' + encodeURIComponent(input.value.trim());
            }
        });
        if (input.value) clearBtn.style.display = 'flex';
    },

    /* ---- NAVBAR SCROLL ---- */
    _initNavbarScroll() {
        const navbar = document.getElementById('mainNavbar');
        if (!navbar) return;
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 10);
        });
    },

    /* ---- ETALASE CART BAR ---- */
    _initEtalaseCart() {
        const buttons = document.querySelectorAll('[data-cart-item]');
        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const name = btn.dataset.cartItem;
                const card = btn.closest('.product-card');
                const priceEl = card ? card.querySelector('strong') : null;
                const priceText = priceEl ? priceEl.textContent.replace(/[^0-9]/g, '') : '0';
                this.addToCart(name, parseInt(priceText));
                this._updateCartBar();
            });
        });
    },
    _updateCartBar() {
        const bar = document.querySelector('.cart-summary-bar');
        if (!bar) return;
        const totalQty = _cart.reduce((s, i) => s + i.qty, 0);
        const total = _cart.reduce((s, i) => s + i.price * i.qty, 0);
        const countEl = bar.querySelector('span');
        const totalEl = bar.querySelector('strong');
        if (countEl) countEl.innerHTML = `<i class="fa-solid fa-cart-shopping"></i> ${totalQty} item`;
        if (totalEl) totalEl.textContent = this._formatRp(total);
    },

    /* ---- CLOSE ON OUTSIDE CLICK ---- */
    _initOutsideClick() {
        document.addEventListener('click', e => {
            if (!e.target.closest('.dropdown-wrap')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
            }
        });
    },

    /* ---- INIT ---- */
    init() {
        this.closeCart();
        this.closeLocationModal();
        this.closeMitraModal();
        this._initNavSearch();
        this._initNavbarScroll();
        this._initEtalaseCart();
        this._initOutsideClick();
        this._initMitraCharCount();
        this._renderCart();

        // Hero search enter key
        const heroSearch = document.getElementById('mainSearch');
        if (heroSearch) {
            heroSearch.addEventListener('keypress', e => {
                if (e.key === 'Enter') searchTemuJasa();
            });
        }

        // Init search page filter if on search page
        if (document.getElementById('searchResultsGrid')) {
            this._filterCards();
        }
    }
};

document.addEventListener('DOMContentLoaded', () => TemuJasa.init());
