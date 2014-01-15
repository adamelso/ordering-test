<?php

namespace FeelUnique\Ordering\Model;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class Category
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param Offer
     */
    // protected $offer;

    /**
     * @param string $name
     */
    public function __construct($name = null)
    {
        $this->setName($name);
    }

    /**
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Offer
     * @return Category
     */
    // public function setOffer(Offer $offer)
    // {
    //     $this->offer = $offer;
    //     return $this;
    // }

    /**
     * @return Offer
     */
    // public function getOffer()
    // {
    //     return $this->offer;
    // }
}
