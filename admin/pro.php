<?php
define('IN_XM', true);
require(dirname(__FILE__) . '/includes/init.php');
include(ROOT_PATH	.'includes/cls.subpage.php');
require(ROOT_PATH . 'includes/cls.image.php');
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

/*编辑*/
if($act=="edit"){
	if($id){
		$pro=$db->get_info("pro_id=$id and menu_id=$mid","pro");
		if(!IS_POST){
			$pro['images']=array_filter(@explode("|",$pro['images']),"array_none");			
			$smarty->assign('pro',$pro);
			$smarty->display("pro_add.htm");
		}else{
			if(empty($_POST['pro_name'])){	
				showMsg('名称不能为空！',1);		
			}
			if(!empty($_FILES['default_image']['name'])){
				$save_path="uploadfile/pro/";
				$html_name="default_image";
				$default_image=uploads(ROOT_PATH,$save_path,$html_name,240,240);
				if(file_exists('../'.$default_image)){
					@unlink("../".$pro['default_image']);
					if(file_exists("../".str_replace('thumb_', '', $pro['default_image']))){
						@unlink("../".str_replace('thumb_', '', $pro['default_image']));
					}
				}
			}else{
				$default_image=$pro['default_image'];
			}			
			$data=array(
				//'menu_id'=>$mid,
				'pro_name'=>$_POST['pro_name'],
				'author'=>$_POST['author'],
				'from'=>$_POST['from'],				
				//'cate_id'=>intval($_POST['cate_id'])>0 ? intval($_POST['cate_id']) : 0,
				'images'=>$_POST['images'],
				'default_image'=>$default_image,
				'detail'=>$_POST['detail'],
				'view_times'=>intval($_POST['view_times']),
				'sort_order'=>$_POST['sort_order'],
				// 'add_time'=>empty($_POST['add_time']) ? time() : strtotime($_POST['add_time'])
			);
			if($db->update($data,"pro_id=$id","pro")>0){
				$links[0]=array('text'=>"返回列表",'href'=>"pro.php?mid=$mid&page=$page");
				$links[1]=array('text'=>"继续编辑",'href'=>"pro.php?mid=$mid&act=edit&page=$page&id=$id");
				showMsg('编辑成功！',1,$links);	
			}else{	
				showMsg('编辑失败，也许你没有做任何修改！');
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
					$pro=$db->get_info("pro_id=$id","pro");
					if($db->delete("pro_id=$id","pro")){						
						@unlink("../".$pro['default_image']);
						if(file_exists("../".str_replace('thumb_', '', $pro['default_image']))){
							@unlink("../".str_replace('thumb_', '', $pro['default_image']));
						}
					}
				}
			}
			echo 1;
		}
	}
	if($ajax){exit;}
}
/*添加*/
elseif($act=="add"){
	if(!IS_POST){		
		$smarty->display("pro_add.htm");
	}else{
		if(empty($_POST['pro_name'])){	
			showMsg('名称不能为空！');		
		}
		if(!empty($_FILES['default_image']['name'])){
			$save_path= "uploadfile/pro/";
			$html_name="default_image";
			$default_image=uploads(ROOT_PATH,$save_path,$html_name,240,240);
		}else{
			showMsg("请上传默认图片！");
		}
		$data=array(
			'menu_id'=>$mid,
			'pro_name'=>$_POST['pro_name'],
			'author'=>$_POST['author'],
			'from'=>$_POST['from'],			
			//'cate_id'=>intval($_POST['cate_id'])>0 ? intval($_POST['cate_id']) : 0,
			'images'=>$_POST['images'],
			'default_image'=>$default_image,
			'detail'=>$_POST['detail'],
			'sort_order'=>$_POST['sort_order'],
			'view_times'=>intval($_POST['view_times']),			
			'add_time'=>empty($_POST['add_time']) ? time() : strtotime($_POST['add_time'])
		);
		if($db->insert($data,"pro")>0){
			$links[0]=array('text'=>"返回列表",'href'=>"pro.php?mid=$mid");
			$links[1]=array('text'=>"继续添加",'href'=>"pro.php?mid=$mid&act=add");			
			showMsg('添加成功！',1,$links);	
		}else{	
			showMsg('添加失败！');	
		}
	}
}
elseif($act=="ajax_edit"){
	if($field){
		$data=array($field =>$val);
	}else{
		echo 0;
		exit;
	}
	if($db->update($data,"pro_id=$id","pro")){
		echo 1;
	}else{
		echo 0;
	}
	if($ajax){exit;}
}
else if($act=="top"){
	$info=$web->get("pro_id=$id","pro");
	$show=($info['is_top']==1) ? 0 : 1;
	$data=array('is_top'=>$show);
	if($db->update($data,"pro_id=$id","pro")){
		echo 1;
	}else{
		echo 0;
	}
	if($ajax){exit;}
}
else if($act=="recom"){
	$info=$web->get("pro_id=$id","pro");
	$show=($info['is_recom']==1) ? 0 : 1;
	$data=array('is_recom'=>$show);
	if($db->update($data,"pro_id=$id","pro")){
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
		$where.="and (pro_name like '%$keyword%')";
	}
	/*分页*/
	$page_size=15;
	$sql="select count(*) from ".$prefix."pro where menu_id=$mid $where";
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
	$sql="select * from ".$prefix."pro where menu_id=$mid $where order by add_time desc limit ".($page-1)*$page_size." , ".$page_size."";
	$pros=$db->getAll($sql);
	$smarty->assign("pros",$pros);
	$subpage=new Subpage($page_size,$all_num,$page,5,"pro.php?mid=$mid&keyword=".urlencode($keyword)."&page=");	
	
	$smarty->assign('subpage',$subpage->subpagehtml_a());
	$smarty->display("pro.htm");
}
?>