@extends('layouts.app')

@section('title', 'Detail Profil Saya')
@section('page-title', 'Detail Profil Saya')

@push('styles')
<style>
    .profile-container {
        max-width: 860px;
        margin: 0 auto;
        padding-bottom: 40px;
    }

    /* ─── ALERT SUKSES ─── */
    .alert-sukses {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #edfdf2;
        color: #1b5e20;
        border: 1px solid #cfeecf;
        border-radius: 14px;
        padding: 14px 16px;
        margin-bottom: 20px;
        font-weight: 700;
        font-size: 14px;
    }

    /* ─── HERO PROFILE CARD ─── */
    .profile-hero-card {
        background: linear-gradient(135deg, #0d1b2a 0%, #1e293b 100%);
        border-radius: 20px;
        padding: 28px 24px;
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(13, 27, 42, 0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
        position: relative;
        /* overflow: hidden dihapus agar avatar & tombol-tombol tidak terpotong */
    }

    /* Dekorasi latar kanan-bawah — pakai pseudo + clip agar tidak memotong avatar */
    .profile-hero-card::after {
        content: '';
        position: absolute;
        right: -30px;
        bottom: -30px;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.25) 0%, rgba(37, 99, 235, 0) 70%);
        pointer-events: none;
        z-index: 0;
    }

    .hero-left {
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        z-index: 1;
    }

    /* ─── AVATAR UPLOAD WRAPPER (1:1 SQUARE/CIRCLE PREVIEW) ─── */
    .hero-avatar-wrapper {
        position: relative;
        width: 86px;
        height: 86px;
        flex-shrink: 0;
        /* Pastikan wrapper tidak di-clip oleh parent */
        overflow: visible;
    }

    .hero-avatar {
        width: 86px;
        height: 86px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1e88e5, #42a5f5);
        color: #ffffff;
        font-size: 32px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 20px rgba(30, 136, 229, 0.35);
        border: 3px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        aspect-ratio: 1 / 1;
    }

    .hero-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .btn-avatar-trigger {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #2563eb;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        cursor: pointer;
        border: 2.5px solid #0f172a;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        transition: transform 0.15s ease, background 0.15s ease;
    }

    .btn-avatar-trigger:hover {
        background: #1d4ed8;
        transform: scale(1.1);
    }

    .btn-avatar-delete {
        position: absolute;
        top: 0;
        right: 0;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #ef4444;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        cursor: pointer;
        border: 2.5px solid #0f172a;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.35);
        transition: transform 0.15s ease, background 0.15s ease;
        padding: 0;
    }

    .btn-avatar-delete:hover {
        background: #dc2626;
        transform: scale(1.1);
    }

    .hero-info h2 {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 4px;
        color: #ffffff;
        letter-spacing: -0.01em;
    }

    .hero-info .hero-username {
        font-size: 13.5px;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
        margin-bottom: 8px;
    }

    .hero-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .hero-badge-role {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        background: rgba(37, 99, 235, 0.25);
        border: 1px solid rgba(37, 99, 235, 0.4);
        color: #93c5fd;
    }

    .hero-badge-branch {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        background: rgba(16, 185, 129, 0.2);
        border: 1px solid rgba(16, 185, 129, 0.35);
        color: #6ee7b7;
    }

    @media (max-width: 640px) {
        .profile-hero-card {
            flex-direction: column;
            align-items: flex-start;
            padding: 20px 18px;
        }
        .hero-left {
            flex-direction: column;
            align-items: flex-start;
            gap: 14px;
        }
    }

    /* ─── MAIN FORM CARD ─── */
    .profile-form-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #eef2f6;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .profile-card-header {
        padding: 20px 24px;
        background: #ffffff;
        border-bottom: 1.5px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .profile-card-header h3 {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .profile-card-header h3 i {
        color: #2563eb;
    }

    .profile-body {
        padding: 24px;
    }

    .form-section-title {
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #475569;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 1.5px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-section-title i {
        color: #2563eb;
        font-size: 13px;
    }

    /* ─── UPLOAD PHOTO BOX (DROP/PREVIEW ZONE) ─── */
    .upload-photo-section {
        display: flex;
        align-items: center;
        gap: 16px;
        background: #f8fafc;
        border: 1.5px dashed #cbd5e1;
        border-radius: 16px;
        padding: 14px 16px;
        margin-bottom: 22px;
        transition: border-color 0.15s ease;
    }

    .upload-photo-section:hover {
        border-color: #2563eb;
    }

    .photo-preview-box {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #eff6ff;
        border: 2px solid #dbeafe;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
        aspect-ratio: 1 / 1;
    }

    .photo-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .photo-upload-meta {
        flex: 1;
    }

    .btn-choose-photo {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        padding: 7px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
        cursor: pointer;
        transition: all 0.15s ease;
        margin-bottom: 4px;
    }

    .btn-choose-photo:hover {
        background: #eff6ff;
        border-color: #2563eb;
        color: #2563eb;
    }

    .photo-hint-text {
        font-size: 11.5px;
        color: #64748b;
        line-height: 1.35;
    }

    .photo-hint-text strong {
        color: #2563eb;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: flex;
        align-items: center;
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 7px;
    }

    .form-group label span.req {
        color: #ef4444;
        margin-left: 3px;
    }

    .form-control {
        width: 100%;
        padding: 12px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        font-family: inherit;
        color: #0f172a;
        background: #ffffff;
        outline: none;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        background: #ffffff;
    }

    .form-control.is-invalid {
        border-color: #ef4444;
        background: #fffcfc;
    }

    .invalid-feedback {
        display: block;
        font-size: 12px;
        color: #ef4444;
        margin-top: 5px;
        font-weight: 600;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 600px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
    }

    .password-wrap {
        position: relative;
    }

    .password-wrap .form-control {
        padding-right: 42px;
    }

    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 14px;
        padding: 4px;
        transition: color 0.15s ease;
    }

    .password-toggle:hover {
        color: #2563eb;
    }

    .info-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 12.5px;
        color: #475569;
        display: flex;
        align-items: flex-start;
        gap: 8px;
        line-height: 1.4;
        margin-top: 6px;
    }

    .info-box i {
        color: #3b82f6;
        margin-top: 2px;
    }

    .profile-footer {
        padding: 18px 24px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-save-profile {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 28px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.28);
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .btn-save-profile:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.38);
    }

    .btn-save-profile:active {
        transform: scale(0.98);
    }
