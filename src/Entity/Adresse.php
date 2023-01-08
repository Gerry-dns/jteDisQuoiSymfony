<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    #[Assert\Length(min: 2, max: 10)]
    private ?string $numRue = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $nomRue = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?lieu $adresseLieu = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumRue(): ?string
    {
        return $this->numRue;
    }

    public function setNumRue(string $numRue): self
    {
        $this->numRue = $numRue;

        return $this;
    }

    public function getNomRue(): ?string
    {
        return $this->nomRue;
    }

    public function setNomRue(string $nomRue): self
    {
        $this->nomRue = $nomRue;

        return $this;
    }

    public function getAdresseLieu(): ?lieu
    {
        return $this->adresseLieu;
    }

    public function setAdresseLieu(?lieu $adresseLieu): self
    {
        $this->adresseLieu = $adresseLieu;

        return $this;
    }
}
