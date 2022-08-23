<?php

// File generated from our OpenAPI spec

namespace Stripe\Service\Radar;

use Stripe\Collection;
use Stripe\Exception\ApiErrorException;
use Stripe\Radar\EarlyFraudWarning;
use Stripe\Service\AbstractService;
use Stripe\Util\RequestOptions;

class EarlyFraudWarningService extends AbstractService
{
    /**
     * Returns a list of early fraud warnings.
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
        return $this->requestCollection('get', '/v1/radar/early_fraud_warnings', $params, $opts);
    }

    /**
     * Retrieves the details of an early fraud warning that has previously been
     * created.
     *
     * Please refer to the <a href="#early_fraud_warning_object">early fraud
     * warning</a> object reference for more details.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return EarlyFraudWarning
     *@throws ApiErrorException if the request fails
     *
     */
    public function retrieve($id, $params = null, $opts = null)
    {
        return $this->request('get', $this->buildPath('/v1/radar/early_fraud_warnings/%s', $id), $params, $opts);
    }
}
