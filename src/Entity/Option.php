<?php

namespace App\Entity;

use App\Repository\OptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OptionRepository::class)]
#[ORM\Table(name: '`option`')]
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
}
