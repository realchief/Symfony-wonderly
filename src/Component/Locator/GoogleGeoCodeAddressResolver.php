<?php

namespace Component\Locator;

use CrEOF\Spatial\PHP\Types\Geometry\Point;

/**
 * Class AddressResolver
 *
 * Use Google geo code api.
 *
 * @package Component\Locator
 *
 * @link https://developers.google.com/maps/documentation/geocoding/intro?hl=en
 */
class GoogleGeoCodeAddressResolver implements AddressResolverInterface
{

    const ENDPOINT = 'https://maps.googleapis.com/maps/api/geocode/json';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * GoogleGeoCodeAddressResolver constructor.
     *
     * @param string $apiKey Google maps api key.
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

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
    public function resolveAddress(string $address)
    {
        $url = sprintf(
            self::ENDPOINT.'?address=%s&key=%s',
            urlencode($address),
            $this->apiKey
        );

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
        ));

        $geo = json_decode(curl_exec($ch), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $exception = new \RuntimeException(json_last_error_msg(), json_last_error());
            throw new FailedResolveRequestException($address, $exception);
        }

        if ($geo['status'] === 'OK') {
            $address = $this->getStateCity($geo);
            $location = $geo['results'][0]['geometry']['location'];
            return [new Point($location['lng'], $location['lat']), $address];
        }

        throw new UnknownAddressException($address);
    }

    /**
     * Get State and City
     *
     * @param array $geo
     *
     * @return string
     */
    public function getStateCity(array $geo)
    {
        $results = $geo['results'][0]['address_components'];
        $array = [];
        foreach ($results as $result) {
            switch ($result['types'][0]) {
                case ("administrative_area_level_1"):
                    $array['area1'] = $result['short_name'];
                    break;
                case ("administrative_area_level_2"):
                    $array['area2'] = $result['short_name'];
                    break;
                case ("locality"):
                    $array['locality'] = $result['short_name'];
                    break;
            }
        }

        $address = '';
        if (isset($array['locality'])) {
            $address .= $array['locality'];
        }
        if (isset($array['area2']) and !isset($array['locality'])) {
            $address .= $array['area2'];
        }
        if (isset($array['area1'])) {
            $address .= ', ' . $array['area1'];
        }

        return $address;
    }
}
