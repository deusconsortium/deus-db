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
                'filePattern' => "",
'Storage' => ['meudon_efiler_data1','meudon_data_bingo']
            ]);

        $nbGroups = count($objectGroups);
        $output->writeln("Found ".$nbGroups." to update");

        $i = 0;

        foreach($objectGroups as $oneGroup) {

	$output->writeln("Update ".$oneGroup."...");

            try {
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
else {
   $output->writeln("FilePattern error: found ".count($files)." with pattern ".$pattern." instead of ".$oneGroup->getNbFiles());
}
            $i++;
           $em->flush();
           $output->writeln($i*100/$nbGroups . "%");

}
catch(\Exception $e) {
   $output->writeln("ERROR: ".$e->getMessage());
   $output->writeln($e->getTraceAsString());
}
        }
        $em->flush();

        $output->writeln("done");
    }
} 
