<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePotonganSiswaRequest;
use App\Http\Requests\Admin\UpdatePotonganSiswaRequest;
use App\Models\JenisPembayaran;
use App\Models\PotonganSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PotonganSiswaController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $tahunAjaranId = $request->query('tahun_ajaran_id');
        $jenisPembayaranId = $request->query('jenis_pembayaran_id');

        $activeYear = TahunAjaran::where('is_active', true)->first();
        if (! $tahunAjaranId && $activeYear) {
            $tahunAjaranId = $activeYear->id;
        }

        $potonganList = PotonganSiswa::with(['siswa.kelas', 'jenisPembayaran', 'tahunAjaran'])
            ->when($search, function ($query, $search) {
                $query->whereHas('siswa', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                })->orWhere('reason', 'like', "%{$search}%");
            })
            ->when($tahunAjaranId, function ($query, $tahunAjaranId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->when($jenisPembayaranId, function ($query, $jenisPembayaranId) {
                $query->where('jenis_pembayaran_id', $jenisPembayaranId);
            })
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $tahunAjaranOptions = TahunAjaran::orderByDesc('name')->get();
        $jenisPembayaranOptions = JenisPembayaran::where('is_active', true)->orderBy('name')->get();
        $siswaOptions = Siswa::where('status', 'active')->with('kelas')->orderBy('name')->get();
        $totalPotongan = PotonganSiswa::count();
        $totalAktif = PotonganSiswa::where('is_active', true)->count();

        return view('admin.potongan-siswa.index', compact(
            'potonganList',
            'tahunAjaranOptions',
            'jenisPembayaranOptions',
            'siswaOptions',
            'tahunAjaranId',
            'jenisPembayaranId',
            'search',
            'totalPotongan',
            'totalAktif'
        ));
    }

    public function store(StorePotonganSiswaRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        PotonganSiswa::create($data);

        return redirect()->route('admin.potongan-siswa.index', ['tahun_ajaran_id' => $data['tahun_ajaran_id']])
            ->with('success', 'Data beasiswa / potongan siswa berhasil ditambahkan.');
    }

    public function update(UpdatePotonganSiswaRequest $request, PotonganSiswa $potonganSiswa): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $potonganSiswa->update($data);

        return redirect()->route('admin.potongan-siswa.index', ['tahun_ajaran_id' => $data['tahun_ajaran_id']])
            ->with('success', 'Data beasiswa / potongan siswa berhasil diperbarui.');
    }

    public function destroy(PotonganSiswa $potonganSiswa): RedirectResponse
    {
        $taId = $potonganSiswa->tahun_ajaran_id;
        $potonganSiswa->delete();

        return redirect()->route('admin.potongan-siswa.index', ['tahun_ajaran_id' => $taId])
            ->with('success', 'Data beasiswa / potongan siswa berhasil dihapus.');
    }

    public function toggleActive(PotonganSiswa $potonganSiswa): RedirectResponse
    {
        $newStatus = ! $potonganSiswa->is_active;
        $potonganSiswa->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.potongan-siswa.index', ['tahun_ajaran_id' => $potonganSiswa->tahun_ajaran_id])
            ->with('success', "Status beasiswa/potongan berhasil {$statusText}.");
    }
}
