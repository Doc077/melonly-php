<?php

namespace Melonly\Authentication;

class User {
    public function logged(): bool {
        return Auth::logged();
    }
}
