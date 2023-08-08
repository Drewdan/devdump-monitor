<?php

return [
	'ingress_url' => env('DEVDUMP_INGRESS_URL', ''),
	'key' => env('DEVDUMP_KEY', ''),
	'user' => [
		'retrieve' => env('DEVDUMP_LOG_USER', false),
		'model' => \App\Models\User::class,
		'identifier' => 'email',
	],
];
