<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TwoFactorAuthenticationCode extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $appends = ['is_expired', 'remaining_time'];

    /**
     * Method to get is expired attribute
     */
    public function getIsExpiredAttribute()
    {
        $createdAt = Carbon::parse($this->created_at);
        $timeDifference = $createdAt->diffInMinutes(Carbon::now());
        $isExpired = $timeDifference > config('app.max_otp_timeout');
        return $isExpired;
    }

    /**
     * Method to get is expired attribute
     */
    public function getRemainingTimeAttribute()
    {
        $createdAt = Carbon::parse($this->created_at);
        $timeDifference = $createdAt->diffInSeconds(Carbon::now());
        return 300 - round($timeDifference);
    }
}
