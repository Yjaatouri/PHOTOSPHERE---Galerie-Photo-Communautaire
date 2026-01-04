<?php

class Photo
{
    public function __construct(
        protected int $id,
        protected int $user_id,
        protected ?string $title = null,
        protected ?string $description = null,
        protected string $file_path,
        protected ?int $file_size = null,
        protected ?string $mime_type = null,
        protected ?string $dimensions = null,
        protected string $state = 'draft',
        protected int $view_count = 0,
        protected ?DateTime $publishedAt = null,
        protected DateTime $createdAt
    ) {}

    // Navigation properties
    protected ?User $user = null;
    protected array $albums = [];
    protected array $tags = [];
    protected array $comments = [];
    protected array $likes = [];

    // Getters
    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->user_id; }
    public function getTitle(): ?string { return $this->title; }
    public function getDescription(): ?string { return $this->description; }
    public function getFilePath(): string { return $this->file_path; }
    public function getFileSize(): ?int { return $this->file_size; }
    public function getMimeType(): ?string { return $this->mime_type; }
    public function getDimensions(): ?string { return $this->dimensions; }
    public function getState(): string { return $this->state; }
    public function getViewCount(): int { return $this->view_count; }
    public function getPublishedAt(): ?DateTime { return $this->publishedAt; }
    public function getCreatedAt(): DateTime { return $this->createdAt; }

    public function getUser(): ?User { return $this->user; }
    public function getAlbums(): array { return $this->albums; }
    public function getTags(): array { return $this->tags; }
    public function getComments(): array { return $this->comments; }
    public function getLikes(): array { return $this->likes; }

    public function getLikeCount(): int { return count($this->likes); }
    public function getCommentCount(): int { return count($this->comments); }

    // Helpers
    public function incrementViewCount(): void { $this->view_count++; }

    public function setUser(User $user): void { $this->user = $user; }
    public function addAlbum(Album $album): void { $this->albums[] = $album; }
    public function addTag(Tag $tag): void { $this->tags[] = $tag; }
    public function addComment(Comment $comment): void { $this->comments[] = $comment; }
    public function addLike(Like $like): void { $this->likes[] = $like; }
}