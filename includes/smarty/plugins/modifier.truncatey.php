<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty truncate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     truncate<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *           optionally splitting in the middle of a word, and
 *           appending the $etc string or inserting $etc into the middle.
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php
 *          truncate (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param integer
 * @param string
 * @param boolean
 * @param boolean
 * @return string
 */
function smarty_modifier_truncatey($string, $length = 80, $etc = '...', $count_words = true){ 

	mb_internal_encoding ( "UTF-8" ); 

	//去除HTML标签,空格  
	$string = strip_tags(trim(html_entity_decode($string)));         

	if ($length == 0) return '';  

		preg_match_all ( "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $info );
	
		if ($count_words) {  $k = 0;  for($i = 0; $i < count ( $info [0] ); $i ++) {  
	
			$wordscut .= $info [0] [$i];     
			
			$k ++;      
			
			if ($k >= $length) {     
			
				return $wordscut . $etc;     
			
			}       
	   }        
	   return join ( '', $info [0] );    
   }    
   return join ( "", array_slice ( $info [0], 0, $length ) ) . $etc; 
}
/* vim: set expandtab: */

?>
