<?php

// File generated from our OpenAPI spec

namespace Stripe\Service;

use Stripe\EphemeralKey;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidArgumentException;
use Stripe\Util\RequestOptions;

class EphemeralKeyService extends AbstractService
{
    /**
     * Invalidates a short-lived API key for a given resource.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return EphemeralKey
     *@throws ApiErrorException if the request fails
     *
     */
    public function delete($id, $params = null, $opts = null)
    {
        return $this->request('delete', $this->buildPath('/v1/ephemeral_keys/%s', $id), $params, $opts);
    }

    /**
     * Creates a short-lived API key for a given resource.
     *
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return EphemeralKey
     *@throws ApiErrorException if the request fails
     *
     */
    public function create($params = null, $opts = null)
    {
        if (!$opts || !isset($opts['stripe_version'])) {
            throw new InvalidArgumentException('stripe_version must be specified to create an ephemeral key');
        }

        return $this->request('post', '/v1/ephemeral_keys', $params, $opts);
    }
}
