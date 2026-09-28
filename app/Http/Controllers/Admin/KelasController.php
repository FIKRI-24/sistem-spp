<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreKelasRequest;
use App\Http\Requests\Admin\UpdateKelasRequest;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $tahunAjaranId = $request->query('tahun_ajaran_id');
        $jurusanId = $request->query('jurusan_id');

        $activeYear = TahunAjaran::where('is_active', true)->first();
        if (! $tahunAjaranId && $activeYear) {
            $tahunAjaranId = $activeYear->id;
        }

        $kelasList = Kelas::with(['jurusan', 'tahunAjaran'])
            ->withCount('siswa')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($tahunAjaranId, function ($query, $tahunAjaranId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->when($jurusanId, function ($query, $jurusanId) {
                $query->where('jurusan_id', $jurusanId);
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $tahunAjaranOptions = TahunAjaran::orderByDesc('name')->get();
        $jurusanOptions = Jurusan::orderBy('name')->get();
        $totalKelas = Kelas::count();

        return view('admin.kelas.index', compact(
            'kelasList',
            'tahunAjaranOptions',
            'jurusanOptions',
            'tahunAjaranId',
            'jurusanId',
            'search',
            'totalKelas'
        ));
    }

    public function store(StoreKelasRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $kelas = Kelas::create($data);

        return redirect()->route('admin.kelas.index', ['tahun_ajaran_id' => $data['tahun_ajaran_id']])
            ->with('success', "Rombel / Kelas {$kelas->name} berhasil ditambahkan.");
    }

    public function update(UpdateKelasRequest $request, Kelas $kela): RedirectResponse
    {
        $kelas = $kela;
        $data = $request->validated();
        $kelas->update($data);

        return redirect()->route('admin.kelas.index', ['tahun_ajaran_id' => $data['tahun_ajaran_id']])
            ->with('success', "Data rombel / kelas {$kelas->name} berhasil diperbarui.");
    }

    public function destroy(Kelas $kela): RedirectResponse
    {
        $kelas = $kela;
        if ($kelas->siswa()->exists()) {
            return redirect()->route('admin.kelas.index', ['tahun_ajaran_id' => $kelas->tahun_ajaran_id])
                ->with('error', "Kelas {$kelas->name} tidak dapat dihapus karena memiliki {$kelas->siswa()->count()} siswa aktif di dalamnya.");
        }

        if ($kelas->riwayatKelasSiswa()->exists()) {
            return redirect()->route('admin.kelas.index', ['tahun_ajaran_id' => $kelas->tahun_ajaran_id])
                ->with('error', "Kelas {$kelas->name} tidak dapat dihapus karena tersimpan dalam riwayat kelas siswa terdahulu.");
        }

        $name = $kelas->name;
        $taId = $kelas->tahun_ajaran_id;
        $kelas->delete();

        return redirect()->route('admin.kelas.index', ['tahun_ajaran_id' => $taId])
            ->with('success', "Kelas {$name} berhasil dihapus.");
    }
}
