<?php

namespace FeelUnique\Context;

use Behat\Behat\Context\ClosuredContextInterface;
use Behat\Behat\Context\TranslatedContextInterface;
use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use FeelUnique\Ordering\Model\Product;
use FeelUnique\Ordering\Model\Category;
use FeelUnique\Ordering\Model\Order;

use FeelUnique\Ordering\Offer\OfferContainer;
use FeelUnique\Ordering\Offer\OfferChecker;
use FeelUnique\Ordering\Offer\OfferProcessor;
use FeelUnique\Ordering\OrderProcessor\OrderProcessor;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Order features context.
 *
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class OrderContext extends BehatContext
{
    /**
     * @var boolean[]
     */
    protected $offersAvailable = [
        '3 for the price of 2' => false,
        "Buy Shampoo & get Conditioner for 50% off" => false,
    ];

    /**
     * @var Product[]
     */
    protected $products = [];

    /**
     * @var integer
     */
    protected $unprocessedOrderTotal = 0;

    /**
     * @var OrderProcessor
     */
    protected $orderProcessor;

    /**
     * @var Order
     */
    protected $order;

    private function createOrderProcessor()
    {
        $offerConfig = [];

        foreach ($this->offersAvailable as $offerName => $isActive) {
            $offerConfig[] = [
                'offer' => $offerName,
                'active' => $isActive
            ];
        }

        $offerContainer = new OfferContainer($offerConfig);
        $offerChecker = new OfferChecker($offerContainer);
        $offerProcessor = new OfferProcessor($offerContainer, $offerChecker);
        $this->orderProcessor = new OrderProcessor($offerProcessor);
    }

    /**
     * @Given /^the "([^"]*)" offer is enabled$/
     */
    public function theOfferIsEnabled($offer)
    {
        foreach ($this->offersAvailable as $offerName => $isActive) {
            $this->offersAvailable[$offerName] = false;
        }

        $this->offersAvailable[$offer] = true;
        $this->createOrderProcessor();
    }

    /**
     * @When /^the following products are put on the order$/
     */
    public function theFollowingProductsArePutOnTheOrder(TableNode $productsTable)
    {
        $order = new Order();
        $products = [];
        $categories = [];

        foreach ($productsTable->getHash() as $productHash) {
            $product = new Product();
            $product
                ->setTitle($productHash['Title'])
                ->setCategory(new Category($productHash['Category']))
                ->setPrice($productHash['Price'])
            ;

            $this->unprocessedOrderTotal += $productHash['Price'];

            $order->addProduct($product);

            $this->products[$productHash['Title']] = $product;
        }

        $this->orderProcessor->processOrder($order);
        $this->order = $order;
    }

    /**
     * @Then /^I should get the "([^"]*)" for free$/
     */
    public function iShouldGetTheForFree($productTitle)
    {
        $product = $this->products[$productTitle];
        $total = $this->order->calculateTotal()->getTotal();

        assertEquals(-1 * $product->getPrice(), $this->order->getAdjustmentsTotal());
    }

    /**
     * @Given /^the order total should be "([^"]*)"$/
     */
    public function theOrderTotalShouldBe($total)
    {
        assertEquals((float) $total, $this->order->calculateTotal()->getTotal());
    }

    /**
     * @Given /^the "([^"]*)" offer is disabled$/
     */
    public function theOfferIsDisabled($offer)
    {
        $this->offersAvailable[$offer] = false;

        $this->createOrderProcessor();
    }

    /**
     * @Then /^I should not get anything for free$/
     */
    public function iShouldNotGetAnythingForFree()
    {
        assertEquals(0, $this->order->getAdjustmentsTotal());
    }

    /**
     * @Then /^I should get a (\d+)% discount on "([^"]*)"$/
     */
    public function iShouldGetADiscountOn($percentageReduction, $productTitle)
    {
        $product = $this->products[$productTitle];

        $this->order->calculateTotal();

        $total = $this->order->getTotal();

        $reducedPrice = $product->getPrice() * ($percentageReduction / 100);
        $reduction = $reducedPrice - $product->getPrice();

        assertEquals($reduction, $this->order->getAdjustmentsTotal());
    }
}
