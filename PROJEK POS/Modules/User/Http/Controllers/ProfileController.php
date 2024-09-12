<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\User\Rules\MatchCurrentPassword;
use Spatie\MediaLibrary\Models\Media;
use App\Models\User; // Pastikan model diimport

class ProfileController extends Controller
{
    public function edit()
    {
        return view('user::profile');
    }

    public function update(Request $request)
    {
        // Validasi data
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'image' => 'nullable|file|mimes:jpg,png,jpeg,gif|max:2048',
        ]);
    
        // Update data pengguna
        $user = auth()->user();
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);
    
        // Menangani file gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($user->hasMedia('avatars')) {
                $user->clearMediaCollection('avatars');
            }
    
            // Simpan gambar baru
            $image = $request->file('image');
            $user->addMedia($image)->toMediaCollection('avatars');
        }
    
        // Tampilkan notifikasi dan kembalikan ke halaman sebelumnya
        session()->flash('success', 'Profile Updated!');
        return back();
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'max:255', new MatchCurrentPassword()],
            'password' => 'required|min:8|max:255|confirmed'
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);

        toast('Password Updated!', 'success');

        return back();
    }
}
