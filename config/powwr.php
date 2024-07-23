<?php

return [

    'api_mode' => 'test',

    'brokerage_id' => 'EWISELTD-001-B',
    'test_brokerage_id' => 'EWISELTD-001-B',

    'brokerage_email' => 'pramod@utilitybox.org.uk',
    'test_brokerage_email' => 'farhan@utilitybox.org.uk',

    'client_id' => 'a67e3a5a-21c2-4511-a41c-ffd04604a084',
    'test_client_id' => 'energywise-crm',

    'client_secret' => '3ecbd950-0e23-43ea-9067-6516ca4d74fa',
    'test_client_secret' => '3788a759-91c3-b56e-7ed1-2d7cbc803fb3',

    'client_scope' => 'udgs-connected-api v2portal-api',

    // Token url for api authentication
    'token_url' => env('POWWR_TOKEN_URL', 'https://accounts.powwr.com/connect/token'),
    'test_token_url' => env('POWWR_TEST_TOKEN_URL', 'https://accounts.sbx.powwr.com/connect/token'),

    //Api endpoints
    'api_endpoint' => env('POWWR_API_ENDPOINT', 'https://api.platform.powwr.net'),
    'test_api_endpoint' => env('POWWR_API_ENDPOINT', 'https://meterlookupuk-sbx.dev-platform.powwr.net'),

    /**
     * UD Core Api details
     */
    'udcore_licence_code' => 'B895EE64-D140-4C4B-B864-68A3560C383C',

    'udcore_user_name' => 'Test User',

    'docusign_api_mode' => 'test',

    'docusign_username' => 'sawera.ijaz@mtp.tech',
    'docusign_test_username' => 'sawera.ijaz@mtp.tech',

    'docusign_password' => '11223344aA!',
    'docusign_test_password' => '11223344aA!',

    'docusign_loa_template' => 'Utility Box LOA',
    'docusign_loa_template_eonnext' => 'Utility Box Dual LOA',

    /**
     * Company House
     */
    'company_house_api_key' => '7855982e-ff7e-47a3-876b-b0ddb2972e3b',
];
