<?php

/**
 * This file is part of laravel-auto-sync,
 * an automatic synchronize MySql database changes on remote locations for laravel applications (Multi slave servers to one Master server replication).
 *
 * @license MIT
 * @package mohammed-zaki/laravel-auto-sync
 */
return [
    'server'             => [
        'id'   => env('AUTO_SYNC_SERVER_ID', 00),
        'name' => env('AUTO_SYNC_SERVER_NAME', 'server-name'),
    ],
    'master_server'      => [
        'url'           => env('AUTO_SYNC_MASTER_SERVER_URL', ''),
        'username'      => env('AUTO_SYNC_MASTER_SERVER_USERNAME', ''),
        'password'      => env('AUTO_SYNC_MASTER_SERVER_PASSWORD', ''),
        'sync_api_name' => '/api/autosync/pushNewSyncFile',
    ],
    'main_folder'        => storage_path('sync'),
    'channel'            => env('AUTO_SYNC_SERVER_NAME', 'server-name') . '-' . env('AUTO_SYNC_SERVER_ID', 00),
    'folders'            => [
        'current_logger'  => 'logger',
        'current_syncing' => 'syncing',
        'synced'          => 'synced'
    ],
    'sync_schedule_time' => '*/30 */3 * * * *',
    'sync_queue'         => [
        'name'   => 'AutoSyncProcessing',
        'driver' => 'database',
        'delay'  => 5,
    ],
    'file'               => [
        'prefix'        => 'bin',
        'current_state' => 'current',
        'max_records'   => '50',
    ],
    'ignored_tables'     => [
        '`oauth_access_tokens`',
        '`oauth_auth_codes`',
        '`oauth_clients`',
        '`oauth_personal_access_clients`',
        '`oauth_refresh_tokens`',
        '`password_resets`',
        '`migrations`',
        '`jobs`',
    ]
];