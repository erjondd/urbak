<?php

// File generated from our OpenAPI spec

namespace Stripe\Service\BillingPortal;

use Stripe\Service\AbstractServiceFactory;
use function array_key_exists;

/**
 * Service factory class for API resources in the BillingPortal namespace.
 *
 * @property ConfigurationService $configurations
 * @property SessionService $sessions
 */
class BillingPortalServiceFactory extends AbstractServiceFactory
{
    /**
     * @var array<string, string>
     */
    private static $classMap = [
        'configurations' => ConfigurationService::class,
        'sessions' => SessionService::class,
    ];

    protected function getServiceClass($name)
    {
        return array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
    }
}
