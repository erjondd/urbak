<?php

// File generated from our OpenAPI spec

namespace Stripe\Service\Radar;

use Stripe\Collection;
use Stripe\Exception\ApiErrorException;
use Stripe\Radar\ValueListItem;
use Stripe\Service\AbstractService;
use Stripe\Util\RequestOptions;

class ValueListItemService extends AbstractService
{
    /**
     * Returns a list of <code>ValueListItem</code> objects. The objects are sorted in
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
        return $this->requestCollection('get', '/v1/radar/value_list_items', $params, $opts);
    }

    /**
     * Creates a new <code>ValueListItem</code> object, which is added to the specified
     * parent value list.
     *
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return ValueListItem
     *@throws ApiErrorException if the request fails
     *
     */
    public function create($params = null, $opts = null)
    {
        return $this->request('post', '/v1/radar/value_list_items', $params, $opts);
    }

    /**
     * Deletes a <code>ValueListItem</code> object, removing it from its parent value
     * list.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return ValueListItem
     *@throws ApiErrorException if the request fails
     *
     */
    public function delete($id, $params = null, $opts = null)
    {
        return $this->request('delete', $this->buildPath('/v1/radar/value_list_items/%s', $id), $params, $opts);
    }

    /**
     * Retrieves a <code>ValueListItem</code> object.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return ValueListItem
     *@throws ApiErrorException if the request fails
     *
     */
    public function retrieve($id, $params = null, $opts = null)
    {
        return $this->request('get', $this->buildPath('/v1/radar/value_list_items/%s', $id), $params, $opts);
    }
}
