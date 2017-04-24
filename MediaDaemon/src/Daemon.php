<?php
namespace MediaDaemon;
use ActiveRecord;
use config;
use React\EventLoop\Factory;
use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;

class Daemon{
	
	private $mediaDir = "/srv/ftp/media";
	public $videoDirIn = __DIR__."/../video/input";
	public $videoDirOut = __DIR__."/../video/output";
	public $msg;
	
	private function askDir() {
		$a = config::first(array('conditions' => array('attr_id = ?', 10)));
		$value = $a->attr_value;
		$this->mediaDir = $value;
		$this->videoDirIn = "$value/input";
		$this->videoDirOut = "$value/output";
	}
	
	public function initORM() {
		ActiveRecord\config::initialize(function($cfg){
			$cfg->set_model_directory(__DIR__.'/../models');
			$cfg->set_connections(array(
					'development' => 'pgsql://postgres:mixactik@localhost/MediaClerk'
			));
		});
		
		$this->askDir();
	}
	
	public function wsSend(){
	
		$loop = Factory::create();
		$connector = new Connector($loop);
	
		$connector('ws://192.168.88.11:8000')->then(function(WebSocket $conn) {
			$conn->send($this->msg);
			$conn->close();
		}, function(\Exception $e) use ($loop) {
			echo "Could not connect websocket-server: {$e->getMessage()}\n";
			$loop->stop();
		});
	
			$loop->run();
	
	}
}