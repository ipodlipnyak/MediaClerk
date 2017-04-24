<?php


use MediaDaemon\todo;

#use MediaDaemon\FolderClerk;
#use MediaDaemon\MpegConverter;
require_once __DIR__.'/../vendor/autoload.php';
/*
$Yiff = new FolderClerk();
$Yiff -> ShowNewItem();

$j = new MpegConverter();
*/

$Yiff = new todo();
$Yiff->magic();