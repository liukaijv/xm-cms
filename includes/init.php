<?php
/*
* 前台公用文件
* 
*/

if (!defined('IN_XM')) {
    die('Hacking attempt');
}

/*报错设置*/
error_reporting(E_ALL);

if (__FILE__ == '') {
    die('Fatal error code: 0');
}

/*引入配置文件*/
require('./data/config.php');

/* 取得当前CMS所在的根目录 */
define('ROOT_PATH', str_replace('includes/init.php', '', str_replace('\\', '/', __FILE__)));

/* 初始化PHP设置 */
@ini_set('memory_limit', '64M');
@ini_set('session.cache_expire', 180);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies', 1);
@ini_set('session.auto_start', 0);
@ini_set('display_errors', 1);

/*包含路径设置为根目录   本地路径以ROOT_PATH起*/
if (DIRECTORY_SEPARATOR == '\\') {
    @ini_set('include_path', '.;' . ROOT_PATH);
} else {
    @ini_set('include_path', '.:' . ROOT_PATH);
}

/*PHP版本设置时区*/
if (PHP_VERSION >= '5.1' && !empty($timezone)) {
    date_default_timezone_set($timezone);
}

/*定义当前根文件名*/
if (isset($_SERVER['PHP_SELF'])) {
    define('PHP_SELF', $_SERVER['PHP_SELF']);
} else {
    define('PHP_SELF', $_SERVER['SCRIPT_NAME']);
}

/*引入公用非依赖性库函数文件*/
require(ROOT_PATH . 'includes/lib.common.php');

/* 对用户传入的变量进行转义操作。*/
if (!get_magic_quotes_gpc()) {
    if (!empty($_GET)) {
        $_GET = addslashes_deep($_GET);
    }
    if (!empty($_POST)) {
        $_POST = addslashes_deep($_POST);
    }
    $_COOKIE = addslashes_deep($_COOKIE);
    $_REQUEST = addslashes_deep($_REQUEST);
}

/* 对路径进行安全处理 */
if (strpos(PHP_SELF, '.php/') !== false) {
    header("Location:" . substr(PHP_SELF, 0, strpos(PHP_SELF, '.php/') + 4) . "\n");
    exit();
}

/*载入配置信息*/
require(ROOT_PATH . 'includes/cls.arrayfile.php');
define('CONFIGFILE', ROOT_PATH . "data/arrayfile/setting.inc.php");
$CFG = file_exists(CONFIGFILE) ? array_merge(Arrayfile::getDefault(), Arrayfile::_loadfromfile(CONFIGFILE)) : Arrayfile::getDefault();
$CFG['lang'] = (!isset($CFG['lang']) && empty($CFG['lang'])) ? 'zh_cn' : $CFG['lang'];
$CFG['cache_time'] = (!isset($CFG['cache_time']) && empty($CFG['cache_time'])) ? 0 : $CFG['cache_time'];
$CFG['template'] = (!isset($CFG['template']) && empty($CFG['template'])) ? 'default' : $CFG['template'];

/*站点后台session开启*/
@session_start();

/* 判断请求方式 */
define('IS_POST', (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST'));
define('IS_AJAX', (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest"));

/*初始化数据库类*/
require(ROOT_PATH . 'includes/cls.mysql.php');

/*初始化数据库连接*/
$db = new Mysql($db_host, $db_user, $db_pass, $db_name, $prefix);
$db_host = $db_user = $db_pass = $db_name = NULL;

/*引入全局公共功能函数 一些数据库操作 包括前台与后台操作*/
require(ROOT_PATH . 'includes/front.class.php');
$web = new Front($db, $prefix);

/*后台初始化 Smarty 对象*/
require_once(ROOT_PATH . 'includes/smarty/Smarty.class.php');
$smarty = new Smarty();

/*对smarty的一些配置信息，以及模板路径的初始化信息配置*/
$smarty->caching = $CFG['caching'] == 1 ? true : false;
$smarty->cache_lifetime = empty($CFG['cache_time']) ? $CFG['cache_time'] : 3600;    //前缓存设置 
$smarty->template_dir = ROOT_PATH . 'themes/' . $CFG['template'];                //前台模板更改
$smarty->cache_dir = ROOT_PATH . 'temp/caches/';
$smarty->compile_dir = ROOT_PATH . 'temp/compiled/';                            //前台设置

/*引入公用依赖性库函数文件*/
require(ROOT_PATH . 'includes/lib.main.php');

/*赋值站点配置信息*/
$smarty->assign("ROOT_PATH", ROOT_PATH);
/*赋值站点配置信息*/
$smarty->assign("CFG", $CFG);

/*过滤非法参数*/
if ($_GET) {
    foreach ($_GET as $k => $v) {
        $array = preg_match('/( select| insert| update| delete| union| into| load_file| outfile| or|\.\.\/|\.\/|\'|"|<|>|!|\[|\]|@|{|}|\*|\^|\(|\)|,|-|%|\$|\.|\/|\?|\|)/', $_GET[$k], $array);
        if ($array) {
            //showMsg('系统参数错误！',1);exit;
        }
    }
}