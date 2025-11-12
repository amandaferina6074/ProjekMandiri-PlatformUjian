<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    // Aturan validasi untuk update profil
    public function rules(): array
    {
        return [
            // Nama wajib diisi, berupa teks, maksimal 255 karakter
            'name' => ['required', 'string', 'max:255'],

            // Email wajib diisi, huruf kecil, format email, unik kecuali milik user sendiri
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }
}
