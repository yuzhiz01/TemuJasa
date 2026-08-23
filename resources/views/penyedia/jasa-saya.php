<?php ($user = auth()->user()); ?>
<?php $__sections['title'] = 'Jasa Saya'; ?>
<?php $__sections['user-initials'] = strtoupper(substr($user->name, 0, 2)); ?>
<?php $__sections['user-name'] = $user->name; ?>
<?php $__sections['role-class'] = 'role-penyedia'; ?>
<?php $__sections['role-label'] = 'Penyedia Jasa'; ?>
<?php $__sections['profile-link'] = route('penyedia.profil'); ?>
<?php $__sections['page-title'] = 'Jasa Saya'; ?>
<?php $__sections['page-subtitle'] = 'Kelola semua layanan yang Anda tawarkan'; ?>

<?php ob_start(); ?>
<span class="sidebar-nav-section">Menu Utama</span>
<a href="<?php echo e(route('penyedia.dashboard')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
<a href="<?php echo e(route('penyedia.jasa-saya')); ?>" class="sidebar-nav-item active"><i class="fa-solid fa-briefcase"></i><span>Jasa Saya</span></a>
<span class="sidebar-nav-section">Aktivitas</span>
<a href="<?php echo e(route('penyedia.pesanan')); ?>" class="sidebar-nav-item"><i class="fa-solid fa-bag-shopping"></i><span>Pesanan</span></a>
<a href="<?php echo e(route('penyedia.chat')); ?>" class="sidebar-nav-item"><i class="fa-regular fa-comment-dots"></i><span>Chat / Pesan</span></a>
<span class="sidebar-nav-section">Akun</span>
<a href="<?php echo e(route('penyedia.profil')); ?>" class="sidebar-nav-item"><i class="fa-regular fa-user"></i><span>Profil Saya</span></a>
<?php $__sections['sidebar-menu'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<button class="btn-primary" onclick="document.getElementById('formTambahJasa').style.display='block'; window.scrollTo({top:0,behavior:'smooth'});">
    <i class="fa-solid fa-plus"></i> Tambah Jasa Baru
</button>
<?php $__sections['page-actions'] = ob_get_clean(); ?>

<?php ob_start(); ?>

<?php if(session('success')): ?>
<div class="alert-success" style="background:#f0fdf4;border:1px solid #86efac;color:#166534;padding:10px 14px;border-radius:8px;margin-bottom:16px;font-size:11px;">
    <i class="fa-solid fa-circle-check"></i> <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<?php if($errors->any()): ?>
<div class="alert-error" style="background:#fef2f2;border:1px solid #fca5a5;color:#991b1b;padding:10px 14px;border-radius:8px;margin-bottom:16px;font-size:11px;">
    <i class="fa-solid fa-circle-exclamation"></i>
    <strong>Periksa kembali isian Anda:</strong>
    <ul style="margin:6px 0 0 18px;">
        <?php foreach ($errors->all() as $error):  ?>
        <li><?php echo e($error); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<!-- FORM TAMBAH JASA -->
<div class="dash-card" id="formTambahJasa" style="display:<?php echo e($errors->any() ? 'block' : 'none'); ?>; margin-bottom:16px;">
    <div class="dash-card-header">
        <span class="dash-card-title">Tambah Jasa Baru</span>
        <span style="cursor:pointer;font-size:11px;color:#888;" onclick="document.getElementById('formTambahJasa').style.display='none';">
            <i class="fa-solid fa-xmark"></i> Tutup
        </span>
    </div>
    <form action="<?php echo e(route('penyedia.jasa-saya.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="form-grid">
            <div class="form-group full">
                <label class="form-label">Nama Jasa <span style="color:red">*</span></label>
                <input type="text" name="title" class="form-input" placeholder="Contoh: Servis AC & Kulkas" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Toko / Mitra <span style="color:red">*</span></label>
                <input type="text" name="shop_name" class="form-input" placeholder="Contoh: Bengkel Jaya Motor" required>
            </div>
            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-select">
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($categories as $cat):  ?>
                    <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Harga (Rp) <span style="color:red">*</span></label>
                <input type="number" name="price" class="form-input" placeholder="100000" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Lokasi</label>
                <input type="text" name="location" id="inputLocation" class="form-input" placeholder="Contoh: Bintan Timur, Kepulauan Riau">
                <div style="display:flex;gap:8px;align-items:center;margin-top:8px;flex-wrap:wrap;">
                    <button type="button" class="btn-secondary btn-sm" onclick="useCurrentLocation('inputLocation')">
                        <i class="fa-solid fa-location-crosshairs"></i> Gunakan GPS
                    </button>
                    <small style="font-size:9px;color:#888;" id="geoStatus"></small>
                </div>
                <input type="hidden" name="latitude" id="inputLatitude">
                <input type="hidden" name="longitude" id="inputLongitude">
            </div>
            <div class="form-group full">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-textarea" rows="3" placeholder="Jelaskan detail layanan Anda..."></textarea>
            </div>
            <div class="form-group full">
                <label class="form-label">Foto / Gambar Jasa</label>
                <input type="file" name="image" class="form-input" accept="image/*"
                    onchange="previewImage(this, 'previewTambah')">
                <img id="previewTambah" src="" alt="" style="display:none;margin-top:8px;width:120px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb;">
            </div>
            <div class="form-group full">
                <button type="submit" class="btn-primary btn-sm" style="width:fit-content;">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Jasa
                </button>
            </div>
        </div>
    </form>
</div>

<!-- LIST JASA -->
<?php if($services->isEmpty()): ?>
<div class="dash-card" style="text-align:center;padding:40px;color:#888;">
    <i class="fa-solid fa-briefcase" style="font-size:36px;margin-bottom:12px;display:block;color:#d1d5db;"></i>
    <p style="font-size:12px;">Belum ada jasa. Klik <strong>Tambah Jasa Baru</strong> untuk mulai.</p>
</div>
<?php else: ?>
<div class="two-col-grid">
    <?php foreach ($services as $s):  ?>
    <div class="dash-card">
        
        <?php if($s->image): ?>
        <img src="<?php echo e(asset('storage/' . $s->image)); ?>" alt="<?php echo e($s->title); ?>"
            style="width:100%;height:140px;object-fit:cover;border-radius:8px;margin-bottom:12px;">
        <?php else: ?>
        <div style="width:100%;height:140px;background:#f0ecff;border-radius:8px;display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
            <i class="fa-solid fa-image" style="font-size:32px;color:#c4b5fd;"></i>
        </div>
        <?php endif; ?>

        <div class="dash-card-header" style="margin-bottom:6px;">
            <span class="dash-card-title"><?php echo e($s->title); ?></span>
            <span class="badge <?php echo e($s->is_active ? 'badge-success' : 'badge-secondary'); ?>">
                <?php echo e($s->is_active ? 'Aktif' : 'Nonaktif'); ?>

            </span>
        </div>

        <div style="font-size:10px;color:#888;margin-bottom:4px;">
            <i class="fa-solid fa-store" style="color:#4f2aa8;"></i> <?php echo e($s->shop_name); ?>

            <?php if($s->category): ?> &nbsp;â€¢&nbsp; <?php echo e($s->category->name); ?> <?php endif; ?>
        </div>
        <div style="font-size:10px;color:#888;margin-bottom:4px;">
            <i class="fa-solid fa-tag" style="color:#4f2aa8;"></i>
            Rp <?php echo e(number_format($s->price, 0, ',', '.')); ?>

            <?php if($s->location): ?>
            &nbsp;â€¢&nbsp; <i class="fa-solid fa-location-dot" style="color:#4f2aa8;"></i> <?php echo e($s->location); ?>

            <?php endif; ?>
        </div>
        <?php if($s->description): ?>
        <p style="font-size:10px;color:#666;margin-top:6px;margin-bottom:10px;line-height:1.5;">
            <?php echo e(Str::limit($s->description, 80)); ?>

        </p>
        <?php endif; ?>

        
        <div style="border-top:1px solid #f0eef4;margin-top:12px;padding-top:12px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <span style="font-size:10px;font-weight:700;color:#17152b;">Opsi Layanan</span>
                <button class="btn-secondary btn-sm" style="font-size:9px;padding:3px 8px;"
                    onclick="openAddOpsi(<?php echo e($s->id); ?>)">
                    <i class="fa-solid fa-plus"></i> Tambah Opsi
                </button>
            </div>
            <?php $__empty_1 = true; foreach ($s->options as $opt):  $__empty_1 = false; ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:6px 8px;background:#f8f7fc;border-radius:6px;margin-bottom:4px;">
                <div>
                    <span style="font-size:10px;font-weight:600;"><?php echo e($opt->name); ?></span>
                    <?php if($opt->description): ?>
                    <p style="font-size:9px;color:#888;margin:1px 0 0;"><?php echo e($opt->description); ?></p>
                    <?php endif; ?>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <span style="font-size:10px;font-weight:700;color:#4f2aa8;">Rp <?php echo e(number_format($opt->price, 0, ',', '.')); ?></span>
                    <button class="btn-secondary btn-sm" style="padding:2px 6px;font-size:9px;"
                        onclick="openEditOpsi(<?php echo e($s->id); ?>, <?php echo e($opt->id); ?>, '<?php echo e(addslashes($opt->name)); ?>', <?php echo e($opt->price); ?>, '<?php echo e(addslashes($opt->description ?? '')); ?>')">  
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <form action="<?php echo e(route('penyedia.jasa-saya.opsi.destroy', [$s->id, $opt->id])); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Hapus opsi ini?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn-secondary btn-sm" style="padding:2px 6px;font-size:9px;color:#ef4444;">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; if ($__empty_1): ?>
            <p style="font-size:9px;color:#aaa;">Belum ada opsi. Tambahkan paket layanan Anda.</p>
            <?php endif; ?>
        </div>

        <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap;">
            
            <button class="btn-secondary btn-sm"
                onclick="openEdit(<?php echo e($s->id); ?>, '<?php echo e(addslashes($s->title)); ?>', '<?php echo e(addslashes($s->shop_name)); ?>', <?php echo e($s->category_id ?? 'null'); ?>, <?php echo e($s->price); ?>, '<?php echo e(addslashes($s->location ?? '')); ?>', '<?php echo e(addslashes($s->description ?? '')); ?>')">
                <i class="fa-solid fa-pen"></i> Edit
            </button>
            
            <form action="<?php echo e(route('penyedia.jasa-saya.toggle', $s->id)); ?>" method="POST" style="display:inline;">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <button type="submit" class="btn-secondary btn-sm">
                    <i class="fa-solid fa-<?php echo e($s->is_active ? 'eye-slash' : 'eye'); ?>"></i>
                    <?php echo e($s->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>

                </button>
            </form>
            
            <form action="<?php echo e(route('penyedia.jasa-saya.destroy', $s->id)); ?>" method="POST" style="display:inline;"
                onsubmit="return confirm('Hapus jasa ini?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn-secondary btn-sm" style="color:#ef4444;">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- MODAL EDIT JASA -->
<div id="modalEdit" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:24px;width:100%;max-width:560px;max-height:90vh;overflow-y:auto;margin:16px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <strong style="font-size:13px;">Edit Jasa</strong>
            <span style="cursor:pointer;color:#888;" onclick="document.getElementById('modalEdit').style.display='none';">
                <i class="fa-solid fa-xmark"></i>
            </span>
        </div>
        <form id="formEdit" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label">Nama Jasa</label>
                    <input type="text" name="title" id="editTitle" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Toko / Mitra</label>
                    <input type="text" name="shop_name" id="editShopName" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" id="editCategory" class="form-select">
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($categories as $cat):  ?>
                        <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" id="editPrice" class="form-input" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="location" id="editLocation" class="form-input">
                    <div style="display:flex;gap:8px;align-items:center;margin-top:8px;flex-wrap:wrap;">
                        <button type="button" class="btn-secondary btn-sm" onclick="useCurrentLocation('editLocation')">
                            <i class="fa-solid fa-location-crosshairs"></i> Gunakan GPS
                        </button>
                        <small style="font-size:9px;color:#888;" id="editGeoStatus"></small>
                    </div>
                    <input type="hidden" name="latitude" id="editLatitude">
                    <input type="hidden" name="longitude" id="editLongitude">
                </div>
                <div class="form-group full">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" id="editDescription" class="form-textarea" rows="3"></textarea>
                </div>
                <div class="form-group full">
                    <label class="form-label">Ganti Foto (opsional)</label>
                    <input type="file" name="image" class="form-input" accept="image/*"
                        onchange="previewImage(this, 'previewEdit')">
                    <img id="previewEdit" src="" alt="" style="display:none;margin-top:8px;width:120px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb;">
                </div>
                <div class="form-group full">
                    <button type="submit" class="btn-primary btn-sm" style="width:fit-content;">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL TAMBAH OPSI -->
<div id="modalAddOpsi" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:24px;width:100%;max-width:420px;margin:16px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <strong style="font-size:13px;">Tambah Opsi Layanan</strong>
            <span style="cursor:pointer;color:#888;" onclick="document.getElementById('modalAddOpsi').style.display='none';">
                <i class="fa-solid fa-xmark"></i>
            </span>
        </div>
        <form id="formAddOpsi" method="POST">
            <?php echo csrf_field(); ?>
            <div class="form-group" style="margin-bottom:12px;">
                <label class="form-label">Nama Opsi <span style="color:red">*</span></label>
                <input type="text" name="name" class="form-input" placeholder="Contoh: Paket Basic" required>
            </div>
            <div class="form-group" style="margin-bottom:12px;">
                <label class="form-label">Harga (Rp) <span style="color:red">*</span></label>
                <input type="number" name="price" class="form-input" placeholder="150000" min="0" required>
            </div>
            <div class="form-group" style="margin-bottom:16px;">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea name="description" class="form-textarea" rows="2" placeholder="Apa yang termasuk dalam paket ini..."></textarea>
            </div>
            <button type="submit" class="btn-primary btn-sm" style="width:100%;justify-content:center;">
                <i class="fa-solid fa-plus"></i> Tambah Opsi
            </button>
        </form>
    </div>
</div>

<!-- MODAL EDIT OPSI -->
<div id="modalEditOpsi" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:24px;width:100%;max-width:420px;margin:16px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <strong style="font-size:13px;">Edit Opsi Layanan</strong>
            <span style="cursor:pointer;color:#888;" onclick="document.getElementById('modalEditOpsi').style.display='none';">
                <i class="fa-solid fa-xmark"></i>
            </span>
        </div>
        <form id="formEditOpsi" method="POST">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="form-group" style="margin-bottom:12px;">
                <label class="form-label">Nama Opsi <span style="color:red">*</span></label>
                <input type="text" name="name" id="editOpsiName" class="form-input" required>
            </div>
            <div class="form-group" style="margin-bottom:12px;">
                <label class="form-label">Harga (Rp) <span style="color:red">*</span></label>
                <input type="number" name="price" id="editOpsiPrice" class="form-input" min="0" required>
            </div>
            <div class="form-group" style="margin-bottom:16px;">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea name="description" id="editOpsiDesc" class="form-textarea" rows="2"></textarea>
            </div>
            <button type="submit" class="btn-primary btn-sm" style="width:100%;justify-content:center;">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>

<?php $__sections['content'] = ob_get_clean(); ?>

<?php ob_start(); ?>
<script>
// â”€â”€ GEOLOKASI (Browser Geolocation API + Nominatim OpenStreetMap) â”€â”€
function useCurrentLocation(locInputId, statusId) {
    const status = document.getElementById(statusId || (locInputId === 'editLocation' ? 'editGeoStatus' : 'geoStatus'));
    const latInput  = document.getElementById(locInputId === 'editLocation' ? 'editLatitude' : 'inputLatitude');
    const lngInput  = document.getElementById(locInputId === 'editLocation' ? 'editLongitude' : 'inputLongitude');

    if (!('geolocation' in navigator)) {
        status.textContent = 'Browser tidak mendukung geolokasi.';
        return;
    }
    status.textContent = 'Mengambil lokasi...';

    navigator.geolocation.getCurrentPosition(async (pos) => {
        const lat = pos.coords.latitude.toFixed(7);
        const lng = pos.coords.longitude.toFixed(7);
        latInput.value = lat;
        lngInput.value = lng;

        // alamat otomatis dari koordinat (OpenStreetMap Nominatim, gratis)
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=16&addressdetails=1`);
            const data = await res.json();
            if (data.display_name) {
                document.getElementById(locInputId).value = data.display_name;
            }
        } catch (e) { /* alamat gagal, koordinat tetap tersimpan */ }

        status.textContent = `Lokasi terkunci (${lat}, ${lng})`;
    }, (err) => {
        status.textContent = err.code === 1
            ? 'Izin lokasi ditolak. Aktifkan izin lokasi di browser.'
            : 'Gagal mengambil lokasi. Coba lagi.';
    }, { enableHighAccuracy: true, timeout: 10000 });
}

function openEdit(id, title, shopName, categoryId, price, location, description) {
    document.getElementById('editTitle').value = title;
    document.getElementById('editShopName').value = shopName;
    document.getElementById('editPrice').value = price;
    document.getElementById('editLocation').value = location;
    document.getElementById('editDescription').value = description;
    document.getElementById('formEdit').action = '/penyedia/jasa-saya/' + id;
    const sel = document.getElementById('editCategory');
    for (let o of sel.options) o.selected = (o.value == categoryId);
    document.getElementById('modalEdit').style.display = 'flex';
}

function openAddOpsi(serviceId) {
    document.getElementById('formAddOpsi').action = '/penyedia/jasa-saya/' + serviceId + '/opsi';
    document.getElementById('formAddOpsi').reset();
    document.getElementById('modalAddOpsi').style.display = 'flex';
}

function openEditOpsi(serviceId, optionId, name, price, description) {
    document.getElementById('editOpsiName').value = name;
    document.getElementById('editOpsiPrice').value = price;
    document.getElementById('editOpsiDesc').value = description;
    document.getElementById('formEditOpsi').action = '/penyedia/jasa-saya/' + serviceId + '/opsi/' + optionId;
    document.getElementById('modalEditOpsi').style.display = 'flex';
}

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}

['modalEdit','modalAddOpsi','modalEditOpsi'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
});
</script>
<?php $__sections['scripts'] = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/dashboard.php'; ?>
