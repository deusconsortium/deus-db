<?php
/**
 * Created by PhpStorm.
 * User: jpasdeloup
 * Date: 13/05/15
 * Time: 15:10
 */

namespace Deus\DBBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportSimulationArchiveCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->addArgument("path", InputArgument::REQUIRED, "Path of the archive simulation to add")
            ->addArgument("storage", InputArgument::REQUIRED, "Id of the storage to use")
            ->addArgument("format", InputArgument::REQUIRED, "Id of the format to use")
            ->setDescription('Import a new simulation via archive dir')
            ->setHelp("Import a new simulation")
            ->setName('deusdb:import:archive')
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
        $storage = $input->getArgument("storage");
        $file_format_id = $input->getArgument("format");

        $output->writeln("IMPORTING Data from ".$path." archive of storage $storage ...");

        $this->getContainer()->get("deus.archiveexplorer")->exploreArchive($path, $storage, $file_format_id);

        $output->writeln("done");
    }
} 