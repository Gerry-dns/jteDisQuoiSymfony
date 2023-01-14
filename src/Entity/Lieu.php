<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: LieuRepository::class)]
/* Not allowed to create a 'Lieu' with the same name*/
#[UniqueEntity('nomLieu')]
#[Vich\Uploadable]
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
    #[Assert\Length(min: 2, max: 255)]
    private ?string $typeLieu = null;

    #[ORM\Column(type : 'string', length: 20, nullable: true)]
    #[Assert\Length(min: 2, max: 20)]
    private ?string $numeroTelLieu = null;

    #[ORM\Column(type : 'string', length: 255, nullable: true)]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $emailLieu = null;

    #[ORM\Column(type : 'string', length: 255, nullable: true)]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $urlLieu = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull()]
    private ?\DateTimeImmutable $createdAt = null;


    #[ORM\ManyToOne(inversedBy: 'lieux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'lieu', targetEntity: Mark::class, orphanRemoval: true)]
    private Collection $marks;

    private ?float $average = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

     // NOTE: This is not a mapped field of entity metadata, just a simple property.
     #[Vich\UploadableField(mapping: 'jtedisquoi_images', fileNameProperty: 'imageName')]
     private ?File $imageFile = null;
 
     #[ORM\Column(type: 'string', nullable: true)]
     private ?string $imageName = null;
    /* Constructor */

    public function __construct() {
        $this->createdAt = new DateTimeImmutable();
        $this->marks = new ArrayCollection();
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

    /**
     * @return Collection<int, Mark>
     */
    public function getMarks(): Collection
    {
        return $this->marks;
    }

    public function addMark(Mark $mark): self
    {
        if (!$this->marks->contains($mark)) {
            $this->marks->add($mark);
            $mark->setLieu($this);
        }

        return $this;
    }

    public function removeMark(Mark $mark): self
    {
        if ($this->marks->removeElement($mark)) {
            // set the owning side to null (unless already changed)
            if ($mark->getLieu() === $this) {
                $mark->setLieu(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of average
     */ 
    public function getAverage()
    {
        $marks = $this->marks;
        
        if($marks->toArray() === []) {
            $this->average = null;
            return $this->average;
        }

        $total = 0;
        foreach ($marks as $mark) {
            $total += $mark->getMark();
        }

        $this->average = $total / count($marks);
            
        return $this->average;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }
}

