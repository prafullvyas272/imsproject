<?php

namespace App\Listeners;

use App\Events\FormRejectedByReviewer;
use App\Traits\Traits\FormStatusTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Enums\BrevoTemplateEnum;

class SendFormRejectionByReviewerEmails
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
    public function handle(FormRejectedByReviewer $event): void
    {
        try {
            // send mail to the user who submitted
            $this->sendFormStatusNotificationEmail($event->authUser, BrevoTemplateEnum::FORM_REJECTED_BY_REVIEWER, $event->data);
        } catch (\Throwable $exception) {
            \Log::error('Error sending form rejected by reviewer email: ' . $exception->getMessage(), [
                'exception' => $exception
            ]);
        }
    }
}
