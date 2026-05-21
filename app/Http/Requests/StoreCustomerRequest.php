<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|unique:customers,email',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string|max:255',
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
            'nama.required' => 'Nama customer harus diisi.',
            'nama.max' => 'Nama customer maksimal 100 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'no_hp.required' => 'Nomor HP harus diisi.',
            'no_hp.max' => 'Nomor HP maksimal 20 karakter.',
            'alamat.max' => 'Alamat maksimal 255 karakter.',
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
            'nama' => 'nama customer',
            'email' => 'email',
            'no_hp' => 'nomor HP',
            'alamat' => 'alamat',
        ];
    }
}
