<?php
define('IN_XM', true);
require(dirname(__FILE__) . '/includes/init.php');
include(ROOT_PATH . "includes/cls.subpage.php");
require(ROOT_PATH . 'includes/cls.image.php');
//检测登录
check_login();
$act = empty($_GET['act']) ? "" : trim($_GET['act']);
$id = empty($_GET['id']) ? "" : intval($_GET['id']);
$ids = empty($_GET['ids']) ? "" : trim($_GET['ids']);
$mid = empty($_GET['mid']) ? "" : intval($_GET['mid']);
$val = empty($_GET['val']) ? "" : trim($_GET['val']);
$field = empty($_GET['field']) ? "" : trim($_GET['field']);
$ajax = empty($_GET['ajax']) ? "" : intval($_GET['ajax']);
$page = isset($_GET['page']) ? intval($_GET['page']) : "1";
$smarty->assign('page', $page);
$smarty->assign('id', $id);
$smarty->assign('mid', $mid);
$smarty->assign('act', $act);
if ($mid) {
    $info = $web->get("menu_id=$mid", "menus");
    $smarty->assign("info", $info);
} else {
    showMsg("参数错误！");
}

/*编辑*/
if ($act == "edit") {
    if ($id) {
        $art = $db->get_info("art_id=$id and menu_id=$mid", "article");
        if (!IS_POST) {
            $smarty->assign('art', $art);
            $smarty->display("article_add.htm");
        } else {
            if (empty($_POST['art_title'])) {
                showMsg('文章标题不能为空！', 1);
            }

            if (!empty($_FILES['default_image']['name'])) {
                $save_path = "uploadfile/art/";
                $html_name = "default_image";
                $default_image = uploads(ROOT_PATH, $save_path, $html_name, 240, 240, 1);
                if (file_exists('../' . $default_image)) {
                    @unlink("../" . $art['default_image']);
                    if (file_exists("../" . str_replace('thumb_', '', $art['default_image']))) {
                        @unlink("../" . str_replace('thumb_', '', $art['default_image']));
                    }
                }
            } else {
                $default_image = $art['default_image'];
            }

            $data = array(
                'art_title' => $_POST['art_title'],
                'art_content' => $_POST['art_content'],
                'art_from' => $_POST['art_from'],
                'default_image' => $default_image,
                'view_times' => intval($_POST['view_times']),
                'art_author' => empty($_POST['art_author']) ? "Admin" : $_POST['art_author'],
                'sort_order' => $_POST['sort_order'],
                //'add_time'=>empty($_POST['add_time']) ? time() : strtotime($_POST['add_time'])
            );
            if ($db->update($data, "art_id=$id", "article") > 0) {
                $links[0] = array('text' => "返回列表", 'href' => "article.php?mid=$mid&page=$page");
                $links[1] = array('text' => "继续编辑", 'href' => "article.php?mid=$mid&act=edit&page=$page&id=$id");
                showMsg('编辑成功！', 1, $links);
            } else {
                //$links[0]=array('text'=>"返回继续编辑",'href'=>'article.php?act=edit&page='.$page.'&id='.$id);
                showMsg('编辑失败，也许你没有做任何修改！');
            }
        }
    } else {
        showMsg('系统参数错误！', 1);
    }
} /*删除*/
elseif ($act == "del") {
    if ($ids) {
        $ids = @explode(',', $ids);
        if (is_array($ids)) {
            foreach ($ids as $id) {
                if ($id) {
                    $art = $db->get_info("art_id=$id and menu_id=$mid", "article");
                    if ($db->delete("art_id=$id", "article")) {
                        @unlink("../" . str_replace('thumb_', '', $art['default_image']));
                        @unlink("../" . $art['default_image']);
                    }
                }
            }
            echo 1;
        }
    }
    if ($ajax) {
        exit;
    }
} /*添加*/
elseif ($act == "add") {
    if (!IS_POST) {
        $smarty->display("article_add.htm");
    } else {
        if (empty($_POST['art_title'])) {
            showMsg('标题不能为空！');
        }
        if (!empty($_FILES['default_image']['name'])) {
            $save_path = "uploadfile/art/";
            $html_name = "default_image";
            $default_image = uploads(ROOT_PATH, $save_path, $html_name, 240, 240, 1);
        } else {
            $default_image = "";
        }
        $data = array(
            'menu_id' => $mid,
            'art_title' => $_POST['art_title'],
            'art_content' => $_POST['art_content'],
            'art_from' => $_POST['art_from'],
            'default_image' => $default_image,
            'view_times' => intval($_POST['view_times']),
            'art_author' => empty($_POST['art_author']) ? "Admin" : $_POST['art_author'],
            'add_time' => empty($_POST['add_time']) ? time() : strtotime($_POST['add_time']),
            'sort_order' => $_POST['sort_order'],
        );
        if ($db->insert($data, "article") > 0) {
            $links[0] = array('text' => "返回列表", 'href' => "article.php?mid=$mid");
            $links[1] = array('text' => "继续添加", 'href' => "article.php?mid=$mid&act=add");
            showMsg('添加成功！', 0, $links);
        } else {
            showMsg('添加失败！', 1);
        }
    }
} elseif ($act == "ajax_edit") {
    if ($field) {
        $data = array($field => $val);
    } else {
        echo 0;
        exit;
    }
    if ($db->update($data, "art_id=$id", "article")) {
        echo 1;
    } else {
        echo 0;
    }
    if ($ajax) {
        exit;
    }
} else if ($act == "recom") {
    $info = $web->get("art_id=$id", "article");
    $show = ($info['is_recom'] == 1) ? 0 : 1;
    $data = array('is_recom' => $show);
    if ($db->update($data, "art_id=$id", "article")) {
        echo 1;
    } else {
        echo 0;
    }
    if ($ajax) {
        exit;
    }
} else if ($act == "top") {
    $info = $web->get("art_id=$id", "article");
    $show = ($info['is_top'] == 1) ? 0 : 1;
    $data = array('is_top' => $show);
    if ($db->update($data, "art_id=$id", "article")) {
        echo 1;
    } else {
        echo 0;
    }
    if ($ajax) {
        exit;
    }
} /*列表*/
else {
    //子栏目
    $sub_menus = $web->get_sub_menu($mid);
    $subids = array();
    if ($sub_menus) {
        foreach ($sub_menus as $m) {
            $subids[] = $m['menu_id'];
        }
        array_push($subids, $mid);
    }
    //条件
    $where = "";
    $keyword = (isset($_GET['keyword'])) ? urldecode(trim($_GET['keyword'])) : "";
    if (!empty($keyword)) {
        $where .= "and art_title like '%$keyword%'";
    }
    if (count($subids) > 0) {
        $where .= " and menu_id " . db_create_in($subids) . "";
    } else {
        $where .= " and menu_id=$mid";
    }
    /*分页*/
    $page_size = 12;
    $sql = "select count(*) from " . $prefix . "article where 1=1 $where";
    $all = $db->getOne($sql);
    if (is_array($all)) {
        $all_num = $all[0];
    } else {
        $all_num = 0;
    }
    $all_page = ceil($all_num / $page_size);
    if (empty($page) || !is_int($page) || $page < 1) {
        $page = 1;
    }
    if ($page >= $all_page && $all_page > 0) {
        $page = $all_page;
    }
    $sql = "select * from " . $prefix . "article where 1=1 $where order by sort_order asc, add_time desc limit " . ($page - 1) * $page_size . " , " . $page_size . "";
    $arts = $db->getAll($sql);
    $smarty->assign("arts", $arts);
    $subpage = new Subpage($page_size, $all_num, $page, 5, "article.php?mid=$mid&keyword=" . urlencode($keyword) . "&page=");

    $smarty->assign('subpage', $subpage->subpagehtml_a());
    $smarty->display("article.htm");
}
?>