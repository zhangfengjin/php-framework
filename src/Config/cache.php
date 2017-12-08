<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2017/12/5
 * Time: 21:52
 */
return [
    'default' => 'redis',
    'stores' => [
        'database' => [
            'driver' => 'database',
            'table' => 'cache',
            'connection' => null,
        ],
        'file' => [
            'driver' => 'file',
            'path' => '',
        ],
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ]
    ],
    'prefix' => 'xy',

];
