<?
function create_name($name='') {  
  $base32 = array ('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',  'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',  'q', 'r', 's', 't', 'u', 'v', 'w', 'x',  'y', 'z', '0', '1', '2', '3', '4', '5' );  
  $hex = md5($name);  
  $hexLen = strlen($hex);  
  $subHexLen = $hexLen / 8;  
  $output = '';  
   
  for ($i = 0; $i < $subHexLen; $i++) {  
    $subHex = substr ($hex, $i * 8, 8);  
    $int = 0x3FFFFFFF & (1 * ('0x'.$subHex));  
    $out = '';  
   
    for ($j = 0; $j < 6; $j++) {  
      $val = 0x0000001F & $int;  
      $out .= $base32[$val];  
      $int = $int >> 5;  
    }  
    $output .= $out;  
  }  
  return $output;  
} 

function  create_dir($name=''){
	if (!file_exists($name)){
		mkdir ($name);
		return 'success';
	} else {
		return 'exist';
	}
}

function Location($controller='',$action = ''){
	header("Location:?controller=".$controller."&action=".$action);  
	exit; 
}


function tip($msg='',$url=''){
	echo '<script>';
	echo 'alert("'.$msg.'");';
	if($url==''){
		//echo 'window.history.go(-1)';
		echo '</script>';
	}else{
		header($url);
		exit;   
	}
	exit; 
}

function makeFile($path=''){
	if($path['']){
	
	}
	
}

function display($type='',$title=''){
	if($type=='header'){
		echo (
		'<!doctype html>
		<html>
		<head>
		<meta charset="utf-8">
		<title>'.$title.' - FDOC文档管理系统</title>
		<style>
		html,body,div,ul,li{ margin:0; padding:0}
		ul,li{ list-style:none}
		.admin-header{ width:100%; height:40px; line-height:40px; background:#333}
		.admin-header ul{ float:right}
		.admin-header ul a { margin-right:10px;font-size:14px; color:#fff; text-decoration:none}
		
		.admin-list{ margin:20px;}
		.admin-list li{ width:}
		form { margin:20px}
		form li{ margin:10px 0}
		form li{ margin:10px 0}
		</style>
		</head>
		<body>'
		);
	}
	if($type=='footer'){
		echo '</body></html>';
	}
	
	if($type=='adminTopbar'){
		echo (
		'<div class="admin-header">
          <ul>
            <a href="index.php?controller=admin&action=list">文档列表</a>
            <a href="index.php?controller=admin&action=new">新建文档</a>
            <a href="index.php?controller=admin&action=loginout">退出</a>
		 </ul>
         </div>');
	}
	
}

function file_list($path)
{
    $result = array();
    if (is_dir($path) && $handle = opendir($path)) {
        while (FALSE !== ($file = readdir($handle))) {
            if ($file == '.' || $file == '..') continue 1;
            $real_path = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $file); //realpath($path.DIRECTORY_SEPARATOR.$file);//得到当前文件的全称路径
            $result[] = $real_path;
            if (is_dir($real_path)) ;
                $result = array_merge($result, file_list($real_path));
 
             
        }
        closedir($handle);  
    }
    return $result;
}

function isLogin(){
	session_start();
	if(isset($_SESSION['admin']) && $_SESSION['admin']==password){  
		return true;
	}else{
		return false;
	}	
}

function  checkLogin(){
	if(!isLogin())exit;
}




function getInfo($path=''){
	if(file_exists($path)){
		$myfile = json_decode(file_get_contents($path.'/info.json'));
		return $myfile;
	}else{
		return false;
	}
	
	
}

?>