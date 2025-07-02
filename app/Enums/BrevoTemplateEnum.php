<?php

namespace App\Enums;

enum BrevoTemplateEnum
{
    const REGISTRATION_EMAIL = 1;
    const FORGOT_PASSWORD_EMAIL = 2;
    const TWO_FACTOR_AUTH_EMAIL = 3;
    const FORM_SUBMITTED = 4;
    const FORM_APPROVED_BY_REVIEWER = 5;
    const FORM_REJECTED_BY_REVIEWER = 6;
    const FORM_APPROVED_BY_DIRECTOR = 7;
    const FORM_REJECTED_BY_DIRECTOR = 8;
}
