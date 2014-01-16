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

    protected $name;

    protected $rules;

    protected $actions;

    public function __construct()
    {
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function addRule($rule)
    {
        $this->rules[] = $rule;
        return $this;
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function addAction($action)
    {
        $this->actions[] = $action;
        return $this;
    }

    public function getProductCount()
    {
        if ($this->getType() === static::PRODUCT_COUNT_RULE) {
            return $this->rules['count'];
        }

        throw new \DomainException("Offer is not subject to a product count rule.");
    }
}
