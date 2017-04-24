<?php
namespace MediaDaemon;

class MediaDaemon extends \Core_Daemon{
    protected  $loop_interval = 1;

	protected function setup_plugins()
	{
        $this->plugin('Lock_File');
	}
	
	protected function setup()
	{

	}
	
	protected function execute()	
	{
		$Yiff = new todo();
		$Yiff->magic();
		$signal = $Yiff->restNeed();
		
		if($signal != 0){
			$Yiff->restCompl();
			$this->signal('SIGHUP');
		}
		
	}
	
	protected function log_file()
	{
		
	}
	
}