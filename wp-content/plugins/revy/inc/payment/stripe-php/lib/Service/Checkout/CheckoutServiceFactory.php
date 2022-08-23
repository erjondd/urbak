<?php

// File generated from our OpenAPI spec

namespace Stripe\Service\Checkout;

use Stripe\Service\AbstractServiceFactory;
use function array_key_exists;

/**
 * Service factory class for API resources in the Checkout namespace.
 *
 * @property SessionService $sessions
 */
class CheckoutServiceFactory extends AbstractServiceFactory
{
    /**
     * @var array<string, string>
     */
    private static $classMap = [
        'sessions' => SessionService::class,
    ];

    protected function getServiceClass($name)
    {
        return array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
    }
}
