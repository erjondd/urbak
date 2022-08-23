<?php

// File generated from our OpenAPI spec

namespace Stripe\Service;

use Stripe\Collection;
use Stripe\Exception\ApiErrorException;
use Stripe\Util\RequestOptions;

class SetupAttemptService extends AbstractService
{
    /**
     * Returns a list of SetupAttempts associated with a provided SetupIntent.
     *
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return Collection
     *@throws ApiErrorException if the request fails
     *
     */
    public function all($params = null, $opts = null)
    {
        return $this->requestCollection('get', '/v1/setup_attempts', $params, $opts);
    }
}
