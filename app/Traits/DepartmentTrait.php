<?php

namespace App\Traits;
use App\Models\Department;

trait DepartmentTrait
{
    /**
     * Method to get all Departments
     */
    public function getAllDepartments()
    {
        return Department::all();
    }
}
