<?php require "../classes/init.php";
  if (!isset($_GET["m_r"])) {
    echo "<script>history.back();</script>";
    exit;
  }
  if ($user_obj->user_exist($_GET["m_r"]) === false OR $_GET["m_r"] == $_SESSION["a_user"]) {
    echo "<script>history.back();</script>";
    exit;
  }

  $reciever = $user_obj->get_user_data($_GET["m_r"]);
  $messages = $msg_obj->get_messages($reciever->username, true);
  $msg_num = $msg_obj->get_messages($reciever->username, false);
  if ($msg_obj->read_message($reciever->username)) {
    echo "";
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link rel="stylesheet" href="../css/font-awesome-4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../css/w3.css">
<!-- <link rel="stylesheet" href="../css/lib/w3-theme-black.css"> -->
<?php require_once '../theme.php'; ?>
<link rel="stylesheet" href="../css/style2.css">
<title>Messages | <?php echo ucfirst($reciever->username); ?></title>
<style media="screen">
  .message-wrapper{
    padding: 4px;
    width: 100%;
    display: flex;
    flex-direction: column;
  }
  .top-wrapper{
    padding: 5px;
    margin-top: 5px;
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: space-between;
  }
  .top-wrapper img{
    height: 50px;
    object-fit: cover;
    border-radius: 50%;
    width: 50px;
    position: relative;
  }
  .body-wrapper{
    border-radius: 10px;
    padding: 5px;
    overflow-y: auto;
    height: 400px;
    width: 100%;
    position: relative;
    margin-top: 5px;
  }
  .user-message-wrapper,
  .my-message-wrapper{
    width: 100%;
    margin: 5px 0;
    display: flex;
  }
  .user-message-wrapper img,
  .my-message-wrapper img{
    border-radius: 50%;
    object-fit: cover;
    height: 25px;
    padding: 3px;
    width: 25px;
  }
  .user-message-wrapper span,
  .my-message-wrapper span{
    font-family: "Trebuchet MS", Helvetica, sans-serif!important;
    margin: 0;
    max-width: 70%;
    border-radius: 30px;
    /* text-align: left; */
    text-align: center;
    padding: 5px 12px;
  }
  /* .user-message-wrapper span::after,
  .my-message-wrapper span::after{

  } */
  .user-message-wrapper{`
    justify-content: flex-start;
  }
  .my-message-wrapper{
    justify-content: flex-end;
  }
  .fa-send{
    cursor: pointer;
  }
  form{
    width: 100%;
    display: flex;
    align-items: center;
  }
  form button{
    min-width: 50px;
    height: 40px;
    border-radius: 5px;
    border: 0;
    margin-top: 10px;
    cursor: pointer;
    display: flex;
    justify-content: space-around;
    align-items: center;
  }
  form textarea{
    flex: 80%;
    resize: none;
    height: 40px;
    border-radius: 5px;
    border: 1px solid grey;
    padding: 5px 10px;
    margin: 10px 4px 0 0;
  }
  .my-date, .user-date{
    margin: 0;
    font-size: 13px;
  }
  .user-date{
    text-align: left;
    display: flex;
    justify-content: flex-start;
    margin-left: 15px;
  }
  .my-date{
    display: flex;
    justify-content: flex-end;
    margin-right: 15px;
  }
  .button{
    width: 100%;
    padding: 5px;
    margin: 1px 0;
    border: 0;
    font-size: 20px;
    display: block;
  }
</style>
</head>
<body class="w3-theme-l3">
  <div class="container w3-theme-light">
    <button class="button w3-green" onclick="history.back()" id="back">Back</button>
    <div class="header w3-theme-dark">
			<div class="left w3-bar">
				<img src="../images/<?php echo $me->profile_pic; ?>" alt="profile"/>
				<div class="search">
					<input type="search" placeholder="search "/>
				</div>
			</div>
      <ul>
				<li title="Home"><i class="fa fa-home"></i></li>
				<li title="Profile">
          <a href="../friends/profile.php">
            <i class="fa fa-user"></i>
          </a>
        </li>
				<li title="Friends">
					<a href="../friends/friends.php">
						<i class="fa fa-users"></i>
						<?php echo ($req_num > 0)? '<span class="badge-red">'.$req_num.'' : '</span>'; ?>
					</a>
				</li>
				<li title="Messages" class="active">
					<a href="index.php">
						<i class="fa fa-wechat"></i>
            <?php echo ($unread > 0)? '<span class="badge-red">'.$unread.'</span>': ''; ?>
					</a>
				</li>
				<li id="notation">
					<i class="fa fa-bars"></i>
					<a id="logout" class="w3-button w3-animate-zoom w3-card-4 w3-theme-d4" href="../friends/logout.php">
						<i class="fa fa-sign-out" style="font-size: 14px;"></i>
						<span>Logout</span>
					</a>
				</li>
			</ul>
		</div>
    <div class="response-wrapper">
      <?php if(isset($_GET['Error'])){?>
      <p class="w3-card-4" id="error"><?php echo $_GET['Error'];?></p>
      <?php } if(isset($_GET['Success'])){?>
      <p class="w3-card-4" id="success"><?php echo $_GET['Success'];?></p>
      <?php } if(isset($_GET['Info'])){?>
      <p class="w3-card-4" id="info"><?php echo $_GET['Info'];?></p>
      <?php }?>
    </div>
    <div class="message-wrapper">
      <div class="top-wrapper w3-card-2 w3-round w3-theme-l1">
        <img src="../images/<?php echo $reciever->profile_pic;?>" alt="<?php echo $reciever->username;?>">
        <span style="position: relative;">
          <span>Messages</span>
          <?php echo ($msg_num > 0)? '<span class="badge-red">'.$msg_num.'</span>' : '0';?>
        </span>
        <i class="fa fa-question"></i>
      </div>
      <div class="body-wrapper w3-theme-d1 w3-border">
        <?php if ($msg_num == 0) {?>
          <h2 style="text-align: center; margin-top: 5%;">No conversations yet</h2>
        <?php }else{
          foreach ($messages as $message) {
          // for ($i=0; $i < count($messages); $i++){
            if (!$msg_obj->i_sent_message($message->id)) { ?>

        <div class="user-message-wrapper">
          <img src="../images/<?php echo $message->profile_pic; ?>" alt="<?php echo $reciever->username;?>">
          <span class="userMessagea w3-theme-dark"><?php echo $message->body; ?></span>
        </div>
        <div class="user-date">
          <span><?php echo $date_obj->dateDiffStr($message->date_); ?></span>
        </div>
        <?php }else{ ?>
        <div class="my-message-wrapper">
          <span class="w3-theme-l3"><?php echo $message->body; ?></span>
          <!-- <img src="../images/<?php //echo $me->profile_pic;?>" alt="<?php //echo $me->username;?>"> -->
        </div>
        <div class="my-date">
          <span><?php echo $date_obj->dateDiffStr($message->date_); ?></span>
        </div>
        <?php } } } ?>
      </div>
      <div class="sender-wrapper">
        <form action="utils.php" method="post" id="m_form">
          <input type="hidden" name="page" value="<?php echo $_SERVER["PHP_SELF"];?>"/>
          <input id="m_r" name="m_r" type="hidden" value="<?php echo $reciever->username;?>"/>
          <input id="me" type="hidden" value="<?php echo $me->username; ?>">
          <textarea placeholder="type message here" id="message" class="w3-padding w3-theme-l3" name="m_body" required></textarea>
          <button type="submit" id="submit" class="w3-theme-dark"><span>Send</span><i class="fa fa-send"></i></button>
        </form>
      </div>
  </div>
</div>
<div id="output">
</div>
<script>
  (function(){
    document.getElementsByTagName('TEXTAREA')[0].value = "";
    document.getElementById('message').focus();
    document.getElementById('message').onkeyup = function (event) {
      if (event.keyCode == 13) {
        document.getElementById('submit'). click();
      }
    }
  })();
</script>
</body>
</html>
