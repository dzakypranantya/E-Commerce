@extends('layouts.app')

@section('title', $judul)
@section('heading', strtoupper($judul))

@section('header-action')
    <a href="{{ route('layanan.index') }}" class="btn btn-primary btn-action">Kembali</a>
@endsection

@section('content')
    <div class="card-form form-center">
        <h2>{{ $judul }}</h2>

        @if ($errors->any())
            <div class="notice notice-error" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $aksi }}" method="POST">
            @csrf
            @if ($metode !== 'POST')
                @method($metode)
            @endif

            <div class="form-group">
                <label for="nama_ekspedisi">Nama Ekspedisi/Kurir</label>
                <input class="form-control" type="text" id="nama_ekspedisi" name="nama_ekspedisi" value="{{ old('nama_ekspedisi', $layanan->nama_ekspedisi) }}" maxlength="100" required>
            </div>

            <div class="form-group">
                <label for="jenis_layanan">Jenis Layanan</label>
                <select class="form-select" id="jenis_layanan" name="jenis_layanan" required>
                    <option value="">Pilih Jenis Layanan</option>
                    @foreach (\App\Models\Layanan::JENIS_LAYANAN as $jenis)
                        <option value="{{ $jenis }}" @selected(old('jenis_layanan', $layanan->jenis_layanan) === $jenis)>{{ $jenis }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="zonasi">Zonasi Pengiriman</label>
                <select class="form-select" id="zonasi" name="zonasi" required>
                    <option value="">Pilih Zonasi</option>
                    @foreach (\App\Models\Layanan::ZONASI as $zona)
                        <option value="{{ $zona }}" @selected(old('zonasi', $layanan->zonasi) === $zona)>{{ $zona }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="tarif">Tarif Dasar (Rp)</label>
                <input class="form-control" type="number" id="tarif" name="tarif" min="0" step="1" value="{{ old('tarif', $layanan->tarif) }}" required>
            </div>

            <div class="form-group">
                <label for="estimasi">Estimasi Pengiriman</label>
                <select class="form-select" id="estimasi" name="estimasi" required>
                    <option value="">Pilih Estimasi Pengiriman</option>
                    @foreach (\App\Models\Layanan::ESTIMASI as $estimasi)
                        <option value="{{ $estimasi }}" @selected(old('estimasi', $layanan->estimasi) === $estimasi)>{{ $estimasi }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status Layanan</label>
                <select class="form-select" id="status" name="status" required>
                    @foreach (\App\Models\Layanan::STATUS as $status)
                        <option value="{{ $status }}" @selected(old('status', $layanan->status) === $status)>{{ $status === 'ON' ? 'On' : 'Off' }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-submit">Simpan Layanan</button>
        </form>
    </div>
@endsection
