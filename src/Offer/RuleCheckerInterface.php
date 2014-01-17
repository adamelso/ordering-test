<?php

namespace FeelUnique\Ordering\Offer;

use FeelUnique\Ordering\Model\Order;

interface RuleCheckerInterface
{
    public function isEligible(Order $order, array $configuration);
}
