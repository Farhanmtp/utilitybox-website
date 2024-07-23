<?php

namespace App\Http\Integrations\Powwr\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class OffersRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected $type;

    protected $data;

    protected $licenceCode;

    protected $userName;

    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::POST;

    /**
     * @param string $input post code or meter number
     */
    public function __construct(string $type, array $data)
    {
        $this->type = $type;

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
        return '/' . $this->type . 'prices';
    }

    protected function defaultBody(): array
    {
        $quoteDetails = [
            'SecurityDetails' => [
                'LicenceCode' => $this->licenceCode,
                "mascaradeuser" => $this->userName
            ]
        ];

        $quoteDetails['Settings'] = [
            //['key' => "ShowNoQuoteReason", "value" => "true"],
            ['key' => "CreditScore", "value" => "50"]
        ];

        $quoteDetails = ['quoteDetails' => array_merge($quoteDetails, $this->data)];

        //echo json_encode($quoteDetails);die;

        return $quoteDetails;
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
        return [
            'verify' => false,
            'timeout' => 300,
        ];
    }
}
