<?php

namespace App\Http\Integrations\Powwr\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class AddDocumentRequest extends Request implements HasBody
{
    use HasJsonBody;


    protected $data;

    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::POST;

    /**
     * @param $data
     */
    public function __construct($data)
    {

        $this->data = $data;

        $this->body()->setJsonFlags(JSON_FORCE_OBJECT);

    }

    protected function defaultConfig(): array
    {
        return ['verify' => false,];
    }

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/addDocument';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
}
