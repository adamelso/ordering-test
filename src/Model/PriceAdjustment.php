<?php

namespace FeelUnique\Ordering\Model;

/**
 * Pricing adjustments when offers are applied to a product.
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
     *
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
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
     *
     */
    public function setAdjustable(ProductAdjustableInterface $adjustable = null)
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
     *
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     *
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     *
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     *
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     *
     */
    public function isCharge()
    {
        return 0 > $this->amount;
    }

    /**
     *
     */
    public function isCredit()
    {
        return 0 < $this->amount;
    }
}
