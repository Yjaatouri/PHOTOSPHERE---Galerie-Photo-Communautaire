<?php

class Album
{
    public function __construct(
        protected int $id,
        protected int $user_id,
        protected string $name,
        protected ?string $description = null,
        protected bool $is_public = true,
        protected ?int $cover_photo_id = null,
        protected int $photo_count = 0,
        protected DateTime $createdAt
    ) {}

    // Navigation properties
    protected ?User $user = null;
    protected ?Photo $coverPhoto = null;
    protected array $photos = [];

    // Getters
    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->user_id; }
    public function getName(): string { return $this->name; }
    public function getDescription(): ?string { return $this->description; }
    public function isPublic(): bool { return $this->is_public; }
    public function getCoverPhotoId(): ?int { return $this->cover_photo_id; }
    public function getPhotoCount(): int { return $this->photo_count; }
    public function getCreatedAt(): DateTime { return $this->createdAt; }

    public function getUser(): ?User { return $this->user; }
    public function getCoverPhoto(): ?Photo { return $this->coverPhoto; }
    public function getPhotos(): array { return $this->photos; }

    // Helpers
    public function setUser(User $user): void { $this->user = $user; }
    public function setCoverPhoto(?Photo $photo): void { $this->coverPhoto = $photo; }

    public function addPhoto(Photo $photo): void
    {
        $this->photos[] = $photo;
        $this->photo_count = count($this->photos);
    }

    public function removePhoto(Photo $photo): void
    {
        $this->photos = array_filter($this->photos, fn($p) => $p !== $photo);
        $this->photo_count = count($this->photos);
    }
}