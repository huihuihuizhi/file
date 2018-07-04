<?php
//简单的文件在线管理
$path="./";//指定路径
$filelist=array("file.php");//要过滤掉的文件

//一、根据action的值做相应的操作
switch($_GET["action"]){
	case "del": //删除一个文件
	unlink ($_GET["filename"]);
	break;
	case "create"://创建一个文件
	//1.获取要创建的文件名
	$filename = trim($path,"/")."/".$_POST["filename"];
	//2.判断这个文件名是否存在
	if(file_exists($filename)){
		die("要创建的文件已经存在");
	}
	//3.创建这个文件
	$f = fopen($filename,"w");//以w的方式打开
	fclose($f);
	break;
	case "edit"://修改一个文件
	  //1.获取要创建的文件名
	  $filename=$_GET["filename"];
	   //2.读取文件的内容
	  $fileinfo=file_get_contents($filename);
	break;
	case "update"://执行修改一个文件
	 //1.获取信息：文件名、内容
	 $filename=$_POST["filename"];
	 $content=$_POST["content"];
	 //2.执行文件内容修改
	 file_put_contents($filename,$content);
	break;
}

//二、浏览指定目录下的文件 ，并用表格输出
   //1、path目录信息的过滤。判断path存在，并且是否是个目录
if(!file_exists($path) || !is_dir($path)){
	die($path."无效");
}
   //输出表头信息
  echo "<center>";
 echo "<h3>{$path}目录下的信息</h3>";
 echo "<h4><a href='file.php?action=add'>创建文件</a></h4>";
 echo "<table width='600' border='0'>";
 	echo "<tr bgcolor='#cccccc' align='left'>";
 		echo "<th>序号</th>";
 		echo "<th>名称</th>";
 		echo "<th>类型</th>";
 		echo "<th>大小</th>";
 		echo "<th>创建时间</th>";
 		echo "<th>操作</th>";
 	echo "</tr>";

    //3、打开这个目录，并遍历、删除目录下的所有文件
   //$dir=opendir();//Warning: opendir() expects at least 1 parameter, 0 given in D:\phpstudy\WWW\file\file.php on line 24
                  //至少给出一个参数
    $dir=opendir($path);//打开目录
    if($dir){
    	$i=0;
    	//遍历目录中的文件
    	while($f=readdir($dir)){//读取文件,$f=得到的是文件的名字
    		//目录中会有. 和 .. 两个隐藏文件，要过滤掉
    		if($f=="." || $f== ".." || in_array($f,$filelist)){
    			continue;
    		}
    		$file = trim($path,"/")."/".$f;//获取文件类型时要加上路径
    		 //trim 删除$path中的/,再连上/,再加上文件名称
    		$i++;
    		echo "<tr >";
    			echo "<td>{$i}</td>";//序号
    			echo "<td>{$f}</td>";//名称
    			echo "<td>".filetype($file)."</td>";//filetype -- 取得文件类型
    			echo "<td>".filesize($file)."</td>";//filesize -- 取得文件大小
    			echo "<td>".@date('Y-m-d',filectime($file))."</td>";//filectime取得文件的 inode 修改时间
    			echo "<td><a href='file.php?filename={$file}&action=del'>删除</a>
    				      <a href='file.php?filename={$file}&action=edit'>修改</a>
    				  </td>";//给的是绝对路径
    		echo "</tr>";   		
    	}
    	closedir($dir);//关闭目录
    	
    }
    echo "<tr bgcolor='#cccccc' align='left'><td colspan='6'>&nbsp;</td></tr>";
 echo "</table>";
 echo "</center>";
 
 //三、判断是否需要创建文件表单，若需要输出文件的表单框
 if($_GET['action']=="add"){
 	echo "<br>";
 	echo "<center>";
 	echo "<form action='file.php?action=create' method='post'>";
 	echo "新建表单：<input type='text' name='filename'>";
 	echo "<input type='submit' value='新建文件'>";
    echo "</form>";
    echo "</center>";
 }
 
//三、判断是否需要编辑文件表单，若需要输出文件的表单框
 if($_GET['action']=="edit"){
 	echo "<br>";
 	echo "<center>";
 	echo "<form action='file.php?action=update' method='post'>";
 	echo "文件名：<input type='text' name='filename' value='$filename'><br>";
    echo "文件内容：<textarea name='content' cols='40' rows='6'>{$fileinfo}</textarea><br>";
 	echo "<input type='submit' value='执行修改'>";
    echo "</form>";
    echo "</center>";
 }
?>