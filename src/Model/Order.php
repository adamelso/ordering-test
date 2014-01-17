<?php

namespace FeelUnique\Ordering\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class Order implements PriceAdjustableInterface
{
    /**
     * Products in order.
     *
     * @var Collection
     */
    protected $products;

    /**
     * Products total.
     *
     * @var integer
     */
    protected $productsTotal;

    /**
     * Adjustments.
     *
     * @var Collection
     */
    protected $adjustments;

    /**
     * Adjustments total.
     *
     * @var integer
     */
    protected $adjustmentsTotal;

    /**
     * Calculated total.
     * Products total + adjustments total.
     *
     * @var integer
     */
    protected $total;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->productsTotal = 0;
        $this->adjustments = new ArrayCollection();
        $this->adjustmentsTotal = 0;
        $this->total = 0;
        $this->confirmed = true;
    }

    /**
     *
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     *
     */
    public function setProducts(Collection $products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     *
     */
    public function clearProducts()
    {
        $this->products->clear();

        return $this;
    }

    /**
     *
     */
    public function countProducts()
    {
        return count($this->products);
    }

    /**
     *
     */
    public function addProduct(Product $product)
    {
        if ($this->hasProduct($product)) {
            return $this;
        }

        foreach ($this->products as $existingProduct) {
            if ($product->equals($existingProduct)) {
                $existingProduct->setQuantity($existingProduct->getQuantity() + $product->getQuantity());

                return $this;
            }
        }

        $product->setOrder($this);
        $this->products->add($product);

        return $this;
    }

    /**
     *
     */
    public function removeProduct(Product $product)
    {
        if ($this->hasProduct($product)) {
            $product->setOrder(null);
            $this->products->removeElement($product);
        }

        return $this;
    }

    /**
     *
     */
    public function hasProduct(Product $product)
    {
        return $this->products->contains($product);
    }

    /**
     *
     */
    public function getProductsTotal()
    {
        return $this->productsTotal;
    }

    /**
     *
     */
    public function setProductsTotal($productsTotal)
    {
        $this->productsTotal = $productsTotal;

        return $this;
    }

    /**
     *
     */
    public function calculateProductsTotal()
    {
        $productsTotal = 0;

        foreach ($this->products as $product) {
            $product->calculateTotal();

            $productsTotal += $product->getTotal();
        }

        $this->productsTotal = $productsTotal;

        return $this;
    }

    /**
     *
     */
    public function getAdjustments()
    {
        return $this->adjustments;
    }

    /**
     *
     */
    public function addAdjustment(PriceAdjustment $adjustment)
    {
        if (!$this->hasAdjustment($adjustment)) {
            $adjustment->setAdjustable($this);
            $this->adjustments->add($adjustment);
        }

        return $this;
    }

    /**
     *
     */
    public function removeAdjustment(PriceAdjustment $adjustment)
    {
        if ($this->hasAdjustment($adjustment)) {
            $adjustment->setAdjustable(null);
            $this->adjustments->removeElement($adjustment);
        }

        return $this;
    }

    /**
     *
     */
    public function hasAdjustment(PriceAdjustment $adjustment)
    {
        return $this->adjustments->contains($adjustment);
    }

    /**
     *
     */
    public function getAdjustmentsTotal()
    {
        return $this->adjustmentsTotal;
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
     *
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     *
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     *
     */
    public function calculateTotal()
    {
        $this->calculateProductsTotal();
        $this->calculateAdjustmentsTotal();

        $this->total = $this->productsTotal + $this->adjustmentsTotal;

        return $this;
    }

    /**
     *
     */
    public function getTotalProducts()
    {
        return $this->countProducts();
    }

    /**
     * @return integer
     */
    public function getTotalQuantity()
    {
        $quantity = 0;

        foreach ($this->products as $product) {
            $quantity += $product->getQuantity();
        }

        return $quantity;
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return 0 === $this->countProducts();
    }

    /**
     * @return integer
     */
    public function getOfferSubjectProductTotal()
    {
        return $this->getProductsTotal();
    }

    /**
     * @return integer
     */
    public function getOfferSubjectProductCount($categoryFilter = null)
    {
        if (!$categoryFilter) {
            return $this->products->count();
        }

        $filtered = $this->products->filter(function($element) use ($categoryFilter) {
            return $element->getCategory()->getName() === $categoryFilter;
        });

        return $filtered->count();
    }

    public function getCheapestProducts($maxResults = 1, $categoryFilter = null)
    {
        $criteria = Criteria::create();

        if ($categoryFilter) {
            $criteria->where(Criteria::expr()->in("category", array(new Category($categoryFilter))));
        }

        $criteria->orderBy(array("price" => Criteria::ASC));
        $criteria->setMaxResults($maxResults);

        return $this->products->matching($criteria);
    }
}
