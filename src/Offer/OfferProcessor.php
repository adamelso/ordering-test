<?php

namespace FeelUnique\Ordering\Offer;

use FeelUnique\Ordering\Model\Order;
use FeelUnique\Ordering\Model\ProductOffer;
use FeelUnique\Ordering\Model\PriceAdjustment;

/**
 * Processes all active offers by checking rules and applying configured actions if rules are eligible.
 */
class OfferProcessor
{
    public function createPriceAdjustment(ProductOffer $product, $amount)
    {
        $priceAdjustment = new PriceAdjustment();

        foreach ($product->getActions() as $action) {
            switch ($action['type']) {
                case ProductOffer::PERCENTAGE_DISCOUNT_ACTION:
                    $ratio = $action['amount'] / 100;
                    $priceAdjustment->setAmount($amount * -1 * $ratio);
                    break;
            }
        }

        return $priceAdjustment;
    }
}