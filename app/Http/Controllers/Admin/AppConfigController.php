<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppConfiguration;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AppConfigController extends Controller
{
    public function index()
    {
        $config = AppConfiguration::first() ?? new AppConfiguration();

        return view('admin.app-config', compact('config'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_kampus' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'website' => 'nullable|string|max:100',
            'profil' => 'nullable|string',
            'petugas_inventaris' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'petugas_signature' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'apply_layout' => 'nullable|boolean',
            'apply_pdf' => 'nullable|boolean',
            'apply_email' => 'nullable|boolean',
        ]);

        $config = AppConfiguration::first() ?? new AppConfiguration();
        $data = $request->only(['nama_kampus', 'alamat', 'telepon', 'email', 'website', 'profil', 'petugas_inventaris']);
        $data['apply_layout'] = $request->boolean('apply_layout');
        $data['apply_pdf'] = $request->boolean('apply_pdf');
        $data['apply_email'] = $request->boolean('apply_email');

        if ($request->hasFile('logo')) {
            if ($config->logo) {
                Storage::disk('public')->delete($config->logo);
            }
            $data['logo'] = $request->file('logo')->store('logo', 'public');
        }
        if ($request->hasFile('petugas_signature')) {
            if ($config->petugas_signature) {
                Storage::disk('public')->delete($config->petugas_signature);
            }
            $data['petugas_signature'] = $request->file('petugas_signature')->store('signatures', 'public');
        }

        $config->fill($data);
        $config->save();

        Cache::forget('app_config');

        LogAktivitas::create([
            'iduser' => Auth::id(),
            'aksi' => 'Perbarui Konfigurasi Aplikasi',
            'deskripsi' => 'Mengubah profil kampus dan pengaturan aplikasi.',
        ]);

        return redirect()->route('admin.aplikasi.index')->with('success', 'Konfigurasi aplikasi berhasil diperbarui.');
    }
}
