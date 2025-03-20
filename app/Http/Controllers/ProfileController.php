<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    protected $fillable = ['name', 'email', 'password', 'profile_photo_url'];

    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
{
    $user = Auth::user();

    // Log incoming request data
    Log::info('Update request data:', $request->all());

    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255'],
        'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
    ]);

    $user->name = $request->name;
    $user->email = $request->email;

    // Handle password update if provided
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    // Handle profile photo upload
    if ($request->hasFile('profile_photo')) {
        $path = $request->file('profile_photo')->store('profile_photos', 'public');
        $user->profile_photo_url = $path;

        // Log the uploaded file path
        Log::info('Profile photo uploaded to: ' . $path);
    } else {
        Log::info('No profile photo uploaded.');
    }

    $user->save();

    return redirect()->route('profile.edit')->with('status', 'Profile updated successfully.');
}


    public function destroy(Request $request)
    {
        $user = Auth::user();
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
