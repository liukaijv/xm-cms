<?php

define('IN_XM', true);
require(dirname(__FILE__) . '/includes/init.php');
include(dirname(__FILE__) . '/includes/cls.subpage.php');

$act = empty($_GET['act']) ? "" : trim($_GET['act']);
$mid = empty($_GET['mid']) ? "" : intval($_GET['mid']);
$id = empty($_GET['id']) ? "" : intval($_GET['id']);
$page = isset($_GET['page']) ? intval($_GET['page']) : "1";
$keyword = isset($_GET['keyword']) ? urldecode($_GET['keyword']) : '';

$site_title = $web->get_title($mid);
$smarty->assign('site_title', $site_title);

//服务QQ
$service_qq = [];
if (strpos($CFG['service_qq'], '|')) {
    $groups = explode('|', $CFG['service_qq']);
    if (is_array($groups)) {
        foreach ($groups as $group) {
            if (strpos($group, ',')) {
                $service_qq[] = explode(',', $group);
            }
        }
    }
} else if (strpos($CFG['service_qq'], ',')) {
    $service_qq[] = explode(',', $CFG['service_qq']);
}
$smarty->assign('service_qq', $service_qq);

//首页
if (!isset($mid) || empty($mid) || !is_int($mid) || $mid < 1) {    

    $smarty->display('index.htm');

} else {
    
    showMsg("出错了，您访问的页面不存在！");  

}