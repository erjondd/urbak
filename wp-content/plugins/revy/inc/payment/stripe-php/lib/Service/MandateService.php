<?php

// File generated from our OpenAPI spec

namespace Stripe\Service;

use Stripe\Exception\ApiErrorException;
use Stripe\Mandate;
use Stripe\Util\RequestOptions;

class MandateService extends AbstractService
{
    /**
     * Retrieves a Mandate object.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return Mandate
     *@throws ApiErrorException if the request fails
     *
     */
    public function retrieve($id, $params = null, $opts = null)
    {
        return $this->request('get', $this->buildPath('/v1/mandates/%s', $id), $params, $opts);
    }
}
