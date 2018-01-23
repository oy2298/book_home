<?
/**
*读文件函数
**/
function file_read($filename,$method="rb"){
	if($handle=@fopen($filename,$method)){
		@flock($handle,LOCK_SH);
		$filedata=@fread($handle,@filesize($filename));
		@fclose($handle);
	}
	return $filedata;
}
/**
*写文件函数
**/
function file_write($filename,$data,$method="rb+",$iflock=1){
	@touch($filename);
	$handle=@fopen($filename,$method);
	if($iflock){
		@flock($handle,LOCK_EX);
	}
	@fputs($handle,$data);
	if($method=="rb+") @ftruncate($handle,strlen($data));
	@fclose($handle);
	@chmod($filename,0777);	
	if( is_writable($filename) ){
		return 1;
	}else{
		return 0;
	}
}

/**
*上传文件
**/
function upfile($upfile,$array){
	global $db,$pre,$webdb;
	$filename=$array[name];

	$path=makepath(HX_PHP_PATH.$array[path]);

	if($path=='false')
	{
		showerr("不能创建目录$array[path]，上传失败",1);
	}
	elseif(!is_writable($path))
	{
		showerr("目录不可写$path",1);
	}
	$size=abs($array[size]);

	$filetype=strtolower(strrchr($filename,"."));

	if(!$upfile)
	{
		showerr("文件不存在，上传失败",1);
	}
	elseif(!$filetype)
	{
		showerr("文件不存在，或文件无后缀名,上传失败",1);
	}
	else
	{
		if($filetype=='.php'||$filetype=='.asp'||$filetype=='.aspx'||$filetype=='.jsp'||$filetype=='.cgi'){
			showerr("系统不允许上传可执行文件,上传失败",1);
		}

	}
	$oldname=preg_replace("/(.*)\.([^.]*)/is","\\1",$filename);
	if(eregi("(.jpg|.png|.gif)$",$filetype)){
		$tempname="{$lfjuid}_".date("YmdHms_",time()).rands(5).$filetype;
	}else{
		$tempname="{$lfjuid}_".date("YmdHms_",time()).base64_encode(urlencode($oldname)).$filetype;
	}
	$newfile="$path/$tempname";

	if(@move_uploaded_file($upfile,$newfile))
	{
		@chmod($newfile, 0777);
		$ck=2;
	}
    if(!$ck)
	{
		if(@copy($upfile,$newfile))
		{
			@chmod($newfile, 0777);
			$ck=2;
		}
	}
	if($ck)
	{
		if($array[updateTable])
		{
			//$db->query("UPDATE {$pre}memberdata SET usespace=usespace+'$size' WHERE uid='$lfjuid' ");
		}
		return $tempname;
	}
	else
	{
		showerr("请检查空间问题,上传失败",1);
	}
}
/**
*生成目录
**/
function makepath($path){
	//这个\没考虑
	$detail=explode("/",$path);
	foreach($detail AS $key=>$value){
		if($value==''&&$key!=0){
			//continue;
		}
		$newpath.="$value/";
		if((eregi("^\/",$newpath)||eregi(":",$newpath))&&!strstr($newpath,HX_PHP_PATH)){continue;}
		if( !is_dir($newpath) ){
			if(substr($newpath,-1)=='\\'||substr($newpath,-1)=='/')
			{
				$_newpath=substr($newpath,0,-1);
			}
			else
			{
				$_newpath=$newpath;
			}
			if(!mkdir($_newpath)&&!file_exists($_newpath)){
				return 'false';
			}
			@chmod($newpath,0777);
		}
	}
	return $path;
}
/**
*删除文件,值不为空，则返回不能删除的文件名
**/
function file_del($path){
	if (file_exists($path)){
		if(is_file($path)){
			if(	!@unlink($path)	){
				$show.="$path,";
			}
		} else{
			$handle = opendir($path);
			while (($file = readdir($handle))!='') {
				if (($file!=".") && ($file!="..") && ($file!="")){
					if (is_dir("$path/$file")){
						$show.=del_file("$path/$file");
					} else{
						if( !@unlink("$path/$file") ){
							$show.="$path/$file,";
						}
					}
				}
			}
			closedir($handle);
			if(!@rmdir($path)){
				$show.="$path,";
			}
		}
	}
	return $show;
}

