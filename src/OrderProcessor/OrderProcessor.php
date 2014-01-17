<?php

namespace FeelUnique\Ordering\OrderProcessor;

use FeelUnique\Ordering\Model\Order;
use FeelUnique\Ordering\Offer\OfferProcessor;
use FeelUnique\Ordering\Model\ProductOffer;

/**
 * Order processor.
 *
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class OrderProcessor
{
    /**
     * Order promotion processor.
     *
     * @var OfferProcessor
     */
    protected $offerProcessor;

    /**
     * Constructor.
     *
     * @param OfferProcessor $offerProcessor
     */
    public function __construct(OfferProcessor $offerProcessor)
    {
        $this->offerProcessor = $offerProcessor;
    }

    /**
     * @param Order $order
     */
    public function processOrder(Order $order)
    {
        $this->offerProcessor->process($order);
    }
}
