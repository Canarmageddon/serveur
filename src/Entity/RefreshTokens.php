<?php

namespace App\Entity;

use App\Repository\RefreshTokensRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RefreshTokensRepository::class)]
class RefreshTokens
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 128)]
    private ?string $refreshToken;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $username;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $valid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getValid(): ?DateTimeInterface
    {
        return $this->valid;
    }

    public function setValid(DateTimeInterface $valid): self
    {
        $this->valid = $valid;

        return $this;
    }
}
