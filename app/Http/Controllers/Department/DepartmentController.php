<?php

namespace App\Http\Controllers\Department;

use App\Enums\UserActivityEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Department\DepartmentRequest;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Models\Department;
use App\Traits\ApiResponseTrait;
use App\Traits\UserActivityTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DepartmentController extends Controller
{
    use ApiResponseTrait, UserActivityTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::all();
        return Inertia::render('departments/ListDepartments', ['departments' => $departments]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {
        try {
            $response = DB::transaction(function () use ($request) {
                $department = Department::create(['name' => $request->input('name')]);
                $departments = $this->getAllDepartments();
                $this->updateUserActivity($department, UserActivityEnum::CREATED);
                return $this->successResponse($departments, 'Department created successfully.', 201);
            });
            return $response;

        } catch(\Throwable $exception) {
            return $this->errorResponse('Something went wrong when creating Department.', $exception, 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        try {
            $response = DB::transaction(function () use ($request, $department) {
                $department->update(['name' => $request->input('name')]);
                $departments = $this->getAllDepartments();
                $this->updateUserActivity($department, UserActivityEnum::UPDATED);
                return $this->successResponse($departments, 'Department updated successfully.', 201);
            });
            return $response;
        } catch(\Throwable $exception) {
            return $this->errorResponse('Something went wrong when updating Department.', $exception, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        try {
            $response = DB::transaction(function () use ($department) {
                $department->delete();
                $this->updateUserActivity($department, UserActivityEnum::DELETED);
                return $this->successResponse([], 'Department deleted successfully.', 201);
            });
            return $response;
        } catch(\Throwable $exception) {
            return $this->errorResponse('Something went wrong when deleting Department.', $exception, 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function getAllDepartments()
    {
        return Department::all();
    }
}
