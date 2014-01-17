<?php

namespace FeelUnique\Ordering\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product Offer.
 *
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class ProductOffer
{
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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param Rule $rule
     * @return $this
     */
    public function addRule(Rule $rule)
    {
        $rule->setOffer($this);

        switch ($rule->getType()) {
            case Rule::PRODUCT_COUNT_RULE:
            case Rule::PRODUCT_CATEGORY_COMBINATION_RULE:
                $config = $rule->getConfiguration();
                $rule->getOffer()->setUsageLimit($config['count']);
                break;
        }

        $this->rules[] = $rule;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param Action $action
     * @return $this
     */
    public function addAction(Action $action)
    {
        $action->setOffer($this);
        $this->actions[] = $action;
        return $this;
    }

    /**
     * @return integer
     */
    public function getUsageLimit()
    {
        return $this->usageLimit;
    }

    /**
     * @param integer $usageLimit
     * @return $this
     */
    public function setUsageLimit($usageLimit)
    {
        $this->usageLimit = $usageLimit;

        return $this;
    }

    /**
     * @return integer
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * @param integer $used
     * @return $this
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * @return integer
     */
    public function incrementUsed()
    {
        $this->used++;

        return $this->used;
    }
}
