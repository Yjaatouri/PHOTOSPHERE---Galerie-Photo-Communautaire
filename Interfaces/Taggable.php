<?php
declare(strict_types=1);

namespace App\Interfaces;


interface Taggable
{
    /**
     *
     * @param string $tag
     */
    public function addTag(string $tag): void;

    /**
     *
     * @param string $tag
     */
    public function removeTag(string $tag): void;

    /**
     *
     * @return string[]
     */
    public function getTags(): array;

    /**
     
     *
     * @param string $tag
     * @return bool
     */
    public function hasTag(string $tag): bool;

    
    public function clearTags(): void;
}
