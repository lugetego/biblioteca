<?php

namespace App\Entity;

use App\Repository\LibroRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=LibroRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Libro
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Gedmo\Slug(fields={"titulo"}, updatable=false)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $titulo;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $autor;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $editorial;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $anio;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="alerta_portada", fileNameProperty="portadaName")
     *
     * @Assert\File(
     *     maxSize = "2M",
     * uploadFormSizeErrorMessage = "El archivo debe ser menor a 2 MB",
     *     mimeTypes = {"image/jpeg", "image/png", "image/jpg"},
     *     mimeTypesMessage = "Favor de subir un archivo de imagen válido"
     * )
     *
     * @var File
     */
    public $portadaFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $portadaName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;

    /**
     * @ORM\ManyToOne(targetEntity=Alerta::class, inversedBy="libro"))
     * @ORM\JoinColumn(nullable=true)
     */
    private $alerta;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clasificacion;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $isbn;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $slide;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getAutor(): ?string
    {
        return $this->autor;
    }

    public function setAutor(string $autor): self
    {
        $this->autor = $autor;

        return $this;
    }

    public function getEditorial(): ?string
    {
        return $this->editorial;
    }

    public function setEditorial(string $editorial): self
    {
        $this->editorial = $editorial;

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

    /**
     * @return mixed
     */
    public function getClasificacion()
    {
        return $this->clasificacion;
    }

    /**
     * @param mixed $clasificacion
     */
    public function setClasificacion($clasificacion): void
    {
        $this->clasificacion = $clasificacion;
    }

    /**
     * Set portadaFile
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $portada
     */
    public function setPortadaFile(?File $portada = null): void
    {
        $this->portadaFile = $portada;

        if (null !== $portada) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }
    public function getPortadaFile(): ?File
    {
        return $this->portadaFile;
    }


    public function getPortadaName(): ?string
    {
        return $this->portadaName;
    }

    public function setPortadaName($portadaName): void
    {
        $this->portadaName = $portadaName;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getAlerta(): ?Alerta
    {
        return $this->alerta;
    }

    public function setAlerta(?Alerta $alerta): self
    {
        $this->alerta = $alerta;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function isSlide(): ?bool
    {
        return $this->slide;
    }

    public function setSlide(?bool $slide): self
    {
        $this->slide = $slide;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * @param mixed $isbn
     */
    public function setIsbn($isbn): void
    {
        $this->isbn = $isbn;
    }



}
