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
}
