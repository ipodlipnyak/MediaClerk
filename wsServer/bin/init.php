<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use ratApp\Chat;

require_once __DIR__.'/../vendor/autoload.php';

$server1 = IoServer::factory(
	new Chat(),
	8001
);

$server2 = IoServer::factory(
	new HttpServer(
		new WsServer(
			new Chat()
		)
	),
	8000
);

#$server1->run();
$server2->run();
