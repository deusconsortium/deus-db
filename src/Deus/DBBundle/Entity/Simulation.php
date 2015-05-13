<?php
namespace Deus\DBBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class Simulation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Deus\DBBundle\Entity\Boxlen")
     */
    private $Boxlen;

    /**
     * @ORM\ManyToOne(targetEntity="Resolution")
     */
    private $Resolution;

    /**
     * @ORM\ManyToOne(targetEntity="Cosmology")
     */
    private $Cosmology;

    /**
     * @ORM\OneToMany(targetEntity="Geometry", mappedBy="Simulation")
     */
    private $geometries;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->geometries = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Boxlen
     *
     * @param \Deus\DBBundle\Entity\Boxlen $boxlen
     * @return Simulation
     */
    public function setBoxlen(\Deus\DBBundle\Entity\Boxlen $boxlen = null)
    {
        $this->Boxlen = $boxlen;

        return $this;
    }

    /**
     * Get Boxlen
     *
     * @return \Deus\DBBundle\Entity\Boxlen 
     */
    public function getBoxlen()
    {
        return $this->Boxlen;
    }

    /**
     * Set Resolution
     *
     * @param \Deus\DBBundle\Entity\Resolution $resolution
     * @return Simulation
     */
    public function setResolution(\Deus\DBBundle\Entity\Resolution $resolution = null)
    {
        $this->Resolution = $resolution;

        return $this;
    }

    /**
     * Get Resolution
     *
     * @return \Deus\DBBundle\Entity\Resolution 
     */
    public function getResolution()
    {
        return $this->Resolution;
    }

    /**
     * Set Cosmology
     *
     * @param \Deus\DBBundle\Entity\Cosmology $cosmology
     * @return Simulation
     */
    public function setCosmology(\Deus\DBBundle\Entity\Cosmology $cosmology = null)
    {
        $this->Cosmology = $cosmology;

        return $this;
    }

    /**
     * Get Cosmology
     *
     * @return \Deus\DBBundle\Entity\Cosmology 
     */
    public function getCosmology()
    {
        return $this->Cosmology;
    }

    /**
     * Add geometries
     *
     * @param \Deus\DBBundle\Entity\Geometry $geometries
     * @return Simulation
     */
    public function addGeometry(\Deus\DBBundle\Entity\Geometry $geometries)
    {
        $this->geometries[] = $geometries;

        return $this;
    }

    /**
     * Remove geometries
     *
     * @param \Deus\DBBundle\Entity\Geometry $geometries
     */
    public function removeGeometry(\Deus\DBBundle\Entity\Geometry $geometries)
    {
        $this->geometries->removeElement($geometries);
    }

    /**
     * Get geometries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGeometries()
    {
        return $this->geometries;
    }

    public function __toString()
    {
        return $this->getBoxlen()->getValue()." Mpc/h ".$this->getResolution()->getValue()."^3 particles ".$this->getCosmology()->getName();
    }
}
