<?php
namespace MediaClerk;
require_once __DIR__.'/../vendor/autoload.php';
use Ratchet\Client as Client;

class Minion{
	public $song = "Good job";
	public function Dig(){
		
		$loop = React\EventLoop\Factory::create();
		$connector = new Ratchet\Client\Connector($loop);
		
		$connector('ws://localhost:8000')->then(function(Ratchet\Client\WebSocket $conn) {
			$conn->send($this->song);
			$conn->close();
		}, function(\Exception $e) use ($loop) {
			echo "Could not connect websocket-server: {$e->getMessage()}\n";
			$loop->stop();
		});
		
		$loop->run();
		
	}
	
};

class NecroMancer extends Minion{	
	public function FSpell(){

		$ffmpeg = FFMpeg\FFMpeg::create();
		$video = $ffmpeg->open('/home/havactik/NeonPHP/joker/SP.mp4');

		$format = new FFMpeg\Format\Video\Ogg();
		$format->on('progress', function ($video, $format, $percentage) {
			$post = media::find(1);			
			$post->progress=$percentage;
			$id=$post->video_id;
			
			$burble = array(
					"id" => $id,
					"percent" => $percentage					
			);
			
			$this->song= json_encode($burble);
			$this->Dig();			
			$post->save();
		});

			$video->save($format, 'video.avi');				
	}
};