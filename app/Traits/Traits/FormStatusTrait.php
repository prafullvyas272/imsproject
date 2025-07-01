<?php

namespace App\Traits\Traits;

use App\Enums\BrevoTemplateEnum;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait FormStatusTrait
{
     /**
     * Method to get the API headers for the Brevo API
     */
    public function getApiHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'api-key' => config('app.brevo_api_key'),
        ];
    }

    /**
     * Method to send email to the user
     */
    public function sendFormStatusNotificationEmail($user, $templateId, $data)
    {
        try {
            $apiHeaders = $this->getApiHeaders();
            $timeout = 90;

            $response = Http::withHeaders($apiHeaders)->timeout($timeout)->post($this->emailUrl, [
                'to' => [
                    [
                        'email' => $user['email'],
                    ],
                ],
                'templateId' => $templateId,
                'params' => $data
            ]);

            return $response->json();
        } catch (\Throwable $exception) {
            Log::error('Something went wrong when sending registration email ' . $exception);
        }
    }


}
