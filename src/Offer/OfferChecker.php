<?php

namespace FeelUnique\Ordering\Offer;

use FeelUnique\Ordering\Model\Order;
use FeelUnique\Ordering\Model\ProductOffer;
use FeelUnique\Ordering\Model\Rule;

/**
 *
 */
class OfferChecker
{
    /**
     * @var OfferContainer
     */
    protected $offerContainer;

    /**
     * @param OfferContainer $offerContainer
     */
    public function __construct(OfferContainer $offerContainer)
    {
        $this->offerContainer = $offerContainer;
        // $this->dispatcher = $dispatcher;
    }

    public function createRuleChecker($type)
    {
        switch ($type) {
            case Rule::PRODUCT_COUNT_RULE:
                return new CountRuleChecker();
            case Rule::PRODUCT_CATEGORY_COMBINATION_RULE:
                return new CategoryCombinationRuleChecker();
        }
    }

    /**
     *
     */
    public function isEligible(Order $order, ProductOffer $offer)
    {
        if (null !== $usageLimit = $offer->getUsageLimit()) {
            if ($offer->getUsed() >= $usageLimit) {
                return false;
            }
        }

        foreach ($offer->getRules() as $rule) {
            // if (! (
            //     $rule->getType() === Rule::PRODUCT_COUNT_RULE &&
            //     $numberOfEligibleProducts = floor($order->getOfferSubjectProductCount() / $offer->getUsageLimit())
            // )) {
            //     return false;
            // }

            $checker = $this->createRuleChecker($rule->getType());

            if (false === $checker->isEligible($order, $rule->getConfiguration())) {
                return false;
            }
        }

        return true;
    }
}
