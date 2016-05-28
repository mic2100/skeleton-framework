<?php

/**
 * Routes will be parsed by the bootloader and applied to the Router library
 *
 * They should setup like this:
 *
 * [
 *  [
 *      'method' => 'GET',
 *      'path' => 'account/login',
 *      'handler' => '<class or container name>::<method>',
 *  ],
 *  [
 *      'method' => 'POST',
 *      'path' => 'account/login',
 *      'handler' => '<class or container name>::<method>',
 *  ],
 *  [
 *      'method' => 'PUT',
 *      'path' => 'account/username/password-update',
 *      'handler' => '<class or container name>::<method>',
 *  ],
 * ]
 */
return [
    [
        'method' => 'POST',
        'path' => '/api/v1/user/login',
        'handler' => 'LoginV1::create',
    ],
];
