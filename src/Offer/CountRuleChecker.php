<?php

namespace FeelUnique\Ordering\Offer;

use FeelUnique\Ordering\Model\Order;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class CountRuleChecker implements RuleCheckerInterface
{
    /**
     * {@inheritDoc}
     */
    public function isEligible(Order $order, array $configuration)
    {
        return $order->getOfferSubjectProductCount() >= $configuration['count'];
    }
}
