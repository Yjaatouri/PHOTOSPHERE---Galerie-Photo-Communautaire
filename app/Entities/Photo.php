<?php
declare(strict_types=1);

namespace App\Entities;

use App\Interfaces\{
    Taggable,
    Commentable,
    Likeable
};
use App\Traits\{
    TaggableTrait,
    TimestampableTrait
};

class Photo implements Taggable, Commentable, Likeable
{
    use TaggableTrait;
    use TimestampableTrait;

    private int $id;
    private int $userId;
    private string $path;
    private bool $isPublic;

    private int $likeCount = 0;
    private int $commentCount = 0;

    /**
     * @var array<int, array>
     */
    private array $comments = [];

    /**
     * @var int[]
     */
    private array $likedBy = [];

    public function __construct(
        int $id,
        int $userId,
        string $path,
        bool $isPublic = true
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->path = $path;
        $this->isPublic = $isPublic;

        $this->initializeTimestamps();
    }

    /* ---------- Commentable ---------- */

    public function addComment(string $content, int $userId): int
    {
        $commentId = count($this->comments) + 1;

        $this->comments[$commentId] = [
            'id' => $commentId,
            'userId' => $userId,
            'content' => $content,
        ];

        $this->commentCount++;
        $this->updateTimestamps();

        return $commentId;
    }

    public function removeComment(int $commentId): bool
    {
        if (!isset($this->comments[$commentId])) {
            return false;
        }

        unset($this->comments[$commentId]);
        $this->commentCount--;
        $this->updateTimestamps();

        return true;
    }

    public function getComments(): array
    {
        return array_values($this->comments);
    }

    public function getCommentCount(): int
    {
        return $this->commentCount;
    }

    /* ---------- Likeable ---------- */

    public function addLike(int $userId): bool
    {
        if ($this->isLikedBy($userId)) {
            return false;
        }

        $this->likedBy[] = $userId;
        $this->likeCount++;
        $this->updateTimestamps();

        return true;
    }

    public function removeLike(int $userId): bool
    {
        if (!$this->isLikedBy($userId)) {
            return false;
        }

        $this->likedBy = array_values(
            array_filter($this->likedBy, fn ($id) => $id !== $userId)
        );

        $this->likeCount--;
        $this->updateTimestamps();

        return true;
    }

    public function isLikedBy(int $userId): bool
    {
        return in_array($userId, $this->likedBy, true);
    }

    public function getLikeCount(): int
    {
        return $this->likeCount;
    }

    public function getLikedBy(): array
    {
        return $this->likedBy;
    }


    protected function loadTagsFromDatabase(): void
    {
           }
}
