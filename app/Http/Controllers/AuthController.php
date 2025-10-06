<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if (!$user->domain) {
                return redirect()->route('cms.domain.setup');
            }
            return redirect()->route('cms.home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,name',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users,phone|regex:/^\+62/',
            'password' => 'required|min:8|confirmed',
        ], [
            'phone.regex' => 'Nomor telepon harus diawali dengan +62',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Create domain entry with slug matching username
        Domain::create([
            'user_id' => $user->id,
            'slug' => $request->username,
            'title' => null,
        ]);

        Auth::login($user);
        // Redirect to builder index page
        return redirect()->route('cms.builder.index');
    }

    public function showProfileEditForm()
    {
        $user = Auth::user();
        return view('auth.profile-edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        // Validate basic profile information
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|regex:/^\+62/|unique:users,phone,' . $user->id,
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed|required_with:current_password',
        ], [
            'phone.regex' => 'Nomor telepon harus diawali dengan +62',
            'profile_photo.image' => 'File harus berupa gambar',
            'profile_photo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'profile_photo.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Check if current password is correct when changing password
        if ($request->password && !Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini tidak sesuai'])->withInput();
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle profile photo upload
        $profilePhotoPath = $user->profile_photo; // Keep existing photo if no new one is uploaded
        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->profile_photo) {
                Storage::delete($user->profile_photo);
            }
            
            // Store new profile photo
            $profilePhotoPath = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        // Prepare data for update
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'profile_photo' => $profilePhotoPath,
        ];

        // Update password if provided
        if ($request->password) {
            $userData['password'] = Hash::make($request->password);
        }

        // Update user profile
        $user->update($userData);

        return redirect()->route('cms.home')->with('status', 'Profile updated successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}