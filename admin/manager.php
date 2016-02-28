<?php
define('IN_XM', true);
include(dirname(__FILE__) . '/includes/init.php');
include(ROOT_PATH . 'includes/cls.subpage.php');
//检测登录
check_login();
/*参数接收*/
$act = empty($_GET['act']) ? "" : trim($_GET['act']);
$id = empty($_GET['id']) ? "" : intval($_GET['id']);
$is_check = empty($_GET['is_check']) ? "" : intval($_GET['is_check']);
$is_recom = empty($_GET['is_recom']) ? "" : intval($_GET['is_recom']);
$ids = empty($_GET['ids']) ? "" : trim($_GET['ids']);
$val = empty($_GET['val']) ? "" : trim($_GET['val']);
$field = empty($_GET['field']) ? "" : trim($_GET['field']);
$ajax = empty($_GET['ajax']) ? "" : intval($_GET['ajax']);
$ret_url = isset($_GET['ret_url']) && !empty($_GET['ret_url']) ? rawurldecode($_GET['ret_url']) : "";

if ($act == "edit") {
    if (!$id) {
        showMsg("参数错误！");
    }
    //当前会员
    $admin = $db->get_info("admin_id=$id", "admin");
    $smarty->assign("admin", $admin);
    if (IS_POST) {
        $data = array(
            'admin_username' => trim($_POST['admin_username']),
            'admin_userpwd' => empty($_POST['admin_userpwd']) ? $admin['admin_userpwd'] : md5(trim($_POST['admin_userpwd'])),
            'real_name' => trim($_POST['real_name']),
            'sex' => trim($_POST['sex']),
            'email' => trim($_POST['email']),
            //'add_time'=>time(),
        );
        if ($data['admin_username'] && $data['admin_userpwd']) {
            if ($insert_id = $db->update($data, "admin_id=$id", "admin")) {
                $links[0] = array('text' => "返回列表", 'href' => "manager.php");
                $links[1] = array('text' => "继续编辑", 'href' => "manager.php?act=edit&id=$id");
                showMsg("编辑成功！", 1, $links);
            } else {
                showMsg("编辑失败，也许你没有做任何修改！");
            }
        }
    }
    $smarty->display("manager_add.htm");
} elseif ($act == "add") {
    if (IS_POST) {
        if (trim($_POST['admin_username']) == '') {
            showMsg("用户名不能为空！");
        }
        $data = array(
            'admin_username' => trim($_POST['admin_username']),
            'admin_userpwd' => md5(trim($_POST['admin_userpwd'])),
            'real_name' => trim($_POST['real_name']),
            'sex' => trim($_POST['sex']),
            'email' => trim($_POST['email']),
            'type_id' => 1,
            'add_time' => time(),
        );
        if ($data['admin_username'] && $data['admin_userpwd']) {
            if ($insert_id = $db->insert($data, "admin")) {
                $links[0] = array('text' => "返回列表", 'href' => "manager.php");
                $links[1] = array('text' => "继续添加", 'href' => "manager.php?act=edit");
                showMsg("添加成功！", 0, $links);
            } else {
                showMsg("添加失败，返回重新添加！");
            }
        }
    }
    $smarty->display("manager_add.htm");
} elseif ($act == "del") {
    if ($ids) {
        $ids = @explode(',', $ids);
        foreach ($ids as $id) {
            $admin = $web->get("admin_id=$id", "admin");
            if ($id) {
                //写入日志
                logs("删除管理员：" . $admin['admin_username']);
                $db->delete("admin_id=$id", "admin");
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
    if ($db->update($data, "admin_id=$id", "admin")) {
        echo 1;
    } else {
        echo 0;
    }
    if ($ajax) {
        exit;
    }
} else {
    //条件
    $where = "";
    $keyword = (isset($_GET['keyword'])) ? urldecode(trim($_GET['keyword'])) : "";
    if (!empty($keyword)) {
        $where .= "and (admin_username like '%$keyword%')";
    }
    /*分页*/
    $page_size = 15;
    $sql = "select count(*) from " . $prefix . "admin where 1=1 $where";
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
    $sql = "select * from " . $prefix . "admin where 1=1 $where order by add_time asc limit " . ($page - 1) * $page_size . " , " . $page_size . "";
    $admins = $db->getAll($sql);
    // print_r($admins);
    $smarty->assign("admins", $admins);
    $subpage = new Subpage($page_size, $all_num, $page, 5, "manager.php?keyword=" . urlencode($keyword) . "&page=");

    $smarty->assign('subpage', $subpage->subpagehtml_a());
    $smarty->assign("admins", $admins);
    $smarty->display("manager.htm");
}

?>