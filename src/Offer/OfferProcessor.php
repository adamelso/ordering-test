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
    protected $offers = array();

    public function __construct(array $offers = array())
    {
        foreach ($offers as $offer) {
            if ($offer['enabled']) {
                $this->offers[] = new ProductOffer($offer['offer']);
            }
        }
    }


    public function getActiveOffers()
    {
        return $this->offers;
    }

    public function process(Order $order)
    {
        foreach ($this->offers as $offer) {
            foreach ($offer->getRules() as $rule) {
                if (
                    $rule['type'] === ProductOffer::PRODUCT_COUNT_RULE &&
                    $numberOfEligibleProducts = floor($order->getOfferSubjectProductCount() / $rule['count'])
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