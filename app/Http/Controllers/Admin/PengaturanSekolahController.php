<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePengaturanSekolahRequest;
use App\Models\PengaturanSekolah;
use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PengaturanSekolahController extends Controller
{
    public function index(): View
    {
        $settings = PengaturanSekolah::firstOrCreate([], [
            'school_name' => 'SMK Negeri 1 Sistem SPP',
            'address' => 'Jl. Pendidikan No. 123, Kota Edukasi',
            'phone' => '(021) 12345678',
            'email' => 'info@sekolah.sch.id',
            'transaction_number_format' => 'TRX-{Ymd}-{###}',
            'auto_create_portal_account' => true,
        ]);

        $tahunAjaranOptions = TahunAjaran::orderByDesc('name')->get();

        return view('admin.settings.index', compact('settings', 'tahunAjaranOptions'));
    }

    public function update(UpdatePengaturanSekolahRequest $request): RedirectResponse
    {
        $settings = PengaturanSekolah::first();
        if (! $settings) {
            $settings = new PengaturanSekolah;
        }

        $data = $request->validated();
        $data['auto_create_portal_account'] = $request->boolean('auto_create_portal_account');

        if ($request->hasFile('logo')) {
            if ($settings->logo_path && Storage::disk('public')->exists($settings->logo_path)) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('school_logos', 'public');
        }

        $settings->fill($data);
        $settings->save();

        // Jika mengubah tahun ajaran aktif melalui pengaturan sekolah
        if (! empty($data['active_tahun_ajaran_id'])) {
            TahunAjaran::where('is_active', true)->where('id', '!=', $data['active_tahun_ajaran_id'])->update(['is_active' => false]);
            TahunAjaran::where('id', $data['active_tahun_ajaran_id'])->update(['is_active' => true]);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Konfigurasi identitas sekolah dan cetak kuitansi berhasil diperbarui.');
    }
}
