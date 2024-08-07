<?php

namespace App\Entity;

use App\Repository\AlertaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AlertaRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Alerta
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Gedmo\Slug(fields={"numero","anio"}, updatable=false)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $anio;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;

    /**
     * @ORM\OneToMany(targetEntity=Libro::class, mappedBy="alerta")
     */
    private $libro;



    public function __construct()
    {
        $this->libro = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getAnio(): ?string
    {
        return $this->anio;
    }

    public function setAnio(string $anio): self
    {
        $this->anio = $anio;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created): void
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param mixed $modified
     */
    public function setModified($modified): void
    {
        $this->modified = $modified;
    }


    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setCreated(new \DateTime());
        $this->setModified(new \DateTime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->setModified(new \DateTime());
    }

    /**
     * @return Collection<int, Libro>
     */
    public function getLibro(): Collection
    {
        return $this->libro;
    }

    public function addLibro(Libro $libro): self
    {
        if (!$this->libro->contains($libro)) {
            $this->libro[] = $libro;
            $libro->setAlerta($this);
        }

        return $this;
    }

    public function removeLibro(Libro $libro): self
    {
        if ($this->libro->removeElement($libro)) {
            // set the owning side to null (unless already changed)
            if ($libro->getAlerta() === $this) {
                $libro->setAlerta(null);
            }
        }

        return $this;
    }



}
