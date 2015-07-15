<?php
namespace Deus\DBBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * @ORM\Entity
 */
class Geometry
{
    const FULLSKY = 'FullSky';
    const NARROW = 'Narrow';
    const NOTCONE = 'N/A';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Simulation", inversedBy="snapshots")
     */
    private $Simulation;

    /**
     * @ORM\ManyToOne(targetEntity="GeometryType")
     */
    private $GeometryType;

    /**
     * @ORM\OneToMany(targetEntity="ObjectGroup", mappedBy="Geometry", orphanRemoval=true)
     */
    private $objectGroups;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Z;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $formattedZ;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $angle;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        $name = $this->getSimulation()->__toString();
        $name .= " " . $this->getGeometryType()->getName();
        if (is_numeric($this->Z)) {
            $name .= " Z=" . $this->getFormattedZ();
        }
        if (is_numeric($this->angle)) {
            $name .= " " . $this->angle . "°";
        }
        return $name;
    }

    /**
     * Set Z
     *
     * @param float $z
     * @return Snapshot
     */
    public function setZ($z)
    {
        $this->Z = $z;
        $this->formattedZ = number_format($z, 2);

        return $this;
    }

    /**
     * Get Z
     *
     * @return float 
     */
    public function getZ()
    {
        return $this->Z;
    }

    /**
     * @return mixed
     */
    public function getFormattedZ()
    {
        return $this->formattedZ;
    }

    /**
     * Set Simulation
     *
     * @param \Deus\DBBundle\Entity\Simulation $simulation
     * @return Snapshot
     */
    public function setSimulation(\Deus\DBBundle\Entity\Simulation $simulation = null)
    {
        $this->Simulation = $simulation;

        return $this;
    }

    /**
     * Get Simulation
     *
     * @return \Deus\DBBundle\Entity\Simulation 
     */
    public function getSimulation()
    {
        return $this->Simulation;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->objectGroups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set GeometryType
     *
     * @param \Deus\DBBundle\Entity\GeometryType $geometryType
     * @return Geometry
     */
    public function setGeometryType(\Deus\DBBundle\Entity\GeometryType $geometryType = null)
    {
        $this->GeometryType = $geometryType;

        return $this;
    }

    /**
     * Get GeometryType
     *
     * @return \Deus\DBBundle\Entity\GeometryType 
     */
    public function getGeometryType()
    {
        return $this->GeometryType;
    }

    /**
     * Add objectGroups
     *
     * @param \Deus\DBBundle\Entity\ObjectGroup $objectGroups
     * @return Geometry
     */
    public function addObjectGroup(\Deus\DBBundle\Entity\ObjectGroup $objectGroups)
    {
        $this->objectGroups[] = $objectGroups;

        return $this;
    }

    /**
     * Remove objectGroups
     *
     * @param \Deus\DBBundle\Entity\ObjectGroup $objectGroups
     */
    public function removeObjectGroup(\Deus\DBBundle\Entity\ObjectGroup $objectGroups)
    {
        $this->objectGroups->removeElement($objectGroups);
        $objectGroups->setGeometry(null);
    }

    /**
     * Get objectGroups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getObjectGroups()
    {
        return $this->objectGroups;
    }

    /**
     * @return mixed
     */
    public function getAngle()
    {
        return $this->angle;
    }

    /**
     * @param mixed $angle
     */
    public function setAngle($angle)
    {
        $this->angle = $angle;
    }
}