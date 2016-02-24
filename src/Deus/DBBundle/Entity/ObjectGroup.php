<?php
namespace Deus\DBBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity("id")
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
     * @ORM\Column(type="bigint")
     */
    private $size;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbFiles;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbGroups;

    /**
     * @ORM\Column(type="string")
     */
    private $filePattern;

    /**
     * @ORM\Column(type="boolean")
     */
    private $public = false;

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
        return gmp_init($this->size);
    }

    /**
     * @param mixed $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = gmp_strval($size);
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
        $size = (double) gmp_strval($size);
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

    /**
     * @param mixed $filePattern
     * @return ObjectGroup
     */
    public function setFilePattern($filePattern)
    {
        $this->filePattern = $filePattern;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilePattern()
    {
        return $this->filePattern;
    }

    /**
     * @param mixed $public
     * @return ObjectGroup
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

    public function calculateFilePattern()
    {
        $Geometry = $this->getGeometry();
        $Simulation = $Geometry->getSimulation();
        $res = "";

        switch($this->getObjectType()->getId()) {
            case 'cube':
                switch($this->getObjectFormat()->getId()) {
                    case 'fof':
                        $res =  "fof_boxlen"
                            .$Simulation->getBoxlen()->getValue()
                            ."_n".$Simulation->getResolution()->getValue()
                            ."_".$Simulation->getCosmology()->getName()."_cube_?????";
                        break;
                    case 'multi':
                        $res = "fof_boxlen"
                        .$Simulation->getBoxlen()->getValue()
                        ."_n".$Simulation->getResolution()->getValue()
                        ."_".$Simulation->getCosmology()->getName()."_multicube_?????";
                        break;
                }
                break;
            case 'grav':
                if("cone" == $Geometry->getGeometryType()->getId()) { // Grav on cone only
                    switch($this->getObjectFormat()->getId()) {
                        case 'fof':
                            $res =  "fof_conegrav"
                                .$Simulation->getBoxlen()->getValue()
                                ."_n".$Simulation->getResolution()->getValue()
                                ."_".$Simulation->getCosmology()->getName()."_cube_?????";
                            break;
                        case 'multi':
                            $res = "fof_conegrav"
                                .$Simulation->getBoxlen()->getValue()
                                ."_n".$Simulation->getResolution()->getValue()
                                ."_".$Simulation->getCosmology()->getName()."_multicube_?????";
                            break;
                    }
                    break;
                }

            case 'strct':
                switch($this->getObjectFormat()->getId()) {
                    case 'fof':
                        $res =  "fof_boxlen"
                            .$Simulation->getBoxlen()->getValue()
                            ."_n".$Simulation->getResolution()->getValue()
                            ."_".$Simulation->getCosmology()->getName()."_strct_?????";
                        break;
                    case 'multi':
                        $res = "fof_boxlen"
                            .$Simulation->getBoxlen()->getValue()
                            ."_n".$Simulation->getResolution()->getValue()
                            ."_".$Simulation->getCosmology()->getName()."_multistrct_?????";
                        break;
                }
                break;
            case 'masst':
                switch($this->getObjectFormat()->getId()) {
                    case 'fof':
                        $res =  "fof_boxlen"
                            .$Simulation->getBoxlen()->getValue()
                            ."_n".$Simulation->getResolution()->getValue()
                            ."_".$Simulation->getCosmology()->getName()."_masst_?????";
                        break;
                    case 'multi':
                        $res = "fof_boxlen"
                            .$Simulation->getBoxlen()->getValue()
                            ."_n".$Simulation->getResolution()->getValue()
                            ."_".$Simulation->getCosmology()->getName()."_multimasst_?????";
                        break;
                }
                break;
        }

        if("cone" == $Geometry->getGeometryType()->getId() && 21000 == $Simulation->getBoxlen()->getValue()) {
            $res = str_replace("fof_boxlen21000","fof_cone21000", $res);
            if("lcdmw7" == $Simulation->getCosmology()->getName()) {
                $res = str_replace("lcdmw7","lcdmw7_dosamplecone", $res);
            }
        }

        return $res;
    }

    /**
     * @return mixed
     */
    public function getNbGroups()
    {
        return $this->nbGroups;
    }

    /**
     * @param mixed $nbGroups
     */
    public function setNbGroups($nbGroups)
    {
        $this->nbGroups = $nbGroups;
    }
}
