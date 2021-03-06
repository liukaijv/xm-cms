<?php
require 'Captcha.class.php';

Captcha::$expire    = 3000;     // 验证码过期时间（s）
Captcha::$useZh     = false;    // 使用中文验证码 
Captcha::$useImgBg  = false;    // 使用背景图片 
Captcha::$fontSize  = 16;     	// 验证码字体大小(px)
Captcha::$useCurve  = false;   	// 是否画混淆曲线
Captcha::$useNoise  = false;   	// 是否添加杂点	
Captcha::$imageH    = 24;      	// 验证码图片高
Captcha::$imageL    = 85;      // 验证码图片宽
Captcha::$length    = 4;        // 验证码位数
Captcha::$bg		= array(255,255,255);   //背景色

Captcha::entry();  				// 输出图片
?>