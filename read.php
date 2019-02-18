<?php
include("core.php");
$login=islogin();

$tmn="";$tmu="";$tmp="";$tms="";$id="";$edi=" <button type=\"button\" class=\"btn btn-default disabled\">无法编辑</button>";
if(!$login)
	$edi=" <button type=\"button\" class=\"btn btn-default disabled\">登录编辑</button>";
if(isset($_GET['id']) && $_GET['id']!="")
{
    $id="&id=".$_GET['id'];
    $con=loginsql();
    if($con=="-1")
        die("无法连接至数据库");
    $result=mysqli_query($con,"SELECT * FROM codes");
    while($row=mysqli_fetch_array($result))
    {
        if($row['id']==$_GET['id'])
        {
            $tmn=$row['name'];
            $tmu=$row['user'];
            $tmp=str_replace('<?php','&lt?php;',$row['prob']);
            $tms=str_replace('<','&lt;',$row['std']);
            
            if(isset($_COOKIE['username'])&&$_COOKIE['username']==$tmu)
                $edi=" <button onclick=\"javascript:window.location.href='edit.php?id=".$_GET['id']."'\" type=\"button\" class=\"btn btn-default\">编辑</button>";
            break;
        }
    }
	mysqli_close($con);
}

if($tmn==$tmu && $tmp==$tms && $tmu==$tmp && $tmn=="")
    die("Empty");
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $tmn." - wcode";?></title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha256-916EbMg70RQy9LHiGkXzG8hSg9EdNy97GazNG/aiY1w=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js" integrity="sha256-wNQJi8izTG+Ho9dyOYiugSFKU6C7Sh1NNqZ2QPmO0Hk=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha256-U5ZEeKfGNOja007MMD3YBI0A3OSZOQbeG6z2f2Y0hu8=" crossorigin="anonymous"></script>
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Buttons/2.0.0/css/buttons.min.css" integrity="sha256-ODfUydfDPL8ChmjqZB6zodKCcaQWXVfB4TTBoO3RCEY=" crossorigin="anonymous" />
	<link href="css/style.css" rel="stylesheet">
</head>
<body>
    
    <?php echo html_get_header($login,"read.php");?>
    
    <br/>
    <br/>
    <main class="packages-list-container" id="all-packages">
		<div class="container">
			<div style="overflow:hidden;">
				<h1><?php echo $tmn;?></h1>
				<h4 style="float:left;color:#FF4081;">By:<?php echo $tmu;?></h4>
            </div>
			
            <div class="panel panel-default">
                <div class="panel-body" id="prob">
                    <?php
                    if(isset($_GET['std']) && $_GET['std']=="1")
                        //echo "<code>".$tms."</code>";
                        echo "<pre style=\"white-space:pre-wrap;word-wrap:break-word;\">\n".$tms."\n</pre>";
                    else
                        echo $tmp;
                    ?>
                </div>
            </div>
			<div style="overflow:hidden;">
				<div style="float:left;"><?php echo $edi;?></div>
				<button onclick="javascript:window.location.href='note.php?id=<?php echo $_GET['id'];?>'" type="button" style="float:left;margin-left:10px;" class="btn btn-default">我的备注</button>
				<?php
					if(isset($_GET['std']) && $_GET['std']=="1")
						echo "<a style=\"float:right;\" class=\"button button-rounded button-rounded\" href=\"read.php?".$id."\">查看题目</a>";
					else
						echo "<a style=\"float:right;\" class=\"button button-rounded button-rounded\" href=\"read.php?std=1".$id."\">查看题解</a>";
				?>
			</div>
        </div>
    </main>
	
	<?php echo html_get_footer();?>
	
</body>
</html>