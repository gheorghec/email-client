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
  $id = $_GET['id'];
  $sms_inbox = $_GET['sms_inbox'];
  $sms_trash = $_GET['sms_trash'];
    $selected_sms = $inbox_obj -> select_sms_inbox($sms_inbox,$id);
        while($row = mysql_fetch_array ($selected_sms)){
          $sms_id = $row['sms_id'];
          $sms_from = $row['sms_from'];
          $text_sms = $row['text_sms'];
        }
      $inbox_obj -> update_sms_status($sms_inbox,$sms_id);
?>
          
<form id="message_form" action="sms_body.php" target="<?php echo $sms_inbox;?>" method="get">
            <input class="disable" type="text" name="id" value="<?php echo $id;?>">
            <input class="disable" type="text" name="sms_inbox" value="<?php echo $sms_inbox;?>">
            <input class="disable" type="text" name="sms_trash" value="<?php echo $sms_trash;?>">
          </form>
          <section class="header-section">
            <div class="email_details">
              <p>from:<span><?php echo $sms_from; ?></span></p>
            </div>
            <div class="email_actions">
              <button type="submit" form="message_form" name="action" value="reply" ><img src="../css/icons/1465762430_mail-reply-sender.png"> 
              <span>reply</span></button>
              <button onclick="delete_sms(this);"  type="submit" form="message_form" name="action" value="delete" 
                data-id="<?php echo $id; ?>"
                data-target="<?php echo $sms_inbox; ?>"
                ><img src="../css/icons/1465134159_trash.png"> 
              <span>delete</span></button>
            </div>
          </section>
  <section class="message-section body-bg">

<?php
switch ($action) {
    case 'show_sms':

      echo "<div class=\"message-content\">";
            echo $text_sms;
            echo "</div>";
        break;

    case 'reply':
          ?>
        <div class="msg_compose">
          <div class="send_to">
          <p>Reply To:</p>
            <?php
              echo "<p class=\"email_to\">".$sms_from."</p>";
            ?>
          </div>
          <div class="send_text">
            <form method="post" id="compose_form">
              <textarea name="text_sms" rows="10" cols="50" placeholder="Reply"></textarea>
            </form>
          </div>
          <div class="send_buton">
            <button form="compose_form" type="submit" name="submit">Send</button>
          </div>
        </div>
          <?php
          if(isset($_POST['submit']) && ($_POST['text_sms'])){
                $name_file = chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90));
                $sms_text = $_POST['text_sms'];
                $to = $sms_from;
                $phone_to = str_replace("+", "", $to);

                $dir    = '/var/spool/sms/outgoing';
                $content = "To: {$phone_to}
{$sms_text}";  
                $fp = fopen($dir. "/send_{$name_file}","wb");
                fwrite($fp,$content);
                fclose($fp);
                $inbox_obj -> insert_sms_sent ($phone_to, $sms_text);

              }

          

        break;


     case 'delete':
          $inbox_obj -> move_sms_trash ($sms_trash, $sms_inbox, $id);
          $inbox_obj -> delete_sms($sms_inbox,$id);

        break;
  

    
  }

}

?>
</section>
<script type="text/javascript">

function delete_sms(content){
  var id = content.getAttribute("data-id");
  var target = content.getAttribute("data-target");
    parent.deleteFunc(target, id);
}

</script>
  </body>
</html>