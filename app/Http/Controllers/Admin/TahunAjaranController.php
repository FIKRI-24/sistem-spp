<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTahunAjaranRequest;
use App\Http\Requests\Admin\UpdateTahunAjaranRequest;
use App\Models\PengaturanSekolah;
use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TahunAjaranController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $tahunAjaranList = TahunAjaran::withCount(['kelas', 'tagihanSiswa', 'tarifPembayaran'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderByDesc('name')
            ->paginate(10)
            ->withQueryString();

        $activeYear = TahunAjaran::where('is_active', true)->first();
        $totalYears = TahunAjaran::count();
        $lockedYears = TahunAjaran::where('is_locked', true)->count();

        return view('admin.tahun-ajaran.index', compact('tahunAjaranList', 'activeYear', 'totalYears', 'lockedYears', 'search'));
    }

    public function store(StoreTahunAjaranRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $isActive = $request->boolean('is_active');
        $isLocked = $request->boolean('is_locked');

        DB::transaction(function () use ($data, $isActive, $isLocked) {
            if ($isActive) {
                TahunAjaran::where('is_active', true)->update(['is_active' => false]);
            }

            $tahunAjaran = TahunAjaran::create([
                'name' => $data['name'],
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,
                'is_active' => $isActive,
                'is_locked' => $isLocked,
            ]);

            if ($isActive) {
                PengaturanSekolah::query()->update(['active_tahun_ajaran_id' => $tahunAjaran->id]);
            }
        });

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', "Tahun ajaran {$data['name']} berhasil ditambahkan.");
    }

    public function update(UpdateTahunAjaranRequest $request, TahunAjaran $tahunAjaran): RedirectResponse
    {
        if ($tahunAjaran->is_locked && ! $request->has('unlock')) {
            return redirect()->route('admin.tahun-ajaran.index')
                ->with('error', "Tahun ajaran {$tahunAjaran->name} sedang terkunci dan tidak dapat diubah.");
        }

        $data = $request->validated();
        $isActive = $request->boolean('is_active');
        $isLocked = $request->boolean('is_locked');

        DB::transaction(function () use ($tahunAjaran, $data, $isActive, $isLocked) {
            if ($isActive && ! $tahunAjaran->is_active) {
                TahunAjaran::where('is_active', true)->where('id', '!=', $tahunAjaran->id)->update(['is_active' => false]);
                PengaturanSekolah::query()->update(['active_tahun_ajaran_id' => $tahunAjaran->id]);
            }

            $tahunAjaran->update([
                'name' => $data['name'],
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,
                'is_active' => $isActive,
                'is_locked' => $isLocked,
            ]);
        });

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', "Tahun ajaran {$tahunAjaran->name} berhasil diperbarui.");
    }

    public function destroy(TahunAjaran $tahunAjaran): RedirectResponse
    {
        if ($tahunAjaran->is_locked) {
            return redirect()->route('admin.tahun-ajaran.index')
                ->with('error', "Tahun ajaran {$tahunAjaran->name} sedang terkunci dan tidak dapat dihapus.");
        }

        if ($tahunAjaran->is_active) {
            return redirect()->route('admin.tahun-ajaran.index')
                ->with('error', 'Tahun ajaran yang sedang aktif tidak dapat dihapus. Silakan aktifkan tahun ajaran lain terlebih dahulu.');
        }

        if ($tahunAjaran->kelas()->exists() || $tahunAjaran->tagihanSiswa()->exists() || $tahunAjaran->tarifPembayaran()->exists()) {
            return redirect()->route('admin.tahun-ajaran.index')
                ->with('error', "Tahun ajaran {$tahunAjaran->name} tidak dapat dihapus karena masih memiliki data kelas, tagihan, atau tarif pembayaran terkait.");
        }

        $name = $tahunAjaran->name;
        $tahunAjaran->delete();

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', "Tahun ajaran {$name} berhasil dihapus.");
    }

    public function toggleActive(TahunAjaran $tahunAjaran): RedirectResponse
    {
        if ($tahunAjaran->is_active) {
            return redirect()->route('admin.tahun-ajaran.index')
                ->with('error', 'Sistem harus memiliki minimal 1 tahun ajaran aktif.');
        }

        DB::transaction(function () use ($tahunAjaran) {
            TahunAjaran::where('is_active', true)->update(['is_active' => false]);
            $tahunAjaran->update(['is_active' => true]);
            PengaturanSekolah::query()->update(['active_tahun_ajaran_id' => $tahunAjaran->id]);
        });

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', "Tahun ajaran {$tahunAjaran->name} kini diatur sebagai tahun ajaran aktif.");
    }

    public function toggleLock(TahunAjaran $tahunAjaran): RedirectResponse
    {
        $newStatus = ! $tahunAjaran->is_locked;
        $tahunAjaran->update(['is_locked' => $newStatus]);

        $statusText = $newStatus ? 'dikunci (data dibekukan)' : 'dibuka kuncinya';

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', "Tahun ajaran {$tahunAjaran->name} berhasil {$statusText}.");
    }
}
