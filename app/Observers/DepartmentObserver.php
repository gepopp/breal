<?php

namespace App\Observers;

use App\Models\Department;

class DepartmentObserver
{
    public function created(Department $department)
    {

        if(!is_null($department->department_id) && is_null($department->company))
        {
            $department->update(['company' => $department->main->company]);
        }
    }
}
