<?php

namespace App\Http\Integrations\Powwr\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class PowwrMeterLookupRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected $type;
    protected $data;

    protected $api_mode;
    protected $brokerage_id;

    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::POST;

    public function __construct(string|array $data, $type = 'postcode')
    {
        if (is_string($data)) {
            if ($type == 'postcode') {
                $this->data = ['PostCode' => $data];
            } else {
                if (strlen($data) == 13) {
                    $this->data = ['Mpan' => $data];
                } else if (strlen($data) <= 12) {
                    $this->data = ['Mprn' => $data];
                } else {
                    $this->data = ['MeterAddressId' => $data];
                }
            }
        } else {
            $this->data = $data;
        }

        $this->type = $type;

        $this->api_mode = config('powwr.api_mode');
        $this->brokerage_id = config('powwr.brokerage_id');
        $this->brokerage_email = config('powwr.brokerage_email');

        if ($this->api_mode == 'test') {
            $this->brokerage_id = config('powwr.test_brokerage_id') ?: config('powwr.brokerage_id');
            $this->brokerage_email = config('powwr.test_brokerage_email') ?: config('powwr.brokerage_email');
        }

        $this->body()->setJsonFlags(JSON_FORCE_OBJECT);
    }

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return $this->type == 'postcode' ? '/addressSearch' : '/meterSearch';
    }

    protected function defaultBody(): array
    {
        $brokerage_id = $this->brokerage_id;
        $brokerage_email = $this->brokerage_email;

        $body = [
            'CallerIdentification' => [
                'OrganisationPowwrId' => $brokerage_id,
                //'ApplicationId' => $brokerage_id,
                //'UserId' => $brokerage_email
            ],
        ];

        if ($this->type != 'postcode') {
            $body['IncludeConsumption'] = false;
            $body['IncludeCurrentSupplier'] = false;

            if (array_key_exists('MeterAddressId', $this->data) ||
                array_key_exists('Mprn', $this->data)) {
                $body['IncludeConsumption'] = true;
            }
            if (array_key_exists('MeterAddressId', $this->data)) {
                $body['IncludeCurrentSupplier'] = true;
            }
        }

        return array_merge($body, $this->data);
    }

    protected function defaultHeaders(): array
    {
        return [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    protected function defaultConfig(): array
    {
        return ['verify' => false,];
    }
}
