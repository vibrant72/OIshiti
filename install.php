<?php
$php_version=explode('-',phpversion());
if(strnatcasecmp($php_version[0],'5.5.0')==-1)
	die("php版本过低(<5.5.0)，无法使用本程序");

if(isset($_GET['post']) && $_GET['post']==1)
{
	if(isset($_POST['name']) && $_POST['name']=="")
		die("数据库名称未填写<a href='install.php'>返回</a>");
	
	if(!(isset($_POST['url']) && isset($_POST['user']) && isset($_POST['pw'])))
		die("表单未填写完整<a href='install.php'>返回</a>");
	
	@$mysql=mysqli_connect($_POST['url'],$_POST['user'],$_POST['pw']);
    if($mysql)
    {
		mysqli_query($mysql,"CREATE DATABASE ".$_POST['name']);
		mysqli_select_db($mysql,$_POST['name']);
		if(mysqli_query($mysql,"CREATE TABLE user (username varchar(25),password varchar(70),passkey int,note LongText)")===FALSE)
			die("无法创建表");
		if(mysqli_query($mysql,"CREATE TABLE codes (id int,user varchar(25),name varchar(50),prob LongText,std LongText)")===FALSE)
			die("无法创建表");
		
		$myfile=fopen("config.php", "w");
		fwrite($myfile,"<?php\n//以下是数据库的配置信息\ndefine(\"data_ip\",\"".$_POST['url']."\");//数据库ip地址,默认为本地localhost\ndefine(\"data_user\",\"".$_POST['user']."\");//数据库用户名\ndefine(\"data_password\",\"".$_POST['pw']."\");//数据库密码\ndefine(\"data_name\",\"".$_POST['name']."\");//数据库名称\n?>");
		fclose($myfile);
		echo "安装完成，如果此文件没能删除请手动删除!<a href='search.php'>立即体验</a>";
		@unlink("install.php");
		exit(0);
    }
    else
		die("无法连接至数据库<a href='install.php'>返回</a>");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>安装</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<style>
	body{
		font-family:"微软雅黑";
	}
	.main{
		width:90%;
		height:28px;
		margin:0 auto;
		border-bottom:1px solid #ddd;
		margin-bottom:28px;
		text-align:center;
	}
	.main div{
		margin-top:10px;
	}
	</style>
</head>
<body>
	<div style="margin-top:70px;"></div>
	<form class="main" action="install.php?post=1" method="POST">
		<span style="padding:0 20px;background:#FFFFFF;width:auto;font-size:38px;" id="wels">安装OI试题管理</span>
		<div style="margin-top:60px;"></div>
		<div><a>数据库地址：</a><input name="url" type="text" placeholder="一般为localhost" value="localhost"></div>
		<div><a>数据库账户：</a><input name="user" type="text" placeholder="默认为root" value="root"></div>
		<div><a>数据库密码：</a><input name="pw" type="text"></div>
		<div><a>数据库名称：</a><input name="name" type="text" value="wcode"></div>
		<div style="margin-top:40px;"></div>
		<button type="submit">确定并安装</button>
	</form>
</body>
</html>