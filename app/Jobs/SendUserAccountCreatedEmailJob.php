<?php

namespace App\Jobs;

use App\Traits\ApiResponseTrait;
use App\Traits\BrevoEmailTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendUserAccountCreatedEmailJob implements ShouldQueue
{
    use Queueable, BrevoEmailTrait, ApiResponseTrait;

    public $user;
    public $password;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            Log::info('Send account creation Job started....');
            $response = $this->sendAccountCreatedEmail($this->user, $this->password);
            Log::info('Send account creation Job ended....');
            return $response;

        } catch (Throwable $exception) {
            $this->errorResponse('Something went wrong when processing sending account created email Job.', $exception, 500);

        }
    }
}
