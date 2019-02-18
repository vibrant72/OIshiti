<?php
include("core.php");
$login=islogin();
if($login==false)
    header('Location: member.php?href='.getallget("note.php",true));
if(!isset($_GET['id']) || (isset($_GET['id']) && $_GET['id']==""))
	die("Empty");
if(isset($_GET['post'])&&isset($_POST['note'])&&$_GET['post']=="1")
	die(change_note_id($_GET['id'],$_POST['note']));
?>
<!DOCTYPE html>
<html>
<head>
    <title>编辑备注</title>
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
    
    <?php echo html_get_header($login,"edit.php");?>
    
    <br/>
    <br/>
	<script type="text/javascript">
        function send(){
			$("#lrc").show();
            $.post("note.php?post=1<?php echo "&id=".$_GET['id'];?>", $('#noteedit').serialize(),
    		function(data){
				if(data=="setok")
					window.location.href='note.php?id=<?php echo $_GET['id'];?>';
				else
					alert(data);
				$("#lrc").hide();
    		})
			.error(function(){
				alert("无法上传备注");
				$("#lrc").hide();
			});
        }
    </script>
    <main class="packages-list-container" id="all-packages">
		<div class="container">
            <form id="noteedit" onsubmit="return false" action="#" method="post">
                <p>你的备注(prob's note):</p>
                <script id="editor" name="note" type="text/plain"><?php echo get_id_note($_GET['id']);?></script>
                <button onclick="send();" class="button button-rounded button-rounded">保存</button>
            </form>
            <script type="text/javascript">
                var ue = UE.getEditor('editor');
            </script>
        </div>
    </main>
	<div id="lrc" style="display:none;">
		<div class="alert alert-info" role="alert" style="width:400px;">备注上传中...请稍后</div>
	</div>
	
	<?php echo html_get_footer();?>
	
</body>
</html>