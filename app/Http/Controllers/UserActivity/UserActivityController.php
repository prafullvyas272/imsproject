<?php

namespace App\Http\Controllers\UserActivity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;

class UserActivityController extends Controller
{
    /**
     * Method to show user activity
     */
    public function showUserActivity(User $user)
    {
        $userActivities = $user->userActivities()->latest()->get()->toArray();
        return Inertia::render('user-activity/UserActivity', [
            'userActivities' => $userActivities
        ]);
    }
}
