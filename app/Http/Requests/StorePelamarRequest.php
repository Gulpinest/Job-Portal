<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePelamarRequest extends FormRequest
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
            'nama_pelamar' => 'required|string|max:255',
            'status_pekerjaan' => 'required|in:Employed,Unemployed,Studying,Other',
            'no_telp' => 'required|string|regex:/^[0-9]{10,15}$/',
            'alamat' => 'required|string|max:500',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tgl_lahir' => 'required|date|before:today',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'nama_pelamar.required' => 'Nama pelamar harus diisi.',
            'nama_pelamar.max' => 'Nama pelamar tidak boleh lebih dari 255 karakter.',
            'status_pekerjaan.required' => 'Status pekerjaan harus dipilih.',
            'status_pekerjaan.in' => 'Status pekerjaan tidak valid.',
            'no_telp.required' => 'Nomor telepon harus diisi.',
            'no_telp.regex' => 'Nomor telepon harus berupa angka 10-15 digit.',
            'alamat.required' => 'Alamat harus diisi.',
            'alamat.max' => 'Alamat tidak boleh lebih dari 500 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan.',
            'tgl_lahir.required' => 'Tanggal lahir harus diisi.',
            'tgl_lahir.date' => 'Tanggal lahir harus format tanggal yang valid.',
            'tgl_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
        ];
    }
}
