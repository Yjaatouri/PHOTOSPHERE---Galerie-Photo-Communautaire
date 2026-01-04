<?php


class User
{
    public function __construct(
        
        protected int $id,
        protected string $username,
        protected string $email,
        protected string $password_hash,
        protected DateTime $createdAt,

        
        protected string $role = 'basic',
        protected ?string $bio = null,
        protected ?string $profile_pic = null,
        protected int $upload_count = 0,
        protected ?DateTime $sub_start = null,
        protected ?DateTime $sub_end = null,
        protected ?string $mod_level = null,
        protected ?DateTime $lastLogin = null
    ) {}

    
    protected array $photos = [];
    protected array $albums = [];
    protected array $comments = [];
    protected array $likes = [];

    
    public function getId(): int { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }
    public function getRole(): string { return $this->role; }
    public function getCreatedAt(): DateTime { return $this->createdAt; }
    public function getLastLogin(): ?DateTime { return $this->lastLogin; }
    public function getPhotos(): array { return $this->photos; }
    public function getAlbums(): array { return $this->albums; }
    public function getComments(): array { return $this->comments; }
    public function getLikes(): array { return $this->likes; }

    
    public function setLastLogin(DateTime $date): void { $this->lastLogin = $date; }
    public function incrementUploadCount(): void { $this->upload_count++; }
    public function verifyPassword(string $password): bool { return $this->password_hash === $password; }

    public function addPhoto(Photo $photo): void { $this->photos[] = $photo; }
    public function addAlbum(Album $album): void { $this->albums[] = $album; }
    public function addComment(Comment $comment): void { $this->comments[] = $comment; }
    public function addLike(Like $like): void { $this->likes[] = $like; }
}

class Photo
{
    public function __construct(
        
        protected int $id,
        protected int $user_id,
        protected string $file_path,
        protected DateTime $createdAt,

    
        protected ?string $title = null,
        protected ?string $description = null,
        protected ?int $file_size = null,
        protected ?string $mime_type = null,
        protected ?string $dimensions = null,
        protected string $state = 'draft',
        protected int $view_count = 0,
        protected ?DateTime $publishedAt = null
    ) {}

   
    protected ?User $user = null;
    protected array $albums = [];
    protected array $tags = [];
    protected array $comments = [];
    protected array $likes = [];

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->user_id; }
    public function getTitle(): ?string { return $this->title; }
    public function getFilePath(): string { return $this->file_path; }
    public function getState(): string { return $this->state; }
    public function getViewCount(): int { return $this->view_count; }
    public function getCreatedAt(): DateTime { return $this->createdAt; }
    public function getUser(): ?User { return $this->user; }
    public function getAlbums(): array { return $this->albums; }
    public function getTags(): array { return $this->tags; }
    public function getComments(): array { return $this->comments; }
    public function getLikes(): array { return $this->likes; }
    public function getLikeCount(): int { return count($this->likes); }

    public function incrementViewCount(): void { $this->view_count++; }
    public function setUser(User $user): void { $this->user = $user; }
    public function addAlbum(Album $album): void { $this->albums[] = $album; }
    public function addTag(Tag $tag): void { $this->tags[] = $tag; }
    public function addComment(Comment $comment): void { $this->comments[] = $comment; }
    public function addLike(Like $like): void { $this->likes[] = $like; }
}

class Album
{
    public function __construct(
        
        protected int $id,
        protected int $user_id,
        protected string $name,
        protected DateTime $createdAt,

        
        protected ?string $description = null,
        protected bool $is_public = true,
        protected ?int $cover_photo_id = null,
        protected int $photo_count = 0
    ) {}

    
    protected ?User $user = null;
    protected ?Photo $coverPhoto = null;
    protected array $photos = [];

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function isPublic(): bool { return $this->is_public; }
    public function getPhotoCount(): int { return $this->photo_count; }
    public function getUser(): ?User { return $this->user; }
    public function getCoverPhoto(): ?Photo { return $this->coverPhoto; }
    public function getPhotos(): array { return $this->photos; }

    public function setUser(User $user): void { $this->user = $user; }
    public function setCoverPhoto(?Photo $photo): void { $this->coverPhoto = $photo; }
    public function addPhoto(Photo $photo): void
    {
        $this->photos[] = $photo;
        $this->photo_count = count($this->photos);
    }
}

class Tag
{
    public function __construct(
        protected int $id,
        protected string $name,
        protected string $slug,
        protected int $usage_count = 0
    ) {}

    protected array $photos = [];

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getSlug(): string { return $this->slug; }
    public function getUsageCount(): int { return $this->usage_count; }
    public function getPhotos(): array { return $this->photos; }

    public function incrementUsage(): void { $this->usage_count++; }
    public function addPhoto(Photo $photo): void { $this->photos[] = $photo; }
}

class Comment
{
    public function __construct(
    
        protected int $id,
        protected int $photo_id,
        protected int $user_id,
        protected string $content,
        protected DateTime $createdAt,

        
        protected ?int $parent_id = null,
        protected bool $is_edited = false
    ) {}

    
    protected ?User $user = null;
    protected ?Photo $photo = null;
    protected ?Comment $parent = null;
    protected array $replies = [];

    public function getId(): int { return $this->id; }
    public function getContent(): string { return $this->content; }
    public function getCreatedAt(): DateTime { return $this->createdAt; }
    public function getUser(): ?User { return $this->user; }
    public function getPhoto(): ?Photo { return $this->photo; }
    public function getParent(): ?Comment { return $this->parent; }
    public function getReplies(): array { return $this->replies; }

    public function markAsEdited(): void { $this->is_edited = true; }
    public function setUser(User $user): void { $this->user = $user; }
    public function setPhoto(Photo $photo): void { $this->photo = $photo; }
    public function setParent(?Comment $parent): void { $this->parent = $parent; }
    public function addReply(Comment $reply): void { $this->replies[] = $reply; }
}

class Like
{
    public function __construct(
        protected int $user_id,
        protected int $photo_id,
        protected DateTime $createdAt
    ) {}

    protected ?User $user = null;
    protected ?Photo $photo = null;

    public function getUserId(): int { return $this->user_id; }
    public function getPhotoId(): int { return $this->photo_id; }
    public function getCreatedAt(): DateTime { return $this->createdAt; }
    public function getUser(): ?User { return $this->user; }
    public function getPhoto(): ?Photo { return $this->photo; }

    public function setUser(User $user): void { $this->user = $user; }
    public function setPhoto(Photo $photo): void { $this->photo = $photo; }
}

class AuditLog
{
    public function __construct(
    
        protected int $id,
        protected string $action,
        protected DateTime $createdAt,

        
        protected ?int $user_id = null,
        protected ?string $ip_source = null,
        protected ?string $reason = null
    ) {}

    protected ?User $user = null;

    public function getId(): int { return $this->id; }
    public function getAction(): string { return $this->action; }
    public function getCreatedAt(): DateTime { return $this->createdAt; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): void { $this->user = $user; }
}