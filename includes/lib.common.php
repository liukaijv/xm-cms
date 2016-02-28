<?php
/*-------------------------------------------------
	Content:一些公共函数库，不依赖于数据库及smarty	
-------------------------------------------------*/

/**
 * 递归方式的对变量中的特殊字符进行转义
 *
 * @access  public
 * @param   mix     $value
 *
 * @return  mix
 */
function addslashes_deep($value){
    if (empty($value)){
        return $value;
    }else{
        return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    }
}

/*自定义跳转,消除安全隐患*/
function my_header($string, $replace = true, $http_response_code = 0){
    $string = str_replace(array("\r", "\n"), array('', ''), $string);

    if (preg_match('/^\s*location:/is', $string)){
        @header($string . "\n", $replace);

        exit();
    }

    if (empty($http_response_code) || PHP_VERSION < '4.3'){
        @header($string, $replace);
    }
    else{
        @header($string, $replace, $http_response_code);
    }
	exit;
}

/*获取真实IP*/
function get_real_ip()
{
    static $realip = NULL;

    if ($realip !== NULL)
    {
        return $realip;
    }

    if (isset($_SERVER))
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach ($arr AS $ip)
            {
                $ip = trim($ip);

                if ($ip != 'unknown')
                {
                    $realip = $ip;

                    break;
                }
            }
        }
        elseif (isset($_SERVER['HTTP_CLIENT_IP']))
        {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else
        {
            if (isset($_SERVER['REMOTE_ADDR']))
            {
                $realip = $_SERVER['REMOTE_ADDR'];
            }
            else
            {
                $realip = '0.0.0.0';
            }
        }
    }
    else
    {
        if (getenv('HTTP_X_FORWARDED_FOR'))
        {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        }
        elseif (getenv('HTTP_CLIENT_IP'))
        {
            $realip = getenv('HTTP_CLIENT_IP');
        }
        else
        {
            $realip = getenv('REMOTE_ADDR');
        }
    }

    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

    return $realip;
}

function get_week(){
	switch(date("w",time())){
		case 0: $week="星期天";break;
		case 1: $week="星期一";break;
		case 2: $week="星期二";break;
		case 3: $week="星期三";break;
		case 4: $week="星期四";break;
		case 5: $week="星期五";break;
		case 6: $week="星期六";break;
	}
	return $week;
}

function filter_str($str){
	if($str){
		$order   = array("\r\n", "\n", "\r" ,"\t");
		$replace = "";
		$str=strip_tags($str,"");
		$str=str_replace("\t","",$str);
		$str=str_replace("\r\n","",$str);
		$str=str_replace("\r","",$str);
		$str=str_replace("\n","",$str);
		$str=str_replace(" ","",$str);
	}
	return $str;
}

//过滤空数组元素回调函数
function array_none($var){
	if(!empty($var)){
		return true;
	}
}

/**
 * 验证输入的邮件地址是否合法
 *
 * @access  public
 * @param   string      $email      需要验证的邮件地址
 *
 * @return bool
 */
function is_email($user_email)
{
    $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
    if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false)
    {
        if (preg_match($chars, $user_email))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

/**
 * 检查是否为一个合法的时间格式
 *
 * @access  public
 * @param   string  $time
 * @return  void
 */
function is_time($time)
{
    $pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}\s[\d]{1,2}:[\d]{1,2}:[\d]{1,2}/';

    return preg_match($pattern, $time);
}

/**
 * 创建像这样的查询: "IN('a','b')";
 *
 * @access   public
 * @param    mix      $item_list      列表数组或字符串
 * @param    string   $field_name     字段名称
 *
 * @return   void
 */
function db_create_in($item_list, $field_name = '')
{
    if (empty($item_list))
    {
        return $field_name . " IN ('') ";
    }
    else
    {
        if (!is_array($item_list))
        {
            $item_list = explode(',', $item_list);
        }
        $item_list = array_unique($item_list);
        $item_list_tmp = '';
        foreach ($item_list AS $item)
        {
            if ($item !== '')
            {
                $item_list_tmp .= $item_list_tmp ? ",'$item'" : "'$item'";
            }
        }
        if (empty($item_list_tmp))
        {
            return $field_name . " IN ('') ";
        }
        else
        {
            return $field_name . ' IN (' . $item_list_tmp . ') ';
        }
    }
}

?>
