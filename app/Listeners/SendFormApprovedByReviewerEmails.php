<?php

namespace App\Listeners;

use App\Events\FormApprovedByDirector;
use App\Events\FormApprovedByReviewer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Enums\BrevoTemplateEnum;
use App\Traits\BrevoEmailTrait;
use App\Traits\Traits\FormStatusTrait;

class SendFormApprovedByReviewerEmails
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
    public function handle(FormApprovedByReviewer $event): void
    {
        try {
            // send mail to the user who submitted
            $this->sendFormStatusNotificationEmail($event->authUser, BrevoTemplateEnum::FORM_APPROVED_BY_REVIEWER, $event->data);

            // send mail to the director
            $this->sendFormStatusNotificationEmail($event->director, BrevoTemplateEnum::FORM_APPROVED_BY_REVIEWER, $event->data);
        } catch (\Throwable $exception) {
            \Log::error('Error sending form approved by reviewer emails: ' . $exception->getMessage(), [
                'exception' => $exception
            ]);
        }
    }
}
