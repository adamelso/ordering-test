<?php

namespace FeelUnique\Ordering\Features;

use Behat\Behat\Context\ClosuredContextInterface;
use Behat\Behat\Context\TranslatedContextInterface;
use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use FeelUnique\Ordering\Model\Product;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

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
    private $offersAvailable = array(
        '3 for the price of 2' => false,
        "Buy Shampoo & get Conditioner for 50% off" => false,
    );

    private $products = array();

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
    }

    /**
     * @Given /^the "([^"]*)" offer is enabled$/
     */
    public function theOfferIsEnabled($offer)
    {
        throw new PendingException();

        $this->offersAvailable[$offer] = true;
    }

    /**
     * @When /^the following products are put on the order$/
     */
    public function theFollowingProductsArePutOnTheOrder(TableNode $productsTable)
    {
        throw new PendingException();

        $products = array();
        $categories = array();

        foreach ($productsTable->getHash() as $productHash) {
            // $product = new Product();
        }

        $promotions = $this->offersAvailable['3 for the price of 2'];
    }

    /**
     * @Then /^I should get the "([^"]*)" for free$/
     */
    public function iShouldGetTheForFree($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given /^the order total should be "([^"]*)"$/
     */
    public function theOrderTotalShouldBe($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given /^the "([^"]*)" offer is disabled$/
     */
    public function theOfferIsDisabled($offer)
    {
        $this->offersAvailable[$offer] = false;
    }

    /**
     * @Then /^I should not get anything for free$/
     */
    public function iShouldNotGetAnythingForFree()
    {
        throw new PendingException();
    }

    /**
     * @Then /^I should get a (\d+)% discount on "([^"]*)"$/
     */
    public function iShouldGetADiscountOn($arg1, $arg2)
    {
        throw new PendingException();
    }
}
