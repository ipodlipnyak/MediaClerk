<?php
require_once __DIR__.'/../vendor/autoload.php'; 

ActiveRecord\Config::initialize(function($cfg){
	$cfg->set_model_directory(__DIR__.'/../models');
	$cfg->set_connections(array(
			'development' => 'pgsql://postgres:mixactik@localhost/MediaClerk'
	));
});

$post = films::find(1);

$title = $post->title;
$progress = $post->progress;

echo $progress . $_GET['progress'];
echo $title . $_GET['title'];