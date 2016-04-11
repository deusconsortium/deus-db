<?php

namespace Acme\HelloBundle\DataFixtures\ORM;

use Deus\DBBundle\Entity\Cosmology;
use Deus\DBBundle\Entity\GeometryType;
use Deus\DBBundle\Entity\Location;
use Deus\DBBundle\Entity\ObjectFormat;
use Deus\DBBundle\Entity\ObjectType;
use Deus\DBBundle\Entity\Storage;
use Deus\DBBundle\Entity\User;
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
        $this->createBasic('Storage', Storage::TGCC_CURIE_STOREDIR, 'TGCC / curie / storedir', ["path" => '/ccc/store/cont003/gen2287/alimij/', "location" => $tgcc]);
        $this->createBasic('Storage', Storage::IDRIS_ERGON_STOREDIR, 'IDRIS / ergon / storedir', ["path" => '/linkhome/rech/acr/racr009/', "location" => $idris]);

        $this->createBasic('ObjectType', ObjectType::STRCT, 'Halo particles');
        $this->createBasic('ObjectType', ObjectType::MASST, 'Halo positions');
        $this->createBasic('ObjectType', ObjectType::CUBE, 'Particles');
        $this->createBasic('ObjectType', ObjectType::GRAV, 'Grid');

        $this->createBasic('ObjectFormat', ObjectFormat::FOF, 'FOF');
        $this->createBasic('ObjectFormat', ObjectFormat::MULTI, 'FOF Multi Tar');
        $this->createBasic('ObjectFormat', ObjectFormat::RAMSES, 'Raw Ramses Tar');
        $this->createBasic('ObjectFormat', ObjectFormat::HDF5, 'HDF5');

        $this->createBasic('GeometryType', GeometryType::SNAPSHOT, 'Comoving Space');
        $this->createBasic('GeometryType', GeometryType::CONE, 'Redshift Space');
        $this->createBasic('GeometryType', GeometryType::SAMPLE, 'Sample');

        $this->createAdminUser("jpasdeloup","jean.pasdeloup@obspm.fr","welcome");
        $this->createAdminUser("alimi","jean-michel.alimi@obspm.fr@obspm.fr","welcome");
        $this->createAdminUser("corasaniti","pier-stefano.corasaniti@obspm.fr","welcome");
        $this->createAdminUser("yrasera","yann.rasera@obspm.fr","welcome");

        $this->createCosmology('lcdmw5', [
            'omega_m' => 0.259999990463257E+00,
            'omega_l' => 0.740000009536743E+00,
            'omega_k' => 0.000000000000000E+00,
            'omega_b' => 0.000000000000000E+00,
            'H0' => 0.720000000000000E+02
        ]);
        $this->createCosmology('rpcdmw5', [
            'omega_m' => 0.230000004172325E+00,
            'omega_l' => 0.769999980926514E+00,
            'omega_k' => 0.149011611938477E-07,
            'omega_b' => 0.0000000000000000E+00,
            'H0' => 0.720000000000000E+02
        ]);
        $this->createCosmology('sucdmw5', [
            'omega_m' => 0.250000000000000E+00,
            'omega_l' => 0.750000000000000E+00,
            'omega_k' => 0.000000000000000E+00,
            'omega_b' => 0.000000000000000E+00,
            'H0' => 0.720000000000000E+02
        ]);
        $this->createCosmology('lcdmw7', [
            'omega_m' => 0.257299989461899E+00,
            'omega_l' => 0.742699980735779E+00,
            'omega_k' => 0.298023223876953E-07,
            'omega_b' => 0.000000000000000E+00,
            'H0' => 0.720000000000000E+02
        ]);
        $this->createCosmology('rpcdmw7', [
            'omega_m' => 0.230000004172325E+00,
            'omega_l' => 0.769999980926514E+00,
            'omega_k' => 0.149011611938477E-07,
            'omega_b' => 0.000000000000000E+00,
            'H0' => 0.720000000000000E+02
        ]);
        $this->createCosmology('wcdmw7', [
            'omega_m' => 0.275000005960464E+00,
            'omega_l' => 0.725000023841858E+00,
            'omega_k' => -0.298023223876953E-07,
            'omega_b' => 0.000000000000000E+00,
            'H0' => 0.720000000000000+02
        ]);

        $manager->flush();
    }

    protected function createAdminUser($username, $email, $password)
    {
        $user = new User();
        $user
            ->setUsername($username)
            ->setEmail($email)
            ->setPlainPassword($password)
            ->setEnabled(true)
            ->setRoles(["ROLE_ADMIN"]);
        $this->manager->persist($user);
    }

    protected function createBasic($type, $id, $name, $parameters = [])
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

    protected function createBasicValue($type, $name, $parameters = [])
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

    protected function createCosmology($name, $properties)
    {
        $item = new Cosmology();
        $item
            ->setName($name)
            ->setProperties($properties);

        $this->manager->persist($item);
        return $item;
    }
} 