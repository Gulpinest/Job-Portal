<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLamaranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User harus authenticated dan memiliki role pelamar (role_id = 2)
        return auth()->check() && auth()->user()->isPelamar();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_lowongan' => 'required|exists:lowongans,id_lowongan',
            'cv' => 'required|mimes:pdf,doc,docx|max:5120', // Max 5MB
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'id_lowongan.required' => 'Lowongan harus dipilih.',
            'id_lowongan.exists' => 'Lowongan yang dipilih tidak ditemukan.',
            'cv.required' => 'File CV harus diunggah.',
            'cv.mimes' => 'File CV harus bertipe PDF, DOC, atau DOCX.',
            'cv.max' => 'Ukuran file CV tidak boleh lebih dari 5MB.',
        ];
    }
}
