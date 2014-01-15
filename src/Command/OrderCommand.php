<?php

namespace FeelUnique\Ordering\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

use FeelUnique\Ordering\OrderProcessor\XmlImporter;

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

        if (!$pathToXmlFile) {
            $dialog = $this->getHelperSet()->get('dialog');

            $pathToXmlFile = $dialog->ask(
                $output,
                'Please specify the XML file to import: ',
                false,
                array('src/Resources/data/order.xml', 'order.xml')
            );
        }

        $output->writeln(
            $fs->exists(getcwd() . $pathToXmlFile)
                ? sprintf('<error>The XML file "%s" could not be found</error>', $pathToXmlFile)
                : sprintf('<info>Reading XML file "%s"</info>', $pathToXmlFile)
        );

        $xmlImporter = $this->getApplication()->getContainer()->get('xml_importer');

        $order = $xmlImporter->import($pathToXmlFile);

        $output->writeln(
            $order->calculateTotal()->getTotal()
        );
    }
}
