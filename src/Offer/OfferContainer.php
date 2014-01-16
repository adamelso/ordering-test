<?php

namespace FeelUnique\Ordering\Offer;

use FeelUnique\Ordering\Model\Rule;
use FeelUnique\Ordering\Model\Action;
use FeelUnique\Ordering\Model\ProductOffer;

class OfferContainer implements \ArrayAccess, \IteratorAggregate
{
    protected $offers;

    public function __construct(array $data = array())
    {
        foreach ($data as $offerName => $offerData) {
            if ($offerData['enabled']) {
                $offer = array_key_exists('offer', $offerData)
                    ? $this->createOfferFromName($offerData['offer'])
                    : $this->createOffer($offerData['name'], $offerData['rules'], $offerData['actions'])
                ;

                $this->offsetSet($offerName, $offer);
            }
        }
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->offers);
    }

    public function offsetSet($offerName, $offer)
    {
        $this->offers[$offerName] = $offer;
    }

    public function offsetGet($offerName)
    {
        return $this->offers[$offerName];
    }

    public function offsetExists($offerName)
    {
        return array_key_exists($offerName, $this->offers);
    }

    public function offsetUnset($offerName)
    {
        unset($this->values[$offerName]);
    }

    /**
     * Create offer with set of rules and actions.
     *
     * @param string $name
     * @param array  $rules
     * @param array  $actions
     *
     * @return ProductOffer
     */
    public static function createOffer($name, array $rules, array $actions)
    {
        $offer = new ProductOffer();

        $offer->setName($name);

        foreach ($rules as $rule) {
            $offer->addRule($rule);
        }

        foreach ($actions as $action) {
            $offer->addAction($action);
        }

        return $offer;
    }

    public static function createOfferFromName($name)
    {
        if (preg_match('/^(\d{1,2}) for the price of (\d{1,2})$/', $name, $matches)) {
            $productCount = $matches[1];

            $rules = array(
                array(
                    'count' => $productCount,
                    'type' => ProductOffer::PRODUCT_COUNT_RULE,
                ),
                // array(
                //     'category' => null,
                //     'type' => static::PRODUCT_CATEGORY_RULE
                // )
            );

            $actions = array(
                array(
                    'amount' => 100,
                    'type' => ProductOffer::PERCENTAGE_DISCOUNT_ACTION
                )
            );

            return static::createOffer($name, $rules, $actions);
        }
    }
}
