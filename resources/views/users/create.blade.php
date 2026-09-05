@extends('layouts.app')

@section('title', 'Tambah Kepala Cabang')
@section('page-title', 'Tambah Kepala Cabang')

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
        padding: 24px 28px;
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 16px;
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
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(30,136,229,0.1);
    }
    .form-control.is-invalid {
        border-color: var(--red);
    }
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
    .password-toggle:hover { color: var(--blue); }

    .cabang-info-box {
        margin-top: 10px;
        padding: 12px 16px;
        background: var(--blue-light);
        border-radius: 10px;
        font-size: 13px;
        color: var(--blue);
        font-weight: 600;
        display: none;
    }
    .cabang-info-box i { margin-right: 6px; }

    .no-cabang-box {
        padding: 20px;
        background: #fff8e1;
        border-radius: 12px;
        border: 1px dashed #f59e0b;
        text-align: center;
        color: #92400e;
        font-size: 14px;
        font-weight: 600;
    }
    .no-cabang-box i { font-size: 22px; margin-bottom: 8px; display: block; }

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
        background: linear-gradient(135deg, #1e88e5, #42a5f5);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(30,136,229,0.28);
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(30,136,229,0.38); }
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
            <h3><i class="fas fa-user-plus"></i> Tambah Kepala Cabang</h3>
            <p>Isi data akun dan pilih cabang yang akan ditugaskan</p>
        </div>

        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="form-body">

                <!-- Informasi Akun -->
                <div class="form-section-title">Informasi Akun</div>

                <div class="form-group">
                    <label for="name">Nama Lengkap <span class="req">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}"
                           placeholder="Masukkan nama lengkap" autocomplete="off">
                    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username <span class="req">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                               id="username" name="username" value="{{ old('username') }}"
                               placeholder="Contoh: john_doe" autocomplete="off">
                        @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="req">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}"
                               placeholder="contoh@email.com" autocomplete="off">
                        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password <span class="req">*</span></label>
                        <div class="password-wrap">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" placeholder="Min. 6 karakter">
                            <button type="button" class="password-toggle" onclick="togglePwd('password','eye1')">
                                <i class="fas fa-eye" id="eye1"></i>
                            </button>
                        </div>
                        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password <span class="req">*</span></label>
                        <div class="password-wrap">
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation" placeholder="Ulangi password">
                            <button type="button" class="password-toggle" onclick="togglePwd('password_confirmation','eye2')">
                                <i class="fas fa-eye" id="eye2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Penempatan Cabang -->
                <div class="form-section-title" style="margin-top:8px;">Penempatan Cabang</div>

                @if($cabangTersedia->isEmpty())
                    <div class="no-cabang-box">
                        <i class="fas fa-exclamation-triangle"></i>
                        Semua cabang sudah memiliki kepala cabang.<br>
                        <small style="font-weight:400;opacity:0.8;">Tambahkan cabang baru atau hapus kepala cabang yang ada untuk mengosongkan slot.</small>
                    </div>
                @else
                    <div class="form-group">
                        <label for="cabang_id">Pilih Cabang <span class="req">*</span></label>
                        <select class="form-control @error('cabang_id') is-invalid @enderror"
                                id="cabang_id" name="cabang_id" onchange="showCabangInfo()">
                            <option value="">-- Pilih Cabang --</option>
                            @foreach($cabangTersedia as $c)
                                <option value="{{ $c->id }}"
                                    data-lokasi="{{ $c->lokasi }}"
                                    data-kode="{{ $c->kode_cabang }}"
                                    {{ old('cabang_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                        @error('cabang_id')<span class="invalid-feedback">{{ $message }}</span>@enderror

                        <div class="cabang-info-box" id="cabangInfoBox">
                            <i class="fas fa-map-marker-alt"></i>
                            <span id="cabangInfoText"></span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="form-footer">
                <a href="{{ route('users.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
                @if(!$cabangTersedia->isEmpty())
                <button type="submit" class="btn-submit" id="btn-simpan-kepala">
                    <i class="fas fa-save"></i> Simpan Kepala Cabang
                </button>
                @endif
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

// Trigger saat halaman load (jika ada old value)
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('cabang_id')) showCabangInfo();
});
</script>
@endpush
