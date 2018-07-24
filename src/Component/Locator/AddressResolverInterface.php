<?php

namespace Component\Locator;

use CrEOF\Spatial\PHP\Types\Geometry\Point;

/**
 * Interface AddressResolverInterface
 *
 * Used for resolving address into coordinates.
 *
 * @package Component\Locator
 */
interface AddressResolverInterface
{

    /**
     * Resolve address into geometry point.
     *
     * @param string $address Some address.
     *
     * @return Point | array
     *
     * @throws FailedResolveRequestException Can't resolve address due to external
     *                                       api error.
     * @throws UnknownAddressException Api can't resolve specified address.
     */
    public function resolveAddress(string $address);
}
