<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 08/02/16
 * Time: 17:32
 */

namespace Deus\DBBundle\Service;

use Deus\DBBundle\Entity\Storage;
use Deus\DBBundle\Model\IndexDirectory;
use Deus\DBBundle\Model\IndexSimulation;
use Deus\DBBundle\Repository\SimulationRepository;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use Psr\Log\LoggerInterface;


/**
 * Class IndexImporterService
 * @package Deus\DBBundle\Service
 * @Service("deus.index_importer")
 */
class IndexImporterService
{
    private $repository;
    private $filesParameters;
    private $conesParameters;
    private $logger;

    /**
     * IndexImporterService constructor.
     * @InjectParams({
     *   "repository": @Inject("deus.sim_repository"),
     *   "filesParameters": @Inject("%deus_files%"),
     *   "conesParameters": @Inject("%deus_cones%")
     * })
     * @param SimulationRepository $repository
     * @param LoggerInterface $logger
     * @param $filesParameters
     * @param $conesParameters
     */
    public function __construct(SimulationRepository $repository, LoggerInterface $logger, $filesParameters, $conesParameters)
    {
        $this->repository = $repository;
        $this->filesParameters = $filesParameters;
        $this->conesParameters = $conesParameters;
        $this->logger = $logger;
    }

    public function importSimulationFromIndex($path)
    {
        $this->logger->notice("Import path", ["path" => $path]);
        $IndexSimulation = new IndexSimulation($path);

        $IndexSimulation->setSimulation($this->repository->getSimulation(
            $IndexSimulation->getBoxlen(),
            $IndexSimulation->getCosmo(),
            $IndexSimulation->getResolution()));

        $Storage = $this->repository->getStorage($IndexSimulation->getStorage());

        // Import different possible files
        foreach($this->filesParameters as $ruleName => $oneFileParameter) {
            $this->logger->info("import rule ", ["rule" => $ruleName]);
            $this->importOneConfigPattern($IndexSimulation, $oneFileParameter, $Storage);
        }

    }

    public function importOneConfigPattern(IndexSimulation $IndexSimulation, $parameter, Storage $Storage)
    {
        $path = IndexImporterService::patternReplace($IndexSimulation, $parameter['path']);
        $dirPattern = IndexImporterService::patternReplace($IndexSimulation, $parameter['dir_pattern']);
        $filePattern = IndexImporterService::patternReplace($IndexSimulation, $parameter['file_pattern']);

        $GeometryType = $this->repository->getGeometryType($parameter['geometry_type']);
        $ObjectType = $this->repository->getObjectType($parameter['file_type']);
        $ObjectFormat = $this->repository->getObjectFormat($parameter['file_format']);

        $dirs = $IndexSimulation->getDirectories($path, $dirPattern);

        foreach($dirs as $oneDir) {
            /**
             * @var $oneDir \Symfony\Component\Finder\SplFileInfo
             */

            $fullpath = IndexImporterService::cleanPath($IndexSimulation->getRootDir().DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR.$oneDir->getFilename());

            $code = IndexImporterService::extractCode($dirPattern, $oneDir->getFilename());
            if(!$code) {
                $this->logger->error("Can't find code",["pattern" => $dirPattern, "filename" => $oneDir->getFilename()]);
            }

            $IndexDirectory = new IndexDirectory($oneDir->getRealPath());
            list($totalNb, $totalSize, $totalGroups) = $IndexDirectory->totalFromPattern($filePattern);
            if($totalNb > 0) {
                $Geometry = $this->repository->getGeometry($IndexSimulation->getSimulation(), $code, $GeometryType);
                $ObjectGroup = $this->repository->getObjectGroup($Storage, $ObjectType, $ObjectFormat, $filePattern, $fullpath);
                $ObjectGroup
                    ->setSize($totalSize)
                    ->setNbFiles($totalNb)
                    ->setNbGroups($totalGroups)
                ;
                $Geometry->addObjectGroup($ObjectGroup);
            }
            else {
                $this->logger->debug("Directory found but files don't match", ["pattern" => $filePattern, "path" => $oneDir->getRealPath()]);
            }
        }

        $this->repository->flush();

    }

    protected static function patternReplace(IndexSimulation $IndexSimulation, $string)
    {
        $string = str_replace(
            ["<cosmo>","<boxlen>","<npart>"],
            [$IndexSimulation->getCosmo(), $IndexSimulation->getBoxlen(), $IndexSimulation->getResolution()],
            $string);
        return $string;
    }

    protected static function extractCode($pattern, $string)
    {
        preg_match($pattern, $string, $matches);
        if(isset($matches[1])) {
            return $matches[1];
        }
        else {
            return "";
        }
    }

    protected static function cleanPath($path) {
        $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = array();
        foreach ($parts as $part) {
            if ('.' == $part) continue;
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        if(0 == count($absolutes)) {
            return DIRECTORY_SEPARATOR;
        }
        else {
            return DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, $absolutes).DIRECTORY_SEPARATOR;
        }

    }
}