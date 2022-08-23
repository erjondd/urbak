<?php

// File generated from our OpenAPI spec

namespace Stripe\Service;

use Stripe\AccountLink;
use Stripe\Exception\ApiErrorException;
use Stripe\Util\RequestOptions;

class AccountLinkService extends AbstractService
{
    /**
     * Creates an AccountLink object that includes a single-use Stripe URL that the
     * platform can redirect their user to in order to take them through the Connect
     * Onboarding flow.
     *
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return AccountLink
     *@throws ApiErrorException if the request fails
     *
     */
    public function create($params = null, $opts = null)
    {
        return $this->request('post', '/v1/account_links', $params, $opts);
    }
}
