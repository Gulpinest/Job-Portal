<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResumeRequest extends FormRequest
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
            'judul_resume' => 'required|string|max:255',
            'file_resume' => 'required|mimes:pdf,doc,docx|max:5120', // Max 5MB
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'judul_resume.required' => 'Judul resume harus diisi.',
            'judul_resume.max' => 'Judul resume tidak boleh lebih dari 255 karakter.',
            'file_resume.required' => 'File resume harus diunggah.',
            'file_resume.mimes' => 'File resume harus bertipe PDF, DOC, atau DOCX.',
            'file_resume.max' => 'Ukuran file resume tidak boleh lebih dari 5MB.',
        ];
    }
}
