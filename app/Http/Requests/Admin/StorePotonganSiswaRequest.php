<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePotonganSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['super_admin', 'admin']);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'siswa_id' => ['required', 'exists:siswa,id'],
            'jenis_pembayaran_id' => ['nullable', 'exists:jenis_pembayaran,id'],
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajaran,id'],
            'discount_type' => ['required', 'in:fixed,percentage'],
            'value' => ['required', 'numeric', 'min:0'],
            'reason' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'siswa_id.required' => 'Siswa penerima beasiswa/potongan wajib dipilih.',
            'tahun_ajaran_id.required' => 'Tahun ajaran wajib dipilih.',
            'discount_type.required' => 'Jenis potongan wajib dipilih.',
            'value.required' => 'Nilai besaran potongan wajib diisi.',
            'value.numeric' => 'Nilai potongan harus berupa angka.',
            'reason.required' => 'Alasan atau keterangan beasiswa wajib diisi (misal: Prestasi, Yatim).',
        ];
    }
}
