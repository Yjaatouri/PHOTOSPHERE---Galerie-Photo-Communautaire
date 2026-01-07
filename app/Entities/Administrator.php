<?php
namespace App\Entities;

class Administrator extends User
{
    public function getRole(): string
    {
        return "Administrator";
    }
}
