<?php
return [
	'paths' => ['api/*', 'eventos'], // ← adiciona 'eventos' aqui
	'allowed_methods' => ['*'],
	'allowed_origins' => ['*'], // ou coloque o IP/domínio exato se preferir
	'allowed_headers' => ['*'],
	'supports_credentials' => false,
];
