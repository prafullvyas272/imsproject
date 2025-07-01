<?php

namespace App\Listeners;

use App\Events\FormRejectedByDirector;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Enums\BrevoTemplateEnum;
use App\Traits\Traits\FormStatusTrait;

class SendFormRejectedByDirectorEmails
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
    public function handle(FormRejectedByDirector $event): void
    {
        try {
            // send mail to the reviewer
            $this->sendFormStatusNotificationEmail($event->reviewer, BrevoTemplateEnum::FORM_REJECTED_BY_DIRECTOR, $event->data);
        } catch (\Throwable $exception) {
            \Log::error('Error sending form rejected by director email: ' . $exception->getMessage(), [
                'exception' => $exception
            ]);
        }
    }
}
