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

        $this->createBasic('Location','Meudon');
        $this->createBasic('Location','TGCC');
        $this->createBasic('Location','Idris');

        $this->createBasic('Storage','efiler1');
        $this->createBasic('Storage','efiler2');
        $this->createBasic('Storage','data_bingo');
        $this->createBasic('Storage','asisu');
        $this->createBasic('Storage','ergon');
        $this->createBasic('Storage','storedir');

        $this->createBasic('ObjectType','Halo particles');
        $this->createBasic('ObjectType','Halo positions');
        $this->createBasic('ObjectType','Particles');
        $this->createBasic('ObjectType','Grid');

        $this->createBasic('ObjectFormat','FOF');
        $this->createBasic('ObjectFormat','Simple Tar');
        $this->createBasic('ObjectFormat','Complex');

        $this->createBasic('GeometryType','Redshift Space');
        $this->createBasic('GeometryType','Comoving Space');
        $this->createBasic('GeometryType','Sample');

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

    public function createBasicValue($type, $name, $parameters = [])
    {
        $type = "Deus\\DBBundle\\Entity\\".$type;
        $item = new $type();
        $item->setValue($name);

        foreach($parameters as $key => $value) {
            $item->{'set'.ucfirst($key)}($value);
        }

        $this->manager->persist($item);
        return $item;
    }
} 