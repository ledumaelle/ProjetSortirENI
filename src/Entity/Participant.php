<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("mail")
 * @UniqueEntity("pseudo")
 * @Vich\Uploadable
 */
class Participant implements UserInterface, Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=125)
     */
    private $nom;
    /**
     * @ORM\Column(type="string", length=125)
     */
    private $prenom;
    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $telephone;
    /**
     * @ORM\Column(type="string", length=125, unique=true)
     */
    private $pseudo;
    /**
     * @ORM\Column(type="string", length=125, unique=true)
     */
    private $mail;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motPasse;
    /**
     * @ORM\Column(type="boolean")
     */
    private $administrateur;
    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;
    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;
    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur", orphanRemoval=true)
     */
    private $sortiesOrganisees;
    /**
     * @ORM\OneToMany(targetEntity=Inscription::class, mappedBy="participant", orphanRemoval=true)
     */
    private $inscriptions;
    /**
     * @Vich\UploadableField(mapping="participant_images", fileNameProperty="imageName", size="imageSize")
     */
    private $imageFile;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $imageName;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $imageSize;
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
        $this->sortiesOrganisees = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param mixed $pseudo
     */
    public function setPseudo($pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getMotPasse(): ?string
    {
        return $this->motPasse;
    }

    public function setMotPasse(string $motPasse): self
    {
        $this->motPasse = $motPasse;

        return $this;
    }

    public function isAdmin()
    {
        return $this->administrateur;
    }

    public function getAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortiesOrganisees(): Collection
    {
        return $this->sortiesOrganisees;
    }

    public function addSortiesOrganisees(Sortie $sorty): self
    {
        if (!$this->sortiesOrganisees->contains($sorty)) {
            $this->sortiesOrganisees[] = $sorty;
            $sorty->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortiesOrganisees(Sortie $sorty): self
    {
        if ($this->sortiesOrganisees->contains($sorty)) {
            $this->sortiesOrganisees->removeElement($sorty);
            // set the owning side to null (unless already changed)
            if ($sorty->getOrganisateur() === $this) {
                $sorty->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Inscription[]
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setParticipant($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->contains($inscription)) {
            $this->inscriptions->removeElement($inscription);
            // set the owning side to null (unless already changed)
            if ($inscription->getParticipant() === $this) {
                $inscription->setParticipant(null);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->motPasse;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->mail;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        $roles = ["ROLE_USER"];

        if ($this->getAdministrateur()) {
            $roles[] = "ROLE_ADMIN";
        }

        return $roles;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        //PAS BESOIN
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        //PAS BESOIN
    }

    public function getImageName()
    {
        return $this->imageName;
    }

    public function setImageName($imageName): void
    {
        $this->imageName = $imageName;
    }

    public function setImageSize($imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize()
    {
        return $this->imageSize;
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
        $this->dateCreated = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
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
        $this->dateModified = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile|null $imageFile
     * @throws Exception
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setDateModified();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->nom,
            $this->prenom,
            $this->pseudo,
            $this->mail,
            $this->motPasse,
            $this->imageName,
            $this->imageSize,
            $this->campus,
            $this->administrateur,
            $this->actif,
            $this->dateModified,
            $this->dateCreated,
        ]);
    }

    public function unserialize($serialized)
    {
        [
            $this->id,
            $this->nom,
            $this->prenom,
            $this->pseudo,
            $this->mail,
            $this->motPasse,
            $this->imageName,
            $this->imageSize,
            $this->campus,
            $this->administrateur,
            $this->actif,
            $this->dateModified,
            $this->dateCreated,
        ] = unserialize($serialized);
    }
}
