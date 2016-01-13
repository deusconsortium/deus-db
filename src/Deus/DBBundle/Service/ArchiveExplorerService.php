<?php

namespace Deus\DBBundle\Service;

/**
 * Created by PhpStorm.
 * User: jpasdeloup
 * Date: 13/05/15
 * Time: 15:22
 */

use Deus\DBBundle\Entity\Geometry;
use Deus\DBBundle\Entity\GeometryType;
use Deus\DBBundle\Entity\ObjectFormat;
use Deus\DBBundle\Entity\ObjectType;
use Deus\DBBundle\Entity\ObjectGroup;
use Deus\DBBundle\Entity\Simulation;
use Deus\DBBundle\Entity\Storage;
use Deus\DBBundle\Manager\DeusArchiveManager;
use Deus\DBBundle\Manager\DeusFileManager;
use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use Psr\Log\LoggerInterface;
use Symfony\Component\Debug\Exception\ContextErrorException;

/**
 * Class FileManagerService
 * @Service("deus.archiveexplorer")
 */
class ArchiveExplorerService extends FileExplorerService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var LoggerInterface
     */
    private $logger;

    private $objectFormat;

    /**
     * @param EntityManagerInterface $em
     * @InjectParams({
            "em": @Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function exploreArchive($path, $storage_id, $file_format_id = 'fof')
    {
        try {
            $simulationFiles = new DeusArchiveManager($path);
        }
        catch(ContextErrorException $error) {
            die($error->getMessage());
            $this->logger->error("DeusFileManager Error", (array) $error);
            //dump($error);
            throw new \Exception("DeusFileManager Error");
        }

        $this->objectFormat = $file_format_id;
        $storage = $this->getStorage($storage_id);
        if(!$storage) {
            throw new \Exception("Storage not found for ".$storage_id );
        }
        $this->saveData($simulationFiles);
    }

    /**
     * @param $path
     * @return \Deus\DBBundle\Entity\Storage|null
     */
    protected function getStorage($storage_id)
    {
        $storage = $this->em->getRepository("DeusDBBundle:Storage")->find($storage_id);

        return $storage;
    }
}
