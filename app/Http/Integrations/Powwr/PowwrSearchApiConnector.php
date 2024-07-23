<?php

namespace App\Http\Integrations\Powwr;

use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Connector;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\OAuth2\ClientCredentialsGrant;
use Saloon\Traits\Plugins\AcceptsJson;

class PowwrSearchApiConnector extends Connector
{
    use AcceptsJson, HasJsonBody, ClientCredentialsGrant;

    protected $api_mode;
    protected $brokerage_id;
    protected $client_id;
    protected $client_secret;
    protected $client_scope;

    protected $token_endpoint = '';
    protected $api_endpoint = '';

    public function __construct()
    {
        $this->api_mode = config('powwr.api_mode');
        $this->token_endpoint = config('powwr.token_url');
        $this->api_endpoint = config('powwr.api_endpoint');
        $this->brokerage_id = config('powwr.brokerage_id');
        $this->client_id = config('powwr.client_id');
        $this->client_secret = config('powwr.client_secret');
        //$this->client_scope = config('powwr.client_scope') . ' brokerage:' . $this->brokerage_id;

        if ($this->api_mode == 'test') {
            $this->token_endpoint = config('powwr.test_token_url');
            $this->api_endpoint = config('powwr.test_api_endpoint') ?: config('powwr.api_endpoint');
            $this->brokerage_id = config('powwr.test_brokerage_id') ?: config('powwr.brokerage_id');
            $this->client_id = config('powwr.test_client_id') ?: config('powwr.client_id');
            $this->client_secret = config('powwr.test_client_secret') ?: config('powwr.client_secret');
        }


        $this->client_scope = 'udgs-lookupservices-meter-api' . ' brokerage:' . $this->brokerage_id;

        $token = $this->getAccessToken()->getAccessToken();


        $this->withTokenAuth($token);
    }

    /**
     * The Base URL of the API
     *
     * @return string
     */
    public function resolveBaseUrl(): string
    {
        return trim($this->api_endpoint, '/') . ($this->api_mode == 'test' ? '/api/Search' : '/meterlookupuk/Search');
    }

    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setClientId($this->client_id)
            ->setClientSecret($this->client_secret)
            ->setDefaultScopes(explode(' ', $this->client_scope))
            ->setTokenEndpoint($this->token_endpoint)/*->setRequestModifier(function (Request $request) {
                // Optional: Modify the requests being sent.
            })*/ ;
    }

    /**
     * Default headers for every request
     *
     * @return string[]
     */
    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Default HTTP client options
     *
     * @return string[]
     */
    protected function defaultConfig(): array
    {
        return ['verify' => false];
    }
}
