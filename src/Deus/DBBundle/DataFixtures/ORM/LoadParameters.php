<?php

namespace Acme\HelloBundle\DataFixtures\ORM;

use Deus\DBBundle\Entity\GeometryType;
use Deus\DBBundle\Entity\Location;
use Deus\DBBundle\Entity\ObjectFormat;
use Deus\DBBundle\Entity\ObjectType;
use Deus\DBBundle\Entity\Storage;
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

        $meudon = $this->createBasic('Location',Location::MEUDON, 'Meudon');
        $tgcc = $this->createBasic('Location',Location::TGCC, 'TGCC');
        $idris = $this->createBasic('Location',Location::IDRIS, 'Idris');

        $this->createBasic('Storage', Storage::MEUDON_EFILER_DATA1, 'Meudon / efiler / data1', ["path" => "/efiler1/", "location" => $meudon]);
        $this->createBasic('Storage', Storage::MEUDON_EFILER_DATA2, 'Meudon / efiler / data2', ["path" => "/efiler2/", "location" => $meudon]);
        $this->createBasic('Storage', Storage::MEUDON_BINGO_DATA, 'Meudon / bingo / data', ["path" => "/data_bingo/", "location" => $meudon]);
        $this->createBasic('Storage', Storage::MEUDON_ASISU_DEUS_DATA, 'Meudon / asisu / deus_data', ["path" => "/asisu/deus_data/", "location" => $meudon]);
        $this->createBasic('Storage', Storage::TGCC_CURIE_STOREDIR, 'TGCC / curie / storedir', ["path" => '$STOREDIR', "location" => $idris]);
        $this->createBasic('Storage', Storage::IDRIS_ERGON_STOREDIR, 'IDRIS / ergon / storedir', ["path" => '$STOREDIR', "location" => $tgcc]);

        $this->createBasic('ObjectType', ObjectType::STRCT, 'Halo particles');
        $this->createBasic('ObjectType', ObjectType::MASST, 'Halo positions');
        $this->createBasic('ObjectType', ObjectType::CUBE, 'Particles');
        $this->createBasic('ObjectType', ObjectType::GRAV, 'Grid');

        $this->createBasic('ObjectFormat', ObjectFormat::FOF, 'FOF');
        $this->createBasic('ObjectFormat', ObjectFormat::MULTI, 'FOF Multi Tar');
        $this->createBasic('ObjectFormat', ObjectFormat::RAMSES, 'Raw Ramses Tar');

        $this->createBasic('GeometryType', GeometryType::SNAPSHOT, 'Comoving Space');
        $this->createBasic('GeometryType', GeometryType::CONE, 'Redshift Space');
        $this->createBasic('GeometryType', GeometryType::SAMPLE, 'Sample');

        $manager->flush();
    }

    public function createBasic($type, $id, $name, $parameters = [])
    {
        $type = "Deus\\DBBundle\\Entity\\".$type;
        $item = new $type();
        $item->setName($name);
        $item->setId($id);

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