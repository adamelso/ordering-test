<?php

namespace FeelUnique\Ordering\OrderProcessor;

use FeelUnique\Ordering\Model\Order;
use FeelUnique\Ordering\Offer\OfferProcessor;
use FeelUnique\Ordering\Model\ProductOffer;

/**
 * Order processor.
 */
class OrderProcessor
{
    /**
     * Order promotion processor.
     *
     * @var OfferProcessor
     */
    protected $offerProcessor;

    protected $offers;

    /**
     * Constructor.
     *
     * @param OfferProcessor $offerProcessor
     */
    public function __construct(OfferProcessor $offerProcessor, array $offers = array())
    {
        $this->offerProcessor = $offerProcessor;

        foreach ($offers as $offer) {
            if ($offer['enabled']) {
                $this->offers[] = new ProductOffer($offer['offer']);
            }
        }
    }

    /**
     * @param Order $order
     */
    public function processOrder(Order $order)
    {
        // $this->offerProcessor->process($order);
        foreach ($this->offers as $offer) {
            foreach ($offer->getRules() as $rule) {
                if (
                    $rule['type'] === ProductOffer::PRODUCT_COUNT_RULE &&
                    $numberOfEligibleProducts = floor($order->getOfferSubjectProductCount() / $rule['count'])
                ) {
                    foreach ($order->getCheapestProducts($numberOfEligibleProducts) as $product) {
                        $priceAdjustment = $this->offerProcessor->createPriceAdjustment($product->getPrice());

                        $order->addAdjustment($priceAdjustment);
                    }
                }
            }
        }
    }
}
