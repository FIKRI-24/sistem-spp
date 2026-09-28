<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePengaturanSekolahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('super_admin');
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'school_name' => ['required', 'string', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
            'receipt_header_note' => ['nullable', 'string'],
            'receipt_footer_note' => ['nullable', 'string'],
            'bank_account_info' => ['nullable', 'string'],
            'transaction_number_format' => ['required', 'string', 'max:50'],
            'active_tahun_ajaran_id' => ['nullable', 'exists:tahun_ajaran,id'],
            'auto_create_portal_account' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'school_name.required' => 'Nama sekolah / lembaga pendidikan wajib diisi.',
            'logo.image' => 'File logo harus berupa gambar valid.',
            'logo.max' => 'Ukuran file logo maksimal 2MB.',
        ];
    }
}
