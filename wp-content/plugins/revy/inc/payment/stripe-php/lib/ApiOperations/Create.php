<?php

namespace Stripe\ApiOperations;

use Stripe\Exception\ApiErrorException;
use Stripe\Util\Util;

/**
 * Trait for creatable resources. Adds a `create()` static method to the class.
 *
 * This trait should only be applied to classes that derive from StripeObject.
 */
trait Create
{
    /**
     * @param null|array $params
     * @param null|array|string $options
     *
     * @throws ApiErrorException if the request fails
     *
     * @return static the created resource
     */
    public static function create($params = null, $options = null)
    {
        self::_validateParams($params);
        $url = static::classUrl();

        list($response, $opts) = static::_staticRequest('post', $url, $params, $options);
        $obj = Util::convertToStripeObject($response->json, $opts);
        $obj->setLastResponse($response);

        return $obj;
    }
}