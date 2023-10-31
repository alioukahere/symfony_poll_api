<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\VoteRepository;
use App\State\VoteOnPollProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
#[UniqueEntity(fields: ['user', 'poll'])]
#[ApiResource(
    normalizationContext: ['groups' => ['vote:read']],
    denormalizationContext: ['groups' => ['vote:write']],
    security: 'is_granted("ROLE_USER")',
    operations: [
        new Post(
            processor: VoteOnPollProcessor::class,
        )
    ]
)]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['vote:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['vote:read'])]
    private User $user;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['vote:read', 'vote:write'])]
    #[Assert\NotBlank()]
    #[ApiProperty(example: '/api/options/1')]
    private Option $options;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Poll $poll;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getOptions(): Option
    {
        return $this->options;
    }

    public function setOptions(Option $options): static
    {
        $this->options = $options;

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
}
