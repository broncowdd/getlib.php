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
	define('LOCAL_FILENAME',$lib_folder.basename($url));
	$ext=pathinfo(LOCAL_FILENAME)['extension'];
	//$flag='non';
	if (
		!is_file(LOCAL_FILENAME)
		||
		($check_updates && !isSameFile($url))
				
	){
		$lib=file_get_contents($url,false,null,0,1000000);
		file_put_contents(LOCAL_FILENAME,$lib);
		$head = array_change_key_case(get_headers($url, TRUE));
		file_put_contents(LOCAL_FILENAME.'.info', $head['last-modified']);
		//$flag='oui';
	}
	if ($ext=='css'){
		$mime='text/css';
	}elseif ($ext=='js'){
		$mime='text/javascript';
	}else{
		$mime=mime_content_type(LOCAL_FILENAME);
	}
	
	header('Content-Type: '.$mime);
	exit(file_get_contents(LOCAL_FILENAME));
}


function isSameFile($url){
	$head = array_change_key_case(get_headers($url, TRUE));
	$local=file_get_contents(LOCAL_FILENAME.'.info');
	$distant=$head['last-modified'];
	return $distant==$local;
}
