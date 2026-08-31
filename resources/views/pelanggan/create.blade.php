@extends('layouts.app')

@section('title', 'Tambah Pelanggan')
@section('page-title', 'Form Tambah Pelanggan')

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
    }
    
    .form-control:focus {
        border-color: #1e88e5;
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
</style>
@endpush

@section('content')

<div class="form-container">
    <form action="{{ route('pelanggan.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label>1. Nama Pelanggan / Toko</label>
            <input type="text" name="nama" class="form-control" placeholder="Contoh: Toko Makmur" value="{{ old('nama') }}" required>
        </div>
        
        <div class="form-group">
            <label>2. No. HP / WhatsApp (Boleh Dikosongkan)</label>
            <input type="text" name="telepon" class="form-control" placeholder="Contoh: 081234..." value="{{ old('telepon') }}">
        </div>
        
        <div class="form-group">
            <label>3. Alamat (Boleh Dikosongkan)</label>
            <input type="text" name="alamat" class="form-control" placeholder="Contoh: Pasar Baru" value="{{ old('alamat') }}">
        </div>
        
        <button type="submit" class="btn-simpan"><i class="fas fa-save"></i> SIMPAN PELANGGAN</button>
        <a href="{{ route('pelanggan.index') }}" class="btn-batal">KEMBALI</a>
    </form>
</div>

@endsection
