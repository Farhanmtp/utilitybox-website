<?php

namespace App\Http\Integrations\Powwr;

use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Connector;
use Saloon\Traits\OAuth2\ClientCredentialsGrant;
use Saloon\Traits\Plugins\AcceptsJson;

class ConnectApi extends Connector
{
    use AcceptsJson, ClientCredentialsGrant;

    protected $brokerage_id;
    protected $client_id;
    protected $client_secret;
    protected $client_scope;

    protected $token_endpoint = 'https://accounts.sbx.powwr.com/connect/token';

    public function __construct($brokerage_id = null)
    {
        $this->brokerage_id = $brokerage_id ?: config('powwr.brokerage_id');
        $this->client_id = config('powwr.client_id');
        $this->client_secret = config('powwr.client_secret');
        $this->client_scope = config('powwr.client_scope') . ' brokerage:' . $this->brokerage_id;

        $token = $this->getAccessToken()->getAccessToken();
        //$token = 'eyJhbGciOiJSUzI1NiIsImtpZCI6IlJKSlhDQ09YRDhaVkJYNjVPMTRFUFhIMU1MTkRVNkdGTUFBR0daM1ciLCJ0eXAiOiJKV1QifQ.eyJzdWIiOiJlbmVyZ3l3aXNlLWNybSIsIm5hbWUiOiJlbmVyZ3l3aXNlIiwidG9rZW5fdXNhZ2UiOiJhY2Nlc3NfdG9rZW4iLCJqdGkiOiI5YTI3ODJiZS00NTk0LTQwOTctYWMyNi0wZjFhOTQzMGRlZmUiLCJjZmRfbHZsIjoicHJpdmF0ZSIsInNjb3BlIjpbInVkZ3MtY29ubmVjdGVkLWFwaSIsInYycG9ydGFsLWFwaSIsImJyb2tlcmFnZTpFV0lTRUxURC0wMDEtQiJdLCJhdWQiOlsidWRncm91cF9yZXNvdXJjZSIsInVkZ3MtY29ubmVjdGVkLWFwaSIsInYycG9ydGFsLWFwaSIsImJyb2tlcmFnZTpFV0lTRUxURC0wMDEtQiJdLCJhenAiOiJlbmVyZ3l3aXNlLWNybSIsIm5iZiI6MTY5NDU5NjYzMCwiZXhwIjoxNjk0NjAwMjMwLCJpYXQiOjE2OTQ1OTY2MzAsImlzcyI6Imh0dHBzOi8vYWNjb3VudHMuc2J4LnBvd3dyLmNvbS8ifQ.oKAzSlEfenTaKBGPJd-Hqoxvh9E823TevMuqc6JVDnt6f1w_Cb2JSvC99q8_mWZy9PktoWTQxnfbA9V_Zr6-QJLmez6wpl2zaDDH8RdmlbnXk6J3-WPfXuxuVtV3bPB0SsdAHGQi-30hn9R-Nu1dE0ukahnGUWa5zlpLAbRnCbZwD43YivQhbyN5wWU5is0EFSIrBebSHySAS4dFik9EpCPF7O0og-HbN5mkWtC9utpBIZaIlVGf1OPyqZfE5656GbbGKAlX3J-dUlK1etR63TJqsaTpkjUILjZKXnseo8EBCWx_3CI8VbrVSMvNLQ-7r710UTNrUhPj9a7bpDbLsw';

        $this->withTokenAuth($token);
    }

    /**
     * The Base URL of the API
     *
     * @return string
     */
    public function resolveBaseUrl(): string
    {
        return 'https://connect.sbx.powwr.com/';
    }

    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setClientId($this->client_id)
            ->setClientSecret($this->client_secret)
            ->setDefaultScopes(explode(' ', $this->client_scope))
            ->setTokenEndpoint($this->token_endpoint)
            /*->setRequestModifier(function (Request $request) {
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
        return ['verify' => false,];
    }
}
