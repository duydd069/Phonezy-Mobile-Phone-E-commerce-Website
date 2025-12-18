<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function show()
    {
        return view('electro.auth.register');
    }

    /**
     * Handle registration submission.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password_hash' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Generate verification token
        $verificationToken = \Illuminate\Support\Str::random(64);
        $verificationExpires = now()->addHours(24);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => \Illuminate\Support\Facades\Hash::make($data['password_hash']),
            'role_id' => 2, // 2 = normal user
            'verification_token' => $verificationToken,
            'verification_expires_at' => $verificationExpires,
        ]);

        // Generate verification URL
        $verificationUrl = route('email.verify', ['token' => $verificationToken]);

        // Send verification email
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)
                ->send(new \App\Mail\VerificationEmail($verificationUrl, $user->name));
        } catch (\Exception $e) {
            // Log error but don't block registration
            \Illuminate\Support\Facades\Log::error('Failed to send verification email: ' . $e->getMessage());
        }

        // Redirect to verification-sent page instead of auto-login
        return redirect()->route('verification.sent')
            ->with('email', $user->email);
    }

}
