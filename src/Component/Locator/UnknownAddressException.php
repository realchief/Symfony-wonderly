<?php

namespace Component\Locator;

/**
 * Class UnknownAddressException
 *
 * @package Component\Locator
 */
class UnknownAddressException extends AddressResolverException
{

    /**
     * UnknownAddressException constructor.
     *
     * @param string          $address  Unresolvable address.
     * @param \Exception|null $previous Previous occurred exception.
     */
    public function __construct(string $address, \Exception $previous = null)
    {
        parent::__construct('Can\'t resolve address '. $address, 0, $previous);
    }
}
