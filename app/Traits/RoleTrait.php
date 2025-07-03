<?php

namespace App\Traits;

use App\Models\Role;

trait RoleTrait
{
    /**
     * Method to get all Roles
     */
    public function getAllRoles()
    {
        return Role::all();
    }


}
