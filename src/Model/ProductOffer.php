<?php

namespace FeelUnique\Ordering\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product Offer.
 */
class ProductOffer
{
    const FIXED_DISCOUNT_ACTION      = 'fixed_discount';
    const PERCENTAGE_DISCOUNT_ACTION = 'percentage_discount';

    protected $name;

    protected $rules;

    protected $actions;

    protected $usageLimit;
    protected $used = 0;

    public function __construct()
    {
        $this->rules = new ArrayCollection();
        $this->actions = new ArrayCollection();
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
        $rule->setOffer($this);

        if ($rule->getType() === Rule::PRODUCT_COUNT_RULE) {
            $config = $rule->getConfiguration();
            $rule->getOffer()->setUsageLimit($config['count']);
        }

        $this->rules[] = $rule;

        return $this;
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function addAction($action)
    {
        $action->setOffer($this);
        $this->actions[] = $action;
        return $this;
    }

    public function getUsageLimit()
    {
        return $this->usageLimit;
    }

    public function setUsageLimit($usageLimit)
    {
        $this->usageLimit = $usageLimit;

        return $this;
    }

    public function getUsed()
    {
        return $this->used;
    }

    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    public function incrementUsed()
    {
        $this->used++;

        return $this->used;
    }
}
