@extends('layouts.app')

@section('title', 'Daftar Layanan')

@section('header-action')
    <a href="{{ route('layanan.create') }}" class="btn btn-primary btn-action">+ Tambah Layanan</a>
@endsection

@section('content')
    <div class="card-table full-width">
        <div class="card-header-flex">
            <h2>List Zona &amp; Ekspedisi Pengiriman</h2>
            <form action="{{ route('layanan.index') }}" method="GET" class="search-form">
                <input type="search" name="search" value="{{ $search }}" placeholder="Cari ekspedisi / zona..." class="form-control search-input" aria-label="Cari ekspedisi atau zona">
            </form>
        </div>

        <div class="table-scroll">
            <table class="table table-hover data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Ekspedisi</th>
                        <th>Jenis Layanan</th>
                        <th>Zonasi Wilayah</th>
                        <th>Tarif Dasar</th>
                        <th>Estimasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($layanan as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $item->nama_ekspedisi }}</strong></td>
                            <td>
                                <span class="badge {{ $item->jenis_layanan === 'Express' ? 'badge-express' : 'badge-reguler' }}">
                                    {{ $item->jenis_layanan }}
                                </span>
                            </td>
                            <td>{{ $item->zonasi }}</td>
                            <td>Rp {{ number_format($item->tarif, 0, ',', '.') }}</td>
                            <td>{{ $item->estimasi }}</td>
                            <td>
                                <span class="status-badge {{ $item->status === 'ON' ? 'status-active' : 'status-inactive' }}">
                                    {{ $item->status === 'ON' ? 'On' : 'Off' }}
                                </span>
                            </td>
                            <td class="action-cell">
                                <a href="{{ route('layanan.edit', $item) }}" class="btn btn-sm btn-edit">Edit</a>
                                <form action="{{ route('layanan.destroy', $item) }}" method="POST" class="inline-form" onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">Belum ada layanan yang cocok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
