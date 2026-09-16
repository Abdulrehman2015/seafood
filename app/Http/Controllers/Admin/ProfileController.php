<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the admin profile edit view.
     */
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the admin's personal information, avatar, and password.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'avatar'           => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'remove_avatar'    => ['nullable', 'boolean'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password'         => ['nullable', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'The provided current password does not match our records.',
            'password.confirmed'                => 'The new password confirmation does not match.',
            'avatar.max'                        => 'The profile picture must not exceed 2MB in size.',
        ]);

        $user->name = trim($validated['name']);
        $user->email = strtolower(trim($validated['email']));

        // Handle Avatar Removal
        if ($request->boolean('remove_avatar')) {
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                @unlink(public_path($user->avatar));
            }
            $user->avatar = null;
        }

        // Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            if ($avatarFile->isValid()) {
                $uploadDir = public_path('uploads/avatars');
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0755, true);
                }

                // Remove existing avatar file if custom
                if ($user->avatar && file_exists(public_path($user->avatar))) {
                    @unlink(public_path($user->avatar));
                }

                $filename = 'admin_' . $user->id . '_' . time() . '.' . $avatarFile->getClientOriginalExtension();
                $avatarFile->move($uploadDir, $filename);
                $user->avatar = 'uploads/avatars/' . $filename;
            }
        }

        // Handle Password Change
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully.');
    }
}
