@extends('layouts.app')

@section('title', 'Buat Transaksi Baru')
@section('page-title', 'Form Transaksi Baru')

@push('styles')
<style>
    .form-container {
        background: white;
        max-width: 700px;
        margin: 0 auto;
        padding: 32px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid #e8ecf0;
    }
    
    .form-group {
        margin-bottom: 24px;
    }
    
    .form-group label {
        display: block;
        font-size: 16px;
        font-weight: 700;
        color: #0d1b2a;
        margin-bottom: 8px;
    }
    
    .form-control {
        width: 100%;
        padding: 16px;
        font-size: 16px;
        border: 2px solid #e8ecf0;
        border-radius: 12px;
        outline: none;
        transition: border-color 0.2s;
        font-family: 'Inter', sans-serif;
    }
    
    .form-control:focus {
        border-color: #1e88e5;
    }
    
    .radio-group {
        display: flex;
        gap: 16px;
    }
    
    .radio-card {
        flex: 1;
        position: relative;
    }
    
    .radio-card input {
        position: absolute;
        opacity: 0;
    }
    
    .radio-card label {
        display: block;
        padding: 16px;
        text-align: center;
        border: 2px solid #e8ecf0;
        border-radius: 12px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 700;
        color: #6b7a8d;
        transition: all 0.2s;
        margin: 0;
    }
    
    .radio-card input:checked + label.lunas-lbl {
        background: #e8f5e9;
        border-color: #43a047;
        color: #2e7d32;
    }
    
    .radio-card input:checked + label.belum-lbl {
        background: #ffebee;
        border-color: #e53935;
        color: #c62828;
    }
    
    .radio-card input:checked + label.dp-lbl {
        background: #fff8e1;
        border-color: #fbc02d;
        color: #f57f17;
    }
    
    .dp-input-box {
        display: none;
        margin-top: 16px;
        background: #fffde7;
        padding: 16px;
        border-radius: 12px;
        border: 1px dashed #fbc02d;
    }
    
    .btn-simpan {
        width: 100%;
        background: #1e88e5;
        color: white;
        padding: 18px;
        border: none;
        border-radius: 12px;
        font-size: 18px;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(30, 136, 229, 0.3);
        margin-top: 10px;
    }
    
    .btn-simpan:hover {
        background: #1976d2;
    }
    
    .btn-batal {
        display: block;
        text-align: center;
        width: 100%;
        padding: 16px;
        margin-top: 12px;
        color: #6b7a8d;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        background: #f1f5f9;
        border-radius: 12px;
    }
    
    .btn-batal:hover {
        background: #e2e8f0;
    }
    
    .error-msg {
        color: #e53935;
        font-size: 14px;
        margin-top: 6px;
        font-weight: 500;
    }
</style>
@endpush

@section('content')

<div class="form-container">
    <form action="{{ route('transaksi.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label>1. Siapa nama pembelinya?</label>
            <input type="text" name="pembeli_nama" class="form-control" placeholder="Contoh: Pak Budi atau Toko Laris" value="{{ old('pembeli_nama') }}" required autocomplete="off">
            @error('pembeli_nama') <div class="error-msg">{{ $message }}</div> @enderror
        </div>
        
        <div class="form-group">
            <label>2. Pilih Jenis Ikan</label>
            <select name="ikan_id" class="form-control" required>
                <option value="">-- Sentuh untuk memilih ikan --</option>
                @foreach($ikan as $i)
                    <option value="{{ $i->id }}" {{ old('ikan_id') == $i->id ? 'selected' : '' }}>
                        {{ $i->nama_ikan }} (Rp {{ number_format($i->harga_per_kg, 0, ',', '.') }} / Kg)
                    </option>
                @endforeach
            </select>
            @error('ikan_id') <div class="error-msg">{{ $message }}</div> @enderror
        </div>
        
        <div class="form-group">
            <label>3. Berapa Kilogram (Kg) ?</label>
            <input type="number" step="0.1" name="jumlah_kg" class="form-control" placeholder="Contoh: 10" value="{{ old('jumlah_kg') }}" required>
            @error('jumlah_kg') <div class="error-msg">{{ $message }}</div> @enderror
        </div>
        
        <div class="form-group">
            <label>4. Apakah sudah dibayar lunas?</label>
            <div class="radio-group" style="flex-wrap: wrap;">
                <div class="radio-card" style="min-width: 200px;">
                    <input type="radio" id="st_lunas" name="status" value="lunas" {{ old('status', 'lunas') == 'lunas' ? 'checked' : '' }} onchange="toggleDpInput()">
                    <label for="st_lunas" class="lunas-lbl"><i class="fas fa-check-circle"></i> LUNAS</label>
                </div>
                <div class="radio-card" style="min-width: 200px;">
                    <input type="radio" id="st_dp" name="status" value="dp" {{ old('status') == 'dp' ? 'checked' : '' }} onchange="toggleDpInput()">
                    <label for="st_dp" class="dp-lbl"><i class="fas fa-hand-holding-usd"></i> BAYAR SEBAGIAN (DP)</label>
                </div>
                <div class="radio-card" style="min-width: 200px;">
                    <input type="radio" id="st_belum" name="status" value="belum_lunas" {{ old('status') == 'belum_lunas' ? 'checked' : '' }} onchange="toggleDpInput()">
                    <label for="st_belum" class="belum-lbl"><i class="fas fa-times-circle"></i> BELUM LUNAS</label>
                </div>
            </div>
            @error('status') <div class="error-msg">{{ $message }}</div> @enderror
            
            <div class="dp-input-box" id="dp-box">
                <label style="color: #f57f17;">Berapa jumlah yang sudah dibayar? (Rp)</label>
                <input type="number" name="jumlah_dibayar" class="form-control" placeholder="Contoh: 500000" value="{{ old('jumlah_dibayar') }}">
            </div>
        </div>
        
        <div class="form-group">
            <label>Catatan Tambahan (Boleh Dikosongkan)</label>
            <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Diambil sore hari" value="{{ old('keterangan') }}">
        </div>
        
        <button type="submit" class="btn-simpan"><i class="fas fa-save"></i> SIMPAN TRANSAKSI</button>
        <a href="{{ route('transaksi.index') }}" class="btn-batal">KEMBALI</a>
    </form>
</div>

<script>
    function toggleDpInput() {
        var isDp = document.getElementById('st_dp').checked;
        document.getElementById('dp-box').style.display = isDp ? 'block' : 'none';
    }
    // Jalankan saat load
    toggleDpInput();
</script>

@endsection
