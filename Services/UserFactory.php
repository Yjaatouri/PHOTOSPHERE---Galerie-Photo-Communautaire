<?php
namespace App\Services;

use App\Entities\{
    BasicUser,
    ProUser,
    Moderator,
    Administrator,
    User
};

class UserFactory
{
    public static function create(
        string $type,
        int $id,
        string $name,
        string $email
    ): User {
        return match ($type) {
            'basic' => new BasicUser($id, $name, $email),
            'pro' => new ProUser($id, $name, $email),
            'moderator' => new Moderator($id, $name, $email),
            'admin' => new Administrator($id, $name, $email),
            default => throw new \Exception("Unknown user type")
        };
    }
}
