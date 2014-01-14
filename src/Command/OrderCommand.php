<?php

namespace FeelUnique\Ordering\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

/*
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class OrderCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('process:xml')
            ->setDescription('Process an order from XML data')
            ->addArgument('file', InputArgument::OPTIONAL, 'The path to the XML file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pathToXmlFile = $input->getArgument('file');

        $fs = new Filesystem();

        if (!$fs->exists($pathToXmlFile)) {
            $output->writeln(sprintf('<error>The XML file "%s" could not be found</error>', $pathToXmlFile));
        }
    }
}
