<?php

// File generated from our OpenAPI spec

namespace Stripe\Util;

use Stripe\Account;
use Stripe\AccountLink;
use Stripe\AlipayAccount;
use Stripe\ApplePayDomain;
use Stripe\ApplicationFee;
use Stripe\ApplicationFeeRefund;
use Stripe\Balance;
use Stripe\BalanceTransaction;
use Stripe\BankAccount;
use Stripe\BillingPortal\Configuration;
use Stripe\BitcoinReceiver;
use Stripe\BitcoinTransaction;
use Stripe\Capability;
use Stripe\Charge;
use Stripe\Checkout\Session;
use Stripe\Collection;
use Stripe\CountrySpec;
use Stripe\Coupon;
use Stripe\CreditNote;
use Stripe\CreditNoteLineItem;
use Stripe\Customer;
use Stripe\CustomerBalanceTransaction;
use Stripe\Discount;
use Stripe\EphemeralKey;
use Stripe\Event;
use Stripe\ExchangeRate;
use Stripe\File;
use Stripe\FileLink;
use Stripe\Identity\VerificationReport;
use Stripe\Identity\VerificationSession;
use Stripe\Invoice;
use Stripe\InvoiceItem;
use Stripe\InvoiceLineItem;
use Stripe\Issuing\Authorization;
use Stripe\Issuing\Card;
use Stripe\Issuing\CardDetails;
use Stripe\Issuing\Cardholder;
use Stripe\Issuing\Dispute;
use Stripe\Issuing\Transaction;
use Stripe\LineItem;
use Stripe\LoginLink;
use Stripe\Mandate;
use Stripe\Order;
use Stripe\OrderItem;
use Stripe\OrderReturn;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Payout;
use Stripe\Person;
use Stripe\Plan;
use Stripe\Price;
use Stripe\Product;
use Stripe\PromotionCode;
use Stripe\Radar\EarlyFraudWarning;
use Stripe\Radar\ValueList;
use Stripe\Radar\ValueListItem;
use Stripe\Recipient;
use Stripe\RecipientTransfer;
use Stripe\Refund;
use Stripe\Reporting\ReportRun;
use Stripe\Reporting\ReportType;
use Stripe\Review;
use Stripe\SetupAttempt;
use Stripe\SetupIntent;
use Stripe\Sigma\ScheduledQueryRun;
use Stripe\SKU;
use Stripe\Source;
use Stripe\SourceTransaction;
use Stripe\Subscription;
use Stripe\SubscriptionItem;
use Stripe\SubscriptionSchedule;
use Stripe\TaxId;
use Stripe\TaxRate;
use Stripe\Terminal\ConnectionToken;
use Stripe\Terminal\Location;
use Stripe\Terminal\Reader;
use Stripe\ThreeDSecure;
use Stripe\Token;
use Stripe\Topup;
use Stripe\Transfer;
use Stripe\TransferReversal;
use Stripe\UsageRecord;
use Stripe\UsageRecordSummary;
use Stripe\WebhookEndpoint;

