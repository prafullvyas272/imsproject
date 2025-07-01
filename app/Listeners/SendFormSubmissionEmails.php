<?php

namespace App\Listeners;

use App\Enums\BrevoTemplateEnum;
use App\Events\FormSubmitted;
use App\Traits\Traits\FormStatusTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendFormSubmissionEmails
{
    use FormStatusTrait;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(FormSubmitted $event): void
    {
        try {
            // send mail to the user who submitted
            $this->sendFormStatusNotificationEmail($event->authUser, BrevoTemplateEnum::FORM_SUBMITTED, $event->data);

            // send mail to the reviewer
            $this->sendFormStatusNotificationEmail($event->reviewer, BrevoTemplateEnum::FORM_SUBMITTED, $event->data);
        } catch (\Throwable $exception) {
            Log::error('Error sending form submission emails: ' . $exception->getMessage(), [
                'exception' => $exception
            ]);
        }
    }
}
