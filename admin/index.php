<?php
define('IN_XM', true);
include(dirname(__FILE__) . '/includes/init.php');
/*参数接收*/
$act = empty($_GET['act']) ? "" : trim($_GET['act']);
$ret_url = isset($_GET['ret_url']) && !empty($_GET['ret_url']) ? rawurldecode($_GET['ret_url']) : "";

/*登录动作*/
if ($act == "login") {
    if (IS_POST) {
        $admin_username = trim($_POST['admin_username']);
        $admin_pwd = trim($_POST['admin_userpwd']);
        if (empty($admin_username) || empty($admin_pwd)) {
            showMsg("请填写用户名密码！", 3);
        }
        /*若启用了验证码*/
        if ($CFG['captcha']['e']) {
            $captcha = trim($_POST['captcha']);
            if (strtoupper($captcha) != strtoupper($_SESSION['code'])) {
                $links[] = array('text' => "重新登录", 'href' => 'index.php');
                showMsg('验证码错误！', 3, $links);
            }
        }
        /*执行登录操作*/
        if ($admin_info = $web->do_login($admin_username, $admin_pwd)) {
            $_SESSION['admin_info'] = $admin_info;
            logs("登陆成功！");
            $links[] = array('text' => "进入系统", 'href' => 'index.php');
            showMsg('登录成功！', 1, $links, true);
        } else {
            $links[] = array('text' => "重新登录", 'href' => 'index.php');
            showMsg('用户名或密码错误，登录失败！', 3, $links);
        }
    } else {
        //print_r($jsmenu);exit;
        $smarty->display("login.htm");
        exit;
    }
} /*退出登录*/
elseif ($act == "logout") {
    @session_destroy();
    my_header("location: index.php?act=login");
} elseif ($act == "default") {
    $smarty->display("default.htm");
} else {
    if (!isset($_SESSION['admin_info']['admin_id'])) {
        $_SESSION['admin_info']['admin_id'] = 0;
    }
    $cache_id = md5($_SESSION['admin_info']['admin_id']);
    if (has_login()) {
        if (!$smarty->is_cached('index.htm')) {
            $menus = $web->get_back_menu(0, '');
            $smarty->assign("menus", $menus);
            switch (date("w", time())) {
                case 0:
                    $week = "星期天";
                    break;
                case 1:
                    $week = "星期一";
                    break;
                case 2:
                    $week = "星期二";
                    break;
                case 3:
                    $week = "星期三";
                    break;
                case 4:
                    $week = "星期四";
                    break;
                case 5:
                    $week = "星期五";
                    break;
                case 6:
                    $week = "星期六";
                    break;
            }
            $smarty->assign("week", $week);
            $smarty->assign("date", date("Y年m月d日", time()));
        }
        $smarty->display("index.htm");
    } else {
        my_header("location: index.php?act=logout");
    }
}

