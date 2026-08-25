<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKonfirmasiTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'no_pendaftaran' => ['required', 'string', 'exists:pendaftar,no_pendaftaran'],
            'nama_siswa' => ['required', 'string', 'max:150'],
            'rekening_id' => ['required', 'exists:rekening,id'],
            'bank_pengirim' => ['required', 'string', 'max:50'],
            'tanggal_transfer' => ['required', 'date', 'before_or_equal:today'],
            'nominal_transfer' => ['required', 'numeric', 'min:1000'],
            'bukti_transfer' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'no_pendaftaran' => 'nomor pendaftaran',
            'nama_siswa' => 'nama calon siswa',
            'rekening_id' => 'rekening tujuan',
            'bank_pengirim' => 'bank pengirim',
            'tanggal_transfer' => 'tanggal transfer',
            'nominal_transfer' => 'nominal transfer',
            'bukti_transfer' => 'bukti transfer',
        ];
    }
}