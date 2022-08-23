<?php

// File generated from our OpenAPI spec

namespace Stripe\Service\BillingPortal;

use Stripe\BillingPortal\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Service\AbstractService;
use Stripe\Util\RequestOptions;

class SessionService extends AbstractService
{
    /**
     * Creates a session of the customer portal.
     *
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return Session
     *@throws ApiErrorException if the request fails
     *
     */
    public function create($params = null, $opts = null)
    {
        return $this->request('post', '/v1/billing_portal/sessions', $params, $opts);
    }
}
