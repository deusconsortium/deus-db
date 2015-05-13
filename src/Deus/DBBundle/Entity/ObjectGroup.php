<?php
namespace Deus\DBBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class ObjectGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="ObjectType")
     */
    private $ObjectType;

    /**
     * @ORM\ManyToOne(targetEntity="ObjectFormat")
     */
    private $ObjectFormat;

    /**
     * @ORM\ManyToOne(targetEntity="Geometry", inversedBy="objectGroups")
     */
    private $Geometry;

    /**
     * @ORM\ManyToOne(targetEntity="Storage")
     */
    private $Storage;

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbFiles;

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
     * Set name
     *
     * @param string $name
     * @return ObjectGroup
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set ObjectType
     *
     * @param \Deus\DBBundle\Entity\ObjectType $objectType
     * @return ObjectGroup
     */
    public function setObjectType(\Deus\DBBundle\Entity\ObjectType $objectType = null)
    {
        $this->ObjectType = $objectType;

        return $this;
    }

    /**
     * Get ObjectType
     *
     * @return \Deus\DBBundle\Entity\ObjectType 
     */
    public function getObjectType()
    {
        return $this->ObjectType;
    }

    /**
     * Set ObjectFormat
     *
     * @param \Deus\DBBundle\Entity\ObjectFormat $objectFormat
     * @return ObjectGroup
     */
    public function setObjectFormat(\Deus\DBBundle\Entity\ObjectFormat $objectFormat = null)
    {
        $this->ObjectFormat = $objectFormat;

        return $this;
    }

    /**
     * Get ObjectFormat
     *
     * @return \Deus\DBBundle\Entity\ObjectFormat 
     */
    public function getObjectFormat()
    {
        return $this->ObjectFormat;
    }

    /**
     * Set Geometry
     *
     * @param \Deus\DBBundle\Entity\Geometry $geometry
     * @return ObjectGroup
     */
    public function setGeometry(\Deus\DBBundle\Entity\Geometry $geometry = null)
    {
        $this->Geometry = $geometry;

        return $this;
    }

    /**
     * Get Geometry
     *
     * @return \Deus\DBBundle\Entity\Geometry 
     */
    public function getGeometry()
    {
        return $this->Geometry;
    }

    /**
     * Set Storage
     *
     * @param \Deus\DBBundle\Entity\Storage $storage
     * @return ObjectGroup
     */
    public function setStorage(\Deus\DBBundle\Entity\Storage $storage = null)
    {
        $this->Storage = $storage;

        return $this;
    }

    /**
     * Get Storage
     *
     * @return \Deus\DBBundle\Entity\Storage 
     */
    public function getStorage()
    {
        return $this->Storage;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getNbFiles()
    {
        return $this->nbFiles;
    }

    /**
     * @param mixed $nbFiles
     */
    public function setNbFiles($nbFiles)
    {
        $this->nbFiles = $nbFiles;
    }
}
