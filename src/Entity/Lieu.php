<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LieuRepository::class)]
/* Not allowed to create a 'Lieu' with the same name*/
#[UniqueEntity('nomLieu')]
class Lieu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(min: 2, max: 255)]
    #[Assert\NotBlank()]
    private ?string $nomLieu = null;

    #[ORM\Column(type : 'string', length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $typeLieu = null;

    #[ORM\Column(type : 'string', length: 20, nullable: true)]
    #[Assert\Length(max: 20)]
    private ?string $numeroTelLieu = null;

    #[ORM\Column(type : 'string', length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $emailLieu = null;

    #[ORM\Column(type : 'string', length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $urlLieu = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'lieux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /* Constructor */

    public function __construct() {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomLieu(): ?string
    {
        return $this->nomLieu;
    }

    public function setNomLieu(string $nomLieu): self
    {
        $this->nomLieu = $nomLieu;

        return $this;
    }

    public function getTypeLieu(): ?string
    {
        return $this->typeLieu;
    }

    public function setTypeLieu(?string $typeLieu): self
    {
        $this->typeLieu = $typeLieu;

        return $this;
    }

    public function getNumeroTelLieu(): ?string
    {
        return $this->numeroTelLieu;
    }

    public function setNumeroTelLieu(?string $numeroTelLieu): self
    {
        $this->numeroTelLieu = $numeroTelLieu;

        return $this;
    }

    public function getEmailLieu(): ?string
    {
        return $this->emailLieu;
    }

    public function setEmailLieu(?string $emailLieu): self
    {
        $this->emailLieu = $emailLieu;

        return $this;
    }

    public function getUrlLieu(): ?string
    {
        return $this->urlLieu;
    }

    public function setUrlLieu(?string $urlLieu): self
    {
        $this->urlLieu = $urlLieu;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
