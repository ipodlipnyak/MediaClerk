<?php
require_once __DIR__.'/../vendor/autoload.php'; 

ActiveRecord\Config::initialize(function($cfg){
	$cfg->set_model_directory(__DIR__.'/../models');
	$cfg->set_connections(array(
			'development' => 'pgsql://postgres:mixactik@localhost/MediaClerk'
	));
});

	$film_id = 2;
	$p = media::first(array('select'=>'progress','conditions'=>array('video_id = ?',$film_id)));	
	echo $p->to_json();
	
/*
$pot = media::find('last');

$post = films::all();

$arrlength = count($post);
$first = films::first();


$t = array();
foreach($post as $book)
	array_push($t, $book->to_array());
*/
	
	
#echo json_encode($t);

/*
foreach($post as $book)
	echo $book->title;
*/
	
#$json = $first->to_array();
/*
for($x = 1; $x < $arrlength; $x++) {
	$q = $post[$x]->to_array();
	$json = array_merge($json, $q);
};
*/
#echo json_encode($json);

#var_dump($json);


/*
for($x = 0; $x < $arrlength; $x++) {
	$json = $post[$x]->to_json();
	echo $json;
	echo "<br>";
}
*/

#echo json_encode($post);

# json should only contain title and progress percentage
/*
$json = $post->to_json(array(
		'only' => array('code', 'title')
));

echo $json;
*/
 
# var_dump($post);