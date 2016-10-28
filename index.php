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

//首页
$smarty->display('index.htm');