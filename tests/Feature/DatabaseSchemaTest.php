<?php

namespace Tests\Feature;

use App\Models\DetailPembayaran;
use App\Models\JenisPembayaran;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\TagihanSiswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test bahwa seluruh relasi Eloquent berjalan normal.
     */
    public function test_eloquent_relationships_work(): void
    {
        $siswa = Siswa::with(['kelas.jurusan', 'kelas.tahunAjaran', 'tagihanSiswa.jenisPembayaran', 'riwayatKelasSiswa'])->first();

        $this->assertNotNull($siswa, 'Siswa harus ada dari seeder');
        $this->assertNotNull($siswa->kelas, 'Relasi siswa->kelas harus ada');
        $this->assertNotNull($siswa->kelas->jurusan, 'Relasi kelas->jurusan harus ada');
        $this->assertNotNull($siswa->kelas->tahunAjaran, 'Relasi kelas->tahunAjaran harus ada');
        $this->assertGreaterThan(0, $siswa->tagihanSiswa->count(), 'Siswa harus memiliki tagihan');
        $this->assertGreaterThan(0, $siswa->riwayatKelasSiswa->count(), 'Siswa harus memiliki riwayat kelas');

        $tagihan = $siswa->tagihanSiswa->first();
        $this->assertNotNull($tagihan->jenisPembayaran, 'Relasi tagihan->jenisPembayaran harus ada');
        $this->assertEquals($siswa->id, $tagihan->siswa->id, 'Relasi balik tagihan->siswa harus sesuai');
    }

    /**
     * Test bahwa accessor calculated_status dan remaining_amount pada TagihanSiswa berfungsi sesuai PRD Bab 10.7.
     */
    public function test_tagihan_siswa_calculated_status_accessor_works(): void
    {
        $tagihan = TagihanSiswa::first();
        $this->assertNotNull($tagihan);

        // Kasus 1: Belum bayar (amount_paid = 0)
        $tagihan->amount = 250000;
        $tagihan->amount_paid = 0;
        $tagihan->status = 'unpaid';
        $this->assertEquals('unpaid', $tagihan->calculated_status);
        $this->assertEquals('Belum Lunas', $tagihan->status_label);
        $this->assertEquals(250000, $tagihan->remaining_amount);

        // Kasus 2: Bayar sebagian (0 < amount_paid < amount)
        $tagihan->amount_paid = 100000;
        $this->assertEquals('partial', $tagihan->calculated_status);
        $this->assertEquals('Sebagian', $tagihan->status_label);
        $this->assertEquals(150000, $tagihan->remaining_amount);

        // Kasus 3: Lunas (amount_paid >= amount)
        $tagihan->amount_paid = 250000;
        $this->assertEquals('paid', $tagihan->calculated_status);
        $this->assertEquals('Lunas', $tagihan->status_label);
        $this->assertEquals(0, $tagihan->remaining_amount);

        // Kasus 4: Dibatalkan (status fisik = void)
        $tagihan->status = 'void';
        $this->assertEquals('void', $tagihan->calculated_status);
        $this->assertEquals('Dibatalkan', $tagihan->status_label);
    }

    /**
     * Test constraint unik: Mencegah duplikasi tagihan (siswa_id, jenis_pembayaran_id, period) di tingkat database.
     */
    public function test_duplicate_bill_rejected_by_unique_constraint(): void
    {
        $siswa = Siswa::first();
        $spp = JenisPembayaran::where('code', 'SPP')->first();
        $ta = TahunAjaran::where('is_active', true)->first();

        $this->assertNotNull($siswa);
        $this->assertNotNull($spp);
        $this->assertNotNull($ta);

        // Tagihan pertama untuk periode 2025-08
        TagihanSiswa::create([
            'siswa_id' => $siswa->id,
            'jenis_pembayaran_id' => $spp->id,
            'tahun_ajaran_id' => $ta->id,
            'period' => '2025-08',
            'amount' => 250000,
            'amount_paid' => 0,
            'status' => 'unpaid',
            'due_date' => '2025-08-10',
        ]);

        // Coba insert tagihan kedua dengan siswa_id, jenis_pembayaran_id, dan period yang sama
        $this->expectException(QueryException::class);

        TagihanSiswa::create([
            'siswa_id' => $siswa->id,
            'jenis_pembayaran_id' => $spp->id,
            'tahun_ajaran_id' => $ta->id,
            'period' => '2025-08', // Periode sama -> Harus gagal / throw QueryException
            'amount' => 250000,
            'amount_paid' => 0,
            'status' => 'unpaid',
            'due_date' => '2025-08-10',
        ]);
    }

    /**
     * Test relasi pembayaran dan alokasi detail_pembayaran multi-tagihan.
     */
    public function test_payment_and_details_relationship(): void
    {
        $siswa = Siswa::first();
        $user = User::first();
        $tagihan = TagihanSiswa::where('siswa_id', $siswa->id)->first();

        $pembayaran = Pembayaran::create([
            'transaction_number' => 'TRX-20250701-0001',
            'siswa_id' => $siswa->id,
            'user_id' => $user->id,
            'total_amount' => 250000,
            'payment_method' => 'cash',
            'status' => 'completed',
        ]);

        $detail = DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'tagihan_siswa_id' => $tagihan->id,
            'amount_allocated' => 250000,
        ]);

        $this->assertEquals(1, $pembayaran->detailPembayaran->count());
        $this->assertEquals($pembayaran->id, $detail->pembayaran->id);
        $this->assertEquals($tagihan->id, $detail->tagihanSiswa->id);
    }

    /**
     * Test Spatie Role assignment pada Super Admin dan Admin.
     */
    public function test_spatie_roles_are_properly_assigned(): void
    {
        $superAdmin = User::where('email', 'superadmin@sppsistem.test')->first();
        $admin = User::where('email', 'admin@sppsistem.test')->first();

        $this->assertTrue($superAdmin->hasRole('super_admin'));
        $this->assertTrue($superAdmin->can('manage_users'));
        $this->assertTrue($superAdmin->can('approve_void_payments'));

        $this->assertTrue($admin->hasRole('admin'));
        $this->assertTrue($admin->can('process_payments'));
        $this->assertFalse($admin->can('approve_void_payments'));
    }
}
