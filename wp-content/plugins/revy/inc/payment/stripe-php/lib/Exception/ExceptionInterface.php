<?php

namespace Stripe\Exception;

// TODO: remove this check once we drop support for PHP 5
use Throwable;
use function interface_exists;

if (interface_exists(Throwable::class, false)) {
    /**
     * The base interface for all Stripe exceptions.
     */
    interface ExceptionInterface extends Throwable
    {
    }
} else {
    /**
     * The base interface for all Stripe exceptions.
     */
    // phpcs:disable PSR1.Classes.ClassDeclaration.MultipleClasses
    interface ExceptionInterface
    {
    }
    // phpcs:enable
}
