<?php

// File generated from our OpenAPI spec

namespace Stripe\Service\Issuing;

use Stripe\Collection;
use Stripe\Exception\ApiErrorException;
use Stripe\Issuing\Card;
use Stripe\Service\AbstractService;
use Stripe\Util\RequestOptions;

class CardService extends AbstractService
{
    /**
     * Returns a list of Issuing <code>Card</code> objects. The objects are sorted in
     * descending order by creation date, with the most recently created object
     * appearing first.
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
        return $this->requestCollection('get', '/v1/issuing/cards', $params, $opts);
    }

    /**
     * Creates an Issuing <code>Card</code> object.
     *
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return Card
     *@throws ApiErrorException if the request fails
     *
     */
    public function create($params = null, $opts = null)
    {
        return $this->request('post', '/v1/issuing/cards', $params, $opts);
    }

    /**
     * Retrieves an Issuing <code>Card</code> object.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return Card
     *@throws ApiErrorException if the request fails
     *
     */
    public function retrieve($id, $params = null, $opts = null)
    {
        return $this->request('get', $this->buildPath('/v1/issuing/cards/%s', $id), $params, $opts);
    }

    /**
     * Updates the specified Issuing <code>Card</code> object by setting the values of
     * the parameters passed. Any parameters not provided will be left unchanged.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return Card
     *@throws ApiErrorException if the request fails
     *
     */
    public function update($id, $params = null, $opts = null)
    {
        return $this->request('post', $this->buildPath('/v1/issuing/cards/%s', $id), $params, $opts);
    }
}
