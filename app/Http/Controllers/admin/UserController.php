<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();
            return view('pageadmin.user.show', compact('users'));
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat memuat data');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,petugas'
        ]);

        try {
            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role
            ]);
            Alert::success('Berhasil', 'Data user berhasil ditambahkan');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan data user');
        }

        return redirect()->route('user');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'role' => 'required|in:admin,petugas'
        ]);

        try {
            $user = User::findOrFail($id);
            $data = [
                'name' => $request->name,
                'username' => $request->username,
                'role' => $request->role
            ];

            if ($request->filled('password')) {
                $request->validate([
                    'password' => 'string|min:6'
                ]);
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);
            Alert::success('Berhasil', 'Data user berhasil diperbarui');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal memperbarui data user');
        }

        return redirect()->route('user');
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            Alert::success('Berhasil', 'Data user berhasil dihapus');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menghapus data user');
        }

        return redirect()->route('user');
    }
}
