<?php

use App\Enums\FormStatus;
use App\Enums\SubmissionStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('form_id')->constrained('forms')->onDelete('cascade');
            $table->enum('status', [
                SubmissionStatusEnum::DRAFT,
                SubmissionStatusEnum::SUBMITTED,
                SubmissionStatusEnum::REJECTED,
                SubmissionStatusEnum::RESUBMITTED,
            ])->default(SubmissionStatusEnum::DRAFT);    //for managing the status of form

            $table->enum('approval_status', [
                null,
                FormStatus::FORM_SUBMITTED,
                FormStatus::FORM_APPROVED_BY_REVIEWER,
                FormStatus::FORM_REJECTED_BY_REVIEWER,
                FormStatus::FORM_APPROVED_BY_DIRECTOR,
                FormStatus::FORM_REJECTED_BY_DIRECTOR,

            ])->default(null);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
