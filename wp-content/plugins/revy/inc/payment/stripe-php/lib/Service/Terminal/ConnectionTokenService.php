<?php

// File generated from our OpenAPI spec

namespace Stripe\Service\Terminal;

use Stripe\Exception\ApiErrorException;
use Stripe\Service\AbstractService;
use Stripe\Terminal\ConnectionToken;
use Stripe\Util\RequestOptions;

class ConnectionTokenService extends AbstractService
{
    /**
     * To connect to a reader the Stripe Terminal SDK needs to retrieve a short-lived
     * connection token from Stripe, proxied through your server. On your backend, add
     * an endpoint that creates and returns a connection token.
     *
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return ConnectionToken
     *@throws ApiErrorException if the request fails
     *
     */
    public function create($params = null, $opts = null)
    {
        return $this->request('post', '/v1/terminal/connection_tokens', $params, $opts);
    }
}
