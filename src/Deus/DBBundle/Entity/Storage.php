<?php
namespace Deus\DBBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class Storage
{
    const MEUDON_EFILER_DATA1="meudon_efiler_data1";
    const MEUDON_EFILER_DATA2="meudon_efiler_data2";
    const MEUDON_BINGO_DATA="meudon_bingo_data";
    const MEUDON_ASISU_DEUS_DATA="meudon_asisu_deus_data";
    const IDRIS_ERGON_STOREDIR="idris_ergon_storedir";
    const TGCC_CURIE_STOREDIR="tgcc_curie_storedir";

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=32)
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity="Deus\DBBundle\Entity\Location")
     */
    private $Location;

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
     * @return Storage
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
     * @return string
     */
    public function __toString()
    {
        return ($this->Location ? $this->Location->getName() : '?').' / '.$this->getName();
    }

    /**
     * Set Location
     *
     * @param \Deus\DBBundle\Entity\Location $location
     * @return Storage
     */
    public function setLocation(\Deus\DBBundle\Entity\Location $location = null)
    {
        $this->Location = $location;

        return $this;
    }

    /**
     * Get Location
     *
     * @return \Deus\DBBundle\Entity\Location 
     */
    public function getLocation()
    {
        return $this->Location;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
