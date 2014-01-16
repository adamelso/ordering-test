<?php

namespace FeelUnique\Ordering\Offer;

use FeelUnique\Ordering\Model\Rule;
use FeelUnique\Ordering\Model\Action;
use FeelUnique\Ordering\Model\ProductOffer;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class OfferContainer implements \ArrayAccess, \IteratorAggregate
{
    /**
     * @var array
     */
    protected $offers;

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        foreach ($data as $offerName => $offerData) {
            if ($offerData['enabled']) {
                if (array_key_exists('offer', $offerData)) {
                    $offer = $this->createOfferFromName($offerData['offer']);
                } else {
                    $rules = array();

                    foreach ($offerData['rules'] as $ruleData) {
                        $rules[] = $this->createRule($ruleData['type'], $ruleData['configuration']);
                    }

                    $offer = $this->createOffer(
                        $offerData['name'],
                        $rules,
                        $offerData['actions']
                    );
                }

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
     * Create offer rule of given type and configuration.
     *
     * @param string $type
     * @param array  $configuration
     *
     * @return Rule
     */
    public static function createRule($type, array $configuration)
    {
        $rule = new Rule();

        $rule->setType($type);
        $rule->setConfiguration($configuration);

        return $rule;
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

    /**
     * @param string $name
     * @return ProductOffer
     * @throws \InvalidArgumentException
     */
    public static function createOfferFromName($name)
    {
        switch (true) {
            case preg_match('/^(\d{1,2}) for the price of (\d{1,2})$/', $name, $matches):
                return static::createBulkOffer($name, $matches[1]);
            default:
                throw new \InvalidArgumentException(sprintf("'%s' is not a recognized offer", $name));
        }
    }

    /**
     * @param string $name
     * @param integer $productCount
     * @return ProductOffer
     */
    public static function createBulkOffer($name, $productCount)
    {
        $bulkCountRule = static::createRule(Rule::PRODUCT_COUNT_RULE, array(
            'count' => $productCount
        ));

        $bulkCategoryRule = static::createRule(Rule::PRODUCT_CATEGORY_RULE, array(
            'category' => null,
        ));

        $actions = array(
            array(
                'amount' => 100,
                'type' => ProductOffer::PERCENTAGE_DISCOUNT_ACTION
            )
        );

        return static::createOffer($name, array($bulkCountRule), $actions);
    }
}
