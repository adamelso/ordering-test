<?php

namespace FeelUnique\Ordering\Model;

/**
 * Pricing adjustments when offers are applied to a product.
 *
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class PriceAdjustment
{
    /**
     * Adjustable order.
     *
     * @var Order
     */
    protected $order;

    /**
     * Adjustable order item.
     *
     * @var Product
     */
    protected $product;

    /**
     * PriceAdjustment label.
     *
     * @var string
     */
    protected $label;

    /**
     * PriceAdjustment amount.
     *
     * @var integer
     */
    protected $amount;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->amount = 0;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Order|Product|null
     */
    public function getAdjustable()
    {
        if (null !== $this->order) {
            return $this->order;
        }

        if (null !== $this->product) {
            return $this->product;
        }

        return null;
    }

    /**
     * @param PriceAdjustableInterface $adjustable
     * @return $this
     */
    public function setAdjustable(PriceAdjustableInterface $adjustable = null)
    {
        $this->order = $this->product = null;

        if ($adjustable instanceof Order) {
            $this->order = $adjustable;
        }

        if ($adjustable instanceof Product) {
            $this->product = $adjustable;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isCharge()
    {
        return 0 > $this->amount;
    }

    /**
     * @return boolean
     */
    public function isCredit()
    {
        return 0 < $this->amount;
    }
}
