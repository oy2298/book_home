<?php
class MYSQL_DB{
	var $IsConnet = 1;		//数据库是否已链接
	var $db_Config=''; 
	     //数据库配置，以数组(dbhost主机，dbuser用户名，dbpw数据库密码，dbname数据库名,dbcharset编码,pconnect为真永久连接)
	function connect($db_Config='') {
	if($db_Config)$this->db_Config=$db_Config;
	$db_Config=$this->db_Config;
		if($db_Config[pconnect]) {
			if(!@mysql_pconnect($db_Config[dbhost],$db_Config[dbuser],$db_Config[dbpw])) {
				$this->Err('MYSQL 不能永久连接数据库,请确定数据库用户名,密码设置正确,并且服务器支持永久连接<br>');
				exit;
			}
		} else {
			if(!@mysql_connect($db_Config[dbhost],$db_Config[dbuser],$db_Config[dbpw])) {
				$this->Err('MYSQL 连接数据库失败,请确定数据库用户名,密码设置正确<br>');
				exit;
			}
		}
		if(!@mysql_select_db($db_Config[dbname])){
			$this->Err("MYSQL 连接成功,但当前使用的数据库 {$dbname} 不存在<br>");
			exit;
		}
		if($db_Config[dbcharset]){
			mysql_query("SET NAMES '$db_Config[dbcharset]'");
		}
		if( mysql_get_server_info() > '5.0' ){
			mysql_query("SET sql_mode=''");
		}
		$this->IsConnet=1;
		
	}

	function close() {
		$this->IsConnet=0;
		return mysql_close();
	}

	function query($SQL,$method='',$showerr='1'){
		if($this->IsConnet==0){
			$this->connect();
		}
		//分析统计查询时间
		$speed_headtime=explode(' ',microtime());
		$speed_headtime=$speed_headtime[0]+$speed_headtime[1];

		if($method=='U_B' && function_exists('mysql_unbuffered_query')){
			$query = mysql_unbuffered_query($SQL);
		}else{
			$query = mysql_query($SQL);
		}
		if (!$query&&$showerr=='1')  $this->Err("数据库连接出错:$SQL<br>");
		return $query;
	}


	function get_one($SQL){
		$query=$this->query($SQL,'U_B');		
		$rs =& mysql_fetch_array($query, MYSQL_ASSOC);
		return $rs;
	}

	function update($SQL) {
		if($this->IsConnet==0){
			$this->connect();
		}

		if(function_exists('mysql_unbuffered_query')){
			$query = mysql_unbuffered_query($SQL);
		}else{
			$query = mysql_query($SQL);
		}
		if (!$query)  $this->Err("数据库连接出错:$SQL<br>");
		return $query;
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $result_type);
	}

	function num_rows($query) {
		$rows = mysql_num_rows($query);
		return $rows;
	}

	function free_result($query) {
		return mysql_free_result($query);
	}

	function insert_id() {
		$id = mysql_insert_id();
		return $id;
	}

	//数据表与字段数组$get_db_alltab
	function get_db_alltab(){
	   $db_tab  = $this->get_table_names(); 
	   $table_num = count($db_tab);
	   for ($i=1;$i<$table_num;$i++){
	   $db_tabF[$db_tab[$i]]= $this->get_result_fields($db_tab[$i],$i);
		}			
			return $db_tabF;
	   }
	   
	//列出 MySQL 数据库中的表
    function get_table_names(){
		if($this->IsConnet==0){
			$this->connect();
		}
		$result = mysql_list_tables($this->db_Config[dbname]);
		$num_tables = @mysql_numrows($result);
		for ($i = 1; $i <= $num_tables; $i++) {
			$tables[$i] = mysql_tablename($result, $i-1);
		}
		mysql_free_result($result);
		return  $tables ;
	}
	//从结果集中取得列信息并作为对象返回，取得所有字段名组成数组
    function get_result_fields($tables='',$num="0"){
		if($this->IsConnet==0){
			$this->connect();
		}		
	     $res = mysql_query('select * from '.$tables.'');
		 $res_num=mysql_num_fields($res);
		 $filed[0] = $tables;
		for ($i = 0; $i < $res_num ; $i++){
		 $filed[$i] = mysql_field_name($res, $i);
		 }
          return $filed;
   	}	   
	
	
	function all_query($tablename,$tab_value_where,$idfrom='*',$idsqllimit=''){//通用修改添加删除查询
	if($idfrom=='')$idfrom='*';
	if(is_array($tablename)){
		foreach($tablename AS $key=>$value){
			if(!$value)die('表名不能为空!');	
			$tablearray[] = $value;
		}
		$tablename = implode(",",$tablearray);
	}else{
		if(!$tablename)die('表名不能为空!');
	}
	////////////////////////////////////	
		if(is_array($tab_value_where)){
			$get_tabledb = $this->get_result_fields($tablename);	
			foreach($tab_value_where AS $key=>$value){
				if(in_array($key,$get_tabledb)){
				$tab[] = "`$key`";
				$val[] = "'$value'";				
				$update[] = "`$key`='$value'";
				}
			}
				$sqltab = implode(",",$tab);
				$sqlval = implode(",",$val);
				$updateval = implode(",",$update);	
				if(!$idsqllimit){//添加	
				$sql = "INSERT INTO ".$tablename." ($sqltab) VALUES ($sqlval)";
				}else{//修改
				if(is_array($idsqllimit))$idsqllimit=implode(",",$idsqllimit);
				$sql = "UPDATE ".$tablename." SET $updateval where $idfrom in ($idsqllimit)";						
				}			
				$this->query($sql);			
				if(!$idsqllimit)return $this->insert_id();
				return $idsqllimit;				
		}else{
				if(!is_array($idsqllimit)){//查询	
				if($idsqllimit!='')if(!strstr($idsqllimit,"LIMIT"))$idsqllimit=" LIMIT $idsqllimit";
				if($tab_value_where!='')if(!strstr($tab_value_where,"where"))$tab_value_where=" where $tab_value_where";
				
				$result = $this->query("SELECT $idfrom FROM ".$tablename." $tab_value_where $idsqllimit");
					while($rs= $this->fetch_array($result)){
					$list[]=$rs;
					}
				//if(count($list)==1)return $list[0];
				return $list;							
				}else{//删除
				$idsqllimit= !$tab_value_where ? " where $idfrom in (".implode(",",$idsqllimit).")" :$idsqllimit=" and $idfrom in (".implode(",",$idsqllimit).")";				
				return $this->query("DELETE FROM ".$tablename." $tab_value_where $idsqllimit");				
				}
		}	
	}
	
	function Err($msg='') {
		$sqlerror = mysql_error();
		$sqlerrno = mysql_errno();
		if(strstr($sqlerror,"Can't open file: '")){
			preg_match("/Can't open file: '([^']+)\.MYI'/is",$sqlerror,$array);
			echo "系统已自动修复数据库,请再次刷新网页,如果修复不成功,请重启数据库再修复<br>";
			$this->query("REPAIR TABLE `$array[1]`");
		}
		if(strstr($sqlerror,"should be repaired")){
			$sqlerror=str_replace("\\","/",$sqlerror);
			preg_match("/([^\/]+)' is marked as/is",$sqlerror,$array);
			echo "系统已自动修复数据库,请再次刷新网页,如果修复不成功,请重启数据库再修复<br>";
			$this->query("REPAIR TABLE `$array[1]`");
		}
		echo "$msg<br>$sqlerror<br>$sqlerrno";
	}
}

	//@extract($this->get_one("SELECT count(*) as num FROM `$tablename` WHERE $tabid='$queryid'"));
?>