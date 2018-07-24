<?php

namespace Component\Locator;

/**
 * Class FailedResolveRequestException
 *
 * @package Component\Locator
 */
class FailedResolveRequestException extends AddressResolverException
{

    /**
     * FailedResolveRequestException constructor.
     *
     * @param string          $address  Address which can't resolve.
     * @param \Exception|null $previous Previous occurred exception.
     */
    public function __construct(string $address, \Exception $previous = null)
    {
        parent::__construct('Can\'t resolve address '. $address .' due to api error', 0, $previous);
    }
}
