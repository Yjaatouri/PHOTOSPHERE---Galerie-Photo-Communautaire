<?php
declare(strict_types=1);

namespace App\Traits;

use DateTimeImmutable;
use DateTimeInterface;


trait TimestampableTrait
{
    protected DateTimeInterface $createdAt;
    protected DateTimeInterface $updatedAt;

    protected function initializeTimestamps(): void
    {
        $now = new DateTimeImmutable();
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    protected function updateTimestamps(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getCreatedAt(?string $format = null): DateTimeInterface|string
    {
        return $format
            ? $this->createdAt->format($format)
            : $this->createdAt;
    }

    public function getUpdatedAt(?string $format = null): DateTimeInterface|string
    {
        return $format
            ? $this->updatedAt->format($format)
            : $this->updatedAt;
    }
}
