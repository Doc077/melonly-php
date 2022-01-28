<?php

namespace App\Services;

use App\Models\User;

class UserService {
    public function getUserById(int $id): User {
        return User::find($id);
    }
}
