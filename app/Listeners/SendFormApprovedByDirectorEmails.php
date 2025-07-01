<?php

namespace App\Listeners;

use App\Events\FormApprovedByDirector;
use App\Traits\Traits\FormStatusTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Enums\BrevoTemplateEnum;

class SendFormApprovedByDirectorEmails
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
    public function handle(FormApprovedByDirector $event): void
    {
        try {
            // send mail to the user who submitted
            $this->sendFormStatusNotificationEmail($event->authUser, BrevoTemplateEnum::FORM_APPROVED_BY_DIRECTOR, $event->data);
        } catch (\Throwable $exception) {
            \Log::error('Error sending form approved by director email: ' . $exception->getMessage(), [
                'exception' => $exception
            ]);
        }
    }
}
