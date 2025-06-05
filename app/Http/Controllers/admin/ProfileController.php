<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('pageadmin.profile.show', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
            'foto' => 'nullable|image'
        ]);

        // Update data dasar
        $user->name = $request->name;
        $user->no_hp = $request->no_hp;
        $user->alamat = $request->alamat;

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Handle upload foto
        if ($request->hasFile('foto')) {
            // Buat direktori jika belum ada
            $uploadPath = public_path('uploads/profile');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true);
            }

            // Hapus foto lama jika ada
            if ($user->foto && File::exists(public_path($user->foto))) {
                File::delete(public_path($user->foto));
            }

            // Upload foto baru
            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $foto->move($uploadPath, $fotoName);
            $user->foto = 'uploads/profile/' . $fotoName;
        }

        $user->save();

        return redirect()->route('profile')->with([
            'success' => true,
            'title' => 'Berhasil!',
            'message' => 'Profile berhasil diperbarui',
            'icon' => 'success'
        ]);
    }
}
