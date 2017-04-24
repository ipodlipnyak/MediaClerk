<?php
namespace MediaDaemon;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\Ogg;
use media;
use FFMpeg\Format\Video\X264;
use FFMpeg\Format\Video\WMV;
use FFMpeg\Format\Video\WebM;

class MpegConverter extends Daemon {
	
	
	private $post = array();
	private $postid;
	
	function __construct() {
		$this->initORM();		
	}
	

	
	
	
	/*
	 * Отправляем данные на WebSocket-сервер пока происходит конвертация медиа-файла. Необходимо указать имя конвертируемого файла и имя нового файла 
	 */	
	public function mpegConvert($input, $output){
		
		$this->post = media::find(1, array('conditions' => array('title = ?', $input)));
		$this->post->update_attributes(array('conv_taskinwork' => 1));
	
		$ffmpeg = FFMpeg::create();
		$video = $ffmpeg->open($this->videoDirIn."/".$input);		
	
// 		$format = new Ogg();

		$format = new WebM();
		$format->on('progress', function ($video, $format, $percentage) {
			
			$this->post->update_attributes(array('conv_progr' => $percentage));			
							
			$message = array(
					"id" => $this->post->media_id,
					"percent" => $percentage
			);
				
			$this->msg = json_encode($message);
			$this->wsSend();
		});	
	
		
			$video->save($format, $this->videoDirOut."/".$output);
			$this->post->update_attributes(array('conv_taskcompl' => 1,'conv_progr' => 100,'conv_query'=>0));			
			$message = array(
					"id" => $this->post->media_id,
					"percent" => 100
					);
			$this->msg = json_encode($message);
			$this->wsSend();
			
	}
	
}