<?php

namespace Tests\Feature;

use App\Models\JenisPembayaran;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\PengaturanSekolah;
use App\Models\PotonganSiswa;
use App\Models\RiwayatKelasSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\TarifPembayaran;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class MasterDataTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole('super_admin');

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    // 1. Hub Access
    public function test_admin_and_super_admin_can_access_master_data_hub(): void
    {
        $this->actingAs($this->superAdmin)->get(route('admin.master-data.index'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.master-data.index'))->assertStatus(200);
    }

    // 2. Tahun Ajaran: Business Rule #10 (Only 1 active year)
    public function test_only_one_academic_year_can_be_active_at_a_time(): void
    {
        $year1 = TahunAjaran::create([
            'name' => '2024/2025',
            'is_active' => true,
        ]);

        $this->assertTrue($year1->fresh()->is_active);

        $response = $this->actingAs($this->admin)->post(route('admin.tahun-ajaran.store'), [
            'name' => '2025/2026',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.tahun-ajaran.index'));

        // Year 1 must be deactivated automatically
        $this->assertFalse($year1->fresh()->is_active);

        // Year 2 must be active
        $year2 = TahunAjaran::where('name', '2025/2026')->first();
        $this->assertNotNull($year2);
        $this->assertTrue($year2->is_active);
    }

    // 3. Tahun Ajaran: Business Rule #11 (Locked year protection)
    public function test_locked_academic_year_cannot_be_modified_or_deleted(): void
    {
        $lockedYear = TahunAjaran::create([
            'name' => '2023/2024',
            'is_active' => false,
            'is_locked' => true,
        ]);

        // Attempt update
        $this->actingAs($this->admin)->put(route('admin.tahun-ajaran.update', $lockedYear), [
            'name' => '2022/2023',
        ])->assertSessionHas('error');

        // Attempt delete
        $this->actingAs($this->admin)->delete(route('admin.tahun-ajaran.destroy', $lockedYear))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('tahun_ajaran', ['id' => $lockedYear->id]);
    }

    // 4. Tahun Ajaran: Relational integrity (Cannot delete if classes exist)
    public function test_academic_year_with_classes_cannot_be_deleted(): void
    {
        $year = TahunAjaran::create(['name' => '2024/2025', 'is_active' => false]);
        $major = Jurusan::create(['code' => 'RPL', 'name' => 'Rekayasa Perangkat Lunak']);
        Kelas::create(['name' => 'X RPL 1', 'jurusan_id' => $major->id, 'tahun_ajaran_id' => $year->id]);

        $this->actingAs($this->admin)->delete(route('admin.tahun-ajaran.destroy', $year))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('tahun_ajaran', ['id' => $year->id]);
    }

    // 5. Jurusan: Relational integrity
    public function test_major_with_linked_classes_cannot_be_deleted(): void
    {
        $year = TahunAjaran::create(['name' => '2024/2025', 'is_active' => true]);
        $major = Jurusan::create(['code' => 'TKJ', 'name' => 'Teknik Komputer Jaringan']);
        Kelas::create(['name' => 'X TKJ 1', 'jurusan_id' => $major->id, 'tahun_ajaran_id' => $year->id]);

        $this->actingAs($this->admin)->delete(route('admin.jurusan.destroy', $major))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('jurusan', ['id' => $major->id]);
    }

    // 6. Kelas: Unique Constraint (name + jurusan_id + tahun_ajaran_id)
    public function test_class_requires_unique_name_per_major_and_academic_year(): void
    {
        $year = TahunAjaran::create(['name' => '2024/2025', 'is_active' => true]);
        $major = Jurusan::create(['code' => 'AKL', 'name' => 'Akuntansi']);

        Kelas::create(['name' => 'X AKL 1', 'jurusan_id' => $major->id, 'tahun_ajaran_id' => $year->id]);

        $response = $this->actingAs($this->admin)->post(route('admin.kelas.store'), [
            'name' => 'X AKL 1',
            'jurusan_id' => $major->id,
            'tahun_ajaran_id' => $year->id,
        ]);

        $response->assertSessionHasErrors('name');
    }

    // 7. Siswa: Creation records initial class history (PRD Bab 10.3)
    public function test_student_creation_records_initial_class_history(): void
    {
        $year = TahunAjaran::create(['name' => '2024/2025', 'is_active' => true]);
        $major = Jurusan::create(['code' => 'RPL', 'name' => 'Rekayasa Perangkat Lunak']);
        $kelas = Kelas::create(['name' => 'X RPL 1', 'jurusan_id' => $major->id, 'tahun_ajaran_id' => $year->id]);

        $response = $this->actingAs($this->admin)->post(route('admin.siswa.store'), [
            'nis' => '2024001',
            'nisn' => '0012345678',
            'name' => 'Budi Santoso',
            'gender' => 'L',
            'kelas_id' => $kelas->id,
            'entry_date' => '2024-07-15',
            'status' => 'active',
            'parent_name' => 'Pak Santoso',
            'parent_phone' => '081234567890',
        ]);

        $response->assertRedirect(route('admin.siswa.index'));

        $siswa = Siswa::where('nis', '2024001')->first();
        $this->assertNotNull($siswa);

        // Verify audit trail entry was created
        $history = RiwayatKelasSiswa::where('siswa_id', $siswa->id)->first();
        $this->assertNotNull($history);
        $this->assertEquals($kelas->id, $history->kelas_id);
        $this->assertEquals($year->id, $history->tahun_ajaran_id);
        $this->assertNull($history->end_date);
    }

    // 8. Siswa: Class change closes previous history and creates new one (PRD 10.3)
    public function test_student_class_change_updates_history_with_closed_and_new_records(): void
    {
        $year = TahunAjaran::create(['name' => '2024/2025', 'is_active' => true]);
        $major = Jurusan::create(['code' => 'RPL', 'name' => 'Rekayasa Perangkat Lunak']);
        $kelas1 = Kelas::create(['name' => 'X RPL 1', 'jurusan_id' => $major->id, 'tahun_ajaran_id' => $year->id]);
        $kelas2 = Kelas::create(['name' => 'XI RPL 1', 'jurusan_id' => $major->id, 'tahun_ajaran_id' => $year->id]);

        $siswa = Siswa::create([
            'nis' => '2024002',
            'name' => 'Siti Nurhaliza',
            'gender' => 'P',
            'kelas_id' => $kelas1->id,
            'entry_date' => '2024-07-15',
            'status' => 'active',
        ]);

        RiwayatKelasSiswa::create([
            'siswa_id' => $siswa->id,
            'kelas_id' => $kelas1->id,
            'tahun_ajaran_id' => $year->id,
            'start_date' => '2024-07-15',
        ]);

        // Now mutate/promote student to Kelas 2
        $response = $this->actingAs($this->admin)->put(route('admin.siswa.update', $siswa), [
            'nis' => '2024002',
            'name' => 'Siti Nurhaliza',
            'gender' => 'P',
            'kelas_id' => $kelas2->id,
            'entry_date' => '2024-07-15',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.siswa.index'));

        // Check history: should have 2 records
        $allHistory = RiwayatKelasSiswa::where('siswa_id', $siswa->id)->orderBy('start_date')->get();
        $this->assertCount(2, $allHistory);

        // Previous record closed
        $this->assertEquals($kelas1->id, $allHistory[0]->kelas_id);
        $this->assertNotNull($allHistory[0]->end_date);

        // New record active
        $this->assertEquals($kelas2->id, $allHistory[1]->kelas_id);
        $this->assertNull($allHistory[1]->end_date);
    }

    // 9. Siswa: Soft delete
    public function test_student_soft_deletes_preserves_records(): void
    {
        $year = TahunAjaran::create(['name' => '2024/2025', 'is_active' => true]);
        $major = Jurusan::create(['code' => 'RPL', 'name' => 'Rekayasa Perangkat Lunak']);
        $kelas = Kelas::create(['name' => 'X RPL 1', 'jurusan_id' => $major->id, 'tahun_ajaran_id' => $year->id]);

        $siswa = Siswa::create([
            'nis' => '2024003',
            'name' => 'Ahmad Dahlan',
            'gender' => 'L',
            'kelas_id' => $kelas->id,
            'entry_date' => '2024-07-15',
            'status' => 'active',
        ]);

        $this->actingAs($this->admin)->delete(route('admin.siswa.destroy', $siswa))
            ->assertRedirect(route('admin.siswa.index'));

        $this->assertSoftDeleted('siswa', ['id' => $siswa->id]);
    }

    // 10. Jenis Pembayaran: Cannot delete if linked to rate or bills
    public function test_payment_type_cannot_be_deleted_if_used_in_rates(): void
    {
        $year = TahunAjaran::create(['name' => '2024/2025', 'is_active' => true]);
        $jenis = JenisPembayaran::create(['code' => 'SPP', 'name' => 'SPP Bulanan', 'category' => 'recurring']);

        TarifPembayaran::create([
            'jenis_pembayaran_id' => $jenis->id,
            'tahun_ajaran_id' => $year->id,
            'amount' => 250000,
            'effective_date' => now()->toDateString(),
        ]);

        $this->actingAs($this->admin)->delete(route('admin.jenis-pembayaran.destroy', $jenis))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('jenis_pembayaran', ['id' => $jenis->id]);
    }

    // 11. Tarif Pembayaran: Creation
    public function test_rate_creation_and_assignment(): void
    {
        $year = TahunAjaran::create(['name' => '2024/2025', 'is_active' => true]);
        $jenis = JenisPembayaran::create(['code' => 'DSP', 'name' => 'Uang Pangkal', 'category' => 'one_time']);

        $response = $this->actingAs($this->admin)->post(route('admin.tarif-pembayaran.store'), [
            'jenis_pembayaran_id' => $jenis->id,
            'tahun_ajaran_id' => $year->id,
            'amount' => 1500000,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tarif_pembayaran', [
            'jenis_pembayaran_id' => $jenis->id,
            'tahun_ajaran_id' => $year->id,
            'amount' => 1500000,
        ]);
    }

    // 12. Potongan Siswa: Assignment and toggle
    public function test_student_discount_can_be_assigned_and_toggled(): void
    {
        $year = TahunAjaran::create(['name' => '2024/2025', 'is_active' => true]);
        $major = Jurusan::create(['code' => 'RPL', 'name' => 'Rekayasa Perangkat Lunak']);
        $kelas = Kelas::create(['name' => 'X RPL 1', 'jurusan_id' => $major->id, 'tahun_ajaran_id' => $year->id]);
        $siswa = Siswa::create(['nis' => '2024004', 'name' => 'Rina', 'gender' => 'P', 'kelas_id' => $kelas->id, 'entry_date' => '2024-07-15', 'status' => 'active']);

        $response = $this->actingAs($this->admin)->post(route('admin.potongan-siswa.store'), [
            'siswa_id' => $siswa->id,
            'tahun_ajaran_id' => $year->id,
            'discount_type' => 'percentage',
            'value' => 50,
            'reason' => 'Beasiswa Prestasi Juara 1 Nasional',
            'is_active' => '1',
        ]);

        $response->assertRedirect();
        $potongan = PotonganSiswa::where('siswa_id', $siswa->id)->first();
        $this->assertNotNull($potongan);
        $this->assertTrue($potongan->is_active);

        // Toggle active
        $this->actingAs($this->admin)->post(route('admin.potongan-siswa.toggle-active', $potongan));
        $this->assertFalse($potongan->fresh()->is_active);
    }

    // 13. Pengaturan Sekolah: Super Admin only
    public function test_only_super_admin_can_update_school_settings(): void
    {
        PengaturanSekolah::create([
            'school_name' => 'SMK Lama',
            'transaction_number_format' => 'TRX-{Ymd}-{###}',
        ]);

        $response = $this->actingAs($this->superAdmin)->put(route('admin.settings.update'), [
            'school_name' => 'SMK Negeri 1 Maju Bersama',
            'transaction_number_format' => 'SPP/{Y}/{m}/{###}',
        ]);

        $response->assertRedirect(route('admin.settings.index'));
        $this->assertEquals('SMK Negeri 1 Maju Bersama', PengaturanSekolah::first()->school_name);

        // Admin biasa harus ditolak (403)
        $this->actingAs($this->admin)->put(route('admin.settings.update'), [
            'school_name' => 'Hacker Name',
            'transaction_number_format' => 'TEST',
        ])->assertStatus(403);
    }
}
