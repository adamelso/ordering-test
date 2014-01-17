<?php

namespace FeelUnique\Ordering\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class Product implements PriceAdjustableInterface
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $category;

    /**
     * @var float
     */
    protected $price = 0;

    /**
     * @var integer
     */
    protected $quantity = 1;

    /**
     * @var float
     */
    protected $adjustmentsTotal = 0;

    /**
     * @var float
     */
    protected $total = 0;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->adjustments = new ArrayCollection();
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = round((float) $price, 2, \PHP_ROUND_HALF_UP);
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get item quantity.
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set quantity.
     *
     * @param integer $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order $order
     * @return $this
     */
    public function setOrder(Order $order = null)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Get item total.
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set item total.
     *
     * @param integer $total
     * @return $this
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * Calculate total based on quantity and unit price.
     * Take adjustments into account.
     *
     * @return $this
     */
    public function calculateTotal()
    {
        $this->calculateAdjustmentsTotal();

        $this->total = ($this->quantity * $this->price) + $this->adjustmentsTotal;

        return $this;
    }

    /**
     *
     */
    public function calculateAdjustmentsTotal()
    {
        $this->adjustmentsTotal = 0;

        foreach ($this->adjustments as $adjustment) {
            $this->adjustmentsTotal += $adjustment->getAmount();
        }
    }

    /**
     * Checks whether the item given as argument corresponds to
     * the same cart item. Can be overwritten to sum up quantity.
     *
     * @param Product $product
     * @return boolean
     */
    public function equals(Product $product)
    {
        ///...
    }

    /**
     *
     */
    public function getAdjustments()
    {
        //...
    }

    /**
     * @param PriceAdjustment $adjustment
     */
    public function addAdjustment(PriceAdjustment $adjustment)
    {
        //...
    }

    /**
     * @param PriceAdjustment $adjustment
     */
    public function removeAdjustment(PriceAdjustment $adjustment)
    {
        //..
    }

    /**
     *
     */
    public function getAdjustmentsTotal()
    {
        ///
    }
}
