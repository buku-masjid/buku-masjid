<?php

namespace App\Policies;

use App\Models\LecturingSchedule;
use App\User;

class LecturingSchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, LecturingSchedule $lecturingSchedule): bool
    {
        return true;
    }

    public function create(User $user, LecturingSchedule $lecturingSchedule): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_CHAIRMAN, User::ROLE_SECRETARY]);
    }

    public function update(User $user, LecturingSchedule $lecturingSchedule): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_CHAIRMAN, User::ROLE_SECRETARY]);
    }

    public function delete(User $user, LecturingSchedule $lecturingSchedule): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_CHAIRMAN, User::ROLE_SECRETARY]);
    }
}
