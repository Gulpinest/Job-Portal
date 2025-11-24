<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLowonganRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User harus authenticated dan memiliki role company (role_id = 3)
        return auth()->check() && auth()->user()->isCompany();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'lokasi_kantor' => 'required|string|max:255',
            'gaji' => 'required|numeric|min:0',
            'keterampilan' => 'required|string',
            'tipe_kerja' => 'required|in:Full-time,Part-time,Remote,Freelance',
            'deskripsi' => 'required|string|min:10',
            'status' => 'sometimes|in:Open,Closed',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul lowongan harus diisi.',
            'judul.max' => 'Judul lowongan tidak boleh lebih dari 255 karakter.',
            'posisi.required' => 'Posisi harus diisi.',
            'posisi.max' => 'Posisi tidak boleh lebih dari 255 karakter.',
            'lokasi_kantor.required' => 'Lokasi kantor harus diisi.',
            'lokasi_kantor.max' => 'Lokasi kantor tidak boleh lebih dari 255 karakter.',
            'gaji.required' => 'Gaji harus diisi.',
            'gaji.numeric' => 'Gaji harus berupa angka.',
            'gaji.min' => 'Gaji tidak boleh kurang dari 0.',
            'keterampilan.required' => 'Keterampilan yang diperlukan harus diisi.',
            'tipe_kerja.required' => 'Tipe pekerjaan harus dipilih.',
            'tipe_kerja.in' => 'Tipe pekerjaan harus salah satu dari: Full-time, Part-time, Remote, atau Freelance.',
            'deskripsi.required' => 'Deskripsi lowongan harus diisi.',
            'deskripsi.min' => 'Deskripsi lowongan minimal harus 10 karakter.',
            'status.in' => 'Status harus salah satu dari: Open atau Closed.',
        ];
    }
}
