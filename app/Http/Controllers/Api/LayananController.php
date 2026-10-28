<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LayananController extends Controller
{
    public function index(Request $request): JsonResponse
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

        return response()->json(['data' => $layanan]);
    }

    public function store(Request $request): JsonResponse
    {
        $layanan = Layanan::create($this->validateData($request));

        return response()->json([
            'message' => 'Layanan berhasil ditambahkan.',
            'data' => $layanan,
        ], 201);
    }

    public function show(Layanan $layanan): JsonResponse
    {
        return response()->json(['data' => $layanan]);
    }

    public function update(Request $request, Layanan $layanan): JsonResponse
    {
        $request->merge(array_merge($layanan->getAttributes(), $request->all()));
        $layanan->update($this->validateData($request));

        return response()->json([
            'message' => 'Layanan berhasil diperbarui.',
            'data' => $layanan->fresh(),
        ]);
    }

    public function destroy(Layanan $layanan): JsonResponse
    {
        $layanan->delete();

        return response()->json(['message' => 'Layanan berhasil dihapus.']);
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
            'status' => ['sometimes', 'required', Rule::in(Layanan::STATUS)],
        ]);
    }
}
