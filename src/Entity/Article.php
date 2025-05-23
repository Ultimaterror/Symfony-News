<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\State\ArticleSetOwnerProcessor;

#[ApiResource(
    operations: [
        new GetCollection(uriTemplate: "/public/articles"),
        new Post(security: "is_granted('ROLE_WRITER') or is_granted('ROLE_ADMIN')"),
        new Get(uriTemplate: "/public/articles/{id}"),
        // new Get(),
        new Put(security: "is_granted('ROLE_ADMIN') or object.author == user", securityPostDenormalize: "is_granted('ROLE_ADMIN') or object.author == user"),
        new Delete(security: "is_granted('ROLE_ADMIN') or object.author == user"),
    ],
    normalizationContext: ['groups' => ['articles:read']],
    denormalizationContext: ['groups' => ['articles:write']]
)]
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('articles:read')]
    private ?int $id = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: "create")]
    #[Groups('articles:read')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable]
    #[Groups('articles:read')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 5, max: 200), Assert\Type('string')]
    #[Groups(['articles:read', "articles:write", 'categories:read'])]
    private ?string $title = "";

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Type('string'), Assert\NotBlank]
    #[Groups(['articles:read', "articles:write"])]
    private ?string $content = null;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\Type('boolean')]
    #[Groups(['articles:read', "articles:write"])]
    private ?bool $visible = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['articles:read', "articles:write"])]
    #[Assert\NotBlank]
    private ?Category $category = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ["title"])]
    #[Groups(['articles:read', 'categories:read'])]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[Groups('articles:read')]
    #[Assert\Valid]
    private ?User $author = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): static
    {
        $this->visible = $visible;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }
}
