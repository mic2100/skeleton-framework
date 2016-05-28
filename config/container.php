<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

return [
    /**
     * @var Logger
     */
    'Logger' => function () use ($container) {
        $config = $container['Config'];
        $logger = new Logger('converse');
        $path = $config->get('log-path');
        $logger->pushHandler(new StreamHandler($path . date('Y-m-d a') . '.log'));

        return $logger;
    },
];
