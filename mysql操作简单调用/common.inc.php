<?php
//header("Content-type: text/html;charset=utf-8");
error_reporting(E_ERROR | E_PARSE);
set_magic_quotes_runtime(0);
ob_start();
@set_time_limit(0);
PHP_VERSION >= '5.1' && date_default_timezone_set('Asia/Shanghai');
session_cache_limiter('private, must-revalidate'); 
@ini_set('session.auto_start',0); //�Զ������ر�
if(PHP_VERSION < '4.1.0') {
	$_GET = &$HTTP_GET_VARS;
	$_POST = &$HTTP_POST_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_ENV = &$HTTP_ENV_VARS;
	$_FILES = &$HTTP_POST_FILES;
}

if(!get_magic_quotes_gpc()){
	$_POST=Add_S($_POST);
	$_GET=Add_S($_GET);
	$_COOKIE=Add_S($_COOKIE);
}
function Add_S($array){
	foreach($array as $key=>$value){
		if(!is_array($value)){
			$array[$key]=addslashes($value);
		}else{
			$array[$key]=Add_S($array[$key]);
		}
	}
	return $array;
}
if(!ini_get('register_globals')){
	@extract($_COOKIE,EXTR_SKIP);
	@extract($_FILES,EXTR_SKIP);
}
foreach($_POST as $_key=>$_value){
	!ereg("^\_[A-Z]+",$_key) && $$_key=$_POST[$_key];
}
foreach($_GET as $_key=>$_value){
	!ereg("^\_[A-Z]+",$_key) && $$_key=$_GET[$_key];
}
isset($_REQUEST['GLOBALS']) && exit('Access Error');

$timestamp=time()+($webdb['time']*60);
$PHP_SELF_TEMP=$_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
define('HX_PHP_PATH', $_SERVER["DOCUMENT_ROOT"].'/');//��ǰ��Ŀ¼����·��
define('HX_PHP_DIRPATH', str_replace('\\','/',THIS_DIR));//��ǰ����Ŀ¼����·��
define('HX_PHP_FILENAME', str_replace(HX_PHP_DIRPATH."/","",str_replace('\\','/',$_SERVER["SCRIPT_FILENAME"])));//��ǰ�ļ���

define('HX_PHP_DIRNAME', str_replace(HX_PHP_PATH,"",HX_PHP_DIRPATH));//��ǰ����Ŀ¼
define('HX_PHP_DIRFILEPATH', $_SERVER["SCRIPT_FILENAME"]);//��ǰ�ļ�����·��

$_SERVER['QUERY_STRING'] && $PHP_SELF_TEMP .= "?".$_SERVER['QUERY_STRING'];
$PHP_SELF=$_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:$PHP_SELF_TEMP;
$HTTP_HOST=$_SERVER['HTTP_HOST']?$_SERVER['HTTP_HOST']:$HTTP_SERVER_VARS['HTTP_HOST'];
define('HX_PHP_WEBNAME', 'http://'.$HTTP_HOST.'/');//վ������
define('HX_PHP_WEBURL', 'http://'.$HTTP_HOST.$PHP_SELF);//��ǰIE·��我
define('HX_PHP_FROMURL', $_SERVER["HTTP_REFERER"]?$_SERVER["HTTP_REFERER"]:$HTTP_SERVER_VARS["HTTP_REFERER"]);//��һ��IE·��
$FROMURL=$_SERVER["HTTP_REFERER"]?$_SERVER["HTTP_REFERER"]:$HTTP_SERVER_VARS["HTTP_REFERER"];
if($_SERVER['HTTP_X_FORWARDED_FOR']){
	$onlineip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif($_SERVER['HTTP_CLIENT_IP']){
	$onlineip = $_SERVER['HTTP_CLIENT_IP'];
} else{
	$onlineip = $_SERVER['REMOTE_ADDR'];
}
$onlineip  = preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/',$onlineip) ? $onlineip : 'Unknown';
unset($_ENV,$HTTP_ENV_VARS,$_REQUEST,$HTTP_POST_VARS,$HTTP_GET_VARS,$HTTP_POST_FILES,$HTTP_COOKIE_VARS);
require_once('config.php');
require_once("mysql_config.php");
require_once('mysql_class.php');
require_once('function.inc.php');
$db = new MYSQL_DB();
$db->connect($db_Config);
//$aa = $db->all_query("met_admin_table",$sqls);
//$aa = $db->all_query("met_admin_table",$sqls,"id",$tt);
//$aa = $db->all_query("met_admin_table","where id='1'");
//$aa = $db->all_query("met_admin_table","");
//$aa = $db->all_query("met_column,met_product","met_product.class2=met_column.id and met_column.id=92","","","met_column.c_name,met_product.c_title");

?>