<?php
namespace App\Entities;

abstract class User
{
    protected int $id;
    protected string $name;
    protected string $email;

    public function __construct(int $id, string $name, string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    abstract public function getRole(): string;

    public function getInfo(): string
    {
        return "{$this->name} ({$this->email}) - {$this->getRole()}";
    }
}
