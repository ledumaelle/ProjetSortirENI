<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Campus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="campus", orphanRemoval=true)
     */
    private $participants;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="siteOrganisateur", orphanRemoval=true)
     */
    private $sorties;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $dateModified;


    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->sorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setCampus($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            // set the owning side to null (unless already changed)
            if ($participant->getCampus() === $this) {
                $participant->setCampus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties[] = $sorty;
            $sorty->setSiteOrganisateur($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        if ($this->sorties->contains($sorty)) {
            $this->sorties->removeElement($sorty);
            // set the owning side to null (unless already changed)
            if ($sorty->getSiteOrganisateur() === $this) {
                $sorty->setSiteOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @ORM\PrePersist
     * @throws Exception
     */
    public function setDateCreated()
    {
        $this->dateCreated = new \DateTime('now',new \DateTimeZone('Europe/Paris'));
    }

    /**
     * @return mixed
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    /**
     * @ORM\PreUpdate()
     * @throws Exception
     */
    public function setDateModified()
    {
        $this->dateModified = new \DateTime('now',new \DateTimeZone('Europe/Paris'));
    }
}
