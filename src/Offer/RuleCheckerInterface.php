<?php

namespace FeelUnique\Ordering\Offer;

use FeelUnique\Ordering\Model\Order;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
interface RuleCheckerInterface
{
    /**
     * @param Order $order
     * @param array $configuration
     * @return boolean
     */
    public function isEligible(Order $order, array $configuration);
}
