<?php
namespace MediaDaemon;
use media;
use config;

class todo extends Daemon{
	private $convlist = array();
	
	private $StatMSG1 = 'Error. Task in query';
	private $StatMSG2 = 'Convertation on the go';
	private $StatMSG3 = 'Error. All tasks in query';
	private $StatMSG4 = 'Query are empty';
	private $StatMSG5 = 'Query execution forbidden';
	
// 	Loged the message into database and compile it before sending to wsServer 
	private function makeJSONmsg ($input){
		$this->msg = json_encode (array(
				'type'=>'dstat',
				'attr_value'=>$input));
		$this->wsSend();
		$post = \config::first(array('conditions' => array('attr_id = ?', 3)));
		$post->attr_value = $input;
		$post->save();
	}
	
	public function magic() {
		
		$Clark = new FolderClerk();
		$Clark -> UpdateDB();	
		
		$taskexist = media::first(array('conditions' => array('conv_taskinwork = ? AND conv_taskcompl = ?', 1, 0)));
		$post = media::first(array('conditions' => array('conv_query = ? AND conv_taskinwork = ?', 1, 0),'order' => 'conv_prior desc, title asc'));		
		$daemonactive = config::first(array('conditions' => array('attr_id = ?', 1)));
		$daemonrestart = config::first(array('conditions' => array('attr_id = ?', 2)));
		
		
		if($daemonactive->attr_value != 0){
			

			if ($post != NULL){
				if ($taskexist != NULL){
					$this->makeJSONmsg($this->StatMSG1);
					#echo 'Что-то в работе'."\n";
				} else {
					$this->makeJSONmsg($this->StatMSG2);
					#echo 'Запускаю задачу'."\n";
					$fox = new MpegConverter();
					$fox->mpegConvert($post->title, $post->title.".avi");
				}
			}
			else {
				if ($taskexist != NULL){
					$this->makeJSONmsg($this->StatMSG3);
					#echo 'Все задачи в работе'."\n";
				} else {
					$this->makeJSONmsg($this->StatMSG4);
					#echo 'В очереди ничего не нашел'."\n";
				}
			}
			
		} else {
			$this->makeJSONmsg($this->StatMSG5);
			#echo 'Демон отключен'."\n";
		}
						
	}

	public function restNeed (){
		$daemonrestart = config::first(array('conditions' => array('attr_id = ?', 2)));
			if ($daemonrestart->attr_value != 0){
			return 1;
		} else {
			return 0;
		}
	}
	
	public function restCompl (){
		$daemonrestart = config::first(array('conditions' => array('attr_id = ?', 2)));
		$daemonrestart->attr_value = '0';
		$daemonrestart->save();
	}
}