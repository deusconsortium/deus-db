<?php
/**
 * Created by PhpStorm.
 * User: jpasdeloup
 * Date: 13/05/15
 * Time: 15:10
 */

namespace Deus\DBBundle\Command;


use Deus\DBBundle\Model\IndexDirectory;
use Deus\DBBundle\Model\IndexSimulation;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class ImportSimulationIndexCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->addArgument("path", InputArgument::REQUIRED, "Path of the index simulation to add")
            ->addArgument("rules", InputArgument::OPTIONAL, "Custom rules if needed", "default")
            ->setDescription('Import a new simulation from Index')
            ->setHelp("Import a new simulation from Index")
            ->setName('deusdb:import_index')
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
        $path = $input->getArgument("path");
        $rules = $input->getArgument("rules");
        $importerService = $this->getContainer()->get('deus.index_importer');

        if(file_exists($path.'/index.txt')) { // Index.txt exist, direct simulation link
            $output->writeln("Importing single simulation");
            $importerService->importSimulationFromIndex($path, $rules);
        }
        else {
            $dirs = new Finder();
            $output->writeln("Importing multiple simulations");
            foreach($dirs->in($path)->depth(0)->directories() as $oneSimDir) {
                $importerService->importSimulationFromIndex($oneSimDir->getRealpath(), $rules);
            }
        }

        $output->writeln("done");
    }
} 