class ObjectTypes
{
    /**
     * @var array Mapping from object types to resource classes
     */
    const mapping = [
        Account::OBJECT_NAME => Account::class,
        AccountLink::OBJECT_NAME => AccountLink::class,
        AlipayAccount::OBJECT_NAME => AlipayAccount::class,
        ApplePayDomain::OBJECT_NAME => ApplePayDomain::class,
        ApplicationFee::OBJECT_NAME => ApplicationFee::class,
        ApplicationFeeRefund::OBJECT_NAME => ApplicationFeeRefund::class,
        Balance::OBJECT_NAME => Balance::class,
        BalanceTransaction::OBJECT_NAME => BalanceTransaction::class,
        BankAccount::OBJECT_NAME => BankAccount::class,
        Configuration::OBJECT_NAME => Configuration::class,
        \Stripe\BillingPortal\Session::OBJECT_NAME => \Stripe\BillingPortal\Session::class,
        BitcoinReceiver::OBJECT_NAME => BitcoinReceiver::class,
        BitcoinTransaction::OBJECT_NAME => BitcoinTransaction::class,
        Capability::OBJECT_NAME => Capability::class,
        \Stripe\Card::OBJECT_NAME => \Stripe\Card::class,
        Charge::OBJECT_NAME => Charge::class,
        Session::OBJECT_NAME => Session::class,
        Collection::OBJECT_NAME => Collection::class,
        CountrySpec::OBJECT_NAME => CountrySpec::class,
        Coupon::OBJECT_NAME => Coupon::class,
        CreditNote::OBJECT_NAME => CreditNote::class,
        CreditNoteLineItem::OBJECT_NAME => CreditNoteLineItem::class,
        Customer::OBJECT_NAME => Customer::class,
        CustomerBalanceTransaction::OBJECT_NAME => CustomerBalanceTransaction::class,
        Discount::OBJECT_NAME => Discount::class,
        \Stripe\Dispute::OBJECT_NAME => \Stripe\Dispute::class,
        EphemeralKey::OBJECT_NAME => EphemeralKey::class,
        Event::OBJECT_NAME => Event::class,
        ExchangeRate::OBJECT_NAME => ExchangeRate::class,
        File::OBJECT_NAME => File::class,
        File::OBJECT_NAME_ALT => File::class,
        FileLink::OBJECT_NAME => FileLink::class,
        VerificationReport::OBJECT_NAME => VerificationReport::class,
        VerificationSession::OBJECT_NAME => VerificationSession::class,
        Invoice::OBJECT_NAME => Invoice::class,
        InvoiceItem::OBJECT_NAME => InvoiceItem::class,
        InvoiceLineItem::OBJECT_NAME => InvoiceLineItem::class,
        Authorization::OBJECT_NAME => Authorization::class,
        Card::OBJECT_NAME => Card::class,
        CardDetails::OBJECT_NAME => CardDetails::class,
        Cardholder::OBJECT_NAME => Cardholder::class,
        Dispute::OBJECT_NAME => Dispute::class,
        Transaction::OBJECT_NAME => Transaction::class,
        LineItem::OBJECT_NAME => LineItem::class,
        LoginLink::OBJECT_NAME => LoginLink::class,
        Mandate::OBJECT_NAME => Mandate::class,
        Order::OBJECT_NAME => Order::class,
        OrderItem::OBJECT_NAME => OrderItem::class,
        OrderReturn::OBJECT_NAME => OrderReturn::class,
        PaymentIntent::OBJECT_NAME => PaymentIntent::class,
        PaymentMethod::OBJECT_NAME => PaymentMethod::class,
        Payout::OBJECT_NAME => Payout::class,
        Person::OBJECT_NAME => Person::class,
        Plan::OBJECT_NAME => Plan::class,
        Price::OBJECT_NAME => Price::class,
        Product::OBJECT_NAME => Product::class,
        PromotionCode::OBJECT_NAME => PromotionCode::class,
        EarlyFraudWarning::OBJECT_NAME => EarlyFraudWarning::class,
        ValueList::OBJECT_NAME => ValueList::class,
        ValueListItem::OBJECT_NAME => ValueListItem::class,
        Recipient::OBJECT_NAME => Recipient::class,
        RecipientTransfer::OBJECT_NAME => RecipientTransfer::class,
        Refund::OBJECT_NAME => Refund::class,
        ReportRun::OBJECT_NAME => ReportRun::class,
        ReportType::OBJECT_NAME => ReportType::class,
        Review::OBJECT_NAME => Review::class,
        SetupAttempt::OBJECT_NAME => SetupAttempt::class,
        SetupIntent::OBJECT_NAME => SetupIntent::class,
        ScheduledQueryRun::OBJECT_NAME => ScheduledQueryRun::class,
        SKU::OBJECT_NAME => SKU::class,
        Source::OBJECT_NAME => Source::class,
        SourceTransaction::OBJECT_NAME => SourceTransaction::class,
        Subscription::OBJECT_NAME => Subscription::class,
        SubscriptionItem::OBJECT_NAME => SubscriptionItem::class,
        SubscriptionSchedule::OBJECT_NAME => SubscriptionSchedule::class,
        TaxId::OBJECT_NAME => TaxId::class,
        TaxRate::OBJECT_NAME => TaxRate::class,
        ConnectionToken::OBJECT_NAME => ConnectionToken::class,
        Location::OBJECT_NAME => Location::class,
        Reader::OBJECT_NAME => Reader::class,
        ThreeDSecure::OBJECT_NAME => ThreeDSecure::class,
        Token::OBJECT_NAME => Token::class,
        Topup::OBJECT_NAME => Topup::class,
        Transfer::OBJECT_NAME => Transfer::class,
        TransferReversal::OBJECT_NAME => TransferReversal::class,
        UsageRecord::OBJECT_NAME => UsageRecord::class,
        UsageRecordSummary::OBJECT_NAME => UsageRecordSummary::class,
        WebhookEndpoint::OBJECT_NAME => WebhookEndpoint::class,
    ];
}
