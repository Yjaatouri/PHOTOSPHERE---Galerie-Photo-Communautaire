<?php
namespace App\Entities;

class ProUser extends User
{
    public function getRole(): string
    {
        return "ProUser";
    }
}
