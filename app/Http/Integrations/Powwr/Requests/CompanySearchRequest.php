<?php

namespace App\Http\Integrations\Powwr\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CompanySearchRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected $input;

    protected $company_type = 'ltd,plc,llp';

    protected $api_key;

    protected $items_per_page = 50;

    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::GET;

    /**
     * @param string $input post code or meter number
     */
    public function __construct(string $q, int $items_per_page = null, $company_type = null)
    {
        $this->input = $q;

        if ($items_per_page) {
            $this->items_per_page = $items_per_page;
        }

        if (in_array(strtolower($company_type), ['llp', 'ltd', 'plc'])) {
            $this->company_type = strtolower($company_type) . ($company_type == 'ltd' ? ',plc' : '');
        }

        $this->api_key = config('powwr.company_house_api_key');

        $this->withBasicAuth($this->api_key, '');
    }

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        //docs: https://developer-specs.company-information.service.gov.uk/companies-house-public-data-api/reference/search/advanced-company-search
        return '/advanced-search/companies';
    }

    protected function defaultQuery(): array
    {
        return [
            'company_name_includes' => addslashes($this->input),
            'size' => (string)$this->items_per_page,
            'company_type' => $this->company_type,
            //'start_index' => '0',
            //'company_status' => 'active',
            //'restrictions' => 'active-companies'
        ];
    }

    /**
     * Default headers for every request
     *
     * @return string[]
     */
    protected function defaultHeaders(): array
    {
        return [
            'Cache-Control' => 'no-cache',
            //'Content-Type' => 'multipart/form-data',
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
