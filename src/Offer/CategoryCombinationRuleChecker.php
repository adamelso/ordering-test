<?php

namespace FeelUnique\Ordering\Offer;

use FeelUnique\Ordering\Model\Order;
use FeelUnique\Ordering\Model\Category;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class CategoryCombinationRuleChecker implements RuleCheckerInterface
{
    /**
     * {@inheritDoc}
     */
    public function isEligible(Order $order, array $configuration)
    {
        return $order->getOfferSubjectProductCount(
            $configuration['qualifier']
        ) && $order->getOfferSubjectProductCount(
            $configuration['discountable']
        );
    }
}