/**
*查询数组所有下级[返回数组]（参数）：数据，返回变量，下级ID，读取的字段
**/
function list_allsort($listdb,$new_db,$fid="0",$imp="",$NID="id",$NFID="fid"){
global ${$new_db};
	foreach($listdb as $key=>$value){
	
	   if($value[$NFID]==$fid){
		   if($imp!=""){
				if(is_array($imp)){
				   foreach($imp as $imkey=>$imvalue){
				   $imarray[] = $value[$imvalue];
				   }
				   ${$new_db}[$value[$NID]]=$imarray;
				   unset($imarray);		   
				 }else{				 
				 ${$new_db}[$value[$NID]]=$value[$imp];
				 } 
		   }else{
		   ${$new_db}[$fid][]=$value;
		   }		
		unset($listdb[$key]);		
		list_allsort($listdb,$new_db,$value[$NID],$imp);
	   }  
	}
}
/**/

function listsort_next($listdb,$listname,$id=0,$Cateimp=''){
global ${$listname};

	foreach($listdb as $key=>$value){
		 if($value[id]==$id){
		 
			 if(is_array($Cateimp)){
			 foreach($Cateimp as $vname){
			  $vnamenew[]=$value[$vname];
			 }
			 ${$listname}[]=$vnamenew;		 
			 }elseif($Cateimp){
			 ${$listname}[]=$value[$Cateimp];
			 }else{
			 ${$listname}[]=$value;
			 }	
		listsort_next($listdb,$listname,$value[fid],$Cateimp);
		 }	    
	}


}

function Blank_In($listdb,$listname,$order="0",$id="0"){
global ${$listname};
	 $order++;
	 foreach($listdb[$id] as $key=>$value){
		if(is_array($listdb[$value[id]]) || $value[fid]==$id){
		$value[order] =	$order;	
		${$listname}[$value[id]] = $value;
		Blank_In($listdb,$listname,$value[order],$value[id]);				
		}
	 }	
}
function BlankIcon($class,$blank='1'){
	if($blank){
		for($i=1;$i<$class;$i++){
			$show.="&nbsp;&nbsp;";
		}
	}
	$detail=array("■■","■","◆","◇","▲","△","★","☆","⊙","※","卐","◎","●","□");
	if($detail[$class]){
		return "{$show}$detail[$class]";
	}
	else{
		return "{$show}【{$class}】";
	}
}

/**
*取得随机字符
**/
function rands($length) {
	$hash = '';
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	$max = strlen($chars) - 1;
	mt_srand((double)microtime() * 1000000);
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}
/**
*模板相关----加载文件与模板判断参数：文件名[]，路径，后缀名
**/
function get_path($url){
	$url = str_replace('\\','/',$url);	
	if(strstr($url,HX_PHP_PATH)){
	return str_replace(HX_PHP_PATH,HX_PHP_WEBNAME,$url);
	}else{
	return str_replace(HX_PHP_WEBNAME,HX_PHP_PATH,$url);
	}
}

