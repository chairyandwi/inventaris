<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BarangMasukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'idbarang' => ['required', 'exists:barang,idbarang'],
            'idkategori' => ['nullable', 'exists:kategori,idkategori'],
            'tgl_masuk' => ['nullable', 'date'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'status_barang' => ['required', Rule::in(['baru', 'bekas'])],
            'jenis_barang' => ['nullable', Rule::in(['tetap', 'pinjam', 'habis_pakai'])],
            'is_pc' => ['nullable', 'boolean'],
            'merk' => ['nullable', 'string', 'max:100'],
            'ram_brand' => ['nullable', 'string', 'max:100'],
            'ram_capacity_gb' => ['nullable', 'integer', 'min:1'],
            'storage_type' => ['nullable', 'string', 'max:50'],
            'storage_capacity_gb' => ['nullable', 'integer', 'min:1'],
            'processor' => ['nullable', 'string', 'max:100'],
            'monitor_brand' => ['nullable', 'string', 'max:100'],
            'monitor_model' => ['nullable', 'string', 'max:100'],
            'monitor_size_inch' => ['nullable', 'numeric', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'catatan' => ['nullable', 'string', 'max:500'],
            'distribusi_ruang' => ['required_if:jenis_barang,tetap', 'array'],
            'distribusi_ruang.*' => ['required_with:distribusi_jumlah.*', 'exists:ruang,idruang'],
            'distribusi_jumlah' => ['required_if:jenis_barang,tetap', 'array'],
            'distribusi_jumlah.*' => ['required_with:distribusi_ruang.*', 'integer', 'min:1'],
            'distribusi_catatan' => ['nullable', 'array'],
            'distribusi_catatan.*' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $isPc = (bool) $this->boolean('is_pc');
            if ($isPc) {
                $requiredPc = ['ram_capacity_gb', 'storage_type', 'storage_capacity_gb', 'processor', 'monitor_size_inch'];
                foreach ($requiredPc as $field) {
                    if (!$this->filled($field)) {
                        $validator->errors()->add($field, 'Spesifikasi PC wajib diisi.');
                    }
                }
            }

            $jenis = $this->input('jenis_barang');
            if ($jenis === 'tetap') {
                $distribusiRuang = $this->input('distribusi_ruang', []);
                $distribusiJumlah = $this->input('distribusi_jumlah', []);
                $rows = collect($distribusiRuang)->filter()->keys();
                if ($rows->isEmpty()) {
                    $validator->errors()->add('distribusi_ruang', 'Distribusi ruang wajib diisi untuk barang tetap.');
                    return;
                }
                $totalDistribusi = 0;
                foreach ($rows as $idx) {
                    $jumlah = (int) ($distribusiJumlah[$idx] ?? 0);
                    if ($jumlah < 1) {
                        $validator->errors()->add('distribusi_jumlah.' . $idx, 'Jumlah unit per ruang minimal 1.');
                    }
                    $totalDistribusi += $jumlah;
                }
                $jumlahMasuk = (int) $this->input('jumlah', 0);
                if ($jumlahMasuk > 0 && $totalDistribusi !== $jumlahMasuk) {
                    $validator->errors()->add('distribusi_jumlah', 'Total jumlah distribusi harus sama dengan jumlah masuk.');
                }
            }
        });
    }
}
