<?php

namespace ciberConnect\shopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * peliculas
 *
 * @ORM\Table(name="peliculas")
 * @ORM\Entity(repositoryClass="ciberConnect\shopBundle\Repository\peliculasRepository")
 * @UniqueEntity("nombreNormalizado")
 */
class peliculas
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="year", type="string", length=10)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreEsp", type="string", length=255)
     */
    private $nombreEsp;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreOrigen", type="string", length=255)
     */
    private $nombreOrigen;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreNormalizado", type="string", length=255)
     */
    private $nombreNormalizado;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="calidad", type="string", length=20)
     */
    private $calidad;

    /**
     * @var string
     *
     * @ORM\Column(name="audio", type="string", length=50)
     */
    private $audio;

    /**
     * @var string
     *
     * @ORM\Column(name="formato", type="string", length=20)
     */
    private $formato;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=2000)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true)
     */
    private $imagen;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string", length=255, nullable=true)
     */
    private $video;

    /**
     * @var float
     *
     * @ORM\Column(name="puntaje", type="float")
     */
    private $puntaje;

    /**
     * @var float
     *
     * @ORM\Column(name="precio", type="float")
     */
    private $precio;

    /**
     * @var bool
     *
     * @ORM\Column(name="checkbox", type="boolean")
     */
    private $checkbox;

    /**
    * @ORM\ManyToMany(targetEntity="listGeneroPelicula", inversedBy="peliculas")
    * @ORM\JoinTable(name="peliculas_genero",
    *     joinColumns={
    *     @ORM\JoinColumn(name="pelicula_id", referencedColumnName="id")
    *   },
    *   inverseJoinColumns={
    *     @ORM\JoinColumn(name="genero_id", referencedColumnName="id")
    *   }
    * )
    * @MaxDepth(0)
    */
   private $genero;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set year
     *
     * @param string $year
     *
     * @return peliculas
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set nombreEsp
     *
     * @param string $nombreEsp
     *
     * @return peliculas
     */
    public function setNombreEsp($nombreEsp)
    {
        $this->nombreEsp = $nombreEsp;

        return $this;
    }

    /**
     * Get nombreEsp
     *
     * @return string
     */
    public function getNombreEsp()
    {
        return $this->nombreEsp;
    }

    /**
     * Set nombreOrigen
     *
     * @param string $nombreOrigen
     *
     * @return peliculas
     */
    public function setNombreOrigen($nombreOrigen)
    {
        $this->nombreOrigen = $nombreOrigen;

        return $this;
    }

    /**
     * Get nombreOrigen
     *
     * @return string
     */
    public function getNombreOrigen()
    {
        return $this->nombreOrigen;
    }

    /**
     * Set calidad
     *
     * @param string $calidad
     *
     * @return peliculas
     */
    public function setCalidad($calidad)
    {
        $this->calidad = $calidad;

        return $this;
    }

    /**
     * Get calidad
     *
     * @return string
     */
    public function getCalidad()
    {
        return $this->calidad;
    }

    /**
     * Set audio
     *
     * @param string $audio
     *
     * @return peliculas
     */
    public function setAudio($audio)
    {
        $this->audio = $audio;

        return $this;
    }

    /**
     * Get audio
     *
     * @return string
     */
    public function getAudio()
    {
        return $this->audio;
    }

    /**
     * Set formato
     *
     * @param string $formato
     *
     * @return peliculas
     */
    public function setFormato($formato)
    {
        $this->formato = $formato;

        return $this;
    }

    /**
     * Get formato
     *
     * @return string
     */
    public function getFormato()
    {
        return $this->formato;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return peliculas
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Get ShortDescripcion
     *
     * @return string
     */
    public function getShortDescripcion()
    {
        return substr($this->descripcion, 0, 200)."...";
    }

    /**
     * Set imagen
     *
     * @param string $imagen
     *
     * @return peliculas
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen
     *
     * @return string
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Set puntaje
     *
     * @param float $puntaje
     *
     * @return peliculas
     */
    public function setPuntaje($puntaje)
    {
        $this->puntaje = $puntaje;

        return $this;
    }

    /**
     * Get puntaje
     *
     * @return float
     */
    public function getPuntaje()
    {
        return $this->puntaje;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->genero = new ArrayCollection();
    }

    /**
     * Add genero
     *
     * @param \ciberConnect\shopBundle\Entity\listGeneroPelicula $genero
     *
     * @return peliculas
     */
    public function addGenero(\ciberConnect\shopBundle\Entity\listGeneroPelicula $genero)
    {
        $this->genero[] = $genero;

        return $this;
    }

    /**
     * Remove genero
     *
     * @param \ciberConnect\shopBundle\Entity\listGeneroPelicula $genero
     */
    public function removeGenero(\ciberConnect\shopBundle\Entity\listGeneroPelicula $genero)
    {
        $this->genero->removeElement($genero);
    }

    /**
     * Get genero
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * Set precio
     *
     * @param float $precio
     *
     * @return peliculas
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set checkbox
     *
     * @param boolean $checkbox
     *
     * @return peliculas
     */
    public function setCheckbox($checkbox)
    {
        $this->checkbox = $checkbox;

        return $this;
    }

    /**
     * Get checkbox
     *
     * @return boolean
     */
    public function getCheckbox()
    {
        return $this->checkbox;
    }

    /**
     * Set video
     *
     * @param string $video
     *
     * @return peliculas
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return string
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set nombreNormalizado.
     *
     * @param string $nombreNormalizado
     *
     * @return peliculas
     */
    public function setNombreNormalizado($nombreNormalizado)
    {
        $this->nombreNormalizado = $nombreNormalizado;

        return $this;
    }

    /**
     * Get nombreNormalizado.
     *
     * @return string
     */
    public function getNombreNormalizado()
    {
        return $this->nombreNormalizado;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return peliculas
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
