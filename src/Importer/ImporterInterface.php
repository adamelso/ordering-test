<?php

namespace FeelUnique\Ordering\Importer;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
interface ImporterInterface
{
    /**
     * The resource parameter can be a resource or a string to the resource
     * which this method implementation will be responsible for opening and
     * accessing the resource, or be some other kind of PHP data type.
     *
     * @param mixed $resource
     * @return \FeelUnique\Ordering\Model\Order
     */
    public function import($resource);
}
