<?php

namespace FeelUnique\Ordering\Offer;

use FeelUnique\Ordering\Model\Order;
use FeelUnique\Ordering\Model\Rule;
use FeelUnique\Ordering\Model\ProductOffer;
use FeelUnique\Ordering\Model\PriceAdjustment;

/**
 * Processes all active offers by checking rules and applying configured actions if rules are eligible.
 */
class OfferProcessor
{
    protected $offerContainer;
    protected $offerChecker;

    public function __construct(OfferContainer $offerContainer, OfferChecker $offerChecker)
    {
        $this->offerContainer = $offerContainer;
        $this->offerChecker = $offerChecker;
    }


    public function getActiveOffers()
    {
        return $this->offerContainer;
    }

    public function applyOffer($order, $offer)
    {
            $numberOfEligibleProducts = floor($order->getOfferSubjectProductCount() / $offer->getUsageLimit());

            foreach ($order->getCheapestProducts($numberOfEligibleProducts) as $product) {
                $priceAdjustment = $this->createPriceAdjustment($offer->getActions(), $product->getPrice());

                $order->addAdjustment($priceAdjustment);
            }
    }

    public function process(Order $order)
    {
        foreach ($this->offerContainer as $offer) {
            if ($this->offerChecker->isEligible($order, $offer)) {
                $this->applyOffer($order, $offer);
            }
        }
    }

    public function createPriceAdjustment($actions, $amount)
    {
        $priceAdjustment = new PriceAdjustment();

        foreach ($actions as $action) {
            switch ($action->getType()) {
                case ProductOffer::PERCENTAGE_DISCOUNT_ACTION:
                    $ratio = $action->getAmount() / 100;
                    $priceAdjustment->setAmount($amount * -1 * $ratio);
                    break;
            }
        }

        return $priceAdjustment;
    }
}