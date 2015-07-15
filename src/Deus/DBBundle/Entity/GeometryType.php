<?php
namespace Deus\DBBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity("id")
 */
class GeometryType
{
    const SNAPSHOT = "snapshot";
    const CONE = "cone";
    const SAMPLE = "sample";

    /**
     * @ORM\Id
     * @ORM\Column(type="string",length=8)
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;


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
     * @return ObjectType
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
        return $this->getName();
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
