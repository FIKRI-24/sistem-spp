<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJenisPembayaranRequest;
use App\Http\Requests\Admin\UpdateJenisPembayaranRequest;
use App\Models\JenisPembayaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JenisPembayaranController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $category = $request->query('category');

        $jenisPembayaranList = JenisPembayaran::withCount(['tarifPembayaran', 'tagihanSiswa'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($category, function ($query, $category) {
                $query->where('category', $category);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $totalJenis = JenisPembayaran::count();
        $totalRutin = JenisPembayaran::where('category', 'recurring')->count();
        $totalSekaliBayar = JenisPembayaran::where('category', 'one_time')->count();

        return view('admin.jenis-pembayaran.index', compact(
            'jenisPembayaranList',
            'search',
            'category',
            'totalJenis',
            'totalRutin',
            'totalSekaliBayar'
        ));
    }

    public function store(StoreJenisPembayaranRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $jenis = JenisPembayaran::create($data);

        return redirect()->route('admin.jenis-pembayaran.index')
            ->with('success', "Jenis pembayaran {$jenis->name} ({$jenis->code}) berhasil ditambahkan.");
    }

    public function update(UpdateJenisPembayaranRequest $request, JenisPembayaran $jenisPembayaran): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $jenisPembayaran->update($data);

        return redirect()->route('admin.jenis-pembayaran.index')
            ->with('success', "Data jenis pembayaran {$jenisPembayaran->name} berhasil diperbarui.");
    }

    public function destroy(JenisPembayaran $jenisPembayaran): RedirectResponse
    {
        // Business Rule #14: Data master yang sudah dipakai di tagihan tidak boleh dihapus
        if ($jenisPembayaran->tagihanSiswa()->exists()) {
            return redirect()->route('admin.jenis-pembayaran.index')
                ->with('error', "Jenis pembayaran {$jenisPembayaran->name} tidak dapat dihapus karena sudah memiliki catatan tagihan siswa. Silakan gunakan fitur nonaktifkan jika pos biaya ini tidak lagi digunakan.");
        }

        if ($jenisPembayaran->tarifPembayaran()->exists()) {
            return redirect()->route('admin.jenis-pembayaran.index')
                ->with('error', "Jenis pembayaran {$jenisPembayaran->name} tidak dapat dihapus karena sudah memiliki penetapan tarif pembayaran.");
        }

        $name = $jenisPembayaran->name;
        $jenisPembayaran->delete();

        return redirect()->route('admin.jenis-pembayaran.index')
            ->with('success', "Jenis pembayaran {$name} berhasil dihapus.");
    }

    public function toggleActive(JenisPembayaran $jenisPembayaran): RedirectResponse
    {
        $newStatus = ! $jenisPembayaran->is_active;
        $jenisPembayaran->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.jenis-pembayaran.index')
            ->with('success', "Jenis pembayaran {$jenisPembayaran->name} berhasil {$statusText}.");
    }
}
