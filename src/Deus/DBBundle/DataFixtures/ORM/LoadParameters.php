<?php

namespace Acme\HelloBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadParameters implements FixtureInterface
{
    protected $manager;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->createBasic('Project','DEUSS');
        $this->createBasic('Project','DEUS FUR');
        $this->createBasic('Project','DEUS PUR');

        $this->createBasic('Cosmology','lcdmw5');
        $this->createBasic('Cosmology','rpcdmw5');
        $this->createBasic('Cosmology','wcdmw5');
        $this->createBasic('Cosmology','sucdmw5');

        $this->createBasic('Supercomputer','Curie', ['endianness' => 'little']);
        $this->createBasic('Supercomputer','Turing', ['endianness' => 'little']);
        $this->createBasic('Supercomputer','Babel', ['endianness' => 'big']);

        $this->createBasic('Storage','efiler1');
        $this->createBasic('Storage','efiler2');
        $this->createBasic('Storage','data bingo');
        $this->createBasic('Storage','asisu');

        $this->createBasic('ObjectType','cube');
        $this->createBasic('ObjectType','cube grav');
        $this->createBasic('ObjectType','masst');
        $this->createBasic('ObjectType','strct');
        $this->createBasic('ObjectType','halo prop');
        $this->createBasic('ObjectType','cube prop');

        $this->createBasic('ObjectFormat','Fortran');
        $this->createBasic('ObjectFormat','Fortran multi');
        $this->createBasic('ObjectFormat','HDF5');
        $this->createBasic('ObjectFormat','ASCII');

        $manager->flush();
    }

    public function createBasic($type, $name, $parameters = [])
    {
        $type = "Deus\\DBBundle\\Entity\\".$type;
        $item = new $type();
        $item->setName($name);

        foreach($parameters as $key => $value) {
            $item->{'set'.ucfirst($key)}($value);
        }

        $this->manager->persist($item);
        return $item;
    }
} 