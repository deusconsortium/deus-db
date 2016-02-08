<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 08/02/16
 * Time: 17:32
 */

namespace Deus\DBBundle\Service;

use Deus\DBBundle\Model\IndexDirectory;
use Deus\DBBundle\Model\IndexSimulation;
use Deus\DBBundle\Repository\SimulationRepository;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;


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

    /**
     * IndexImporterService constructor.
     * @InjectParams({
     *   "repository": @Inject("deus.sim_repository"),
     *   "filesParameters": @Inject("%deus_files%"),
     *   "conesParameters": @Inject("%deus_cones%"),
     * })
     * @param SimulationRepository $repository
     * @param $filesParameters
     * @param $conesParameters
     */
    public function __construct(SimulationRepository $repository, $filesParameters, $conesParameters)
    {
        $this->repository = $repository;
        $this->filesParameters = $filesParameters;
        $this->conesParameters = $conesParameters;
    }

    public function importSimulationFromIndex($path)
    {
        $IndexSimulation = new IndexSimulation($path);

        $IndexSimulation->setSimulation($this->repository->getSimulation(
            $IndexSimulation->getBoxlen(),
            $IndexSimulation->getCosmo(),
            $IndexSimulation->getResolution()));

        // Import different possible files
        foreach($this->filesParameters as $oneFileParameter)
        {
            $this->importOneConfigPattern($IndexSimulation, $oneFileParameter);
        }

//
//        $dirs = $IndexSimulation->getDirectories("post","output_*"); //
//
//        foreach($dirs as $oneDir) {
//
//            /**
//             * @var $oneDir \Symfony\Component\Finder\SplFileInfo
//             */
//            echo $oneDir->getFilename()."\n";
////            $IndexDirectory = new IndexDirectory($oneDir->getRealPath());
////            dump(count($IndexDirectory->getFiles()));
//        }
    }

    public function importOneConfigPattern(IndexSimulation $IndexSimulation, $parameter)
    {
        $path = IndexImporterService::patternReplace($IndexSimulation, $parameter['path']);
        $dir_pattern = IndexImporterService::patternReplace($IndexSimulation, $parameter['dir_pattern']);
        $file_pattern = IndexImporterService::patternReplace($IndexSimulation, $parameter['file_pattern']);

        dump([
            $path, $dir_pattern, $file_pattern
        ]);

        $dirs = $IndexSimulation->getDirectories($path,$dir_pattern);

        foreach($dirs as $oneDir) {
            /**
             * @var $oneDir \Symfony\Component\Finder\SplFileInfo
             */
            echo $oneDir->getFilename()."\n";
            $IndexDirectory = new IndexDirectory($oneDir->getRealPath());
            list($totalNb, $totalSize) = $IndexDirectory->totalFromPattern($file_pattern);
            if($totalNb > 0) {
                if("snapshot" == $parameter['geometry_type']) {
                    $geometry = $this->repository->getSnapshot($IndexSimulation->setSimulation());
                }
                elseif("cone" == $parameter['geometry_type']) {
                    $geometry = $this->repository->getCone($IndexSimulation->setSimulation(), $oneDir->getRealPath());
                }

            }
        }
    }

    protected static function patternReplace(IndexSimulation $IndexSimuation, $string)
    {
        $string = str_replace(
            ["<cosmo>","<boxlen>","<npart>"],
            [$IndexSimuation->getCosmo(), $IndexSimuation->getBoxlen(), $IndexSimuation->getResolution()],
            $string);
        return $string;
    }

}