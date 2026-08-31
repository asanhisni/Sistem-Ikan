@extends('layouts.app')

@section('title', 'Edit Kepala Cabang')
@section('page-title', 'Edit Kepala Cabang')

@push('styles')
<style>
    .form-container {
        max-width: 680px;
        margin: 0 auto;
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
        transition: color 0.15s;
    }
    .back-link:hover { color: var(--blue); }

    .form-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: var(--elev);
        overflow: hidden;
        border: 1px solid rgba(11,35,60,0.04);
    }
    .form-card-header {
        padding: 28px 32px 24px;
        background: linear-gradient(135deg, #43a047, #66bb6a);
        color: #fff;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .form-card-header-avatar {
        width: 52px; height: 52px;
        border-radius: 14px;
        background: rgba(255,255,255,0.25);
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; font-weight: 800;
        flex-shrink: 0;
    }
    .form-card-header h3 { font-size: 20px; font-weight: 800; margin-bottom: 4px; }
    .form-card-header p  { font-size: 13px; opacity: 0.85; }
    .form-body { padding: 32px; }

    .form-section-title {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--border);
    }
    .form-group { margin-bottom: 20px; }
    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 7px;
    }
    .form-group label span.req { color: var(--red); margin-left: 3px; }
    .form-group label .opt-badge {
        font-size: 11px;
        background: #f3f4f6;
        color: var(--text-muted);
        padding: 2px 8px;
        border-radius: 20px;
        margin-left: 6px;
        font-weight: 600;
    }
    .form-control {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        color: var(--text);
        background: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--green);
        box-shadow: 0 0 0 3px rgba(67,160,71,0.1);
    }
    .form-control.is-invalid { border-color: var(--red); }
    .invalid-feedback {
        display: block;
        font-size: 12px;
        color: var(--red);
        margin-top: 5px;
        font-weight: 600;
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    @media(max-width:600px){ .form-row { grid-template-columns: 1fr; } }

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
        color: var(--text-muted);
        cursor: pointer;
        font-size: 14px;
        padding: 4px;
        transition: color 0.15s;
    }
    .password-toggle:hover { color: var(--green); }

    .cabang-info-box {
        margin-top: 10px;
        padding: 12px 16px;
        background: var(--green-light);
        border-radius: 10px;
        font-size: 13px;
        color: var(--green);
        font-weight: 600;
    }
    .cabang-info-box i { margin-right: 6px; }

    .password-hint {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-footer {
        padding: 24px 32px;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }
    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 28px;
        background: linear-gradient(135deg, #43a047, #66bb6a);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(67,160,71,0.28);
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(67,160,71,0.38); }
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: #f3f4f6;
        color: var(--text);
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s;
        font-family: 'Inter', sans-serif;
    }
    .btn-cancel:hover { background: #e5e7eb; }
</style>
@endpush

@section('content')
<div class="form-container">
    <a href="{{ route('users.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>

    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-header-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div>
                <h3>Edit Kepala Cabang</h3>
                <p>{{ $user->name }} · @{{ $user->username }}</p>
            </div>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-body">

                <!-- Informasi Akun -->
                <div class="form-section-title">Informasi Akun</div>

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

                <!-- Ganti Password -->
                <div class="form-section-title" style="margin-top:8px;">Ganti Password</div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">
                            Password Baru
                            <span class="opt-badge">Opsional</span>
                        </label>
                        <div class="password-wrap">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" placeholder="Kosongkan jika tidak diganti">
                            <button type="button" class="password-toggle" onclick="togglePwd('password','eye1')">
                                <i class="fas fa-eye" id="eye1"></i>
                            </button>
                        </div>
                        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        <div class="password-hint"><i class="fas fa-info-circle"></i> Kosongkan jika tidak ingin mengganti password.</div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <div class="password-wrap">
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                            <button type="button" class="password-toggle" onclick="togglePwd('password_confirmation','eye2')">
                                <i class="fas fa-eye" id="eye2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Penempatan Cabang -->
                <div class="form-section-title" style="margin-top:8px;">Penempatan Cabang</div>

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
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn-submit" id="btn-update-kepala">
                    <i class="fas fa-save"></i> Simpan Perubahan
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
        infoBox.style.display = 'block';
    } else {
        infoBox.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    showCabangInfo();
});
</script>
@endpush
