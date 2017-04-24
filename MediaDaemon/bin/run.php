#!/usr/bin/php
<?php
require_once __DIR__.'/../vendor/autoload.php';

// The daemon needs to know from which file it was executed.
MediaDaemon\MediaDaemon::setFilename(__FILE__);

// The run() method will start the daemon loop. 
MediaDaemon\MediaDaemon::getInstance()->run();