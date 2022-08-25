<?php

namespace Stripe;

use Stripe\Exception\AuthenticationException;
use Stripe\Exception\InvalidArgumentException;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Util\RequestOptions;
use Stripe\Util\Util;
use function array_diff;
use function array_keys;
use function array_merge;
use function get_class;
use function implode;
use function is_array;
use function is_string;
use function preg_match;

class BaseStripeClient implements StripeClientInterface
{
    /** @var string default base URL for Stripe's API */
    const DEFAULT_API_BASE = 'https://api.stripe.com';

    /** @var string default base URL for Stripe's OAuth API */
    const DEFAULT_CONNECT_BASE = 'https://connect.stripe.com';

    /** @var string default base URL for Stripe's Files API */
    const DEFAULT_FILES_BASE = 'https://files.stripe.com';

    /** @var array<string, mixed> */
    private $config;

    /** @var RequestOptions */
    private $defaultOpts;

    /**
     * Initializes a new instance of the {@link BaseStripeClient} class.
     *
     * The constructor takes a single argument. The argument can be a string, in which case it
     * should be the API key. It can also be an array with various configuration settings.
     *
     * Configuration settings include the following options:
     *
     * - api_key (null|string): the Stripe API key, to be used in regular API requests.
     * - client_id (null|string): the Stripe client ID, to be used in OAuth requests.
     * - stripe_account (null|string): a Stripe account ID. If set, all requests sent by the client
     *   will automatically use the {@code Stripe-Account} header with that account ID.
     * - stripe_version (null|string): a Stripe API verion. If set, all requests sent by the client
     *   will include the {@code Stripe-Version} header with that API version.
     *
     * The following configuration settings are also available, though setting these should rarely be necessary
     * (only useful if you want to send requests to a mock server like stripe-mock):
     *
     * - api_base (string): the base URL for regular API requests. Defaults to
     *   {@link DEFAULT_API_BASE}.
     * - connect_base (string): the base URL for OAuth requests. Defaults to
     *   {@link DEFAULT_CONNECT_BASE}.
     * - files_base (string): the base URL for file creation requests. Defaults to
     *   {@link DEFAULT_FILES_BASE}.
     *
     * @param array<string, mixed>|string $config the API key as a string, or an array containing
     *   the client configuration settings
     */
    public function __construct($config = [])
    {
        if (is_string($config)) {
            $config = ['api_key' => $config];
        } elseif (!is_array($config)) {
            throw new InvalidArgumentException('$config must be a string or an array');
        }

        $config = array_merge($this->getDefaultConfig(), $config);
        $this->validateConfig($config);

        $this->config = $config;

        $this->defaultOpts = RequestOptions::parse([
            'stripe_account' => $config['stripe_account'],
            'stripe_version' => $config['stripe_version'],
        ]);
    }

    /**
     * Gets the API key used by the client to send requests.
     *
     * @return null|string the API key used by the client to send requests
     */
    public function getApiKey()
    {
        return $this->config['api_key'];
    }

    /**
     * Gets the client ID used by the client in OAuth requests.
     *
     * @return null|string the client ID used by the client in OAuth requests
     */
    public function getClientId()
    {
        return $this->config['client_id'];
    }

    /**
     * Gets the base URL for Stripe's API.
     *
     * @return string the base URL for Stripe's API
     */
    public function getApiBase()
    {
        return $this->config['api_base'];
    }

    /**
     * Gets the base URL for Stripe's OAuth API.
     *
     * @return string the base URL for Stripe's OAuth API
     */
    public function getConnectBase()
    {
        return $this->config['connect_base'];
    }

    /**
     * Gets the base URL for Stripe's Files API.
     *
     * @return string the base URL for Stripe's Files API
     */
    public function getFilesBase()
    {
        return $this->config['files_base'];
    }

