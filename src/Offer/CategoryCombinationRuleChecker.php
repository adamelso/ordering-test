<?php

namespace FeelUnique\Ordering\Offer;

use FeelUnique\Ordering\Model\Order;
use FeelUnique\Ordering\Model\Category;

/**
 *
 */
class CategoryCombinationRuleChecker implements RuleCheckerInterface
{
    public function isEligible(Order $order, array $configuration)
    {
        return $order->getOfferSubjectProductCount(
            new Category($configuration['qualifier'])
        ) && $order->getOfferSubjectProductCount(
            new Category($configuration['discountable'])
        );
    }
}
