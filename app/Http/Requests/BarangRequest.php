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
            'keterangan' => ['nullable', 'string', 'max:500'],
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
        ];
    }
}
