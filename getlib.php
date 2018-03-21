<?php 
# get libs from distant servers to local (& avoid unnecessary requests to servers who can log user's connections)
# ex:  
# https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js
# becomes
# getlib.php?url=https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js
# if you want to update local file if the distant one changes, just add "update" 
# getlib.php?update&url=https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js
# author: warriordudimanche.net
# 
$lib_folder='libs/';
$check_updates=isset($_GET['update']);

if (!empty($_GET['url'])){
	if (!is_dir($lib_folder)){mkdir($lib_folder);}
	
	$url=strip_tags($_GET['url']);
	$local_filename=$lib_folder.basename($url);
	$flag='non';
	if (
		!is_file($local_filename)
		||
		($check_updates && !isSameFile())
				
	){
		$lib=file_get_contents($url);
		file_put_contents($local_filename,$lib);
		$head = array_change_key_case(get_headers($url, TRUE));
		file_put_contents($local_filename.'.info', $head['last-modified']);
		$flag='oui';
	}
	header('Content-Type: '.mime_content_type($local_filename));
	exit(file_get_contents($local_filename));
}

function getDistantFile($url){
	global $local_filename;
	$lib=file_get_contents($url);
	file_put_contents($local_filename,$lib);
	$head = array_change_key_case(get_headers($url, TRUE));
	file_put_contents($local_filename.'.info', $head['last-modified']);
}

function isSameFile(){
	global $local_filename,$url;
	$head = array_change_key_case(get_headers($url, TRUE));
	$local=file_get_contents($local_filename.'.info');
	$distant=$head['last-modified'];
	return $distant==$local;
}
