<?php

namespace Database\Seeders;

use App\Models\JenisPembayaran;
use App\Models\Kelas;
use App\Models\RiwayatKelasSiswa;
use App\Models\Siswa;
use App\Models\TagihanSiswa;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ta = TahunAjaran::where('is_active', true)->first();
        $kelas = Kelas::where('name', 'XII RPL 1')->first();
        $spp = JenisPembayaran::where('code', 'SPP')->first();

        if (! $ta || ! $kelas) {
            return;
        }

        $studentsData = [
            [
                'nis' => '2025001',
                'nisn' => '0061234501',
                'name' => 'Ahmad Fathoni',
                'gender' => 'L',
                'kelas_id' => $kelas->id,
                'entry_date' => '2023-07-15',
                'status' => 'active',
                'parent_name' => 'Bambang Sutrisno',
                'parent_phone' => '081234567890',
                'address' => 'Jl. Kebon Jeruk No. 12',
            ],
            [
                'nis' => '2025002',
                'nisn' => '0061234502',
                'name' => 'Budi Santoso',
                'gender' => 'L',
                'kelas_id' => $kelas->id,
                'entry_date' => '2023-07-15',
                'status' => 'active',
                'parent_name' => 'Agus Santoso',
                'parent_phone' => '081234567891',
                'address' => 'Jl. Mawar Merah No. 5',
            ],
            [
                'nis' => '2025003',
                'nisn' => '0061234503',
                'name' => 'Citra Lestari',
                'gender' => 'P',
                'kelas_id' => $kelas->id,
                'entry_date' => '2023-07-15',
                'status' => 'active',
                'parent_name' => 'Hendro Pratama',
                'parent_phone' => '081234567892',
                'address' => 'Jl. Melati Putih No. 8',
            ],
        ];

        foreach ($studentsData as $data) {
            $siswa = Siswa::firstOrCreate(['nis' => $data['nis']], $data);

            // Buat riwayat kelas siswa
            RiwayatKelasSiswa::firstOrCreate([
                'siswa_id' => $siswa->id,
                'kelas_id' => $kelas->id,
                'tahun_ajaran_id' => $ta->id,
            ], [
                'start_date' => '2025-07-01',
            ]);

            // Buat contoh 1 tagihan SPP Juli 2025 jika ada jenis pembayaran SPP
            if ($spp) {
                TagihanSiswa::firstOrCreate([
                    'siswa_id' => $siswa->id,
                    'jenis_pembayaran_id' => $spp->id,
                    'period' => '2025-07',
                ], [
                    'tahun_ajaran_id' => $ta->id,
                    'amount' => 250000.00,
                    'amount_paid' => 0.00,
                    'status' => 'unpaid',
                    'due_date' => '2025-07-10',
                ]);
            }
        }
    }
}
