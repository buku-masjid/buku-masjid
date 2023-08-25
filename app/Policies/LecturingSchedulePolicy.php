<?php

namespace App\Policies;

use App\User;
use App\Models\LecturingSchedule;
use Illuminate\Auth\Access\HandlesAuthorization;

class LecturingSchedulePolicy
{
    use HandlesAuthorization;

    public function view(User $user, LecturingSchedule $lecturingSchedule)
    {
        // Update $user authorization to view $lecturingSchedule here.
        return true;
    }

    public function create(User $user, LecturingSchedule $lecturingSchedule)
    {
        // Update $user authorization to create $lecturingSchedule here.
        return true;
    }

    public function update(User $user, LecturingSchedule $lecturingSchedule)
    {
        // Update $user authorization to update $lecturingSchedule here.
        return true;
    }

    public function delete(User $user, LecturingSchedule $lecturingSchedule)
    {
        // Update $user authorization to delete $lecturingSchedule here.
        return true;
    }
}
