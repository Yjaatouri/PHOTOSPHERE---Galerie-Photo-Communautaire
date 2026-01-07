<?php
require_once "app/Entities/User.php";
require_once "app/Entities/BasicUser.php";
require_once "app/Entities/ProUser.php";
require_once "app/Entities/Moderator.php";
require_once "app/Entities/Administrator.php";
require_once "app/Services/UserFactory.php";

use App\Services\UserFactory;

$users = [
    UserFactory::create('basic', 1, "Ali", "ali@mail.com"),
    UserFactory::create('pro', 2, "Sara", "sara@mail.com"),
    UserFactory::create('moderator', 3, "Yassine", "yas@mail.com"),
    UserFactory::create('admin', 4, "Admin", "admin@mail.com"),
];

foreach ($users as $user) {
    echo $user->getRole() . " → " . $user->getInfo() . PHP_EOL;
}
