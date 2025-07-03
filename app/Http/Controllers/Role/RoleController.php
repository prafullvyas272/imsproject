<?php

namespace App\Http\Controllers\Role;

use App\Enums\UserActivityEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleRequest;
use App\Models\Role;
use App\Traits\ApiResponseTrait;
use App\Traits\UserActivityTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RoleController extends Controller
{
    use ApiResponseTrait, UserActivityTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return Inertia::render('roles/ListRoles', ['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        try {
            $response = DB::transaction(function () use ($request) {
                $role = Role::create(['name' => $request->input('name')]);
                $roles = $this->getAllRoles();
                $this->updateUserActivity($role, UserActivityEnum::CREATED);
                return $this->successResponse($roles, 'Role created successfully.', 201);
            });
            return $response;

        } catch(\Throwable $exception) {
            return $this->errorResponse('Something went wrong when creating Role.', $exception, 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role)
    {
        try {
            $response = DB::transaction(function () use ($request, $role) {
                $role->update(['name' => $request->input('name')]);
                $roles = $this->getAllRoles();
                $this->updateUserActivity($role, UserActivityEnum::UPDATED);
                return $this->successResponse($roles, 'Role updated successfully.', 201);
            });
            return $response;
        } catch(\Throwable $exception) {
            return $this->errorResponse('Something went wrong when updating Role.', $exception, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            $response = DB::transaction(function () use ($role) {
                $role->delete();
                $roles = $this->getAllRoles();
                $this->updateUserActivity($role, UserActivityEnum::DELETED);
                return $this->successResponse($roles, 'Role deleted successfully.', 201);
            });
            return $response;
        } catch(\Throwable $exception) {
            return $this->errorResponse('Something went wrong when deleting Role.', $exception, 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function getAllRoles()
    {
        return Role::all();
    }
}
