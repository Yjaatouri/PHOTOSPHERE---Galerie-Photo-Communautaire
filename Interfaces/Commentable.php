<?php
declare(strict_types=1);

namespace App\Interfaces;

interface Commentable
{
    /**
     *
     * @param string $content
     * @param int $userId
     * @return int Comment ID
     */
    public function addComment(string $content, int $userId): int;

    /**
     *
     * @param int $commentId
     * @return bool
     */
    public function removeComment(int $commentId): bool;

    /**
     *
     * @return array
     */
    public function getComments(): array;

    /**
     *
     * @return int
     */
    public function getCommentCount(): int;
}