function import($filename,$tpl='',$html=''){
	global $language;
	$import_filename = template($filename,$tpl,$html);
	if($import_filename){
	return str_replace(HX_PHP_WEBNAME,HX_PHP_PATH,$import_filename);
	}else{
	refreshto("","Error：当前加载文件[$filename]不存在！",5);
	}
}
function template($filename,$tpl='',$html=''){
	if($tpl&&strstr($tpl,HX_PHP_PATH)&&(is_file($tpl.$filename)||is_file($tpl.$filename."$html")))
	{
		$filename = $tpl.$filename."$html";
	}
	elseif($tpl&&strstr($tpl,HX_PHP_PATH)&&(is_file($tpl)||is_file($tpl."$html")))
	{
		$filename = $tpl."$html";
	}
	elseif($tpl&&(is_file(HX_PHP_PATH.$tpl.$filename)||is_file(HX_PHP_PATH.$tpl.$filename."$html")))
	{
		$filename = HX_PHP_PATH.$tpl.$filename."$html";
	}
	elseif($tpl&&(is_file(HX_PHP_PATH.$tpl)||is_file(HX_PHP_PATH.$tpl."$html")))
	{
		$filename = HX_PHP_PATH.$tpl."$html";
	}
	elseif($tpl&&(is_file($tpl.$filename)||is_file($tpl.$filename."$html")))
	{
		$filename = $tpl.$filename."$html";
	}
	elseif(is_file("template/".$tpl."/".$filename."$html"))
	{
		$filename = "template/".$tpl."/".$filename."$html";
	}
	elseif(is_file("template/default/".$filename."$html"))
	{
		$filename = "template/default/".$filename."$html";
	}else{
	    $filename = "";
	}
	
	$filename = str_replace(HX_PHP_PATH,HX_PHP_WEBNAME,$filename);
	return $filename;	
	
}

/**
*页面跳转函数
**/
function refreshto($url,$msg,$time=0){
	if(!$url){$url = HX_PHP_FROMURL;}
	if($time==0){
		header("location:$url");
	}else{
		if(is_file(HX_PHP_PATH."template/admin/refreshto.html")){
			require(HX_PHP_PATH."template/admin/refreshto.html");
		}else{
			echo $msg;
		}
	}
	exit;
}

/**
*警告页面函数
**/
function showerr($msg,$type=''){
	if($type==1){
		$msg=str_replace("'","\'",$msg);
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		alert('$msg');
		history.back(-1);
		</SCRIPT>";
	}else{
		if(is_file(HX_PHP_PATH."template/admin/refreshto.html")){
		   require(HX_PHP_PATH."template/admin/refreshto.html");
		}else{
			echo $msg;
		}
		
	}
	exit;
}



/**
*简单采集函数
**/
function preg_get_web($url,$topstr,$footstr,$patterns='',$replace=''){
	$webexp=implode("",file($url)); 
	$toop = get_rule($topstr);
	$foot = get_rule($footstr);
	preg_match("/$toop(.*?)$foot/is",$webexp,$tempmatch);
	$str=str_replace($strreparray,$retstrreparray,$tempmatch[0]);	
	if($patterns){
	$str=preg_replace($patterns,$replace,$str);
	}
return $str;
}

function get_tag_data($str,$start,$end,$patterns='',$replace=''){
        if ( $start == '' || $end == '' ){
           return;
        }
        $str = explode($start, $str);
		for($i=1;$i<=count($str);$i++){
		$strarr = $str[$i];
		$strarrs[] = explode($end, $strarr);
		}
		$num=count($str)-1;
		for($i=0;$i<$num;$i++){
		$str=$patterns ? preg_replace($patterns,$replace,$start.$strarrs[$i][0].$end) : $start.$strarrs[$i][0].$end;
		$strarray[] = $str;
		}	
        return $strarray;
}
/**
*正则表达式HTML转换
**/
function get_rule($string){
	$string=str_replace('\\','\\\\',$string);
	$string=str_replace("(","\(",$string);
	$string=str_replace(")","\)",$string);
	$string=str_replace("[","\[",$string);
	$string=str_replace("]","\]",$string);
	$string=str_replace('"','\"',$string);
	$string=str_replace('.','\.',$string);
	$string=str_replace('?','\?',$string);
	$string=str_replace('$','\$',$string);
	$string=str_replace('^','\^',$string);
	$string=str_replace('/','\/',$string);
	$string=str_replace('+','\+',$string);
	return $string;
}
/**
*替换空格回车为空
**/
function Tblanks($string){
	$string=str_replace("&nbsp;","",$string);
	$string=str_replace(" ","",$string);
	$string=str_replace("　","",$string);
	$string=str_replace("\r","",$string);
	$string=str_replace("\n","",$string);
	$string=str_replace("\t","",$string);
  return  $string;
}


