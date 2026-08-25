<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePendaftarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'jenis_kelamin' => ['required', Rule::in(['laki-laki', 'perempuan'])],
            'agama' => ['required', Rule::in(['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu', 'lainnya'])],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],

            'asal_sekolah' => ['required', 'string', 'max:150'],
            'nisn' => ['required', 'digits:10', 'unique:pendaftar,nisn'],
            'nik' => ['required', 'digits:16', 'unique:pendaftar,nik'],
            'anak_ke' => ['required', 'integer', 'min:1'],

            'whatsapp_siswa' => ['required', 'string', 'max:20'],
            'email_siswa' => ['required', 'email', 'max:150'],
            'alamat_lengkap' => ['required', 'string'],
            'desa_kelurahan' => ['required', 'string', 'max:100'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'kabupaten' => ['required', 'string', 'max:100'],

            'jurusan_id' => ['required', 'exists:jurusan,id'],

            'nama_ibu' => ['required', 'string', 'max:150'],
            'pendidikan_ibu' => ['required', Rule::in(['tidak-sekolah', 'sd', 'smp', 'sma-smk', 'd3', 's1', 's2', 's3'])],
            'pekerjaan_ibu' => ['required', 'string', 'max:100'],
            'nama_ayah' => ['required', 'string', 'max:150'],
            'pendidikan_ayah' => ['required', Rule::in(['tidak-sekolah', 'sd', 'smp', 'sma-smk', 'd3', 's1', 's2', 's3'])],
            'pekerjaan_ayah' => ['required', 'string', 'max:100'],
            'whatsapp_ortu' => ['required', 'string', 'max:20'],

            'punya_kip' => ['required', Rule::in(['ya', 'tidak'])],
            'nomor_kip' => ['required_if:punya_kip,ya', 'nullable', 'string', 'max:30'],
            'rekomendasi_guru_id' => ['nullable', 'exists:guru,id'],

            'persetujuan_data' => ['accepted'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_lengkap' => 'nama lengkap',
            'jenis_kelamin' => 'jenis kelamin',
            'tempat_lahir' => 'tempat lahir',
            'tanggal_lahir' => 'tanggal lahir',
            'asal_sekolah' => 'asal sekolah',
            'whatsapp_siswa' => 'nomor WhatsApp',
            'email_siswa' => 'email',
            'alamat_lengkap' => 'alamat lengkap',
            'desa_kelurahan' => 'desa/kelurahan',
            'jurusan_id' => 'kompetensi keahlian',
            'nama_ibu' => 'nama ibu',
            'pendidikan_ibu' => 'pendidikan ibu',
            'pekerjaan_ibu' => 'pekerjaan ibu',
            'nama_ayah' => 'nama ayah',
            'pendidikan_ayah' => 'pendidikan ayah',
            'pekerjaan_ayah' => 'pekerjaan ayah',
            'whatsapp_ortu' => 'WhatsApp orang tua',
            'nomor_kip' => 'nomor KIP',
            'rekomendasi_guru_id' => 'rekomendasi guru',
        ];
    }
}