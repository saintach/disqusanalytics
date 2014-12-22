<?php
define('__ROOT__', dirname(dirname(__FILE__))); 

require_once(__ROOT__.'/src/disqus_analytics.php');

$resources = DisqusResource::getInstance(); 


/*
echo '<h1>Disqus Available Data - GET</h1>';

echo '<h2>forums/details</h2><pre>'; print_r($resources->dqs_get_data('forums', 'details', array('forum'=>'disqusstat'))); echo '</pre>';
echo '<h2>forums/listCategories</h2><pre>'; print_r($resources->dqs_get_data('forums', 'listCategories', array('forum'=>'disqusstat'))); echo '</pre>';
echo '<h2>forums/listFollowers</h2><pre>'; print_r($resources->dqs_get_data('forums', 'listFollowers', array('forum'=>'disqusstat'))); echo '</pre>';
echo '<h2>forums/listModerators</h2><pre>'; print_r($resources->dqs_get_data('forums', 'listModerators', array('forum'=>'disqusstat'))); echo '</pre>';
echo '<h2>forums/listMostActiveUsers</h2><pre>'; print_r($resources->dqs_get_data('forums', 'listMostActiveUsers', array('forum'=>'disqusstat'))); echo '</pre>';
echo '<h2>forums/listPosts</h2><pre>'; print_r($resources->dqs_get_data('forums', 'listPosts', array('forum'=>'disqusstat', 'limit'=>1))); echo '</pre>';
echo '<h2>forums/listThreads</h2><pre>'; print_r($resources->dqs_get_data('forums', 'listThreads', array('forum'=>'disqusstat'))); echo '</pre>';
echo '<h2>forums/listUsers</h2><pre>'; print_r($resources->dqs_get_data('forums', 'listUsers', array('forum'=>'disqusstat'))); echo '</pre>';
echo '<h2>forums/listMostLikedUsers</h2><pre>'; print_r($resources->dqs_get_data('forums', 'listMostLikedUsers', array('forum'=>'disqusstat'))); echo '</pre>';



echo '<h2>Posts/list</h2><pre style="color:grey;">'; print_r($resources->dqs_get_data('posts', 'list', array('forum'=>'disqusstat'))); echo '</pre>';
echo '<h2>forums/listPosts</h2><pre style="color:blue;">'; print_r($resources->dqs_get_data('forums', 'listPosts', array('forum'=>'disqusstat'))); echo '</pre>';

echo '<h2>Posts/listPopular</h2><pre>'; print_r($resources->dqs_get_data('posts', 'listPopular', array('forum'=>'disqusstat'))); echo '</pre>';
echo '<h2>Posts/getContext</h2><p>Get hierarchal tree of a post</p><pre>'; print_r($resources->dqs_get_data('posts', 'getContext', array('post'=>1693838306))); echo '</pre>';


echo '<h2>forums/listPosts</h2><pre>'; print_r($resources->dqs_get_data('forums', 'listPosts', array('forum'=>'disqusstat', 'limit'=>1))); echo '</pre>';
*/

echo '<h2>threads/list</h2><pre>'; print_r($resources->dqs_get_data('threads', 'list', array('forum'=>'disqusstat'))); echo '</pre>';
echo '<h2>threads/listPosts</h2><pre>'; print_r($resources->dqs_get_data('threads', 'listPosts', array('forum'=>'disqusstat', 'thread'=> 3235221931))); echo '</pre>';


echo '<h2>users/listActivity</h2><p>Get hierarchal tree of a post</p><pre>'; print_r($resources->dqs_get_data('users', 'listActivity', array(
	'api_secret' => 'WtIsPp39iiiRZx2ukly7DEhqtgFqQsDdutsMh2SRpZ7Ki07e0wTEkfnFLEAfoxrD',
	'access_token'=>'5a33314330814734b6be6c568788bff8'
))); echo '</pre>';