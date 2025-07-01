<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Traits\BrevoEmailTrait;
use App\Models\TwoFactorAuthenticationCode;


class AuthenticatedSessionController extends Controller
{
    use BrevoEmailTrait;

    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->ensureIsNotRateLimited();
        $isAuthenticated = Auth::validate($request->only('email', 'password'));
        if (!$isAuthenticated) {
            RateLimiter::hit($request->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
        $authUser = User::whereEmail($request->email)->first();

        if ($authUser->userSetting && $authUser->userSetting->two_factor_authentication) {
            $this->generateAuthCodeAndSendToUser($authUser);
            return redirect()->route('show-verify-code-page', ['email' => $authUser['email']]);
        } else {
            Auth::login($authUser);
            return redirect()->intended(route('dashboard', absolute: false));
        }

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Method to logout the user from all devices
     */
    public function showVerifyCodePage($email)
    {
        $user = User::whereEmail($email)->first();
        $twoFaCode = $remainingTime = null;
        if ($user) {
            $twoFaCode = TwoFactorAuthenticationCode::whereUserId($user['id'])->first();
            $remainingTime = $twoFaCode->remaining_time;
        }
        return Inertia::render('auth/VerifyEmail', [
            'email' => $email,
            'remainingTime' => $remainingTime,
        ]);
    }

    /**
     * Method to verify code
     */
    public function verifyCode(Request $request)
    {
        $authUser = User::whereEmail($request->email)->first();
        $code = $request->input('code');
        $isCodeCorrect = TwoFactorAuthenticationCode::whereUserId($authUser['id'])->whereCode($code)->exists();
        if ($isCodeCorrect) {
            Auth::login($authUser);
            TwoFactorAuthenticationCode::whereUserId($authUser['id'])->whereCode($code)->delete();
            return response()->json([
                'message' => 'Logged in successfully',
                'status' => true,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Incorrect code',
                'status' => false,
            ], 422);
        }
    }


    /**
     * Method to resend the code
     */
    public function resendCode(Request $request)
    {
        $authUser = User::whereEmail($request->email)->first();
        $this->generateAuthCodeAndSendToUser($authUser);
        return response()->json([
            'message' => 'Code sent successfully',
            'status' => true,
        ], 200);
    }
}
