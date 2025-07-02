<?php

namespace App\Jobs\Form;

use App\Enums\FormStatus;
use App\Events\FormApprovedByDirector;
use App\Events\FormApprovedByReviewer;
use App\Events\FormRejectedByDirector;
use App\Events\FormRejectedByReviewer;
use App\Events\FormSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class HandleFormStatusNotifications implements ShouldQueue
{
    use Queueable;

    public $formStatus;
    public $authUser;
    public $director;
    public $reviewer;
    public $data;

    /**
     * Create a new job instance.
     */
    public function __construct($formStatus, $authUser, $data, $director = null, $reviewer = null)
    {
        $this->formStatus = $formStatus;
        $this->authUser = $authUser;
        $this->director = $director;
        $this->reviewer = $reviewer;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            switch ($this->formStatus) {
                case FormStatus::FORM_SUBMITTED:
                    // code for handling form submission notification
                    event(new FormSubmitted($this->authUser, $this->reviewer, $this->data));
                    break;

                case FormStatus::FORM_APPROVED_BY_REVIEWER:
                    // code for handling form approved by reviewer notification
                    event(new FormApprovedByReviewer($this->authUser, $this->reviewer, $this->director, $this->data));
                    break;

                case FormStatus::FORM_REJECTED_BY_REVIEWER:
                    // code for handling form rejected by reviewer notification
                    event(new FormRejectedByReviewer($this->authUser, $this->reviewer, $this->data));
                    break;

                case FormStatus::FORM_APPROVED_BY_DIRECTOR:
                    // code for handling form approved by director notification
                    event(new FormApprovedByDirector($this->authUser, $this->director, $this->data));
                    break;

                case FormStatus::FORM_REJECTED_BY_DIRECTOR:
                    // code for handling form rejected by director notification
                    event(new FormRejectedByDirector($this->authUser, $this->reviewer, $this->director, $this->data));
                    break;

                default:
                    # code...
                    break;
            }

        } catch (\Throwable $exception) {
            Log::error('Something went wrong when dispatching job');
        }
    }
}
