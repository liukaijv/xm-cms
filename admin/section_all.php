<?php
/*
** 开发人员所有栏目管理
**/
define('IN_XM', true);
include(dirname(__FILE__) . '/includes/init.php');
//检测登录
check_login();
/*参数接收*/
$act = empty($_GET['act']) ? "" : trim($_GET['act']);
$id = empty($_GET['id']) ? "" : intval($_GET['id']);
$ids = empty($_GET['ids']) ? "" : trim($_GET['ids']);
$pid = empty($_GET['pid']) ? 0 : intval($_GET['pid']);
$val = empty($_GET['val']) ? "" : trim($_GET['val']);
$field = empty($_GET['field']) ? "" : trim($_GET['field']);
$ajax = empty($_GET['ajax']) ? "" : intval($_GET['ajax']);
$ret_url = isset($_GET['ret_url']) && !empty($_GET['ret_url']) ? rawurldecode($_GET['ret_url']) : "";

if ($act == "add") {
    $section = $db->get_info("menu_id=$pid", "menus");
    $smarty->assign("section", $section);
    if (IS_POST) {
        if (trim($_POST['menu_name']) == '') {
            showMsg("栏目名不能为空！");
        }
        $data = array(
            'menu_name' => trim($_POST['menu_name']),
            'menu_name_en' => trim($_POST['menu_name_en']),
            'parent_id' => intval($pid),
            'type_id' => intval($_POST['type_id']),
            'sort_order' => intval($_POST['sort_order']),
            'is_show' => empty($_POST['is_show']) ? 0 : 1,
            'can_delete' => empty($_POST['can_delete']) ? 0 : 1,
        );
        if ($mid = $db->insert($data, "menus")) {
            //添加单页图文信息
            $data1 = array(
                'menu_id' => $mid,
                'name' => $data['menu_name'],
                'content' => ''
            );
            $db->insert($data1, "simple");

            $links[0] = array('text' => "返回列表", 'href' => "?pid=0");
            $links[1] = array('text' => "继续添加", 'href' => "?pid=$pid&act=add");
            showMsg("添加成功！", 1, $links);
        } else {
            showMsg("添加失败！");
        }
    }
    $smarty->assign("menu_types", $web->get_menu_type());
    $smarty->display("section_all_add.htm");
} elseif ($act == "edit") {
    if (!$id) {
        showMsg("参数错误！");
    }
    //当前栏目
    $menu = $db->get_info("menu_id=$id", "menus");
    $smarty->assign("menu", $menu);
    //上级栏目
    $section = $db->get_info("menu_id={$menu['parent_id']}", "menus");
    $smarty->assign("section", $section);
    if (IS_POST) {
        if (trim($_POST['menu_name']) == '') {
            showMsg("栏目名不能为空！");
        }
        $data = array(
            'menu_name' => trim($_POST['menu_name']),
            'menu_name_en' => trim($_POST['menu_name_en']),
            'type_id' => intval($_POST['type_id']),
            'sort_order' => intval($_POST['sort_order']),
            'is_show' => isset($_POST['is_show']) ? 1 : 0,
            'can_delete' => isset($_POST['can_delete']) ? 1 : 0,
        );
        if ($db->update($data, "menu_id=$id", "menus")) {
            //编辑单页图文栏目
            $data1 = array(
                'name' => $data['menu_name'],
            );
            $db->update($data1, "menu_id=$id", "simple");


            $links[0] = array('text' => "返回列表", 'href' => "?pid=0");
            $links[1] = array('text' => "继续编辑", 'href' => "?pid=$pid&act=edit&id=$id");
            showMsg("编辑成功！", 1, $links);
        } else {
            showMsg("编辑失败，也许你没有做任何修改！");
        }
    }
    $smarty->assign("menu_types", $web->get_menu_type());
    $smarty->display("section_all_add.htm");
} elseif ($act == "del") {
    if ($ids) {
        $ids = @explode(',', $ids);
        foreach ($ids as $id) {
            $menu = $web->get("menu_id=$id", "menus");
            $child = $db->fetchAll("menus", "parent_id=$id");
            if (!count($child) > 0) {
                if ($id) {

                    $db->delete("menu_id=$id", "menus");
                    if ($menu['type_id'] == 1) {
                        $db->delete("menu_id=$id", "simple");
                    }
                }
            }
        }
        echo 1;
    }
    if ($ajax) {
        exit;
    }
} else if ($act == "ajax_edit") {
    if ($field) {
        $data = array($field => $val);
    } else {
        echo 0;
        exit;
    }

    if ($db->update($data, "menu_id=$id", "menus")) {
        if ($field == 'name') {  //字段为name
            //编辑单页图文栏目
            $db->update($data, "menu_id=$id", "simple");
        }
        echo 1;
    } else {
        echo 0;
    }
    if ($ajax) {
        exit;
    }
} else if ($act == "show") {
    $info = $web->get("menu_id=$id", "menus");
    $show = ($info['is_show'] == 1) ? 0 : 1;
    $data = array('is_show' => $show);
    if ($db->update($data, "menu_id=$id", "menus")) {
        echo 1;
    } else {
        echo 0;
    }
    if ($ajax) {
        exit;
    }
} else {
    if ($pid) {
        $menus = $web->get_back_menu($pid);
    } else {
        $menus = $web->get_back_menu(0, 0);
    }
    $smarty->assign("menus", $menus);
    $smarty->display("section_all.htm");
}

?>