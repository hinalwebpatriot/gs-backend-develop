<?php

use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
        ],

        'fail-engagement' => [
            'driver' => 'single',
            'path' => storage_path('logs/import-engagement-fail.log'),
            'level' => 'debug',
        ],

        'fail-wedding' => [
            'driver' => 'single',
            'path' => storage_path('logs/import-wedding-fail.log'),
            'level' => 'debug',
        ],

        'fail-diamonds' => [
            'driver' => 'daily',
            'path' => storage_path('logs/import-diamonds-fail.log'),
            'level' => 'debug',
            'days' => 7,
        ],

        'import-prod' => [
            'driver' => 'single',
            'path' => storage_path('logs/import/import-prod.log'),
            'level' => 'debug',
        ],

        'create-update-diamonds' => [
            'driver' => 'daily',
            'path' => storage_path('logs/import/create-upd-deb.log'),
            'level' => 'debug',
            'days' => 7,
        ],

        'error-create-diamond' => [
            'driver' => 'daily',
            'path' => storage_path('logs/import/create-err.log'),
            'level' => 'debug',
            'days' => 7,
        ],

        'error-update-diamond' => [
            'driver' => 'daily',
            'path' => storage_path('logs/import/update-err.log'),
            'level' => 'debug',
            'days' => 7,
        ],

        'error-del-diamond' => [
            'driver' => 'daily',
            'path' => storage_path('logs/import/delete-err.log'),
            'level' => 'debug',
            'days' => 7,
        ],

        'success-engagement' => [
            'driver' => 'single',
            'path' => storage_path('logs/import-engagement-success.log'),
            'level' => 'debug',
        ],

        'success-wedding' => [
            'driver' => 'single',
            'path' => storage_path('logs/import-wedding-success.log'),
            'level' => 'debug',
        ],

        'success-diamonds' => [
            'driver' => 'single',
            'path' => storage_path('logs/import-diamonds-success.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'permission' => 0664,
            'days' => 14,
        ],

        'payment' => [
            'driver' => 'daily',
            'path' => storage_path('logs/payments/payment.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'search' => [
            'driver' => 'daily',
            'path' => storage_path('logs/search/search.log'),
            'level' => 'debug',
            'days' => 7,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'papertrail' => [
            'driver'  => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'with' => [
                'stream' => 'php://stderr',
            ],
            'formatter' => Monolog\Formatter\JsonFormatter::class
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],
    ],

];
