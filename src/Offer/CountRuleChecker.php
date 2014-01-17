<?php

namespace FeelUnique\Ordering\Offer;

use FeelUnique\Ordering\Model\Order;

/**
 *
 */
class CountRuleChecker implements RuleCheckerInterface
{
    public function isEligible(Order $order, array $configuration)
    {
        return $order->getOfferSubjectProductCount() >= $configuration['count'];
    }
}
