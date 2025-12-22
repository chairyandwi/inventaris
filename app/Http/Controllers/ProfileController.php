<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        if ($user && $user->role === 'peminjam' && $request->routeIs('peminjam.profile.*')) {
            return view('profile.peminjam-edit', [
                'user' => $user,
                'missingFields' => $user->missingProfileFields(),
            ]);
        }

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->tipe_peminjam !== 'mahasiswa') {
            $user->prodi = null;
            $user->angkatan = null;
            $user->nim = null;
            if ($user->foto_identitas_mahasiswa) {
                Storage::disk('public')->delete($user->foto_identitas_mahasiswa);
            }
            $user->foto_identitas_mahasiswa = null;
        }

        if ($user->tipe_peminjam !== 'pegawai') {
            $user->divisi = null;
            if ($user->foto_identitas_pegawai) {
                Storage::disk('public')->delete($user->foto_identitas_pegawai);
            }
            $user->foto_identitas_pegawai = null;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('foto_identitas_mahasiswa')) {
            if ($user->foto_identitas_mahasiswa) {
                Storage::disk('public')->delete($user->foto_identitas_mahasiswa);
            }
            $user->foto_identitas_mahasiswa = $request->file('foto_identitas_mahasiswa')->store('identitas_peminjam', 'public');
        }

        if ($request->hasFile('foto_identitas_pegawai')) {
            if ($user->foto_identitas_pegawai) {
                Storage::disk('public')->delete($user->foto_identitas_pegawai);
            }
            $user->foto_identitas_pegawai = $request->file('foto_identitas_pegawai')->store('identitas_peminjam', 'public');
        }

        $user->save();

        $redirectRoute = $request->routeIs('peminjam.profile.*') ? 'peminjam.profile.edit' : 'profile.edit';

        return Redirect::route($redirectRoute)->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
