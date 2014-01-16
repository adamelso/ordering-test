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
    protected $offerContainer;

    public function __construct(OfferContainer $offerContainer)
    {
        $this->offerContainer = $offerContainer;
    }


    public function getActiveOffers()
    {
        return $this->offerContainer;
    }

    public function process(Order $order)
    {
        foreach ($this->offerContainer as $offer) {
            foreach ($offer->getRules() as $rule) {
                if (
                    $rule->getType() === ProductOffer::PRODUCT_COUNT_RULE &&
                    $numberOfEligibleProducts = floor($order->getOfferSubjectProductCount() / $rule->getProductCount())
                ) {
                    foreach ($order->getCheapestProducts($numberOfEligibleProducts) as $product) {
                        $priceAdjustment = $this->createPriceAdjustment($offer->getActions(), $product->getAmount());

                        $order->addAdjustment($priceAdjustment);
                    }
                }
            }
        }
    }

    public function createPriceAdjustment(array $actions, $amount)
    {
        $priceAdjustment = new PriceAdjustment();

        foreach ($actions as $action) {
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