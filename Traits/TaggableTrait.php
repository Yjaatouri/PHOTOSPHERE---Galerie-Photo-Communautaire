<?php
declare(strict_types=1);

namespace App\Traits;


trait TaggableTrait
{
    /**
     * @var string[]
     */
    protected array $tags = [];

    protected bool $tagsLoaded = false;

    public function addTag(string $tag): void
    {
        $this->loadTagsIfNeeded();
        $tag = $this->normalizeTag($tag);

        if (!in_array($tag, $this->tags, true)) {
            $this->tags[] = $tag;
        }
    }

    public function removeTag(string $tag): void
    {
        $this->loadTagsIfNeeded();
        $tag = $this->normalizeTag($tag);

        $this->tags = array_values(
            array_filter($this->tags, fn ($t) => $t !== $tag)
        );
    }

    public function getTags(): array
    {
        $this->loadTagsIfNeeded();
        return $this->tags;
    }

    public function hasTag(string $tag): bool
    {
        $this->loadTagsIfNeeded();
        return in_array($this->normalizeTag($tag), $this->tags, true);
    }

    public function clearTags(): void
    {
        $this->tags = [];
    }

    public function hasAllTags(array $tags): bool
    {
        $this->loadTagsIfNeeded();

        foreach ($tags as $tag) {
            if (!$this->hasTag($tag)) {
                return false;
            }
        }
        return true;
    }

    public function hasAnyTag(array $tags): bool
    {
        $this->loadTagsIfNeeded();

        foreach ($tags as $tag) {
            if ($this->hasTag($tag)) {
                return true;
            }
        }
        return false;
    }

    protected function normalizeTag(string $tag): string
    {
        return strtolower(trim($tag));
    }

    protected function loadTagsIfNeeded(): void
    {
        if (!$this->tagsLoaded) {
            $this->loadTagsFromDatabase();
            $this->tagsLoaded = true;
        }
    }

  
    abstract protected function loadTagsFromDatabase(): void;
}
