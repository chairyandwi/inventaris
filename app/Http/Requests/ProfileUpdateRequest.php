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
        return [
            'nama' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique(User::class, 'username')->ignore($this->user()->id),
            ],
            'nohp' => ['nullable', 'string', 'max:20'],
            'tipe_peminjam' => ['required', Rule::in(['mahasiswa', 'pegawai'])],
            'prodi' => ['nullable', 'string', 'max:100', 'required_if:tipe_peminjam,mahasiswa'],
            'angkatan' => ['nullable', 'string', 'max:10', 'required_if:tipe_peminjam,mahasiswa'],
            'nim' => [
                'nullable',
                'string',
                'max:50',
                'required_if:tipe_peminjam,mahasiswa',
                Rule::unique(User::class, 'nim')->ignore($this->user()->id),
            ],
            'divisi' => ['nullable', 'string', 'max:100', 'required_if:tipe_peminjam,pegawai'],
            'foto_identitas_mahasiswa' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
                Rule::requiredIf(fn () => $this->input('tipe_peminjam') === 'mahasiswa' && !$this->user()->foto_identitas_mahasiswa),
            ],
            'foto_identitas_pegawai' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
                Rule::requiredIf(fn () => $this->input('tipe_peminjam') === 'pegawai' && !$this->user()->foto_identitas_pegawai),
            ],
        ];
    }
}
