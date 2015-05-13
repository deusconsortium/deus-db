<?php
namespace Deus\DBBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class Storage
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
}
