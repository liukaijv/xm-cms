<?php
define('IN_XM', true);
require(dirname(__FILE__) . '/includes/init.php');
include(ROOT_PATH."includes/cls.subpage.php");
//检测登录
check_login();
$act=empty($_GET['act']) ? "" : trim($_GET['act']);
$id=empty($_GET['id']) ? "" : intval($_GET['id']);
$ids=empty($_GET['ids']) ? "" : trim($_GET['ids']);
$mid=empty($_GET['mid']) ? "" : intval($_GET['mid']);
$val=empty($_GET['val']) ? "" : trim($_GET['val']);
$field=empty($_GET['field']) ? "" : trim($_GET['field']);
$ajax=empty($_GET['ajax']) ? "" : intval($_GET['ajax']);
$page=isset($_GET['page'])? intval($_GET['page']) : "1";
$smarty->assign('page',$page);
$smarty->assign('id',$id);
$smarty->assign('mid',$mid);
$smarty->assign('act',$act);
if($mid){
	$info=$web->get("menu_id=$mid","menus");
	$smarty->assign("info",$info);
}else{
	showMsg("参数错误！");
}
/*审核*/
if($act=="edit"){
	if($id){
		$feed=$db->get_info("id=$id","feedback");
		if(!IS_POST){
			$smarty->assign('feed',$feed);
			$smarty->display("feedback_reply.htm");
		}else{
			$data=array(
				//'add_time'=>strtotime($_POST['add_time']),
				//'reply_name'=>trim($_POST['reply_name']),	
				'reply_content'=>trim($_POST['reply_content']),
				'is_check'=>isset($_POST['is_check']) ? 1 : 0,
				'reply_time'=>time()
			);
			if($db->update($data,"id=$id","feedback")>0){
				$links[0]=array('text'=>"返回列表",'href'=>"feedback.php?mid=$mid&page=$page");
				$links[1]=array('text'=>"继续编辑",'href'=>"feedback.php?mid=$mid&act=edit&page=$page&id=$id");
				
				showMsg('操作成功！',1,$links);	
			}else{	
				showMsg('操作失败，也许你没有做任何修改！');	
			}
		}
	}else{
		showMsg('系统参数错误！',1);	
	}
}
/*删除*/
elseif($act=="del"){
	if($ids){
		$ids=@explode(',',$ids);
		if(is_array($ids)){
			foreach($ids as $id){
				if($id){
					$feed=$db->get_info("id=$id","feedback");
					if($db->delete("id=$id","feedback")){						
					}
				}
			}
			echo 1;
		}
	}
	if($ajax){exit;}
}

else if($act=="check"){
	$info=$web->get("id=$id","feedback");
	$show=($info['is_check']==1) ? 0 : 1;
	$data=array('is_check'=>$show);
	if($db->update($data,"id=$id","feedback")){
		echo 1;
	}else{
		echo 0;
	}
	if($ajax){exit;}
}
/*列表*/
else{	
	//条件
	$where="";
	$keyword=(isset($_GET['keyword'])) ? urldecode(trim($_GET['keyword'])) : "";
	if(!empty($keyword)){
		$where.="and (content like '%$keyword%' or name like '%$keyword%')";
	}
	/*分页*/
	$page_size=6;
	$sql="select count(*) from ".$prefix."feedback where 1=1 $where";
	$all=$db->getOne($sql);
	if(is_array($all)){
		$all_num=$all[0];
	}else{
		$all_num=0;
	}
	$all_page=ceil($all_num/$page_size);
	if(empty($page)||!is_int($page)||$page<1){
		$page=1;
	}
	if($page>=$all_page&&$all_page>0){
		$page=$all_page;
	}
	$sql="select * from ".$prefix."feedback where 1=1 $where order by add_time desc limit ".($page-1)*$page_size." , ".$page_size."";
	$feedbacks=$db->getAll($sql);
	$smarty->assign("feedbacks",$feedbacks);
	$subpage=new Subpage($page_size,$all_num,$page,5,"feedback.php?mid=$mid&keyword=".urlencode($keyword)."&page=");
	
	$smarty->assign('subpage',$subpage->subpagehtml_a());
	$smarty->display("feedback.htm");
}
?>