function pages($url,$total=0,$psize=30,$page_id=0,$halfPage=5,$is_select=false)
{
	if(empty($psize))
	{
		$psize = 30;
	}
	#[添加链接随机数]
	if(strpos($url,"?") === false)
	{
		$url = $url."?qgrand=efsef";
	}
	#[共有页数]
	$totalPage = intval($total/$psize);
	if($total%$psize)
	{
		$totalPage++;#[判断是否存余，如存，则加一
	}
	#[如果分页总数为1或0时，不显示]
	if($totalPage<2)
	{
		return false;
	}
	#[判断分页ID是否存在]
	if(empty($page_id))
	{
		$page_id = 1;
	}
	#[判断如果分页ID超过总页数时]
	if($page_id > $totalPage)
	{
		$page_id = $totalPage;
	}
	#[Html]
	$array_m = 0;
	if($page_id > 0)
	{
		$returnlist[$array_m]["url"] = $url;
		$returnlist[$array_m]["name"] = "首页";
		$returnlist[$array_m]["status"] = 0;
		if($page_id > 1)
		{
			$array_m++;
			$returnlist[$array_m]["url"] = $url."&page_id=".($page_id-1);
			$returnlist[$array_m]["name"] = "上页";
			$returnlist[$array_m]["status"] = 0;
		}
	}
	if($halfPage>0)
	{
		#[添加中间项]
		for($i=$page_id-$halfPage,$i>0 || $i=0,$j=$page_id+$halfPage,$j<$totalPage || $j=$totalPage;$i<$j;$i++)
		{
			$l = $i + 1;
			$array_m++;
			$returnlist[$array_m]["url"] = $url."&page_id=".$l;
			$returnlist[$array_m]["name"] = $l;
			$returnlist[$array_m]["status"] = ($l == $page_id) ? 1 : 0;
		}
	}
	if($is_select)
	{
		if($halfPage <1)
		{
			$halfPage = 5;
		}
		#[添加select里的中间项]
		for($i=$page_id-$halfPage*3,$i>0 || $i=0,$j=$page_id+$halfPage*3,$j<$totalPage || $j=$totalPage;$i<$j;$i++)
		{
			$l = $i + 1;
			$select_option_msg = "<option value='".$l."'";
			if($l == $page_id)
			{
				$select_option_msg .= " selected";
			}
			$select_option_msg .= ">".$l."</option>";
			$select_option[] = $select_option_msg;
		}
	}
	#[添加尾项]
	if($page_id < $totalPage)
	{
		$array_m++;
		$returnlist[$array_m]["url"] = $url."&page_id=".($page_id+1);
		$returnlist[$array_m]["name"] = "下页";
		$returnlist[$array_m]["status"] = 0;
	}
	$array_m++;
	if($page_id != $totalPage)
	{
		$returnlist[$array_m]["url"] = $url."&page_id=".$totalPage;
		$returnlist[$array_m]["name"] = "尾页";
		$returnlist[$array_m]["status"] = 0;
	}
	#[组织样式]
	$msg = "<table class='pagelist'><tr><td class='n'>".$total."/".$psize."</td>";
	foreach($returnlist AS $key=>$value)
	{
		if($value["status"])
		{
			$msg .= "<td class='m'>".$value["name"]."</td>";
		}
		else
		{
			$msg .= "<td class='n'><a href='".$value["url"]."'>".$value["name"]."</a></td>";
		}
	}
	if($is_select)
	{
		$msg .= "<td><select onchange=\"location.href('".$url."&page_id='+this.value)\">".implode("",$select_option)."</option></select></td>";
	}
	$msg .= "</tr></table>";
	unset($returnlist);
	return $msg;
}

