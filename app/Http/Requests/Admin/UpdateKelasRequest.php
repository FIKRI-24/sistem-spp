<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKelasRequest extends FormRequest
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
        $id = $this->route('kelas')?->id ?? $this->route('kela');

        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kelas')->where(function ($query) {
                    return $query->where('jurusan_id', $this->jurusan_id)
                        ->where('tahun_ajaran_id', $this->tahun_ajaran_id);
                })->ignore($id),
            ],
            'jurusan_id' => ['required', 'exists:jurusan,id'],
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajaran,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama rombongan belajar / kelas wajib diisi.',
            'name.unique' => 'Kelas dengan nama tersebut sudah ada pada jurusan dan tahun ajaran yang dipilih.',
            'jurusan_id.required' => 'Jurusan wajib dipilih.',
            'jurusan_id.exists' => 'Jurusan yang dipilih tidak valid.',
            'tahun_ajaran_id.required' => 'Tahun ajaran wajib dipilih.',
            'tahun_ajaran_id.exists' => 'Tahun ajaran yang dipilih tidak valid.',
        ];
    }
}
