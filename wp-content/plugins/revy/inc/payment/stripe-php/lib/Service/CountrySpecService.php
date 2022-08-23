<?php

// File generated from our OpenAPI spec

namespace Stripe\Service;

use Stripe\Collection;
use Stripe\CountrySpec;
use Stripe\Exception\ApiErrorException;
use Stripe\Util\RequestOptions;

class CountrySpecService extends AbstractService
{
    /**
     * Lists all Country Spec objects available in the API.
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
        return $this->requestCollection('get', '/v1/country_specs', $params, $opts);
    }

    /**
     * Returns a Country Spec for a given Country code.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return CountrySpec
     *@throws ApiErrorException if the request fails
     *
     */
    public function retrieve($id, $params = null, $opts = null)
    {
        return $this->request('get', $this->buildPath('/v1/country_specs/%s', $id), $params, $opts);
    }
}
