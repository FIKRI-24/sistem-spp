<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSiswaRequest;
use App\Http\Requests\Admin\UpdateSiswaRequest;
use App\Models\Kelas;
use App\Models\RiwayatKelasSiswa;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SiswaController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $kelasId = $request->query('kelas_id');
        $status = $request->query('status');

        $siswaList = Siswa::with(['kelas.jurusan', 'kelas.tahunAjaran'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%");
                });
            })
            ->when($kelasId, function ($query, $kelasId) {
                $query->where('kelas_id', $kelasId);
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $kelasList = Kelas::with(['jurusan', 'tahunAjaran'])->orderBy('name')->get();
        $totalSiswa = Siswa::count();
        $totalAktif = Siswa::where('status', 'active')->count();
        $totalLulus = Siswa::where('status', 'graduated')->count();

        return view('admin.siswa.index', compact(
            'siswaList',
            'kelasList',
            'search',
            'kelasId',
            'status',
            'totalSiswa',
            'totalAktif',
            'totalLulus'
        ));
    }

    public function create(): View
    {
        $kelasList = Kelas::with(['jurusan', 'tahunAjaran'])
            ->whereHas('tahunAjaran', function ($q) {
                $q->where('is_active', true);
            })
            ->orWhereDoesntHave('tahunAjaran')
            ->orderBy('name')
            ->get();

        if ($kelasList->isEmpty()) {
            $kelasList = Kelas::with(['jurusan', 'tahunAjaran'])->orderBy('name')->get();
        }

        return view('admin.siswa.create', compact('kelasList'));
    }

    public function store(StoreSiswaRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $siswa = Siswa::create($data);

            $kelas = Kelas::findOrFail($data['kelas_id']);

            RiwayatKelasSiswa::create([
                'siswa_id' => $siswa->id,
                'kelas_id' => $kelas->id,
                'tahun_ajaran_id' => $kelas->tahun_ajaran_id,
                'start_date' => $data['entry_date'] ?? now(),
            ]);
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', "Data siswa {$data['name']} (NIS: {$data['nis']}) berhasil didaftarkan dan riwayat kelas otomatis dicatat.");
    }

    public function show(Siswa $siswa): View
    {
        $siswa->load([
            'kelas.jurusan',
            'kelas.tahunAjaran',
            'riwayatKelasSiswa.kelas.jurusan',
            'riwayatKelasSiswa.tahunAjaran',
            'tagihanSiswa.jenisPembayaran',
            'potonganSiswa.jenisPembayaran',
        ]);

        return view('admin.siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa): View
    {
        $kelasList = Kelas::with(['jurusan', 'tahunAjaran'])->orderBy('name')->get();

        return view('admin.siswa.edit', compact('siswa', 'kelasList'));
    }

    public function update(UpdateSiswaRequest $request, Siswa $siswa): RedirectResponse
    {
        $data = $request->validated();
        $oldKelasId = $siswa->kelas_id;
        $newKelasId = (int) $data['kelas_id'];

        DB::transaction(function () use ($siswa, $data, $oldKelasId, $newKelasId) {
            // Jika siswa pindah / naik kelas, perbarui riwayat kelas
            if ($oldKelasId !== $newKelasId) {
                // Tutup riwayat kelas sebelumnya
                RiwayatKelasSiswa::where('siswa_id', $siswa->id)
                    ->where('kelas_id', $oldKelasId)
                    ->whereNull('end_date')
                    ->update(['end_date' => now()]);

                // Buat entri riwayat kelas baru
                $newKelas = Kelas::findOrFail($newKelasId);
                RiwayatKelasSiswa::create([
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $newKelas->id,
                    'tahun_ajaran_id' => $newKelas->tahun_ajaran_id,
                    'start_date' => now(),
                ]);
            }

            $siswa->update($data);
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', "Data siswa {$siswa->name} berhasil diperbarui.");
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $name = $siswa->name;
        // Soft delete agar histori keuangan tetap utuh
        $siswa->delete();

        return redirect()->route('admin.siswa.index')
            ->with('success', "Data siswa {$name} berhasil dinonaktifkan / diarsipkan (Soft Delete).");
    }

    public function riwayatKelas(Siswa $siswa): View
    {
        $riwayat = $siswa->riwayatKelasSiswa()
            ->with(['kelas.jurusan', 'tahunAjaran'])
            ->orderByDesc('start_date')
            ->get();

        return view('admin.siswa.partials.riwayat-modal', compact('siswa', 'riwayat'));
    }
}
