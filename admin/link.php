<?php
define('IN_XM', true);
require(dirname(__FILE__) . '/includes/init.php');
include(ROOT_PATH . 'includes/cls.subpage.php');
require(ROOT_PATH . 'includes/cls.image.php');
//检测登录
check_login();
$act = empty($_GET['act']) ? "" : trim($_GET['act']);
$id = empty($_GET['id']) ? "" : intval($_GET['id']);
$ids = empty($_GET['ids']) ? "" : trim($_GET['ids']);
$cate_id = empty($_GET['cate_id']) ? "" : intval($_GET['cate_id']);
$val = empty($_GET['val']) ? "" : trim($_GET['val']);
$field = empty($_GET['field']) ? "" : trim($_GET['field']);
$ajax = empty($_GET['ajax']) ? "" : intval($_GET['ajax']);
$page = isset($_GET['page']) ? intval($_GET['page']) : "1";
$smarty->assign('page', $page);
$smarty->assign('id', $id);
$smarty->assign('cate_id', $cate_id);
$smarty->assign('act', $act);

/*编辑*/
if ($act == "edit") {
    if ($id) {
        $link = $db->get_info("lid=$id and cate_id=$cate_id", "link");
        if (!IS_POST) {

            $smarty->assign('link', $link);
            $smarty->display("link_add.htm");
        } else {
            if (empty($_POST['name'])) {
                showMsg('链接名称不能为空！');
            }
            if (!empty($_FILES['default_image']['name'])) {
                $save_path = "uploadfile/link/";
                $html_name = "default_image";
                $default_image = uploads(ROOT_PATH, $save_path, $html_name, 189, 80);
                if (file_exists('../' . $default_image)) {
                    @unlink('../' . $link['default_image']);
                }
            } else {
                $default_image = $link['default_image'];
            }
            $data = array(
                'name' => $_POST['name'],
                'default_image' => $default_image,
                'url' => $_POST['url'],
                'sort_order' => intval($_POST['sort_order']),
                //'add_time'=>time()
            );

            if ($db->update($data, "lid=$id", "link") > 0) {
                $links[0] = array('text' => "返回列表", 'href' => "link.php?cate_id=$cate_id&page=$page");
                $links[1] = array('text' => "继续编辑", 'href' => "link.php?cate_id=$cate_id&act=edit&page=$page&id=$id");
                showMsg('编辑链接成功！', 1, $links);
            } else {
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
                    $link = $db->get_info("lid=$id and cate_id=$cate_id", "link");
                    if ($db->delete("lid=$id", "link")) {
                        logs("删除链接：" . $link['name']);
                        @unlink(ROOT_PATH . $link['default_image']);
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
        $smarty->display("link_add.htm");
    } else {
        if (empty($_POST['name'])) {
            showMsg('名称不能为空！');
        }
        $default_image = '';
        if ($_GET['cate_id'] == 2) {  //合作伙伴必须上传链接图片
            if (!empty($_FILES['default_image']['name'])) {
                $save_path = "uploadfile/link/";
                $html_name = "default_image";
                $default_image = uploads(ROOT_PATH, $save_path, $html_name, 189, 80);
                if (empty($default_image)) {
                    showMsg("默认图片上传失败！");
                }
            } else {
                showMsg("请上传默认图片！");
            }
        }
        $data = array(
            'cate_id' => $cate_id,
            'name' => $_POST['name'],
            'default_image' => $default_image,
            'url' => $_POST['url'],
            'sort_order' => intval($_POST['sort_order']),
            'add_time' => time()
        );
        //print_r($data);exit;
        if ($db->insert($data, "link") > 0) {
            $links[0] = array('text' => "返回列表", 'href' => "link.php?cate_id=$cate_id");
            $links[1] = array('text' => "继续添加", 'href' => "link.php?cate_id=$cate_id&act=add");
            logs("添加链接：" . $data['name']);
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
    if ($db->update($data, "lid=$id", "link")) {
        echo 1;
    } else {
        echo 0;
    }
    if ($ajax) {
        exit;
    }
} /*列表*/
else {
    //条件
    $where = "";
    $keyword = (isset($_GET['keyword'])) ? urldecode(trim($_GET['keyword'])) : "";
    if (!empty($keyword)) {
        $where .= "and (name like '%$keyword%')";
    }
    /*分页*/
    $page_size = 12;
    $sql = "select count(*) from " . $prefix . "link where cate_id=$cate_id $where";
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
    $sql = "select * from " . $prefix . "link where cate_id=$cate_id $where order by sort_order asc limit " . ($page - 1) * $page_size . " , " . $page_size . "";
    $links = $db->getAll($sql);
    $smarty->assign("links", $links);
    $subpage = new Subpage($page_size, $all_num, $page, 5, "link.php?cate_id=$cate_id&keyword=" . urlencode($keyword) . "&page=");

    $smarty->assign('subpage', $subpage->subpagehtml_a());
    $smarty->display("link.htm");
}
?>