    /**
     * Sends a request to Stripe's API.
     *
     * @param string $method the HTTP method
     * @param string $path the path of the request
     * @param array $params the parameters of the request
     * @param array|RequestOptions $opts the special modifiers of the request
     *
     * @return StripeObject the object returned by Stripe's API
     */
    public function request($method, $path, $params, $opts)
    {
        $opts = $this->defaultOpts->merge($opts, true);
        $baseUrl = $opts->apiBase ?: $this->getApiBase();
        $requestor = new ApiRequestor($this->apiKeyForRequest($opts), $baseUrl);
        list($response, $opts->apiKey) = $requestor->request($method, $path, $params, $opts->headers);
        $opts->discardNonPersistentHeaders();
        $obj = Util::convertToStripeObject($response->json, $opts);
        $obj->setLastResponse($response);

        return $obj;
    }

    /**
     * Sends a request to Stripe's API.
     *
     * @param string $method the HTTP method
     * @param string $path the path of the request
     * @param array $params the parameters of the request
     * @param array|RequestOptions $opts the special modifiers of the request
     *
     * @return Collection of ApiResources
     */
    public function requestCollection($method, $path, $params, $opts)
    {
        $obj = $this->request($method, $path, $params, $opts);
        if (!($obj instanceof Collection)) {
            $received_class = get_class($obj);
            $msg = "Expected to receive `Stripe\\Collection` object from Stripe API. Instead received `{$received_class}`.";

            throw new UnexpectedValueException($msg);
        }
        $obj->setFilters($params);

        return $obj;
    }

    /**
     * @param RequestOptions $opts
     *
     * @throws AuthenticationException
     *
     * @return string
     */
    private function apiKeyForRequest($opts)
    {
        $apiKey = $opts->apiKey ?: $this->getApiKey();

        if (null === $apiKey) {
            $msg = 'No API key provided. Set your API key when constructing the '
                . 'StripeClient instance, or provide it on a per-request basis '
                . 'using the `api_key` key in the $opts argument.';

            throw new AuthenticationException($msg);
        }

        return $apiKey;
    }

    /**
     * TODO: replace this with a private constant when we drop support for PHP < 5.
     *
     * @return array<string, mixed>
     */
    private function getDefaultConfig()
    {
        return [
            'api_key' => null,
            'client_id' => null,
            'stripe_account' => null,
            'stripe_version' => null,
            'api_base' => self::DEFAULT_API_BASE,
            'connect_base' => self::DEFAULT_CONNECT_BASE,
            'files_base' => self::DEFAULT_FILES_BASE,
        ];
    }

    /**
     * @param array<string, mixed> $config
     *
     * @throws InvalidArgumentException
     */
    private function validateConfig($config)
    {
        // api_key
        if (null !== $config['api_key'] && !is_string($config['api_key'])) {
            throw new InvalidArgumentException('api_key must be null or a string');
        }

        if (null !== $config['api_key'] && ('' === $config['api_key'])) {
            $msg = 'api_key cannot be the empty string';

            throw new InvalidArgumentException($msg);
        }

        if (null !== $config['api_key'] && (preg_match('/\s/', $config['api_key']))) {
            $msg = 'api_key cannot contain whitespace';

            throw new InvalidArgumentException($msg);
        }

        // client_id
        if (null !== $config['client_id'] && !is_string($config['client_id'])) {
            throw new InvalidArgumentException('client_id must be null or a string');
        }

        // stripe_account
        if (null !== $config['stripe_account'] && !is_string($config['stripe_account'])) {
            throw new InvalidArgumentException('stripe_account must be null or a string');
        }

        // stripe_version
        if (null !== $config['stripe_version'] && !is_string($config['stripe_version'])) {
            throw new InvalidArgumentException('stripe_version must be null or a string');
        }

        // api_base
        if (!is_string($config['api_base'])) {
            throw new InvalidArgumentException('api_base must be a string');
        }

        // connect_base
        if (!is_string($config['connect_base'])) {
            throw new InvalidArgumentException('connect_base must be a string');
        }

        // files_base
        if (!is_string($config['files_base'])) {
            throw new InvalidArgumentException('files_base must be a string');
        }

        // check absence of extra keys
        $extraConfigKeys = array_diff(array_keys($config), array_keys($this->getDefaultConfig()));
        if (!empty($extraConfigKeys)) {
            // Wrap in single quote to more easily catch trailing spaces errors
            $invalidKeys = "'" . implode("', '", $extraConfigKeys) . "'";

            throw new InvalidArgumentException('Found unknown key(s) in configuration array: ' . $invalidKeys);
        }
    }
}