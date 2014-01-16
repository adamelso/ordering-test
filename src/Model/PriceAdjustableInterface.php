<?php

namespace FeelUnique\Ordering\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
interface PriceAdjustableInterface
{
    /**
     * @return ArrayCollection
     */
    public function getAdjustments();

    /**
     * Add pricing adjustment.
     *
     * @param PriceAdjustment $adjustment
     */
    public function addAdjustment(PriceAdjustment $adjustment);

    /**
     * Remove pricing adjustment.
     *
     * @param PriceAdjustment $adjustment
     */
    public function removeAdjustment(PriceAdjustment $adjustment);

    /**
     * Get pricing adjustments total.
     *
     * @return integer
     */
    public function getAdjustmentsTotal();

    /**
     * Calculate pricing adjustments total.
     */
    public function calculateAdjustmentsTotal();
}
