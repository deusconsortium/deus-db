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
     * @ORM\OneToMany(targetEntity="Geometry", mappedBy="Simulation", orphanRemoval=true)
     */
    private $geometries;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $properties;

    /**
     * @ORM\Column(type="boolean")     *
     */
    private $public = false;

    /**
     * @ORM\Column(type="float")     *
     */
    private $particleMass = false;

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
        $geometries->setSimulation(null);
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

    /**
     * @return mixed
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param mixed $properties
     * @return $this
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * @param mixed $public
     * @return Simulation
     */
    public function setPublic($public)
    {
        $this->public = $public;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @param mixed $particleMass
     * @return Simulation
     */
    public function setParticleMass($particleMass)
    {
        $this->particleMass = $particleMass;
        return $this;
    }

    public function calculateParticleMass()
    {
        $properties = $this->getProperties();
        $omegaM = $properties['omega_m'];

        if(!$omegaM && $this->getCosmology()) {
            $cosmoProperties = $this->getCosmology()->getProperties();
            $omegaM = $cosmoProperties['omega_m'];
        }

        if(!isset($omegaM) || !$this->getBoxlen() || !$this->getResolution()) {
            $this->setParticleMass("0.0");
        }
        else {
            bcscale(10);
            $mp = (float) bcdiv(
                bcmul(
                    bcmul(
                        2.775E11,
                        (float) $omegaM
                    ),
                    bcpow((float) $this->getBoxlen()->getValue(), 3)
                ),
                bcpow((float) $this->getResolution()->getValue(), 3)
            );

            $this->setParticleMass($mp);
        }

    }

    /**
     * @return mixed
     */
    public function getParticleMass()
    {
        return $this->particleMass;
    }

}
