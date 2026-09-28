<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisPembayaran;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\PengaturanSekolah;
use App\Models\PotonganSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\TarifPembayaran;
use Illuminate\View\View;

class MasterDataController extends Controller
{
    public function index(): View
    {
        $stats = [
            'tahun_ajaran' => [
                'total' => TahunAjaran::count(),
                'active' => TahunAjaran::where('is_active', true)->value('name') ?? 'Belum ada',
                'locked' => TahunAjaran::where('is_locked', true)->count(),
            ],
            'jurusan' => [
                'total' => Jurusan::count(),
            ],
            'kelas' => [
                'total' => Kelas::count(),
            ],
            'siswa' => [
                'total' => Siswa::count(),
                'aktif' => Siswa::where('status', 'active')->count(),
                'lulus' => Siswa::where('status', 'graduated')->count(),
            ],
            'jenis_pembayaran' => [
                'total' => JenisPembayaran::count(),
                'rutin' => JenisPembayaran::where('category', 'recurring')->count(),
                'sekali_bayar' => JenisPembayaran::where('category', 'one_time')->count(),
            ],
            'tarif_pembayaran' => [
                'total' => TarifPembayaran::count(),
            ],
            'potongan_siswa' => [
                'total' => PotonganSiswa::count(),
                'aktif' => PotonganSiswa::where('is_active', true)->count(),
            ],
            'pengaturan_sekolah' => [
                'school_name' => PengaturanSekolah::value('school_name') ?? 'SMK Negeri 1 Sistem SPP',
            ],
        ];

        return view('admin.master-data.index', compact('stats'));
    }
}
