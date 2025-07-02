<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleRequest;
use App\Models\Role;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    use ApiResponseTrait;

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
        Role::create(['name' => $request->input('name')]);
        $roles = $this->getAllRoles();
        return $this->successResponse($roles, 'Role created successfully.', 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role)
    {
        $role->update(['name' => $request->input('name')]);
        $roles = $this->getAllRoles();
        return $this->successResponse($roles, 'Role updated successfully.', 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return $this->successResponse([], 'Role deleted successfully.', 201);
    }

    /**
     * Display a listing of the resource.
     */
    public function getAllRoles()
    {
        return Role::all();
    }
}
