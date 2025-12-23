<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'idbarang' => ['required', 'exists:barang,idbarang'],
            'kegiatan' => ['required', 'in:kampus,luar'],
            'keterangan_kegiatan' => ['required', 'string', 'max:255'],
            'idruang' => ['required_if:kegiatan,kampus', 'nullable', 'exists:ruang,idruang'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'tgl_pinjam_rencana' => ['required', 'date', 'after_or_equal:today'],
            'tgl_kembali_rencana' => ['required', 'date', 'after_or_equal:tgl_pinjam_rencana'],
        ];
    }

    public function messages(): array
    {
        return [
            'idruang.required_if' => 'Ruang wajib dipilih untuk kegiatan di kampus.',
        ];
    }
}
