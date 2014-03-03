<?php

namespace FeelUnique\Context;

use Behat\Behat\Context\ClosuredContextInterface;
use Behat\Behat\Context\TranslatedContextInterface;
use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Order features context.
 *
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class FeatureContext extends BehatContext
{
    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
        $this->useContext('order', new OrderContext());
    }
}
