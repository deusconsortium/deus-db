<?php
namespace Deus\DBBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="search_idx", columns={"code", "geometryType_id", "simulation_id"})})
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
     * @ORM\Column(type="string", length=10)
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity="Simulation", inversedBy="geometries")
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
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $properties;

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

        $name .= " Z=" . Geometry::formatZ($this->getFormattedZ());

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
        if(is_null($z) || !is_numeric($z)) {
            $this->formattedZ = "?";
        }
        else {
            if($z < 1.0) {
                    $this->formattedZ = round($z, 2);
                }
            elseif($z < 10.0) {
                    $this->formattedZ = round($z, 1);
                }
            else {
                    $this->formattedZ = round($z, 0);
                }

            $this->formattedZ = sprintf("%+5.2f", $this->formattedZ);
            $this->formattedZ = $this->formattedZ{0}.str_pad(substr($this->formattedZ,1),7,'0',STR_PAD_LEFT);
        }

        return $this;
    }

    static public function formatZ($Z)
    {
        if('?' == $Z) {
            return $Z;
        }
        $Z = (float) $Z;
        if($Z < 1.0) {
            $Z = number_format($Z, 2);
        }
        elseif($Z < 10.0) {
            $Z = number_format($Z, 1);
        }
        else {
            $Z = number_format($Z, 0);
        }
        return $Z;
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
     * @return Geometry
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
        $objectGroups->setGeometry($this);

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
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
}
