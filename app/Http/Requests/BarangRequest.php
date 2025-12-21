<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BarangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $barangId = $this->route('barang')?->idbarang;

        return [
            'idkategori' => ['required', 'exists:kategori,idkategori'],
            'kode_barang' => [
                'required',
                'string',
                'max:20',
                Rule::unique('barang', 'kode_barang')->ignore($barangId, 'idbarang'),
            ],
            'nama_barang' => ['required', 'string', 'max:100'],
            'jenis_barang' => ['nullable', 'in:pinjam,tetap'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'distribusi_ruang' => ['required_if:jenis_barang,tetap', 'array', 'min:1'],
            'distribusi_ruang.*' => ['required_with:distribusi_jumlah.*', 'exists:ruang,idruang'],
            'distribusi_jumlah' => ['required_if:jenis_barang,tetap', 'array'],
            'distribusi_jumlah.*' => ['required_with:distribusi_ruang.*', 'integer', 'min:1', 'max:500'],
            'distribusi_catatan' => ['nullable', 'array'],
            'distribusi_catatan.*' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'idkategori.required' => 'Kategori wajib dipilih.',
            'idkategori.exists' => 'Kategori tidak valid.',
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique' => 'Kode barang sudah ada.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'distribusi_ruang.required_if' => 'Pilih minimal satu ruang inventaris untuk barang tetap.',
            'distribusi_ruang.*.exists' => 'Ruang tidak valid.',
            'distribusi_jumlah.*.integer' => 'Jumlah unit harus berupa angka.',
        ];
    }
}
