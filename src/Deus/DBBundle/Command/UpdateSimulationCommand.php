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

class UpdateSimulationCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDescription('Update Simulation to fix particle mass')
            ->setName('deusdb:import:updateSimulation');
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
        $output->writeln("Start updating simulation");

        $em = $this->getContainer()
            ->get("doctrine.orm.default_entity_manager");

        $objectGroups = $em
            ->getRepository("DeusDBBundle:Simulation")
            ->findAll();

        $nbGroups = count($objectGroups);
        $output->writeln("Found " . $nbGroups . " to update");

        $i = 0;

        foreach ($objectGroups as $oneGroup) {

            $output->writeln("Update " . $oneGroup . "...");

            try {

                $oneGroup->calculateParticleMass();

                $i++;
                $em->flush();
                $output->writeln($i * 100 / $nbGroups . "%");

            } catch (\Exception $e) {
                $output->writeln("ERROR: " . $e->getMessage());
                $output->writeln($e->getTraceAsString());
            }
        }
        $em->flush();

        $output->writeln("done");
    }
} 
