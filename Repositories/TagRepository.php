<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;
use DateTimeImmutable;

class TagRepository
{
    private array $cache = [];

    public function __construct(private PDO $pdo) {}

  
    public function getPopularTags(int $limit = 50, string $period = 'all'): array
    {
        $cacheKey = "popular_$limit_$period";
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $dateFilter = match ($period) {
            'day' => "AND p.created_at >= NOW() - INTERVAL 1 DAY",
            'week' => "AND p.created_at >= NOW() - INTERVAL 7 DAY",
            'month' => "AND p.created_at >= NOW() - INTERVAL 1 MONTH",
            default => "",
        };

        $stmt = $this->pdo->prepare(
            "SELECT t.name, COUNT(*) AS usage_count
             FROM tags t
             JOIN photo_tags pt ON pt.tag_id = t.id
             JOIN photos p ON p.id = pt.photo_id
             WHERE 1=1 $dateFilter
             GROUP BY t.id
             ORDER BY usage_count DESC
             LIMIT ?"
        );
        $stmt->execute([$limit]);

        return $this->cache[$cacheKey] = $stmt->fetchAll();
    }

  
    public function searchTags(string $query, int $limit = 20): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT t.name, COUNT(pt.photo_id) AS usage_count
             FROM tags t
             LEFT JOIN photo_tags pt ON pt.tag_id = t.id
             WHERE t.name LIKE ?
             GROUP BY t.id
             ORDER BY usage_count DESC
             LIMIT ?"
        );

        $stmt->execute([strtolower($query) . '%', $limit]);
        return $stmt->fetchAll();
    }

  
    public function getPhotosByTag(
        string $tagName,
        int $page = 1,
        int $perPage = 30
    ): array {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->pdo->prepare(
            "SELECT SQL_CALC_FOUND_ROWS p.*, u.username
             FROM photos p
             JOIN photo_tags pt ON pt.photo_id = p.id
             JOIN tags t ON t.id = pt.tag_id
             JOIN users u ON u.id = p.user_id
             WHERE t.name = ?
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([$tagName, $perPage, $offset]);

        $photos = $stmt->fetchAll();
        $total = (int) $this->pdo->query("SELECT FOUND_ROWS()")->fetchColumn();

        return [
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'data' => $photos,
        ];
    }

    public function getTagStats(string $tagName): array
    {
        $stats = [];

        $stats['total'] = (int) $this->pdo
            ->prepare(
                "SELECT COUNT(*) FROM photo_tags pt
                 JOIN tags t ON t.id = pt.tag_id
                 WHERE t.name = ?"
            )
            ->execute([$tagName]);

        $stats['histogram'] = $this->pdo->query(
            "SELECT DATE_FORMAT(p.created_at, '%Y-%m') AS month, COUNT(*) total
             FROM photos p
             JOIN photo_tags pt ON pt.photo_id = p.id
             JOIN tags t ON t.id = pt.tag_id
             WHERE t.name = '$tagName'
             GROUP BY month"
        )->fetchAll();

        return $stats;
    }

    public function mergeTags(string $fromTag, string $toTag): bool
    {
        $this->pdo->beginTransaction();

        try {
            $this->pdo->exec(
                "UPDATE IGNORE photo_tags
                 SET tag_id = (SELECT id FROM tags WHERE name = '$toTag')
                 WHERE tag_id = (SELECT id FROM tags WHERE name = '$fromTag')"
            );

            $this->pdo->prepare(
                "INSERT INTO tag_merge_log (from_tag, to_tag, merged_at)
                 VALUES (?, ?, ?)"
            )->execute([
                $fromTag,
                $toTag,
                (new DateTimeImmutable())->format('Y-m-d H:i:s')
            ]);

            $this->pdo->prepare(
                "DELETE FROM tags WHERE name = ?"
            )->execute([$fromTag]);

            return $this->pdo->commit();
        } catch (\Throwable) {
            $this->pdo->rollBack();
            return false;
        }
    }
}
