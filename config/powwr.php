<?php

return [

    'brokerage_id' => env('POWWR_BROKERAGE_ID', 'EWISELTD-001-B'),

    'brokerage_email' => env('POWWR_BROKERAGE_EMAIL', 'pramod@utilitybox.org.uk'),

    'client_id' => env('POWWR_CLIENT_ID', 'energywise-crm'),

    'client_secret' => env('POWWR_CLIENT_SECRET', '3788a759-91c3-b56e-7ed1-2d7cbc803fb3'),

    'client_scope' => env('POWWR_CLIENT_SCOPE', 'udgs-connected-api v2portal-api'),

    /**
     * UD Core Api details
     */
    'udcore_licence_code' => env('POWWR_UDCORE_LICENCE_CODE', 'B895EE64-D140-4C4B-B864-68A3560C383C'),

    'udcore_user_name' => env('POWWR_UDCORE_USER_NAME', 'Test User'),

    'docusign_username' => env('POWWR_DOCUSIGN_USERNAME', 'sawera.ijaz@mtp.tech'),
    'docusign_password' => env('POWWR_DOCUSIGN_PASSWORD', '11223344aA!'),

    'docusign_loa_template' => env('POWWR_DOCUSIGN_LOA_TEMPLATE', 'Utility Box LOA'),
    'docusign_loa_template_eonnext' => env('POWWR_DOCUSIGN_LOA_TEMPLATE_EONNEXT', 'Utility Box Dual LOA'),

    /**
     * Company House
     */
    'company_house_api_key' => env('POWWR_COMPANIES_HOUSE_API_KEY', '7855982e-ff7e-47a3-876b-b0ddb2972e3b'),

];
