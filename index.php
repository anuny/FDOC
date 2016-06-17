<?
define('ROOT',str_replace('','/',dirname(__FILE__)));//定义系统目录
require('class/config.php');
require('class/function.php');

if(controller=='admin'){
	// 判断登录
	if(!isLogin()){
		display('header','登录');
		echo(
		'<form name="LoginForm" method="post" action="index.php?controller=admin&action=login">
		  <input id="password" name="password" type="password" class="input" placeholder="登录密码"/>
		  <input type="submit" name="submit" value="  确 定  " class="left" />
		</form>');
		if(isset($_POST["password"]) && $_POST["password"] == password){
			session_start();  
			$_SESSION['admin'] = password; 
			Location(controller,'list') ;
		};
		display('footer');
		exit;
	}
	
	// 退出
	if(action == 'loginout'){
		session_start();
		unset($_SESSION['admin']);
		Location(controller,'login') ;
	// 列表
	}elseif(action == 'list'){
		display('header','文档列表');
		display('adminTopbar');
		echo '<div class="admin-list">';
		$filesnames = file_list(database);
		foreach ($filesnames as $file) {
			if(is_dir(database.$file)){
				if (file_exists(database.$file.'/info.json')){
					$myfile = json_decode(file_get_contents(database.$file.'/info.json'));
					echo '<li><a href="?controller=admin&action=edit&id='.$file.'">'.$myfile->name.'</a></li>';
				}else{
					 echo '没有项目文档,<a href="index.php?controller=admin&action=new">新建?</a>';
				}
				
			}
		}
		echo '</div>';
		display('footer');
	// 新项目
	}elseif(action == 'new'){
		display('header','新建项目');
		display('adminTopbar');
		echo 
		'<div class="admin-new">
		  新建项目
		  <form method="post" action="index.php?controller=admin&action=add">
			<li><input name="casename" type="text" class="input" placeholder="项目名称" /></li>
			<li><input name="aliasname" type="text" class="input" placeholder="项目别名" /></li>
			<li><input name="order" type="text" class="input" placeholder="项目排序"/></li>
			<li><textarea name="desc" placeholder="项目描述"></textarea></li>
			<input name="type" type="hidden" value="newcase"/>
			<li><input type="submit" name="submit" value="确 定" />
			<input type="button" onClick="window.history.go(-1);" value="取消" ></li>
		  </form>
		</div>';
		display('footer');
	// 添加文档	
	}elseif(action == 'add'){
		// 创建新项目
		if(isset($_POST["type"])){
			$type = $_POST["type"];
			if($type =='newcase'){
				$casename = $_POST["casename"];
				$caseuuid = create_name($casename);
				$aliasname = (!empty($_POST['aliasname']))?$_POST['aliasname']:$caseuuid;
				$order=(!empty($_POST['order']))?$_POST['order']:null;
				$desc=(!empty($_POST['desc']))?$_POST['desc']:null;
				if (file_exists(database.$aliasname)){
					$date=date('YmdHis');
					$aliasname = $aliasname.'=='.$date;
				}
				mkdir(database.$aliasname);
				$filename = database.$aliasname.'/info.json ';
				$caseInfo=array('name'=>$casename,'desc'=>$desc,'order'=>$order,'pages'=>array());
				file_put_contents($filename,json_encode($caseInfo));
				tip('项目创建成功','Location:?controller=admin&action=edit&id='.$aliasname);
			}
		}
		if(isset($_GET["type"])){
			$type=$_GET["type"];
			$cid = $_GET["id"];
			if($type=='folder'){
				display('header','新建目录');
				display('adminTopbar');
				echo(
				'<form method="post" action="index.php?controller=admin&action=add">
				  <li><input name="newcase" type="text" placeholder="目录名称" /><li>
				 <li> <select>'
				 );
					echo '<option>顶级目录</option>';
				echo(
				  '</select></li>
				  <li><input name="order" type="text" placeholder="排序"/></li>
				  <li><input name="order" type="text" placeholder="排序"/></li>
				  <li><input type="submit" name="submit" value="  确 定  " class="left" />
				  <input type="button" onClick="window.history.go(-1);" value="取消" ></li>
				</form>');
				display('footer');
			};
			if($type=='page'){
				display('header','新建页面');
				display('adminTopbar');
				echo(
				'<form method="post" action="index.php?controller=admin&action=add">
				  <li><input name="newcase" type="text" placeholder="目录名称" /></li>
				  <li><select>
					<option>顶级目录</option>
				  </select></li>
				  <li><input name="order" type="text" placeholder="排序"/></li>
				  <li><input type="submit" name="submit" value="  确 定  " class="left" />
				  <input type="button" onClick="window.history.go(-1);" value="取消" ></li>
				</form>');
				display('footer');
			};

			
			
		}
	// 删除文档
	}elseif(action == 'del'){
	// 编辑文档
	}elseif(action == 'edit'){
		display('header','编辑文档');
		display('adminTopbar');
		if(isset($_GET["id"])){
			$caseId = $_GET["id"];
			$caseArry = explode('-',$caseId);
			$casePath = database.$caseArry[0];
			$caseInfo = getInfo($casePath);
			if(file_exists($casePath)){
				echo '<div class="admin-edit">';
				echo '<div class="admin-edit-left">';
				echo '<h3>'.$caseInfo->name.'</h3>';
				echo '<p>'.$caseInfo->desc.'</p>';
				echo '<a href="index.php?controller=admin&action=add&type=folder&id='.$caseId.'">新目录</a>';
				echo '<a href="index.php?controller=admin&action=add&type=page&id='.$caseId.'">新页面</a>';
				echo '<ul>
				<li><a href="#">d</a></li>
				</ul>
				</div>
				</div>';
			}else{
				tip('文档不存在');
			}
		};
		display('footer');
	// 管理首页
	}else{
		Location(controller,'list') ;
	}
	
	
}elseif(controller=='pages'){
	$fileName = database.create_name('test');
	echo create_dir($fileName);
}else{
	echo '404';
}




?>

