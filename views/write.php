<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
     <title>Control Panel</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../css/font-awesome/css/font-awesome.min.css">
     <link href="../css/style.css" rel="stylesheet">
     <link href="../css/jquery-ui.css" rel="stylesheet">
    <script src="../js/jquery.js"></script>
    <script src="../js/jquery-ui.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
<body>
<div class="compose_header">
  <a href="write.php?type=email"><img src="../css/icons/1463966233_email.png"><p>Write e-mail message</p></a>
  <a href="write.php?type=sms"><img src="../css/icons/1463845368_message.png"><p>Write SMS message</p></a>
</div>
<?php
require_once '../lib/main-class.php';
$contact_obj = new DB_query();
  if(isset($_GET['type'])){
    $type = $_GET['type'];
    switch ($type) {
    case 'email':
      ?>
      <div class="compose">
      <div class="send_to">
          <label for="select">From: </label>
          <select id="select" name="email_from" form="compose_form">
            <?php
              $email_from = $contact_obj -> select_emails_acc();
                while($row = mysql_fetch_array($email_from)){
                  echo "<option  value=\"{$row['email']}\">{$row['email']}</option>";
                }
            ?>
          </select>

          <label for="tags">To: </label>
          <input id="tags" name="email_to" form="compose_form">
      </div>
      <div class="send_text">
        <textarea type="text" name="subject" rows="1" cols="100" form="compose_form" placeholder="Subject"></textarea>
      </div>
      <div class="send_text">
        <form method="post" id="compose_form" name="compose_form">
          <textarea name="text_msg" rows="20" cols="100" placeholder="Message"></textarea>
        </form>
      </div>
      <div class="send_buton">
        <button form="compose_form" type="submit" name="submit">Send</button>
      </div>
    </div>
      <?php
          if(isset($_POST['submit']) && ($_POST['text_msg']) && ($_POST['email_from']) && ($_POST['email_to']) && ($_POST['subject'])){
                $email_from =  $_POST['email_from'];
                $email_to = $_POST['email_to'];
                $subject = $_POST['subject'];
                require_once '../PHPMailer/PHPMailerAutoload.php';
                  $email_data = $contact_obj -> select_emails_byemail($email_from);
                    while($row = mysql_fetch_array($email_data)){
                      $smtp = $row['smtp'];
                      $sent = $row['sent'];
                      $hostname = $row['hostname'];
                      $username = $row['email'];
                      $password = $row['password'];
                      $full_name = $row['full_name'];
                    }

                  $mail = new PHPMailer;

                  $mail->isSMTP();                            // Set mailer to use SMTP
                  $mail->Host = $smtp;             // Specify main and backup SMTP servers
                  $mail->SMTPAuth = true;                     // Enable SMTP authentication
                  $mail->Username = $username;          // SMTP username
                  $mail->Password = $password; // SMTP password
                  $mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
                  $mail->Port = 587;

                  $mail->setFrom($username, $full_name);
            
                  $mail->addAddress($email_to);

                  $mail->isHTML(true);  // Set email format to HTML

                  $bodyContent = $_POST['text_msg'];

                  $mail->Subject = $subject;
                  $mail->Body    = $bodyContent;
                  $contact_obj -> insert_msg_sent($sent,' ',$email_to,$email_date,$full_name,$bodyContent);

                    if(!$mail->send()) {
                              echo "<h6 class=\"error\">Messages could not be sent.<h6>";
                              echo "<h6 class=\"error\">Mailer Error:<h6>" . $mail->ErrorInfo;
                          } else {
                              echo "<h6 class=\"succes\">Messages has been sent.<h6>";
                          }
      
          }
        break;
    case 'sms':
            ?>
              <div class="compose">
                <div class="send_to">
                    <label for="phone">To: </label>
                    <input id="phone" type="tel" name="phone_to" form="compose_form">
                </div>
                <div class="send_text">
                  <form method="post" id="compose_form" name="compose_form">
                    <textarea name="text_msg" rows="20" cols="100" placeholder="Message"></textarea>
                  </form>
                </div>
                <div class="send_buton">
                  <button form="compose_form" type="submit" name="submit">Send</button>
                </div>
              </div>
            <?php
              if(isset($_POST['submit']) && ($_POST['text_msg']) && ($_POST['phone_to'])){
                $name_file = chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90));
                $sms_text = $_POST['text_msg'];
                $to = $_POST['phone_to'];
                $phone_to = str_replace("+", "", $to);

                $dir    = '/var/spool/sms/outgoing';
                $content = "To: {$phone_to}

{$sms_text}";  
                $fp = fopen($dir. "/send_{$name_file}","wb");
                fwrite($fp,$content);
                fclose($fp);
                $contact_obj -> insert_sms_sent ($phone_to, $sms_text);

              }
        break;
    }
  }
?>

<script type="text/javascript">
window.onload = function() {
    parent.iframeLoaded();
}

$('.email-heading').click(function(e){
    $('.email-heading').removeClass('active');
    $(this).addClass('active');
    $(this).removeClass('unseen').addClass('seen');
});

$(document).ready(function() {
$( "#delete_button" ).click(function() {
        $( "input:checked.check_box" ).closest('.msg').hide();
      });



});
  
function mark(content){
  var dataMark = content.getAttribute("data-mark");
  if ( $( "input[data-mark="+dataMark+"]" ).is( ":checked" ) ){
    $("input[data-mark="+dataMark+"]").prop( "checked", false );
  }else{
    $("input[data-mark="+dataMark+"]").prop( "checked", true );
  }
  
}
function replyAll(content){
  var dataframe = content.getAttribute("data-frame");
  $("#action_form").prop("target", dataframe);
}
function deleteAll(){
  $("#action_form").prop("target", 'action_frame');
}



  $(function() {
    var availableTags = [
      <?php 
      $tags = $contact_obj -> select_contact();
        while($row = mysql_fetch_array($tags)){
          echo "\"{$row['email']}\",";
        }


      ?>
    ];
    $( "#tags" ).autocomplete({
      source: availableTags
    });
  });

  $(function() {
    var availablePhone = [
      <?php 
      $tags = $contact_obj -> select_contact();
        while($row = mysql_fetch_array($tags)){
          echo "\"{$row['phone']}\",";
        }


      ?>
    ];
    $( "#phone" ).autocomplete({
      source: availablePhone
    });
  });
</script>

  </body>
</html>
