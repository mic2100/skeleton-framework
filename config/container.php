<?php

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Framework\Domain\Configuration\Config;
use Framework\Domain\Configuration\ConfigItem;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

return [
    [
        'shared' => true,
        'name' => 'logger',
        'concrete' => function () use ($container) {
            $config = $container['Config'];
            $logger = new Logger('converse');
            $path = $config->get('log-path');
            $logger->pushHandler(new StreamHandler($path . date('Y-m-d a') . '.log'));

            return $logger;
        },
    ],
    [
        'shared' => true,
        'name' => 'config',
        'concrete' => function () {
                $configItems = require_once __DIR__ . '/config.php';
                $object = new Config();
                foreach ($configItems as $name => $value) {
                        $object->addItem(new ConfigItem($name, $value));
                }

                return $object;
        },
    ],
    [
        'shared' => true,
        'name' => 'db',
        'concrete' => function () {
            $config = require_once __DIR__ . '/db.php';
            $dbConfig = new Configuration();

            return DriverManager::getConnection($config, $dbConfig);
        },
    ],
];
