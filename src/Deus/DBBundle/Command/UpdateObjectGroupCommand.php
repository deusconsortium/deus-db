<?php
/**
 * Created by PhpStorm.
 * User: jpasdeloup
 * Date: 13/05/15
 * Time: 15:10
 */

namespace Deus\DBBundle\Command;


use Deus\DBBundle\Manager\DeusFileManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class UpdateObjectGroupCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDescription('Update ObjectGroups to get FilePatterns + Simulation infos')
            ->setName('deusdb:import:updateObjectGroup')
        ;
    }

    /**
     * @see Command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start updating object groups");

        $em = $this->getContainer()
            ->get("doctrine.orm.default_entity_manager");

        $objectGroups = $em
            ->getRepository("DeusDBBundle:ObjectGroup")
            ->findBy([
                'filePattern' => ""
            ]);

        $output->writeln("Found ".count($objectGroups)." to update");

        $i = 0;

        foreach($objectGroups as $oneGroup) {
            $infos = DeusFileManager::findSnapshotInfos($oneGroup->getFullPath());
            $Geometry = $oneGroup->getGeometry();
            $Simulation = $Geometry->getSimulation();

            if(!$Geometry->getProperties() || $Geometry->getProperties() == []) {
                $Geometry->setProperties($infos['snapshot']);
            }
            if(!$Simulation->getProperties() || $Simulation->getProperties() == []) {
                $Simulation->setProperties($infos['simulation']);
            }

            $pattern = $oneGroup->calculateFilePattern();
            $finder = new Finder();
            $files = $finder->files()->in($oneGroup->getFullPath())->name($pattern);
            if(count($files) == $oneGroup->getNbFiles()) {
                $oneGroup->setFilePattern($pattern);
            }
            $i++;

            if($i % 20 == 0) {
                $em->flush();
            }
        }
        $em->flush();

        $output->writeln("done");
    }
} 