<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreJenisPembayaranRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:20', 'unique:jenis_pembayaran,code'],
            'name' => ['required', 'string', 'max:100'],
            'category' => ['required', 'in:recurring,one_time'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Kode pos / jenis pembayaran wajib diisi.',
            'code.unique' => 'Kode ini sudah digunakan oleh jenis pembayaran lain.',
            'name.required' => 'Nama jenis pembayaran wajib diisi.',
            'category.required' => 'Kategori pembayaran wajib dipilih.',
            'category.in' => 'Kategori hanya boleh berupa rutin (bulanan) atau sekali bayar.',
        ];
    }
}
