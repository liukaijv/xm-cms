<?php
define('IN_XM', true);
require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/cls.smtp.php');
//检测登录
check_login();
/*参数接收*/
$act = empty($_REQUEST['act']) ? "base" : trim($_REQUEST['act']);

/*基本设置*/
if ($act == "base") {
    if (IS_POST) {
        if ($_POST['foot_content']) {
            $db->update(array('content' => $_POST['foot_content']), "id=1 and menu_id=0", "simple");
        }
        $data = array(
            'site_title' => $_POST['site_title'],
            'site_keyword' => $_POST['site_keyword'],
            'site_description' => $_POST['site_description'],
            'site_domain' => $_POST['site_domain'],
            'site_copyright' => $_POST['site_copyright'],
            'site_qq' => $_POST['site_qq'],
            'service_qq' => $_POST['service_qq'],
            'site_company_address' => $_POST['site_company_address'],
            'site_tel' => $_POST['site_tel'],
            'site_fax' => $_POST['site_fax'],
            'site_email' => $_POST['site_email'],
            'site_phone' => $_POST['site_phone'],
        );
        $post_data = array_merge($CFG, $data);
        $arrayfile = new Arrayfile($post_data, CONFIGFILE);
        showMsg("基本信息设置成功！", 1);
    }
} /*邮件设置*/
elseif ($act == "email") {
    if (IS_POST) {
        $data = array(
            'email_method' => $_POST['email_method'],
            'smtp_service' => $_POST['smtp_service'],
            'smtp_port' => $_POST['smtp_port'],
            'send_user_email' => $_POST['send_user_email'],
            'auth_username' => $_POST['auth_username'],
            'auth_password' => base64_encode($_POST['auth_password'] . mt_rand(1000, 9999)),
            'test_email' => $_POST['test_email'],
        );
        $post_data = array_merge($CFG, $data);
        $arrayfile = new Arrayfile($post_data, CONFIGFILE);
        showMsg("邮件信息设置成功！");
    }
} /*上传设置*/
elseif ($act == "upload") {
    if (IS_POST) {
        $data = array(
            'is_thumb' => $_POST['is_thumb'],
            'thumb_way' => $_POST['thumb_way'],
            'thumb_width' => $_POST['thumb_width'],
            'thumb_height' => $_POST['thumb_height'],
            'img_maxsize' => $_POST['img_maxsize'],
            'video_maxsize' => $_POST['video_maxsize'],
        );
        $post_data = array_merge($CFG, $data);
        $arrayfile = new Arrayfile($post_data, CONFIGFILE);
        showMsg("上传信息设置成功！");
    }
} /*验证码设置*/
elseif ($act == "code") {
    if (IS_POST) {
        $data = array(
            'captcha' => array(
                'a' => empty($_POST['captcha1']) ? 0 : $_POST['captcha1'],
                'b' => empty($_POST['captcha2']) ? 0 : $_POST['captcha2'],
                'c' => empty($_POST['captcha3']) ? 0 : $_POST['captcha3'],
                'd' => empty($_POST['captcha4']) ? 0 : $_POST['captcha4'],
                'e' => empty($_POST['captcha5']) ? 0 : $_POST['captcha5'],
            ),
        );
        $post_data = array_merge($CFG, $data);
        $arrayfile = new Arrayfile($post_data, CONFIGFILE);
        showMsg("验证码设置成功！");
    }
} /*伪静态设置*/
elseif ($act == "html") {

    $htc_file = ROOT_PATH . ".htaccess";

    if (IS_POST) {
        if (!empty($_POST['html_rules'])) {
            /*print_r(explode('\r\n',htmlspecialchars($_POST['html_rules'])));exit;
            fopen($htc_file,"w");
            fwrite($htc_file,explode("\r\n","$_POST[html_rules]"));
            fclose($htc_file);*/
        }
        $data = array(
            'html' => $_POST['html']
        );
        $post_data = array_merge($CFG, $data);
        $arrayfile = new Arrayfile($post_data, CONFIGFILE);
        showMsg("伪静态设置成功！");
    } else {
        $html_rules = '';
        if (file_exists($htc_file)) {
            $file_handle = fopen($htc_file, "r");
            while (!feof($file_handle)) {
                $html_rules .= fgets($file_handle);
            }
            fclose($file_handle);
        }
        $smarty->assign("html_rules", $html_rules);
    }
} //其他信息设置
elseif ($act == "other") {
    if (IS_POST) {
        $data = array(
            //'lang' => $_POST['lang'],
            'caching' => $_POST['caching'],
            'cache_time' => $_POST['cache_time'],
            'template' => $_POST['template'],
        );
        $post_data = array_merge($CFG, $data);
        $arrayfile = new Arrayfile($post_data, CONFIGFILE);
        showMsg("其他信息设置成功！");
    }
} //授权设置
elseif ($act == "author") {
    if (IS_POST) {
        $data = array(
            'author_name' => $_POST['author_name'], //授权账号
            'author_pwd' => base64_encode($_POST['author_pwd'] . mt_rand(1000, 9999)), //授权密码
        );
        $post_data = array_merge($CFG, $data);
        $arrayfile = new Arrayfile($post_data, CONFIGFILE);
        showMsg("授权信息设置成功！");
    }
} //测试邮件发送
elseif ($act == "mail") {
    $server = $_GET['server'];
    $usermail = $_GET['usermail'];
    $user = $_GET['user'];
    $pass = $_GET['pass'];
    $mailto = $_GET['mailto'];
    $sub = "测试邮件";
    $body = "<p>这是一条测试邮件，请勿回复，谢谢！</p>";
    if ($re = send_email($server, $usermail, $mailto, $sub, $body, true, $user, $pass)) {
        echo 1;
    } else {
        //echo $server.$usermail.$mailto.$sub.$body.$user.$pass;
        echo 0;
    }
    exit;
}

$new_data = file_exists(CONFIGFILE) ? array_merge(Arrayfile::getDefault(), Arrayfile::_loadfromfile(CONFIGFILE)) : Arrayfile::getDefault();
if (is_array($new_data)) {
    foreach ($new_data as $k => $v) {
        /*if($k=="site_total"){
            $new_datas[$k]=stripslashes($v);
        }else{
            $new_datas[$k]=$v;
        }*/
        if ($k == "auth_password" || $k == "author_pwd") {
            $new_datas[$k] = substr(base64_decode($v), 0, -4);
        } else {
            $new_datas[$k] = $v;
        }
    }
}
$foot = $db->get_info('id=1 and menu_id =0', 'simple');
$smarty->assign('foot', $foot);
$smarty->assign("CFG", $new_datas);
// print_r($new_datas);
$smarty->display("setting.htm", rand());