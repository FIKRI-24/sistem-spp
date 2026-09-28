<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTarifPembayaranRequest;
use App\Http\Requests\Admin\UpdateTarifPembayaranRequest;
use App\Models\JenisPembayaran;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\TarifPembayaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TarifPembayaranController extends Controller
{
    public function index(Request $request): View
    {
        $tahunAjaranId = $request->query('tahun_ajaran_id');
        $jenisPembayaranId = $request->query('jenis_pembayaran_id');

        $activeYear = TahunAjaran::where('is_active', true)->first();
        if (! $tahunAjaranId && $activeYear) {
            $tahunAjaranId = $activeYear->id;
        }

        $tarifList = TarifPembayaran::with(['jenisPembayaran', 'tahunAjaran', 'kelas', 'jurusan'])
            ->when($tahunAjaranId, function ($query, $tahunAjaranId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->when($jenisPembayaranId, function ($query, $jenisPembayaranId) {
                $query->where('jenis_pembayaran_id', $jenisPembayaranId);
            })
            ->orderByDesc('tahun_ajaran_id')
            ->paginate(12)
            ->withQueryString();

        $tahunAjaranOptions = TahunAjaran::orderByDesc('name')->get();
        $jenisPembayaranOptions = JenisPembayaran::where('is_active', true)->orderBy('name')->get();
        $jurusanOptions = Jurusan::orderBy('name')->get();
        $kelasList = Kelas::with('jurusan')->orderBy('name')->get();
        $totalTarif = TarifPembayaran::count();

        return view('admin.tarif-pembayaran.index', compact(
            'tarifList',
            'tahunAjaranOptions',
            'jenisPembayaranOptions',
            'jurusanOptions',
            'kelasList',
            'tahunAjaranId',
            'jenisPembayaranId',
            'totalTarif'
        ));
    }

    public function store(StoreTarifPembayaranRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['effective_date'] = $data['effective_date'] ?? now();

        TarifPembayaran::create($data);

        return redirect()->route('admin.tarif-pembayaran.index', [
            'tahun_ajaran_id' => $data['tahun_ajaran_id'],
            'jenis_pembayaran_id' => $data['jenis_pembayaran_id'],
        ])->with('success', 'Tarif pembayaran baru berhasil ditetapkan.');
    }

    public function update(UpdateTarifPembayaranRequest $request, TarifPembayaran $tarifPembayaran): RedirectResponse
    {
        $data = $request->validated();
        $tarifPembayaran->update($data);

        return redirect()->route('admin.tarif-pembayaran.index', [
            'tahun_ajaran_id' => $data['tahun_ajaran_id'],
            'jenis_pembayaran_id' => $data['jenis_pembayaran_id'],
        ])->with('success', 'Tarif pembayaran berhasil diperbarui.');
    }

    public function destroy(TarifPembayaran $tarifPembayaran): RedirectResponse
    {
        $taId = $tarifPembayaran->tahun_ajaran_id;
        $jpId = $tarifPembayaran->jenis_pembayaran_id;
        $tarifPembayaran->delete();

        return redirect()->route('admin.tarif-pembayaran.index', [
            'tahun_ajaran_id' => $taId,
            'jenis_pembayaran_id' => $jpId,
        ])->with('success', 'Tarif pembayaran berhasil dihapus.');
    }
}
