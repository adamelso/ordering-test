<?php

namespace FeelUnique\Ordering\Importer;

use FeelUnique\Ordering\Model\Order;
use FeelUnique\Ordering\Model\Category;
use FeelUnique\Ordering\Model\Product;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class XmlImporter implements ImporterInterface
{
    /**
     * @var \SimpleXMLElement
     */
    protected $orderData;

    /**
     * @return \SimpleXMLElement
     */
    public function getXmlOrderData()
    {
        if (!$this->orderData) {
            throw new \RuntimeException('No XML data has been imported. XmlImporter::import($xmlFile) must be called first.');
        }

        return $this->orderData;
    }

    /**
     * @param resource $xmlFile  The path to the XML file
     * @return Order
     */
    public function import($xmlFile)
    {
        $order = new Order();

        $this->orderData = simplexml_load_file($xmlFile);

        $categories = array();

        foreach ($this->orderData->products[0] as $productData) {
            $categoryName = strtolower((string) $productData->category);

            if (!array_key_exists($categoryName, $categories)) {
                $category = new Category();
                $category->setName($categoryName);

                $categories[$categoryName] = $category;
            } else {
                $category = $categories[$categoryName];
            }

            $product = new Product();

            $productAttributes = $productData->attributes();

            $product
                ->setTitle($productAttributes['title'])
                ->setCategory($category)
                ->setPrice($productAttributes['price'])
            ;

            $order->addProduct($product);
        }

        return $order;
    }
}
