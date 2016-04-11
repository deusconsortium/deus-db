<?php
/**
 * Created by PhpStorm.
 * User: jpasdeloup
 * Date: 13/05/15
 * Time: 15:10
 */

namespace Deus\DBBundle\Command;


use Deus\DBBundle\Entity\Location;
use Deus\DBBundle\Model\IndexDirectory;
use Deus\DBBundle\Model\IndexSimulation;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class PublishSimulationCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->addArgument("boxlen", InputArgument::REQUIRED, 'Boxlen ("648","21000", ...)')
            ->addArgument("npart", InputArgument::REQUIRED, 'Npart ("1024","2048", ...)')
            ->addArgument("cosmo", InputArgument::REQUIRED, 'Cosmology ("lcdmw5","rpcdmw5", ...)')
            ->setDescription('Publish an imported simulation')
            ->setHelp("Publish an imported simulation")
            ->setName('deusdb:publish_simulation')
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
        $boxlen = $input->getArgument("boxlen");
        $npart = $input->getArgument("npart");
        $cosmo = $input->getArgument("cosmo");

        $em = $this->getContainer()->get("doctrine.orm.entity_manager");
        $simuRepository = $this->getContainer()->get("deus.sim_repository");

        $simulation = $simuRepository->getSimulation($boxlen, $cosmo, $npart, false);
        if($simulation) {
            $simulation->setPublic(true);
            foreach($simulation->getGeometries() as $oneGeometry) {
                foreach($oneGeometry->getObjectGroups() as $oneFile) {
                    if(Location::MEUDON === $oneFile->getStorage()->getLocation()->getId()) {
                        $oneFile->setPublic(true);
                    }
                }
            }
            $em->flush();
            $output->writeln("done");
        }
        else {
            $output->writeln("not found");
        }


    }
} 
