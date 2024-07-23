<?php

namespace App\Http\Integrations\Powwr\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class MeterLookupAddress extends Request implements HasBody
{
    use HasJsonBody;

    protected $by = 'post_code';

    protected $input;

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
    public function __construct(string $input)
    {

        $this->input = $input;

        $this->licenceCode = config('powwr.udcore_licence_code');

        $this->userName = config('powwr.udcore_user_name');

    }

    /**
     * @param string $by
     */
    public function byPostCode(): void
    {
        $this->by = 'post_code';
    }

    /**
     * @param string $by
     */
    public function byMpan(): void
    {
        $this->by = 'mpan';
    }

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/addresses';
    }

    protected function defaultBody(): array
    {
        $body = [
            'mpanSearch' => [
                "IncludeDomestic" => false,
                'ForceLiveResult' => true,
                'SecurityDetails' => [
                    'LicenceCode' => $this->licenceCode,
                    'mascaradeuser' => $this->userName
                ],
                "MpanSearchType" => 0,
                "Address" => [
                    "PostCode" => $this->input
                ]
            ]
        ];

        if (is_numeric($this->input)) {
            unset($body['mpanSearch']['Address']);

            $body['mpanSearch']['MpanSearchType'] = 2;
            $body['mpanSearch']['BottomLine'] = $this->input;
        }

        return $body;
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
        return ['verify' => false];
    }
}
