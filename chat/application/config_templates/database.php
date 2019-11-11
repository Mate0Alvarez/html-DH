<?php

return [
	'default' => [
		'type' => 'PDO',
		'connection' => [
			'dsn' => 'mysql:host=localhost;dbname=botster',
			'username' => 'root',
			'password' => '',
			'persistent' => FALSE,
		],
		'table_prefix' => '',
		'charset' => 'utf8',
		'caching' => FALSE,
	],
];
