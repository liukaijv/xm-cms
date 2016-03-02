<?php
/*-------------------------------------------------
	Content:一些系统内置函数库，依赖于数据库及smarty	
-------------------------------------------------*/

/**
 * 系统提示信息
 *
 * @access      public
 * @param       string      msg      消息内容
 * @param       int         msg_type        消息类型， 0消息，1错误，2询问
 * @param       array       links           可选的链接
 * @param       boolen $auto_jump 是否需要自动跳转
 * @return      void
 */
function showMsg($msg, $msg_type = 0, $links = array(), $auto_jump = 1, $time = 2)
{
    if (count($links) == 0 || $links == '') {
        $links[0]['text'] = "返回上一页";
        $links[0]['href'] = 'javascript:history.go(-1)';
    }

    $GLOBALS['smarty']->assign('ur_here', "系统提示信息");
    $GLOBALS['smarty']->assign('msg', $msg);
    $GLOBALS['smarty']->assign('msg_type', $msg_type);
    $GLOBALS['smarty']->assign('links', $links);
    $GLOBALS['smarty']->assign('default_url', $links[0]['href']);
    $GLOBALS['smarty']->assign('time', $time);
    $GLOBALS['smarty']->assign('auto_jump', $auto_jump);
    $GLOBALS['smarty']->assign('time', $time);


    $GLOBALS['smarty']->display('_message.htm');

    exit;
}

/**
 * 邮件发送，需引入库文件
 *    $server        //smtp服务器
 *    $usermail        //发件人邮箱
 *    $emailto        //收件人邮箱
 *    $subject        //邮件主题
 *    $body            //邮件内容
 *    $user            //验证用户名
 *    $pass            //验证用户密码
 *    $type            //邮件发送格式
 *    $port            //smtp端口
 * @return
 */
function send_email($server, $usermail, $emailto, $subject, $body, $auth, $user = '', $pass = '', $type = 'HTML', $port = 25)
{
    if (empty($server)) {
        $server = $GLOBALS['CFG']['smtp_service'];
    }

    $smtp = new Smtp($server, $port, $auth, $user, $pass);    //这里面的一个true是表示使用身份验证,否则不使用身份验证.
    $smtp->debug = false;                                //是否显示发送的调试信息
    if ($smtp->sendmail($emailto, $usermail, $subject, $body, $type)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 上传方法，需引入库文件
 *
 * @return
 */
function upload($root_path, $save_path, $html_name, $thumb_w, $thumb_h, $is_big = 0, $is_water = 0, $water_logo = '')
{
    $filename_info = pathinfo($_FILES[$html_name]['name']);
    $randname = time() . mt_rand(1000, 9999);
    $saveFileName = $randname . '.' . $filename_info['extension'];
    //上传图片目录
    $savePath = $root_path . $save_path;     //大图路径
    $img = new Image();
    $upimgPath = $img->setsaveName($saveFileName)->upfile($html_name, $savePath);//将图片上传至$savePath并保存图片名

    //创建缩略图
    $thumbpath = $root_path . $save_path;    //缩略图目录
    $thumbFileName = "thumb_" . $saveFileName; //缩略图名称

    //以下处理只取上传文件名，不包含路径（完整路径$file_all_path）
    $upload_img_thumb = str_replace($thumbpath, '', $file_all_path = $img->setShrinkWidth($thumb_w)//缩略图宽度
    ->setShrinkHeight($thumb_h)//缩略图高度
    ->setShrinkType("jpg")//缩略图类型
    ->setsaveName($thumbFileName)//缩略图名字
    ->resizeImage($upimgPath, $thumbpath));    //生成缩略图方法$upimgPath为大图地址

    if (file_exists($file_all_path)) {
        if (!$is_big) {
            @unlink($upimgPath);
        }                                                //生成缩略图后删除原图
        if ($is_water && file_exists($water_logo)) {
            $waterFileName = "water_" . $saveFileName;                    //缩略图带水印名称
            $waterlogo = $water_logo;                                    //水印图片
            $waterpath = $root_path . $save_path;;                        //加有水印图_图片目录
            $upload_img_water = str_replace($waterpath, '', $file_water = $img->setsaveName($waterFileName)
                ->setImgWaterPosType(4)//加水印位置 4 为右下角
                ->read_waterImg($waterlogo)//读取水印LOGO
                ->createImg($file_all_path, $waterpath));    //加水印方法
            if (file_exists($file_water)) {
                @unlink($file_all_path);                                //删除原缩略图
                return $save_path . $upload_img_water;
            }
        } else {
            return $save_path . $upload_img_thumb;
        }
    } else {
        return '';
    }
}

/**
 * 上传方法，需引入库文件cls.image.php
 *
 * @return
 */
function uploads($root_path, $save_path, $html_name, $thumb_w, $thumb_h, $is_thumb = 1, $is_two = 0, $is_cut = 0)
{
    //目的目录
    $uploaddir = $root_path . $save_path;
    //重写文件名
    $name = time() . rand(1000, 9999);
    //取得扩展名
    $ext = substr($_FILES[$html_name]['name'], strrpos($_FILES[$html_name]['name'], '.'));
    //拼出完整文件名
    $fullname = $name . $ext;
    //以下不重写文件名
    //$uploadfile = $uploaddir . basename($_FILES['myfile']['name']);

    //继续拼出上传目录
    $uploadfile = $uploaddir . $fullname;
    //开始上传,输出结果对应上传页的response
    if (move_uploaded_file($_FILES[$html_name]['tmp_name'], $uploadfile)) {
        if ($is_thumb) {
            //缩略图
            $img = new Upload();
            $thumb_name = 'thumb_' . $fullname;
            $thumb_file = $img->param($uploadfile)->thumb($uploaddir . $thumb_name, $thumb_w, $thumb_h, $is_cut);
            if (!$is_two) {
                @unlink($uploadfile);
            }
            if (file_exists($thumb_file)) {
                return $save_path . $thumb_name;
            } else {
                return '';
            }
        } else {
            return $save_path . $fullname;
        }
    }
}

/**
 * 检测是否登录
 *
 * @return      bool
 */
function has_login()
{
    if (empty($_SESSION['admin_info']['admin_username']) || !isset($_SESSION['admin_info']['admin_id'])) {
        /*$links[]=array('text'=>"返回登录",'href'=>"index.php?act=login");
        showMsg("你还没有登录或是登录超时，请重新登录！",1,$links);*/
        return false;
    } else {
        return true;
    }
}

function check_login()
{
    if (!has_login()) {
        @session_destroy();
        //bf_header("location: index.php?act=login");
        echo "<script>alert('登陆超时，请重新登录！');top.location.href='index.php?act=login';</script>";
    }
}

/**
 * 写入管理员日志
 *
 * @return      bool
 */
function logs($action)
{
    if ($action) {
        $GLOBALS['web']->admin_log($action);
    }
}