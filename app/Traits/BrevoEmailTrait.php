<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Enums\BrevoTemplateEnum;
use App\Enums\RoleEnum;
use Illuminate\Support\Str;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\DB;
use App\Models\TwoFactorAuthenticationCode;

trait BrevoEmailTrait
{
    protected string $emailUrl;

    public function __construct()
    {
        $this->emailUrl = config('app.brevo_email_url');
    }

    /**
     * Method to get the API headers for the Brevo API
     */
    public function getApiHeaders()
    {
        $this->emailUrl = config('app.brevo_email_url');
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'api-key' => env('BREVO_API_KEY'),
        ];
    }

    /**
     * Method to send a registration email to the user
     */
    public function sendRegistrationEmail($user, $password)
    {
        try {
            $apiHeaders = $this->getApiHeaders();

            $templateId = BrevoTemplateEnum::REGISTRATION_EMAIL;

            $response = Http::withHeaders($apiHeaders)->timeout(90)->post($this->emailUrl, [
                'to' => [
                    [
                        'email' => $user['email'],
                    ],
                ],
                'templateId' => $templateId,
                'params' => [
                    'first_name' => $user['name'],
                    'email' => $user['email'],
                    'password' => $password,
                    'app_name' => config('app.name'),
                    'app_url' => config('app.url') . '/login',
                ],
            ]);

            return $response->json();
        } catch (\Throwable $exception) {
            Log::error('Something went wrong when sending registration email ' . $exception);
        }
    }

    /**
     * Method to send a forgot password email to the user
     */
    public function sendForgotPasswordEmail($user)
    {
        try {
            $apiHeaders = $this->getApiHeaders();

            $templateId = BrevoTemplateEnum::FORGOT_PASSWORD_EMAIL;
            $token = Str::random(30);

            // The password_reset_tokens table does not have updated_at, so we disable timestamps for this model
            DB::table('password_reset_tokens')->insert([
                'email' => $user['email'],
                'token' => $token,
            ]);

            $passwordResetLink = config('app.url') . "/reset-password/" . $token . "?email=" . $user['email'];

            $response = Http::withHeaders($apiHeaders)->timeout(90)->post($this->emailUrl, [
                'to' => [
                    [
                        'email' => $user['email'],
                    ],
                ],
                'templateId' => $templateId,
                'params' => [
                    'first_name' => $user['name'],
                    'email' => $user['email'],
                    'password_reset_link' => $passwordResetLink,
                    'app_url' => config('app.url') . '/login',
                ],
            ]);

            return $response->json();

        } catch (\Throwable $exception) {
            Log::error('Something went wrong when sending account invite email ' . $exception);
        }
    }

    /**
     * Method to generate 2fa auth code and send it to user
     */
    public function generateAuthCodeAndSendToUser($authUser)
    {
        try {
            $apiHeaders = $this->getApiHeaders();
            DB::beginTransaction();

            TwoFactorAuthenticationCode::whereUserId($authUser['id'])->delete();
            $twoFactorAuthCode = TwoFactorAuthenticationCode::create([
                'code' => rand(100000, 999999),
                'user_id' => $authUser['id'],
            ]);

            DB::commit();

            $response = Http::withHeaders($apiHeaders)->post($this->emailUrl, [
                'to' => [
                    [
                        'email' => $authUser['email'],
                    ],
                ],
                'templateId' => BrevoTemplateEnum::TWO_FACTOR_AUTH_EMAIL,
                'params' => [
                    'name' => $authUser['name'],
                    'email' => $authUser['email'],
                    'code' => $twoFactorAuthCode['code'],
                    'app_url' => config('app.url'),
                ],
            ]);
            return $response->json();
        } catch (\Throwable $exception) {
            Log::error('Something went wrong when sending code' . $exception);
        }
    }

    /**
     * Method to send a user account created email to the user
     */
    public function sendAccountCreatedEmail($user, $password)
    {
        try {
            $apiHeaders = $this->getApiHeaders();

            $templateId = BrevoTemplateEnum::USER_ACCOUNT_CREATED_EMAIL;

            $response = Http::withHeaders($apiHeaders)->timeout(90)->post($this->emailUrl, [
                'to' => [
                    [
                        'email' => $user['email'],
                    ],
                ],
                'templateId' => $templateId,
                'params' => [
                    'first_name' => $user['name'],
                    'email' => $user['email'],
                    'password' => $password,
                    'role' => RoleEnum::toArray()[$user['role_id']],
                    'app_name' => config('app.name'),
                    'app_url' => config('app.url') . '/login',
                ],
            ]);

            return $response->json();
        } catch (\Throwable $exception) {
            Log::error('Something went wrong when sending registration email ' . $exception);
        }
    }
}
