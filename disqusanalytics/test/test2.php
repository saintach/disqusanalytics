<?php 
	ini_set('display_errors', 'On');


	define('__ROOT__', dirname(dirname(__FILE__))); 
	
	require_once(__ROOT__.'/src/disqus.php'); 

	$collection = DisqusDataCollection::getInstance(); 
	
	$collection->dqs_collect();

?>