<?php
session_start();
require_once("config.php");
function loginsql()
{
    @$mysql=mysqli_connect(constant("data_ip"),constant("data_user"),constant("data_password"));
    if($mysql)
    {
        mysqli_select_db($mysql,constant("data_name"));
        return $mysql;
    }
    return "-1";
}
function islogin()
{
    if(!(!empty($_COOKIE['username'])&&!empty($_COOKIE['USER'])))
        return false;
    if(!($_SESSION["'".$_COOKIE['username']."'"]=="1"))
        return false;
    
    $con=loginsql();
    if(!$con)
        return false;
    
    $row=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM user WHERE username='".$_COOKIE['username']."'"));
    if(is_array($row) && password_verify(md5($row['username']."|".$row['password']."|".$row['passkey']),$_COOKIE['USER']))
        return true;
    return false;
}
function setcookies($username,$password,$passkey)
{
    $_SESSION["'".$username."'"]=1;
    $tmp=password_hash(md5($username."|".$password."|".$passkey),PASSWORD_DEFAULT);
    setcookie("USER",$tmp);
    setcookie("username",$username);
}
function register($username,$password,$repassword)
{
    if($password!=$repassword)
        return "二次密码不一致";
    if(!(strlen($username)<=20 && strlen($password)<=20 && $password!="" && $repassword!="" && $password==$repassword))
        return "用户名或密码过长";
    if(!(strpos($password, "\"")===FALSE && strpos($password, "'")===FALSE && strpos($password, "\\")===FALSE && strpos($password, "<")===FALSE
	&& strpos($username, "\"")===FALSE && strpos($username, "'")===FALSE && strpos($username, "\\")===FALSE && strpos($username, "<")===FALSE
	))
	    return "非法字符";
    
    $con=loginsql();
    if($con=="-1")
        return "无法连接至数据库";
    
    if(is_array(mysqli_fetch_array(mysqli_query($con,"SELECT * FROM user WHERE username='".$username."'"))))
        return "用户名重复";
    else
    {
        $paskey=mt_rand(10000,99999);
    	if(mysqli_query($con,"INSERT INTO user (username, password, passkey) VALUES ('".$username."', '".hash('sha256',$password)."', '".$paskey."')")===FALSE)
    	    return "注册失败";
    	else
    	{
    	    setcookies($username,hash('sha256',$password),$paskey);
    	    /*if(isset($_GET['href']))
    	        header('Location: '.base64_decode($_GET['href']));*/
    	    return "register success";
    	}
    }
    return "未知错误";
}
function login($username,$password)
{
    $con=loginsql();
    if($con=="-1")
        return "无法连接至数据库";
    
    $row=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM user WHERE username='".$username."'"));
	if(!is_array($row))
		return "用户不存在";
	if(hash('sha256',$password)==$row['password'])
	{
	    setcookies($username,$row['password'],$row['passkey']);
	    /*if(isset($_GET['href']))
            header('Location: '.base64_decode($_GET['href']));*/
	    return "login success";
	}
	else
	    return "登录失败";
    return "未知错误";
}
function logout()
{
    $_SESSION["'".$_COOKIE['username']."'"]=0;
    setcookie("USER","");
    setcookie("username","");
}
function getallget($qz,$jm)
{
    $gets=$qz;
    $first=1;
    foreach ($_GET as $key=>$value) 
    {
        if($first)
        {
            $first=0;
            $gets=$gets."?".$key."=".$value;
        }
        else
            $gets=$gets."&".$key."=".$value;
    }
    if($jm==true)
        $gets=base64_encode($gets);
    return $gets;
}
function substrwithkey($txt,$key,$long,$nohtml)
{
    $txts=preg_replace("/\s/","",$txt);
    if($nohtml)
        $txts=strip_tags($txts);
    $tmp_di=stripos($txts,$key,0);
    if($tmp_di!==FALSE && strlen($txts)-$tmp_di>$long)
        return mb_substr(str_replace("<","&lt;",$txts),$tmp_di,$long,"UTF8");
    else if(strlen($txts)>$long)
        return mb_substr(str_replace("<","&lt;",$txts),0,$long,"UTF8");
    else
        return $txts;
}
function html_get_header($islogin,$userphp)
{
    $memjump=getallget($userphp,true);
    if($memjump!="")
        $memjump="href=".$memjump;
    $mem="<li class=\"disabled\"><a>登录/注册</a></li>";
    $tc="<li><a href=\"member.php?res=1&".$memjump."\">退出</a></li>";
    $wly="欢迎回来";
    if(!$islogin)
    {
        $mem="<li><a href=\"member.php?".$memjump."\">登录/注册</a></li>";
        if($userphp=="member.php")
            $mem="";
        $tc="<li class=\"disabled\"><a>退出</a></li>";
        $wly="您未登录";
    }
    
    $se="";$ed="";
    if($userphp=="search.php")
        $se=" class=\"active\"";
    if($userphp=="edit.php")
        $ed=" class=\"active\"";
    return <<<EOF
              <head>
              <meta charset="utf-8">
              <meta name="viewport" content="width=device-width">
              <link rel="stylesheet" href="/css/mdui.min.css"/>
             </head>
 <body background="https://acg.toubiec.cn/random"
style=" background-repeat:no-repeat ;
background-size:100% 100%; 
background-attachment: fixed;" 
>            </script>
            <div id="toolbar" class="mdui-appbar mdui-appbar-fixed">
              <div class="mdui-toolbar mdui-color-white " id="toolbarinner">
                <a  href="javascript:var inst = new mdui.Drawer('#drawer',{overlay: 'true'});inst.toggle();" class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white "><i class="mdui-icon material-icons">menu</i></a>
                <a href="javascript:scrollbacktotop();" class="mdui-typo-headline">OI试题管理</a>
                <div class="mdui-toolbar-spacer"></div>
                <a href="index.php" class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white"  mdui-tooltip="{content: '首页'}" id="toolbarbuttonhome" ><i class="mdui-icon material-icons">home</i></a>
               <a href="search.php" class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white" mdui-tooltip="{content: '搜索题目'}" id="toolbarbuttonsearch" ><i class="mdui-icon material-icons">search</i></a>
              </div>
            </div>
        <div class="mdui-drawer mdui-color-white mdui-drawer-close" id="drawer" overlay="true">
        <ul class="mdui-list">
            <a class="mdui-list-item mdui-ripple" href="index.php">
                <i class="mdui-list-item-icon mdui-icon material-icons">home</i>
                <div class="mdui-list-item-content">首页</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="search.php">
                <i class="mdui-list-item-icon mdui-icon material-icons">search</i>
                <div class="mdui-list-item-content">搜索题目</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="edit.php">
                <i class="mdui-list-item-icon mdui-icon material-icons">edit</i>
                <div class="mdui-list-item-content">添加题目</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="member.php">
                <i class="mdui-list-item-icon mdui-icon material-icons">account_circle</i>
                <div class="mdui-list-item-content">注册登录</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="/member.php?res=1&/">
                <i class="mdui-list-item-icon mdui-icon material-icons">call_missed_outgoing</i>
                <div class="mdui-list-item-content">退出登录</div>
            </a>
                                                     </a>
                        <a class="mdui-list-item mdui-ripple" href="//github.com/wangshengjun33/OImanagement">
                <i class="mdui-list-item-icon mdui-icon material-icons">format_line_spacing</i>
                <div class="mdui-list-item-content">项目地址</div>
            </a>
           
               
            </a>
                        <a class="mdui-list-item mdui-ripple">
                              
            </a>
            
                        </a>
            </ul>
        </div>
        <script src="/js/mdui.min.js"></script>
        </body>
EOF;
}
function html_get_footer()
{
	return <<<EOF
	<div style="margin-top:40px;"></div>
	<footer class="footer" style="text-align:center;">
		<br/>
		&copy;2018  <a  href="//cnblogs.com/wangshengjun">WSJ</a>
，Powered By 
                                <a  href="//github.com/wangshengjun33/OImanagement">OI management</a>
   	</footer>
	<br/>
EOF;
}
function get_id_note($id)
{
	$con=loginsql();
	if($con=="-1")
		return "无法连接至数据库";

	$note="";
	$result=mysqli_query($con,"SELECT * FROM user");
	while($row=mysqli_fetch_array($result))
	{
		if($row['username']==$_COOKIE['username'])
		{
			$__tmp=explode("\n",$row['note']);
			for($index=0;$index<count($__tmp);$index++)
			{
				$__tmp2=explode(":",$__tmp[$index]);
				if(count($__tmp2)>1 && $__tmp2[0]==$id)
				{
					$note=$__tmp2[1];
					break;
				}
			}
			break;
		}
	}
	mysqli_close($con);
	return base64_decode($note);
}
function change_note_id($id,$note)
{
	$con=loginsql();
	if($con=="-1")
		return "无法连接至数据库";
	
	$nnote="";
	$result=mysqli_query($con,"SELECT * FROM user");
	while($row=mysqli_fetch_array($result))
	{
		if($row['username']==$_COOKIE['username'])
		{
			$__tmp=explode("\n",$row['note']);
			$empty=true;$haveit=false;$first=true;
			for($index=0;$index<count($__tmp);$index++)
			{
				$__tmp2=explode(":",$__tmp[$index]);
				if(count($__tmp2)>1)
				{
					if($__tmp2[0]==$id)
					{
						if($first)
						{
							$nnote=$id.":".addslashes(base64_encode($note));
							$first=false;
						}
						else
							$nnote=$nnote."\\n".$id.":".addslashes(base64_encode($note));
						$haveit=true;
					}
					else
					{
						if($first)
						{
							$nnote=$__tmp2[0].":".addslashes($__tmp2[1]);
							$first=false;
						}
						else
							$nnote=$nnote."\\n".$__tmp2[0].":".addslashes($__tmp2[1]);
					}
					$empty=false;
				}
			}
			if(!$haveit)
				$nnote=$nnote."\\n".$id.":".addslashes(base64_encode($note));
			if($empty)
				$nnote=$id.":".addslashes(base64_encode($note));
			break;
		}
	}
	mysqli_query($con,"UPDATE user SET note='".$nnote."' WHERE username='".$_COOKIE['username']."'");
	return "setok";
}
function checkrobot($useragent='')
{
	static $kw_spiders=array('bot', 'crawl', 'spider' ,'slurp', 'sohu-search', 'lycos', 'robozilla');
    static $kw_browsers=array('msie', 'netscape', 'opera', 'konqueror', 'mozilla');
	
    $useragent=strtolower(empty($useragent)?$_SERVER['HTTP_USER_AGENT']:$useragent);
    if(strpos($useragent,'http://')===false&&dstrpos($useragent, $kw_browsers))return false;
    if(dstrpos($useragent,$kw_spiders))return true;
    return false;
}
function dstrpos($string,$arr,$returnvalue=false)
{
    if(empty($string)) return false;
    foreach((array)$arr as $v)
	{
        if(strpos($string, $v)!==false)
		{
            $return=$returnvalue?$v:true;
			return $return;
        }
    }
    return false;
}
?>

