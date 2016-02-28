<?php
define('IN_XM', true);
include(dirname(__FILE__) . '/includes/init.php');
//检测登录
check_login();
/*参数接收*/
$mid=empty($_GET['mid']) ? "" : intval($_GET['mid']);

if($mid){
	$menu=$db->get_info("menu_id=$mid","menus");
	
	if($menu['type_id']==1){  //单页
		my_header('Location: simple.php?mid='.$mid);
	}
	elseif($menu['type_id']==2){ //文章
		my_header('Location: article.php?mid='.$mid);
	}
	elseif($menu['type_id']==3){ //产品
		my_header('Location: pro.php?mid='.$mid);
	}
	elseif($menu['type_id']==4){ //案例
		my_header('Location: case_image.php?mid='.$mid);
	}	
	elseif($menu['type_id']==5){ //在线留言
		my_header('Location: feedback.php?mid='.$mid);
	}	
}else{
	showMsg("参数错误！");
}