<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLayananRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => 'sometimes|string|max:100',
            'kategori' => ['sometimes', Rule::in(['kiloan', 'satuan'])],
            'harga' => 'sometimes|numeric|min:0',
            'satuan' => 'sometimes|string|max:20',
            'estimasi' => 'nullable|string|max:100',
            'badge' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:50',
            'status' => 'sometimes|boolean',
            'needs_washing' => 'sometimes|boolean',
            'needs_ironing' => 'sometimes|boolean',
            'needs_packing' => 'sometimes|boolean',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.max' => 'Nama layanan maksimal 100 karakter.',
            'kategori.in' => 'Kategori layanan tidak valid. Pilih Kiloan atau Satuan.',
            'harga.numeric' => 'Harga layanan harus berupa angka.',
            'harga.min' => 'Harga layanan minimal Rp 0.',
            'satuan.max' => 'Satuan layanan maksimal 20 karakter.',
            'estimasi.max' => 'Estimasi waktu maksimal 100 karakter.',
            'badge.max' => 'Badge maksimal 50 karakter.',
            'icon.max' => 'Icon maksimal 50 karakter.',
            'status.boolean' => 'Status harus aktif atau nonaktif.',
            'needs_washing.boolean' => 'Nilai needs_washing harus true atau false.',
            'needs_ironing.boolean' => 'Nilai needs_ironing harus true atau false.',
            'needs_packing.boolean' => 'Nilai needs_packing harus true atau false.',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nama' => 'nama layanan',
            'kategori' => 'kategori',
            'harga' => 'harga',
            'satuan' => 'satuan',
            'estimasi' => 'estimasi waktu',
            'badge' => 'badge',
            'icon' => 'icon',
            'status' => 'status',
            'needs_washing' => 'perlu dicuci',
            'needs_ironing' => 'perlu disetrika',
            'needs_packing' => 'perlu dipacking',
        ];
    }
}
