<?php
return [
	'driver' => env('DB_DRIVER'),
	'host' => env('DB_HOST'),
	'port' => env('DB_PORT'),
	'dbname' => env('DB_DATABASE'),
	'prefix' => env('DB_TABLE_PREFIX'),
	'user' => env('DB_USER'),
	'password' => env('DB_PASSWORD'),
	'charset' => env('DB_CHARSET', 'utf8mb4'),
	'options' => [
		'collation' => env('DB_COLLATION', 'utf8mb4_general_ci'),
	]
];
