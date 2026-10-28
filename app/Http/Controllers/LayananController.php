<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LayananController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $layanan = Layanan::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('nama_ekspedisi', 'like', "%{$search}%")
                        ->orWhere('jenis_layanan', 'like', "%{$search}%")
                        ->orWhere('zonasi', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->get();

        return view('layanan.index', compact('layanan', 'search'));
    }

    public function create(): View
    {
        return view('layanan.form', [
            'layanan' => new Layanan(['status' => 'ON']),
            'judul' => 'Tambah Layanan',
            'aksi' => route('layanan.store'),
            'metode' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Layanan::create($this->validateData($request));

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Layanan $layanan): View
    {
        return view('layanan.form', [
            'layanan' => $layanan,
            'judul' => 'Edit Layanan',
            'aksi' => route('layanan.update', $layanan),
            'metode' => 'PUT',
        ]);
    }

    public function update(Request $request, Layanan $layanan): RedirectResponse
    {
        $layanan->update($this->validateData($request));

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Layanan $layanan): RedirectResponse
    {
        $layanan->delete();

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        $request->merge([
            'nama_ekspedisi' => mb_strtoupper(trim((string) $request->input('nama_ekspedisi')), 'UTF-8'),
        ]);

        return $request->validate([
            'nama_ekspedisi' => ['required', 'string', 'max:100', 'not_regex:/[<>\/"]/'],
            'jenis_layanan' => ['required', Rule::in(Layanan::JENIS_LAYANAN)],
            'zonasi' => ['required', Rule::in(Layanan::ZONASI)],
            'tarif' => ['required', 'integer', 'min:0'],
            'estimasi' => ['required', Rule::in(Layanan::ESTIMASI)],
            'status' => ['required', Rule::in(Layanan::STATUS)],
        ]);
    }
}
