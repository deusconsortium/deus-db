<?php
namespace Deus\DBBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class Supercomputer
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $endianness;


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
     * @return Supercomputer
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
     * Set endianness
     *
     * @param string $endianness
     * @return Supercomputer
     */
    public function setEndianness($endianness)
    {
        $this->endianness = $endianness;

        return $this;
    }

    /**
     * Get endianness
     *
     * @return string 
     */
    public function getEndianness()
    {
        return $this->endianness;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
