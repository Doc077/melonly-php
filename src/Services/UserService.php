<?php

namespace App\Services;

use App\Models\User;

class UserService {
    public function show(int $id): User {
        return User::find($id);
    }
}