/**
*时间格式还原
**/
function dateTOtime($times){
return preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","@mktime('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$times);
}
/**
*时间相减生成数组：d代表天，h代表小时，n代表分
**/
function date_date($time2,$time1,$datearray=array()){
if($time2<$time1){
return 0;
}
$time2 = date("Y-m-d H:i:s",$time2);
$time1 = date("Y-m-d H:i:s",$time1);
$hh=(strtotime($time2)-strtotime($time1))/60/60;
$datearray[h]=floor($hh)%24;
$dd= $hh/24;
$datearray[d]=floor($dd);
$nn=(strtotime($time2)-strtotime($time1))/60;
$datearray[n]=$nn%60;
return $datearray;
}

/**
*最后加工处理参数：需要调用的函数[可为数组](为数组时键值为函数名，值为参数)
**/
function foot_final($fun=''){
	$content=ob_get_contents();
	ob_end_clean();
	ob_start();
	$content=preg_replace("/<!-(.+?)->/is","",$content);
	if($fun){
		if(!is_array($fun)){
		$forfun[$fun]=$content;
		}else{
		$forfun = $fun;
		}
		foreach($forfun as $f_name=>$Parameters){
			if(function_exists($f_name)){
			if(!$Parameters)$Parameters=$content;
			$content = call_user_func($f_name,$Parameters);
			}
		}
	}	
	echo "$content";
}

/**
*服务器信息
**/
function systemMsg(){
	global $db,$siteurl,$onlineip,$SCRIPT_FILENAME,$WEBURL;
	if(mysql_get_server_info()<'4.1'){
		$rs[mysqlVersion]=mysql_get_server_info()."(低版本);";
	}else{
		$rs[mysqlVersion]=mysql_get_server_info()."(高版本);";
	}
	isset($_COOKIE) ? $rs[ifcookie]="SUCCESS" : $rs[ifcookie]="FAIL";
	$rs[sysversion]=PHP_VERSION;	//PHP版本
	$rs[max_upload]= ini_get('upload_max_filesize') ? ini_get('upload_max_filesize') : 'Disabled';	//最大上传限制
	$rs[max_ex_time]=ini_get('max_execution_time').' 秒';	//最大执行时间
	$rs[sys_mail]= ini_get('sendmail_path') ? 'Unix Sendmail ( Path: '.ini_get('sendmail_path').')' :( ini_get('SMTP') ? 'SMTP ( Server: '.ini_get('SMTP').')': 'Disabled' );	//邮件支持模式
	$rs[systemtime]=date("Y-m-j g:i A");	//服务器所在时间
	$rs[onlineip]=$onlineip;				//当前IP
	if( function_exists("imagealphablending") && function_exists("imagecreatefromjpeg") && function_exists("ImageJpeg") ){
		$rs[gdpic]="支持";
	}else{
		$rs[gdpic]="不支持";
	}
	$rs[allow_url_fopen]=ini_get('allow_url_fopen')?"On 支持采集数据":"OFF 不支持采集数据";
	$rs[safe_mode]=ini_get('safe_mode')?"打开":"关闭";
	$rs[DOCUMENT_ROOT]=$_SERVER["DOCUMENT_ROOT"];	//程序所在磁盘物理位置
	$rs[SERVER_ADDR]=$_SERVER["SERVER_ADDR"]?$_SERVER["SERVER_ADDR"]:$_SERVER["LOCAL_ADDR"];		//服务器IP
	$rs[SERVER_PORT]=$_SERVER["SERVER_PORT"];		//服务器端口
	$rs[SERVER_SOFTWARE]=$_SERVER["SERVER_SOFTWARE"];	//服务器软件
	$rs[SCRIPT_FILENAME]=$_SERVER["SCRIPT_FILENAME"];	//当前文件路径
	$rs[SERVER_NAME]=$_SERVER["SERVER_NAME"];	//域名

	//获取ZEND的版本
	ob_end_clean();
	ob_start();
	phpinfo();
	$phpinfo=ob_get_contents();
	ob_end_clean();
	ob_start();
	preg_match("/with(&nbsp;| )Zend(&nbsp;| )Optimizer(&nbsp;| )([^,]+),/is",$phpinfo,$zenddb);
	$rs[zendVersion]=$zenddb[4]?$zenddb[4]:"未知/可能没安装";
	
	return $rs;
}

?>