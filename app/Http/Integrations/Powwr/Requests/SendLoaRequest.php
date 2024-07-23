<?php

namespace App\Http\Integrations\Powwr\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SendLoaRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected $licenceCode;

    protected $userName;

    protected $data;
    protected $brokerageEmail;

    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::POST;

    /**
     * @param string $input post code or meter number
     */
    public function __construct(array $data)
    {
        $this->data = $data;

        $this->licenceCode = config('powwr.udcore_licence_code');

        $this->brokerageEmail = config('powwr.brokerage_email');

        $this->userName = config('powwr.udcore_user_name');
    }

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/sbx/DocusignService.svc/web/sendloa';
    }

    protected function defaultBody(): array
    {
        $docusign_username = config('powwr.docusign_username');
        $docusign_password = config('powwr.docusign_password');

        $body = array_merge([
            'SecurityDetails' => [
                'LicenceCode' => $this->licenceCode,
                'mascaradeuser' => $this->userName
            ],
            "WebhookUrl" => str_replace('http:', 'https:', route('webhooks.docusign')),
            "AgentEmail" => $this->brokerageEmail,
            "InterceptEmail" => $this->brokerageEmail,
        ], $this->data);

        if ($docusign_username && $docusign_password) {
            $body['Credentials'] = [
                'Username' => $docusign_username,
                'Password' => $docusign_password
            ];
        }

        return ['docusignDetails' => $body];
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
