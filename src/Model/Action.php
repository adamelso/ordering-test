<?php

namespace FeelUnique\Ordering\Model;

/**
 * Offer action.
 *
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class Action
{
    const FIXED_DISCOUNT_ACTION      = 'fixed_discount';
    const PERCENTAGE_DISCOUNT_ACTION = 'percentage_discount';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $configuration = array();

    /**
     * @var ProductOffer
     */
    protected $offer;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param array $configuration
     * @return $this
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * @return ProductOffer
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * @param ProductOffer $offer
     * @return $this
     */
    public function setOffer(ProductOffer $offer = null)
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->configuration['amount'];
    }
}
