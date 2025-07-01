<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\BrevoEmailTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\UserSetting;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    use BrevoEmailTrait;

    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);


        DB::beginTransaction();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        UserSetting::create([
            'user_id' => $user->id,
        ]);

        DB::commit();

        $this->sendRegistrationEmail($user, $request->password);

        event(new Registered($user));

        return to_route('login');
    }

    public function enableTwoFA(Request $request)
    {
        $isTwoFaEnabled = UserSetting::whereUserId($request->input('id'))->first()['two_factor_authentication'];
        UserSetting::whereUserId($request->input('id'))->update([
            'two_factor_authentication' => !$isTwoFaEnabled,
        ]);

        return response()->json([
            'message' => 'Two FA setting updated.',
            'status' => true,
            'current_status' => !$isTwoFaEnabled
        ]);
    }
}
