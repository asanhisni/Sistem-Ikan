@extends('layouts.app')

@section('title', 'Edit Kepala Cabang')
@section('page-title', 'Edit Kepala Cabang')

@push('styles')
<style>
    .form-container {
        max-width: 680px;
        margin: 0 auto;
        padding-bottom: 40px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 18px;
        transition: color 0.15s ease;
    }

    .back-link:hover {
        color: #2563eb;
    }

    .form-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border: 1px solid #eef2f6;
    }

    /* ─── HEADER (NAVY / BLUE TEMA APLIKASI) ─── */
    .form-card-header {
        padding: 24px 28px;
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .form-card-header-avatar {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 800;
        flex-shrink: 0;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
    }

    .form-card-header-text h3 {
        font-size: 19px;
        font-weight: 800;
        margin: 0 0 4px 0;
        color: #ffffff;
        letter-spacing: -0.01em;
    }

    .form-card-header-text p {
        font-size: 13px;
        color: #94a3b8;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .form-card-header-text .header-badge {
        display: inline-block;
        padding: 2px 8px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 6px;
        font-size: 11px;
        color: #cbd5e1;
        font-weight: 600;
    }

    /* ─── BODY & SECTIONS ─── */
    .form-body {
        padding: 28px 30px;
    }

    @media (max-width: 640px) {
        .form-body {
            padding: 20px 18px;
        }
        .form-card-header {
            padding: 20px 18px;
        }
    }

    .form-section-title {
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #475569;
        margin-bottom: 18px;
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

    .form-section-divider {
        margin-top: 32px;
    }

    /* ─── FORM GROUP & CONTROLS ─── */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-group label {
        display: flex;
        align-items: center;
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .form-group label span.req {
        color: #ef4444;
        margin-left: 3px;
    }

    .form-group label .opt-badge {
        font-size: 11px;
        background: #f1f5f9;
        color: #64748b;
        padding: 2px 8px;
        border-radius: 20px;
        margin-left: 8px;
        font-weight: 600;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14.5px;
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

    .form-control::placeholder {
        color: #94a3b8;
    }

    .invalid-feedback {
        display: block;
        font-size: 12px;
        color: #ef4444;
        margin-top: 6px;
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

    /* ─── PASSWORD INPUT ─── */
    .password-wrap {
        position: relative;
    }

    .password-wrap .form-control {
        padding-right: 44px;
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
        font-size: 15px;
        padding: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.15s ease;
    }

    .password-toggle:hover {
        color: #2563eb;
    }

    .password-hint {
        font-size: 12px;
        color: #64748b;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .password-hint i {
        color: #3b82f6;
    }

    /* ─── CABANG INFO BOX ─── */
    .cabang-info-box {
        margin-top: 10px;
        padding: 12px 16px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        border-radius: 12px;
        font-size: 13px;
        color: #1e40af;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ─── FOOTER ─── */
    .form-footer {
        padding: 20px 30px;
        border-top: 1px solid #f1f5f9;
        background: #f8fafc;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 26px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.25);
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
        color: #ffffff;
    }

    .btn-submit:active {
        transform: scale(0.98);
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 22px;
        background: #ffffff;
        color: #475569;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s ease;
        font-family: inherit;
    }

    .btn-cancel:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    @media (max-width: 640px) {
        .form-footer {
            flex-direction: column-reverse;
            padding: 16px 18px;
        }
        .btn-submit,
        .btn-cancel {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <a href="{{ route('users.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Daftar</span>
    </a>

    <div class="form-card">
        <!-- Header Card: Navy Theme -->
        <div class="form-card-header">
            <div class="form-card-header-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div class="form-card-header-text">
                <h3>Edit Kepala Cabang</h3>
                <p>
                    <span>{{ $user->name }}</span>
                    <span>·</span>
                    <span>{{ '@' . $user->username }}</span>
                    <span class="header-badge">Kepala Cabang</span>
                </p>
            </div>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-body">

                <!-- 1. INFORMASI AKUN -->
                <div class="form-section-title">
                    <i class="fas fa-user"></i>
                    <span>Informasi Akun</span>
                </div>

                <div class="form-group">
                    <label for="name">Nama Lengkap <span class="req">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $user->name) }}"
                           placeholder="Masukkan nama lengkap">
                    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username <span class="req">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                               id="username" name="username" value="{{ old('username', $user->username) }}"
                               placeholder="Username">
                        @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="req">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', $user->email) }}"
                               placeholder="contoh@email.com">
                        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <!-- 2. GANTI PASSWORD -->
                <div class="form-section-title form-section-divider">
                    <i class="fas fa-lock"></i>
                    <span>Ganti Password</span>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">
                            Password Baru
                            <span class="opt-badge">Opsional</span>
                        </label>
                        <div class="password-wrap">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" placeholder="Kosongkan jika tidak diganti">
                            <button type="button" class="password-toggle" onclick="togglePwd('password','eye1')" title="Lihat Password">
                                <i class="fas fa-eye" id="eye1"></i>
                            </button>
                        </div>
                        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        <div class="password-hint">
                            <i class="fas fa-info-circle"></i>
                            <span>Kosongkan jika tidak ingin mengganti password.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <div class="password-wrap">
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                            <button type="button" class="password-toggle" onclick="togglePwd('password_confirmation','eye2')" title="Lihat Password">
                                <i class="fas fa-eye" id="eye2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 3. PENEMPATAN CABANG -->
                <div class="form-section-title form-section-divider">
                    <i class="fas fa-store"></i>
                    <span>Penempatan Cabang</span>
                </div>

                <div class="form-group">
                    <label for="cabang_id">Cabang Ditugaskan <span class="req">*</span></label>
                    <select class="form-control @error('cabang_id') is-invalid @enderror"
                            id="cabang_id" name="cabang_id" onchange="showCabangInfo()">
                        <option value="">-- Pilih Cabang --</option>
                        @foreach($cabangTersedia as $c)
                            <option value="{{ $c->id }}"
                                data-lokasi="{{ $c->lokasi }}"
                                data-kode="{{ $c->kode_cabang }}"
                                {{ old('cabang_id', $user->cabang_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                    @error('cabang_id')<span class="invalid-feedback">{{ $message }}</span>@enderror

                    <div class="cabang-info-box" id="cabangInfoBox" style="display:none;">
                        <i class="fas fa-map-marker-alt"></i>
                        <span id="cabangInfoText"></span>
                    </div>
                </div>
            </div>

            <div class="form-footer">
                <a href="{{ route('users.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i>
                    <span>Batal</span>
                </a>
                <button type="submit" class="btn-submit" id="btn-update-kepala">
                    <i class="fas fa-save"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePwd(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon  = document.getElementById(iconId);
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

function showCabangInfo() {
    const select = document.getElementById('cabang_id');
    const infoBox = document.getElementById('cabangInfoBox');
    const infoText = document.getElementById('cabangInfoText');
    const option = select.options[select.selectedIndex];
    if (option && option.value) {
        const lokasi = option.getAttribute('data-lokasi');
        const kode   = option.getAttribute('data-kode');
        infoText.textContent = `Kode: ${kode} · Lokasi: ${lokasi || 'Belum diatur'}`;
        infoBox.style.display = 'flex';
    } else {
        infoBox.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    showCabangInfo();
});
</script>
@endpush
