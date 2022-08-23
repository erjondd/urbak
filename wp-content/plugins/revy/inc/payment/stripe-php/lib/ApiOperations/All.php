<?php

namespace Stripe\ApiOperations;

use Stripe\Collection;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Util\Util;
use function get_class;

/**
 * Trait for listable resources. Adds a `all()` static method to the class.
 *
 * This trait should only be applied to classes that derive from StripeObject.
 */
trait All
{
    /**
     * @param null|array $params
     * @param null|array|string $opts
     *
     * @throws ApiErrorException if the request fails
     *
     * @return Collection of ApiResources
     */
    public static function all($params = null, $opts = null)
    {
        self::_validateParams($params);
        $url = static::classUrl();

        list($response, $opts) = static::_staticRequest('get', $url, $params, $opts);
        $obj = Util::convertToStripeObject($response->json, $opts);
        if (!($obj instanceof Collection)) {
            throw new UnexpectedValueException(
                'Expected type ' . Collection::class . ', got "' . get_class($obj) . '" instead.'
            );
        }
        $obj->setLastResponse($response);
        $obj->setFilters($params);

        return $obj;
    }
}
