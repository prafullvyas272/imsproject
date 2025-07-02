<?php

namespace App\Console\Commands;

use App\Jobs\Form\HandleFormStatusNotifications;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestingFormEmailTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:testing-email-templates {--status=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('TestingFormEmailTemplates command started....');

            // Example users and form data for testing
            $authUser = \App\Models\User::find(1);     // user with id 1
            $reviewer = \App\Models\User::find(2);     // user with id 2
            $director = \App\Models\User::find(3);     // user with id 3
            $data = [
                'first_name' => $authUser['name'],
                'email' => $authUser['email'],
                'app_url' => config('app.url') . '/login',
            ];

            // Get form status from command option or default
            $formStatus = (int) $this->option('status') ?? \App\Enums\FormStatus::FORM_SUBMITTED;

            dispatch(new HandleFormStatusNotifications(
                $formStatus,
                $authUser,
                $data,
                $director,
                $reviewer
            ));

            $this->info('TestingFormEmailTemplates command End....');

        } catch (\Throwable $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            Log::error('TestingFormEmailTemplates error: ' . $e->getMessage(), [
                'exception' => $e
            ]);
        }
    }
}
