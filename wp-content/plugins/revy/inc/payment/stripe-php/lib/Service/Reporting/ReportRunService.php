<?php

// File generated from our OpenAPI spec

namespace Stripe\Service\Reporting;

use Stripe\Collection;
use Stripe\Exception\ApiErrorException;
use Stripe\Reporting\ReportRun;
use Stripe\Service\AbstractService;
use Stripe\Util\RequestOptions;

class ReportRunService extends AbstractService
{
    /**
     * Returns a list of Report Runs, with the most recent appearing first.
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
        return $this->requestCollection('get', '/v1/reporting/report_runs', $params, $opts);
    }

    /**
     * Creates a new object and begin running the report. (Certain report types require
     * a <a href="https://stripe.com/docs/keys#test-live-modes">live-mode API key</a>.).
     *
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return ReportRun
     *@throws ApiErrorException if the request fails
     *
     */
    public function create($params = null, $opts = null)
    {
        return $this->request('post', '/v1/reporting/report_runs', $params, $opts);
    }

    /**
     * Retrieves the details of an existing Report Run.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return ReportRun
     *@throws ApiErrorException if the request fails
     *
     */
    public function retrieve($id, $params = null, $opts = null)
    {
        return $this->request('get', $this->buildPath('/v1/reporting/report_runs/%s', $id), $params, $opts);
    }
}
