<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'nohp' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    
        $user = User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'nohp' => $request->nohp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'peminjam', // default
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($user->role === 'admin') {
            return redirect()->route('admin.index');
        } elseif ($user->role === 'kabag') {
            return redirect()->route('kabag.index');
        } elseif ($user->role === 'pegawai') {
            return redirect()->route('pegawai.index');
        } else {
            // default peminjam dashboard
            return redirect()->route('peminjam.index');
        }
    }
}
