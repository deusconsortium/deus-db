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
     * @ORM\Column(type="string")
     */
    private $localPath;

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
     * @ORM\Column(type="float")
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
        return $this->getLocalPath();
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
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
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
     * @return $this
     */
    public function setNbFiles($nbFiles)
    {
        $this->nbFiles = $nbFiles;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocalPath()
    {
        return $this->localPath;
    }

    public function getFullPath()
    {
        return $this->getStorage()->getPath().$this->localPath;
    }

    /**
     * @param mixed $localPath
     * @return $this
     */
    public function setLocalPath($localPath)
    {
        $this->localPath = $localPath;
        return $this;
    }

    public function getFormattedSize()
    {
        return $this::formatSize($this->getSize());
    }

    public static function formatSize($size)
    {
        if($size < 1024) {
            $unit = 'Ko';
        }
        else {
            $size /= 1024;
            if($size < 1024) {
                $unit = 'Mo';
            }
            else {
                $size /= 1024;
                if($size < 1024) {
                    $unit = 'Go';
                }
                else {
                    $size /= 1024;
                    $unit = 'To';
                }
            }
        }
        return number_format($size, 2).' '.$unit;
    }
}
