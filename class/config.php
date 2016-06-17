<?
header("Content-type: text/html; charset=utf-8");
define('database',ROOT.'/datas/');
define('controller',(!empty($_GET['controller']))?$_GET['controller']:'index');
define('action',(!empty($_GET['action']))?$_GET['action']:'index');
define('id',(!empty($_GET['id']))?$_GET['id']:'index');
define('password','admin');

?>