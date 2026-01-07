<?php
require_once __DIR__ . '/app/Entities/User.php';

use App\Entities\User;


$user = new User(1, "Test", "test@mail.com");
