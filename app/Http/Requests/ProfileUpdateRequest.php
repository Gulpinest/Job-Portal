<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];

        // Add company fields if user is company
        if ($this->user()->isCompany()) {
            $rules = array_merge($rules, [
                'nama_perusahaan' => ['required', 'string', 'max:255'],
                'no_telp_perusahaan' => ['nullable', 'string', 'max:20'],
                'alamat_perusahaan' => ['nullable', 'string', 'max:500'],
                'desc_company' => ['nullable', 'string', 'max:2000'],
            ]);
        }

        return $rules;
    }
}
