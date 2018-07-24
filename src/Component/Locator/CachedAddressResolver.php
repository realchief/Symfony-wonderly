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
class CachedAddressResolver implements AddressResolverInterface
{

    /**
     * @var AddressResolverInterface
     */
    private $resolver;

    /**
     * @var Point[]
     */
    private $cache = array();

    /**
     * CachedAddressResolver constructor.
     *
     * @param AddressResolverInterface $resolver A AddressResolverInterface instance.
     */
    public function __construct(AddressResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Resolve address into geometry point.
     *
     * @param string $address Some address.
     *
     * @return Point
     *
     * @throws FailedResolveRequestException Can't resolve address due to external
     *                                       api error.
     * @throws UnknownAddressException Api can't resolve specified address.
     */
    public function resolveAddress(string $address)
    {
        if (! isset($this->cache[$address])) {
            $this->cache[$address] = $this->resolver->resolveAddress($address);
        }

        return $this->cache[$address];
    }
}
