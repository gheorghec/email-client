
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
if(isset($_GET['email_id']) && isset($_GET['loadInbox']))
  {
    $email_id = mysql_real_escape_string($_GET['email_id']); //intval

$selectedEmail = $inbox_obj -> selectedEmail($email_id);
    while($row = mysql_fetch_array ($selectedEmail))
            {
                $hostname = $row['hostname'];
                $username = $row['email'];
                $password = $row['password'];
                $emails_inbox = $row['inbox'];
                $emails_trash = $row['trash'];
            }
?>
      <div class="fixed_menu">
        <form  id="action_form"  action="../action_message.php" method="get">
            <button onclick = "deleteAll();" form="action_form"  id="delete_button" type="submit" name="action" value="delete" ><img src="../css/icons/1465134159_trash.png"> 
              <span>delete all</span></button>
            <button  form="action_form" id="replyall_button" type="submit" name="action" value="replyall" 
            onclick="replyAll(this);" data-frame="<?php echo $emails_inbox;?>"
            ><img src="../css/icons/1465762420_mail-reply-all.png"> 
            <span>reply all</span></button>
        </form>
      </div>
        <form  id="mark_form" target="mark_frame" action="../mark_message.php" method="get">
        </form>

<section class="mails-section">
  <input class="disable" form="action_form" type="text" name="email_inbox" value="<?php echo $emails_inbox;?>">
  <input class="disable" form="mark_form" type="text" name="email_inbox" value="<?php echo $emails_inbox;?>">
<?php

/*$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to server: ' . imap_last_error());

$emails = imap_search($inbox,'ALL');

if($emails) {

    foreach($emails as $key => $email_number) {
        $overview = imap_fetch_overview($inbox,$email_number,0);
        $structure = imap_fetchstructure($inbox, $email_number);

        if(isset($structure->parts) && is_array($structure->parts) && isset($structure->parts[1])) {
            $part = $structure->parts[1];
            $message = imap_fetchbody($inbox,$email_number,2);

            if($part->encoding == 3) {
                $message = imap_base64($message);
            } else if($part->encoding == 1) {
                $message = imap_8bit($message);
            } else {
                $message = imap_qprint($message);
            }
        }
        $email_uid = utf8_decode(imap_utf8($overview[0]->uid));
        $email_msgno = utf8_decode(imap_utf8($overview[0]->msgno));
        $email_seen = ($overview[0]->seen ? 'read' : 'unread');
        $stremail_from = utf8_decode(imap_utf8($overview[0]->from));
        $email_date = utf8_decode(imap_utf8($overview[0]->date));
        $email_subject = utf8_decode(imap_utf8($overview[0]->subject));

            $patterns = array();
            $patterns[0] = '/>/';
            $patterns[1] = '/</';
            $replacements = array();
            $replacements[2] = '';
            $replacements[1] = '';
            $email_from = preg_replace($patterns, $replacements, $stremail_from);
              $pattern = '/([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])' .
              '(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)/i';
              preg_match ($pattern, $stremail_from, $email_only);

          $msgno = $inbox_obj -> select_msgno($emails_inbox);
            $arrMsgno = array();
          while($row = mysql_fetch_array ($msgno))
            {
                array_push($arrMsgno, $row['uid']);
            }

        if (!in_array($email_uid, $arrMsgno)) {
          $inbox_obj -> insert_emails($emails_inbox,$email_uid, $email_seen, $email_from, $email_date, $email_subject, $message, 'unseen', $email_only[0],'', $email_msgno);
        }           
    }
}
imap_close($inbox);*/
$inbox_result  = $inbox_obj -> select_emails($emails_inbox);
        while($row = mysql_fetch_array ($inbox_result))
        {
          echo "
          <div class=\"msg\" id=\"{$row['uid']}\">
              <button 
                name=\"action\" 
                value=\"mark_message\"
                class =\"style_none\"
                onclick=\"mark(this);\" 
                data-mark=\"{$row['uid']}\" 
                type=\"submit\" 
                form=\"mark_form\">

                <input form=\"mark_form\"  
                  id=\"{$row['uid']}\" 
                  data-mark=\"{$row['uid']}\"  
                  class=\"icon-checkbox\" 
                  type=\"checkbox\" 
                  name=\"uid\" 
                  value=\"{$row['uid']}\" 
                  {$row['mark']}/>
                  <label for=\"{$row['uid']}\">
                    <i class=\"fa fa-star \" aria-hidden=\"true\" ></i>
                  </label>
              </button>
              <input class=\"check_box\" form=\"action_form\" type=\"checkbox\" name=\"uid\" value=\"{$row['uid']}\">
                    
              <form class=\"email-heading {$row['status']}\" target=\"{$emails_inbox}\" action=\"message_body.php\" method=\"get\">
              <button type=\"submit\" class=\"email-link\" name=\"action\" value=\"show_msg\">
                    <input class=\"disable\" type=\"text\" name=\"uid\" value=\"{$row['uid']}\">
                    <input class=\"disable\" type=\"text\" name=\"email_inbox\" value=\"{$emails_inbox}\">
                    <input class=\"email_from\" type=\"text\" name=\"email_from\" placeholder=\"{$row['email_from']}\" disabled>
                    <input class=\"subject\" type=\"text\" name=\"subject\" placeholder=\"{$row['subject']}\" disabled>
                    <input class=\"email_date\" type=\"text\" name=\"email_date\" placeholder=\"{$row['email_date']}\" disabled>
              </button>
            </form>
            </div>
          ";

        }

}
?>

</section>


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
</script>

  </body>
</html>

