<?php

namespace FeelUnique\Ordering\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use FeelUnique\Ordering\Importer\XmlImporter;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class OrderCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('process:xml')
            ->setDescription('Process an order from XML data')
            ->addArgument('file', InputArgument::OPTIONAL, 'The path to the XML file')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * The service container.
         *
         * @var Symfony\Component\DependencyInjection\ContainerInterface
         */
        $container = $this->getApplication()->getContainer();

        $pathToXmlFile = $input->getArgument('file');

        if (!$pathToXmlFile) {
            $dialog = $this->getHelperSet()->get('dialog');

            $pathToXmlFile = $dialog->ask(
                $output,
                'Please specify the XML file to import: ',
                false,
                ['src/Resources/data/order.xml', 'order.xml']
            );
        }

        $output->writeln(
            $container->get('filesystem')->exists(getcwd() . $pathToXmlFile)
                ? sprintf('<error>The XML file "%s" could not be found</error>', $pathToXmlFile)
                : sprintf('<info>Reading XML file "%s"</info>', $pathToXmlFile)
        );

        $xmlImporter = $container->get('xml_importer');
        $orderProcessor = $container->get('order_processor');

        $order = $xmlImporter->import($pathToXmlFile);

        $orderProcessor->processOrder($order);

        $output->writeln(sprintf(
            "Order total: £ %s",
            $order->calculateTotal()->getTotal()
        ));
    }
}
