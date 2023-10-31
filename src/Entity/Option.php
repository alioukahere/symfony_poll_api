<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\OptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OptionRepository::class)]
#[ORM\Table(name: '`option`')]
#[ApiResource(
    operations: [],
)]
class Option
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['poll:read', 'poll:write'])]
    private string $content;

    #[ORM\ManyToOne(inversedBy: 'options')]
    #[ORM\JoinColumn(nullable: false)]
    private Poll $poll;

    #[ORM\Column(options: ['default' => 0])]
    #[Groups(['poll:read'])]
    private int $voteCount = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPoll(): Poll
    {
        return $this->poll;
    }

    public function setPoll(Poll $poll): static
    {
        $this->poll = $poll;

        return $this;
    }

    public function getVoteCount(): int
    {
        return $this->voteCount;
    }

    public function setVoteCount(int $voteCount): static
    {
        $this->voteCount = $voteCount;

        return $this;
    }

    public function incrementVoteCount(): static
    {
        ++$this->voteCount;

        return $this;
    }
}
