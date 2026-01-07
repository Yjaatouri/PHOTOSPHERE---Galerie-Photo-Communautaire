<?php
namespace App\Entities;

class BasicUser extends User
{
    public function getRole(): string
    {
        return "BasicUser";
    }
}
