<?php

// File generated from our OpenAPI spec

namespace Stripe\Service\Identity;

use Stripe\Collection;
use Stripe\Exception\ApiErrorException;
use Stripe\Identity\VerificationReport;
use Stripe\Service\AbstractService;
use Stripe\Util\RequestOptions;

class VerificationReportService extends AbstractService
{
    /**
     * List all verification reports.
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
        return $this->requestCollection('get', '/v1/identity/verification_reports', $params, $opts);
    }

    /**
     * Retrieves an existing VerificationReport.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|RequestOptions $opts
     *
     * @return VerificationReport
     *@throws ApiErrorException if the request fails
     *
     */
    public function retrieve($id, $params = null, $opts = null)
    {
        return $this->request('get', $this->buildPath('/v1/identity/verification_reports/%s', $id), $params, $opts);
    }
}
