<?php

namespace FeelUnique\Ordering\OrderProcessor;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class XmlImporter
{
    /**
     * @var \SimpleXMLElement
     */
    protected $orderData;

    public function __construct($xmlFile)
    {
        $this->orderData = simplexml_load_file($xmlFile);
    }
}
