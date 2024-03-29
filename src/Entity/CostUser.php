<?php

namespace App\Entity;

use App\Repository\CostUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CostUserRepository::class)]
class CostUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Cost::class, inversedBy: 'costUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cost $cost;

    #[ORM\ManyToOne(targetEntity: SuperUser::class, inversedBy: 'costUsers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['cost:list', 'cost:item'])]
    private ?SuperUser $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCost(): ?Cost
    {
        return $this->cost;
    }

    public function setCost(?Cost $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getUser(): ?SuperUser
    {
        return $this->user;
    }

    public function setUser(?SuperUser $user): self
    {
        $this->user = $user;

        return $this;
    }
}