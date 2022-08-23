<?php

// File generated from our OpenAPI spec

namespace Stripe;

use Stripe\Service\AccountLinkService;
use Stripe\Service\AccountService;
use Stripe\Service\ApplePayDomainService;
use Stripe\Service\ApplicationFeeService;
use Stripe\Service\BalanceService;
use Stripe\Service\BalanceTransactionService;
use Stripe\Service\BillingPortal\BillingPortalServiceFactory;
use Stripe\Service\ChargeService;
use Stripe\Service\Checkout\CheckoutServiceFactory;
use Stripe\Service\CoreServiceFactory;
use Stripe\Service\CountrySpecService;
use Stripe\Service\CouponService;
use Stripe\Service\CreditNoteService;
use Stripe\Service\CustomerService;
use Stripe\Service\DisputeService;
use Stripe\Service\EphemeralKeyService;
use Stripe\Service\EventService;
use Stripe\Service\ExchangeRateService;
use Stripe\Service\FileLinkService;
use Stripe\Service\FileService;
use Stripe\Service\Identity\IdentityServiceFactory;
use Stripe\Service\InvoiceItemService;
use Stripe\Service\InvoiceService;
use Stripe\Service\Issuing\IssuingServiceFactory;
use Stripe\Service\MandateService;
use Stripe\Service\OAuthService;
use Stripe\Service\OrderReturnService;
use Stripe\Service\OrderService;
use Stripe\Service\PaymentIntentService;
use Stripe\Service\PaymentMethodService;
use Stripe\Service\PayoutService;
use Stripe\Service\PlanService;
use Stripe\Service\PriceService;
use Stripe\Service\ProductService;
use Stripe\Service\PromotionCodeService;
use Stripe\Service\Radar\RadarServiceFactory;
use Stripe\Service\RefundService;
use Stripe\Service\Reporting\ReportingServiceFactory;
use Stripe\Service\ReviewService;
use Stripe\Service\SetupAttemptService;
use Stripe\Service\SetupIntentService;
use Stripe\Service\Sigma\SigmaServiceFactory;
use Stripe\Service\SkuService;
use Stripe\Service\SourceService;
use Stripe\Service\SubscriptionItemService;
use Stripe\Service\SubscriptionScheduleService;
use Stripe\Service\SubscriptionService;
use Stripe\Service\TaxRateService;
use Stripe\Service\Terminal\TerminalServiceFactory;
use Stripe\Service\TokenService;
use Stripe\Service\TopupService;
use Stripe\Service\TransferService;
use Stripe\Service\WebhookEndpointService;

/**
 * Client used to send requests to Stripe's API.
 *
 * @property AccountLinkService $accountLinks
 * @property AccountService $accounts
 * @property ApplePayDomainService $applePayDomains
 * @property ApplicationFeeService $applicationFees
 * @property BalanceService $balance
 * @property BalanceTransactionService $balanceTransactions
 * @property BillingPortalServiceFactory $billingPortal
 * @property ChargeService $charges
 * @property CheckoutServiceFactory $checkout
 * @property CountrySpecService $countrySpecs
 * @property CouponService $coupons
 * @property CreditNoteService $creditNotes
 * @property CustomerService $customers
 * @property DisputeService $disputes
 * @property EphemeralKeyService $ephemeralKeys
 * @property EventService $events
 * @property ExchangeRateService $exchangeRates
 * @property FileLinkService $fileLinks
 * @property FileService $files
 * @property IdentityServiceFactory $identity
 * @property InvoiceItemService $invoiceItems
 * @property InvoiceService $invoices
 * @property IssuingServiceFactory $issuing
 * @property MandateService $mandates
 * @property OAuthService $oauth
 * @property OrderReturnService $orderReturns
 * @property OrderService $orders
 * @property PaymentIntentService $paymentIntents
 * @property PaymentMethodService $paymentMethods
 * @property PayoutService $payouts
 * @property PlanService $plans
 * @property PriceService $prices
 * @property ProductService $products
 * @property PromotionCodeService $promotionCodes
 * @property RadarServiceFactory $radar
 * @property RefundService $refunds
 * @property ReportingServiceFactory $reporting
 * @property ReviewService $reviews
 * @property SetupAttemptService $setupAttempts
 * @property SetupIntentService $setupIntents
 * @property SigmaServiceFactory $sigma
 * @property SkuService $skus
 * @property SourceService $sources
 * @property SubscriptionItemService $subscriptionItems
 * @property SubscriptionScheduleService $subscriptionSchedules
 * @property SubscriptionService $subscriptions
 * @property TaxRateService $taxRates
 * @property TerminalServiceFactory $terminal
 * @property TokenService $tokens
 * @property TopupService $topups
 * @property TransferService $transfers
 * @property WebhookEndpointService $webhookEndpoints
 */
class StripeClient extends BaseStripeClient
{
    /**
     * @var CoreServiceFactory
     */
    private $coreServiceFactory;

    public function __get($name)
    {
        if (null === $this->coreServiceFactory) {
            $this->coreServiceFactory = new CoreServiceFactory($this);
        }

        return $this->coreServiceFactory->__get($name);
    }
}
