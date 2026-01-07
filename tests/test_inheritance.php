<?php
require_once "app/Entities/User.php";
require_once "app/Entities/BasicUser.php";
require_once "app/Entities/ProUser.php";
require_once "app/Entities/Moderator.php";
require_once "app/Entities/Administrator.php";

use App\Entities\{BasicUser, ProUser, Moderator, Administrator};

$users = [
    new BasicUser(1, "Ali", "ali@mail.com"),
    new ProUser(2, "Sara", "sara@mail.com"),
    new Moderator(3, "Yassine", "yas@mail.com"),
    new Administrator(4, "Admin", "admin@mail.com"),
];

foreach ($users as $user) {
    echo $user->getInfo() . PHP_EOL;
}
