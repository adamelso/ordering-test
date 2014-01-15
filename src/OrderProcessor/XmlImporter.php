<?php

namespace FeelUnique\Ordering\OrderProcessor;

use FeelUnique\Ordering\Model\Order;
use FeelUnique\Ordering\Model\Category;
use FeelUnique\Ordering\Model\Product;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class XmlImporter
{
    /**
     * @var \SimpleXMLElement
     */
    protected $orderData;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @param resource $xmlFile  The path to the XML file
     */
    public function __construct($xmlFile)
    {
        $this->order = new Order();
        $this->orderData = simplexml_load_file($xmlFile);
    }

    /**
     * @return Order
     */
    public function import()
    {
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

            $this->order->addProduct($product);
        }

        return $this->order;
    }
}
