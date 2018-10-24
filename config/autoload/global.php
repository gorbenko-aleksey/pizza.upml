<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

$timeZone = 'UTC';
date_default_timezone_set($timeZone);

return [
    // For php.ini
    'session_config' => [
        'cookie_lifetime' => 60 * 60 * 24 * 15,
        'gc_maxlifetime' => 60 * 60 * 24 * 15,
    ],
    'session_storage' => [
        'type' => \Zend\Session\Storage\SessionArrayStorage::class
    ],
    // Custom session settings
    'session' => [
        'remember_me' => 60 * 60 * 24 * 30,
    ],
    'time_zone' => $timeZone,
];
