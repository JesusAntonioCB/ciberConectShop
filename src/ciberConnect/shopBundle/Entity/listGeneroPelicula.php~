<?php

namespace ciberConnect\shopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\MaxDepth;
/**
 * listGeneroPelicula
 *
 * @ORM\Table(name="list_genero_pelicula")
 * @ORM\Entity(repositoryClass="ciberConnect\shopBundle\Repository\listGeneroPeliculaRepository")
 */
class listGeneroPelicula
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=55)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\ManyToMany(targetEntity="peliculas", mappedBy="genero")
     * @MaxDepth(0)
     */
    private $peliculas;


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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return listGeneroPelicula
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return listGeneroPelicula
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
     * Constructor
     */
    public function __construct()
    {
        $this->peliculas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return listGeneroPelicula
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Add pelicula
     *
     * @param \ciberConnect\shopBundle\Entity\peliculas $pelicula
     *
     * @return listGeneroPelicula
     */
    public function addPelicula(\ciberConnect\shopBundle\Entity\peliculas $pelicula)
    {
        $this->peliculas[] = $pelicula;

        return $this;
    }

    /**
     * Remove pelicula
     *
     * @param \ciberConnect\shopBundle\Entity\peliculas $pelicula
     */
    public function removePelicula(\ciberConnect\shopBundle\Entity\peliculas $pelicula)
    {
        $this->peliculas->removeElement($pelicula);
    }

    /**
     * Get peliculas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeliculas()
    {
        return $this->peliculas;
    }

    public function __toString()
    {
      return $this->nombre == null ? '' : $this->nombre;
    }
}
