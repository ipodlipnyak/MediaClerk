<?php 
namespace MediaDaemon;
use media;


class FolderClerk extends Daemon {
	private $dblist = array();
	private $dblist_assoc = array();
	private $folderlist = array();
	private $folderlist_assoc = array();
	private $newItem;

	function __construct() {
		$this->initORM();
	}

	private function scratchdb() {
		$post = media::find('all', array('select' => 'title'));
		$t = array();
		$this->deposite = array();
		foreach($post as $film){
			array_push($this->dblist_assoc, $film->to_array());
		}
	}

	private function scratchfolder() {
		if ($handle = opendir($this->videoDirIn)){
			while (false !== ($entry = readdir($handle))){
				if ($entry != "." && $entry != ".."){
					array_push($this->folderlist_assoc, array ( 'title' => $entry));
				}
			}
			closedir($handle);
		}
	}

	private function savenew($input) {
		$ItemAttributes = array('title' => $input, 'conv_query' => 0, 'conv_taskinwork' => 0, 'conv_progr' => 0, 'conv_prior' => 5, 'conv_taskcompl' => 0);
		$newitem = new media($ItemAttributes);
		$newitem -> save();
		
		/* 
		 * To-Do: создавать записи в таблице input
		 * 
		$InputAttributes = array('media_id' => $input, 'format' => 0);
		$newinput = new \input($InputAttributes);
		$newinput -> save();
		*/
	}
	
	private function difflist() {
		$this -> dblist_assoc = array();
		$this -> scratchdb();
		$this -> scratchfolder();

		foreach ($this->dblist_assoc as $ditem){
			array_push ($this -> dblist, implode($ditem));
		}

		foreach ($this->folderlist_assoc as $fitem){
			array_push ($this -> folderlist, implode($fitem));
		}

		$this->newItem = array_diff($this->folderlist, $this->dblist);
	}
	
	
	
	/*
	 * При появлении в каталоге файлов без записи в БД, cоздем новые записи 
	 */	
	public function UpdateDB() {
		$this -> difflist();
		foreach ($this -> newItem as $input) {
			$this-> savenew($input);
		}
	}
	
	
	
	/*
	 * Тестовый метод для вывода списков новых файлов в каталоге без записи в БД
	 */
	public function ShowNewItem() {
		$this -> difflist();
		echo json_encode($this -> dblist)."\n";
		echo json_encode($this -> folderlist)."\n";
		echo json_encode($this -> newItem)."\n";
	}
}