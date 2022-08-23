<?php

// File generated from our OpenAPI spec

namespace Stripe\Service\Sigma;

use Stripe\Collection;
use Stripe\Exception\ApiErrorException;
use Stripe\Service\AbstractService;
use Stripe\Sigma\ScheduledQueryRun;
use Stripe\Util\RequestOptions;

class ScheduledQueryRunService extends AbstractService
{
    /**
     * Returns a list of scheduled query runs.
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
        return $this->requestCollection('get', '/v1/sigma/scheduled_query_runs', $params, $opts);
    }

    /**
     * Retrieves the details of an scheduled query run.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return ScheduledQueryRun
     *@throws ApiErrorException if the request fails
     *
     */
    public function retrieve($id, $params = null, $opts = null)
    {
        return $this->request('get', $this->buildPath('/v1/sigma/scheduled_query_runs/%s', $id), $params, $opts);
    }
}
