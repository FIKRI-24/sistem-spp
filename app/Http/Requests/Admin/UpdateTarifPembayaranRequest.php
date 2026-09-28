<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTarifPembayaranRequest extends FormRequest
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
            'jenis_pembayaran_id' => ['required', 'exists:jenis_pembayaran,id'],
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajaran,id'],
            'jurusan_id' => ['nullable', 'exists:jurusan,id'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'effective_date' => ['nullable', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'jenis_pembayaran_id.required' => 'Pos jenis pembayaran wajib dipilih.',
            'tahun_ajaran_id.required' => 'Tahun ajaran wajib dipilih.',
            'amount.required' => 'Besaran nominal tarif wajib diisi.',
            'amount.numeric' => 'Nominal tarif harus berupa angka.',
            'amount.min' => 'Nominal tarif tidak boleh bernilai negatif.',
        ];
    }
}
