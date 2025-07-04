<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use App\Traits\DepartmentTrait;
use App\Traits\RoleTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Enums\RoleEnum;
use App\Enums\UserActivityEnum;
use App\Jobs\SendUserAccountCreatedEmailJob;
use App\Traits\UserActivityTrait;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use ApiResponseTrait, RoleTrait, DepartmentTrait, UserActivityTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->getAllUsers();
        $roles = $this->getAllRoles();
        $departments = $this->getAllDepartments();
        // $roles = RoleEnum::toArray();

        return Inertia::render('users/ListUsers', [
            'users' => $users,
            'roles' => $roles,
            'departments' => $departments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $response = DB::transaction(function () use ($request) {
                $password = Str::random(8);
                $data = [
                    'password' => Hash::make($password)
                ];
                $userData = array_merge($data, $request->userData());

                $user = User::create($userData);
                $this->updateUserActivity($user, UserActivityEnum::CREATED);
                dispatch(new SendUserAccountCreatedEmailJob($user, $password));

                $users = $this->getAllUsers();
                return $this->successResponse($users, 'User created successfully.', 201);
            });
            return $response;
        } catch (\Throwable $exception) {
            return $this->errorResponse('Something went wrong when creating User.', $exception, 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $response = DB::transaction(function () use ($request, $user) {

                $user->update($request->userData());
                $this->updateUserActivity($user, UserActivityEnum::UPDATED);

                $users = $this->getAllUsers();
                return $this->successResponse($users, 'User updated successfully.', 200);
            });
            return $response;
        } catch (\Throwable $exception) {
            return $this->errorResponse('Something went wrong when updating User.', $exception, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $response = DB::transaction(function () use ($user) {
                $user->delete();
                $this->updateUserActivity($user, UserActivityEnum::DELETED);

                return $this->successResponse([], 'User deleted successfully.', 200);
            });
            return $response;
        } catch (\Throwable $exception) {
            return $this->errorResponse('Something went wrong when deleting User.', $exception, 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function getAllUsers()
    {
        return User::all();
    }
}
