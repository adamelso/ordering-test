<?php

namespace FeelUnique\Ordering\Tests\OrderProcessor;

use FeelUnique\Ordering\OrderProcessor\XmlImporter;

class XmlImporterTest extends \PHPUnit_Framework_TestCase
{
    private $xmlImporter;

    public function setUp()
    {
        $this->xmlImporter = new XmlImporter(__DIR__.'/../../Resources/data/order.xml');
    }

    public function testImport()
    {
        $order = $this->xmlImporter->import();

        $order->calculateTotal();

        $this->assertEquals(9.98, $order->getTotal());
    }
}
