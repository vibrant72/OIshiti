<?php
include("core.php");
$login=islogin();

$href="";

if(isset($_GET['res']) && $_GET['res']=="1")
{
    logout();
    go();
}
if($login)
    die("你已经登录过了");
function go()
{
    if(isset($_GET['href']))
        header('Location: '.base64_decode($_GET['href']));
    exit(0);
}
if(isset($_GET['post']) && $_GET['post']=="1" && isset($_POST['user']) && isset($_POST['pw']))
{
    echo login($_POST['user'],$_POST['pw']);
    exit(0);
}
    
if(isset($_GET['post']) && $_GET['post']=="2" && isset($_POST['user']) && isset($_POST['pw']) && isset($_POST['apw']))
{
    echo register($_POST['user'],$_POST['pw'],$_POST['apw']);
    exit(0);
}
if(isset($_GET['href']) && $_GET['href']!="")
    $href="&href=".$_GET['href'];
?>

<!DOCTYPE html>
<html>
</br></br>
</br></br>
<head>
	<title>用户</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	
	<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha256-916EbMg70RQy9LHiGkXzG8hSg9EdNy97GazNG/aiY1w=" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js" crossorigin="anonymous" integrity="sha256-wNQJi8izTG+Ho9dyOYiugSFKU6C7Sh1NNqZ2QPmO0Hk="></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous" integrity="sha256-U5ZEeKfGNOja007MMD3YBI0A3OSZOQbeG6z2f2Y0hu8="></script>
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Buttons/2.0.0/css/buttons.min.css" integrity="sha256-ODfUydfDPL8ChmjqZB6zodKCcaQWXVfB4TTBoO3RCEY=" crossorigin="anonymous" />
	<link href="css/style.css" rel="stylesheet">
	
	<script type="text/javascript">
		$(document).ready(function(){
			$("a[id='qha']").click(function(){
				if($("#apassw").css('display') === 'none'){
					$("#apassw").css('display','inline');
					$("#wels").text('注册');
					$("#frmbut").text('注册');
					$("#frmbut").attr('onclick','register();');
					$("#qha").text('已有账号？立即登录');
				}else{
					$("#apassw").css('display','none');
					$("#wels").text('登录');
					$("#frmbut").text('登录');
					$("#frmbut").attr('onclick','login();');
					$("#qha").text('没有账号？立即注册');
				}
			});
		});
	</script>
	
    <script type="text/javascript">
        function login(){
            $.post("member.php?post=1<?php echo $href;?>", $('#userform').serialize(),
    		function(data){
    		    if(data=="login success")
    		        window.location.href='<?php echo addslashes(base64_decode($_GET['href']));?>';
    			else
    			    alert(data);
    		});
        }
        function register(){
            $.post("member.php?post=2<?php echo $href;?>", $('#userform').serialize(),
    		function(data){
    		    if(data=="register success")
    		        window.location.href='<?php echo addslashes(base64_decode($_GET['href']));?>';
    			else
    			    alert(data);
    		});
        }
    </script>
</head>
<body>
    
    <?php echo html_get_header($login,"member.php");?>
    
    <br/>
    <br/>
	<main class="packages-list-container" id="all-packages">
		<div class="container">
			<div class="col-xs-6 col-md-4 col-center-block" style="text-align:center;width:100%;">
				<form id="userform" onsubmit="return false" action="#" method="post" style="width:100%;">
					<div style="width:90%;height:28px;margin:0 auto;border-bottom:1px solid #ddd;margin-bottom:28px;">
						<span style="padding:0 20px;background:#FFFFFF;width:auto;font-size:38px;" id="wels">登录</span>
					</div>
					<div style="margin-top:100px;width:40%;" class="regfrm col-center-block">
						<input class="form-control" name="user" type="text" placeholder="昵称"/>
						<div style="margin-top:30px;"></div>
						<input class="form-control" name="pw" type="password" placeholder="密码"/>
						<div style="margin-top:30px;"></div>
						<div id="apassw" style="display:none;"><!-- inline-->
							<input class="form-control" name="apw" type="password" placeholder="重复密码"/>
							<div style="margin-top:30px;"></div>
						</div>
						<button class="button button-rounded button-rounded button-large" style="width:100%;" onclick="login();" id="frmbut">登录</button>
						<div style="margin-top:40px;"></div>
						<a style="float:right;cursor:pointer;" id="qha">没有账号？立即注册</a>
					</div>
				</form>
			</div>
		</div>
	</main>
	
	<?php echo html_get_footer();?>
	
	<script>
		Function.prototype.getMultiLine=function (){
			var lines=new String(this);
			lines=lines.substring(lines.indexOf("/*")+3,lines.lastIndexOf("*/"));
			return lines;
		}
		var string=function ()
		{
		/* 
				   #          #    #
				##           #       ##
			 ##             #           ##         #     #     #           ####   #####   ####     ######
		   #               #               #       #     #     #         #       #     #  #    #   #
			 ##           #             ##          #   # #   #         #        #     #  #     #  #####
				##       #           ##              # #   # #           #       #     #  #    #   #
				   #    #          #                  #     #    ######   #####   #####   ####     ######
		
								   Release 2.0.0  Powered by 主站前端.
		*/ 
		}
		window.console.log(string.getMultiLine());
	</script>

</body>
</html>