<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\PollRepository;
use App\State\CreatePollProcessor;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PollRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['poll:read']],
    denormalizationContext: ['groups' => ['poll:write']],
    security: 'is_granted("ROLE_USER")',
    operations: [
        new Post(
            processor: CreatePollProcessor::class,
        ),
        new GetCollection(),
        new Get(
            security: 'object.getOwner() === user',
        )
    ]
)]
class Poll
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['poll:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['poll:read', 'poll:write'])]
    #[Assert\NotBlank()]
    private string $question;

    #[ORM\OneToMany(
        mappedBy: 'poll',
        targetEntity: Option::class,
        orphanRemoval: true,
        cascade: ['persist'],
    )]
    #[Groups(['poll:read', 'poll:write'])]
    #[Assert\Count(min: 2, max: 10)]
    private Collection $options;

    #[ORM\Column]
    #[Groups(['poll:read'])]
    private DateTimeImmutable $createdAt;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private User $owner;

    #[ORM\Column(options: ['default' => 0])]
    #[Groups(['poll:read'])]
    private int $totalVotes = 0;

    public function __construct()
    {
        $this->options = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection<int, Option>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): static
    {
        if (!$this->options->contains($option)) {
            $this->options->add($option);
            $option->setPoll($this);
        }

        return $this;
    }

    public function removeOption(Option $option): static
    {
        $this->options->removeElement($option);

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function initializeCreatedAt(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getTotalVotes(): int
    {
        return $this->totalVotes;
    }

    public function setTotalVotes(int $totalVotes): static
    {
        $this->totalVotes = $totalVotes;

        return $this;
    }

    public function incrementTotalVotes(): static
    {
        ++$this->totalVotes;

        return $this;
    }
}
