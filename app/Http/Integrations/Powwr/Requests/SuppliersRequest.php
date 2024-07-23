<?php

namespace App\Http\Integrations\Powwr\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SuppliersRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected $licenceCode;

    protected $userName;

    protected $data;

    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::POST;

    /**
     * @param string $input post code or meter number
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;

        $this->licenceCode = config('powwr.udcore_licence_code');

        $this->userName = config('powwr.udcore_user_name');
    }

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/Service.svc/web/pricechange';
    }

    protected function defaultBody(): array
    {
        $body = array_merge([
            'SecurityDetails' => [
                'LicenceCode' => $this->licenceCode,
                'mascaradeuser' => $this->userName
            ],
            "IncludeFuture" => false,
            "FullHistory" => false,
        ], $this->data);

        return ['priceChangeRequest' => $body];
    }

    /**
     * Default headers for every request
     *
     * @return string[]
     */
    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
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
        return ['verify' => false,'timeout' => 1000];
    }
}
