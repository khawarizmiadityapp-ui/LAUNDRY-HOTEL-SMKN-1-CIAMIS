<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransaksiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow authenticated users
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'service_type' => 'required|in:regular,express',
            'weight' => 'required|numeric|min:0.1|max:1000',
            'notes' => 'nullable|string|max:500',
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
            'customer_name.required' => 'Nama customer harus diisi.',
            'customer_name.max' => 'Nama customer maksimal 100 karakter.',
            'customer_phone.required' => 'Nomor telepon customer harus diisi.',
            'customer_phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'service_type.required' => 'Jenis layanan harus dipilih.',
            'service_type.in' => 'Jenis layanan tidak valid. Pilih Regular atau Express.',
            'weight.required' => 'Berat cucian harus diisi.',
            'weight.numeric' => 'Berat cucian harus berupa angka.',
            'weight.min' => 'Berat cucian minimal 0.1 kg.',
            'weight.max' => 'Berat cucian maksimal 1000 kg.',
            'notes.max' => 'Catatan maksimal 500 karakter.',
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
            'customer_name' => 'nama customer',
            'customer_phone' => 'nomor telepon',
            'service_type' => 'jenis layanan',
            'weight' => 'berat cucian',
            'notes' => 'catatan',
        ];
    }
}
