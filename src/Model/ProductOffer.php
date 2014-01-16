<?php

namespace FeelUnique\Ordering\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product Offer.
 */
class ProductOffer
{
    const PRODUCT_TOTAL_RULE = 'product_total';
    const PRODUCT_COUNT_RULE = 'product_count';
    const PRODUCT_CATEGORY_RULE = 'product_category';

    const FIXED_DISCOUNT_ACTION      = 'fixed_discount';
    const PERCENTAGE_DISCOUNT_ACTION = 'percentage_discount';

    protected $rules;

    protected $actions;

    public function __construct($name)
    {
        if (preg_match('/^(\d{1,2}) for the price of (\d{1,2})$/', $name, $matches)) {
            $productCount = $matches[1];

            $this->rules = array(
                array(
                    'count' => $productCount,
                    'type' => static::PRODUCT_COUNT_RULE,
                ),
                // array(
                //     'category' => null,
                //     'type' => static::PRODUCT_CATEGORY_RULE
                // )
            );

            $this->actions = array(
                array(
                    'amount' => 100,
                    'type' => static::PERCENTAGE_DISCOUNT_ACTION
                )
            );
        }
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function getProductCount()
    {
        if ($this->getType() === static::PRODUCT_COUNT_RULE) {
            return $this->rules['count'];
        }

        throw new \DomainException("Offer is not subject to a product count rule.");
    }

    public function createPriceAdjustment($amount)
    {
        $priceAdjustment = new PriceAdjustment();

        foreach ($this->actions as $action) {
            switch ($action['type']) {
                case static::PERCENTAGE_DISCOUNT_ACTION:
                    $ratio = $action['amount'] / 100;
                    $priceAdjustment->setAmount($amount * -1 * $ratio);
                    break;
            }
        }

        return $priceAdjustment;
    }
}
