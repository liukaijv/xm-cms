<?php
class Mysql{
	private $host;  
	private $user;     
	private $pswd;       
	private $dbname;
	private $prefix;
	private $link_id;
	private $table;
	private $starttime;

	function __construct($Console,$DataUser,$Password,$Database,$Prefix){
		$this->host=$Console;
		$this->user=$DataUser;
		$this->pswd=$Password;
		$this->dbname=$Database;
		$this->prefix=$Prefix;
		$this->starttime=time();
		$this->doDB();
	}
	
	function doDB(){
		$this->link_id = mysqli_connect($this->host,$this->user,$this->pswd,$this->dbname);
		//mysqli_select_db($this->link_id,$this->dbname);
		//mysqli_ping($this->link_id);
		mysqli_query($this->link_id,"set NAMES 'utf8'");
		return $this->link_id;
	}
	
	function getOne($sql){
		$rs=self::query($sql.' limit 1');
		
		if($rs){
			$arr=@mysqli_fetch_array($rs);
			return $arr;
		}else{
			return "";
		}
	}
	
	function getAll($sql){
		$rs=self::query($sql);
		if($rs){
			$arr=array();
			while($array=@mysqli_fetch_array($rs)){
				$arr[]=$array;
			}
			return $arr;
		}else{
			return "";
		}
	}
	
	function getNum($sql){
	    $rs=self::query($sql);
		if($rs){
		    $num=@mysqli_num_rows($rs);
			return $num;
		}else{
		    return 0;
		}
	}
	
	function get_Affected(){
		$num=@mysqli_affected_rows($this->link_id);
		return $num;
	}
	
	function get_insert_id(){
		return mysqli_insert_id($this->link_id);
	}
	
	function get_mysql_version(){
		return mysqli_get_server_version($this->link_id);
	}
	
	function get_mysql_server(){
		return mysqli_get_server_info($this->link_id);
	}
	
	/*设置表*/
	function setTable($tab){
		$this->table=$this->prefix.$tab;
	}
	
	/*获取所有*/
	function fetchAll($tab,$condition=1,$order=''){
		if(!empty($tab)){
			$this->setTable($tab);
		}else{
			die("error");	
		}
		if($condition==1||empty($condition)){
			$condition='';
		}else{
			$condition="and $condition";
		}
		if(empty($order)){
			$order="";
		}else{
			$order="order by ".$order;
		}
		$sql="select * from ".$this->table." where 1=1  ".$condition." ".$order;
		//print_r($sql);exit;
		return $this->getAll($sql);
	}
	
	/*插入*/
	function insert($data=array(),$tab){
		if(!empty($tab)){
			$this->setTable($tab);
		}else{
			die("error");	
		}
		if(count($data)>0){
			$ks=array_keys($data);
			$vs=array_values($data);
			if(count($ks)==1){
				$fields='`'.$ks.'`';
			}else{
				$fields='`'.implode($ks,'`,`').'`';
			}
			foreach($vs as $v){
				if((empty($v)||$v=='')&&($v>0||$v<0)){
					$value[]='';
				}elseif(is_int($v)||is_float($v)||is_numeric($v)){
					$value[]="".$v."";
				}else{
					$value[]="'".$v."'";
				}
			}
			$values=@implode($value,',');
		}
		$sql="insert into ".$this->table." (".$fields.") values (".$values.")";
//		exit($sql);
		$this->query($sql);
		return $this->get_insert_id($this->link_id);
	}
	
	/*更新*/
	function update($data,$condition,$tab){
		if(!empty($tab)){
			$this->setTable($tab);
		}else{
			die("error");	
		}
		if($condition!='' and !empty($condition) and count($data)>0){
			$ks=array_keys($data);
			$vs=array_values($data);
			if(count($data)==1){
				$k_v="`".$ks[0]."`='".$vs[0]."'";
			}else{
				foreach($data as $k=>$v){
					if((empty($v)||$v=='')&&($v>0||$v<0)){
						$kv[]="`".$k."`=".''."";
					}elseif(is_int($v)||is_float($v)||is_numeric($v)){
						$kv[]="`".$k."`=".$v."";
					}else{
						$kv[]="`".$k."`='".$v."'";
					}
				}
				$k_v=implode($kv,',');
			}
			$sql="update ".$this->table." set ".$k_v." where ".$condition."";
			//echo $sql;exit;
			$this->query($sql);
			return $this->get_affected();
		}else{
			return 0;	
		}
	}
	
	/*删除*/
	function delete($condition,$tab){
		if(!empty($tab)){
			$this->setTable($tab);
		}else{
			die("error");	
		}
		if($condition!='' and !empty($condition)){
			$sql="delete from ".$this->table." where ".$condition."";
			$this->query($sql);
			return $this->get_affected();
		}else{
			return 0;
		}
	}
	
	/*获取*/
	function get_info($condition,$tab){
		if(!empty($tab)){
			$this->setTable($tab);
		}else{
			die("error");	
		}
		if($condition){
			$sql="select * from ".$this->table." where ".$condition."";				
			return $this->getOne($sql);
		}else{
			return 0;	
		}
	}
	
	function query($sql){
		//当当前时间大于类初始化时间时执行ping这个动作，防止出现未连接或连接超时现象出现
		/*if (PHP_VERSION >= '4.3' && time() > $this->starttime + 1){
            mysql_ping($this->link_id);
        }*/
	    $rs=@mysqli_query($this->link_id,$sql);
		return $rs;
	}
	
	function error(){
        return mysql_error($this->link_id);
    }

    function errno(){
        return mysql_errno($this->link_id);
    }
	
	function close(){
		mysqli_close();
	}
}
?>