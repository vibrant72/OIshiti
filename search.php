<?php
include("core.php");
$login=islogin();
?>

<!DOCTYPE html>
<html>
</br></br>
<head>
	<meta charset="utf-8">
	<title>OI试题管理——搜索题目</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha256-916EbMg70RQy9LHiGkXzG8hSg9EdNy97GazNG/aiY1w=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js" integrity="sha256-wNQJi8izTG+Ho9dyOYiugSFKU6C7Sh1NNqZ2QPmO0Hk=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha256-U5ZEeKfGNOja007MMD3YBI0A3OSZOQbeG6z2f2Y0hu8=" crossorigin="anonymous"></script>
	<link href="css/minimal/grey.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
    <script src="js/icheck.min.js"></script>
</head>
<body>
                </br></br>
                </br></br>
                <div style="width:50%;height:18px;margin:0 auto;border-bottom:1px solid #ddd;margin-bottom:28px;">
               <span style="padding:0 20px;background:#FFFFFF;width:auto;font-size:38px;" id="wels">搜索题目</span>
	</div>	
	<?php echo html_get_header($login,"search.php");?>
    
    <main class="packages-list-container" id="all-packages">
		<div class="container">
		    <br/>
		    <br/>
		    <div style="width:100%;overflow:hidden;">
		        <form id="search" action="search.php?s=1" method="POST">
		            <input style="width:75%;float:left;" type="text" name="txt" class="form-control search clearable" placeholder="点击'搜索'按钮开始搜索，关于您所选的关键字 留空搜索全部">
                    <div style="width:25%;float:left;text-align:center;">
                        <input type="checkbox" name="name" checked/>题目名
                        <input type="checkbox" name="prob"/>内容
                        <input type="checkbox" name="user"/>提供者
                        <button type="submit" class="btn btn-default">搜索</button>
                    </div>
		        </form>
            </div>
            <br/>
            <br/>
		    <br/>
			<div class="list-group packages" id="common-packages">
			    <?php
					$isrotbot=checkrobot();
    			    if(((isset($_GET['s']) && isset($_POST['txt'])) && $_GET['s']=="1") || $isrotbot)
    {
        $con=loginsql();
        if($con=="-1")
            die("无法连接至数据库");
        $result=mysqli_query($con,"SELECT * FROM codes");
        while($row=mysqli_fetch_array($result))
        {
            if($isrotbot||((($_POST['txt']=="")|(isset($_POST['name']) && $_POST['name']==true && stripos($row['name'],$_POST['txt'],0)!==FALSE)|(isset($_POST['prob']) && $_POST['prob']==true && stripos(strip_tags($row['prob']),$_POST['txt'],0)!==FALSE)|(isset($_POST['user']) && $_POST['user']==true && stripos($row['user'],$_POST['txt'],0)!==FALSE))))
            {
                if($isrotbot)
					$tpro=substrwithkey($row['prob'],"",1000,true);
                else
					$tpro=substrwithkey($row['prob'],$_POST['txt'],1000,true);
                echo <<<EOF
    				<a class="package list-group-item" href="read.php?id={$row['id']}">
    					<div class="row">
    						<div class="col-md-3" style="width:15%">
    							<h4 style="font-size:20px;">{$row['name']}</h4>
    							<h5 style="color:#FF4081;">By:{$row['user']}</h5>
    						</div>
    						<div class="col-md-9 hidden-xs" style="width:85%;color:rgba(0, 0, 0, 0.54);">
    							<p style="width:100%;height:100px;word-break:break-all;overflow:hidden;text-overflow:ellipsis;">{$tpro}</p>
    						</div>
    					</div>
    				</a>
EOF;
            }
        }
		mysqli_close($con);
    }
?>
			</div>
		</div>
	</main>
	
	<?php echo html_get_footer();?>
	
	<script>
    $(document).ready(function(){
        $('input').iCheck({
            checkboxClass: 'icheckbox_minimal-grey',
            radioClass: 'iradio_minimal-grey',
            increaseArea: '20%' // optional
        });
    });
	</script>
	
</body>
</html>