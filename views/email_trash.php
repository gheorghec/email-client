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
if(isset($_GET['email_id']) && isset($_GET['loadTrash']))
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
                $emails_sent = $row['sent'];
            }
	$inbox_result  = $inbox_obj -> select_emails($emails_trash);
        while($row = mysql_fetch_array ($inbox_result))
        {
          //echo $row['uid'].'<br>';
          //echo $row['seen'].'<br>';
          //echo $row['email_from'].'<br>';
          //echo $row['email_date'].'<br>';
          //echo $row['subject'].'<br>';
          //echo $row['message'].'<br><hr />';
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
                    
              <form class=\"email-heading {$row['status']}\" target=\"{$emails_trash}\" action=\"message_body.php\" method=\"get\">
              <button type=\"submit\" class=\"email-link\" name=\"action\" value=\"show_msg\">
                    <input class=\"disable\" type=\"text\" name=\"uid\" value=\"{$row['uid']}\">
                    <input class=\"disable\" type=\"text\" name=\"email_inbox\" value=\"{$emails_trash}\">
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
