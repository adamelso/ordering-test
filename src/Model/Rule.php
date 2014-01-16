<?php

namespace FeelUnique\Ordering\Model;

/**
 * Offer rule.
 *
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class Rule
{
    const PRODUCT_TOTAL_RULE    = 'product_total';
    const PRODUCT_COUNT_RULE    = 'product_count';
    const PRODUCT_CATEGORY_RULE = 'product_category';

    /**
     * Type
     *
     * @var string
     */
    protected $type;

    /**
     * Configuration
     *
     * @var array
     */
    protected $configuration = array();

    /**
     * Associated offer
     *
     * @var Offer
     */
    protected $offer;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->configuration = array();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
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
     */
    public function setOffer(ProductOffer $offer = null)
    {
        $this->offer = $offer;

        return $this;
    }
}
