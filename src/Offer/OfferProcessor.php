<?php

namespace FeelUnique\Ordering\Offer;

use FeelUnique\Ordering\Model\Order;
use FeelUnique\Ordering\Model\Rule;
use FeelUnique\Ordering\Model\Action;
use FeelUnique\Ordering\Model\ProductOffer;
use FeelUnique\Ordering\Model\PriceAdjustment;
use FeelUnique\Ordering\Model\Category;

/**
 * Processes all active offers by checking rules and applying configured actions if rules are eligible.
 *
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class OfferProcessor
{
    protected $offerContainer;
    protected $offerChecker;

    /**
     * @param OfferContainer $offerContainer
     * @param OfferChecker $offerChecker
     */
    public function __construct(OfferContainer $offerContainer, OfferChecker $offerChecker)
    {
        $this->offerContainer = $offerContainer;
        $this->offerChecker = $offerChecker;
    }

    /**
     * @return OfferContainer
     */
    public function getActiveOffers()
    {
        return $this->offerContainer;
    }

    /**
     * @param Order $order
     * @param ProductOffer $offer
     */
    public function applyOffer(Order $order, ProductOffer $offer)
    {
        foreach ($offer->getRules() as $rule) {
            $numberOfEligibleProducts = floor($order->getOfferSubjectProductCount() / $offer->getUsageLimit());

            switch ($rule->getType()) {
                case Rule::PRODUCT_CATEGORY_COMBINATION_RULE:
                    $config = $rule->getConfiguration();
                    $cheapestProducts = $order->getCheapestProducts($numberOfEligibleProducts, $config['discountable']);
                    break;

                default:
                    $cheapestProducts = $order->getCheapestProducts($numberOfEligibleProducts);
                    break;
            }

            foreach ($cheapestProducts as $product) {
                $priceAdjustment = $this->createPriceAdjustment($offer->getActions(), $product->getPrice());

                $order->addAdjustment($priceAdjustment);
            }

            break;
        }
    }

    /**
     * @param Order $order
     */
    public function process(Order $order)
    {
        foreach ($this->offerContainer as $offer) {
            if ($this->offerChecker->isEligible($order, $offer)) {
                $this->applyOffer($order, $offer);
            }
        }
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $actions
     * @param float $amount
     * @return PriceAdjustment
     */
    public function createPriceAdjustment($actions, $amount)
    {
        $priceAdjustment = new PriceAdjustment();

        foreach ($actions as $action) {
            switch ($action->getType()) {
                case Action::PERCENTAGE_DISCOUNT_ACTION:
                    $ratio = $action->getAmount() / 100;
                    $priceAdjustment->setAmount($amount * -1 * $ratio);
                    break;
            }
        }

        return $priceAdjustment;
    }
}
