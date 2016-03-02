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

//导航
$menus = $web->get_menus();
$smarty->assign('menus', $menus);

//焦点图
$banners = $web->get_ads(1);
$smarty->assign('banners', $banners);

$foot_info = $web->get('id=1', 'simple');
$smarty->assign('foot_info', $foot_info);

//友情链接
$friend_links = $web->get_links(1, 10);
$smarty->assign('friend_links', $friend_links);

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


    $company_info = $web->get_simple(10);
    $smarty->assign('company_info', $company_info);

    $service_info = $web->get_simple(16);
    $smarty->assign('service_info', $service_info);

    $hot_articles = $web->get_articles(11, 5, 0, 1);
    $smarty->assign('hot_articles', $hot_articles);

    $smarty->display('index.htm');

} else {

    $menu = $web->get('menu_id=' . $mid, 'menus');
    if (!$menu) {
        showMsg("出错了，您访问的页面不存在！");
    }
    $type_id = intval($menu['type_id']);
    $smarty->assign('current_menu', $menu);

    $top_mid = $web->get_top_mid($mid);
    $smarty->assign('top_mid', $top_mid);

    $left_title = $web->get_left_title($top_mid);
    $smarty->assign('left_title', $left_title);

    //下级分类
    $sub_menus = $web->get_sub_menus($top_mid);
    $smarty->assign('sub_menus', $sub_menus);

    $position = $web->get_position($mid);
    $smarty->assign('position', $position);

    $menu_ids = [];
    if ($sub_menus) {
        foreach ($sub_menus as $m) {
            $menu_ids[] = $m['menu_id'];
        }
        array_push($menu_ids, $mid);
    }

    //单页
    if ($type_id == 1) {

        $info = $web->get_simple($mid);

        if (!$info) {
            showMsg("出错了，您访问的页面不存在！");
        }

        $smarty->assign('info', $info);
        $smarty->display('page.htm');

    } //文章
    else if ($type_id == 2) {

        //详情
        if (isset($id) && !empty($id)) {

            $info = $web->get("art_id = $id", 'article');
            if (!$info) {
                showMsg("出错了，您访问的页面不存在！");
            }

            $smarty->assign('info', $info);
            $smarty->display('article_show.htm');

            //列表
        } else {

            $page_size = 10;
            $where = '';

            if (!empty($keyword)) {
                $where .= " and art_title like '%$keyword%'";
            }

            if (count($menu_ids) > 0) {
                $where .= " and menu_id " . db_create_in($menu_ids);
            } else {
                $where .= " and menu_id = $mid";
            }

            $sql = "select count(*) from " . $prefix . "article where 1=1 $where";
            $all = $db->getOne($sql);

            if (is_array($all)) {
                $all_num = $all[0];
            } else {
                $all_num = $all;
            }

            $all_page = ceil($all_num / $page_size);

            if (empty($page) || !is_int($page) || $page < 1) {
                $page = 1;
            }

            if ($page >= $all_page && $page > 0) {
                $page = $all_page;
            }

            $sql = "select * from " . $prefix . "article where 1=1 $where order by sort_order asc, add_time desc limit " . ($page - 1) * $page_size . "," . $page_size;

            $data = $db->getAll($sql);
            $pager = new Subpage($page_size, $all_num, $page, 5, "index.php?mid=$mid&keywork=" . urldecode($keyword) . "&page=");

            $smarty->assign('data', $data);
            $smarty->assign('pager', $pager->subpagehtml_a());
            $smarty->display('article_list.htm');
        }


    } //留言
    else if ($type_id = 5) {

        //提交留言
        if (IS_POST) {
            $data = [
                'name' => $_POST['name'],
//                'sex' => $_POST['sex'],
//                'tel' => $_POST['tel'],
                'phone' => $_POST['phone'],
                'qq' => $_POST['qq'],
                'email' => $_POST['email'],
                'content' => $_POST['content'],
                'add_time' => time()
            ];
            if ($db->insert($data, "feedback") > 0) {
                if (IS_AJAX) {
                    return json_encode(['提交成功！']);
                    exit;
                } else {
                    showMsg("您的留言已提交，请耐心等待答复！");
                }
            } else {
                showMsg("出错了，您访问的页面不存在！");
            }
        }

        $page_size = 10;
        $where = '';

        if (!empty($keyword)) {
            $where .= "and (content like '%$keyword%' or name like '%$keyword%')";
        }

        $sql = "select count(*) from " . $prefix . "feedback where 1=1 $where";
        $all = $db->getOne($sql);

        if (is_array($all)) {
            $all_num = $all[0];
        } else {
            $all_num = $all;
        }

        $all_page = ceil($all_num / $page_size);

        if (empty($page) || !is_int($page) || $page < 1) {
            $page = 1;
        }

        if ($page >= $all_page && $page > 0) {
            $page = $all_page;
        }

        $sql = "select * from " . $prefix . "feedback where 1=1 $where order by sort_order asc, add_time desc limit " . ($page - 1) * $page_size . "," . $page_size;

        $data = $db->getAll($sql);
        $pager = new Subpage($page_size, $all_num, $page, 5, "index.php?mid=$mid&keywork=" . urldecode($keyword) . "&page=");

        $smarty->assign('data', $data);
        $smarty->assign('pager', $pager->subpagehtml_a());
        $smarty->display('feedback.htm');

    }

}