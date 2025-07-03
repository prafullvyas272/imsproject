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
class UserController extends Controller
{
    use ApiResponseTrait, RoleTrait, DepartmentTrait;

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
        $data = [
            'password' => Hash::make(Str::random(8))
        ];
        $userData = array_merge($data, $request->userData());
        User::create($userData);
        $users = $this->getAllUsers();
        return $this->successResponse($users, 'User created successfully.', 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->userData());
        $users = $this->getAllUsers();
        return $this->successResponse($users, 'User updated successfully.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->successResponse([], 'User deleted successfully.', 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function getAllUsers()
    {
        return User::all();
    }
}
