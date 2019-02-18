<?php
include("core.php");
$login=islogin();
if($login==false)
    header('Location: member.php?href='.getallget("edit.php",true));
$tn="";$tp="";$ts="";
$con=loginsql();
if($con=="-1")
	die("无法连接至数据库");
if(isset($_GET['id']))
{
    $result=mysqli_query($con,"SELECT * FROM codes");
    while($row=mysqli_fetch_array($result))
    {
        if($row['id']==$_GET['id'])
        {
            $tn=str_replace('<','&lt;',$row['name']);$tp=str_replace('<?','&lt;?',$row['prob']);$ts=str_replace('<','&lt;',$row['std']);
            break;
        }
    }
}
if(isset($_GET['post']) && $_GET['post']=="1")
{
    if(isset($_POST['name']))$tn=addslashes($_POST['name']);
    if(isset($_POST['prob']))$tp=addslashes($_POST['prob']);
    if(isset($_POST['std']))$ts=addslashes($_POST['std']);
    
	if($tn=="")
		die("题目名不可为空");
	
	if(strpos($tn,Chr(13)))
		die("题目名中不得含有换行符");
	
    $prob_id="";
    if(isset($_GET['id']))
    {
        $row=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM codes WHERE id='".$_GET['id']."'"));
        if($row['user']!=$_COOKIE['username'])
            die("只有创建本题者才可以修改这道题");
        mysqli_query($con,"UPDATE codes SET name='".$tn."', prob='".$tp."', std='".$ts."' WHERE id='".$_GET['id']."'");
        $prob_id=$_GET['id'];
    }
    else if(isset($_POST['name']))
    {
        $row=mysqli_fetch_array(mysqli_query($con,"select * from codes order by id desc limit 1"));
        mysqli_query($con,"INSERT INTO codes (id, user, name, prob, std) VALUES ('".($row['id']+1)."', '".$_COOKIE['username']."', '".$tn."', '".$tp."', '".$ts."')");
        $prob_id=$row['id']+1;
    }
	die("P".strval($prob_id));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>OI试题管理——编辑题目</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <script type="text/javascript" charset="utf-8" src="ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="ueditor/ueditor.all.min.js"> </script>

    <script type="text/javascript" charset="utf-8" src="ueditor/lang/zh-cn/zh-cn.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha256-916EbMg70RQy9LHiGkXzG8hSg9EdNy97GazNG/aiY1w=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js" integrity="sha256-wNQJi8izTG+Ho9dyOYiugSFKU6C7Sh1NNqZ2QPmO0Hk=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha256-U5ZEeKfGNOja007MMD3YBI0A3OSZOQbeG6z2f2Y0hu8=" crossorigin="anonymous"></script>
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Buttons/2.0.0/css/buttons.min.css" integrity="sha256-ODfUydfDPL8ChmjqZB6zodKCcaQWXVfB4TTBoO3RCEY=" crossorigin="anonymous" />
	<link href="css/style.css" rel="stylesheet">
</head>
<body>
    </br></br>
    </br></br>
    <?php echo html_get_header($login,"edit.php");?>
    
    <br/>
    <br/>
    <script type="text/javascript">
        function send(){
			$("#lrc").show();
            $.post("edit.php?post=1<?php if(isset($_GET['id']))echo "&id=".$_GET['id'];?>", $('#probedit').serialize(),
    		function(data){
				if(data.substring(0,1)=="P")
					window.location.href='read.php?id='+data.substring(1);
				else
					alert(data);
				$("#lrc").hide();
    		})
			.error(function(){
				alert("无法上传题目");
				$("#lrc").hide();
			});
        }
    </script>
	
	<main class="packages-list-container" id="all-packages">
		<div class="container">
            <form id="probedit" onsubmit="return false" action="#" method="post">
                <p>题目名(prob's name):<textarea rows=1 class="form-control" name="name" onkeydown="checkEnter(event);" style="resize:none;"><?php echo $tn;?></textarea></p>
                <p>题目描述(prob's description):</p>
                <script id="editor" name="prob" type="text/plain"><?php echo $tp;?></script>
                <p>标准程序(std):</p>
                <p><textarea rows=15 name="std" class="form-control" style="width:100%;"><?php echo $ts;?></textarea></p>
                <button onclick="send();" class="button button-rounded button-rounded">保存</button>
            </form>
            <script type="text/javascript">
                var ue = UE.getEditor('editor');
                
                function checkEnter(e){
                    var et=e||window.event;
                    var keycode=et.charCode||et.keyCode;   
                    if(keycode==13)
                    {
                        if(window.event)
                           window.event.returnValue = false;
                         else
                           e.preventDefault();//for firefox
                    }
                }
            </script>
        </div>
    </main>
	<div id="lrc" style="display:none;">
		<div class="alert alert-info" role="alert" style="width:400px;">题目上传中...请稍后</div>
	</div>
	
	<?php echo html_get_footer();?>
	
</body>
</html>