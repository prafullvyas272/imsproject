<?php

namespace App\Traits;

use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;

trait UserActivityTrait
{
     /**
     * Method to update user activity
     */
    public function updateUserActivity($module, $activity)
    {
        $moduleType = class_basename($module);
        $authUser = Auth::user();
        $title = $moduleType . ' "' .  $module['name'] . '" ' . $activity . ' by ' .  $authUser['name'];

        UserActivity::create([
            'title' => $title,
            'user_id' => $authUser['id'],
        ]);
    }
}
