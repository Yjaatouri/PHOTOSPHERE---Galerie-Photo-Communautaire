<?php
namespace App\Entities;

class Moderator extends User
{
    public function getRole(): string
    {
        return "Moderator";
    }
}
