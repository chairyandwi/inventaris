<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();
        $stats = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'pegawai' => User::where('role', 'pegawai')->count(),
            'kabag' => User::where('role', 'kabag')->count(),
            'peminjam' => User::where('role', 'peminjam')->count(),
        ];

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('nama', 'like', "%{$searchTerm}%")
                ->orWhere('email', 'like', "%{$searchTerm}%")
                ->orWhere('username', 'like', "%{$searchTerm}%")
                ->orWhere('nohp', 'like', "%{$searchTerm}%")
                ->orWhere('role', 'like', "%{$searchTerm}%");
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'id'); // <- ubah jadi id
        $sortDirection = $request->get('sort_direction', 'asc');

        $allowedSortFields = ['id', 'nama', 'email', 'username', 'role']; // <- sesuaikan
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->orderBy('id', 'asc');
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $allowedPerPage = [10, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $users = $query->paginate($perPage)->appends($request->all());

        return view('pegawai.user.index', compact('users', 'stats'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pegawai.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $routePrefix = Auth::check() && Auth::user()->role === 'admin' ? 'admin' : 'pegawai';
        $validator = Validator::make($request->all(), [
            'nama'      => 'required|string|max:255',
            'username'  => 'required|string|max:100|unique:users,username',
            'email'     => 'required|email|unique:users,email',
            'nohp'      => 'nullable|string|max:20',
            'rfid_uid'  => 'nullable|string|max:50|unique:users,rfid_uid',
            'role'      => 'required|in:admin,kabag,pegawai,peminjam',
            'password'  => 'required|string|min:6|confirmed'
        ], [
            'nama.required' => 'Nama wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah digunakan',
            'role.required' => 'Role wajib dipilih',
            'password.required' => 'Password wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $rfidUid = $request->input('rfid_uid');
        $rfidUid = is_string($rfidUid) ? trim($rfidUid) : null;
        $rfidUid = $rfidUid !== '' ? $rfidUid : null;

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'nohp' => $request->nohp,
            'rfid_uid' => $rfidUid,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route($routePrefix . '.user.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('pegawai.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $routePrefix = Auth::check() && Auth::user()->role === 'admin' ? 'admin' : 'pegawai';
        $validator = Validator::make($request->all(), [
            'nama'      => 'required|string|max:255',
            'username'  => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($user->id, 'id'),
            ],
            'email'     => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id, 'id'),
            ],
            'nohp'      => 'nullable|string|max:20',
            'rfid_uid'  => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('users', 'rfid_uid')->ignore($user->id, 'id'),
            ],
            'role'      => 'required|in:admin,kabag,pegawai,peminjam',
            'password'  => 'nullable|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $rfidUid = $request->input('rfid_uid');
        $rfidUid = is_string($rfidUid) ? trim($rfidUid) : null;
        $rfidUid = $rfidUid !== '' ? $rfidUid : null;

        $data = $request->only(['nama', 'username', 'email', 'nohp', 'role']);
        $data['rfid_uid'] = $rfidUid;
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route($routePrefix . '.user.index')->with('success', 'User berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $routePrefix = Auth::check() && Auth::user()->role === 'admin' ? 'admin' : 'pegawai';
        try {
            $user->delete();
            return redirect()->route($routePrefix . '.user.index')->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route($routePrefix . '.user.index')->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    /**
     * Get all users for API (optional).
     */
    public function api()
    {
        $users = User::select('id', 'nama', 'username', 'email', 'nohp', 'role')
            ->orderBy('nama')
            ->get();

        return response()->json($users);
    }
}
