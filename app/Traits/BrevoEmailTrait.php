<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Enums\BrevoTemplateEnum;
use Illuminate\Support\Str;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\DB;

trait BrevoEmailTrait
{
    public $emailUrl = 'https://api.brevo.com/v3/smtp/email';

    /**
     * Method to get the API headers for the Brevo API
     */
    public function getApiHeaders()
    {
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
                    'first_name' => $user['first_name'],
                    'email' => $user['email'],
                    'password' => $password,
                    'app_name' => config('app.name'),
                    'app_url' => config('app.url') . '/login',
                ],
            ]);

            return $response;
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
                    'first_name' => $user['first_name'],
                    'email' => $user['email'],
                    'password_reset_link' => $passwordResetLink,
                    'app_url' => config('app.url') . '/login',
                ],
            ]);

            return $response;
        } catch (\Throwable $exception) {
            Log::error('Something went wrong when sending account invite email ' . $exception);
        }
    }

    /**
     * Method to send a two factor auth email to the user
     */
    public function sendTwoFactorAuthEmail($user, $password)
    {
        try {
            $apiHeaders = $this->getApiHeaders();

            $templateId = BrevoTemplateEnum::TWO_FACTOR_AUTH_EMAIL;
            $code = Str::random(6);

            $response = Http::withHeaders($apiHeaders)->timeout(90)->post($this->emailUrl, [
                'to' => [
                    [
                        'email' => $user['email'],
                    ],
                ],
                'templateId' => $templateId,
                'params' => [
                    'first_name' => $user['first_name'],
                    'email' => $user['email'],
                    'password' => $password,
                    'code' => $code,
                    'app_url' => config('app.url') . '/login',
                ],
            ]);

            return $response;
        } catch (\Throwable $exception) {
            Log::error('Something went wrong when sending 2fa email ' . $exception);
        }
    }
}
