<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Modules\Upload\Entities\Upload;
use Modules\User\Rules\MatchCurrentPassword;

class ProfileController extends Controller
{

    public function edit() {
        return view('user::profile');
    }


    public function update(Request $request)
    {
        // Validasi data
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'image' => 'nullable|file|mimes:jpg,png,jpeg,gif|max:500',
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
            if ($user->getFirstMedia('avatars')) {
                $user->getFirstMedia('avatars')->delete();
            }
    
            // Simpan gambar baru
            $image = $request->file('image');
            $imagePath = $image->store('avatars', 'public');
            $user->addMedia(storage_path('app/public/' . $imagePath))->toMediaCollection('avatars');
        }
    
        // Tampilkan notifikasi dan kembalikan ke halaman sebelumnya
        session()->flash('success', 'Profile Updated!');
        return back();
    }
    


    public function updatePassword(Request $request) {
        $request->validate([
            'current_password'  => ['required', 'max:255', new MatchCurrentPassword()],
            'password' => 'required|min:8|max:255|confirmed'
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);

        toast('Password Updated!', 'success');

        return back();
    }
}
