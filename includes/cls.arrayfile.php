<?php
/*
* 数组文件操作类
* POWER BY ZB
*/

class Arrayfile{
	var $arrays;
	var $filepath;
	
	function __construct($array,$filepath){
		$this->arrays=$array;
		$this->filepath=$filepath;
		$this->setAll();
	}
	
	public static function getDefault(){
		return array(
			/*基本设置*/
			'site_title'=>'',
			'site_keyword'=>'',
			'site_description'=>'',
			'site_logo'=>'',
			'site_domain'=>'',
			'site_gg'=>'',
			'site_record'=>'',
			'site_copyright'=>'',
			'site_company_address'=>'',
			'site_tel'=>'',
			'site_qq'=>'',
			'site_ts_tel'=>'',
			'site_fax'=>'',
			'site_email'=>'',
			'site_total'=>'',
			/*邮件设置*/
			'email_method'=>'1',
			'smtp_service'=>'',
			'smtp_port'=>'25',
			'send_user_email'=>'',
			'auth_username'=>'',
			'auth_password'=>'',
			'test_email'=>'',
			/*上传设置*/
			'is_thumb'=>'1',
			'thumb_way'=>'proportion',
			'thumb_width'=>'120',
			'thumb_height'=>'150',
			'img_maxsize'=>'300',
			'video_maxsize'=>'10',
			'rar_maxsize'=>'10',
			/*验证码设置*/
			'captcha'=>array('a'=>0,'b'=>0,'c'=>0,'d'=>0,'e'=>0),
			/*伪静态设置*/
			'html'=>'0',
			/*其他设置*/
			'lang'=>'zh_cn',
			'caching'=>'1',
			'cache_time'=>'3600',
			'template'=>'',  		//前台模板
			//授权设置
			'author_name'=>'',
			'author_pwd'=>'',
			/*客服设置*/
			'yewu_qq'=>'',
			'shouhou_qq'=>'',
			'jishu_qq'=>'',
			/*创始人照片*/
			'csr_name'=>'',
			'csr_pics'=>'',
			
		);
	}
	
	public function setAll(){
		if ($this->arrays===false){
			return false;
		}
		$old_data = $this->_loadfromfile($this->filepath);
		if(is_array($this->arrays)){
			foreach ($this->arrays as $key => $value){
				isset($value) && $old_data[$key] = $value;
			}
		}
		
		return $this->_savetofile($old_data);
	}
	
	public static function _loadfromfile($filepath){
		return file_exists($filepath) ? include($filepath) : array();
	}
	
	public function _savetofile($data){
		return file_put_contents($this->filepath, "<?php \nreturn " . var_export($data , true) . ";\n?>");
	}
}

?>
