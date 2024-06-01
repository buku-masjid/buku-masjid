<?php

return [
    /**
     * Ref: https://laravel.com/docs/10.x/requests#configuring-trusted-proxies
     * Ref: https://github.com/fideloper/TrustedProxy/blob/a751f2bc86dd8e6cfef12dc0cbdada82f5a18750/config/trustedproxy.php#L5-L18
     */
    'proxies' => env('APP_TRUSTED_PROXIES'),
];
