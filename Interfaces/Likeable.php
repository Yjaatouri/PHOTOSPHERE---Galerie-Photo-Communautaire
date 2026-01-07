<?php
declare(strict_types=1);

namespace App\Interfaces;


interface Likeable
{
    /**
     *
     * @param int $userId
     * @return bool
     */
    public function addLike(int $userId): bool;

    /**
     *
     * @param int $userId
     * @return bool
     */
    public function removeLike(int $userId): bool;

    /**
     *
     * @param int $userId
     * @return bool
     */
    public function isLikedBy(int $userId): bool;

    /**
     *
     * @return int
     */
    public function getLikeCount(): int;

    /**
     *
     * @return int[]
     */
    public function getLikedBy(): array;
}
