<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;
use DateTimeImmutable;
use RuntimeException;

class AlbumRepository
{
    public function __construct(private PDO $pdo) {}


    public function createAlbum(
        int $userId,
        string $title,
        string $description,
        bool $isPrivate
    ): int {
        $stmt = $this->pdo->prepare(
            "SELECT 1 FROM albums WHERE user_id = ? AND title = ?"
        );
        $stmt->execute([$userId, $title]);
        if ($stmt->fetch()) {
            throw new RuntimeException('Album title already exists');
        }

        if ($isPrivate && !$this->isProUser($userId)) {
            throw new RuntimeException('Private albums require Pro account');
        }

        $now = new DateTimeImmutable();

        $stmt = $this->pdo->prepare(
            "INSERT INTO albums (user_id, title, description, is_private, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $userId,
            $title,
            $description,
            (int) $isPrivate,
            $now->format('Y-m-d H:i:s'),
            $now->format('Y-m-d H:i:s'),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

 
    public function addPhotoToAlbum(int $albumId, int $photoId, int $userId): bool
    {
        $this->assertAlbumOwner($albumId, $userId);
        $this->assertPhotoOwner($photoId, $userId);

       
        $count = $this->pdo->prepare(
            "SELECT COUNT(*) FROM album_photos WHERE album_id = ?"
        );
        $count->execute([$albumId]);
        if ((int) $count->fetchColumn() >= 100) {
            throw new RuntimeException('Album photo limit reached');
        }

       
        $stmt = $this->pdo->prepare(
            "INSERT IGNORE INTO album_photos (album_id, photo_id) VALUES (?, ?)"
        );

        return $stmt->execute([$albumId, $photoId]);
    }

   
    public function removePhotoFromAlbum(
        int $albumId,
        int $photoId,
        int $userId
    ): bool {
        $this->assertAlbumOwner($albumId, $userId);

        $stmt = $this->pdo->prepare(
            "DELETE FROM album_photos WHERE album_id = ? AND photo_id = ?"
        );

        return $stmt->execute([$albumId, $photoId]);
    }

    public function getAlbumWithPhotos(
        int $albumId,
        int $userId,
        int $page = 1,
        int $perPage = 20
    ): ?array {
        $offset = ($page - 1) * $perPage;

  
        $album = $this->pdo->prepare(
            "SELECT a.*, u.username
             FROM albums a
             JOIN users u ON u.id = a.user_id
             WHERE a.id = ?
             AND (a.is_private = 0 OR a.user_id = ?)"
        );
        $album->execute([$albumId, $userId]);

        $albumData = $album->fetch();
        if (!$albumData) {
            return null;
        }

        $photos = $this->pdo->prepare(
            "SELECT p.*
             FROM photos p
             JOIN album_photos ap ON ap.photo_id = p.id
             WHERE ap.album_id = ?
             LIMIT ? OFFSET ?"
        );
        $photos->execute([$albumId, $perPage, $offset]);

        $albumData['photos'] = $photos->fetchAll();

        return $albumData;
    }

 
    public function getUserAlbums(int $userId, bool $includePrivate = true): array
    {
        $sql = "
            SELECT a.*,
                   COUNT(ap.photo_id) AS photo_count,
                   MAX(p.created_at) AS last_photo
            FROM albums a
            LEFT JOIN album_photos ap ON ap.album_id = a.id
            LEFT JOIN photos p ON p.id = ap.photo_id
            WHERE a.user_id = ?
        ";

        if (!$includePrivate) {
            $sql .= " AND a.is_private = 0";
        }

        $sql .= " GROUP BY a.id ORDER BY a.updated_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    public function updateAlbum(int $albumId, int $userId, array $data): bool
    {
        $this->assertAlbumOwner($albumId, $userId);

        if (isset($data['isPrivate']) && $data['isPrivate'] === true) {
            if (!$this->isProUser($userId)) {
                throw new RuntimeException('Private albums require Pro account');
            }
        }

        $fields = [];
        $values = [];

        foreach (['title', 'description', 'isPrivate'] as $key) {
            if (array_key_exists($key, $data)) {
                $fields[] = match ($key) {
                    'isPrivate' => 'is_private = ?',
                    default => "$key = ?",
                };
                $values[] = $data[$key];
            }
        }

        if (!$fields) {
            return false;
        }

        $fields[] = 'updated_at = NOW()';
        $values[] = $albumId;

        $sql = "UPDATE albums SET " . implode(', ', $fields) . " WHERE id = ?";
        return $this->pdo->prepare($sql)->execute($values);
    }

   
    public function deleteAlbum(int $albumId, int $userId): bool
    {
        $this->assertAlbumOwner($albumId, $userId);

        $stmt = $this->pdo->prepare("DELETE FROM albums WHERE id = ?");
        return $stmt->execute([$albumId]);
    }

    
    private function assertAlbumOwner(int $albumId, int $userId): void
    {
        $stmt = $this->pdo->prepare(
            "SELECT 1 FROM albums WHERE id = ? AND user_id = ?"
        );
        $stmt->execute([$albumId, $userId]);

        if (!$stmt->fetch()) {
            throw new RuntimeException('Unauthorized album access');
        }
    }

    private function assertPhotoOwner(int $photoId, int $userId): void
    {
        $stmt = $this->pdo->prepare(
            "SELECT 1 FROM photos WHERE id = ? AND user_id = ?"
        );
        $stmt->execute([$photoId, $userId]);

        if (!$stmt->fetch()) {
            throw new RuntimeException('Photo ownership mismatch');
        }
    }

    private function isProUser(int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT role FROM users WHERE id = ?"
        );
        $stmt->execute([$userId]);

        return in_array($stmt->fetchColumn(), ['pro', 'moderator', 'admin'], true);
    }
}
