<?php

namespace FeelUnique\Ordering\Offer;

use FeelUnique\Ordering\Model\Rule;
use FeelUnique\Ordering\Model\Action;
use FeelUnique\Ordering\Model\ProductOffer;
use FeelUnique\Ordering\Model\Category;

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
            if ($offerData['active']) {
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

    /**
     * @return \Iterator
     */
    public function getIterator()
    {
        return count($this->offers)
            ? new \ArrayIterator($this->offers)
            : new \EmptyIterator()
        ;
    }

    /**
     * @param string $offerName
     * @param ProductOffer offerName
     */
    public function offsetSet($offerName, $offer)
    {
        $this->offers[$offerName] = $offer;
    }

    /**
     * @param string $offerName
     * @return ProductOffer
     */
    public function offsetGet($offerName)
    {
        return $this->offers[$offerName];
    }

    /**
     * @param string $offerName
     * @return boolean
     */
    public function offsetExists($offerName)
    {
        return array_key_exists($offerName, $this->offers);
    }

    /**
     * @param string $offerName
     */
    public function offsetUnset($offerName)
    {
        unset($this->values[$offerName]);
    }

    /**
     * Create offer rule of given type and configuration.
     *
     * @param string $type
     * @param array  $configuration
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
     * Create offer action of given type and configuration.
     *
     * @param string $type
     * @param array  $configuration
     * @return Action
     */
    public static function createAction($type, array $configuration)
    {
        $action = new Action();

        $action->setType($type);
        $action->setConfiguration($configuration);

        return $action;
    }

    /**
     * Create offer with set of rules and actions.
     *
     * @param string $name
     * @param array  $rules
     * @param array  $actions
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

            case preg_match('/^Buy ([A-z\'\-\ ]+) & get ([A-z\'\-\ ]+) for (100|\d{1,2})% off$/', $name, $matches):
                return static::createCombinationOffer($name, $matches[1], $matches[2], $matches[3]);

            default:
                throw new \InvalidArgumentException(sprintf("'%s' is not a recognized offer", $name));
        }
    }

    /**
     * @param string $name
     * @param string $categoryQualifier
     * @param string $categoryDiscountable
     * @param float $discountPercentage
     * @return ProductOffer
     */
    public static function createCombinationOffer($name, $categoryQualifier, $categoryDiscountable, $discountPercentage)
    {
        $comboCategoryRule = static::createRule(Rule::PRODUCT_CATEGORY_COMBINATION_RULE, array(
            'qualifier' => $categoryQualifier,
            'discountable' => $categoryDiscountable,
            'count' => 1,
        ));

        $halfDiscountAction = static::createAction(Action::PERCENTAGE_DISCOUNT_ACTION, array(
            'amount' => 50,
            'category' => $categoryDiscountable,
        ));

        return static::createOffer($name, array($comboCategoryRule), array($halfDiscountAction));
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

        $fullDiscountAction = static::createAction(Action::PERCENTAGE_DISCOUNT_ACTION, array(
            'amount' => 100
        ));

        return static::createOffer($name, array($bulkCountRule), array($fullDiscountAction));
    }
}
