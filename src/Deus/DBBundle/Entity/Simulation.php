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
     * @ORM\Column(type="string", nullable=true)
     */
    private $boxlen;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $npart;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Supercomputer")
     */
    private $Supercomputer;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     */
    private $Project;

    /**
     * @ORM\ManyToOne(targetEntity="Cosmology")
     */
    private $Cosmology;

    private $baseDirectory;


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
     * Set boxlen
     *
     * @param string $boxlen
     * @return Simulation
     */
    public function setBoxlen($boxlen)
    {
        $this->boxlen = $boxlen;

        return $this;
    }

    /**
     * Get boxlen
     *
     * @return string 
     */
    public function getBoxlen()
    {
        return $this->boxlen;
    }

    /**
     * Set Supercomputer
     *
     * @param \Deus\DBBundle\Entity\Supercomputer $supercomputer
     * @return Simulation
     */
    public function setSupercomputer(\Deus\DBBundle\Entity\Supercomputer $supercomputer = null)
    {
        $this->Supercomputer = $supercomputer;

        return $this;
    }

    /**
     * Get Supercomputer
     *
     * @return \Deus\DBBundle\Entity\Supercomputer 
     */
    public function getSupercomputer()
    {
        return $this->Supercomputer;
    }

    /**
     * Set Project
     *
     * @param \Deus\DBBundle\Entity\Project $project
     * @return Simulation
     */
    public function setProject(\Deus\DBBundle\Entity\Project $project = null)
    {
        $this->Project = $project;

        return $this;
    }

    /**
     * Get Project
     *
     * @return \Deus\DBBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->Project;
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
     * Set npart
     *
     * @param integer $npart
     * @return Simulation
     */
    public function setNpart($npart)
    {
        $this->npart = $npart;

        return $this;
    }

    /**
     * Get npart
     *
     * @return integer 
     */
    public function getNpart()
    {
        return $this->npart;
    }

    public function __toString()
    {
        return 'boxlen'.$this->boxlen.'_n'.$this->getNpart().'_'.$this->getCosmology()->getName();
    }

    /**
     * @return mixed
     */
    public function getBaseDirectory()
    {
        return $this->baseDirectory;
    }

    /**
     * @param mixed $baseDirectory
     */
    public function setBaseDirectory($baseDirectory)
    {
        $this->baseDirectory = $baseDirectory;
    }
}