</style>
@endpush

@section('content')
<div class="profile-container">

    @if(session('success'))
    <div class="alert-sukses">
        <i class="fas fa-check-circle" style="font-size: 20px; color: #16a34a;"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    <!-- ════ FORM UTAMA UPDATE PROFIL ════ -->
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
        @csrf
        @method('PUT')

        <!-- Input File Foto Tersembunyi -->
        <input type="file" name="foto" id="fotoInput" accept="image/jpeg,image/png,image/jpg,image/webp" style="display: none;" onchange="handlePhotoSelect(event)">

        <!-- ════ HERO PROFILE CARD ════ -->
        <div class="profile-hero-card">
            <div class="hero-left">
                <!-- AVATAR DENGAN FOTO ATAU INISIAL -->
                <div class="hero-avatar-wrapper">
                    <div class="hero-avatar" id="heroAvatarPreview">
    @if(!empty($user->foto))
        <img src="{{ asset($user->foto) }}?v={{ $user->updated_at ? $user->updated_at->timestamp : time() }}"
             alt="{{ $user->name }}"
             id="heroAvatarImg"
             onerror="this.style.display='none'; document.getElementById('heroAvatarInitial').style.display='flex';">
        <span id="heroAvatarInitial" style="display:none;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
    @else
        <span id="heroAvatarInitial">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
    @endif
                    </div>

                    <!-- Tombol Ganti / Upload Foto -->
                    <label for="fotoInput" class="btn-avatar-trigger" title="Ubah Foto Profil">
                        <i class="fas fa-camera"></i>
                    </label>

                    <!-- Tombol Hapus Foto Profil (Jika sudah ada foto) -->
                    @if(!empty($user->foto))
                    <button type="button" class="btn-avatar-delete" title="Hapus Foto Profil" onclick="confirmDeletePhoto()">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    @endif
                </div>

                <div class="hero-info">
                    <h2>{{ $user->name }}</h2>
                    <div class="hero-username"><i class="fas fa-at"></i> {{ $user->username }}</div>
                    <div class="hero-badges">
                        <span class="hero-badge-role">
                            <i class="fas fa-shield-alt"></i>
                            <span>{{ $user->role === 'admin' ? 'Administrator' : 'Kepala Cabang' }}</span>
                        </span>
                        @if($user->cabangRelasi || $user->cabang)
                        <span class="hero-badge-branch">
                            <i class="fas fa-store"></i>
                            <span>Cabang: {{ $user->cabangRelasi ? $user->cabangRelasi->nama_cabang : $user->cabang }}</span>
                        </span>
                        @else
                        <span class="hero-badge-branch" style="background: rgba(148, 163, 184, 0.2); color: #cbd5e1; border-color: rgba(148, 163, 184, 0.3);">
                            <i class="fas fa-store"></i>
                            <span>Pusat / Kantor Utama</span>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ════ FORM DETAIL & UPDATE PROFIL ════ -->
        <div class="profile-form-card">
            <div class="profile-card-header">
                <h3><i class="fas fa-user-edit"></i> Edit Informasi Akun</h3>
            </div>

            @error('foto')
                <div style="padding: 12px 24px 0 24px;">
                    <div class="invalid-feedback">{{ $message }}</div>
                </div>
            @enderror

            <div class="profile-body">

                <!-- 1. DATA IDENTITAS -->
                <div class="form-section-title">
                    <i class="fas fa-id-card"></i>
                    <span>Informasi Pribadi & Kontak</span>
                </div>

                <div class="form-group">
                    <label for="name">Nama Lengkap <span class="req">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $user->name) }}"
                           placeholder="Masukkan nama lengkap" required>
                    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username <span class="req">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                               id="username" name="username" value="{{ old('username', $user->username) }}"
                               placeholder="Username akun" required>
                        @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Alamat Email <span class="req">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', $user->email) }}"
                               placeholder="contoh@email.com" required>
                        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telepon">No. WhatsApp / HP</label>
                        <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                               id="telepon" name="telepon" value="{{ old('telepon', $user->telepon) }}"
                               placeholder="Contoh: 08123456789">
                        @error('telepon')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="recovery_pin">PIN Pemulihan Akun <span class="req">*</span></label>
                        <div class="password-wrap">
                            <input type="password" class="form-control @error('recovery_pin') is-invalid @enderror"
                                   id="recovery_pin" name="recovery_pin" value="{{ old('recovery_pin', $user->recovery_pin ?? '123456') }}"
                                   placeholder="Kode PIN darurat" required>
                            <button type="button" class="password-toggle" onclick="toggleField('recovery_pin', 'eyePin')" title="Lihat PIN">
                                <i class="fas fa-eye" id="eyePin"></i>
                            </button>
                        </div>
                        @error('recovery_pin')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        <div class="info-box">
                            <i class="fas fa-shield-alt"></i>
                            <span>PIN ini digunakan untuk memulihkan akun saat lupa password di halaman login.</span>
                        </div>
                    </div>
                </div>

                <!-- 3. GANTI PASSWORD -->
                <div class="form-section-title" style="margin-top: 28px;">
                    <i class="fas fa-lock"></i>
                    <span>Keamanan & Password Baru (Opsional)</span>
                </div>

                <div class="form-group">
                    <label for="current_password">Password Saat Ini</label>
                    <div class="password-wrap">
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                               id="current_password" name="current_password" placeholder="Wajib diisi jika ingin mengganti password">
                        <button type="button" class="password-toggle" onclick="toggleField('current_password', 'eyeCurrent')" title="Lihat Password">
                            <i class="fas fa-eye" id="eyeCurrent"></i>
                        </button>
                    </div>
                    @error('current_password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <div class="password-wrap">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" placeholder="Minimal 6 karakter">
                            <button type="button" class="password-toggle" onclick="toggleField('password', 'eyeNew1')" title="Lihat Password">
                                <i class="fas fa-eye" id="eyeNew1"></i>
                            </button>
                        </div>
                        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <div class="password-wrap">
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                            <button type="button" class="password-toggle" onclick="toggleField('password_confirmation', 'eyeNew2')" title="Lihat Password">
                                <i class="fas fa-eye" id="eyeNew2"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="profile-footer">
                <button type="submit" class="btn-save-profile" id="btnSaveProfile">
                    <i class="fas fa-save"></i>
                    <span>Simpan Perubahan Profil</span>
                </button>
            </div>
        </div>
    </form>

    <!-- Form Tersembunyi untuk Hapus Foto Profil -->
    <form action="{{ route('profile.photo.destroy') }}" method="POST" id="formDeletePhoto" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

</div>
@endsection

@push('scripts')
<script>
function toggleField(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (!input || !icon) return;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// ─── LIVE PREVIEW FOTO PROFIL SAAT DIPILIH ───
function handlePhotoSelect(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const resultUrl = e.target.result;

        // Update preview di hero avatar
        const heroAvatar = document.getElementById('heroAvatarPreview');
        if (heroAvatar) {
            heroAvatar.innerHTML = `<img src="${resultUrl}" alt="Preview" id="heroAvatarImg">`;
        }
    };
    reader.readAsDataURL(file);
}

// ─── KONFIRMASI HAPUS FOTO PROFIL VIA SWEETALERT2 ───
function confirmDeletePhoto() {
    Swal.fire({
        title: 'Hapus Foto Profil?',
        text: 'Foto profil Anda akan dihapus dan dikembalikan ke inisial nama.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-trash-alt"></i> Ya, Hapus Foto',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formDeletePhoto').submit();
        }
    });
}

document.getElementById('profileForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnSaveProfile');
    if (btn) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan & Mengompres...';
        btn.disabled = true;
    }
});
</script>
@endpush
