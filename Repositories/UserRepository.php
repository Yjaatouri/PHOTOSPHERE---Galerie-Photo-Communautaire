<?php
namespace App\Repositories;

use App\Entities\User;
use App\Services\UserFactory;
use PDO;

class UserRepository implements RepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        $rows = $stmt->fetchAll();

        $users = [];
        foreach ($rows as $row) {
            $users[] = UserFactory::create(
                strtolower($row['role']),
                (int)$row['id'],
                $row['name'],
                $row['email']
            );
        }

        return $users;
    }

    public function find(int $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return UserFactory::create(
            strtolower($row['role']),
            (int)$row['id'],
            $row['name'],
            $row['email']
        );
    }

    public function save(object $entity): void
    {
        if (!$entity instanceof User) {
            throw new \InvalidArgumentException("Only User objects allowed");
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO users (id, name, email, role)
             VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
               name = VALUES(name),
               email = VALUES(email),
               role = VALUES(role)"
        );

        $stmt->execute([
            $entity->id,
            $entity->name,
            $entity->email,
            $entity->getRole()
        ]);
    }
}
