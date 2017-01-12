<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
     <title>Control Panel</title>
    <!-- Bootstrap -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
     <link href="../css/style.css" rel="stylesheet">
     <script src="../js/jquery.js"></script>
      <script src="../js/jquery-ui.js"></script>
      <script src="../js/jquery.cookie.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
<body>
<?php
require_once '../lib/main-class.php';
$inbox_obj = new DB_query();
if(isset($_GET['action'])){

  $action = $_GET['action'];

  $uid = $_GET['uid'];

  $email_inbox = $_GET['email_inbox'];

  $inbox_obj -> update_email_status($email_inbox, $uid);
  $showMess = $inbox_obj -> select_email_byUid($email_inbox,$uid);
    while($row = mysql_fetch_array($showMess)){
      $email_from = $row['email_from'];
      $email_date = $row['email_date'];
      $subject = $row['subject'];
      $message = $row['message'];
      $status = $row['status'];
      $email_only = $row['email_only'];
    }

    $selected_email = $inbox_obj -> select_email_action($email_inbox);
          while($row = mysql_fetch_array ($selected_email)){
            $smtp = $row['smtp'];
            $sent = $row['sent'];
            $hostname = $row['hostname'];
            $username = $row['email'];
            $password = $row['password'];
            $trash = $row['trash'];
            $full_name = $row['full_name'];
          }
?>
          <form id="message_form" action="message_body.php" target="<?php echo $email_inbox;?>" method="get">
            <input class="disable" type="text" name="uid" value="<?php echo $uid;?>">
            <input class="disable" type="text" name="email_inbox" value="<?php echo $email_inbox;?>">
          </form>
          <section class="header-section">
            <div class="email_details">
              <p>from:<span><?php echo $email_from; ?></span></p>
              <p>subject:<span><b><?php echo $subject; ?></b></span></p>
              <p>date:<span><?php echo $email_date; ?></span></p>
            </div>
            <div class="email_actions">
              <button type="submit" form="message_form" name="action" value="reply" ><img src="../css/icons/1465762430_mail-reply-sender.png"> 
              <span>reply</span></button>
              <button onclick="delete_msg(this);"  type="submit" form="message_form" name="action" value="delete" 
                data-uid="<?php echo $uid; ?>"
                data-target="<?php echo $email_inbox; ?>"
                ><img src="../css/icons/1465134159_trash.png"> 
              <span>delete</span></button>
            </div>
          </section>

  <section class="message-section body-bg">

<?php
switch ($action) {
    case 'show_msg':
            echo "<div class=\"message-content\">";
            echo $message;
            echo "</div>";
        break;
    case 'delete':

      /*$mbox = imap_open($hostname,$username,$password) or die('Cannot connect to server: ' . imap_last_error());
      imap_delete($mbox, $uid, FT_UID);
      imap_expunge($mbox);
      imap_close($mbox);*/
      
        $inbox_obj -> move_row_trash($trash,$email_inbox, $uid);
        $inbox_obj -> delete_row($email_inbox, $uid);
        die();
        break;


    case 'reply':
        ?>
        <div class="msg_compose">
          <div class="send_to">
          <p>Reply To:</p>
            <?php
              echo "<p class=\"email_to\">".$email_only."</p>";
            ?>
          </div>
          <div class="send_text">
            <form method="post" id="compose_form">
              <textarea name="text_msg" rows="10" cols="50" placeholder="Reply"></textarea>
            </form>
          </div>
          <div class="send_buton">
            <button form="compose_form" type="submit" name="submit">Send</button>
          </div>
        </div>
        <?php
          if(isset($_POST['submit']) && ($_POST['text_msg'])){

              require_once '../PHPMailer/PHPMailerAutoload.php';

              $mail = new PHPMailer;

              $mail->isSMTP();                            // Set mailer to use SMTP
              $mail->Host = $smtp;             // Specify main and backup SMTP servers
              $mail->SMTPAuth = true;                     // Enable SMTP authentication
              $mail->Username = $username;          // SMTP username
              $mail->Password = $password; // SMTP password
              $mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
              $mail->Port = 587;                       // TCP port to connect to

                $mail->setFrom($username, $full_name);

                $mail->addAddress($email_only);

                $mail->isHTML(true);  // Set email format to HTML

                $bodyContent = $_POST['text_msg'];

                $mail->Subject = $full_name;
                $mail->Body    = $bodyContent;

              if(!$mail->send()) {
                  echo "<h6 class=\"error\">Messages could not be sent.<h6>";
                  echo "<h6 class=\"error\">Mailer Error:<h6>" . $mail->ErrorInfo;
                } else {
                  $inbox_obj -> insert_msg_sent($sent,$uid,$email_only,$email_date,$full_name,$bodyContent);
                  echo "<h6 class=\"succes\">Messages has been sent.<h6>";
                }

          }
          
        break;

    
  }
}

?>
</section>
<script type="text/javascript">

function delete_msg(content){
  var uid = content.getAttribute("data-uid");
  var target = content.getAttribute("data-target");
    parent.deleteFunc(target, uid);
}

</script>
  </body>
</html>