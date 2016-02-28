<?php
define('IN_XM', true);
require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/cls.image.php');
//检测登录
check_login();
$file=empty($_GET['file']) ? "" :trim($_GET['file']);
$act=empty($_GET['act']) ? "" :trim($_GET['act']);
$name=empty($_GET['name']) ? "" :trim($_GET['name']);

if($act==''){
	$uploaddir = "../uploadfile/$file/";
	$name=time().rand(1000,9999);
	$ext=substr($_FILES['myfile']['name'],strrpos($_FILES['myfile']['name'],'.'));
	$fullname=$name.$ext;	
	$uploadfile = $uploaddir . $fullname;
	if (move_uploaded_file($_FILES['myfile']['tmp_name'], $uploadfile)) {
		//加水印
		/*$water = 'images/water.gif';
		$water_txt='images/water_txt.gif';
		$img = new Upload();
		$img->param($uploadfile)->water($uploadfile,$water,5);
		$img->param($uploadfile)->water($uploadfile,$water_txt,9);*/
		//缩略图
		$img = new Upload();
		$thumb_name='thumb_'.$fullname;
		$thumb_file=$img->param($uploadfile)->thumb($uploaddir.'thumb_'.$fullname,80,60,0,0);
		echo basename($_FILES['myfile']['name'])."-and-".$fullname;
	} else {	
		echo "error";
	}
}elseif($act=="del"){
	$uploaddir = "../uploadfile/$file/";	
	if($name){
		@unlink($uploaddir.$name);
		@unlink($uploaddir.'thumb_'.$name);
	}
	echo 1;
}
?>