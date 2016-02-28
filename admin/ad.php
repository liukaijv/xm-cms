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
$pos_id=empty($_GET['pos_id']) ? "" : intval($_GET['pos_id']);
$val=empty($_GET['val']) ? "" : trim($_GET['val']);
$field=empty($_GET['field']) ? "" : trim($_GET['field']);
$ajax=empty($_GET['ajax']) ? "" : intval($_GET['ajax']);
$page=isset($_GET['page'])? intval($_GET['page']) : "1";
$smarty->assign('page',$page);
$smarty->assign('id',$id);
$smarty->assign('pos_id',$pos_id);
$smarty->assign('act',$act);

/*编辑*/
if($act=="edit"){
	//取广告位宽高
	if($pos_id){
		$pos=$web->get("pos_id=$pos_id","ad_pos");
		if(!$pos['width']||!$pos['height']){
			showMsg("参数错误，或是广告位不存在！");
		}
		$smarty->assign("pos",$pos);
	}
	if($id){
		$ad=$db->get_info("ad_id=$id","ad");
		if(!IS_POST){
			
			$smarty->assign('ad',$ad);
			$smarty->display("ad_add.htm");
		}else{
			if(empty($_POST['ad_name'])){
				showMsg('广告名称不能为空！',1);		
			}
			if(!empty($_FILES['default_image']['name'])){
				$save_path= "uploadfile/ad/";
				$html_name="default_image";
				$default_image=uploads(ROOT_PATH,$save_path,$html_name,$pos['width'],$pos['height'],0);
				//加水印						
				$water_txt=ROOT_PATH.'images/water_txt.gif';			
				$img = new Upload();				
				$img->param(ROOT_PATH.$default_image)->water(ROOT_PATH.$default_image,$water_txt,7,10);
				if(file_exists('../'.$default_image)){
					@unlink('../'.$ad['default_image']);//删除原图
				}
			}else{
				$default_image=$ad['default_image'];
			}
			$data=array(
				'pos_id'=>$pos_id,
				'ad_name'=>$_POST['ad_name'],
				'ad_url'=>$_POST['ad_url'],
				'default_image'=>$default_image,
				//'add_time'=>time()
			);
			
			if($db->update($data,"ad_id=$id","ad")>0){
				$links[0]=array('text'=>"返回列表",'href'=>"ad.php?pos_id=$pos_id&page=$page");
				$links[1]=array('text'=>"继续编辑",'href'=>"ad.php?pos_id=$pos_id&act=edit&page=$page&id=$id");
				showMsg('编辑链接成功！',0,$links);	
			}else{
				showMsg('编辑失败，也许你没有做任何修改！',1);	
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
					$ad=$db->get_info("ad_id=$id","ad");
					if($db->delete("ad_id=$id","ad")){						
						@unlink(ROOT_PATH.$ad['default_image']);
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
	//取广告位宽高
	if($pos_id){
		$pos=$web->get("pos_id=$pos_id","ad_pos");
		if(!$pos['width']||!$pos['height']){
			showMsg("参数错误，或是广告位不存在！");
		}
	}
	if(!IS_POST){
		$smarty->assign("pos",$pos);
		$smarty->display("ad_add.htm");
	}else{
		if(empty($_POST['ad_name'])){
			showMsg('名称不能为空！',1);		
		}
		
		$default_image='';
		if(!empty($_FILES['default_image']['name'])){
			$save_path= "uploadfile/ad/";
			$html_name="default_image";
			$default_image=uploads(ROOT_PATH,$save_path,$html_name,$pos['width'],$pos['height'],0);
			//加水印						
			$water_txt=ROOT_PATH.'images/water_txt.gif';			
			$img = new Upload();				
			$img->param(ROOT_PATH.$default_image)->water(ROOT_PATH.$default_image,$water_txt,7,10);			
			if(empty($default_image)){
				showMsg("默认图片上传失败！",1);
			}
		}else{
			showMsg("请上传默认图片！",1);
		}
		$data=array(
			'pos_id'=>$pos_id,
			'ad_name'=>$_POST['ad_name'],
			'ad_url'=>$_POST['ad_url'],
			'default_image'=>$default_image,
			'add_time'=>time()
		);
//		print_r($data);exit;
		if($db->insert($data,"ad")>0){
			$links[0]=array('text'=>"返回列表",'href'=>"ad.php?pos_id=$pos_id");
			$links[1]=array('text'=>"继续添加",'href'=>"ad.php?pos_id=$pos_id&act=add");			
			showMsg('添加成功！',0,$links);	
		}else{	
			showMsg('添加失败！',1);	
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
	if($field=="description"){
		if($db->update($data,"pos_id=$id","ad_pos")){
			echo 1;
		}else{
			echo 0;
		}
	}else{
		if($db->update($data,"ad_id=$id","ad")){
			echo 1;
		}else{
			echo 0;
		}
	}
	if($ajax){exit;}
}
elseif($act=="pos_add"){
	if(!IS_POST){
		$smarty->display("ad_pos_add.htm");
	}else{
		if(empty($_POST['name'])){
			showMsg('名称不能为空！',1);		
		}
		$data=array(
			'name'=>$_POST['name'],
			'width'=>intval($_POST['width']),
			'height'=>intval($_POST['height']),
			'is_blank'=>intval($_POST['is_blank']),
			'description'=>$_POST['description'],
			'add_time'=>time()
		);
		//print_r($data);exit;
		if($db->insert($data,"ad_pos")>0){
			$links[0]=array('text'=>"返回列表",'href'=>"ad.php?act=pos");
			$links[1]=array('text'=>"继续添加",'href'=>"ad.php?act=pos_add");			
			showMsg('添加成功！',1,$links);	
		}else{	
			showMsg('添加失败！');	
		}
	}
}
elseif($act=="pos_edit"){
	$pos=$db->get_info("pos_id=$id","ad_pos");
	$smarty->assign("pos",$pos);
	if(!IS_POST){
		$smarty->display("ad_pos_add.htm");
	}else{
		if(empty($_POST['name'])){
			showMsg('名称不能为空！',1);		
		}
		$data=array(
			'name'=>$_POST['name'],
			'width'=>intval($_POST['width']),
			'height'=>intval($_POST['height']),
			'description'=>$_POST['description'],
			'is_blank'=>intval($_POST['is_blank']),
			//'add_time'=>time()
		);
		if($db->update($data,"pos_id=$id","ad_pos")>0){
			$links[0]=array('text'=>"返回列表",'href'=>"ad.php?act=pos");
			$links[1]=array('text'=>"继续编辑",'href'=>"ad.php?act=pos_add&id=$id");			
			showMsg('编辑成功！',1,$links);	
		}else{	
			showMsg('编辑失败！');	
		}
	}
}
/*删除广告位*/
elseif($act=="pos_del"){
	if($ids){
		$ids=@explode(',',$ids);
		if(is_array($ids)){
			foreach($ids as $id){
				if($id){
					$ad_pos=$db->get_info("ad_id=$id","ad_pos");
					if($db->delete("pos_id=$id","ad_pos")){						
						//删除下面的广告
						$ads=$db->getAll("select * from ".$prefix."ad where pos_id=$id");
						if(is_array($ads)&&count($ads)>0){
							foreach($ads as $ad){
								@unlink(ROOT_PATH.$ad['default_image']);
							}
						}
						$db->delete("pos_id=$id","ad");
					}
				}
			}
			echo 1;
		}
	}
	if($ajax){exit;}
}
else if($act=="blank"){
	$info=$web->get("pos_id=$id","ad_pos");
	$show=($info['is_blank']==1) ? 0 : 1;
	$data=array('is_blank'=>$show);
	if($db->update($data,"pos_id=$id","ad_pos")){
		echo 1;
	}else{
		echo 0;
	}
	if($ajax){exit;}
}
elseif($act=="pos"){
	$sql="select * from ".$prefix."ad_pos order by add_time asc";
	$ad_poss=$db->getAll($sql);
	$posALl=array();
	if(is_array($ad_poss)&&count($ad_poss)>0){
		foreach($ad_poss as $k=>$pos){
			$posALl[$k]['pos_id']=$pos['pos_id'];
			$posALl[$k]['name']=$pos['name'];
			$posALl[$k]['width']=$pos['width'];
			$posALl[$k]['height']=$pos['height'];
			$posALl[$k]['description']=$pos['description'];
			$posALl[$k]['is_blank']=$pos['is_blank'];
			$posALl[$k]['add_time']=$pos['add_time'];
			$posALl[$k]['number']=$db->getNum("select * from ".$prefix."ad where pos_id=$pos[pos_id]");
		}
	}
	$smarty->assign("ad_poss",$posALl);
	$smarty->display("ad_pos.htm");
}
/*列表*/
else{
	//所有广告位
	$poss=$db->fetchAll("ad_pos");
	$smarty->assign("poss",$poss);
	
	//条件
	$where="";
	$keyword=(isset($_GET['keyword'])) ? urldecode(trim($_GET['keyword'])) : "";
	if(!empty($keyword)){
		$where.=" and (a.ad_name like '%$keyword%')";
	}
	/*分页*/
	$page_size=12;
	$sql="select count(*) from ".$prefix."ad as a left join ".$prefix."ad_pos as p on(p.pos_id=a.pos_id) where a.pos_id=$pos_id $where";
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
	$sql="select a.*,p.width,p.height,p.name as pos_name from ".$prefix."ad as a left join ".$prefix."ad_pos as p on(p.pos_id=a.pos_id) where a.pos_id=$pos_id $where order by a.add_time desc limit ".($page-1)*$page_size." , ".$page_size."";
	$ads=$db->getAll($sql);
	$smarty->assign("ads",$ads);
	$subpage=new Subpage($page_size,$all_num,$page,5,"ad.php?pos_id=$pos_id&keyword=".urlencode($keyword)."&page=");
	
	$smarty->assign('subpage',$subpage->subpagehtml_a());
	$smarty->display("ad.htm");
}
