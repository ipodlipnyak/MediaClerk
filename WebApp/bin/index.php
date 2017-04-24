<?php
#php -S localhost:8080 -t /home/havactik/NeonPHP/MediaClerk/ /home/havactik/NeonPHP/MediaClerk/bin/index.php

require_once __DIR__.'/../vendor/autoload.php';

use MediaClerk\NecroMancer;
use ActiveRecord;
use media;
use config;


ActiveRecord\Config::initialize(function($cfg){
	$cfg->set_model_directory(__DIR__.'/../models');
	$cfg->set_connections(array(
			'development' => 'pgsql://postgres:mixactik@localhost/MediaClerk'
	));
});

$loader = new Twig_Loader_Filesystem(__DIR__.'/../tmp');
$twig = new Twig_Environment($loader);


$args = explode('/', rtrim($_SERVER['QUERY_STRING'], '/'));
$method = array_shift($args);

switch($method) {
	case '':
		$post = media::all();
		$t = array();
		foreach($post as $film)
			array_push($t, $film->to_array());
		echo $twig->render('Jocker.twig', array('films' => $t));		
		break;
		
	case 'push':		
		$film_id = $args[0];
		$p = media::first(array('conditions'=>array('media_id = ?',$film_id)));
		if ($args[1]==0){
			$p->conv_taskinwork=0;
			$p->conv_taskcompl=0;
			$p->conv_query=1;
		}
		elseif ($args[1]==1){			
			$p->conv_query=0;
		}
		
		$p->save();		
		break;		
	
		
	case 'ask_progr':
		$film_id = $args[0];
		$p = media::first(array('conditions'=>array('media_id = ?',$film_id)));	
		echo $p->to_json();		
		break;
		
	case 'ask_json':
		$post = media::all();
		$t = array();
		foreach($post as $book)
			array_push($t, $book->to_array());
		
		echo json_encode($t);
		
		break;
	
	case 'films':		
		
		$post = media::find(1);
		$json = $post->to_json(array(
				'only' => array('title', 'conv_progr')
		));
		echo $json;
		
		break;
		
	case 'ws':
		
		echo $twig->render('wsChat.twig');
		
		break;
		
	case 'convert':
		$Keen = new NecroMancer();
		$Keen->FSpell();
		break;
	
	case 'statusd3':
		$status = config::first(array('conditions'=>array('attr_id = ?', 3)));
		echo $status->to_json();
		break;
		
	case 'statusd1':
		$status = config::first(array('conditions'=>array('attr_id = ?', 1)));
		echo $status->to_json();
		break;
		
	case 'actived':
		$newvalue =  $args[0];
		$status = config::first(array('conditions' => array('attr_id = ?', 1)));
		if ($newvalue ==1){
			$status->attr_value = 0;
		} else {
			$status->attr_value = 1;			
		}		
		$status->save();
		break;
		
	case 'priorupd':		
		$film_id = $args[0];
		$newvalue =  $args[1];
		$p = media::first(array('conditions'=>array('media_id = ?',$film_id)));
		$p->conv_prior = $newvalue;
		$p->save();		
		break;
		
	

};


