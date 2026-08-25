<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePesanKontakRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:150'],
            'subjek' => ['required', Rule::in(['pendaftaran', 'biaya', 'jurusan', 'lainnya'])],
            'pesan' => ['required', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'nama lengkap',
            'whatsapp' => 'nomor WhatsApp',
            'subjek' => 'subjek',
            'pesan' => 'pesan',
        ];
    }
}