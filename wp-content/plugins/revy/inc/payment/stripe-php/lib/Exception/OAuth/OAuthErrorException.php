<?php

namespace Stripe\Exception\OAuth;

use Stripe\Exception\ApiErrorException;
use Stripe\OAuthErrorObject;

/**
 * Implements properties and methods common to all (non-SPL) Stripe OAuth
 * exceptions.
 */
abstract class OAuthErrorException extends ApiErrorException
{
    protected function constructErrorObject()
    {
        if (null === $this->jsonBody) {
            return null;
        }

        return OAuthErrorObject::constructFrom($this->jsonBody);
    }
}
