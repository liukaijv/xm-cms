<?php
define('IN_XM', true);
include(dirname(__FILE__) . '/includes/init.php');
//检测登录
check_login();
$mid=empty($_GET['mid']) ? "" : intval($_GET['mid']);

if($mid){
	$info=$web->get("menu_id=$mid","simple");
	if(!$info['id']){
		showMsg("系统错误！");
	}
	if(IS_POST){		
		$data=array(
			'content'=>$_POST['content']			
		);
		$db->update($data,"menu_id=".$info['menu_id'],"simple");
		
		showMsg("保存成功！",1);
	}	
	$smarty->assign('info',$info);
	$smarty->display("simple.htm");
}else{
	showMsg("参数错误！");
}

?>